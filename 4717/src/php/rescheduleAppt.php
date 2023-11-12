<?php
require_once 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentId = $_POST['appointment_id'];
    $rescheduleDate = $_POST['reschedule_date'];
    $rescheduleTime = $_POST['reschedule_time'];

    // Update the appointment in the database
    $sql = "UPDATE Appointment SET scheduled_date = '$rescheduleDate', scheduled_time = '$rescheduleTime' WHERE id = $appointmentId";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to the previous page or provide a success message.
        $referer = $_SERVER['HTTP_REFERER'];
        header("Location: $referer?message=Appointment rescheduled successfully");
        exit();
    } else {
        // Handle the case where the update failed.
        echo 'Error rescheduling appointment: ' . mysqli_error($conn);
    }

    $conn->close();
} else {
    // Handle cases where the request method is not POST
    echo 'Invalid request method';
}
