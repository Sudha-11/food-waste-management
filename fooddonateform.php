<?php
session_start(); // Ensure session is started

include("connection.php");

// Redirect to signin if user is not logged in
if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: signin.php");
    exit();
}

$emailid = $_SESSION['email'];
$name = isset($_SESSION['name']) ? $_SESSION['name'] : ''; // Avoid undefined index error

if (isset($_POST['submit'])) {
    $foodname = mysqli_real_escape_string($connection, $_POST['foodname']);
    $meal = mysqli_real_escape_string($connection, $_POST['meal']);
    $category = mysqli_real_escape_string($connection, $_POST['image-choice']);
    $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
    $phoneno = isset($_POST['phoneno']) ? mysqli_real_escape_string($connection, $_POST['phoneno']) : '';
    $district = mysqli_real_escape_string($connection, $_POST['district']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $name = mysqli_real_escape_string($connection, $_POST['name']);

    // Check if phoneno is empty
    if (empty($phoneno)) {
        echo "<script>alert('Phone number is required');</script>";
    } else {
        // Insert into database
        $query = "INSERT INTO food_donations (email, food, type, category, phoneno, location, address, name, quantity, date) 
                  VALUES ('$emailid', '$foodname', '$meal', '$category', '$phoneno', '$district', '$address', '$name', '$quantity', NOW())";

        if (mysqli_query($connection, $query)) {
            echo "<script>alert('Data saved successfully!'); window.location.href='delivery.html';</script>";
            exit();
        } else {
            echo "<script>alert('Error: Unable to save data');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body style="background-color: #06C167;">
    <div class="container">
        <div class="regformf">
            <form action="" method="post">
                <p class="logo">Food <b style="color: #06C167;">Donate</b></p>
                
                <div class="input">
                    <label for="foodname">Food Name:</label>
                    <input type="text" id="foodname" name="foodname" required/>
                </div>
              
                <div class="radio">
                    <label for="meal">Meal type:</label> 
                    <br><br>
                    <input type="radio" name="meal" id="veg" value="veg" required/>
                    <label for="veg" style="padding-right: 40px;">Veg</label>
                    <input type="radio" name="meal" id="Non-veg" value="Non-veg">
                    <label for="Non-veg">Non-veg</label>
                </div>
                <br>

                <div class="input">
                    <label for="food">Select the Category:</label>
                    <br><br>
                    <div class="image-radio-group">
                        <input type="radio" id="raw-food" name="image-choice" value="raw-food">
                        <label for="raw-food">
                          <img src="img/raw-food.png" alt="raw-food">
                        </label>
                        <input type="radio" id="cooked-food" name="image-choice" value="cooked-food" checked>
                        <label for="cooked-food">
                          <img src="img/cooked-food.png" alt="cooked-food">
                        </label>
                        <input type="radio" id="packed-food" name="image-choice" value="packed-food">
                        <label for="packed-food">
                          <img src="img/packed-food.png" alt="packed-food">
                        </label>
                    </div>
                    <br>
                </div>

                <div class="input">
                    <label for="quantity">Quantity (Number of persons/Kg):</label>
                    <input type="text" id="quantity" name="quantity" required/>
                </div>

                <b><p style="text-align: center;">Contact Details</p></b>

                <div class="input">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required/>
                </div>

                <div class="input">
                    <label for="phoneno">Phone No:</label>
                    <input type="text" id="phoneno" name="phoneno" maxlength="10" pattern="[0-9]{10}" required/>
                </div>

                <div class="input">
                    <label for="district">District:</label>
                    <select id="district" name="district" style="padding:10px;" required>
                        <option value="">-- Select District --</option>
                        <option value="anantapur">Anantapur</option>
                        <option value="chittoor">Chittoor</option>
                        <option value="east_godavari">East Godavari</option>
                        <option value="guntur">Guntur</option>
                        <option value="krishna">Krishna</option>
                        <option value="kurnool">Kurnool</option>
                        <option value="nellore">Nellore</option>
                        <option value="prakasam">Prakasam</option>
                        <option value="srikakulam">Srikakulam</option>
                        <option value="visakhapatnam">Visakhapatnam</option>
                        <option value="vizianagaram">Vizianagaram</option>
                        <option value="west_godavari">West Godavari</option>
                        <option value="ysr_kadapa">YSR Kadapa</option>
                        <option value="tirupati">Tirupati</option>
                        <option value="palnadu">Palnadu</option>
                        <option value="bapatla">Bapatla</option>
                        <option value="ntr">NTR</option>
                        <option value="eluru">Eluru</option>
                    </select>
                </div>

                <div class="input">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required/>
                </div>

                <div class="btn">
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
