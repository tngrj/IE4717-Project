<?php
require_once 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['appointment_id'])) {
        $appointmentId = $_POST['appointment_id'];

        // Update the 'seen' column in the database.
        $sql = "UPDATE Appointment SET seen = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $appointmentId);

        if ($stmt->execute()) {
            // Redirect back to the previous page or provide a success message.
            header('Location: aMain.php');
            exit();
        } else {
            // Handle the case where the update failed.
            echo 'Error confirming appointment';
        }

        $stmt->close();
        $conn->close();
    }
}
