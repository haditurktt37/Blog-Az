<?php
include('db.php');
session_start();
$old_username = $_SESSION['username']; // Username from session (old username)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form data
    $new_username = isset($_POST['username']) ? $_POST['username'] : $old_username; // New username from form
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $bio = isset($_POST['bio']) ? $_POST['bio'] : '';
    $profile_picture = isset($_FILES['profile_picture']['name']) ? $_FILES['profile_picture']['name'] : null;

    $target_dir = "images/users/profile/";

    // Profile picture upload handling
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $new_file_name = $new_username . "_" . time() . "." . $file_ext;
        $target_file = $target_dir . $new_file_name;

        $allowed_extensions = array("jpg", "jpeg", "png", "gif");

        // If file type is allowed
        if (in_array($file_ext, $allowed_extensions)) {

            // Delete old profile picture (if not default img.png)
            if (!empty($_SESSION['profile_picture']) && $_SESSION['profile_picture'] !== 'img.png') {
                $old_picture = $target_dir . $_SESSION['profile_picture'];
                if (file_exists($old_picture)) {
                    unlink($old_picture); // Delete old profile picture
                }
            }

            // Move uploaded file
            if (move_uploaded_file($file_tmp, $target_file)) {
                $profile_picture = $new_file_name;
                $_SESSION['profile_picture'] = $profile_picture; // Update session with new picture
            } else {
                echo "Error uploading the file.";
                exit();
            }
        } else {
            echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
            exit();
        }
    } else {
        $profile_picture = $_SESSION['profile_picture']; // If no new picture is uploaded, keep the current one
    }

    // Update user information
    if (!empty($password)) {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username = ?, first_name = ?, last_name = ?, password = ?, bio = ?, profile_picture = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $new_username, $first_name, $last_name, $password_hashed, $bio, $profile_picture, $old_username);
    } else {
        // If password is not provided, do not update it
        $sql = "UPDATE users SET username = ?, first_name = ?, last_name = ?, bio = ?, profile_picture = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $new_username, $first_name, $last_name, $bio, $profile_picture, $old_username);
    }

    if ($stmt->execute()) {
        // Update session with new username
        $_SESSION['username'] = $new_username;
        header("Location: settings.php?success=true");
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
