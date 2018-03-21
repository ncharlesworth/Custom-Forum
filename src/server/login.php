<?php
include "Temp-Client-Stuff/header.php";
?>

<?php

include 'connectType.php';
include 'arrays.php';

if(validatePOSTInfo($reqLoginArray)){ //IMPORTANT: Include necessary ReqField from Arrays.php in validatePOSTInfo or it won't work
  include 'connect.php'; //$connection
  //Not sure how clean it is, but if there's an error nothing below would proceed. So it should be fine to write whatever.
  //Otherwise, I should include the Error check here


  $username = $_POST["username"];
  $userpassword = md5($_POST["password"]);

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
            echo "<a href='index.php'> <br>Click here to return to the home page </a>";
            break;
          }
        }

        if($acctFound == "false"){
          echo "username and/or password are invalid";
        }

        mysqli_free_result($results);
        mysqli_close($connection);

}

?>

<?php
include "Temp-Client-Stuff/footer.php";
?>
