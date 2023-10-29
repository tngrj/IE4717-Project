let appointmentId;

// Function to open the confirmation modal
function openModal(id, date, startTime, doctor, appointmentType, status, comments) {
	appointmentId = id;
	document.getElementById('modalDate').textContent = date;
	document.getElementById('modalStartTime').textContent = startTime;
	document.getElementById('modalDoctor').textContent = doctor;
	document.getElementById('modalAppointmentType').textContent = appointmentType;
	document.getElementById('modalStatus').textContent = status;
	document.getElementById('modalComments').textContent = comments;
	const cancellationFormContainer = document.getElementById('cancellationFormContainer');
	cancellationFormContainer.classList.add('is-visible');
}

// Function to close the confirmation modal
function closeCancellationForm() {
	const cancellationFormContainer = document.getElementById('cancellationFormContainer');
	cancellationFormContainer.classList.remove('is-visible');
}

// Function to cancel the appointment
function cancelAppointment() {
	window.location.href = `cancelAppt.php?id=${appointmentId}`;
}
