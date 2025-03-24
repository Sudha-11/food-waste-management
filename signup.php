<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connection.php';

$msg = ""; // To store error messages

if (isset($_POST['sign'])) {
    $username = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $gender = mysqli_real_escape_string($connection, $_POST['gender']);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check_sql = "SELECT * FROM login WHERE email='$email'";
    $check_result = mysqli_query($connection, $check_sql);

    if ($check_result && mysqli_num_rows($check_result) > 0) {
        $msg = "<script>alert('Account already exists');</script>";
    } else {
        // Insert new user
        $insert_sql = "INSERT INTO login (name, email, password, gender) 
                       VALUES ('$username', '$email', '$hashed_password', '$gender')";

        if (mysqli_query($connection, $insert_sql)) {
            echo "<script>alert('Account created successfully'); window.location.href='signin.php';</script>";
            exit();
        } else {
            $msg = "<script>alert('Error: Unable to create account');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body>

    <div class="container">
        <div class="regform">
            <form action="" method="post">
                <p class="logo">Food <b style="color: #06C167;">Donate</b></p>
                <p id="heading">Create your account</p>

                <?php echo $msg; ?> <!-- Display messages -->

                <div class="input">
                    <label for="name">User Name</label>
                    <input type="text" id="name" name="name" required/>
                </div>
                
                <div class="input">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required/>
                </div>

                <div class="input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required/>
                </div>

                <div class="radio">
                    <input type="radio" name="gender" id="male" value="male" required/>
                    <label for="male">Male</label>
                    <input type="radio" name="gender" id="female" value="female">
                    <label for="female">Female</label>
                </div>

                <div class="btn">
                    <button type="submit" name="sign">Continue</button>
                </div>

                <div class="signin-up">
                    <p>Already have an account? <a href="signin.php">Sign in</a></p>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
