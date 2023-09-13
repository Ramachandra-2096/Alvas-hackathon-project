<?php
$servername = "localhost";
$username = "root";
$password1 = "";
$dbname = "alva";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $phone = $_POST['phone'];
    $name = $_POST['fullname'];
    $place = $_POST['gst'];
}

try {
    $conn = new mysqli($servername, $username, $password1, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        // Check if email or phone already exist in the database
        $checkExistingQuery = "SELECT * FROM company_signup WHERE email = '$email' OR phone_number = '$phone'";
        $existingResult = $conn->query($checkExistingQuery);

        if ($existingResult->num_rows > 0) {
            echo "<script>alert('Email or phone number already exists. Please use a different email or phone number.')</script>";
            $redirectUrl = ".sighnup\companysingh.html"; 
            header("Location: " . $redirectUrl);
        } else {
            // Insert the new user if email and phone are not already in the database
            $sql = "INSERT INTO company_signup (company_id, company_name, contact_person, email, phone_number, gstno) 
                    VALUES ('$id', '$name', '$contact', '$email', '$phone', '$place')";

            if ($conn->query($sql) === TRUE) {
                sleep(2);
                echo "<script>alert('User registered successfully!')</script>";
                $redirectUrl = "../login/company.html";
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
