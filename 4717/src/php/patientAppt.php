<?php
require_once 'db-connect.php';

// Check if the user is logged in as a patient
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'patient') {
    $patient_id = $_SESSION['patient_id'];
    $current_date = date('Y-m-d');

    // Get patient name (Required when name is updated)
    $sql = "SELECT * FROM Patient WHERE id = $patient_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['patient_name'] = $row['first_name'] . ' ' . $row['last_name'];
    }

    // Retrieve all appointments for the specified patient along with the doctor's name
    $sql = "SELECT A.*, D.first_name AS doctor_first_name, D.last_name AS doctor_last_name
            FROM Appointment AS A
            JOIN Doctor AS D ON A.doctor_id = D.id
            WHERE A.patient_id = $patient_id";

    $result = $conn->query($sql);

    // Initialize arrays to store appointments
    $today_appointments = [];
    $other_appointments = [];

    // Fetch appointment data and add to the appropriate array
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['scheduled_date'] == $current_date) {
                $row['scheduled_date'] = date("d-M-Y", strtotime($row['scheduled_date']));
                $row['scheduled_time'] = date("H:i", strtotime($row['scheduled_time']));
                $today_appointments[] = $row;
            } else {
                $row['scheduled_date'] = date("d-M-Y", strtotime($row['scheduled_date']));
                $row['scheduled_time'] = date("H:i", strtotime($row['scheduled_time']));
                $other_appointments[] = $row;
            }
        }

        // Sort appointments by date and time
        usort($today_appointments, function ($a, $b) {
            return strtotime($a['scheduled_date'] . ' ' . $a['scheduled_time']) - strtotime($b['scheduled_date'] . ' ' . $b['scheduled_time']);
        });

        usort($other_appointments, function ($a, $b) {
            return strtotime($a['scheduled_date'] . ' ' . $a['scheduled_time']) - strtotime($b['scheduled_date'] . ' ' . $b['scheduled_time']);
        });
    }
} else {
    // Handle the case where the user is not logged in as a patient or doesn't have access
    echo "Access denied";
}
