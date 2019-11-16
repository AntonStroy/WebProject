<?php
/**********************************
 *  Name: Anton Stroy             *
 *  Course: WEBD-2006 (186289)    *
 *  Date: 23/10/2019              *
 *  Purpose:                      *
 **********************************/

  // Using login.php file for user authentication.
  //require 'login.php';
    include 'connection.php';

    session_start();
    
    $errorFlag  = False;
    $errorMessage = '';
    $user = null;

  if(isset($_POST['command']))
  {

    echo $_POST['login'];
    echo $_POST['password'];

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
                WHERE Login = :login AND Password = :password";

      $statement = $db->prepare($query);
      $statement->bindValue(':login', $login);        
      $statement->bindValue(':password', $password);

      $statement->execute();
      $rows = $statement->rowCount();
      $user = $statement->fetchall();
      
      //print_r($user);

      if($rows == 0) 
      {
          $errorMessage= "Wrong Passsword or Login";
          $errorFlag  = True;     
      }
      else
      { 

        $_SESSION['UserId'] = $user[0][0];
        $_SESSION['Login'] = $user[0][1];
        $_SESSION['Password'] = $user[0][2];
        $_SESSION['Admin'] = $user[0][3];

        Echo $_SESSION['Login'];
        Echo $_SESSION['Password'];
        Echo $_SESSION['UserId'];
        Echo $_SESSION['Admin'];
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
          
          
              <div id="topBar">
                    <a href="index.php">Main</a>
                    <a href="registration.php">Register</a>  
                    <button type="submit" form="Form" name="command" value="login">Login</button> 
                    <button type="button">Reset</button>               
              </div>
      
      <div id="content">
        <form id="Form" action="login_form.php" method="POST" enctype="multipart/form-data">
          <legend>Login</legend>
            <ul>
              <li>
                <label for="login">login</label>
                <input type="text" name="login" id="login" />
                <p class="loginError error" id="login_error">* Required field</p>
              </li>

              <li>
                <label for="password">Password</label>
                <input type="text" name="password" id="password" />
                <p class="loginError error" id="password_error">* Required field</p>
              </li>
            </ul>
              
              <?php if($errorFlag) :?>
                <p><?= $errorMessage ?></p>
              <?php endif ?>
              
              <a href="#">Forgot your password?</a>
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