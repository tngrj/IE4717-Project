<?php
session_start();

include 'patientAppt.php';
include 'doctorData.php';

// Check if the user is logged in as a patient
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'patient') {
	$patient_id = $_SESSION['patient_id'];
	$patient_name = $_SESSION['patient_name'];
} else {
	// Handle the case where the user is not logged in as a patient
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
	<script src="../js/bookAppt.js"></script>
	<script src="../js/rescheduleAppt.js"></script>
	<script src="../js/cancelAppt.js"></script>
	<script src="../js/script.js"></script>
</head>

<body>
	<nav class="navbar">
		<a href="uMain.php" id="homePage"><img src="../css/logo.png" alt="Home" width="50%" /></a>
		<div class="nav-links">
			<a href="uMain.php"><img src="../css/calendar.png" title="Calendar" /></a>
			<a href="doctorList.php"><img src="../css/doctors.png" title="List of Doctors" /></a>
			<a href="patientProfile.php"><img src="../css/profile.png" title="Profile" /></a>
			<a href="#" id="logoutButton" onclick="confirmLogout();"><img src="../css/logout.png" title="Logout" width="80%" /></a>
		</div>
	</nav>
	<br />

	<div class="userMain-body">
		<div class="appointment-hero">
			<div class="appointment-card">
				<h4 id="uMainTime"></h4>
				<h2><?php echo $patient_name ?></h2>
			</div>
			<button id="booknewappointment" onclick="toggleForm('bookingContainer')">
				Book a new Appointment
			</button>
		</div>

		<div class="appointment-modal">
			<?php generateAppointmentTable('Upcoming Appointment(s)', $today_appointments); ?>

			<br /><br /><br /><br /><br />

			<?php generateAppointmentTable('Scheduled Appointment(s)', $other_appointments); ?>
		</div>

		<?php
		function generateAppointmentTable($caption, $appointments)
		{
		?>
			<div id="appointmentTableContainer">
				<table class="apptTable">
					<caption><?php echo $caption; ?></caption>
					<thead>
						<tr>
							<th>No.</th>
							<th>Date</th>
							<th>Start Time</th>
							<th>Doctor</th>
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
								<td><?php echo $appointment['scheduled_date']; ?></td>
								<td><?php echo $appointment['scheduled_time']; ?></td>
								<td><?php echo $appointment['doctor_first_name'] . ' ' . $appointment['doctor_last_name']; ?></td>
								<td><?php echo $appointment['consultation_type']; ?></td>
								<td><?php echo $appointment['status']; ?></td>
								<td><?php echo $appointment['comments']; ?></td>
								<td>
									<button onclick="toggleRescheduleData(
                                '<?php echo $appointment['id']; ?>',
								'<?php echo $appointment['doctor_first_name'] . ' ' . $appointment['doctor_last_name']; ?>',
                            )"><img src="../css/edit.png" class="button-image"></button>
									<button onclick="openModal(
                                '<?php echo $appointment['id']; ?>',
                                '<?php echo $appointment['scheduled_date']; ?>',
                                '<?php echo $appointment['scheduled_time']; ?>',
                                '<?php echo $appointment['doctor_first_name'] . ' ' . $appointment['doctor_last_name']; ?>',
                                '<?php echo $appointment['consultation_type']; ?>',
                                '<?php echo $appointment['comments']; ?>'
                            )"><img src="../css/cancel.png" class="button-image"></button>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php
		}
		?>

	</div>

	<!-- Booking Modal -->

	<div class="form-popup-bg" id="bookingContainer">
		<div class="form-container" style="max-width:800px">
			<button class="close-button" onclick="toggleForm('bookingContainer')">
				<img src="../css/cancel.png" class="button-image" title="Close Form" />
			</button>
			<div class="modal-content">
				<h2>Choose your Doctor</h2><br>
				<div class="row" id="doctorList">
					<?php
					foreach ($doctorData as $doctor) {
						$doctorName = $doctor['doctorName'];
						$doctorImage = '../assets/' . $doctor['doctorImage'];
						$doctorId = $doctor['doctorId'];
						echo '<div class="card doctor-card" data-doctorid="' . $doctorId . '">
                        <img src="' . $doctorImage . '" alt="' . $doctorName . '" class="doc-image">
                        <p class="doctor-name">' . $doctorName . '</p>
                    </div>';
					}
					?>
				</div>

				<!-- Hidden until Doctor is selected -->
				<div class="appointment-data" style="display: none;">
					<h3 id="selectedDoctorName">Selected Doctor: </h3>
					<img id="selectedDoctorImage" class="doc-image" alt="Selected Doctor" />
					<div class="days-of-week" id="dayList">
					</div>
					<div class="appointment-timings" id="timeList">
					</div>
				</div>

				<!-- Hidden until time is selected -->
				<div class="appointment-details" style="display: none;">
					<h3>Appointment Details</h3><br />
					<form id="appointmentForm" action="createAppt.php" method="POST">
						<input type="hidden" name="doctor_id" id="selectedDoctorId">
						<input type="hidden" name="patient_id" id="patientId" value="<?php echo $patient_id; ?>">
						<input type="hidden" name="selected_date" id="selectedDate">
						<input type="hidden" name="selected_time" id="selectedTime">
						<label for="appointmentType">Select Appointment Type:</label>
						<select name="appointment_type" id="appointmentType" required>
							<option value="" disabled selected>Select Appointment Type</option>
							<option value="General Check-Up">General Check-Up</option>
							<option value="Specialist Consultation">Specialist Consultation</option>
							<option value="Vaccination">Vaccination</option>
							<option value="Follow Up">Follow Up</option>
							<option value="Preventive Screening">Preventive Screening</option>
						</select>
						<label for="userComments">Comments (optional):</label>
						<textarea name="comment" id="userComments" class="commentbox"></textarea>
						<button type="submit" class="submitBtn" id="submitAppointment">Submit Appointment</button>
					</form>
				</div>
			</div>
		</div>
	</div>

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

	<!-- Cancellation Modal -->

	<div class="form-popup-bg" id="cancellationFormContainer">
		<div class="form-container">
			<button class="close-button" onclick="toggleForm('cancellationFormContainer')">
				<img src="../css/cancel.png" class="button-image" title="Close Form" />
			</button>
			<div class="modal-content">
				<h2>Confirm Appointment Cancellation</h2><br>
				<p>Date: <span id="modalDate"></span></p>
				<p>Start Time: <span id="modalStartTime"></span></p>
				<p>Doctor: <span id="modalDoctor"></span></p>
				<p>Appointment Type: <span id="modalAppointmentType"></span></p>
				<p>Comments: <span id="modalComments"></span></p>
			</div>
			<button class="cancelBtn" onclick="cancelAppointment()">Confirm</button>
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

	// Add an event listener to each doctor card
	const doctorCards = document.querySelectorAll('.doctor-card');
	doctorCards.forEach((card) => {
		const doctorId = card.getAttribute('data-doctorid');
		card.addEventListener('click', () => {

			// Update the selected doctor ID to form input
			document.getElementById('selectedDoctorId').value = doctorId;

			// Trigger the function to toggle the appointment data
			toggleAppointmentData(doctorId);
		});
	});

	dayList.addEventListener('click', (event) => {
		if (event.target.classList.contains('day-card')) {
			const selectedDay = event.target.textContent;
			const formattedDate = formatDate(new Date(), selectedDay);
			document.getElementById('selectedDate').value = formattedDate;
		}
	});

	timeList.addEventListener('click', (event) => {
		if (event.target.classList.contains('time-card')) {
			const selectedTime = event.target.textContent;
			const formattedTime = formatTime(selectedTime);
			document.getElementById('selectedTime').value = formattedTime;
		}
	});
</script>