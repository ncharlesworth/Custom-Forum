<?php
include "Temp-Client-Stuff/header.php";
?>

<?php

include 'connectType.php';

if(validatePOSTInfo($reqLoginArray)){ //IMPORTANT: Include necessary ReqField from Arrays.php in validatePOSTInfo or it won't work
  include 'connect.php'; //$connection
  //Not sure how clean it is, but if there's an error nothing below would proceed. So it should be fine to write whatever.
  //Otherwise, I should include the Error check here


  $username = $_POST["username"];
  $userpassword = md5($_POST["password"]);

  if(strlen($username) < 6){
    echo "Please make a username at least 6 characters long.";
    echo "<a href='".$_SERVER['HTTP_REFERER']."'>Click here to return to page.</a>";
  }
  else if(strlen($userpassword) < 8){
    echo "Please make a password at least 8 characters long.";
    echo "<a href='".$_SERVER['HTTP_REFERER']."'>Click here to return to page.</a>";
  }
  else if(preg_match('~[0-9]+~', $userpassword) == false){
    echo "Password must have 1 number in it.";
    echo "<a href='".$_SERVER['HTTP_REFERER']."'>Click here to return to page.</a>";
  }
  else{

  $sql = "SELECT userId, userName, userPass, userRank FROM users;";

        $results = mysqli_query($connection, $sql);

        $acctFound = "false";

        while ($row = mysqli_fetch_assoc($results)){
          if($username == $row['userName'] && $userpassword == $row['userPass']){
            echo "You have been logged in!.";
            $acctFound = "true";
            $_SESSION['loggedIn'] = true;
            $_SESSION['userId'] = $row['userId'];
            $_SESSION['userName'] = $row['userName'];
            $_SESSION['userRank'] = $row['userRank'];

            $time = date("Y-m-d H:i:s");
            $updateLastActionSQL = "UPDATE users SET lastAction='".$time."' WHERE userId=".$_SESSION['userId'];
            mysqli_query($connection, $updateLastActionSQL);

            header("Location: " . $_SERVER['HTTP_REFERER']);
            break;
          }
        }

        if($acctFound == "false"){
          echo "username and/or password are invalid";
        }

        mysqli_free_result($results);
        mysqli_close($connection);
  }
}

?>

<?php
include "Temp-Client-Stuff/footer.php";
?>
