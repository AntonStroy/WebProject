
// return True if an error was found; False if no errors were found 
function formHasErrors()
{
	var errorFlag = false;

	// Validate fields for input
	var requiredTextFields = ["firstName", "lastName", "login", "password", "confirmPassword", "phone", "email", "address", "city", "province", "postalCode"];
	for(var i = 0; i < requiredTextFields.length; i++)
	{
		var textField = document.getElementById(requiredTextFields[i]);
		if(!formFieldHasInput(textField))
		{
			// Display the appropriate error message
			document.getElementById(requiredTextFields[i] + "_error").style.display = "block";

			// Checks if there was another errors
			if(!errorFlag)
			{
				textField.focus();
			}

			// rise the error flag
			errorFlag = true;
		}
	}

	// Validate postal code
	var postalCheck = document.getElementById("postalCode");
	if(!validatePostal(postalCheck.value))
	{
		document.getElementById("postalformat_error").style.display = "block";

		if(!errorFlag)
		{
			postalCheck.focus();
			postalCheck.select();
		}
		
		errorFlag = true;
	}

	// Validate email fromat
	var emailCheck = document.getElementById("email");
	if(!validateEmail(emailCheck.value))
	{
		document.getElementById("emailformat_error").style.display = "block";
		
		if(!errorFlag)
		{
			emailCheck.focus();
			emailCheck.select();
		}
	
		errorFlag = true;
	}
		
	// Validate phone number
	var phoneNumberChecked = document.getElementById("phone");
	if(!validatePhone(phoneNumberChecked.value))
	{
		document.getElementById("phoneformat_error").style.display = "block";
	
		if(!errorFlag)
		{
			phoneNumberChecked.focus();
			phoneNumberChecked.select();
		}

		errorFlag = true;
	}

	return errorFlag;
}

// Removes white space from a string value. return  A string with leading and trailing white-space removed.
function trim(str) 
{
	// Uses a regex to remove spaces from a string.
	return str.replace(/^\s+|\s+$/g,"");
}

// Determines if a text field element has input. True if the field contains input; False if nothing entered
function formFieldHasInput(fieldElement)
{
	
	if(fieldElement.value == null || trim(fieldElement.value) == "" || fieldElement.value == 0)
	{
		return false;
	}

	return true;
}

// Validate phone number function
function validatePhone(phone)
{
	if(isNaN(phone) == false)
	{
		var phonePatern = /^(\d{10})$/;
		return phonePatern.test(phone);
	}	
}

// Validate postal function
function validatePostal(postal)
{
	var postalPatern = /^[A-Za-z]\d[A-Za-z][ -]?\d[A-Za-z]\d$/;
	return postalPatern.test(postal);

}

// Validate email function
function validateEmail(email) 
{
    var emailPatern = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    
    return emailPatern.test(String(email).toLowerCase());
}

// Handles the submit event of the registration form. Return True if no validation errors; False if the form has validation errors
function validate(e)
{
	// Hides all errors on the form
	hideErrors();
	
	// Determines if a form has errors
	if(formHasErrors())
	{	
		
		// Return false and prevent from submiting
		e.preventDefault();

		return false;
	}
	
	alert("successfully Updated");
	return true;
}

// Hides all of the error elements. 
function hideErrors()
{
	// Get am array of the error ids
	var errorFields = document.getElementsByClassName("error");

	for(var i = 0; i < errorFields.length; i++)
	{
		errorFields[i].style.display = "none";
	}
}

// Handles the reset event for the form.
function resetForm(e)
{
	// Confirm that the user wants to reset the form
	if ( confirm('Reset the form?') )
	{
		// Ensure all error fields are hidden
		hideErrors();
		
		// When using onReset="resetForm()" in markup, returning true will allow
		// the form to reset
		return true;
	}

	// Prevents the form from submitting
	e.preventDefault();
	
	// When using onReset="resetForm()" in markup, returning false would prevent
	// the form from resetting
	return false;	
}

function deleteForm(e)
{
	hideErrors();

	errorFlag = true;

	alert("successfully Deleted");
	return true;
}



// Handles the load event of the document
function load()
{
	// Add event listener for the form delete
	document.getElementById("Form").addEventListener("delete", deleteForm, false);
	// Add event listener for the form submit
	document.getElementById("Form").addEventListener("submit", validate, false);
	// Add event listener for the form reset
	document.getElementById("Form").addEventListener("reset", resetForm, false);

	hideErrors()
}	

// Add document load event listener
document.addEventListener("DOMContentLoaded", load);