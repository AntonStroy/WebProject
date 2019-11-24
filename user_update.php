<?php
/**********************************
 *  Name: Anton Stroy             *
 *  Course: WEBD-2006 (186289)    *
 *  Date: 23/10/2019              *
 *  Purpose:                      *
 **********************************/


// Using connection.php file to connect to the data base.
include 'connection.php';

//------------------------SELECT BLOCK----------------------------------------
	if(isset($_GET['userId']))
	{

		// function for Validation of the input id.
		function valid_user_id() 
  		{
    		return filter_input(INPUT_GET, 'userId', FILTER_VALIDATE_INT);
  		}

  		// If validation of input id fails return to the index page
  		if(!valid_user_id())
  		{
    		header('Location: index.php');
    		exit;
  		}

  	}
  		// Sanitize the id that comes with get method from index page.
  		$userId = filter_input(INPUT_GET, 'userId', FILTER_SANITIZE_NUMBER_INT);
	
  		// Build a query using ":id" as a placeholder parameter.
  		$query = "SELECT * FROM user WHERE USERID = :userId";  
  		$statement = $db->prepare($query);
  		$statement->bindValue(':userId', $userId, PDO::PARAM_INT);
  		$statement->execute(); 
    
  		// Call the date from the database and input it into the variable.
  		$userInfo = $statement->fetchAll();

  		//var_dump($userInfo);
	
