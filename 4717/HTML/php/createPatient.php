<?php
require_once 'db-connect.php';

// Get form data
$firstname = $_POST['addfirstname'];
$lastname = $_POST['addlastname'];
$dob = $_POST['adddob'];
$email = $_POST['addemail'];
$password = $_POST['addpassword'];

// Check if the email already exists in the database
$sql = "SELECT * FROM Patient WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Email already exists, show error message
    echo "Error: Email already exists";
} else {
    // Insert data into the database
    $sql = "INSERT INTO Patient (first_name, last_name, date_of_birth, email, password)
    VALUES ('$firstname', '$lastname', '$dob', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../LandingPage.html");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
