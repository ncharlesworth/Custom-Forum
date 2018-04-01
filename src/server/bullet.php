<?php
include "Temp-Client-Stuff/header.php";
?>
<?php

include 'connectType.php';
include 'connect.php';


if($_SESSION['userRank'] > 1){
  echo "You do not have permission to view this page.<br>";
  echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Return to previous page.</a>";
}
else if(checkGet()){
  echo "Where did you come from, where did you go?<br>";
  echo "Where did you come from, " . $_SESSION['userName'] , "<br>";
  echo "Hmm... not what I was expecting to GET. Huehuehue.<br>";
  echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Return to previous page.</a>";
}
else if(empty($_POST['threadId']) == false){
  $threadId = mysqli_real_escape_string($connection, $_POST['threadId']);

  $findIfBulletedSQL = "SELECT bulleted FROM thread WHERE threadId=" . $threadId;
  $findIfBulleted = mysqli_fetch_assoc(mysqli_query($connection, $findIfBulletedSQL));

  if($findIfBulleted['bulleted'] == 0){
    $bulletThreadSQL = "UPDATE thread SET bulleted=1 WHERE threadId=".$threadId;

    if(mysqli_query($connection, $bulletThreadSQL)){
      echo "Your thread has been bulleted!<br>";
      echo "<a href='index.php'>Return to home page.</a>";
    }
    else{
      echo "There was an error bulleting your thread: ".mysqli_error($connection)."<br>";
      echo "<a href='". $_SERVER['HTTP_REFERER'] ."'>Return to thread.</a>";
    }
  }
  else{
    $bulletThreadSQL = "UPDATE thread SET bulleted=0 WHERE threadId=".$threadId;

    if(mysqli_query($connection, $bulletThreadSQL)){
      echo "Your thread has been unbulleted!<br>";
      echo "<a href='index.php'>Return to home page.</a>";
    }
    else{
      echo "There was an error unbulleting your thread: ".mysqli_error($connection)."<br>";
      echo "<a href='". $_SERVER['HTTP_REFERER'] ."'>Return to thread.</a>";
    }
  }
}
else{
  echo "What went wrong!? I don't know, and I made this!";
  echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Return to previous page.</a>";
}

mysqli_close($connection);

?>
<?php
include "Temp-Client-Stuff/footer.php";
?>
