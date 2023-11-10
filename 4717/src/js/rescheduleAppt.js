// Toggle the reschedule data when a reschedule button is clicked
function toggleRescheduleData(appointmentId, doctorFullName) {
	const rescheduleFormContainer = document.getElementById('rescheduleFormContainer');
	rescheduleFormContainer.classList.add('is-visible');

	// Find the doctor in the array based on the provided doctor full name
	const selectedDoctor = doctorDataJS.find((doctor) => {
		const fullName = doctor.doctorName;
		return fullName === doctorFullName;
	});

	// Clear previous data from rescheduleDaysOfWeek and rescheduleTimeList
	const rescheduleDaysOfWeekContainer = document.getElementById('rescheduleDaysOfWeek');
	const rescheduleTimeCardContainer = document.getElementById('rescheduleTimeList');
	rescheduleDaysOfWeekContainer.innerHTML = '';
	rescheduleTimeCardContainer.innerHTML = '';

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
			day: '2-digit',
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
				year: 'numeric',
			};
			const formattedDate = selectedDate.toLocaleDateString('en-UK', options).replace(/ /g, '-');
			const appointments = selectedDoctor.appointments;

			// Extract the time slots for the selected day
			const selectedAppointments = appointments.filter(
				(appointment) => appointment.scheduledDate === formattedDate
			);

			// Create an array of booked time slots for the selected day
			const bookedTimeSlots = selectedAppointments.map((appointment) => appointment.scheduledTime);

			// Loop through time slots and disable booked slots
			rescheduleTimeCardContainer.childNodes.forEach((slot) => {
				let slotTime = slot.textContent.replace(' AM', '').replace(' PM', '');
				const isPM = slot.textContent.includes('PM');

				const [hour, minute] = slotTime.split(':').map((str) => parseInt(str));
				const adjustedHour = isPM && hour !== 12 ? hour + 12 : !isPM && hour === 12 ? 0 : hour;

				slotTime = `${adjustedHour.toString().padStart(2, '0')}:${minute
					.toString()
					.padStart(2, '0')}`;

				slot.disabled = bookedTimeSlots.includes(slotTime);
				slot.classList.toggle('booked', slot.disabled);
			});
		});

		rescheduleDaysOfWeekContainer.appendChild(dayCard);
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

		rescheduleTimeCardContainer.appendChild(timeCard);
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
		// Opens the appointment details form
		const appointmentDetail = (document.querySelector('.appointment-details').style.display =
			'flex');
	}

	// Assuming you have a similar event listener for days
	rescheduleDaysOfWeekContainer.addEventListener('click', (event) => {
		if (event.target.classList.contains('day-card')) {
			const selectedDay = event.target.textContent;
			const formattedDate = formatDate(new Date(), selectedDay);
			document.getElementById('rescheduleDate').value = formattedDate;
		}
	});

	// Assuming you have a similar event listener for times
	rescheduleTimeCardContainer.addEventListener('click', (event) => {
		if (event.target.classList.contains('time-card')) {
			const selectedTime = event.target.textContent;
			const formattedTime = formatTime(selectedTime);
			document.getElementById('rescheduleTime').value = formattedTime;
		}
	});

	// Update the appointment ID input field
	document.getElementById('rescheduleApptId').value = parseInt(appointmentId, 10); // Ensure the base is 10
}
