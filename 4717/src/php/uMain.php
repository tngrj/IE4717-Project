<?php
session_start();

include 'patientAppt.php';
include 'doctorAvailability.php';

if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'patient') {
	$patient_id = $_SESSION['patient_id'];
	$patient_name = $_SESSION['patient_name'];
} else {
	header("Location: ../html/error.html");
	exit();
}

if (isset($_SESSION['availableTimeSlots'])) {
    $availableTimeSlots = $_SESSION['availableTimeSlots'];
} else {
    $availableTimeSlots = [];
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
	<script src="../js/makeAppt.js"></script>
	<script src="../js/cancelAppt.js"></script>
	<script src="../js/script.js"></script>
	<style>
		#unewAppointment {
			position: fixed;
			padding: 60px;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			border: 1px solid #ccc;
			border-radius: 10px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
			background-color: white;
			max-height: 90vh;
			overflow-y: auto;
			z-index: 1000;
}
	</style>
</head>

<body>
	<nav class="navbar">
		<a href="uMain.php" class="home-link" id="homePage"><img src="../css/logo.png" alt="Home" width="50%" /></a>
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
			<button id="booknewappointment" onclick="booknewappointment(unewAppointment);">
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
									)"><img src="../css/cancel2.png" class="button-image"></button>
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
									)"><img src="../css/cancel2.png" class="button-image" title="Cancel Appointment"></button>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>

		<div id="unewAppointment" class="hidden">
			<div class="header-container">
				<label style="font-size: 28px;">Choose your Doctor</label>
				<img class="exitBtn header-container-right" id="closeBtn" src="../css/cancel.png" alt="close button" onclick="closePopup('unewAppointment');">
			</div>
			<br>
			<button class="doctBtn" onclick="bookAppointment('drTan');">Dr. Tan</button>
			<button class="doctBtn" onclick="bookAppointment('drLim');">Dr. Lim</button>
			<button class="doctBtn" onclick="bookAppointment('drKoh');">Dr. Koh</button>
		</div>
	</div>

	<!-- Confirmation Modal -->

	<div class="form-popup-bg" id="cancellationFormContainer">
		<div class="form-container">
			<button class="close-button" onclick="closeCancellationForm()">X</button>
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

	function confirmLogout() {
		if (confirm('Are you sure you want to log out?')) {
			window.location.href = 'logout.php';
		}
	}

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

	const overlayStack = [];

