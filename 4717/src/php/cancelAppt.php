<?php
require_once 'db-connect.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $appointmentId = $_GET['id'];

    $message = array();

    // Convert the appointment ID to an integer for safety
    $appointmentId = (int)$appointmentId;

    // Fetch appointment details before deletion
    $appointmentQuery = $conn->query("SELECT * FROM Appointment WHERE id = $appointmentId");

    if ($appointmentQuery) {
        $appointmentData = $appointmentQuery->fetch_assoc();

        // SQL query to delete the appointment with the provided ID
        $sql = "DELETE FROM Appointment WHERE id = $appointmentId";

        // Execute the SQL query
        if ($conn->query($sql)) {
            // Get user email from the database
            $userId = $appointmentData['patient_id'];
            $userIdQuery = $conn->query("SELECT email FROM Patient WHERE id = '$userId'");

            if ($userIdQuery) {
                $userData = $userIdQuery->fetch_assoc();
                $receiver = $userData['email'];
                $sender = 'f32ee@localhost';

                $subject = 'Appointment Cancellation';
                $message = "Your appointment has been canceled.\n\n";
                $message .= "Date: {$appointmentData['scheduled_date']}\n";
                $message .= "Time: {$appointmentData['scheduled_time']}\n";

                $headers = 'From: ' . $sender . "\r\n" .
                    'Reply-To: ' . $sender . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                // Send the email
                mail($receiver, $subject, $message, $headers, '-f' . $sender);

                header('Location: uMain.php?message=Appointment deleted successfully.');
                exit();
            } else {
                // Handle the case where the user email query fails
                header('Location: uMain.php?message=Error fetching user email: ' . $conn->error);
                exit();
            }
        } else {
            // Handle the case where the appointment deletion fails
            header('Location: uMain.php?message=Error deleting appointment: ' . $conn->error);
            exit();
        }
    } else {
        // Handle the case where the appointment query fails
        header('Location: uMain.php?message=Error fetching appointment details: ' . $conn->error);
        exit();
    }
}

// Close the database connection
$conn->close();
