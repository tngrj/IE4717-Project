<?php
require_once 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['appointment_id'])) {
        $appointmentId = $_POST['appointment_id'];

        // Update the 'seen' column in the database.
        $sql = "UPDATE Appointment SET seen = 1 WHERE id = $appointmentId";

        if ($conn->query($sql)) {
            // Redirect back to the previous page with a success message.
            header('Location: aMain.php?message=Appointment confirmed successfully');
            exit();
        } else {
            // Handle the case where the update failed.
            echo 'Error confirming appointment';
        }

        $conn->close();
    }
}
