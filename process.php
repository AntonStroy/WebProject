<?php
/**********************************
 *  Name: Anton Stroy             *
 *  Course: WEBD-2006 (186289)    *
 *  Date: 23/10/2019              *
 *  Purpose:                      *
 **********************************/
        
    // using authentication.php file for user authentication.
    //require 'login.php';
    // using connection.php file to connect to the data base.
    include 'connection.php';
    session_start();

    // Temporary user id before setup login system.
    $userId = $_SESSION['UserId'];
    $buyOrSell = 0;
     
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
    $categoryId  = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
    $buyOrSell   = filter_var($buyOrSell, FILTER_VALIDATE_INT);

    // Sanitize the PostId that comes with post method from edit_post page.
    $PostId      = filter_input(INPUT_POST, 'PostId', FILTER_SANITIZE_NUMBER_INT);
    
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
        }
        
        // Execution of the binds
        $statement->execute();

        // If Create is used determine the last autoincremented number
        if($create_flag)
        {
           $insert_id = $db->lastInsertId(); 
        }
       
       // return to index page 
       header('Location: index.php');
       exit;
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
        <meta charset="utf-8">
        <title>Bloooger - Error</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
  
    <body>
        <div id="wrapper">
            <div id="header">
                <h1><a href="index.php">Bloooger - Error</a></h1>
            </div>

            <ul id="menu">
                <li><a href="index.php" class='active'>Home</a></li>
                <li><a href="create.php" >New Post</a></li>
            </ul>

            <div id="all_blogs">
                <?php if($Error_flag): ?>
                    <div class ="invoice">
                        <p>Error!</p>
                        <?php foreach ($error_mesage_array as $current_error => $value): ?>
                            <?php if($value['error']): ?>         
                                <p><?= $value['error mesage']?>.</p> 
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            </div>       
        </div>    
    </body>
</html>
