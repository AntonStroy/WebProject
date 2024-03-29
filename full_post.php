<?php
/************************************************
 *  Name: Anton Stroy                           *
 *  Course: WEBD-2006 (186289)                  *
 *  Date: 5/12/2019                             *
 *  Purpose: Page with the full description of  *
 *  the post.                                   *
 ************************************************/
  
  // Using connection.php file to connect to the data base.
  include 'connection.php';
  session_start();
  
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

  // Sanitize the id that comes with get method from index page.
  $PostId = filter_input(INPUT_GET, 'PostId', FILTER_SANITIZE_NUMBER_INT);
  
  // Build a query using ":id" as a placeholder parameter.
  $query = "SELECT u.UserId, a.PostId, a.CategoryId, a.Name, a.Description, a.Price, a.BuyOrSell, a.PostDate, i.ImageLocation, u.FirstName, u.LastName, u.PhoneNum, u.Email, u.Address, u.City, u.Province, u.PostalCode 
                FROM adpost a 
                LEFT JOIN image i ON (a.PostId = i.PostId)
                LEFT JOIN user u ON (u.UserId = a.UserId)
                WHERE a.PostId = :PostId";
    
  $statement = $db->prepare($query);
  $statement->bindValue(':PostId', $PostId, PDO::PARAM_INT);
  $statement->execute(); 
    
  // Call the date from the database and input it into the variable.
  $info = $statement->fetchAll();

  $_SESSION['PostId'] = $PostId;
  $_SESSION['receiverId'] = $info[0][0];
  $receiverId = $_SESSION['receiverId'];

  // function that format paragraphs for returning text from data base
  function paragraphs_return($string)
  { 
    $paragraphs = ''; 
    foreach(explode("\n", $string) as $line) 
    {
      if (trim($line)) 
      {
        $paragraphs .= '<p>' . $line . '</p>';
      }
    }
    
    return $paragraphs;
  }

//----------------------------------------------Comment Code Block-------------------------------------------------//

  // Variables flags for empty data and printing
  $error_flag = False;
  $print_flag = False;

  // Query the data from the database
  $query = "SELECT c.Comment, c.CommentDate, u.Login  
              FROM comment c
              JOIN user u ON (u.UserId = c.SenderId)
              WHERE c.ReceiverId = $receiverId
              ORDER BY c.CommentDate DESC";

  $statement = $db->prepare($query);
  $statement->execute();
  $comments = $statement->fetchall();
  
  if($comments == null)
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
    <link rel="stylesheet" type="text/css" href="css/post.css">
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
        			
      <?php foreach($info as $currentInfo): ?>
        <div id="topBar">
          <a href="index.php">Main</a>
          <?php if(isset($_SESSION['Login'])): ?>
            <a href="user_ads.php?id=<?= $_SESSION['UserId'] ?>">My Adds</a>
            <a href="new_post.php">New Add</a>
          <?php endif ?>                   		 		     
        </div>
        	     
        <div id="leftSide">
         	<form method="post" action="comment_process.php" enctype="multipart/form-data">    
            <?php if(isset($_SESSION['Login'])): ?>
              <label>Tell what you think about this Seller</label>
              <input type="text" name="commentPost">
              <button type="submit">Comment</button>
            <?php else: ?>
              <label>What other users think about this seller</label> 
            <?php endif ?>
      
            <?php if($error_flag): ?>
              <p>No comments please add yours</p>
            <?php endif ?>

            <?php if($print_flag): ?>
              <?php foreach($comments as $current): ?>
                <div class="comment">
                  <p><?= $current['Comment'] ?></p>
                  <p><strong><?= $current['Login'] ?></strong></p>
                  <p><?= date('F d, Y', strtotime($current['CommentDate'])) ?></p>
                </div>
              <?php endforeach ?>
            <?php endif ?>
          </form>
        </div>
			
			  <div id="content">
				  <div class="ShortAdDiscription">
            <img src="<?= $currentInfo['ImageLocation'] ?>" alt="advertisement">
            <p><strong><?= $currentInfo['Name'] ?></strong></p>
            <p>Posted On: <?= date('F d, Y', strtotime($currentInfo['PostDate'])) ?></p> 
            <p>Price: $<?= $currentInfo['Price'] ?></p>
            <?=  paragraphs_return("Description: ".$currentInfo['Description']) ?>
          </div>
        </div>
	    <?php endforeach ?>
	    
      <div id="rightSide">
        <ul>
          <?php if(isset($_SESSION['Login'])): ?>
            <li><a href="index.php?destroy=true">Sign Out</a></li>
          <?php endif ?>
        </ul>       
          
        <img src="images/flight.jpg" alt="Airlines advertisement" >
        <p>Seller: <?= $currentInfo['FirstName'] ?> <?= $currentInfo['LastName'] ?></p>
        <p>Item Location: <?= $currentInfo['Address'] ?></p>
        <p><?= $currentInfo['City'] ?></p>
        <p><?= $currentInfo['Province'] ?></p>
        <p><?= $currentInfo['PostalCode'] ?></p>
        <p><?= $currentInfo['Email'] ?></p>
        <p><?= $currentInfo['PhoneNum'] ?></p>
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