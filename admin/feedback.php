<?php
session_start();
include '../connection.php'; // Ensure this file correctly connects to the database

// Redirect if admin is not logged in
if (!isset($_SESSION['Aid'])) {
    header("location: signin.php");
    exit();
}

// Fetch feedback from user_feedback table
$feedback_query = "SELECT feedback_id, name, email, message FROM user_feedback ORDER BY feedback_id DESC";
$feedback_result = mysqli_query($connection, $feedback_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .table-container {
            width: 90%;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background: #06C167;
            color: white;
        }
        .profile-box {
            background: #f9f9f9;
            padding: 15px;
            margin: 10px;
            border-radius: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

    <nav>
        <div class="logo-name">
            <span class="logo_name">ADMIN</span>
        </div>
        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="analytics.php">Analytics</a></li>
                <li><a href="donate.php">Donations</a></li>
                <li><a href="feedback.php" class="active">Feedback</a></li>
                <li><a href="assign_delivery.php">Assign Delivery</a></li>
                <li><a href="profile.php">Profile</a></li> <!-- Profile section added -->
            </ul>
            <ul class="logout-mode">
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <p class="logo">User <b style="color: #06C167;">Feedback</b></p>
        </div>

        <div class="table-container">
            <h2>📢 User Feedback</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Feedback ID</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($feedback_result)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['feedback_id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

</body>
</html>
