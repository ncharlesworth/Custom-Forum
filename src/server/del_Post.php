<?php
include "Temp-Client-Stuff/header.php";
?>
<?php

include 'connectType.php';
include 'connect.php';

if(checkGET()){
  mysqli_autocommit($connection, FALSE);

  /*You have to order posts by ascending. Then, unable to delete the first choice.
      If the pid matches the lowest postId, it won't work*/





  $postToDel = mysqli_real_escape_string($connection, $_GET['pid']);

  $getPost = "SELECT * FROM posts WHERE `postId`='" . $postToDel . "'";

  $postAuthor = mysqli_fetch_assoc(mysqli_query($connection, $getPost));

  $tempThreadSqL = "SELECT * FROM posts WHERE postThread=".$postAuthor['postThread']." ORDER BY postId ASC";

  $postToDeleteThreadFrom = mysqli_fetch_assoc(mysqli_query($connection, $tempThreadSqL));

  if($postToDeleteThreadFrom['postId'] == $postToDel){
    echo "You cannot delete the first post of a thread.";
    echo "<a href='".$_SERVER['HTTP_REFERER']."'>Return to previous page.</a>";
  }
  else{

  if($_SESSION['userRank'] < 2 || $_SESSION['userId'] == $postAuthor['postBy']){
    $sql = "DELETE FROM posts WHERE `postId`='" . $postToDel . "'";

    if(mysqli_query($connection, $sql)){
      $selectUserSQL = "SELECT * FROM users WHERE userId=".$postAuthor['postBy'];

      $selectedUser= mysqli_query($connection, $selectUserSQL);

      if($selectedUser){
        $selectedUserValues=mysqli_fetch_assoc($selectedUser);
        $newpostCount = $selectedUserValues['postCount'] - 1;
        $postCountSQL = "UPDATE users SET postCount=".$newpostCount." WHERE userId=".$selectedUserValues['userId'];

        if(mysqli_query($connection, $postCountSQL)){
          echo "Your post has been deleted.<br>";
          echo "<a href='".$_SERVER['HTTP_REFERER']."'>Return to previous page.</a>";
          mysqli_commit($connection);
        }
        else{
          Echo "There was an error reducing user's post count:" . mysqli_error($connection);
          echo "<a href='". $_SERVER['HTTP_REFERER'] ."'>Return to page</a>";
          mysqli_rollback($connection);
        }
      }
      else{
        Echo "There was an error selecting the user:" . mysqli_error($connection);
        echo "<a href='". $_SERVER['HTTP_REFERER'] ."'>Return to page</a>";
        mysqli_rollback($connection);
      }
    }
    else{
      Echo "There was an error deleting your post:" . mysqli_error($connection);
      echo "<a href='". $_SERVER['HTTP_REFERER'] ."'>Return to page</a>";
      mysqli_rollback($connection);
    }
  }
  else{
    Echo "You do not have permission to delete posts.";
    echo "<a href='". $_SERVER['HTTP_REFERER'] ."'>Return to previous page</a>";
  }
  }
}
else{
  Echo "Please do not access this page the way you have.";
  echo "<a href='". $_SERVER['HTTP_REFERER'] ."'>Return to page</a>";
}
mysqli_close($connection);




?>
<?php
include "Temp-Client-Stuff/footer.php";
?>
