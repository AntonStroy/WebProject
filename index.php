<?php
/**********************************
 *  Name: Anton Stroy             *
 *  Course: WEBD-2006 (186289)    *
 *  Date: 23/10/2019              *
 *  Purpose:                      *
 **********************************/

    // Include connection from the connect.php file
    include 'connection.php';

    session_start();

    if(isset($_GET['destroy']) == 'true')
    {
      session_destroy();
      header('Location:index.php');
    }
    
   /* $priceSort = '';
    $nameSort = '';
    $dateSort = '';*/

    /*if(isset($_POST['nameSort']) || isset($_POST['priceSort']) || isset($_POST['dateSort']))
    { 
        // Condition to set the price sort variable
        if($_POST['nameSort'] === 'A-Z')
        {
       
            $nameSort = 'ASC'; 
        }
        elseif($_POST['nameSort'] === 'Z-A') 
        {
        
            $nameSort = 'DESC';
        }

      
        // Condition to set the name sort variable
        if($_POST['priceSort'] === 'lowToHigh')
        {
            $priceSort = 'ASC';
        }
        elseif($_POST['priceSort'] === 'highToLow')
        {
            $priceSort = 'DESC';
        }

   
        // Condition to set the date sort variable
        if($_POST['dateSort'] === 'oldestFirst')
        {
            $dateSort = 'ASC';
        }
        elseif($_POST['dateSort'] === 'newestFirst')
        {
            $dateSort = 'DESC';
        }

    }*/
    
    // Selecting categories to create a dynamic category list
    $categories = "SELECT CategoryId, CategoryName
                FROM category
                ORDER BY CategoryName";

    $statement = $db->prepare($categories);
    $statement->execute();
    $categories = $statement->fetchall();  

    // Part of the code to sort the post set the $sort variable to the one of the sort strings Date, Price or Name
    $sort = 'PostDate';
    if(isset($_GET['sort']))
    {
        $sort = $_GET['sort'];
    }
    
    
    // Variables flags for empty data and printing
    $error_flag = False;
    $print_flag = False;

    
    //Validating the input category
    function valid_Count_Input() 
    {
        return filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT) && ($_GET['category'] <= 10 && $_GET['category'] > 1);
    }
  
   if(isset($_GET['category']))
    {
        $categoryId = $_GET['category'];
        $query = "SELECT a.PostId, a.PostDate, a.Name, a.Price, i.ImageLocation
                FROM adpost a 
                LEFT JOIN image i ON (a.PostID = i.PostID)
                LEFT OUTER JOIN category c ON (c.categoryId = a.categoryId)
                WHERE a.categoryId = $categoryId   
                ORDER BY a.$sort"; //LIMIT 30
    }
    else
    {
        // Query the data from the database
        $query = "SELECT a.PostId, a.PostDate, a.Name, a.Price, i.ImageLocation
                FROM adpost a 
                LEFT JOIN image i ON (a.PostID = i.PostID)
                ORDER BY a.$sort"; //LIMIT 30   
    }

    $statement = $db->prepare($query);
    $statement->execute();
    $adPosts = $statement->fetchall();
    
    
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
    	<meta charset="UTF-8" />
    	<title>SoldOut Sell all your staff</title>
    	<link rel="stylesheet" type="text/css" href="css/sold_out.css">
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
        	
        	<div id="leftSide">
                <?php foreach ($categories as $category): ?>
                    <li><a href="index.php?category=<?= $category['CategoryId'] ?>"><?= $category['CategoryName'] ?></a></li>
        	   <?php endforeach ?>
            </div>

        	<div id="topBar" >
            <form id="sortButtons" action="index.php" method="POST" enctype="multipart/form-data">
        		<span>Sort by: </span>
                <a href="index.php?sort=Price">Price</a>
                <a href="index.php?sort=Name">Name</a>
                <a href="index.php?sort=PostDate">Date</a>

                 
        		<!--Sorting mechanism complicated disabled for now another one used instead-->
                <!--  <label for="priceSort">Price</label>
        		 <select name="priceSort" id="priceSort">
                 	<option value="lowToHigh">Low to High</option>
                    <option value="highToLow">High to Low</option>
                </select>
                <label for="nameSort">Name</label>
                <select name="nameSort" id="nameSort">
                 	<option value="A-Z">A - Z</option>
                    <option value="Z-A">Z - A</option>
                </select>
                <label for="dateSort">Date</label>
                <select name="dateSort" id="dateSort">
                 	<option value="newestFirst">Newest First</option>
                    <option value="oldestFirst">Oldest First</option>
                </select>
                <button type="submit">Update List</button> -->

                <input id="searchbox" type="text" />
                <button type="button">Search</button>
                <?php if(isset($_SESSION['Login'])): ?>
                   <span><?= $_SESSION['Login'] ?></span>
                <?php endif ?> 
                       
            </form>
            </div>

        	<div id="content">
                <?php if($error_flag): ?>
                    <p>There are no posts, please check other category</p>
                <?php endif ?>
        
                <?php if($print_flag): ?>
                    <?php foreach($adPosts as $adPost): ?>    
                       

                        <a target="_blank" href="full_post.php?PostId=<?= $adPost['PostId'] ?>">

                        <div class="ShortAd">
                            <div class="ShortAdImage">
                                <img src="<?= $adPost['ImageLocation'] ?>" alt="advertisement">
                            </div>
                            
                            <div class="ShortAdDiscription">
                                <p>Posted on <?= date('F d, Y', strtotime($adPost['PostDate'])) ?></p>
                                <p><strong><?= $adPost['Name'] ?></strong></p>
                                <p style="color:red;">$<?= $adPost['Price'] ?></p>
                            </div>
                        </div> 
                        </a>      
                    <?php endforeach ?> 
                <?php endif ?>  
            </div>      

        	<div id="rightSide">
                <?php if(isset($_SESSION['Login'])): ?>
                    <li><a href="index.php?destroy=true">Sign Out</a></li>
                    <li><a href="user_ads.php?id=<?= $_SESSION['UserId'] ?>">My Adds</a></li>
                    <li><a href="new_post.php">New Add</a></li>
                        <?php if($_SESSION['Admin'] == 1): ?>
                           <li><a href="user_configuration.php">User Configuration</a></li>
                           <li><a href="category_configuration.php">Category Configuration</a></li> 
                        <?php endif ?>
                <?php else: ?>
                   <li><a href="login_form.php">Sign In</a></li>
                <?php endif ?>          
                    <li><a href="registration.php">Register</a></li>
                
        		<img src="images\vocation.jpg" alt="Vacation Advertisement" >
        	</div>

        	<div id="footer">
                <div>
                    <li><a href="#">Terms of Use</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Posting Policy</a></li>
                    <li><a href="#">Support</a></li>
                </div>
                <div>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Member Benefits</a></li>
                    <li><a href="#">Advertise on SoldOut</a></li>
                </div>
                <p>copyright &copy; all rights reserved</p>
        	</div>
    	</div>
	</body>
</html>