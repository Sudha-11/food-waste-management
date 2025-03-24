<?php
session_start();
include '../connection.php';

// Redirect if not logged in
if (!isset($_SESSION['Aid']) || empty($_SESSION['Aid'])) {
    header("location: signin.php");
    exit();
}

$admin_id = $_SESSION['Aid']; 

// Fetch admin details
$query_admin = "SELECT name, email, location, address FROM admin WHERE Aid = '$admin_id'";
$result_admin = mysqli_query($connection, $query_admin);
$admin = mysqli_fetch_assoc($result_admin);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="admin.css">
    <title>Admin Profile</title>
    <style>
        .admin-details {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .admin-details h2 {
            margin-bottom: 20px;
            color: #06C167;
        }
        .admin-details p {
            font-size: 18px;
            margin: 10px 0;
        }
        .activity {
            margin-top: 50px;
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
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="assign_delivery.php">Assign Delivery</a></li>
                <li><a href="adminprofile.php" class="active">Profile</a></li> <!-- Marked Profile as active -->
            </ul>
            <ul class="logout-mode">
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <p class="logo">Admin <b style="color: #06C167;">Profile</b></p>
        </div>

        <div class="admin-details">
            <h2>Admin Details</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($admin['name']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($admin['email']); ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($admin['location']); ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($admin['address']); ?></p>
        </div>

        <div class="activity">
            <h2>Donation History</h2>
            <div class="table-container">
                <div class="table-wrapper">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Food</th>
                                <th>Category</th>
                                <th>Phone No</th>
                                <th>Date/Time</th>
                                <th>Pickup Address</th>
                                <th>Delivery Address</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch donation history for this admin
                            $query = "SELECT fd.*, dp.name AS delivery_person, fd.delivery_address 
                                      FROM food_donations fd 
                                      LEFT JOIN delivery_persons dp ON fd.delivery_by = dp.Did 
                                      WHERE fd.assigned_to = '$admin_id'";
                            
                            $result = mysqli_query($connection, $query);

                            if (!$result) {
                                die("Error fetching donation history: " . mysqli_error($connection));
                            }

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                        <td data-label='Name'>{$row['name']}</td>
                                        <td data-label='Food'>{$row['food']}</td>
                                        <td data-label='Category'>{$row['category']}</td>
                                        <td data-label='Phone No'>{$row['phoneno']}</td>
                                        <td data-label='Date/Time'>{$row['date']}</td>
                                        <td data-label='Pickup Address'>{$row['address']}</td>
                                        <td data-label='Delivery Address'>" . (!empty($row['delivery_address']) ? $row['delivery_address'] : "Not assigned yet") . "</td>
                                        <td data-label='Quantity'>{$row['quantity']}</td>
                                    </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' style='text-align: center;'>No history available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script src="admin.js"></script>
</body>
</html>
