function validateEmail() {
	var emailInput = document.getElementById('emailInput');
	var emailError = document.getElementById('emailError');

	// Define individual regex components
	var usernameRegex = /^[a-zA-Z0-9._-]+/;
	var atSymbolRegex = /@/;

	var email = emailInput.value;

	if (!usernameRegex.test(email)) {
		emailError.textContent = 'Invalid email format. The username is invalid.';
		emailError.style.display = 'block';
		return false; // Prevent form submission
	} else if (!atSymbolRegex.test(email)) {
		emailError.textContent = 'Invalid email format. Missing the "@" symbol.';
		emailError.style.display = 'block';
		return false; // Prevent form submission
	} else {
		var parts = email.split('@');

		// Check for numbers immediately after '@'
		if (/[0-9]/.test(parts[1][0])) {
			emailError.textContent =
				'Invalid email format. No numbers are allowed immediately after "@" symbol.';
			emailError.style.display = 'block';
			return false; // Prevent form submission
		} else {
			// Further break down the domain
			var domainParts = parts[1].split('.');

			if (domainParts.length < 2 || domainParts.length > 4) {
				emailError.textContent =
					'Invalid email format. Domain extensions must be between 2 and 4 parts.';
				emailError.style.display = 'block';
				return false; // Prevent form submission
			} else if (
				domainParts.some((part) => !/^[a-zA-Z0-9-]+$/.test(part)) ||
				!/^[a-zA-Z]+$/.test(domainParts[domainParts.length - 1])
			) {
				emailError.textContent =
					'Invalid email format. Domain extensions must contain only letters, numbers, and hyphens, and the last extension must contain only letters.';
				emailError.style.display = 'block';
				return false; // Prevent form submission
			} else {
				if (domainParts.length === 2) {
					if (domainParts[1].length < 2) {
						emailError.textContent = 'Last extension needs to have 2-3 characters.';
						emailError.style.display = 'block';
						return false; // Prevent form submission
					} else {
						emailError.style.display = 'none'; // Clear error message
					}
				}
				if (domainParts.length === 3) {
					if (domainParts[2].length < 2 || domainParts[2].length > 3) {
						emailError.textContent = 'Last extension needs to have 2-3 characters.';
						emailError.style.display = 'block';
						return false; // Prevent form submission
					} else {
						emailError.style.display = 'none'; // Clear error message
					}
				}
				if (domainParts.length === 4) {
					if (domainParts[3].length < 2 || domainParts[3].length > 3) {
						emailError.textContent = 'Last extension needs to have 2-3 characters.';
						emailError.style.display = 'block';
						return false; // Prevent form submission
					} else {
						emailError.style.display = 'none'; // Clear error message
					}
				}
				if (domainParts.length > 4) {
					emailError.textContent = 'Too many extensions. 4 max.';
					emailError.style.display = 'block';
					return false; // Prevent form submission
				}
			}
		}
	}

	// If the function has reached this point, the email is valid, and the form can be submitted.
	return true;
}
