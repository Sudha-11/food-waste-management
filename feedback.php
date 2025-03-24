<?php
session_start();
include 'connection.php'; // Ensure database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['feedback'])) {
    // Validate input fields
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['message'])) {
        echo "<script>alert('All fields are required'); window.history.back();</script>";
        exit();
    }

    // Sanitize inputs to prevent SQL injection
    $name = mysqli_real_escape_string($connection, trim($_POST['name']));
    $email = mysqli_real_escape_string($connection, trim($_POST['email']));
    $message = mysqli_real_escape_string($connection, trim($_POST['message']));

    // Insert into the database
    $query = "INSERT INTO user_feedback (name, email, message) VALUES ('$name', '$email', '$message')";
    
    if (mysqli_query($connection, $query)) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href='contact.html';</script>";
        exit();
    } else {
        echo "<script>alert('Error: Unable to submit feedback'); window.history.back();</script>";
    }
}
?>
