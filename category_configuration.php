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

  // Build a query using ":id" as a placeholder parameter.
  $query = "SELECT CategoryId, CategoryName
                FROM category";
  
  $statement = $db->prepare($query);
  //$statement->bindValue(':PostId', $PostId, PDO::PARAM_INT);
  $statement->execute(); 
  // Call the date from the database and input it into the variable.
  $categories = $statement->fetchAll();

//--------------------------------------------------------------------------------------//
  
  if(isset($_POST['command']))
  {
    
      if($_POST['command'] === 'Select')
      {
        $_SESSION['CategoryId'] =  $_POST['categorySelect'];
        $id = $_SESSION['CategoryId'];

        $query = "SELECT CategoryId, CategoryName
                FROM category
                WHERE categoryId = $id";
  
          $statement = $db->prepare($query);
          $statement->execute(); 
          $display = $statement->fetchAll(); 
      }

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
        
        $statement = $db->prepare($query);
        $statement->bindValue(':newCategory', $newCategory);
        $statement->execute();
        $insert_id = $db->lastInsertId();       
      }
      
      
      elseif($_POST['command'] === 'Update')
      {
            $query = "UPDATE category SET CATEGORYNAME = :category WHERE CATEGORYID = :categoryId";
           
            $statement = $db->prepare($query);
            $statement->bindValue(':category', $category);
            $statement->bindValue(':categoryId', $categoryId);
            $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
            $statement->execute();
            header('Location: category_configuration.php');
      }
      elseif($_POST['command'] === 'Delete')
      {
        $query = "DELETE FROM category WHERE CATEGORYID = :categoryId";
        $statement = $db->prepare($query);
        $statement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
        $statement->execute();   
        header('Location: category_configuration.php');
      }
               
      // refresh
    
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
        <a href="user_configuration.php">User Configuration</a>     		       
      </div>

      <div id="content">
        <form id="Form" action="category_configuration.php" method="POST" enctype="multipart/form-data">
          <ul>    
            <li>
              <input type="text" name="newCategory" id="newCategory" />
              <button type="submit" form="Form" name="command" value="Create">Add New</button>
            </li>
            
            <li>
              <label for="categorySelect">Select Category</label>
              <select id="categorySelect" name="categorySelect">
                <option value="0" selected="selected">Category</option>
                  <?php foreach($categories as $current): ?>          
                    <option value="<?= $current['CategoryId'] ?>"><?= $current['CategoryName'] ?></option>  
				          <?php endforeach ?>
              </select>
              <button type="submit" name="command" form="Form" value="Select">Select</button>
            </li>
              
            <li>
              
                  <input type="text" name="category" value="<?= $display[0][1] ?>" />
                  <input type="hidden" name="categoryId" value="<?= $display[0][0] ?>" />
               
              
              <button type="submit" name="command" form="Form" value="Update">Update</button>
              <button type="submit" name="command" form="Form" value="Delete">Delete</button>
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
          <p>copyright &copy; all rights reserved</p>
        </ul>
      </div>
    </div>
	</body>
</html>