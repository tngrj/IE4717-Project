// Prompt the user to confirm logout
function confirmLogout() {
	if (confirm('Are you sure you want to log out?')) {
		// If the user confirms, then trigger the logout process by navigating to the PHP script.
		window.location.href = 'logout.php';
	}
}

// Toggles Booking / Cancellation Modal
function toggleForm(formId) {
	const formContainer = document.getElementById(formId);
	formContainer.classList.toggle('is-visible');
}

// Close Popup
const closeButton = document.querySelector('#popup button');
const popup = document.querySelector('#popup');

if (closeButton && popup) {
	closeButton.addEventListener('click', () => {
		popup.style.display = 'none';
	});
}

// Get the current time and display the appropriate greeting
function setGreeting() {
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
}

// Attach the setGreeting function to the window.onload event
window.onload = function () {
	setGreeting();
};

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
