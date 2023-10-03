//for both or send to API functions

// ===== BOTH: STYLE ========================================================================================================================================================
//Popout Message wif Version#1
function getParameterByName(name, url) {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, '\\$&');
	var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, ' '));
}

//Popout Version#1: Submission Prompt
function popoutOne() {
	var popup = document.createElement('div');
	popup.textContent = message;
	popup.style.position = 'fixed';
	popup.style.top = '10px';
	popup.style.left = '50%';
	popup.style.padding = '10px';
	popup.style.transform = 'translate(-50%, 0)';
	popup.style.borderRadius = '10px';
	popup.style.backgroundColor = '#17A7D3';
	popup.style.color = 'white';
	document.body.appendChild(popup);
	setTimeout(function () {
		document.body.removeChild(popup);
	}, 2000);
}

//Popout Version#2: Element Prompt
function popoutTwo(inputElement, message) {
	var popup = document.createElement('div');
	popup.textContent = message;
	popup.className = 'popout';

	var arrow = document.createElement('div');
	arrow.className = 'arrow-up';
	popup.appendChild(arrow);

	popup.style.position = 'absolute';
	var selectRect = inputElement.getBoundingClientRect();
	popup.style.top = selectRect.bottom + window.scrollY + 5 + 'px';
	popup.style.left = selectRect.left + window.scrollX + 'px';
	popup.style.padding = '10px';
	popup.style.border = '1px solid #1596c1';
	popup.style.borderRadius = '10px';
	popup.style.backgroundColor = '#15c1c1';
	popup.style.color = 'white';
	popup.style.zIndex = '1000';
	document.body.appendChild(popup);
	setTimeout(function () {
		document.body.removeChild(popup);
	}, 2000);
}

//FormatDay Version#1: en-Us -> DD Month YYYY
function formatDate(inputDate) {
	const dateParts = inputDate.split('/');
	const month = parseInt(dateParts[0]) - 1;
	const day = parseInt(dateParts[1]);
	const year = parseInt(dateParts[2]);

	const options = { day: 'numeric', month: 'long', year: 'numeric', weekday: 'long' };
	const formattedDate = new Date(year, month, day).toLocaleDateString('en-US', options);

	const datePartsFormatted = formattedDate.split(',');
	const dayOfWeek = datePartsFormatted[0];
	const [, mm, dd] = datePartsFormatted[1].split(' ');
	const daymonth = `${dd} ${mm}`;
	const dayMonthYear = daymonth + ' ' + datePartsFormatted[2];

	return `${dayMonthYear} <br> (${dayOfWeek})`;
}

/* Button Select
function toggleSelected(button) {
    button.classList.toggle('selected');
}
*/

// ===== BOTH: FUNCTION ========================================================================================================================================================
//LOGIN
function login() {
	var request = new XMLHttpRequest();
	var userinfo = new Object();

	userinfo.email = document.getElementById('username').value;
	userinfo.password = document.getElementById('password').value;

	request.open('POST', '/login', true);
	request.setRequestHeader('Content-Type', 'application/json');

	request.onload = function () {
		response = JSON.parse(request.responseText);

		if (response.length == 1) {
			sessionStorage.setItem('email', response[0].email);
			sessionStorage.setItem('lastname', response[0].lastname);
			sessionStorage.setItem('firstname', response[0].firstname);
			sessionStorage.setItem('birthday', response[0].birthday);
			sessionStorage.setItem('userId', response[0]._id);
			sessionStorage.setItem('usertype', response[0].usertype);
			userType = sessionStorage.getItem('usertype');
			if (userType === 'admin') {
				window.location = 'aMain.html';
			} else if (userType === 'user') {
				window.location = 'uMain.html';
			}
			alert('Login successfully');
		} else {
			if (response.message == 'Invalid password') {
				window.location = 'LandingPage.html?open=' + encodeURIComponent(userinfo.email);
				alert('Invalid password');
			}
			if (response.message == 'Invalid email') {
				window.location = 'LandingPage.html?open=landingLogin';
				alert('Invalid username');
			}
		}
	};
	request.send(JSON.stringify(userinfo));
}

