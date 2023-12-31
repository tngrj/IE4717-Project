<?php
require_once 'db-connect.php';

// Check if the user is logged in as a doctor
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'doctor') {
    $doctor_id = $_SESSION['doctor_id'];
    $current_date = date('d-M-Y');

    // Get doctor name (Required when name is updated)
    $sql = "SELECT * FROM Doctor WHERE id = $doctor_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['doctor_name'] = $row['first_name'] . ' ' . $row['last_name'];
    }

    // Get appointments
    $sql = "SELECT A.*, 
            CONCAT(P.first_name, ' ', P.last_name) AS patient_name
            FROM Appointment AS A
            JOIN Patient AS P ON A.patient_id = P.id
            WHERE A.doctor_id = $doctor_id";

    $result = $conn->query($sql);

    $today_appointments = [];
    $scheduled_appointments = [];
    $new_appointments = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (isset($row['scheduled_date'], $row['seen'])) {
                $row['scheduled_date'] = date("d-M-Y", strtotime($row['scheduled_date'])); // Format scheduled_date
                $row['scheduled_time'] = date("H:i", strtotime($row['scheduled_time'])); // Format scheduled_time
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

        // Sort arrays based on scheduled date and time
        usort($today_appointments, function ($a, $b) {
            return strtotime($a['scheduled_date'] . ' ' . $a['scheduled_time']) - strtotime($b['scheduled_date'] . ' ' . $b['scheduled_time']);
        });

        usort($scheduled_appointments, function ($a, $b) {
            return strtotime($a['scheduled_date'] . ' ' . $a['scheduled_time']) - strtotime($b['scheduled_date'] . ' ' . $b['scheduled_time']);
        });

        usort($new_appointments, function ($a, $b) {
            return strtotime($a['scheduled_date'] . ' ' . $a['scheduled_time']) - strtotime($b['scheduled_date'] . ' ' . $b['scheduled_time']);
        });
    }
} else {
    echo "Access denied";
}
