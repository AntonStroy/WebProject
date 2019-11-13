<?php
/**********************************
 *  Name: Anton Stroy             *
 *  Course: WEBD-2006 (186289)    *
 *  Date: 23/10/2019              *
 *  Purpose:                      *
 **********************************/
    
    // Connection to the database
	define('DB_DSN','mysql:host=127.0.0.1;dbname=serverside;charset=utf8');
    define('DB_USER','serveruser');
    define('DB_PASS','gorgonzola7!');

    // Creating a PDO object
    try 
    {
        $db = new PDO(DB_DSN, DB_USER, DB_PASS);
    } 
    catch(PDOException $e) 
    {
        print "Error: " . $e->getMessage();
        die();
    }
?>