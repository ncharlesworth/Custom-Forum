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

    if($_SESSION['userRank'] < 2){
      echo "Mods Only? <input type='checkbox' class='topicForm' name='modOnly' value='1'>";
    }
    echo "<input type='submit' class='topicForm' value='Add Topic'></form>";
  }
  else if(validatePOSTInfo($reqTopicInfo)){

    mysqli_autocommit($connection, FALSE);

    $topName = $_POST["topicName"];
    $topDesc = $_POST["topicDescription"];
    $homeTopic = $_POST["topid"];

    if($homeTopic == 0){
      if(empty($_POST['modOnly'])){
        $sql = "INSERT INTO topic(topicName, topicDescription) VALUES ('$topName', '$topDesc')";
      }
      else{
        $sql = "INSERT INTO topic(topicName, topicDescription, modOnly) VALUES ('$topName', '$topDesc', '1')";
      }
    }
    else{
      if(empty($_POST['modOnly'])){
        $sql = "INSERT INTO topic(topicName, topicDescription, super_Topic) VALUES ('$topName', '$topDesc', '$homeTopic')";
      }
      else{
        $sql = "INSERT INTO topic(topicName, topicDescription, super_Topic, modOnly) VALUES ('$topName', '$topDesc', '$homeTopic', '1')";
      }
    }

    if(mysqli_query($connection, $sql)){
      mysqli_commit($connection);
      echo "Your topic has been created! ";
      Echo "<a href='display_Topic.php?topid=" . mysqli_insert_id($connection) . "'> Click here to go to your Topic.";
    }
    else{
      mysqli_rollback($connection);
      echo "There was an error: " . mysqli_error($connection);
    }
  }
  mysqli_close($connection);
}
else{
  echo "You need to be a Moderator to create Topics. Feel free to post and create threads if you are a user, however. And not banned.";
}


?>

<?php
include "Temp-Client-Stuff/footer.php";
 ?>
