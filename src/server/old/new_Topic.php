<?php
include "Temp-Client-Stuff/header.php";
?>

<?php

include 'connectType.php';
include 'arrays.php';

if(validatePOSTInfo($reqTopicInfo)){ //IMPORTANT: Include necessary ReqField from Arrays.php in validatePOSTInfo or it won't work
  include 'connect.php'; //$connection
  //Not sure how clean it is, but if there's an error nothing below would proceed. So it should be fine to write whatever.
  //Otherwise, I should include the Error check here

  $topName = $_POST["topicName"];
  $topDesc = $_POST["topicDescription"];
  $homeTopic = mysqli_real_escape_string($connection, $_GET['topid']);

  if($homeTopic == 0){
      $sql = "INSERT INTO topic(topicName, topicDescription) VALUES ('$topName', '$topDesc')";
  }
  else{
      $sql = "INSERT INTO topic(topicName, topicDescription, superTopic) VALUES ('$topName', '$topDesc', '$homeTopic')";
  }

  if(mysqli_query($connection, $sql)){
    echo "Your topic has been created! ";
    mysqli_close($connection);
  }
  else{
    echo "There was an error: " . mysqli_error($connection);
  }

}

?>

<?php
include "Temp-Client-Stuff/footer.php";
?>
