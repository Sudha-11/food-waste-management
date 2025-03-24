<?php
session_start();
include '../connection.php'; // Ensure database connection is correct

// Redirect if the admin is not logged in
if (!isset($_SESSION['Aid'])) {
    header("location: signin.php");
    exit();
}

$admin_id = $_SESSION['Aid'];

// Fetch newly arrived food donations (orders that are not assigned yet)
$new_orders_query = "SELECT * FROM food_donations WHERE assigned_to IS NULL ORDER BY Fid DESC";
$new_orders_result = mysqli_query($connection, $new_orders_query);

// Fetch assigned orders with delivery persons' names
$assigned_orders_query = "SELECT fd.*, dp.name AS delivery_person
                          FROM food_donations fd
                          LEFT JOIN delivery_persons dp ON fd.delivery_by = dp.Did
                          WHERE fd.assigned_to IS NOT NULL";
$assigned_orders_result = mysqli_query($connection, $assigned_orders_query);

// Fetch available delivery persons
$delivery_query = "SELECT * FROM delivery_persons ORDER BY name";
$delivery_result = mysqli_query($connection, $delivery_query);

// Handle order assignment
if (isset($_POST['assign_delivery'])) {
    $order_id = mysqli_real_escape_string($connection, $_POST['order_id']);
    $delivery_person_id = mysqli_real_escape_string($connection, $_POST['delivery_person_id']);
    $delivery_address = mysqli_real_escape_string($connection, $_POST['delivery_address']);

    // Update the database with assigned delivery person and address
    $update_query = "UPDATE food_donations 
                     SET assigned_to = '$delivery_person_id', 
                         delivery_by = '$delivery_person_id',  -- ✅ Ensure order appears in delivery person orders
                         delivery_address = '$delivery_address' 
                     WHERE Fid = '$order_id'";
    $update_result = mysqli_query($connection, $update_query);

    if ($update_result) {
        echo "<script>alert('Order assigned successfully!'); window.location.href='assign_delivery.php';</script>";
    } else {
        echo "<script>alert('Error assigning order.'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Delivery</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <nav>
        <div class="logo-name">
            <span class="logo_name">ADMIN</span>
        </div>
        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="assign_delivery.php" class="active">Assign Delivery</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
            <ul class="logout-mode">
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="table-container">
            <h2>📌 Newly Arrived Orders</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Name</th>
                        <th>Food</th>
                        <th>Category</th>
                        <th>Phone No</th>
                        <th>Pickup Address</th>
                        <th>Delivery Address</th>
                        <th>Assign To</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($new_orders_result)) { ?>
                        <tr>
                            <td><?= $row['Fid'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['food']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><?= htmlspecialchars($row['phoneno']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="order_id" value="<?= $row['Fid'] ?>">
                                    <input type="text" name="delivery_address" placeholder="Enter Delivery Address" required>
                            </td>
                            <td>
                                <select name="delivery_person_id" required>
                                    <option value="">Select Delivery Person</option>
                                    <?php 
                                    mysqli_data_seek($delivery_result, 0);
                                    while ($d_row = mysqli_fetch_assoc($delivery_result)) { ?>
                                        <option value="<?= $d_row['Did'] ?>"><?= htmlspecialchars($d_row['name']) ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <button type="submit" name="assign_delivery">Assign</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="table-container">
            <h2>✅ Assigned Orders</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Name</th>
                        <th>Food</th>
                        <th>Category</th>
                        <th>Phone No</th>
                        <th>Pickup Address</th>
                        <th>Delivery Address</th>
                        <th>Assigned To</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($assigned_orders_result)) { ?>
                        <tr>
                            <td><?= $row['Fid'] ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['food']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><?= htmlspecialchars($row['phoneno']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($row['delivery_address']) ?></td>
                            <td><?= htmlspecialchars($row['delivery_person']) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>