//REGISTER
function register() {
	var request = new XMLHttpRequest();
	var adduserinfo = new Object();

	adduserinfo.addemail = document.getElementById('addemail').value;
	adduserinfo.addpassword = document.getElementById('addpassword').value;
	adduserinfo.addlastname = document.getElementById('addlastname').value.toUpperCase();
	adduserinfo.addfirstname = document.getElementById('addfirstname').value.toUpperCase();
	adduserinfo.addbirthday = document.getElementById('adddob').value;

	if (
		adduserinfo.addemail === '' ||
		adduserinfo.addpassword === '' ||
		adduserinfo.addlastname === '' ||
		adduserinfo.addfirstname === '' ||
		adduserinfo.addbirthday === ''
	) {
		return;
	}

	request.open('POST', '/addUser', true);
	request.setRequestHeader('Content-Type', 'application/json');

	request.onload = function () {
		response = JSON.parse(request.responseText);

		if (response.message === 'Added Successfully') {
			window.location = 'LandingPage.html?message=Account created successfully, please login';
		} else {
			if (response.message == 'Duplicate Entry') {
				alert('The email has been used. Press here to change password' + adduserinfo.addemail);
				//FORGOT PASSWORD + EMAIL
			}
		}
	};
	request.send(JSON.stringify(adduserinfo));
}

/* +: CHANGE PASSWORD */ //TBE: REGISTER

//Close Overlay
function closePopup(formDiv) {
	formDiv.style.display = 'none';
	document.body.classList.remove('overlay');

	var inputElements = formDiv.querySelectorAll('input');
	inputElements.forEach(function (input) {
		input.value = '';
	});
}

// ===== USER ========================================================================================================================================================
// User - Main: Get Appointments
function displayuAppt() {
	var request = new XMLHttpRequest();
	userId = sessionStorage.getItem('userId');

	request.open('GET', '/uMainAppt/' + userId, true);
	request.setRequestHeader('Content-Type', 'application/json');

	request.onload = function () {
		response = JSON.parse(request.responseText);

		for (var i = 0; i < response.length; i++) {
			var date = new Date(response[i].date);
			var today = new Date();
			date.setHours(0, 0, 0, 0);
			today.setHours(0, 0, 0, 0);
			var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
			var formattedDate = date.toLocaleDateString('en-GB', options);

			if (date.getTime() === today.getTime()) {
				tbodyuAppt = document.getElementById('uupApptTBody');
			} else {
				tbodyuAppt = document.getElementById('ushApptTBody');
			}
			tbodyuAppt.innerHTML +=
				'<tr><td colspan="1">' +
				i +
				'</td>' +
				'<td colspan="3">' +
				formattedDate +
				'</td>' +
				'<td colspan="3">' +
				response[i].time +
				'</td>' +
				'<td colspan="3">' +
				response[i].doctor +
				'</td>' +
				'<td colspan="5">' +
				response[i].appointmentType +
				'</td>' +
				'<td colspan="3" class="centered-t">' +
				'<button type="submit" style="margin-right: 20px;" id="editAppt" onclick="editAppt(' +
				response[i].apptId +
				')">Edit</button>' +
				'<button type="submit" id="cancelAppt" onclick="cancelAppt(' +
				response[i].apptId +
				')">Cancel</button>' +
				'</td>' +
				'</tr>';
		}
	};

	request.send();
}

//User - Main: Add Appointments
function submituAppt() {
	var request = new XMLHttpRequest();
	var unewappt = new Object();

	unewappt.doctorname = sessionStorage.getItem('doctorname');
	unewappt.appttype = sessionStorage.getItem('appttype');
	unewappt.apptdate = sessionStorage.getItem('apptdate');
	unewappt.appttime = sessionStorage.getItem('appttime');
	unewappt.apptcomment = sessionStorage.getItem('apptcomment');
	unewappt.patientname = sessionStorage.getItem('userId');

	request.open('POST', '/adduNewAppt', true);
	request.setRequestHeader('Content-Type', 'application/json');

	request.onload = function () {
		response = JSON.parse(request.responseText);

		if (response.message == 'Form is Submitted. Please close the page.') {
			alert('Form is created successfully.');
			sessionStorage.removeItem('doctorname');
			sessionStorage.removeItem('appttype');
			sessionStorage.removeItem('apptdate');
			sessionStorage.removeItem('appttime');
			sessionStorage.removeItem('apptcomment');
			closePopup(unewAppointment);
		}
	};

	request.send(JSON.stringify(unewappt));
}
