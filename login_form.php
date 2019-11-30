<?php
/**********************************
 *  Name: Anton Stroy             *
 *  Course: WEBD-2006 (186289)    *
 *  Date: 23/10/2019              *
 *  Purpose:                      *
 **********************************/

  include 'connection.php';
  session_start();
    
  $errorFlag  = False;
  $errorMessage = '';
  $user = null;

  if(isset($_POST['command']))
  {

    $login    = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if($_POST['login'] === '' || $_POST['password'] === '')
    {
        $errorFlag = True;
        $errorMessage = 'Please input login and password';
    }
    else
    {
      $query = "SELECT UserId, Login, Password, Admin 
                  FROM user 
                  WHERE Login = :login";
      
      $statement = $db->prepare($query);
      $statement->bindValue(':login', $login);        

      $statement->execute();
      $rows = $statement->rowCount();
      $user = $statement->fetch();
      
      //print_r($user);
      if($rows == 0) 
      {
          $errorMessage= "Wrong Login";
          $errorFlag  = True;     
      }
      else
      { 
        if($verifiedPassword = password_verify($password, $user['Password']))
        {
          $_SESSION['UserId'] = $user['UserId'];
          $_SESSION['Login'] = $user['Login'];
          $_SESSION['Password'] = $user['Password'];
          $_SESSION['Admin'] = $user['Admin'];
        }
        else
        {
            $errorMessage= "Wrong Password";
            $errorFlag  = True;
        }

        header('Location:index.php');
        exit;  
      }
    }
  }  
?>

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <title>SoldOut Sell all your staff</title>
      <link rel="stylesheet" type="text/css" href="css/post.css">
      <script src="JavaScript/loginValidation.js" type="text/javascript"></script>
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
        <img src="images/carAdd.jpg" alt="car Advertisement" >
      </div>    
      
      <div id="topBar">
        <a href="index.php">Main</a>
        <a href="registration.php">Register</a>
        <a href="#">Restore Password</a>  
        <button type="submit" form="Form" name="command" value="login">Login</button> 
        <button type="reset"  id="reset" form="Form" name="reset" class="buttonStyle">Reset</button>               
      </div>
      
      <div id="content">
        <form id="Form" action="login_form.php" method="POST" enctype="multipart/form-data">
          <h2>Login</h2>
          <ul>
            <li>
              <label for="login">Login</label>
              <input type="text" name="login" id="login" />
              <p class="personalError error" id="login_error">* Required field</p>
            </li>
            <li>
              <label for="password">Password</label>
              <input type="text" name="password" id="password" />
              <p class="personalError error" id="password_error">* Required field</p>
            </li>
          </ul>
          <?php if($errorFlag) :?>
            <p class="personalError"><?= $errorMessage ?></p>
          <?php endif ?>
        </form>
      </div>

      <div id="rightSide">
        <img src="images/wine.jpg" alt="Wine Advertisement" >
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