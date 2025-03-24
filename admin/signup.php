<?php
// ✅ Start session only if it's not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include '../connection.php'; // Ensure database connection

$msg = "";

// ✅ Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sign'])) {
    // ✅ Validate required fields
    if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['district']) || empty($_POST['address'])) {
        $msg = "<script>alert('All fields are required');</script>";
    } else {
        // ✅ Sanitize inputs
        $username = mysqli_real_escape_string($connection, trim($_POST['username']));
        $email = mysqli_real_escape_string($connection, trim($_POST['email']));
        $password = $_POST['password'];
        $location = mysqli_real_escape_string($connection, $_POST['district']);
        $address = mysqli_real_escape_string($connection, trim($_POST['address']));

        // ✅ Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // ✅ Check if the email is already registered
        $sql = "SELECT * FROM admin WHERE email = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $msg = "<script>alert('Account already exists!');</script>";
        } else {
            // ✅ Insert new user into the database
            $query = "INSERT INTO admin (name, email, password, location, address) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($connection, $query);
            mysqli_stmt_bind_param($stmt, "sssss", $username, $email, $hashed_password, $location, $address);
            $query_run = mysqli_stmt_execute($stmt);

            if ($query_run) {
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $username;

                echo "<script>alert('Account created successfully!'); window.location.href='signin.php';</script>";
                exit();
            } else {
                $msg = "<script>alert('Error: Data not saved');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="formstyle.css">
    <script src="signin.js" defer></script>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>Register</title>
</head>
<body>
    <div class="container">
        <?= $msg; ?>  <!-- Show messages here -->

        <form action="signup.php" method="post" id="form">
            <span class="title">Register</span>
            <br><br>

            <div class="input-group">
                <label for="username">Name</label>
                <input type="text" id="username" name="username" required/>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required/>
            </div>

            <label class="textlabel" for="password">Password</label> 
            <div class="password">
                <input type="password" name="password" id="password" required/>
                <i class="uil uil-eye-slash showHidePw" id="showpassword"></i>                
            </div>

            <div class="input-group">
                <label for="address">Address</label>
                <textarea id="address" name="address" required></textarea>
            </div>

            <div class="input-field">
    <label for="district">District</label>
    <select id="district" name="district" required>
        <option value="anantapur">Anantapur</option>
        <option value="chittoor">Chittoor</option>
        <option value="east_godavari">East Godavari</option>
        <option value="guntur">Guntur</option>
        <option value="kadapa">Kadapa</option>
        <option value="krishna">Krishna</option>
        <option value="kurnool">Kurnool</option>
        <option value="nellore">Nellore</option>
        <option value="prakasam">Prakasam</option>
        <option value="srikakulam">Srikakulam</option>
        <option value="visakhapatnam">Visakhapatnam</option>
        <option value="vizianagaram">Vizianagaram</option>
        <option value="west_godavari">West Godavari</option>
    </select> 
</div>


            <button type="submit" name="sign">Register</button>
            <div class="login-signup">
                <span class="text">Already a member?
                    <a href="signin.php" class="text login-link">Login Now</a>
                </span>
            </div>
        </form>
    </div>

    <script src="login.js"></script>
</body>
</html>
