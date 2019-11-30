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
    
  // Selecting categories to create a dynamic category list for the right side category links
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
   
  $search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $categoryId  = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_NUMBER_INT);

  if(isset($_GET['category']))
  {
    $query = "SELECT a.PostId, a.PostDate, a.Name, a.Price, i.ImageLocation
                FROM adpost a 
                LEFT JOIN image i ON (a.PostID = i.PostID)
                LEFT OUTER JOIN category c ON (c.categoryId = a.categoryId)
                WHERE a.categoryId = $categoryId   
                ORDER BY a.$sort";
  }
  elseif(isset($_POST['search']))
  {
    $query = "SELECT a.PostId, a.PostDate, a.Name, a.Price, i.ImageLocation
                FROM adpost a 
                LEFT JOIN image i ON (a.PostID = i.PostID)
                LEFT OUTER JOIN category c ON (c.categoryId = a.categoryId)
                WHERE a.Name LIKE :Name   
                ORDER BY a.$sort";
  }
  else
  {
    // Query the data from the database
    $query = "SELECT a.PostId, a.PostDate, a.Name, a.Price, i.ImageLocation
                FROM adpost a 
                LEFT JOIN image i ON (a.PostID = i.PostID)
                ORDER BY a.$sort"; 
  }

  $statement = $db->prepare($query);
    
  if(isset($_POST['search']))
  {
    $statement->bindValue(':Name', "%$search%");
  } 
  
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
        <a href="index.php"><img src="images/Sold_Out.png" alt="logo sold out" id="logo" ></a>
      </div>
      
      <div id="leftSide">
        <ul>
          <?php foreach ($categories as $category): ?>
            <li><a href="index.php?category=<?= $category['CategoryId'] ?>"><?= $category['CategoryName'] ?></a></li>
        	<?php endforeach ?>
        </ul>
        <img src="images/SocialAdd.png" alt="Wine Advertisement" >
      </div>
      
      <div id="topBar" >
        <form id="sortButtons" action="index.php" method="POST" enctype="multipart/form-data">
        <span>Sort by: </span>
          <a href="index.php?sort=Price">Price</a>
          <a href="index.php?sort=Name">Name</a>
          <a href="index.php?sort=PostDate">Date</a>
          <input id="searchbox" type="text" name="search"/>
          <button type="submit" name="command" value="Search">Search</button>
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
        <ul>
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
          
          <?php if(isset($_SESSION['Login'])): ?>
            <li><a href="index.php"><?= $_SESSION['Login'] ?></a></li>                        
          <?php else: ?>
            <li><a href="registration.php">Register</a></li>
          <?php endif ?>
        </ul>
        <img src="images/vocation.jpg" alt="Vacation Advertisement" >
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