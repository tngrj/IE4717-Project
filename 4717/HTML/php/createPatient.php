<?php
require_once 'db-connect.php';

// Get form data
$firstname = $_POST['addfirstname'];
$lastname = $_POST['addlastname'];
$dob = $_POST['adddob'];
$email = $_POST['addemail'];
$password = $_POST['addpassword'];

// Insert data into the database
$sql = "INSERT INTO patients (firstname, lastname, dob, email, password)
VALUES ('$firstname', '$lastname', '$dob', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    header("Location: ../LandingPage.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
