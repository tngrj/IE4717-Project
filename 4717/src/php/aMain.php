<!-- 1) Change the Logo 
     2) Edit Table
     3) Remove all Bootstrap
-->

<?php
session_start();

include 'doctorAppt.php';

// Check if the user is logged in as a patient
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
<!----------------------------- HEAD --------------------------------------->

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Go Doc</title>

	<link rel="stylesheet" href="../css/style.css" />
	<link rel="stylesheet" href="../css/form.css" />
	<link rel="icon" href="../css/LogoIcon.png" />
	<script src="../js/script.js"></script>
</head>
<!-- --------------------------- HEAD ------------------------------------- -->

<!-- --------------------------- BODY ------------------------------------- -->

<body>
	<!-----NAVBAR--------------------------------------------------------------->
	<nav class="navbar">
		<a href="aMain.php" class="home-link" id="homePage"><img src="../css/LogoName.png" alt="Home" width="130" height="35" /></a>
		<div class="nav-links">
			<a href="adminCalendar.html" id="userCalendarPage">Calendar</a>
			<a href="adminProfile.html" id="userProfilePage">Profile</a>
			<a href="#" id="logoutButton" onclick="confirmLogout();">Logout</a>
		</div>
	</nav>

	<br />
	<!-----END: NAVBAR---------------------------------------------------------->

	<div class="adminMain-body">
		<div class="appointment-hero">
			<div class="appointment-card">
				<h4 id="uMainTime"></h4>
				<h2><?php echo $doctor_name ?></h2>
			</div>
		</div>

		<div class="appointment-modal">
			<div id="appointmentTableContainer">
				<table class="apptTable">
					<caption>Scheduled Appointment(s)</caption>
					<thead>
						<tr>
							<th>No.</th>
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
						foreach ($other_appointments as $appointment) {
						?>
							<tr>
								<td><?php echo $appointment['id']; ?></td>
								<td><?php echo $appointment['scheduled_date']; ?></td>
								<td><?php echo $appointment['scheduled_time']; ?></td>
								<td><?php echo $appointment['consultation_type']; ?></td>
								<td><?php echo $appointment['status']; ?></td>
								<td><?php echo $appointment['comments']; ?></td>
								<td>
									<button onclick="openModal(
									'<?php echo $appointment['id']; ?>',
									'<?php echo $appointment['scheduled_date']; ?>',
									'<?php echo $appointment['scheduled_time']; ?>',
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


			<div id="anewAppointment" class="hidden">
				<!-- <div class="header-container">
                <label style="font-size: 28px;">Choose your Doctor</label>
                <img class="exitBtn" id="closeBtn" src="CSS/cancel.png" alt="close button" onclick="closePopup(unewAppointment);">                
            </div>
            <br>
                //<button class="doctBtn" id="drTanNewAppt" onclick="newappt('drTan');">Dr. Tan</button>
                <img class="doctBtn" id="drTanNewAppt" src="CSS/drTan.jpg" alt="Dr. Tan" onclick="newappt('drTan');">
                <img class="doctBtn" id="drNgNewAppt" src="CSS/drTan.jpg" alt="Dr. Ng" onclick="newappt('drNg');">
                <img class="doctBtn" id="drKohNewAppt" src="CSS/drTan.jpg" alt="Dr. Koh" onclick="newappt('drKoh');">
                //<button id="closeBtn" onclick="closePopup(newappointment);">Cancel</button> -->
				<br /><br />
			</div>
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
<!-- --------------------------- BODY --------------------------------------- -->

<!-- ------------------------------- FOOTER ----------------------------------- -->
<br /><br /><br /><br /><br />
<footer class="footer">
	<div class="container">
		<p class="pull-left">&copy; Go Doc 2023</p>
	</div>
</footer>
<!-- --------------------------- FOOTER ------------------------------------- -->

</html>