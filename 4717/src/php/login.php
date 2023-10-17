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
    $_SESSION['patient_id'] = $row['id'];
    $_SESSION['patient_name'] = $row['first_name'] . ' ' . $row['last_name'];

    // Redirect to the patient main page
    header("Location: uMain.php?user_type=patient&patient_id=" . $_SESSION['patient_id']);
    exit();
}

// Check if the user exists in the Doctor table
$sql = "SELECT * FROM Doctor WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User is a doctor, store doctor data in session
    $row = $result->fetch_assoc();
    $_SESSION['user_type'] = 'doctor';
    $_SESSION['doctor_id'] = $row['id'];
    $_SESSION['doctor_name'] = $row['first_name'] . ' ' . $row['last_name'];

    // Redirect to the doctor main page
    header("Location: aMain.php?user_type=doctor&doctor_id=" . $_SESSION['doctor_id']);
    exit();
}

// No match found in either table, show error message
header("Location: ../html/error.html");
exit();

$conn->close();
