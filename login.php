<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connection.php';

if (isset($_POST['sign'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = $_POST['password'];

    // Check if user exists
    $sql = "SELECT * FROM login WHERE email='$email'";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['gender'] = $row['gender'];

            header("Location: home.html");
            exit();
        } else {
            echo "<script>alert('Login Failed: Incorrect password');</script>";
        }
    } else {
        echo "<script>alert('Account does not exist');</script>";
    }
}
?>
