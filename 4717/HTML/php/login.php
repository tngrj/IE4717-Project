<?php
require_once 'db-connect.php';

// Start the session
session_start();

// Get form data
$email = $_POST['username'];
$password = $_POST['password'];

// Check if the user exists in the Patient table
$sql = "SELECT * FROM Patient WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User is a patient, store patient data in session
    $row = $result->fetch_assoc();
    $_SESSION['user_type'] = 'patient';
    $_SESSION['patient_id'] = $row['id']; // Assuming 'id' is the patient's ID in the database
    $_SESSION['patient_name'] = $row['first_name'] . ' ' . $row['last_name']; // Assuming 'first_name' and 'last_name' are the patient's name fields

    // Redirect to the patient main page
    header("Location: ../uMain.php?user_type=patient&patient_id=" . $_SESSION['patient_id']);
    exit();
} else {
    // User is not a patient, check the Doctor table
    $sql = "SELECT * FROM Doctor WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User is a doctor, redirect to the doctor main page
        header("Location: ../aMain.html");
        exit();
    } else {
        // No match found in either table, show error message
        header("Location: ../error.html");
        exit();
    }
}


$conn->close();
