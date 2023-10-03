<?php
require_once 'db-connect.php';

// Check if the user is logged in as a patient
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'patient') {
    $patient_id = $_SESSION['patient_id'];

    // Retrieve all appointments for the specified patient
    $sql = "SELECT * FROM Appointment WHERE patient_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize an array to store appointment data
    $appointments = [];

    // Fetch appointment data and add to the array
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
    }

    $stmt->close();
    $conn->close();

    // Return the appointment data as JSON
    // echo json_encode($appointments);
} else {
    // Handle the case where the user is not logged in as a patient or doesn't have access
    echo json_encode(['error' => 'Access denied']);
}
