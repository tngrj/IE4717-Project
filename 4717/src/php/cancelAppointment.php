<?php
require_once 'db-connect.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $appointmentId = $_GET['id'];

    // SQL query to delete the appointment with the provided ID
    $sql = "DELETE FROM Appointment WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $appointmentId);

    if ($stmt->execute()) {
        // Appointment deleted successfully

        // Redirect the user back to uMain.php
        header("Location: uMain.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Handle the case where the 'id' parameter is not set
    echo "Invalid request.";
}
