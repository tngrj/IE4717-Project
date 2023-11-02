<?php
session_start();

include 'doctorAppt.php';

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

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Go Doc</title>

	<link rel="stylesheet" href="../css/style.css" />
	<link rel="stylesheet" href="../css/form.css" />
	<link rel="icon" href="../css/LogoIcon.png" />
	<script src="../js/script.js"></script>
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
		<table class="apptTable">
			<caption>New Appointment(s)</caption>
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
				<?php
				foreach ($new_appointments as $appointment) {
				?>
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
								<button><img src="../css/edit.png" class="button-image" title="Reschedule Appointment"></button>
								<button onclick="openModal(
											'<?php echo $appointment['id']; ?>',
											'<?php echo $appointment['scheduled_date']; ?>',
											'<?php echo $appointment['scheduled_time']; ?>',
											'<?php echo $appointment['consultation_type']; ?>',
											'<?php echo $appointment['status']; ?>',
											'<?php echo $appointment['comments']; ?>'
											)"><img src="../css/cancel2.png" class="button-image" title="Cancel Appointment"></button>
								<form action="confirmAppt.php" method="post">
									<input type="hidden" name="appointment_id" value="<?php echo $appointment['id']; ?>">
									<button type="submit">
										<img src="../css/confirm.png" class="button-image" title="Confirm Appointment">
									</button>
								</form>
							</div>
						</td>
					</tr>
				<?php
				}
				?>
			</tbody>

		</table>

		<br>

		<table class="apptTable">
			<caption>Scheduled Appointment(s)</caption>
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
				<?php
				foreach ($scheduled_appointments as $appointment) {
				?>
					<tr>
						<td><?php echo $appointment['id']; ?></td>
						<td><?php echo $appointment['patient_name']; ?></td>
						<td><?php echo $appointment['scheduled_date']; ?></td>
						<td><?php echo $appointment['scheduled_time']; ?></td>
						<td><?php echo $appointment['consultation_type']; ?></td>
						<td><?php echo $appointment['status']; ?></td>
						<td><?php echo $appointment['comments']; ?></td>
						<td>
							<button><img src="../css/edit.png" class="button-image" title="Reschedule Appointment"></button>
							<button onclick="openModal(
									'<?php echo $appointment['id']; ?>',
									'<?php echo $appointment['scheduled_date']; ?>',
									'<?php echo $appointment['scheduled_time']; ?>',
									'<?php echo $appointment['consultation_type']; ?>',
									'<?php echo $appointment['status']; ?>',
									'<?php echo $appointment['comments']; ?>'
									)"><img src="../css/cancel2.png" class="button-image" title="Cancel Appointment"></button>
						</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>

	</div>

	<script>
		/*TBE
        //Edit Appt
        function viewCFasmForm(asmId) {
            window.location.href = "aCFasm.html?view_id=" + asmId;
            getCFasmForm(asmId);
        }  

        //Cancel Appt 
        function printCFuenoForm(uenoId) s{
            getCFuenoForm(uenoId);
            var newwindow = window.open("aCFueno.html?view_id=" + uenoId);
            getPDF(newwindow, uenoId, "ueno");
        }
        */

		var now = new Date();
		var hours = now.getHours();
		var greetingElement = document.getElementById('uMainTime');

		if (hours >= 0 && hours < 12) {
			greetingElement.textContent = 'Good Morning';
		} else if (hours >= 12 && hours < 17) {
			greetingElement.textContent = 'Good Afternoon';
		} else {
			greetingElement.textContent = 'Good Evening';
		}


		function confirmLogout() {
			if (confirm('Are you sure you want to log out?')) {
				// If the user confirms, then trigger the logout process by navigating to the PHP script.
				window.location.href = 'logout.php';
			}
		}
	</script>
</body>

<footer>
	<p>&copy; Go Doc 2023</p>
</footer>

</html>