<?php
include "Temp-Client-Stuff/header.php"
 ?>

<?php

include 'connectType.php';

if(checkGet()){
  if($_SESSION['loggedIn'] == false){
    echo "<form method='post' action='' id='newUserForm' >
      First Name:<br>
      <input type='text' name='firstname' id='firstname' class='required'>
      <br>
      Last Name:<br>
      <input type='text' name='lastname' id='lastname' class='required'>
      <br>
      Username:<br>
      <input type='text' name='username' id='username' class='required'>
      <br>
      email:<br>
      <input type='text' name='email' id='email' class='required'>
      <br>
      Password:<br>
      <input type='password' name='password' id='password' class='required'>
      <br>
      Re-enter Password:<br>
      <input type='password' name='password-check' id='password-check' class='required'>
      <br><br>
      <input type='submit' id='newUserButton' value='Create New User'>
      </form>";
  }
  else{
    header("Location: index.php");
  }
}


else if(validatePOSTInfo($reqNewUserArray) && passCheck($_POST["password"], $_POST["password-check"])){ //IMPORTANT: Include necessary ReqField from Arrays.php in validatePOSTInfo or it won't work
  include 'connect.php'; //$connection
  //Not sure how clean it is, but if there's an error nothing below would proceed. So it should be fine to write whatever.
  //Otherwise, I should include the Error check here

  mysqli_autocommit($connection, FALSE);


  $firstname = mysqli_real_escape_string($connection, $_POST["firstname"]);
  $lastname = mysqli_real_escape_string($connection,$_POST["lastname"]);
  $username = mysqli_real_escape_string($connection,$_POST["username"]);
  $email = mysqli_real_escape_string($connection,$_POST["email"]);
  $userpassword = mysqli_real_escape_string($connection,$_POST["password"]);
  $tempPass = md5($userpassword);

  if(strlen($username) < 6){
    echo "Please make a username at least 6 characters long.";
    echo "<a href=new_User.php>Click here to return to page.</a>";
  }
  else if(strlen($userpassword) < 8){
    echo "Please make a password at least 8 characters long.";
    echo "<a href=new_User.php>Click here to return to page.</a>";
  }
  else if(preg_match('~[0-9]+~', $userpassword) == false){
    echo "Password must have 1 number in it.";
    echo "<a href=new_User.php>Click here to return to page.</a>";
  }
  else{

      $sql = "SELECT userName, userEmail FROM users;";

      $results = mysqli_query($connection, $sql);

      $acctFound = "false";

      while ($row = mysqli_fetch_assoc($results)){
        if($username == $row['userName'] || $email == $row['userEmail']){
          echo "User already exists with this name and/or email.";
          $acctFound = "true";
          break;
        }
      }

      if($acctFound == "false"){

        $date = date("Y-m-d");
        $time = date("Y-m-d H:i:s");

        $tempUserStat = "INSERT INTO user_status (`user_status_id`, `name`) VALUES (NULL, 'unverified')";

        if(mysqli_query($connection, $tempUserStat)){

          /*$userStat = mysqli_query($connection, "SELECT LAST_INSERT_ID()");*/
          $userStat = mysqli_insert_id($connection);

          $tempSQL = "INSERT INTO users (userName, userPass, userEmail, firstName, lastName, creationDate, userRank, lastAction, user_status)
          VALUES ('$username', '$tempPass', '$email', '$firstname', '$lastname', '$date', '2', '$time', '$userStat')";

          if(mysqli_query($connection, $tempSQL)){
            mysqli_commit($connection);
            echo "An account for the user " . $username . " has been created.";
            echo "<a href='index.php'> <br>Click here to return to the home page </a>";
          }
          else{
            echo "There was an error: " . mysqli_error($connection);
            mysqli_rollback($connection);
          }

        }
        else{
          echo "There was an error: " . mysqli_error($connection);
          mysqli_rollback($connection);
        }

        mysqli_free_result($results);
        mysqli_close($connection);

        //set UserID, creationDate, lastAction, and user_status

        //mysqli_query($connection, "START TRANSACTION");

      }
    }
  }

?>


<?php
include "Temp-Client-Stuff/footer.php"
 ?>
