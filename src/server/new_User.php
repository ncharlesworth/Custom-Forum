<?php
include "Temp-Client-Stuff/header.php"
 ?>

<?php

include 'arrays.php';
include 'connectType.php';

if(validatePOSTInfo($reqNewUserArray) && passCheck($_POST["password"], $_POST["password-check"])){ //IMPORTANT: Include necessary ReqField from Arrays.php in validatePOSTInfo or it won't work
  include 'connect.php'; //$connection
  //Not sure how clean it is, but if there's an error nothing below would proceed. So it should be fine to write whatever.
  //Otherwise, I should include the Error check here

  mysqli_autocommit($connection, FALSE);


  $firstname = $_POST["firstname"];
  $lastname = $_POST["lastname"];
  $username = $_POST["username"];
  $email = $_POST["email"];
  $userpassword = $_POST["password"];
  $tempPass = md5($userpassword);

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
        $time = date("Y-m-d h:i:s");

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

?>


<?php
include "Temp-Client-Stuff/footer.php"
 ?>
