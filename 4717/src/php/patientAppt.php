<?php
require_once 'db-connect.php';

// Check if the user is logged in as a patient
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'patient') {
    $patient_id = $_SESSION['patient_id'];

    // Get the current date
    $current_date = date('Y-m-d');

    // Retrieve all appointments for the specified patient along with the doctor's name
    $sql = "SELECT A.*, D.first_name AS doctor_first_name, D.last_name AS doctor_last_name
            FROM Appointment AS A
            JOIN Doctor AS D ON A.doctor_id = D.id
            WHERE A.patient_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize arrays to store appointments
    $today_appointments = [];
    $other_appointments = [];

    // Fetch appointment data and add to the appropriate array
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['scheduled_date'] == $current_date) {
                $today_appointments[] = $row;
            } else {
                $other_appointments[] = $row;
            }
        }
    }

    $stmt->close();
    $conn->close();
} else {
    // Handle the case where the user is not logged in as a patient or doesn't have access
    echo json_encode(['error' => 'Access denied']);
}
