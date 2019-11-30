<?php
/*******************************************
 *  Name: Anton Stroy                      *
 *  Course: WEBD-2006 (186289)             *
 *  Date: 23/10/2019                       *
 *  Purpose: Functionality code to         *
 *  process comment post on the websites.  *
 *******************************************/

  // using authentication.php file for user authentication 
  require 'login.php';

  // Using connection.php file to connect to the data base
  include 'connection.php';

  if(isset($_POST['commentPost']))
  {
    // Variables flags for empty string or string is over 255 characters
    $Error_flag_empty = False;
    $Error_flag_char = False;
    $senderId = $_SESSION['UserId'];
    $receiverId = $_SESSION['receiverId'];

    // Sanitize the input
    $comment = filter_input(INPUT_POST, 'commentPost', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Checking if the input is empty or has more than 140 characters
    if($comment == "")
    {
      $Error_flag_empty = True; 
    }
    elseif(strlen($comment) > 255)
    {
      $Error_flag_char = True;
    }
    else // If all good insert and redirect back to index
    {
      $query = "INSERT INTO comment (SENDERID, RECEIVERID, COMMENT) values (:senderId, :receiverId, :commentPost)";
      $statement = $db->prepare($query);
      $statement->bindValue(':senderId', $senderId);
      $statement->bindValue(':receiverId', $receiverId);
      $statement->bindValue(':commentPost', $comment);

      $statement->execute();

      $insert_id = $db->lastInsertId();
      $returnId = $_SESSION['PostId'];
      header("Location: full_post.php?PostId=$returnId");
    }
  }
?>