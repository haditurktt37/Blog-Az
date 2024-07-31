<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Blog Az</title>
    <link rel="stylesheet" href="./staticts/login.css">
</head>
<body>
<?php

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include "db.php";

    $token = $_POST["token"];
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $errors = [];

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if (empty($confirm_password)) {
        $errors[] = "Please confirm your password.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (empty($errors)) {
        $sql = "SELECT * FROM users WHERE token = ? AND expires > ?";
        $stmt = $conn->prepare($sql);
        $current_time = time();
        $stmt->bind_param("si", $token, $current_time);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET password = ?, token = NULL, expires = NULL WHERE token = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $hashed_password, $token);
            $stmt->execute();

            header('Location: ./login.php?msg=Password has been successfully reset.');
            exit();
        } else {
            $errors[] = "Invalid or expired token.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<div class="container">
    <h1>Reset Password</h1>
    <form action="reset_password.php" method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <button type="submit">Reset Password</button>
    </form>
    <?php
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo "<p style='color: red;'>$error</p>";
            }
        }
    ?>
    <p>Remembered your password? <a href="login.php">Login Here</a></p>
</div>
</body>
</html>
