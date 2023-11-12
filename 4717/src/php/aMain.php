<?php
session_start();

include 'doctorAppt.php';
include 'doctorData.php';

// Check if the user is logged in as a doctor
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'doctor') {
	$doctor_id = $_SESSION['doctor_id'];
	$doctor_name = $_SESSION['doctor_name'];
} else {
	// Handle the case where the user is not logged in as a doctor
	header("Location: ../html/error.html");
	exit();
}
?>

<?php
if (isset($_GET['message'])) {
	$messages = explode('||', $_GET['message']);
	foreach ($messages as $msg) {
		echo '<div id="popup" class="message"><span>' . $msg  . '</span><button class="button-image"><img src="../css/cancel.png" ></button> </div>';
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Go Doc</title>

	<link rel="stylesheet" href="../css/style.css" />
	<link rel="stylesheet" href="../css/form.css" />
	<link rel="icon" href="../css/logo.png" />
	<script src="../js/script.js"></script>
	<script src="../js/rescheduleAppt.js"></script>
</head>

<body>
	<nav class="navbar">
		<a href="aMain.php" class="home-link" id="homePage"><img src="../css/logo.png" alt="Home" width="50%" /></a>
		<div class="nav-links">
			<a href="aMain.php"><img src="../css/calendar.png" title="Calendar" /></a>
			<a href="doctorProfile.php"><img src="../css/profile.png" title="Profile" /></a>
			<a href="#" id="logoutButton" onclick="confirmLogout();"><img src="../css/logout.png" title="Logout" width="80%" /></a>
		</div>
	</nav>
	<br />

	<div class="appointment-hero">
		<div class="appointment-card">
			<h4 id="uMainTime"></h4>
			<h2>Dr <?php echo $doctor_name ?></h2>
		</div>

		<table>
			<caption>Upcoming Appointment(s) - Today</caption>
			<thead>
				<tr>
					<th>No.</th>
					<th>Patient Name</th>
					<th>Start Time</th>
					<th>Appointment Type</th>
					<th>Status</th>
					<th>Comments</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($today_appointments as $appointment) {
				?>
					<tr>
						<td><?php echo $appointment['id']; ?></td>
						<td><?php echo $appointment['patient_name']; ?></td>
						<td><?php echo $appointment['scheduled_time']; ?></td>
						<td><?php echo $appointment['consultation_type']; ?></td>
						<td><?php echo $appointment['status']; ?></td>
						<td><?php echo $appointment['comments']; ?></td>

					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>

	<div class="appointment-modal">
		<?php generateAppointmentTable('New Appointment(s)', $new_appointments); ?>

		<br>

		<?php generateAppointmentTable('Scheduled Appointment(s)', $scheduled_appointments); ?>
	</div>

	<?php
	function generateAppointmentTable($caption, $appointments)
	{
	?>
		<table class="apptTable">
			<caption><?php echo $caption; ?></caption>
			<thead>
				<tr>
					<th>No.</th>
					<th>Patient Name</th>
					<th>Date</th>
					<th>Start Time</th>
					<th>Appointment Type</th>
					<th>Status</th>
					<th>Comments</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($appointments as $appointment) : ?>
					<tr>
						<td><?php echo $appointment['id']; ?></td>
						<td><?php echo $appointment['patient_name']; ?></td>
						<td><?php echo $appointment['scheduled_date']; ?></td>
						<td><?php echo $appointment['scheduled_time']; ?></td>
						<td><?php echo $appointment['consultation_type']; ?></td>
						<td><?php echo $appointment['status']; ?></td>
						<td><?php echo $appointment['comments']; ?></td>
						<td>
							<div class="btn-group">
								<button onclick="toggleRescheduleData(
									'<?php echo $appointment['id']; ?>',
									'<?php echo $_SESSION['doctor_name']; ?>',
								)"><img src="../css/edit.png" class="button-image"></button>
								<?php
								if ($caption == 'New Appointment(s)') :
								?>
									<form action="doctorSeen.php" method="post">
										<input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
										<button type="submit">
											<img src="../css/confirm.png" class="button-image" title="Confirm Appointment">
										</button>
									</form>
								<?php endif; ?>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php
	}
	?>

	<!-- Reschedule Form -->

	<div class="form-popup-bg" id="rescheduleFormContainer">
		<div class=" form-container" style="max-width:45%">
			<button class="close-button" onclick="toggleForm('rescheduleFormContainer')">
				<img src="../css/cancel.png" class="button-image" title="Close Form" />
			</button>
			<div class="modal-content">
				<h2>Reschedule Appointment</h2><br>
				<input type="hidden" name="appointment_id" id="rescheduleAppointmentId">

				<!-- Display Days of the Week -->
				<div class="days-of-week" id="rescheduleDaysOfWeek">
				</div>
				<!-- Display Timings -->
				<div class="appointment-timings" id="rescheduleTimeList">
				</div>

				<form id="rescheduleForm" action="rescheduleAppt.php" method="POST">
					<input type="hidden" name="appointment_id" id="rescheduleApptId">
					<input type="hidden" name="reschedule_date" id="rescheduleDate">
					<input type="hidden" name="reschedule_time" id="rescheduleTime">
					<button type="submit" class="submitBtn" id="submitReschedule">Submit Reschedule</button>
				</form>
			</div>
		</div>
	</div>

</body>

<footer>
	<p>&copy; Go Doc 2023</p>
</footer>

</html>

<script>
	// Convert PHP array into JavaScript array
	const doctorDataJS = [];

	<?php foreach ($doctorData as $doc) { ?>
		doctorDataJS.push({
			doctorName: '<?php echo $doc['doctorName']; ?>',
			doctorImage: '<?php echo '../assets/' . $doc['doctorImage']; ?>',
			doctorId: '<?php echo $doc['doctorId']; ?>',
			appointments: [
				<?php foreach ($doc['appointments'] as $appointment) { ?> {
						scheduledDate: '<?php echo $appointment['scheduledDate']; ?>',
						scheduledTime: '<?php echo $appointment['scheduledTime']; ?>'
					},
				<?php } ?>
			]
		});
	<?php } ?>
</script>