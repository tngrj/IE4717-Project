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
					const formattedDate = selectedDate
						.toLocaleDateString('en-UK', options)
						.replace(/ /g, '-');
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

						slotTime = `${adjustedHour.toString().padStart(2, '0')}:${minute
							.toString()
							.padStart(2, '0')}`;

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
				// Opens the appointment details form
				const appointmentDetail = (document.querySelector('.appointment-details').style.display =
					'flex');
			}
		}
	}
}
