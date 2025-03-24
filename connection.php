<?php
$connection = mysqli_connect("localhost", "root", "Renu123@", "demo");

// If your MySQL runs on port 3307, use this:
# $connection = mysqli_connect("localhost:3307", "root", "", "demo");

// Check if the connection failed
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>