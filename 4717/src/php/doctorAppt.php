<?php
require_once 'db-connect.php';

// Check if the user is logged in as a doctor
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'doctor') {
    $doctor_id = $_SESSION['doctor_id'];
    $current_date = date('Y-m-d');

    $sql = "SELECT A.*, 
            CONCAT(P.first_name, ' ', P.last_name) AS patient_name
            FROM Appointment AS A
            JOIN Patient AS P ON A.patient_id = P.id
            WHERE A.doctor_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $today_appointments = [];
    $scheduled_appointments = [];
    $new_appointments = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (isset($row['scheduled_date'], $row['seen'])) {
                $appointmentData = $row;

                if ($row['scheduled_date'] == $current_date) {
                    $today_appointments[] = $appointmentData;
                } elseif ($row['seen'] == 1) {
                    $scheduled_appointments[] = $appointmentData;
                } elseif ($row['seen'] == 0) {
                    $new_appointments[] = $appointmentData;
                }
            }
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Access denied']);
}
