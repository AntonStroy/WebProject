<?php
/**********************************
 *  Name: Anton Stroy             *
 *  Course: WEBD-2006 (186289)    *
 *  Date: 23/10/2019              *
 *  Purpose:                      *
 **********************************/

	session_start();

	if(!isset($_SESSION['Login']) && !isset($_SESSION['Password'])) 
  {
    	header('HTTP/1.1 401 Unauthorized');

    	header('WWW-Authenticate: SoldOut');

    	exit("Access Denied: Username and password required.");
  }
?>    
