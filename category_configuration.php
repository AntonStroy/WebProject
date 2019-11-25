<?php
/**********************************
 *  Name: Anton Stroy             *
 *  Course: WEBD-2006 (186289)    *
 *  Date: 23/10/2019              *
 *  Purpose:                      *
 **********************************/

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

//--------------------------------------------------------------------------------------//
  
    $create_flag = False;
    $update_flag = False;
    $delete_flag = False;
    $Error_flag  = False;
    $flag = '';

  if(isset($_POST['command']))
  {
    if($_POST['command'] === 'Select')
    {   
        if($_POST['categorySelect'] == 0)
        {
          $flag = 'hide';
        }
        else
        {
          $flag = 'show';
          $_SESSION['CategoryId'] =  $_POST['categorySelect'];
          $id = $_SESSION['CategoryId'];

          $query = "SELECT CategoryId, CategoryName
                FROM category
                WHERE CATEGORYID = $id";
  
          $statement = $db->prepare($query);
          $statement->execute(); 
          $display = $statement->fetchAll();
        }       
    }

    // Sanitize user input to escape HTML entities and filter out dangerous characters.
    $newCategory = filter_input(INPUT_POST, 'newCategory', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $categoryId = filter_input(INPUT_POST, 'categoryId', FILTER_SANITIZE_NUMBER_INT);
    
    if($_POST['command'] === 'Create')
      {
        if($_POST['newCategory'] === '' ||  strlen($newCategory) > 70)
        {
          $Error_flag = True; 
        }

        if(!$Error_flag)
        {
          $query = "INSERT INTO category (CATEGORYNAME) values (:newCategory)";
        
          $statement = $db->prepare($query);
          $statement->bindValue(':newCategory', $newCategory);
          $statement->execute();
          $insert_id = $db->lastInsertId();
          header('Location: category_configuration.php');
        }      
      }

      // Checking for empty  name is over 70 characters. 
      if($_POST['category'] === '' ||  strlen($category) > 70)
      {
        $Error_flag = True; 
      }

      // Process block
      if(!$Error_flag)   
      {       

        if($_POST['command'] === 'Update')
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
        <form id="CategoryForm" action="category_configuration.php" method="POST" enctype="multipart/form-data">
          <ul>    
            <li>
              <input type="text" name="newCategory" id="newCategory" />
              <button type="submit" form="CategoryForm" name="command" value="Create">Add New</button>
            </li>
            
            <li>
              <label for="categorySelect">Select Category</label>
              <select id="categorySelect" name="categorySelect">
                <option value="0" selected="selected">- Category -</option>
                  <?php foreach($categories as $current): ?>          
                    <option value="<?= $current['CategoryId'] ?>"><?= $current['CategoryName'] ?></option>  
				          <?php endforeach ?>
              </select>
              <button type="submit" name="command" form="CategoryForm" value="Select">Select</button>
            </li>
              
            <li>
              <?php if($flag == 'show'): ?>
                <input type="text" name="category" value="<?= $display[0][1] ?>" />
                <input type="hidden" name="categoryId" value="<?= $display[0][0] ?>" /> 
                <button type="submit" name="command" form="CategoryForm" value="Update">Update</button>
                <button type="submit" name="command" form="CategoryForm" value="Delete">Delete</button>
                <?php else: ?>
                <input type="text" name="category" value="" />
                <button type="submit" name="command" form="CategoryForm" value="Update">Update</button>
                <button type="submit" name="command" form="CategoryForm" value="Delete">Delete</button>
              <?php endif ?>
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