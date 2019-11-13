<?php
/**********************************
 *  Name: Anton Stroy             *
 *  Course: WEBD-2006 (186289)    *
 *  Date: 23/10/2019              *
 *  Purpose:                      *
 **********************************/

//require 'login.php';
session_start();

?>
		
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="UTF-8">
    	<title>SoldOut Sell all your staff</title>
    	<link rel="stylesheet" type="text/css" href="css/post.css">
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
                		<a href="user_ads.php?id=1">My adds</a>  
                		<button type="submit" form="Form" name="command" value="Create">Post</button> 
                		<button type="button">Reset</button>      		     
        			</div>
			
			<div id="content">
				<form id="Form" action="process.php" method="POST" enctype="multipart/form-data">
					<legend>New Post</legend>
						<ul>
							<li>
								<label for="category">Choose Category</label>
								<select name="category" id="category">
    								<option value="0">- category -</option>
    								<option value="7">BabyItems</option>
    								<option value="6">Books</option>
    								<option value="3">Cars</option>
    								<option value="9">Clothing</option>
    								<option value="1">Electronics</option>
    								<option value="4">Furniture</option>
    								<option value="5">Hobbies</option>
    								<option value="8">Home Appliances</option>
    								<option value="10">Pets</option>
    								<option value="2">Toys</option>
  								</select>
  								<p class="categoryError error" id="category_error">* Required field</p>
							</li>

							<li>
								<label for="itemName">Item Name</label>
								<input type="text" name="itemName" id="itemName" />
								<p class="itemNameError error" id="itemName_error">* Required field</p>
							</li>

							<fieldset id="buyOrSell" class="innerFieldset">
								<legend>Type of Post</legend>
									<ul>
										<li>				
											<input id="buy" name="buyOrSell" value="Buy" type="radio" />
											<label for="buy">Buy</label>
								
											<input id="sell" name="buyOrSell" value="Sell" type="radio" />
											<label for="sell">Sell</label>
											<p class="buyOrSellError error" id="buyOrSell_error">* You must choose post type type</p>	
										</li>
									</ul>					
							</fieldset>

							<li>
								<label for="price">Price</label>
								<input type="number" step="0.01" name="price" id="price" />
								<p class="PriceError error" id="Price_error">* Required field</p>
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