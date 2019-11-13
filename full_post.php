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
  $query = "SELECT a.PostId, a.CategoryId, a.Name, a.Description, a.Price, a.BuyOrSell, a.PostDate, i.ImageLocation, u.FirstName, u.LastName,
  					u.PhoneNum, u.Email, u.Address, u.City, u.Province, u.PostalCode 
                FROM adpost a 
                LEFT JOIN image i ON (a.PostId = i.PostId)
                LEFT JOIN user u ON (u.UserId = a.UserId)
                WHERE a.PostId = :PostId";
    
  $statement = $db->prepare($query);
  $statement->bindValue(':PostId', $PostId, PDO::PARAM_INT);
  $statement->execute(); 
    
  // Call the date from the database and input it into the variable.
  $info = $statement->fetchAll();

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
        			
<?php foreach($info as $currentInfo): ?>
        			<div id="topBar">
                		<a href="index.php">Main</a>
                		
                		 		     
        			</div>

        	<div id="leftSide">
         		
        	</div>
			
			<div id="content">
				           
                    <div class="ShortAdDiscription">
                        <img src="<?= $currentInfo['ImageLocation'] ?>" alt="advertisement">
                        <p><strong><?= $currentInfo['Name'] ?></strong></p>
                        <p>Posted On: <?= date('F d, Y', strtotime($currentInfo['PostDate'])) ?></p> 
                        <p>Price: $<?= $currentInfo['Price'] ?></p>
                        <p>Description: <?= paragraphs_return($currentInfo['Description']) ?></p>
                    </div>


                
        	</div>
	<?php endforeach ?>
	  <div id="rightSide">
                <?php if(isset($_SESSION['Login'])): ?>
                    <li><a href="index.php?destroy=true">Sign Out</a></li>
                    <li><a href="user_ads.php?id=<?= $_SESSION['UserId'] ?>">My adds</a></li>
                    <li><a href="new_post.php">Post new add</a></li>
                <?php endif ?>       
        		<img src="images\vocation.jpg" alt="chocolate bar add" >

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