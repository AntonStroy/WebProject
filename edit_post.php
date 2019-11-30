<?php
/************************************************
 *  Name: Anton Stroy                           *
 *  Course: WEBD-2006 (186289)                  *
 *  Date: 5/12/2019                             *
 *  Purpose: Edit posts page functionality and  *
 *  layout without processing to the database.  *
 ************************************************/

  // Using login.php file for user authentication.
  require 'login.php';
  // Using connection.php file to connect to the data base.
  include 'connection.php';
   // Build a query using ":id" as a placeholder parameter.
  $query = "SELECT CategoryId, CategoryName
              FROM category";

  $statement = $db->prepare($query);
  //$statement->bindValue(':PostId', $PostId, PDO::PARAM_INT);
  $statement->execute(); 
  // Call the date from the database and input it into the variable.
  $categories = $statement->fetchAll();

  // function for Validation of the input id.
  function valid_Post_id() 
  {
    return filter_input(INPUT_GET, 'PostId', FILTER_VALIDATE_INT);
  }

  // If validation of input id fails return to the index page
  if(!valid_Post_id())
  {
    header('Location: index.php');
    exit;
  }
  
  $UserId = $_SESSION['UserId'];
  // Sanitize the id that comes with get method from index page.
  $PostId = filter_input(INPUT_GET, 'PostId', FILTER_SANITIZE_NUMBER_INT);

  // Build a query using ":id" as a placeholder parameter.
  $query = "SELECT i.ImageId, i.ImageLocation, a.PostId, a.CategoryId, a.Name, a.Description, a.Price, a.BuyOrSell, a.PostDate  
              FROM adpost a 
              LEFT JOIN image i ON (a.PostId = i.PostId)
               WHERE a.PostId = :PostId AND a.UserId = :UserId";
    
  $statement = $db->prepare($query);
  $statement->bindValue(':PostId', $PostId, PDO::PARAM_INT);
  $statement->bindValue(':UserId', $UserId, PDO::PARAM_INT);
  $statement->execute(); 
    
  // Call the date from the database and input it into the variable.
  $info = $statement->fetchAll();
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
        <a href="user_ads.php?id=1">My Adds</a>
        <button type="submit" form="Form" name="command" value="Update">Update</button>
        <button type="submit" form="Form" name="command" value="Delete">Delete</button> 
        <button type="reset"  id="reset" form="Form" name="reset" class="buttonStyle">Reset</button>     		       
      </div>

      <div id="content">
        <form id="Form" action="process.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="PostId" value="<?= $PostId ?>" />
          <?php foreach($info as $current): ?> 
          <h2>New Post</h2>
            <ul>
							<li>
              <label for="categoryId">Choose Category</label>
                <select name="categoryId" id="categoryId">
                  <option value="0">- Category -</option>
                    <?php foreach($categories as $category): ?>      
                      <option value="<?= $category['CategoryId'] ?>" <?php if($current['CategoryId'] == $category['CategoryId'] ) echo 'selected="selected"'; ?>><?= $category['CategoryName'] ?></option>  
                    <?php endforeach ?>
  							</select>
  							<p class="personalError error" id="categoryId_error">* Category is Required </p>
							</li>

							<li>
								<label for="itemName">Item Name</label>
								<input type="text" name="itemName" id="itemName" value="<?= $current['Name'] ?>" />
								<p class="personalError error" id="itemName_error">* Required field</p>
							</li>
            </ul>
						
            <fieldset id="postOption" class="innerFieldset">
							<legend>Type of Post</legend>
							<ul>
								<li>				
									<input id="buy" name="buyOrSell" value="Buy" <?php if($current['BuyOrSell'] == 1): ?>checked="checked"<?php endif ?> type="radio"  />
									<label for="buy">Buy</label>
								
									<input id="sell" name="buyOrSell" value="Sell" <?php if($current['BuyOrSell'] == 0): ?>checked="checked"<?php endif ?> type="radio" />
									<label for="sell">Sell</label>
									<p class="personalError error" id="postOption_error">* You must choose post type type</p>	
								</li>
							</ul>					
						</fieldset>

            <ul>
							<li>
								<label for="price">Price</label>
								<input type="number" step="0.01" name="price" id="price" value="<?= $current['Price'] ?>" />
							</li>
							
							<li>
								<label for="uploadFile">Upload Picture</label>
								<input type="file" name="uploadFile" id="uploadFile">
                <input type="hidden" name="removeFile" value="0" />
                <input type="checkbox" name="removeFile" value="1"> Check to delete on update
							</li>
              
              <?php if($current['ImageId'] != 0): ?>
                <li>
                  <img src="<?= $current['ImageLocation'] ?>" alt="advertisement">
                </li>
              <?php endif ?>
							
              <li>
							<br>
							<label for="description">Item Description</label>
							<br>
  							<textarea name="description" id="description" rows="10" cols="70"><?= $current['Description'] ?></textarea>
                <p class="personalError error" id="description_error">* You must provide description</p>
							</li>							
					  </ul>
				  <?php endforeach ?>
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