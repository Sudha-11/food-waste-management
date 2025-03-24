<?php
session_start();
include("../connection.php");

// Redirect if not logged in
if (!isset($_SESSION['Aid']) || empty($_SESSION['Aid'])) {
    header("location: signin.php");
    exit();
}

$admin_id = $_SESSION['Aid'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donations</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .table-container {
            width: 90%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #06C167;
            color: white;
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
                <li><a href="donate.php" class="active">Donations</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li><a href="assign_delivery.php">Assign Delivery</a></li>
                <li><a href="adminprofile.php">Profile</a></li>
            </ul>
            <ul class="logout-mode">
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <p class="logo">Food <b style="color: #06C167;">Donations</b></p>
        </div>

        <div class="table-container">
            <h2>Donations List</h2>
            <?php
            $query = "SELECT * FROM food_donations ORDER BY date DESC";
            $result = mysqli_query($connection, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table>";
                echo "<thead>
                        <tr>
                            <th>Name</th>
                            <th>Food</th>
                            <th>Category</th>
                            <th>Phone No</th>
                            <th>Date/Time</th>
                            <th>Address</th>
                            <th>Quantity</th>
                        </tr>
                      </thead>
                      <tbody>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['name']}</td>
                            <td>{$row['food']}</td>
                            <td>{$row['category']}</td>
                            <td>{$row['phoneno']}</td>
                            <td>{$row['date']}</td>
                            <td>{$row['address']}</td>
                            <td>{$row['quantity']}</td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p style='text-align:center; font-size:18px;'>No donations available.</p>";
            }
            ?>
        </div>
    </section>

    <script src="admin.js"></script>
</body>
</html>

<?php mysqli_close($connection); ?>
