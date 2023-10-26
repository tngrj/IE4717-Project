<!-- Week6
    1) Doct Appt Options: only able to select one time n one day, correct values key into mysql
    2) Doct Timing Database + Options
    3) Clean Up Script Part
-->

<!-- 1) Forms Validation & API(PHP) 
     2) Change the CSS; 
        - change & add image: logo, doctor, etc
        - popoutforms: back btn, font type, spacing&colour 
     3) Enhance the CSS
-->
<?php
session_start();

include 'patientAppt.php';

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
<!----------- HEAD --------------------------------------->

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Go Doc</title>

	<link rel="stylesheet" href="../css/style.css" />
	<link rel="stylesheet" href="../css/form.css" />
	<link rel="icon" href="../css/LogoIcon.png" />
	<script src="../js/cancelAppt.js"></script>
	<script src="../js/script.js"></script>
</head>
<!----------- HEAD --------------------------------------->

<!----------- BODY --------------------------------------->

<body onload="displayuAppt();">
	<!-----NAVBAR--------------------------------------------------------------->
	<nav class="navbar">
		<a href="uMain.php" class="home-link" id="homePage"><img src="../css/logo.png" alt="Home" width="50%" /></a>
		<div class="nav-links">
			<a href="doctorList.php"><img src="../css/doctors.png" title="List of Doctors" /></a>
			<a href="userProfile.php"><img src="../css/profile.png" title="Profile" /></a>
			<a href="#" id="logoutButton" onclick="confirmLogout();"><img src="../css/logout.png" title="Logout" width="80%" /></a>
		</div>
	</nav>
	<br />
	<!-----END: NAVBAR---------------------------------------------------------->

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
									<button onclick="openModal(
									'<?php echo $appointment['id']; ?>',
									'<?php echo $appointment['scheduled_date']; ?>',
									'<?php echo $appointment['scheduled_time']; ?>',
									'<?php echo $appointment['doctor_first_name'] . ' ' . $appointment['doctor_last_name']; ?>',
									'<?php echo $appointment['consultation_type']; ?>',
									'<?php echo $appointment['status']; ?>',
									'<?php echo $appointment['comments']; ?>'
									)">Cancel</button>
									<button>Reschedule</button>
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
									<button onclick="openModal(
									'<?php echo $appointment['id']; ?>',
									'<?php echo $appointment['scheduled_date']; ?>',
									'<?php echo $appointment['scheduled_time']; ?>',
									'<?php echo $appointment['doctor_first_name'] . ' ' . $appointment['doctor_last_name']; ?>',
									'<?php echo $appointment['consultation_type']; ?>',
									'<?php echo $appointment['status']; ?>',
									'<?php echo $appointment['comments']; ?>'
									)">Cancel</button>
									<button>Reschedule</button>
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
				<p>Appointment Details:</p>
				<p>Date: <span id="modalDate"></span></p>
				<p>Start Time: <span id="modalStartTime"></span></p>
				<p>Doctor: <span id="modalDoctor"></span></p>
				<p>Appointment Type: <span id="modalAppointmentType"></span></p>
				<p>Status: <span id="modalStatus"></span></p>
				<p>Comments: <span id="modalComments"></span></p>
			</div>
			<button onclick="cancelAppointment()">Confirm Cancellation</button>
		</div>
	</div>


</body>
<!----------- HEAD --------------------------------------->

<!----------- FOOTER ------------------------------------->
<br /><br /><br /><br /><br />
<footer class="footer">
	<div class="container">
		<p class="pull-left">&copy; Go Doc 2023</p>
	</div>
</footer>
<!----------- FOOTER ------------------------------------->

</html>

