<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Az | Your Blog Name</title>
    <link rel="stylesheet" href="./staticts/panel.css">
    <script src="./staticts/functions.js"></script>
</head>
<body>

<?php
    session_start();

    if (isset($_SESSION['is_logged']) && $_SESSION['is_logged'] === true) {
?>

<div class="wrapper">
    <header>
        <div class="container">
            <h1>My Panel</h1>
            <br><br>
            <nav>
                <ul>
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Posts</a></li>
                    <li><a href="#">Write</a></li>
                    <li><a href="logout.php">Logout</a></li>
                    <li><?php echo "<b>".htmlspecialchars($_SESSION['username'])."</b>"; ?></li>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
            <article>
                <h2>Blog Post Title</h2>
                <p class="date">July 30, 2024</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sit amet accumsan arcu. Sed a turpis arcu. Vivamus commodo arcu id libero ultricies, in tristique ipsum auctor.</p>
                <a href="#" class="read-more">Read More</a>
            </article>
            
            <article>
                <h2>Another Blog Post</h2>
                <p class="date">July 29, 2024</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque sit amet accumsan arcu. Sed a turpis arcu. Vivamus commodo arcu id libero ultricies, in tristique ipsum auctor.</p>
                <a href="#" class="read-more">Read More</a>
            </article>
        </div>
    </main>
    <footer>
        <div class="container">
            <p>&copy; 2024 Blog Az. All rights reserved.</p>
        </div>
    </footer>
</div>

<?php
    } else {
?>

<h1>Redirecting in 3 seconds...</h1>
<script>
    const url = "http://blog.az/login.php";
    setTimeout(function() {
        window.location.href = url;
    }, 3000);
</script>

<?php
    }
?>

</body>
</html>
