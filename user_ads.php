<?php
/**********************************
 *  Name: Anton Stroy             *
 *  Course: WEBD-2006 (186289)    *
 *  Date: 23/10/2019              *
 *  Purpose:                      *
 **********************************/

  // Using login.php file for user authentication.
  //require 'login.php';
  // Using connection.php file to connect to the data base.
  include 'connection.php';
  session_start();

  $error_flag = False;
  $print_flag = False;

  // function for Validation of the input id.
  function valid_user_id() 
  {
    return filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
  }

  // If validation of input id fails return to the index page
  if(!valid_user_id())
  {
    header('Location: index.php');
    exit;
  }

  // Sanitize the id that comes with get method from index page.
  $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

  //$query = "SELECT * FROM adpost WHERE USERID = :id";
    $query = "SELECT a.PostId, a.PostDate, a.Name, a.Price, i.ImageLocation 
                FROM adpost a 
                LEFT JOIN image i ON (a.PostId = i.PostId) 
                WHERE UserId = :id
                ORDER BY PostDate";

  $statement = $db->prepare($query);
  $statement->bindValue(':id', $id, PDO::PARAM_INT);
  $statement->execute(); 
    
  // Call the date from the database and input it into the variable.
  $adPosts = $statement->fetchAll();

  if($adPosts == null)
    {
        $error_flag = True;
    }
    else
    {
        $print_flag = True;
    }

?>
		
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="UTF-8">
    	<title>SoldOut Sell all your staff</title>
    	<link rel="stylesheet" type="text/css" href="css/user_ads.css">
		<script src="js/formValidation.js" type="text/javascript"></script>
	</head>

	<body>
    <div id="wrapper">
      <div id="header">
        <h1>S o l d   O u t</h1>
        <h3>Best Canadian online classified advertising service</h3>
      </div>

      <div id="logoBox">
        	<img src="images\Sold_Out.png" alt="logo sold out" height="150px" width="150px" >
      </div>
        	
      <div id="topBar">
        <a href="index.php">Main</a>  
        <a href="new_post.php">New Post</a>       
      </div>

      <div id="content">
        <?php if($error_flag): ?>
          <p>There are no posts, please check other category</p>
        <?php endif ?>
        
        <?php if($print_flag): ?>
          <?php foreach($adPosts as $adPost): ?>    
            <div class="ShortAd">
              <div class="ShortAdImage">
                <img src="<?= $adPost['ImageLocation'] ?>" alt="advertisement">
              </div>
            
              <div class="ShortAdDiscription">
                <p>Posted on <?= date('F d, Y', strtotime($adPost['PostDate'])) ?></p>
                <p><strong><?= $adPost['Name'] ?></strong></p>
                <p style="color:red;">$<?= $adPost['Price'] ?></p>               
              </div>
                            
              <div class = "links">
                <a href="edit_post.php?PostId=<?= $adPost['PostId'] ?>">Edit</a>
                <a href="full_post.php?PostId=<?= $adPost['PostId'] ?>">Full Details</a>
                <a href="process.php?PostId=<?= $adPost['PostId'] ?>">Delete</a>
                <input type="hidden" name="PostId" value="<?= $PostId ?>" /> 
              </div>
            </div> 
          <?php endforeach ?> 
        <?php endif ?> 
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
          <p>copyright &copy; all rights reserved</p>
        </ul>

      </div>
    </div>
	</body>
</html>