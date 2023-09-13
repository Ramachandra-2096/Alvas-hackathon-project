<?php
$servername = "localhost";
$username = "root";
$password1 = "";
$dbname = "alva";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $crops = isset($_POST['crops']) ? $_POST['crops'] : [];
    $place = $_POST['Place'];
    $land = $_POST['Land'];
}

try {
    $conn = new mysqli($servername, $username, $password1, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
else{
    // Check if email or phone already exist in the database
    $checkExistingQuery = "SELECT * FROM users WHERE email = '$email' OR phone = '$phone'";
    $existingResult = $conn->query($checkExistingQuery);

    if ($existingResult->num_rows > 0) {
        echo "<script>alert('Email or phone number already exists. Please use a different email or phone number.')</script>";
        $redirectUrl = "signup.html"; 
        header("Location: " . $redirectUrl);
    } else {
        // Insert the new user if email and phone are not already in the database
        $sql = "INSERT INTO users (fullname, email, phone, gender, birthdate, land, city) 
                VALUES ('$fullname', '$email', '$phone', '$gender', '$birthdate', '$land', '$place')";

        if ($conn->query($sql) === TRUE) {
            $userId = $conn->insert_id;
            foreach ($crops as $crop) {
                $crop = $conn->real_escape_string($crop);
                $sql = "INSERT INTO user_crops (user_id, crop) VALUES ('$userId', '$crop')";
                if ($conn->query($sql) !== TRUE) {
                    echo "Error inserting crop: " . $conn->error;
                }
            }
            // Sleep for 5 seconds
sleep(2);

            echo "<script>alert('User registered successfully!')</script>";
            $redirectUrl = "../login/login.html";

            header("Location: " . $redirectUrl);
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
