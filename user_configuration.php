<?php
/**********************************
 *  Name: Anton Stroy             *
 *  Course: WEBD-2006 (186289)    *
 *  Date: 23/10/2019              *
 *  Purpose:                      *
 **********************************/

  // using authentication.php file for user authentication.
	require 'login.php';
  
  // Using connection.php file to connect to the data base.
  include 'connection.php';
  


  // Build a query using ":id" as a placeholder parameter.
  $query = "SELECT FirstName, LastName, UserId
                FROM user
                ORDER BY UserId";
  
  $statement = $db->prepare($query);
  //$statement->bindValue(':PostId', $PostId, PDO::PARAM_INT);
  $statement->execute(); 
  // Call the date from the database and input it into the variable.
  $users = $statement->fetchAll();

//--------------------------------------------------------------------------------------//

  if(isset($_POST['command']))
  {
    $create_flag = False;
    $update_flag = False;
    $delete_flag = False;
    $Error_flag  = False;

  // Sanitize user input to escape HTML entities and filter out dangerous characters.
  $newCategory = filter_input(INPUT_POST, 'newCategory', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $categoryId = filter_input(INPUT_POST, 'categoryId', FILTER_SANITIZE_NUMBER_INT);

  // Checking for empty  name is over 70 characters. 
  if($_POST['category'] === '' ||  strlen($category) > 70 || strlen($newCategory) > 70)
  {
    $Error_flag = True; 
  }

  // Process block
  if(!$Error_flag)   
    {       
      // Else If block to determine which command need to be used create, update or delete.
      if($_POST['command'] === 'Create')
      {
        $query = "INSERT INTO category (CATEGORYNAME) values (:newCategory)";
        $create_flag = True;       
      }
      elseif($_POST['command'] === 'Update')
      {
        $query = "UPDATE category SET CATEGORYNAME = :category WHERE CATEGORYID = :categoryId";
        $update_flag = True;
      }
      elseif($_POST['command'] === 'Delete')
      {
        $query = "DELETE FROM category WHERE CATEGORYID = :categoryId";
        $delete_flag = True;   
      }
        
      // Preparing query for database and bind the values.     
      $statement = $db->prepare($query);
        
      // If Create is used the newCategory required binding.
      if($create_flag)
      {
        $statement->bindValue(':newCategory', $newCategory);
      }

      // If Update is used the category and categoryId required binding. 
      if($update_flag)
      {
        $statement->bindValue(':category', $category);
        $statement->bindValue(':categoryId', $categoryId);  
      }
        
      // If Update or Delete is used the categoryId required binding.
      if($update_flag || $delete_flag)
      {    
        $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);  
      }
        
      // Execution of the binds
      $statement->execute();

      // If Create is used determine the last autoincremented number
      if($create_flag)
      {
        $insert_id = $db->lastInsertId(); 
      }
       
      // refresh
      header('Location: category_configuration.php');
  }
}

?>
		
<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="UTF-8">
    	<title>SoldOut Sell all your staff</title>
    	<link rel="stylesheet" type="text/css" href="css/admin_configuration.css">	
	</head>
	
  <body>
    <div id="wrapper">
      <div id="header">
        <h1>S o l d   O u t</h1>
        <h3>Best Canadian online classified advertising service</h3>
      </div>

      <div id="logoBox">
        <a href="index.php"><img src="images/Sold_Out.png" alt="logo sold out" height="150px" width="150px" ></a>
      </div>
      
      <div id="topBar">
        <a href="index.php">Main</a>
        <a href="category_configuration.php">Category Configuration</a>
        <input id="searchbox" type="text" />
        <button type="button">Search</button>     		       
      </div>

      <div id="content">
        <form id="Form" action="category_configuration.php" method="POST" enctype="multipart/form-data">
            <ul>
            <?php foreach($users as $user): ?>   
  			 <li><a href="user_update.php?userId=<?= $user['UserId'] ?>">User Id: <?= $user['UserId'] ?> Name: <?= $user['FirstName'] ?> <?= $user['LastName'] ?></a></li>
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
          <p>copyright &copy; all rights reserved</p>
        </ul>
      </div>
    </div>
	</body>
</html>