<script>
	function confirmLogout() {
		if (confirm('Are you sure you want to log out?')) {
			// If the user confirms, then trigger the logout process by navigating to the PHP script.
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

	//User: Book New Appointment
	//as we use image, when add new doctor, have to add html also. to solve this, instead of picture use dropbox as make it dynamic...
	function booknewappointment(formDiv) {
		formDiv.style.display = 'block';
		document.body.classList.add('overlay');
		const overlayContent = document.querySelector('#unewAppointment');
		overlayStack.push(overlayContent.innerHTML);
		overlayContent.innerHTML = `
                <div class="header-container">
                    <label style="font-size: 28px;">Choose your Doctor</label>
                    <img class="exitBtn header-container-right" id="closeBtn" src="CSS/cancel.png" alt="close button" onclick="closePopup(unewAppointment);">                
                </div>
                <br>
                <img class="doctBtn" id="drTanNewAppt" src="CSS/drTan.jpg" alt="Dr. Tan" onclick="newapptdoctor('drTan');">
                <img class="doctBtn" id="drNgNewAppt" src="CSS/drTan.jpg" alt="Dr. Lim" onclick="newapptdoctor('drLim');">
                <img class="doctBtn" id="drKohNewAppt" src="CSS/drTan.jpg" alt="Dr. Koh" onclick="newapptdoctor('drKoh');">
            `;
		//overlayStack.push(overlayContent.innerHTML);
	}

	//User: New Appt - Back Button
	function backPopup(formDiv) {
		if (overlayStack.length > 0) {
			const previousContent = overlayStack.pop();
			const overlayContent = document.querySelector('#unewAppointment');
			overlayContent.innerHTML = previousContent;
		}
	}

	/*TBD
        //User: New Appt - Exit Button
        function closePopup(formDiv) {
            formDiv.style.display = 'none';
            document.body.classList.remove('overlay');
        }
        */

	//User: New Appt - Doctor
	function newapptdoctor(doctor) {
		const overlayContent = document.querySelector('#unewAppointment');

		sessionStorage.setItem('doctor', doctor);

		let doctorname, positionname, imageSource;
		//replace it if making dynamic: doctor, doctorname, positionname, picture, ids?
		if (doctor === 'drTan') {
			sessionStorage.setItem('doctorname', 'Dr Tan Wee Lian');
			sessionStorage.setItem('positionname', 'Family Physician');
		} else if (doctor === 'drLim') {
			sessionStorage.setItem('doctorname', 'Dr Lim Koon Meng');
			sessionStorage.setItem('positionname', 'Family Physician');
		} else if (doctor === 'drKoh') {
			sessionStorage.setItem('doctorname', 'Dr Koh Wee Beng');
			sessionStorage.setItem('positionname', 'Dentist');
		}
		imageSource = 'css/' + doctor + '.jpg';

		const currentDate = new Date();
		const options = {
			year: 'numeric',
			month: 'long'
		};
		const currentMonthYear = currentDate.toLocaleDateString('en-US', options);
		sessionStorage.setItem('currentMonthYear', currentMonthYear);

		//1) only one day/time is selected
		//2) day n date not match
		//change to calender?
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
                <br>
                <label style="font-size: 22px; font-weight: bold;" id="monthname">Month</label><br><br>
                <div id="buttons-container"></div><br><br>
                <h3>Morning</h3><br>
//based on date selected, get time, use for loop to generate?<br><br>
                <h3>Afternoon</h3><br>
                <button class="timeBtn" value="1500" onclick="newappttime(this)">1500</button>
                <button class="timeBtn" value="1700" onclick="newappttime(this)">1700</button>
//based on date selected, get time, use for loop to generate?<br><br>
            `;

		document.getElementById('doctorname').textContent = sessionStorage.getItem('doctorname');
		document.getElementById('positionname').textContent =
			sessionStorage.getItem('positionname');
		document.getElementById('monthname').textContent =
			sessionStorage.getItem('currentMonthYear');

		const daysOfWeek = [
			'Sunday',
			'Monday',
			'Tuesday',
			'Wednesday',
			'Thursday',
			'Friday',
			'Saturday',
		];
		const buttonContainer = document.getElementById('buttons-container');
		for (let i = 0; i < daysOfWeek.length; i++) {
			const day = daysOfWeek[i];
			const date = new Date();
			date.setDate(date.getDate() + i);
			const shortDay = day.slice(0, 3);
			const dateParts = date.toLocaleDateString().split('/');
			const shortDate = `${dateParts[1]}`;
			const button = document.createElement('button');
			button.innerHTML = `${shortDay}<br>${shortDate}`;
			button.classList.add('dayBtn');
			button.setAttribute('onclick', 'newappttime(this)');
			buttonContainer.appendChild(button);
		}
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

	/*TBD: User - Main: Add Appointments
        function submituAppt() {

            var request = new XMLHttpRequest();
            var unewappt = new Object();

            unewappt.doctorname = sessionStorage.getItem("doctorname");
            unewappt.appttype = sessionStorage.getItem("appttype");
            unewappt.apptdate = sessionStorage.getItem("apptdate");
            unewappt.appttime = sessionStorage.getItem("appttime");
            unewappt.apptcomment = sessionStorage.getItem("apptcomment");
            unewappt.patientname = sessionStorage.getItem("userId");

            request.open("POST", "/adduNewAppt", true);
            request.setRequestHeader("Content-Type", "application/json");

            request.onload = function () {
                response = JSON.parse(request.responseText);

                    if (response.message == "Form is Submitted. Please close the page.") {
                        alert("Form is created successfully.");
                        sessionStorage.removeItem("doctorname");
                        sessionStorage.removeItem("appttype");
                        sessionStorage.removeItem("apptdate");
                        sessionStorage.removeItem("appttime");
                        sessionStorage.removeItem("apptcomment");
                        closePopup(unewAppointment);
                    }
            };

            request.send(JSON.stringify(unewappt));
        }
        */

	/*TBE
        //Edit Appt
        function viewCFasmForm(asmId) {
            window.location.href = "aCFasm.html?view_id=" + asmId;
            getCFasmForm(asmId);
        }  

       
        */
</script>