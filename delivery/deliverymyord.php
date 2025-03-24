<?php
session_start();
include '../connection.php'; // Ensure this file has the correct database connection

// Redirect if not logged in
if (!isset($_SESSION['Did']) || empty($_SESSION['Did'])) {
    header("location: deliverylogin.php");
    exit();
}

$delivery_id = $_SESSION['Did']; // Get delivery person ID

// Handle status update
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];

    $update_query = "UPDATE food_donations SET delivery_status = '$new_status' WHERE Fid = '$order_id' AND assigned_to = '$delivery_id'";
    mysqli_query($connection, $update_query);

    echo "<script>alert('Order status updated successfully!'); window.location.href='deliverymyord.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="delivery.css">
    <link rel="stylesheet" href="../home.css">
</head>
<body>

<header>
    <div class="logo">Food <b style="color: #06C167;">Donate</b></div>
    <nav class="nav-bar">
        <ul>
            <li><a href="delivery.php">Home</a></li>
            <li><a href="openmap.php">Map</a></li>
            <li><a href="deliverymyord.php" class="active">My Orders</a></li>
        </ul>
    </nav>
</header>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
    <h3>Your Assigned Orders</h3>

    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Name</th>
                        <th>Food</th>
                        <th>Category</th>
                        <th>Phone No</th>
                        <th>Date/Time</th>
                        <th>Pickup Address</th>
                        <th>Delivery Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch orders assigned to this delivery person
                    $query = "SELECT Fid, name, food, category, phoneno, date, 
                                     address AS pickup_address, delivery_address, delivery_status
                              FROM food_donations
                              WHERE assigned_to = '$delivery_id'";

                    $result = mysqli_query($connection, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                <td>{$row['Fid']}</td>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['food']) . "</td>
                                <td>" . htmlspecialchars($row['category']) . "</td>
                                <td>" . htmlspecialchars($row['phoneno']) . "</td>
                                <td>" . htmlspecialchars($row['date']) . "</td>
                                <td>" . htmlspecialchars($row['pickup_address']) . "</td>
                                <td>" . htmlspecialchars($row['delivery_address']) . "</td>
                                <td>";

                            // Show current status
                            if ($row['delivery_status'] === 'Completed') {
                                echo "<span style='color: green;'>✅ Completed</span>";
                            } else {
                                echo "<span style='color: orange;'>🟡 Pending</span>";
                            }

                            echo "</td>
                                <td>";
                            
                            // Show status update option only if not completed
                            if ($row['delivery_status'] !== 'Completed') {
                                echo "<form method='post' onsubmit='return confirm(\"Mark this order as completed?\");'>
                                    <input type='hidden' name='order_id' value='{$row['Fid']}'>
                                    <input type='hidden' name='new_status' value='Completed'>
                                    <button type='submit' name='update_status'>✅ Mark as Completed</button>
                                    </form>";
                            } else {
                                echo "-"; // No action available if already completed
                            }

                            echo "</td></tr>";
                        }
                    } else {
                        echo "<tr><td colspan='10' style='text-align: center;'>No orders assigned</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
