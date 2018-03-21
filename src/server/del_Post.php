<?php
include "Temp-Client-Stuff/header.php";
?>
<?php

include 'connectType.php';
include 'connect.php';

if(checkGET()){
  $postToDel = mysqli_real_escape_string($connection, $_GET['pid']);

  $getPost = "SELECT postBy, postThread FROM posts WHERE `postId`='" . $postToDel . "'";

  $postAuthor = mysqli_fetch_assoc(mysqli_query($connection, $getPost));

  if($_SESSION['userRank'] < 2 || $_SESSION['userId'] == $postAuthor['postBy']){
    $sql = "DELETE FROM posts WHERE `postId`='" . $postToDel . "'";

    if(mysqli_query($connection, $sql)){
      echo "Your post has been deleted.";
      echo "<a href='display_thread.php?thrid=". $postAuthor['postThread'] ."'>";
    }
    else{
      Echo "There was an error deleting your post.";
      echo mysqli_error($connection);
    }

  }





}
mysqli_close($connection);










?>
<?php
include "Temp-Client-Stuff/footer.php";
?>
