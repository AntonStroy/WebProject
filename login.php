<?php
/*******************************************************
 *  Name: Anton Stroy                                  *
 *  Course: WEBD-2006 (186289)                         *
 *  Date: 5/12/2019                                    *
 *  Purpose: Code that helps to prevent unauthorized   *
 *	user to enter certain pages on the website         *
 *******************************************************/

 session_start();

 if(!isset($_SESSION['Login']) && !isset($_SESSION['Password'])) 
 {
  	header('HTTP/1.1 401 Unauthorized');

    header('WWW-Authenticate: SoldOut');

    exit("Access Denied: Username and password required.");
  }
?>    
