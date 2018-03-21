<?php
include "Temp-Client-Stuff/header.php";
?>

<?php

$_SESSION['loggedIn'] = false;
$_SESSION['userId'] = NULL;
$_SESSION['userName'] = "unregistered";
$_SESSION['userRank'] = 4;

if($_SESSION['userName'] != "unregistered"){
  echo "Error! Not sure what happened, but you are still logged in";
  echo "<a href='index.php'> <br>Click here to return to the home page </a>";
}
else{
  echo "You have successfully logged out.";
  echo "<a href='index.php'> <br>Click here to return to the home page </a>";

}

?>

<?php
include "Temp-Client-Stuff/footer.php";
?>
