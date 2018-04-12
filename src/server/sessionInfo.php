<?php
  session_start();
  if(isset($_SESSION['active']) == false){
    $_SESSION['active'] = true;
    $_SESSION['loggedIn'] = false;
    $_SESSION['userId'] = NULL;
    $_SESSION['userName'] = "unregistered";
    $_SESSION['userRank'] = 4;
  }
?>
