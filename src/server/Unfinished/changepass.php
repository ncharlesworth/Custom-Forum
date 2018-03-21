<?php

include 'connectType.php';
include 'arrays.php';

if(validatePOSTInfo($reqChangePassArray) && passCheck($_POST["newpassword"], $_POST["newpassword-check"])){
  //IMPORTANT: Include necessary ReqField from Arrays.php in validatePOSTInfo or it won't work
  include 'connect.php'; //$connection
  //Not sure how clean it is, but if there's an error nothing below would proceed. So it should be fine to write whatever.
  //Otherwise, I should include the Error check here

  $username = $_POST["username"];
  $userpassword = md5($_POST["oldpassword"]);
  $newpassword = md5($_POST["newpassword"]);

  $sql = "SELECT userName, userPass FROM users;";

  $results = mysqli_query($connection, $sql);

  $acctFound = "false";

  while ($row = mysqli_fetch_assoc($results)){
    if($username == $row['userName'] && $userpassword == $row['userPass']){
            //mysqli_query($connection, "START TRANSACTION");
      if(mysqli_query($connection, "UPDATE users SET userPass='$newpassword' WHERE userName='$username'")){
        echo "Success: You have updated your password";
      }
      else{
        echo "Failure: " . mysqli_error($connection);
      }
            //mysqli_commit($connection);

      $acctFound = "true";
      break;
    }
  }
  if($acctFound == "false"){
    echo "username and/or password are invalid, or do not exist.";
  }

  mysqli_free_result($results);
  mysqli_close($connection);

}










?>
