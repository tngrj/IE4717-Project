<?php
require_once 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctorId = $_POST["doctor_id"];
    $patientId = $_POST["patient_id"];
    $selectedDate = $_POST["selected_date"];
    $selectedTime = $_POST["selected_time"];
    $comment = $_POST["comment"];
    $appointmentType = $_POST["appointment_type"];

    $message = array();

    // Construct the SQL INSERT statement
    $sql = "INSERT INTO Appointment (patient_id, doctor_id, scheduled_date, scheduled_time, comments, consultation_type) 
            VALUES ('$patientId', '$doctorId', '$selectedDate', '$selectedTime', '$comment', '$appointmentType')";

    // Execute the SQL query
    if ($conn->query($sql) === TRUE) {
        // Get user email from the database
        $userIdQuery = $conn->query("SELECT email FROM Patient WHERE id = '$patientId'");

        if ($userIdQuery) {
            $userData = $userIdQuery->fetch_assoc();
            $receiver = $userData['email'];
            $sender = 'f32ee@localhost';

            $subject = 'New Appointment Scheduled';
            $message = "You have a new appointment scheduled.\n\n";
            $message .= "Date: $selectedDate\n";
            $message .= "Time: $selectedTime\n";
            $message .= "Comments: $comment\n";
            $message .= "Consultation Type: $appointmentType\n";

            $headers = 'From: ' . $sender . "\r\n" .
                'Reply-To: ' . $sender . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            // Send the email
            mail($receiver, $subject, $message, $headers, '-f' . $sender);

            header('Location: uMain.php?message=Appointment created successfully');
            exit();
        } else {
            // Handle the case where the user email query fails
            header('Location: uMain.php?message=Error fetching user email: ' . $conn->error);
            exit();
        }
    } else {
        // Handle the case where the appointment creation fails
        header('Location: uMain.php?message=Error creating appointment: ' . $conn->error);
        exit();
    }
}

// Close the database connection
$conn->close();
