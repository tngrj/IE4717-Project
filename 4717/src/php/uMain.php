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
	<script src="../js/cancelAppt.js"></script>
	<script src="../js/script.js"></script>
</head>

<body>
	<nav class="navbar">
		<a href="uMain.php" id="homePage"><img src="../css/logo.png" alt="Home" width="50%" /></a>
		<div class="nav-links">
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
			<div id="appointmentTableContainer">
				<table class="apptTable">
					<caption>Upcoming Appointment(s)</caption>
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
						<?php
						foreach ($today_appointments as $appointment) {
						?>
							<tr>
								<td><?php echo $appointment['id']; ?></td>
								<td><?php echo $appointment['scheduled_date']; ?></td>
								<td><?php echo $appointment['scheduled_time']; ?></td>
								<td><?php echo $appointment['doctor_first_name'] . ' ' . $appointment['doctor_last_name']; ?></td>
								<td><?php echo $appointment['consultation_type']; ?></td>
								<td><?php echo $appointment['status']; ?></td>
								<td><?php echo $appointment['comments']; ?></td>
								<td>
									<button><img src="../css/edit.png" class="button-image"></button>
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
						<?php
						}
						?>
					</tbody>
				</table>
			</div>

			<br /><br /><br /><br /><br />

			<div id="appointmentTableContainer">
				<table class="apptTable">
					<caption>Scheduled Appointment(s)</caption>
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
						<?php
						foreach ($other_appointments as $appointment) {
						?>
							<tr>
								<td><?php echo $appointment['id']; ?></td>
								<td><?php echo $appointment['scheduled_date']; ?></td>
								<td><?php echo $appointment['scheduled_time']; ?></td>
								<td><?php echo $appointment['doctor_first_name'] . ' ' . $appointment['doctor_last_name']; ?></td>
								<td><?php echo $appointment['consultation_type']; ?></td>
								<td><?php echo $appointment['status']; ?></td>
								<td><?php echo $appointment['comments']; ?></td>
								<td>
									<button><img src="../css/edit.png" class="button-image" title="Reschedule Appointment"></button>
									<button onclick="openModal(
									'<?php echo $appointment['id']; ?>',
									'<?php echo $appointment['scheduled_date']; ?>',
									'<?php echo $appointment['scheduled_time']; ?>',
									'<?php echo $appointment['doctor_first_name'] . ' ' . $appointment['doctor_last_name']; ?>',
									'<?php echo $appointment['consultation_type']; ?>',
									'<?php echo $appointment['comments']; ?>'
									)"><img src="../css/cancel.png" class="button-image" title="Cancel Appointment"></button>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- Confirmation Modal -->

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
						<select name="appointment_type" id="appointmentType">
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

</body>

<footer>
	<p>&copy; Go Doc 2023</p>
</footer>

</html>

