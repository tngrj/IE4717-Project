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
        $message[] = 'Appointment created successfully.';
    } else {
        $message[] = "Error creating appointment: " . $conn->error;
    }
    header('Location: uMain.php?message=' . implode('||', $message));
}

// Close the database connection
$conn->close();
