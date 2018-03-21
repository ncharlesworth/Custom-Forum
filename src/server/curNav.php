<?php

function curTopicNav($connection, $topicId){

  $forumidTitle = "SELECT topicName, super_Topic FROM topic WHERE `topicId`='" . mysqli_real_escape_string($connection, $_GET['topid']) . "'";
  $fidResult = mysqli_fetch_assoc(mysqli_query($connection, $forumidTitle));

  $fidParent = "SELECT topicName FROM topic WHERE `topicId`='" . $fidResult['super_Topic'] . "'";
  $fidParentTitle = mysqli_fetch_assoc(mysqli_query($connection, $fidParent));


  echo "<h2><a href='index.php'>Home</a> -- <a href='display_Topic.php?topid=" . $fidResult['super_Topic'] . "'>" . $fidParentTitle['topicName'] . "</a> -- "
   . $fidResult['topicName'] . "</h2>";

}


function curThreadNav($connection, $currentThread){

  $currentThreadTop = $currentThread['threadTopic'];
  $currentThreadTitle = $currentThread['threadTitle'];

  $forumidTitle = "SELECT topicName, super_Topic FROM topic WHERE `topicId`='" . $currentThreadTop . "'";
  $fidResult = mysqli_fetch_assoc(mysqli_query($connection, $forumidTitle));

  $fidParent = "SELECT topicName FROM topic WHERE `topicId`='" . $fidResult['super_Topic'] . "'";
  $fidParentTitle = mysqli_fetch_assoc(mysqli_query($connection, $fidParent));


  echo "<h2><a href='index.php'>Home</a> -- <a href='display_Topic.php?topid=" . $fidResult['super_Topic'] . "'>" . $fidParentTitle['topicName'] .
    "</a> -- <a href='display_Topic.php?topid=" . $currentThread['threadTopic'] . "'>" . $fidResult['topicName'] . "</a>";

  echo " -- " . $currentThreadTitle ."</h2>";

}









?>
