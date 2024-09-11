<?php
include('db.php');
session_start();

$username = $_SESSION['username']; // Username from session

// Get user info from database
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit();
}

$profile_picture = !empty($user['profile_picture']) ? $user['profile_picture'] : 'img.png';
$_SESSION['profile_picture'] = $profile_picture;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Settings</title>
    <link rel="stylesheet" href="./staticts/panel.css">
    <link rel="stylesheet" href="./staticts/settings.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="wrapper">
    <header>
        <div class="container">
            <h1>My Panel</h1>
            <nav>
                <ul>
                    <li><a href="panel.php">Home</a></li>
                    <li><a href="#">Posts</a></li>
                    <li><a href="#">Write</a></li>
                    <li><a href="settings.php">Settings</a></li>
                    <li><a href="logout.php">Logout</a></li>
                    <li><?php echo "<b>".htmlspecialchars($_SESSION['username'])."</b>"; ?></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>User Settings</h2>
            <form action="submit-settings.php" method="post" enctype="multipart/form-data">
                <div class="profile-picture">
                    <img id="profile-pic-preview" src="/images/users/profile/<?php echo $profile_picture; ?>" alt="Profile Picture">
                    <div>
                        <label for="profile-picture">Change Profile Picture</label>
                        <input type="file" id="profile-picture" name="profile_picture">
                    </div>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password">
                </div>

                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="first_name" value="<?php echo $user['first_name']; ?>">
                </div>

                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="last_name" value="<?php echo $user['last_name']; ?>">
                </div>

                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="4"><?php echo $user['bio']; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="email">Email (Not Changeable)</label>
                    <input type="email" id="email" value="<?php echo $user['email']; ?>" disabled>
                </div>

                <div class="form-group">
                    <button type="submit">Update</button>
                </div>
            </form>
        </div>
    </main>
</div>

    <script>
        $('#profile-picture').on('change', function() {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#profile-pic-preview').attr('src', e.target.result);
            };
            reader.readAsDataURL(this.files[0]);
        });
    </script>

</body>
</html>