//User: New Appt - Doctor
function newapptdoctor(doctor) {
		const overlayContent = document.querySelector('#unewAppointment');

		sessionStorage.setItem('doctor', doctor);

		let doctorname, doctorid, positionname;
		if (doctor === 'drTan') {
			sessionStorage.setItem('doctorname', 'Dr Tan Wee Lian');
			doctorid = 1;
			sessionStorage.setItem('positionname', 'Family Physician');
		} else if (doctor === 'drLim') {
			sessionStorage.setItem('doctorname', 'Dr Lim Koon Meng');
			doctorid = 2;
			sessionStorage.setItem('positionname', 'Family Physician');
		} else if (doctor === 'drKoh') {
			sessionStorage.setItem('doctorname', 'Dr Koh Wee Beng');
			doctorid = 3;
			sessionStorage.setItem('positionname', 'Dentist');
		}

		const currentDate = new Date();
		const options = { year: 'numeric', month: 'long'};
		const currentMonthYear = currentDate.toLocaleDateString('en-US', options);
		sessionStorage.setItem('currentMonthYear', currentMonthYear);

		overlayStack.push(overlayContent.innerHTML);
		overlayContent.innerHTML = `
                <div class="header-container">
                    <img class="exitBtn header-container-left" id="backBtn" src="../css/cancel.png" alt="back button" onclick="backPopup(unewAppointment);">  
                    <img class="exitBtn header-container-right" id="closeBtn" src="../css/cancel.png" alt="close button" onclick="closePopup(unewAppointment);">                
                </div>
                <br>
                <div class="body-container">
                    <img class="doctBtn" id="drImage" src="../css/${doctor}.jpg" alt="drImage">
                    <div class="doctor-info">
                        <label style="font-size: 28px;" id="doctorname">Doctor Name</label><br>
                        <label style="font-size: 18px;" id="positionname">Position</label>
                    </div>
                </div>
                <br>

				<label style="font-size: 22px; font-weight: bold;" id="monthname">Month</label><br><br>
				<div id="date-buttons-container"></div><br><br>
				<form id="dateForm" method="POST" action="doctorAvailability.php">
					<input type="hidden" id="selectedDateInput" name="selectedDate">
					<input type="hidden" name="doctorid" value="${doctorid}">
				</form>

				<h3>Morning</h3><br>
				<div id="timeam-buttons-container"></div><br>
				<h3>Afternoon</h3><br>
				<div id="timepm-buttons-container"></div><br>

        `;

		document.getElementById('doctorname').textContent = sessionStorage.getItem('doctorname');
		document.getElementById('positionname').textContent = sessionStorage.getItem('positionname');
		document.getElementById('monthname').textContent = sessionStorage.getItem('currentMonthYear');
		
		const dateButtonContainer = document.getElementById('date-buttons-container');
		const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        for (let i = 0; i < daysOfWeek.length; i++) {
            const day = daysOfWeek[(currentDate.getDay() + i) % 7];
			const date = new Date(currentDate);
			date.setDate(date.getDate() + i);
			const shortDay = day.slice(0, 3);
			const dateParts = date.toLocaleDateString().split('/');
			const shortDate = `${dateParts[1]}`;
			const button = document.createElement('button');
			button.innerHTML = `${shortDay}<br>${shortDate}`;
			button.classList.add('dayBtn');
			button.setAttribute('data-date', date.toISOString().split('T')[0]);
			button.setAttribute('onclick', 'newappttime(this)');
			dateButtonContainer.appendChild(button);
		}
		generateTimeSlots(false, <?php echo json_encode($availableTimeSlots); ?>);
        /*
        const timeamButtonContainer = document.getElementById('timeam-buttons-container');
        const morningTimeSlots = generateTimeSlots(8, 12, 15);
        morningTimeSlots.forEach((timeSlot) => {
            const button = document.createElement('button');
            button.textContent = timeSlot;
            button.classList.add('timeBtn');
            button.setAttribute('onclick', 'selectTimeSlot(this)');
            timeamButtonContainer.appendChild(button);
        });
        
        const timepmButtonContainer = document.getElementById('timepm-buttons-container');
        const afternoonTimeSlots = generateTimeSlots(14, 20, 15);
        afternoonTimeSlots.forEach((timeSlot) => {
            const button = document.createElement('button');
            button.textContent = timeSlot;
            button.classList.add('timeBtn');
            button.setAttribute('onclick', 'selectTimeSlot(this)');
            timepmButtonContainer.appendChild(button);
        });
		*/
	}

	function attachDateButtonListeners() {
		document.querySelectorAll('.dayBtn').forEach((button) => {
			button.addEventListener('click', function () {
				document.querySelectorAll('.dayBtn').forEach((btn) => {
					btn.classList.remove('selected');
				});

				this.classList.add('selected');
				const selectedDate = this.getAttribute('data-date'); 

				document.getElementById('selectedDateInput').value = selectedDate;
				document.getElementById('dateForm').submit();
			});

			if (availableTimeSlots.length === 0) {
				generateTimeSlots(true);
			} else {
				generateTimeSlots(false, <?php echo json_encode($availableTimeSlots); ?>);  // Generate available time slots
			}
		});
	}


	function generateTimeSlots(noRows, availableTimeSlots) {
		const timeamButtonContainer = document.getElementById('timeam-buttons-container');
		const timepmButtonContainer = document.getElementById('timepm-buttons-container');
		timeamButtonContainer.innerHTML = '';
		timepmButtonContainer.innerHTML = '';

		const startHourAM = 8; // 8 am
		const endHourAM = 12;  // 12 pm
		const startHourPM = 14; // 2 pm
		const endHourPM = 20;  // 8 pm
		const intervalMinutes = 15;

		const currentDate = new Date(); // Current date and time
		const currentHour = currentDate.getHours();
		const currentMinute = currentDate.getMinutes();

		/*
		for (let hour = startHourAM; hour < endHourAM; hour++) {
			for (let minute = 0; minute < 60; minute += intervalMinutes) {
				const time = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
				const button = document.createElement('button');
				button.textContent = time;
				button.classList.add('timeBtn');
				button.setAttribute('onclick', 'selectTimeSlot(this)');

				if (noRows || !availableTimeSlots.includes(time)) {
					if (time >= '08:00' && time < '12:00') {
						timeamButtonContainer.appendChild(button);
					}
				}
			}
		}

		for (let hour = startHourPM; hour < endHourPM; hour++) {
			for (let minute = 0; minute < 60; minute += intervalMinutes) {
				const time = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
				const button = document.createElement('button');
				button.textContent = time;
				button.classList.add('timeBtn');
				button.setAttribute('onclick', 'selectTimeSlot(this)');

				if (noRows || !availableTimeSlots.includes(time)) {
					if (time >= '14:00' && time < '20:00') {
						timepmButtonContainer.appendChild(button);
					}
				}
			}
		}
		*/
		for (let hour = startHourAM; hour < endHourAM; hour++) {
			for (let minute = 0; minute < 60; minute += intervalMinutes) {
				const time = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;

				// Compare the time with the current time to filter out past time slots
				const timeParts = time.split(':');
				const timeHour = parseInt(timeParts[0], 10);
				const timeMinute = parseInt(timeParts[1], 10);

				if ((hour > currentHour || (hour === currentHour && minute >= currentMinute)) && (noRows || !availableTimeSlots.includes(time))) {
					if (time >= '08:00' && time < '12:00') {
						timeamButtonContainer.appendChild(createTimeSlotButton(time));
					}
				}
			}
		}

		for (let hour = startHourPM; hour < endHourPM; hour++) {
			for (let minute = 0; minute < 60; minute += intervalMinutes) {
				const time = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;

				// Compare the time with the current time to filter out past time slots
				const timeParts = time.split(':');
				const timeHour = parseInt(timeParts[0], 10);
				const timeMinute = parseInt(timeParts[1], 10);

				if ((hour > currentHour || (hour === currentHour && minute >= currentMinute)) && (noRows || !availableTimeSlots.includes(time))) {
					if (time >= '14:00' && time < '20:00') {
						timepmButtonContainer.appendChild(createTimeSlotButton(time));
					}
				}
			}
    	}

	}
	
	function createTimeSlotButton(time) {
		const button = document.createElement('button');
		button.textContent = time;
		button.classList.add('timeBtn');
		button.setAttribute('onclick', 'selectTimeSlot(this)');
		return button;
	}

	function newappttime(button) {
		button.classList.toggle('selected');
		const selectedDayBtn = document.querySelector('.dayBtn.selected');
		const selectedTimeBtn = document.querySelector('.timeBtn.selected');
		if (selectedDayBtn && selectedTimeBtn) {
			const apptdate = selectedDayBtn.textContent;
			const appttime = selectedTimeBtn.textContent;

			const date = new Date();
			date.setDate(
				date.getDate() + [...document.querySelectorAll('.dayBtn')].indexOf(selectedDayBtn)
			);
			const apptdatefull = date.toLocaleDateString();
			sessionStorage.setItem('apptdatefull', apptdatefull);
			sessionStorage.setItem('apptdate', apptdate);
			sessionStorage.setItem('appttime', appttime);
			//const doctor =  document.getElementById("doctorname").textContent;
			newapptcomment();
		}
	}


	//User: New Appt - Comment
	function newapptcomment() {
		const overlayContent = document.querySelector('#unewAppointment');

		let doctor, doctorname, positionname, imageSource, currentMonthYear, apptdate, appttime;
		doctor = sessionStorage.getItem('doctor');
		doctorname = sessionStorage.getItem('doctorname');
		positionname = sessionStorage.getItem('positionname');
		imageSource = 'CSS/' + doctor + '.jpg';
		currentMonthYear = sessionStorage.getItem('currentMonthYear');
		apptdate = sessionStorage.getItem('apptdate');
		appttime = sessionStorage.getItem('appttime');

		overlayStack.push(overlayContent.innerHTML);
		overlayContent.innerHTML = `

                <div class="header-container">
                    <img class="exitBtn header-container-left" id="backBtn" src="CSS/cancel.png" alt="back button" onclick="backPopup(unewAppointment);">  
                    <img class="exitBtn header-container-right" id="closeBtn" src="CSS/cancel.png" alt="close button" onclick="closePopup(unewAppointment);">                
                </div>
                <br>
                <div class="body-container">
                    <img class="doctBtn" id="drImage" src="${imageSource}" alt="drImage">
                    <div class="doctor-info">
                        <label style="font-size: 28px;" id="doctorname">Doctor Name</label><br>
                        <label style="font-size: 18px;" id="positionname">Position</label>
                    </div>
                </div>
                <div class="header-container">
                    <p style="font-size: 18px; font-weight: bold;">You selected: </p>
                    <select id='appttype' required>
                        <option value="" disabled selected hidden>Select Appointment Type</option>
                        <option value="Consultation">Consultation</option>
                        <option value="Medical Examination">Medical Examination</option>
                    </select>
                </div>
                <br><br>
                <div class="body-container">
                    <div class="label-container">
                        <label style="font-size: 18px; font-weight: bold;" id="currentMonthYear">Month Year</label><br>
                        <label class="confirmoption" id="apptdate">Appointment Day<br>Date</label><br>
                        <label class="confirmoption" id="appttime">Appointment Time</label>
                    </div>
                    <textarea rows="10" cols="30" class="commentbox" id='uapptcomment' placeholder="Comment(s)"></textarea>
                </div>
                <br><br>
                <button type='submit' id='submituAppt' onclick="checkrequired();">Confirm</button>
                <br><br>
                    
            `;

		document.getElementById('doctorname').textContent = doctorname;
		document.getElementById('positionname').textContent = positionname;
		document.getElementById('currentMonthYear').textContent = currentMonthYear;
		const formattedApptDate = apptdate.slice(0, 3) + '<br>' + apptdate.slice(3);
		document.getElementById('apptdate').innerHTML = formattedApptDate;
		document.getElementById('appttime').textContent = appttime;
	}

	function checkrequired(input) {
		const selectedApptType = document.getElementById('appttype').value;
		const apptcomment = document.getElementById('uapptcomment').value;

		if (selectedApptType === '') {
			popoutTwo(document.getElementById('appttype'), 'Please select the type of appointment.');
			return;
		} else {
			sessionStorage.setItem('appttype', selectedApptType);
			sessionStorage.setItem('apptcomment', apptcomment);
			confirmuAppt();
		}
	}

	function confirmuAppt() {
		const overlayContent = document.querySelector('#unewAppointment');
		let doctorname, appttype, apptdate, appttime, comment;
		doctorname = sessionStorage.getItem('doctorname');
		appttype = sessionStorage.getItem('appttype');
		apptdate = sessionStorage.getItem('apptdatefull');
		appttime = sessionStorage.getItem('appttime');
		apptcomment = sessionStorage.getItem('apptcomment');
		overlayStack.push(overlayContent.innerHTML);
		overlayContent.innerHTML = `

                <label class="headertextbox">Appointment Confirmed</label>
                <br><br><br><br>
                <div class="label-container-two">
                    <label id="doctorname">Doctor Name</label><br>
                    <label class="transparenttextbox" id="appttype">Appointment Type</label><br>
                    <label id="apptdate">Appointment Date<br>Day </label><br>
                    <label id="appttime">Appointment Time</label><br><br>
                    <p>Comment(s):</p><br>
                    <label id="apptcomment">Appointment Comment</label>
                </div>
                <br><br>
                <button type='submit' id='submituAppt' onclick="submituAppt();">Close</button>
            `;

		document.getElementById('doctorname').textContent = doctorname;
		document.getElementById('appttype').textContent = appttype;
		const formattedDate = formatDate(apptdate);
		document.getElementById('apptdate').innerHTML = formattedDate;
		document.getElementById('appttime').textContent = appttime;
		document.getElementById('apptcomment').textContent = apptcomment;
	}

</script>