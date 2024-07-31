<?php
    $db_servername = "localhost";
    $db_username = "blog.az";
    $db_password = "p@ssw0rd";
    $db_name = "blog_db";

    $conn = new mysqli($db_servername, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed:. $conn->connect_error ");
    }
?>
