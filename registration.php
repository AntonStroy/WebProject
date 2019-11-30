<?php
/**********************************
 *  Name: Anton Stroy             *
 *  Course: WEBD-2006 (186289)    *
 *  Date: 23/10/2019              *
 *  Purpose:                      *
 **********************************/

	// using connection.php file to connect to the data base.
  include 'connection.php';
  $errorFlag = False;

	if(isset($_POST['command']))
  {

		if($_POST['password'] != $_POST['confirmPassword'])
		{
			$errorFlag = True;
		}
		
		$admin = 0;

		if(!$errorFlag)
		{
    	// Sanitize user input to escape HTML entities and filter out dangerous characters.
    	$firstName  = filter_input(INPUT_POST, 'firstName',  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    	$lastName   = filter_input(INPUT_POST, 'lastName',   FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    	$login      = filter_input(INPUT_POST, 'login',      FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    	$password   = filter_input(INPUT_POST, 'password',   FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    	$phone      = filter_input(INPUT_POST, 'phone',      FILTER_SANITIZE_NUMBER_INT);
    	$email      = filter_input(INPUT_POST, 'email',      FILTER_SANITIZE_EMAIL);
    	$address    = filter_input(INPUT_POST, 'address',    FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    	$city       = filter_input(INPUT_POST, 'city',       FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    	$province   = filter_input(INPUT_POST, 'province', 	 FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    	$postalCode = filter_input(INPUT_POST, 'postalCode', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    	$saltNpaper = password_hash($password, PASSWORD_DEFAULT);

    	$query = "INSERT INTO user (FIRSTNAME, LASTNAME, LOGIN, PASSWORD, PHONENUM, EMAIL, ADDRESS, CITY, PROVINCE, POSTALCODE, ADMIN) values (:firstName, :lastName, :login, :password, :phone, :email, :address, :city, :province, :postalCode, :admin)"; 
    	 
			$statement = $db->prepare($query);
            
    	$statement->bindValue(':firstName', $firstName);
    	$statement->bindValue(':lastName', $lastName);
    	$statement->bindValue(':login', $login);        
    	$statement->bindValue(':password', $saltNpaper);
    	$statement->bindValue(':phone', $phone);
    	$statement->bindValue(':email', $email);
    	$statement->bindValue(':address', $address);        
    	$statement->bindValue(':city', $city);
    	$statement->bindValue(':province', $province);
    	$statement->bindValue(':postalCode', $postalCode);
    	$statement->bindValue(':admin', $admin);  
        
    	// Execution of the binds
    	$statement->execute();
			// Determine the last autoincremented number
   		$insert_id = $db->lastInsertId();
      
    	// return to index page 
   		header('Location: index.php');
    	exit;
  	}
  }
?>
		
<!DOCTYPE html>
<html lang="en">
	<head>
    <meta charset="UTF-8">
    <title>SoldOut Sell all your staff</title>
    <link rel="stylesheet" type="text/css" href="css/post.css">
    <script src="JavaScript/registrationValidation.js" type="text/javascript"></script>
	</head>

	<body>
    <div id="wrapper">
      <div id="header">
        <h1>S o l d   O u t</h1>
        <h3>Best Canadian online classified advertising service</h3>
      </div>

      <div id="logoBox">
        <a href="index.php"><img src="images/Sold_Out.png" alt="logo sold out" id="logo" ></a>
      </div>

      <div id="leftSide">
       	<img src="images/carAdd.jpg" alt="car Advertisement" >
      </div> 
      
      <div id="topBar">
       	<a href="index.php">Main</a>  
        <button type="submit" id="submit" form="Form" name="command" value="Register" class="buttonStyle">Register</button><button type="reset"  id="reset" form="Form" name="reset" class="buttonStyle">Reset</button>      		     
      </div>

			<div id="content">
				<form id="Form" action="Registration.php" method="POST" enctype="multipart/form-data">
				<h2>New User Registration</h2>
					<?php if($errorFlag): ?>
						<p>Wrong Password</p>	
					<?php endif ?>

					<ul>
						<li>
							<label for="firstName">First name</label>
							<input type="text" name="firstName" id="firstName" />
							<p class="personalError error" id="firstName_error">* Required field</p>
						</li>
							
						<li>
							<label for="lastName">Last name</label>
							<input type="text" name="lastName" id="lastName" />
							<p class="personalError error" id="lastName_error">* Required field</p>
						</li>

						<li>
							<label for="login">Login</label>
							<input type="text" name="login" id="login" />
							<p class="personalError error" id="login_error">* Required field</p>
						</li>
							
						<li>
							<label for="password">Password</label>
							<input type="text" name="password" id="password" />
							<p class="personalError error" id="password_error">* Required field</p>
						</li>

						<li>
							<label for="confirmPassword">Confirm Password</label>
							<input type="text" name="confirmPassword" id="confirmPassword" />
							<p class="personalError error" id="confirmPassword_error">* Required field</p>
						</li>

						<li>
  						<label for="phone">Phone Number</label>
  						<input type="tel" name="phone" id="phone" />
  						<p class="personalError error" id="phone_error">* Required field</p>
  						<p class="personalError error" id="phoneformat_error">* Invalid phone number</p>
  					</li>
  							
  					<li>
  						<label for="email">Email</label>
  						<input type="text" name="email" id="email" placeholder="user@gimal.com" />
  						<p class="personalError error" id="email_error">* Required field</p>
  						<p class="personalError error" id="emailformat_error">* Invalid email address</p>
 						</li>

  					<li>
							<label for="address">Address</label>
							<input id="address" name="address" type="text" />
							<p class="personalError error" id="address_error">* Required field</p>
						</li>
						
						<li>
							<label for="city">City</label>
							<input id="city" name="city" type="text" />
							<p class="personalError error" id="city_error">* Required field</p>
						</li>
							
						<li>
							<label for="province">Province</label>
							<select id="province" name="province">
								<option value="0">Province</option>
								<option value="AB">Alberta</option>
								<option value="BC">British Columbia</option>
								<option value="MB">Manitoba</option>
								<option value="NB">New Brunswick</option>
								<option value="NL">Newfoundland</option>
								<option value="NS">Nova Scotia</option>
								<option value="ON">Ontario</option>
								<option value="PE">Prince Edward Island</option>
								<option value="QC">Quebec</option>
								<option value="SK">Saskatchewan</option>
								<option value="NT">Northwest Territories</option>
								<option value="NU">Nunavut</option>
								<option value="YT">Yukon</option>
							</select>
							<p class="personalError error" id="province_error">* Required field</p>
						</li>
						
						<li>
							<label for="postalCode">Postal Code</label>
							<input id="postalCode" name="postalCode" type="text" />
							<p class="personalError error" id="postalCode_error">* Required field</p>
							<p class="personalError error" id="postalformat_error">* Invalid postal code</p>
						</li>					
					</ul>
				</form>
      </div>

      <div id="rightSide">
        <img src="images/wine.jpg" alt="Wine Advertisement" >
      </div>
	
      <div id="footer">
        <ul>
          <li><a href="#">Terms of Use</a></li>
          <li><a href="#">Privacy Policy</a></li>
       		<li><a href="#">Posting Policy</a></li>
          <li><a href="#">Support</a></li>
        </ul>
        
        <ul>
          <li><a href="#">About</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Member Benefits</a></li>
          <li><a href="#">Advertise on SoldOut</a></li>
       	</ul>
        <p>copyright &copy; all rights reserved</p>
      </div>
    </div>
	</body>
</html>