<?php
require_once 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentId = $_POST['appointment_id'];
    $rescheduleDate = $_POST['reschedule_date'];
    $rescheduleTime = $_POST['reschedule_time'];

    // Update the appointment in the database
    $sql = "UPDATE Appointment SET scheduled_date = '$rescheduleDate', scheduled_time = '$rescheduleTime' WHERE id = $appointmentId";

    if ($conn->query($sql) === TRUE) {
        // Get user email from the database
        $userIdQuery = $conn->query("SELECT Patient.email FROM Appointment
                                    JOIN Patient ON Appointment.patient_id = Patient.id
                                    WHERE Appointment.id = $appointmentId");

        if ($userIdQuery) {
            $userData = $userIdQuery->fetch_assoc();
            $receiver = $userData['email'];
            $sender = 'f32ee@localhost';

            $subject = 'Appointment Rescheduled';
            $message = "Your appointment has been rescheduled.\n\n";
            $message .= "New Date: $rescheduleDate\n";
            $message .= "New Time: $rescheduleTime\n";

            $headers = 'From: ' . $sender . "\r\n" .
                'Reply-To: ' . $sender . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            // Send the email
            mail($receiver, $subject, $message, $headers, '-f' . $sender);

            // Redirect back to the previous page or provide a success message.
            $referer = $_SERVER['HTTP_REFERER'];
            header("Location: $referer?message=Appointment rescheduled successfully");
            exit();
        } else {
            // Handle the case where the user email query fails
            echo 'Error fetching user email.';
        }
    } else {
        // Handle the case where the update failed.
        echo 'Error rescheduling appointment: ' . mysqli_error($conn);
    }

    $conn->close();
} else {
    // Handle cases where the request method is not POST
    echo 'Invalid request method';
}
