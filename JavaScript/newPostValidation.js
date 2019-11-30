
// return True if an error was found; False if no errors were found 
function formHasErrors()
{
	var errorFlag = false;

	// Validate fields for input
	var requiredTextFields = ["categoryId", "itemName", "description"];
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

	// Validate if post option selected 
	var postOption = ["buy", "sell"];
	var postOptionChecked = false;
	for(var i = 0; i < postOption.length; i++)
	{
		if(document.getElementById(postOption[i]).checked)
		{
			postOptionChecked = true;
		}
	}

	// If no post option selected
	if(!postOptionChecked)
	{
		//show the error if card not selected 
		document.getElementById("postOption_error").style.display = "block";
	
		if(!errorFlag)
		{
			document.getElementById("buy").focus();
		}
		
		// Raise the error flag indicating a validation error
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

// Handles the submit event of the new post form. Return True if no validation errors; False if the form has validation errors
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

	// Prevents the form from resetting
	e.preventDefault();
	
	// When using onReset="resetForm()" in markup, returning false would prevent
	// the form from resetting
	return false;	
}

// Handles the load event of the document
function load()
{
	// Add event listener for the form submit
	document.getElementById("Form").addEventListener("submit", validate, false);
	// Add event listener for the form reset
	document.getElementById("Form").addEventListener("reset", resetForm, false);

	hideErrors()
}	

// Add document load event listener
document.addEventListener("DOMContentLoaded", load);