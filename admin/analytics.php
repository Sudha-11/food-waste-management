<?php
session_start();
include("../connection.php");

// Ensure the database connection is established
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Redirect if the user is not logged in
if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
    header("location: signin.php");
    exit();
}

// Fetch user gender count
$male_query = "SELECT COUNT(*) as count FROM login WHERE gender='male'";
$female_query = "SELECT COUNT(*) as count FROM login WHERE gender='female'";
$male_count = mysqli_fetch_assoc(mysqli_query($connection, $male_query))['count'];
$female_count = mysqli_fetch_assoc(mysqli_query($connection, $female_query))['count'];

// Fetch donation counts by location from food_donations table
$location_query = "SELECT address AS location, COUNT(*) as count FROM food_donations 
                   WHERE address IS NOT NULL AND address <> '' 
                   GROUP BY address 
                   ORDER BY COUNT(*) DESC";
$location_result = mysqli_query($connection, $location_query);

$locations = [];
$donation_counts = [];
while ($row = mysqli_fetch_assoc($location_result)) {
    $locations[] = $row['location'];
    $donation_counts[] = $row['count'];
}

// Encode for JavaScript
$locations_json = json_encode($locations);
$donation_counts_json = json_encode($donation_counts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
</head>
<body>
    <nav>
        <div class="logo-name">
            <span class="logo_name">ADMIN</span>
        </div>
        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="analytics.php" class="active">Analytics</a></li>
                <li><a href="donate.php">Donations</a></li>
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
        <div class="analytics-header">📊 Analytics Overview</div>

        <div class="chart-container">
            <h3>User Gender Distribution</h3>
            <canvas id="userChart" width="250" height="250"></canvas>
        </div>

        <div class="chart-container">
            <h3>Donations by Location</h3>
            <canvas id="donationChart"></canvas>
        </div>
    </section>

    <script>
        var userChart = new Chart(document.getElementById("userChart"), {
            type: "pie",
            data: {
                labels: ["Male", "Female"],
                datasets: [{
                    backgroundColor: ["#06C167", "blue"],
                    data: [<?php echo json_encode($male_count); ?>, <?php echo json_encode($female_count); ?>]
                }]
            },
            options: {
                title: { display: true, text: "User Gender Distribution" },
                responsive: true,
                maintainAspectRatio: false
            }
        });

        var donationChart = new Chart(document.getElementById("donationChart"), {
            type: "bar",
            data: {
                labels: <?php echo $locations_json; ?>, // Updated to use address from food_donations
                datasets: [{
                    label: "Donations Count",
                    backgroundColor: "#06C167",
                    borderColor: "#04632b",
                    borderWidth: 1,
                    data: <?php echo $donation_counts_json; ?>
                }]
            },
            options: {
                title: { display: true, text: "Donations by Location" },
                legend: { display: false },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        ticks: { autoSkip: false }
                    }],
                    yAxes: [{
                        ticks: { beginAtZero: true }
                    }]
                }
            }
        });
    </script>
</body>
</html>