//--------------------------UPDATE BLOCK--------------------------------------------------
    $errorFlag = False;
    $update_flag = False;
    $delete_flag = False;

    if(isset($_POST['command']))
    {

		// Sanitize user input to escape HTML entities and filter out dangerous characters.
    		$userId     = filter_input(INPUT_POST, 'userId',     FILTER_SANITIZE_NUMBER_INT);
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
    		$admin = 0;

		if($_POST['password'] != $_POST['confirmPassword'])
		{
			$errorFlag = True;
		}

		

		if($_POST['command'] === "Update")
		{
			if(!$errorFlag)
			{
    			$query = "UPDATE user SET FIRSTNAME = :firstName, LASTNAME = :lastName, LOGIN = :login, PASSWORD = :password, PHONENUM = :phone, EMAIL = :email, ADDRESS = :address, CITY = :city, PROVINCE = :province, POSTALCODE = :postalCode, ADMIN = :admin WHERE USERID = :userId";		
					$update_flag = True;
			}

			
    	}

		if($_POST['command'] === "Delete")
		{
			$query = "DELETE FROM user WHERE USERID = :userId";
            $delete_flag = True; 
		}


			// Preparing query for database and bind the values
			$statement = $db->prepare($query);
            
        	// If Update is used the all values required binding
        	if($update_flag)
        	{
            	$statement->bindValue(':firstName', $firstName);
            	$statement->bindValue(':lastName', $lastName);
           	 	$statement->bindValue(':login', $login);        
            	$statement->bindValue(':password', $password);
            	$statement->bindValue(':phone', $phone);
            	$statement->bindValue(':email', $email);
            	$statement->bindValue(':address', $address);        
            	$statement->bindValue(':city', $city);
            	$statement->bindValue(':province', $province);
            	$statement->bindValue(':postalCode', $postalCode);
            	$statement->bindValue(':admin', $admin);
			}
            
            if($update_flag || $delete_flag)
            {
            	$statement->bindValue(':userId', $userId, PDO::PARAM_INT);
    			$statement->execute();  
        	}
    		
    		
      
    		if(!$errorFlag)
    		{
    			header('Location: user_configuration.php');
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
        		<img src="images/Sold_Out.png" alt="logo sold out" height="150px" width="150px" >
        	</div>
        	
        	
        	<div id="topBar">
                <a href="index.php">Main</a>
                <a href="user_configuration.php">Users List</a> 
                <button type="submit" form="Form" name="command" value="Update">Update</button>
                <button type="submit" form="Form" name="command" value="Delete">Delete</button> 
                <button type="reset"  id="reset" form="Form" name="reset" class="buttonStyle">Reset</button>   		     
        	</div>
			
			<div id="content">
				<form id="Form" action="user_update.php" method="POST" enctype="multipart/form-data">			 
					<legend>User details Configuration</legend>
					<?php if($errorFlag): ?>
						<p>Wrong Password</p>	
					<?php endif ?>

					<ul>
					<?php foreach($userInfo as $info): ?>
						<input type="hidden" name="userId" value="<?= $info['USERID'] ?>" />
						<li>
							<label for="firstName">First name</label>
							<input type="text" name="firstName" id="firstName" value="<?= $info['FIRSTNAME'] ?>"/>
							<p class="personalError error" id="firstName_error">* Required field</p>
						</li>
							
						<li>
							<label for="lastName">Last name</label>
							<input type="text" name="lastName" id="lastName" value="<?= $info['LASTNAME'] ?>"/>
							<p class="personalError error" id="lastName_error">* Required field</p>
						</li>
   

						<li>
							<label for="login">Login</label>
							<input type="text" name="login" id="login" value="<?= $info['LOGIN'] ?>"/>
							<p class="personalError error" id="login_error">* Required field</p>
						</li>
							
						<li>
							<label for="password">Password</label>
							<input type="text" name="password" id="password" value="<?= $info['PASSWORD'] ?>"/>
							<p class="personalError error" id="password_error">* Required field</p>
						</li>

						<li>
							<label for="confirmPassword">Confirm Password</label>
							<input type="text" name="confirmPassword" id="confirmPassword" value="<?= $info['PASSWORD'] ?>"/>
							<p class="personalError error" id="confirmPassword_error">* Required field</p>
						</li>

						<li>
  							<label for="phone">Phone Number</label>
  							<input type="tel" name="phone" id="phone" value="<?= $info['PHONENUM'] ?>"/>
  							<p class="personalError error" id="phone_error">* Required field</p>
  							<p class="personalError error" id="phoneformat_error">* Invalid phone number</p>
  						</li>
  							
  						<li>
  							<label for="email">Email</label>
  							<input type="text" name="email" id="email" placeholder="user@gimal.com" value="<?= $info['EMAIL'] ?>"/>
  							<p class="personalError error" id="email_error">* Required field</p>
  							<p class="personalError error" id="emailformat_error">* Invalid email address</p>
  						</li>

  						<li>
							<label for="address">Address</label>
							<input id="address" name="address" type="text" value="<?= $info['ADDRESS'] ?>"/>
							<p class="personalError error" id="address_error">* Required field</p>
						</li>
						
						<li>
							<label for="city">City</label>
							<input id="city" name="city" type="text" value="<?= $info['CITY'] ?>"/>
							<p class="personalError error" id="city_error">* Required field</p>
						</li>
							
						<li>
						<label for="province">Province</label>
						<select id="province" name="province">
							<option value="0"  <?php if($info['PROVINCE'] == "0" ) echo 'selected="selected"'; ?>>Province</option>
							<option value="AB" <?php if($info['PROVINCE'] == "AB") echo 'selected="selected"'; ?>>Alberta</option>
							<option value="BC" <?php if($info['PROVINCE'] == "BC") echo 'selected="selected"'; ?>>British Columbia</option>
							<option value="MB" <?php if($info['PROVINCE'] == "MB") echo 'selected="selected"'; ?>>Manitoba</option>
							<option value="NB" <?php if($info['PROVINCE'] == "NB") echo 'selected="selected"'; ?>>New Brunswick</option>
							<option value="NL" <?php if($info['PROVINCE'] == "NL") echo 'selected="selected"'; ?>>Newfoundland</option>
							<option value="NS" <?php if($info['PROVINCE'] == "NS") echo 'selected="selected"'; ?>>Nova Scotia</option>
							<option value="ON" <?php if($info['PROVINCE'] == "ON") echo 'selected="selected"'; ?>>Ontario</option>
							<option value="PE" <?php if($info['PROVINCE'] == "PE") echo 'selected="selected"'; ?>>Prince Edward Island</option>
							<option value="QC" <?php if($info['PROVINCE'] == "QC") echo 'selected="selected"'; ?>>Quebec</option>
							<option value="SK" <?php if($info['PROVINCE'] == "SK") echo 'selected="selected"'; ?>>Saskatchewan</option>
							<option value="NT" <?php if($info['PROVINCE'] == "NT") echo 'selected="selected"'; ?>>Northwest Territories</option>
							<option value="NU" <?php if($info['PROVINCE'] == "NU") echo 'selected="selected"'; ?>>Nunavut</option>
							<option value="YT" <?php if($info['PROVINCE'] == "YT") echo 'selected="selected"'; ?>>Yukon</option>
						</select>
						<p class="personalError error" id="province_error">* Required field</p>
						</li>
						
						<li>
							<label for="postalCode">Postal Code</label>
							<input id="postalCode" name="postalCode" type="text" value="<?= $info['POSTALCODE'] ?>"/>
							<p class="personalError error" id="postalCode_error">* Required field</p>
							<p class="personalError error" id="postalformat_error">* Invalid postal code</p>
						</li>					
					<?php endforeach ?>
					</ul>
				</form>
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