<?php
include "Temp-Client-Stuff/header.php";
 ?>

<?php

if($_SESSION['loggedIn'] == true && $_SESSION['userRank'] < 2){
  include "connectType.php";
  include "connect.php";


  if(checkGET()){

    echo "<form method='post' action='topic.php'>
          <input type='hidden' name='topid' value=". mysqli_real_escape_string($connection, $_GET['topid']) .">
          Topic name: <input type='text' class='topicForm' name='topicName'>
          Topic description: <textarea name='topicDescription' class='topicForm'></textarea>";
    $topId=mysqli_real_escape_string($connection, $_GET["topid"]);
    if($topId == 0){
      echo "What image to use?
          <input type='radio' name='image' value='images/default.png'><img src='images/default.png' alt='default'>
          <input type='radio' name='image' value='images/book.png'><img src='images/book.png' alt='book'>
          <input type='radio' name='image' value='images/poetry.png'><img src='images/poetry.png' alt='poetry'>
          <input type='radio' name='image' value='images/art.png'><img src='images/art.png' alt='art'>
          <input type='radio' name='image' value='images/photo.png'><img src='images/photo.png' alt='photo'>";
    }
    echo "<br> Mods Only? <input type='checkbox' class='topicForm' name='modOnly' value='1'>";
    echo "<input type='submit' class='topicForm' value='Add Topic'></form>";
  }
  else if(validatePOSTInfo($reqTopicInfo)){

    mysqli_autocommit($connection, FALSE);

    $topName = mysqli_real_escape_string($connection, $_POST["topicName"]);
    $topDesc = mysqli_real_escape_string($connection, $_POST["topicDescription"]);
    $homeTopic = mysqli_real_escape_string($connection, $_POST["topid"]);
    if(empty($_POST["image"])){
      $topImage = "images/default.png";
    }
    else{
      $topImage = mysqli_real_escape_string($connection, $_POST["image"]);
    }

    if($homeTopic == 0){
      if(empty($_POST['modOnly'])){
        $sql = "INSERT INTO topic(topicName, topicDescription, topicPic) VALUES ('$topName', '$topDesc', '$topImage')";
      }
      else{
        $sql = "INSERT INTO topic(topicName, topicDescription, modOnly, topicPic) VALUES ('$topName', '$topDesc', '1', '$topImage')";
      }
    }
    else{
      $isTopModOnlySQL = "SELECT * FROM topic WHERE topicId=".$homeTopic;
      $isTopModOnly = mysqli_fetch_assoc(mysqli_query($connection, $isTopModOnlySQL));
      $topModPic = $isTopModOnly['topicPic'];
      if(empty($_POST['modOnly']) && $isTopModOnly['modOnly'] == 0){
        $sql = "INSERT INTO topic(topicName, topicDescription, super_Topic, topicPic) VALUES ('$topName', '$topDesc', '$homeTopic', '$topModPic')";
      }
      else{
        $sql = "INSERT INTO topic(topicName, topicDescription, super_Topic, modOnly, topicPic) VALUES ('$topName', '$topDesc', '$homeTopic', '1', '$topModPic')";
      }
    }

    if(mysqli_query($connection, $sql)){
      $insertId = mysqli_insert_id($connection);
      mysqli_commit($connection);
      echo "Your topic has been created! <br>";
      Echo "<a href='display_Topic.php?topid=" . $insertId . "'> Click here to go to your Topic.</a>";
    }
    else{
      mysqli_rollback($connection);
      echo "There was an error: " . mysqli_error($connection) ."<br>";
      echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page.</a>";
    }
  }
  mysqli_close($connection);
}
else{
  echo "You need to be a Moderator to create Topics. Feel free to post and create threads if you are a user, however. And not banned.<br>";
  echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page.</a>";
}


?>

<?php
include "Temp-Client-Stuff/footer.php";
 ?>
