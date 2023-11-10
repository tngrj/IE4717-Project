// Prompt the user to confirm logout
function confirmLogout() {
	if (confirm('Are you sure you want to log out?')) {
		// If the user confirms, then trigger the logout process by navigating to the PHP script.
		window.location.href = 'logout.php';
	}
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
