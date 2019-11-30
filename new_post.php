<?php
/*****************************************
 *  Name: Anton Stroy                    *
 *  Course: WEBD-2006 (186289)           *
 *  Date: 05/12/2019                     *
 *  Purpose: Page where user input       *
 *	information to create a new post.    * 
 *****************************************/

	// using authentication.php file for user authentication.
	require 'login.php';
	// Include connection from the connect.php file
	include 'connection.php';
	// Selecting categories to create a dynamic category list
	$categories = "SELECT CategoryId, CategoryName
			        		FROM category
                	ORDER BY CategoryName";

	$statement = $db->prepare($categories);
	$statement->execute();
	$categories = $statement->fetchall();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
    <meta charset="UTF-8">
    <title>SoldOut Sell all your staff</title>
    <link rel="stylesheet" type="text/css" href="css/post.css">
    <script src="JavaScript/newPostValidation.js" type="text/javascript"></script>
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
        	     	
      <div id="topBar">
        <a href="index.php">Main</a>
        <a href="user_ads.php?id=<?= $_SESSION['UserId'] ?>">My Adds</a>  
        <button type="submit" form="Form" name="command" value="Create">Post</button> 
        <button type="reset"  id="reset" form="Form" name="reset" class="buttonStyle">Reset</button>      		     
      </div>
			
			<div id="content">
				<form id="Form" action="process.php" method="POST" enctype="multipart/form-data">
					<h2>New Post</h2>
					<ul>
						<li>
							<label for="categoryId">Choose Category</label>
							<select name="categoryId" id="categoryId">
								<option value="0">- category -</option>
									<?php foreach ($categories as $category): ?>
                    <option value="<?= $category['CategoryId'] ?>"><?= $category['CategoryName'] ?></option>
                  <?php endforeach ?>
  						</select>
  						<p class="personalError error" id="categoryId_error">* Required field</p>
						</li>

						<li>
							<label for="itemName">Item Name</label>
							<input type="text" name="itemName" id="itemName" />
							<p class="personalError error" id="itemName_error">* Required field</p>
						</li>
					</ul>

					<fieldset id="postOption" class="innerFieldset">
					<legend>Type of Post</legend>
						<ul>
							<li>				
								<input id="buy" name="buyOrSell" value="Buy" type="radio" />
								<label for="buy">Buy</label>
								<input id="sell" name="buyOrSell" value="Sell" type="radio" />
								<label for="sell">Sell</label>
								<p class="personalError error" id="postOption_error">* You must provide description</p>	
							</li>
						</ul>						
					</fieldset>
					
					<ul>	
						<li>
							<label for="price">Price</label>
							<input type="number" step="0.01" name="price" id="price" />
						</li>
								
						<li>
							<label for="uploadFile">Upload Picture</label>
							<input type="file" name="uploadFile" id="uploadFile">
						</li>
						
						<li>
							<br>
							<label for="description">Item Description</label>
							<br>
  							<textarea name="description" id="description" rows="10" cols="70"></textarea>
  							<p class="personalError error" id="description_error">* You mustprovide description</p>
						</li>							
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