<?php
/*******************************************************************
 *  Name: Anton Stroy                                              *
 *  Course: WEBD-2006 (186289)                                     *
 *  Date: 05/12/2019                                               *  
 *  Purpose: Process of the input data into the                    *
 *  database including updating deleting and image inserting.      *
 *******************************************************************/
        
  // using authentication.php file for user authentication.
  require 'login.php';
  // using connection.php file to connect to the data base.
  include 'connection.php';

  // Connecting to resize library.
  include 'php-image-resize-master/lib/ImageResize.php';
  use \Gumlet\ImageResize;

//-------------------------------------------------Process Block---------------------------------------------//
  // Temporary user id before setup login system.
  $userId = $_SESSION['UserId'];    
  $buyOrSell = 0;
  $newPostId = 0;
     
  // Variable flags required for navigation of the code flow.
  $Error_flag  = False;
  $create_flag = False;
  $update_flag = False;
  $delete_flag = False;

  if($_POST['buyOrSell'] === 'Buy')
  {
    $buyOrSell = 1;
  }

  // Sanitize user input to escape HTML entities and filter out dangerous characters.
  $itemName    = filter_input(INPUT_POST, 'itemName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $price       = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
  $categoryId  = filter_input(INPUT_POST, 'categoryId', FILTER_SANITIZE_NUMBER_INT);
  $buyOrSell   = filter_var($buyOrSell, FILTER_VALIDATE_INT);

  // Sanitize the PostId that comes with post method from edit_post page.
  $PostId      = filter_input(INPUT_POST, 'PostId', FILTER_SANITIZE_NUMBER_INT);

  // Selecting categories to create a dynamic category list
  $query = "SELECT ImageId, ImageLocation
              FROM image
              WHERE POSTID = $PostId";

  $statement = $db->prepare($query);
  $statement->execute();
  $images = $statement->fetchall();
    
  // Checking for empty item name or description or item name is over 70 characters. 
  if($_POST['itemName'] === '' || $_POST['description'] === '' ||  strlen($itemName) > 70)
  {
    $Error_flag = True; 
  }

  // Process block
  if(!$Error_flag)    
  {       
    // Else If block to determine which command need to be used create, update or delete.
    if($_POST['command'] === 'Create')
    {
      $query = "INSERT INTO adPost (USERID, CATEGORYID, NAME, DESCRIPTION, PRICE, BUYORSELL) values (:userId, :categoryId, :itemName, :description, :price, :buyOrSell)";
      $create_flag = True;       
    }
    elseif($_POST['command'] === 'Update')
    {
      $query = "UPDATE adPost SET USERID = :userId, CATEGORYID = :categoryId, NAME = :itemName, DESCRIPTION = :description, PRICE = :price, BUYORSELL = :buyOrSell WHERE POSTID = :PostId";
      $update_flag = True;
    }
    elseif($_POST['command'] === 'Delete')
    {
      $query = "DELETE FROM adPost WHERE POSTID = :PostId";
      $delete_flag = True;   
    }
        
    // Preparing query for database and bind the values.     
    $statement = $db->prepare($query);
        
    // If Create or Update is used the itemName and description required binding. 
    if($create_flag || $update_flag)
    {
      $statement->bindValue(':userId', $userId);
      $statement->bindValue(':categoryId', $categoryId);
      $statement->bindValue(':itemName', $itemName);        
      $statement->bindValue(':description', $description);
      $statement->bindValue(':price', $price);
      $statement->bindValue(':buyOrSell', $buyOrSell);   
    }
        
    // If Update or Delete is used the id required binding.
    if($update_flag || $delete_flag)
    {    
      $statement->bindValue(':PostId', $PostId, PDO::PARAM_INT);
      $newPostId = $PostId;  
    }
        
    // Execution of the binds
    $statement->execute();

    // If Create is used determine the last autoincremented number
    if($create_flag)
    {
      $insert_id = $db->lastInsertId(); 
      $newPostId = $insert_id;
    }

    if(($update_flag && $_POST['removeFile'] == 1) || $delete_flag)
    {
      $file = 'C:\\xampp\htdocs\WebProject\\'.$images[0][1];
      
      unlink($file);

      $query = "DELETE FROM image WHERE IMAGEID = :imageId";
      $statement = $db->prepare($query);
      $statement->bindValue(':imageId', $images[0][0], PDO::PARAM_INT);
      $statement->execute();  
    }
    
//-----------------------------------File Upload Block-----------------------------------------------------------//
    
    // file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
    // Default upload path is an 'images' sub-folder in the current folder.
    function file_upload_path($original_filename, $upload_subfolder_name = 'images') 
    {
      $current_folder = dirname(__FILE__);
       
      // Build an array of paths segment names to be joins using OS specific slashes.
      $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
       
      // The DIRECTORY_SEPARATOR constant is OS specific.
      return join(DIRECTORY_SEPARATOR, $path_segments);
    }
    
    // file_is_an_image() - Checks the mime-type & extension of the uploaded file.
    function file_check($temporary_path, $new_path) 
    {
      $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
      $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
        
      $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
      $actual_mime_type        = getimagesize($temporary_path)['mime'];
        
      $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
      $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
        
      return $file_extension_is_valid && $mime_type_is_valid;
    }

    $file_upload_detected  = isset($_FILES['uploadFile']) && ($_FILES['uploadFile']['error'] === 0);
    $upload_error_detected = isset($_FILES['uploadFile']) && ($_FILES['uploadFile']['error'] > 0);
    
    if($file_upload_detected) 
    { 
      $filename             = $_FILES['uploadFile']['name'];
      $temporary_file_path  = $_FILES['uploadFile']['tmp_name'];
      $new_file_path        = file_upload_path($filename);
        
      if(file_check($temporary_file_path, $new_file_path)) 
      {      
        if($images == NULL)
        {   
          $query = "INSERT INTO image (POSTID, IMAGELOCATION) values (:PostId, :ImageLocation)";
        
          $statement = $db->prepare($query);
          $statement->bindValue(':PostId', $newPostId);
          $statement->bindValue(':ImageLocation', $new_file_path);  
          $statement->execute();
          $insert_id = $db->lastInsertId();

          $new_file_path = "images/".$insert_id.".".pathinfo($new_file_path, PATHINFO_EXTENSION);

          $query = "UPDATE image SET IMAGELOCATION = :ImageLocation WHERE IMAGEID = :PostId";
          $statement = $db->prepare($query);
          $statement->bindValue(':ImageLocation', $new_file_path);
          $statement->bindValue(':PostId', $insert_id, PDO::PARAM_INT);
          $statement->execute();
        }
        elseif($images[0][0] != NULL)
        { 
          $file = 'C:\\xampp\htdocs\WebProject\\'.$images[0][1];
          unlink($file);
          
          $new_file_path = "images/".$images[0][0].".".pathinfo($new_file_path, PATHINFO_EXTENSION); 

          $query = "UPDATE image SET IMAGELOCATION = :ImageLocation WHERE IMAGEID = :imageId";
                        
          $statement = $db->prepare($query);
          $statement->bindValue(':ImageLocation', $new_file_path);
          $statement->bindValue(':imageId', $images[0][0], PDO::PARAM_INT);
          $statement->execute();
        }
 
        move_uploaded_file($temporary_file_path, $new_file_path);
                    
        $ResizedFile = new ImageResize($new_file_path);
        $ResizedFile->resizeToWidth(500);
        $ResizedFile->save(file_upload_path($new_file_path)); 
      }
    }          
      
    if($create_flag || $delete_flag)
    {
      header("Location: user_ads.php?id=$userId");
      exit; 
    }        
    elseif ($update_flag) 
    {  
      header("Location: edit_post.php?PostId=$PostId");
      exit;
    } 
  }
    
    // Array of error mesages 
    $error_mesage_array = [
      
      ['error' => $_POST['itemName'] === '', 'error mesage' => 'Item Name can\'t be blank'],
                               
      ['error' => $_POST['description'] === '', 'error mesage' => 'Description can\'t be blank'],

      ['error' => strlen($itemName) > 140, 'error mesage' => 'Title can\'t exceed 140 characters']
    ]; 
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
                    
      <div id="topBar">
        <a href="index.php">Main</a>
        <a href="user_ads.php?id=<?= $_SESSION['UserId'] ?>">My Adds</a>  
        <button type="submit" form="Form" name="command" value="Create">Post</button> 
        <button type="button">Reset</button>                 
      </div>
            
      <div id="content">
        <form id="Form" action="process.php" method="POST" enctype="multipart/form-data">
          <h2>New Post</h2>
          <?php if ($upload_error_detected): ?>
            <p>Error Number: <?= $_FILES['uploadFile']['error'] ?></p>
          <?php elseif ($file_upload_detected): ?>
            <p>Client-Side Filename: <?= $_FILES['uploadFile']['name'] ?></p>
            <p>Apparent Mime Type:   <?= $_FILES['uploadFile']['type'] ?></p>
            <p>Size in Bytes:        <?= $_FILES['uploadFile']['size'] ?></p>
            <p>Temporary Path:       <?= $_FILES['uploadFile']['tmp_name'] ?></p>
          <?php endif ?>

          <?php if($Error_flag): ?>
            <div>
              <p>Error!</p>
              <?php foreach ($error_mesage_array as $current_error => $value): ?>
                <?php if($value['error']): ?>         
                  <p><?= $value['error mesage']?>.</p> 
                <?php endif ?>
              <?php endforeach ?>
            </div>
          <?php endif ?>         
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