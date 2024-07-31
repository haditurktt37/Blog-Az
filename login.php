<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Blog Az</title>
    <link rel="stylesheet" href="./staticts/login.css">
</head>
<body>

<?php
session_start();

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'db.php';

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['is_logged'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            header('Location: panel.php');
            exit();
        } else {
            $errorMessage = "Invalid username or password.";
            $username = $_POST['username'];
        }
    } else {
        $errorMessage = "Invalid username or password.";
        $username = $_POST['username'];
    }

    $stmt->close();
    $conn->close();
}
?>

<div class="container">
    <h1>Login to Blog Az</h1>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>
    <br>
    <?php 
        if ($errorMessage) {
            echo "<p style='color: red;'>".htmlspecialchars($errorMessage)."</p>";
        }
        if (array_key_exists('msg', $_GET)) {
            $msg = $_GET['msg'];
        }
        if (isset($msg)) {
            echo "<p style='color: green;'>".htmlspecialchars($msg)."</p>";
        }

        if (isset($username))
        {
            echo "<p>forgot password? <a href='forgot_pass.php?username=$username'>forgot password Here</a></p>";
        }
        else{
            echo "<p>forgot password? <a href='forgot_pass.php'>Forgot password Here</a></p>";
        }
    ?>
    <p>Don't have an account? <a href="register.php">Register Here</a></p>
</div>

</body>
</html>
