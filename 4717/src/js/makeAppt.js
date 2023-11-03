
	//User: New Appt - Back Button
	function backPopup(formDiv) {
		if (overlayStack.length > 0) {
			const previousContent = overlayStack.pop();
			const overlayContent = document.querySelector('#unewAppointment');
			overlayContent.innerHTML = previousContent;
		}
	}

    //User: New Appt - Exit Button
    function closePopup(formDiv) {
        formDiv.style.display = 'none';
         document.body.classList.remove('overlay');
    }


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
                    <img class="exitBtn header-container-right" id="closeBtn" src="../css/cancel.png" alt="close button" onclick="closePopup(unewAppointment);">                
                </div>
                <br>
                <img class="doctBtn" id="drTanNewAppt" src="../css/drTan.jpg" alt="Dr. Tan" onclick="newapptdoctor('drTan');">
                <img class="doctBtn" id="drNgNewAppt" src="../css/drTan.jpg" alt="Dr. Lim" onclick="newapptdoctor('drLim');">
                <img class="doctBtn" id="drKohNewAppt" src="../css/drTan.jpg" alt="Dr. Koh" onclick="newapptdoctor('drKoh');">
            `;
		//overlayStack.push(overlayContent.innerHTML);
	}

    
   