<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Blog Az</title>
    <link rel="stylesheet" href="./staticts/login.css">
</head>
<body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include "db.php";

    $username = trim($_POST["username"]);
    $errors = [];

    if (empty($username)) {
        $errors[] = "Username is required.";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $token = bin2hex(random_bytes(20));
            $expires = time() + 1800; // Token expires in 30 minutes

            $sql = "UPDATE users SET token = ?, expires = ? WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sis", $token, $expires, $username);
            $stmt->execute();

            $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/reset_password.php?token=" . $token;

        } else {
            $errors[] = "No account found with that username.";
        }

        $stmt->close();
        $conn->close();
    }

}
?>

<div class="container">
    <h1>Forgot Password</h1>
    <form action="forgot_pass.php" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php if(array_key_exists('username', $_GET)) echo $_GET['username'];?>" required>
        <button type="submit">Reset</button>
    </form>
    <?php
        if (!empty($errors)) {
            
            foreach ($errors as $error) {
                echo "<p style='color: red;'>$error</p>";
            }
        }
        else
       {
        echo"<p><a href='$resetLink'>$resetLink</a></p>";
       }
       ?>
    <p>Back to login page? <a href="login.php">Login Here</a></p>
    <br>
    
</div>


</body>
</html>
