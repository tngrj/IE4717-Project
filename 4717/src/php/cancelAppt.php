<?php
require_once 'db-connect.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $appointmentId = $_GET['id'];

    $message = array();

    // Convert the appointment ID to an integer for safety
    $appointmentId = (int)$appointmentId;

    // SQL query to delete the appointment with the provided ID
    $sql = "DELETE FROM Appointment WHERE id = $appointmentId";

    // Execute the SQL query
    if ($conn->query($sql)) {
        header('Location: uMain.php?message=Appointment deleted successfully.');
        exit();
    } else {
        header('Location: uMain.php?message=Error deleting appointment : ' . $conn->error);
        exit();
    }
}

// Close the database connection
$conn->close();
