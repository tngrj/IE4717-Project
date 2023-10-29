<?php
require_once 'db-connect.php';

// Start the session
session_start();

// Get form data
$email = $_POST['username'];
$password = $_POST['password'];

// Define a function to set session data
function setUserData($userType, $userId, $userName)
{
    $_SESSION['user_type'] = $userType;
    $_SESSION[$userType . '_id'] = $userId;
    $_SESSION[$userType . '_name'] = $userName;
}

// Check if the user exists in the Patient table
$sql = "SELECT * FROM Patient WHERE email=? AND password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows > 0) {
    // User is a patient, store patient data in session
    $row = $result->fetch_assoc();
    setUserData('patient', $row['id'], $row['first_name'] . ' ' . $row['last_name']);
    header("Location: uMain.php?user_type=patient&patient_id=" . $_SESSION['patient_id']);
    exit();
}

// Check if the user exists in the Doctor table
$sql = "SELECT * FROM Doctor WHERE email=? AND password=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();

if ($result->num_rows > 0) {
    // User is a doctor, store doctor data in session
    $row = $result->fetch_assoc();
    setUserData('doctor', $row['id'], $row['first_name'] . ' ' . $row['last_name']);
    header("Location: aMain.php?user_type=doctor&doctor_id=" . $_SESSION['doctor_id']);
    exit();
}

// No match found in either table, show error message
header("Location: ../html/error.html");
exit();