<script>
	// Prompt the user to confirm logout
	function confirmLogout() {
		if (confirm('Are you sure you want to log out?')) {
			// If the user confirms, then trigger the logout process by navigating to the PHP script.
			window.location.href = 'logout.php';
		}
	}

	// Get the current time and display the appropriate greeting
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

	// Toggles Booking / Cancellation Modal
	function toggleForm(formId) {
		const formContainer = document.getElementById(formId);
		formContainer.classList.toggle('is-visible');
	}

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
			const selectedDoctorIdInput = document.getElementById('selectedDoctorId');
			selectedDoctorIdInput.value = doctorId;

			// Trigger the function to toggle the appointment data
			toggleAppointmentData(doctorId);
		});
	});

	// Toggle the appointment data when a doctor card is clicked
	function toggleAppointmentData(doctorId) {
		const appointmentData = document.querySelector('.appointment-data');
		const isVisible = appointmentData.style.display === 'block';

		if (isVisible) {
			appointmentData.style.display = 'none';
		} else {
			appointmentData.style.display = 'block';

			// Find the selected doctor by doctorId
			const selectedDoctor = doctorDataJS.find((doctor) => doctor.doctorId === doctorId);

			// Check if a doctor with the given ID was found
			if (selectedDoctor) {
				// Populate the selected doctor's name and image
				const doctorNameElement = document.getElementById('selectedDoctorName');
				const doctorImageElement = document.getElementById('selectedDoctorImage');

				doctorNameElement.textContent = 'Selected Doctor: ' + selectedDoctor.doctorName;
				doctorImageElement.src = selectedDoctor.doctorImage;

				// Clear previous data from daysOfWeekContainer and timeCardContainer
				const daysOfWeekContainer = document.querySelector('.days-of-week');
				const timeCardContainer = document.querySelector('.appointment-timings');
				daysOfWeekContainer.innerHTML = '';
				timeCardContainer.innerHTML = '';

				// Get the current date
				const currentDate = new Date();

				// Create an array of days of the week
				const daysOfWeek = [];
				let selectedDay = null; // Track the selected day

				for (let i = 0; i < 6; i++) {
					const date = new Date(currentDate);
					date.setDate(currentDate.getDate() + i);
					const options = {
						weekday: 'short',
						day: '2-digit'
					};
					const day = date.toLocaleDateString(undefined, options);
					daysOfWeek.push(day);
				}

				daysOfWeek.forEach((day) => {
					const dayCard = document.createElement('button');
					dayCard.classList.add('day-card');
					dayCard.textContent = day;

					// Add a click event listener to select/deselect the day
					dayCard.addEventListener('click', () => {
						if (selectedDay) {
							selectedDay.classList.remove('selected'); // Deselect the previous day
						}
						dayCard.classList.add('selected'); // Select the clicked day
						selectedDay = dayCard; // Update the selected day

						// Check if the selected day has appointments
						const dayText = dayCard.textContent;
						const selectedDate = new Date(currentDate);
						selectedDate.setDate(currentDate.getDate() + daysOfWeek.indexOf(dayText));
						const options = {
							day: '2-digit',
							month: 'short',
							year: 'numeric'
						};
						const formattedDate = selectedDate.toLocaleDateString('en-UK', options).replace(/ /g, '-');
						const appointments = selectedDoctor.appointments;

						// Extract the time slots for the selected day
						const selectedAppointments = appointments.filter(
							(appointment) => appointment.scheduledDate === formattedDate
						);

						// Create an array of booked time slots for the selected day
						const bookedTimeSlots = selectedAppointments.map(
							(appointment) => appointment.scheduledTime
						);

						// Loop through time slots and disable booked slots
						timeCardContainer.childNodes.forEach((slot) => {
							let slotTime = slot.textContent.replace(' AM', '').replace(' PM', '');
							const isPM = slot.textContent.includes('PM');

							const [hour, minute] = slotTime.split(':').map((str) => parseInt(str));
							const adjustedHour = isPM && hour !== 12 ? hour + 12 : !isPM && hour === 12 ? 0 : hour;

							slotTime = `${adjustedHour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;

							slot.disabled = bookedTimeSlots.includes(slotTime);
							slot.classList.toggle('booked', slot.disabled);
						});
					});

					daysOfWeekContainer.appendChild(dayCard);
				});

				const timeSlots = [
					'8:00 AM',
					'9:00 AM',
					'10:00 AM',
					'11:00 AM',
					'1:00 PM',
					'2:00 PM',
					'3:00 PM',
					'4:00 PM',
				];

				let selectedTimeSlot = null;

				timeSlots.forEach((slot, index) => {
					const timeCard = document.createElement('button');
					timeCard.classList.add('time-card');
					timeCard.textContent = slot;

					// Add a data attribute to identify the time slot
					timeCard.setAttribute('data-slot', index);

					timeCard.addEventListener('click', () => {
						if (selectedTimeSlot) {
							// Remove the "selected" class from the previously selected time slot
							selectedTimeSlot.classList.remove('selected');
						}

						// Add the "selected" class to the clicked time slot
						timeCard.classList.add('selected');

						// Update the selected time slot
						selectedTimeSlot = timeCard;

						const selectedTime = convertTo24HourFormat(slot);
						handleTimeSlotSelection(selectedTime);
					});

					timeCardContainer.appendChild(timeCard);
				});

				function convertTo24HourFormat(time) {
					const [timePart, meridian] = time.split(' ');
					const [hour, minute] = timePart.split(':');

					let hour24 = parseInt(hour);

					if (meridian === 'PM' && hour24 !== 12) {
						hour24 += 12;
					} else if (meridian === 'AM' && hour24 === 12) {
						hour24 = 0;
					}

					return hour24.toString().padStart(2, '0') + ':' + minute;
				}

				function handleTimeSlotSelection(selectedTime) {
					// Handle the logic for selecting a time slot here
					const appointmentDetail = document.querySelector('.appointment-details').style.display = 'flex';
				}
			}
		}
	}

	dayList.addEventListener('click', (event) => {
		if (event.target.classList.contains('day-card')) {
			const selectedDay = event.target.textContent;
			const formattedDate = formatDate(new Date(), selectedDay);
			document.getElementById('selectedDate').value = formattedDate;
			// enableSubmitButton();
		}
	});

	timeList.addEventListener('click', (event) => {
		if (event.target.classList.contains('time-card')) {
			const selectedTime = event.target.textContent;
			const formattedTime = formatTime(selectedTime);
			document.getElementById('selectedTime').value = formattedTime;
			// enableSubmitButton();
		}
	});

	function formatDate(currentDate, selectedDay) {
		const dateParts = selectedDay.split(' ');
		const day = dateParts[1];
		const month = currentDate.getMonth() + 1;
		const year = currentDate.getFullYear();
		return `${year}-${month.toString().padStart(2, '0')}-${day.padStart(2, '0')}`;
	}

	function formatTime(selectedTime) {
		const timeParts = selectedTime.split(' ');
		const time = timeParts[0];
		const meridian = timeParts[1];
		const [hours, minutes] = time.split(':');
		let formattedHours = parseInt(hours, 10);
		if (meridian === 'PM' && formattedHours !== 12) {
			formattedHours += 12;
		} else if (meridian === 'AM' && formattedHours === 12) {
			formattedHours = 0;
		}
		return `${formattedHours.toString().padStart(2, '0')}:${minutes}:00`;
	}
</script>