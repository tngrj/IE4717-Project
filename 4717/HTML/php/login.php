<?php
require_once 'db-connect.php';

// Start the session
session_start();

// Get form data
$email = $_POST['username'];
$password = $_POST['password'];

// Check if the user exists in the patients table
$sql = "SELECT * FROM patients WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User is a patient, redirect to the patient main page
    header("Location: ../uMain.html");
    exit();
} else {
    // User is not a patient, check the doctors table
    $sql = "SELECT * FROM doctors WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User is a doctor, redirect to the doctor main page
        header("Location: ../aMain.html");
        exit();
    } else {
        // No match found in either table, show error message
        echo "Invalid email or password";
    }
}

$conn->close();
