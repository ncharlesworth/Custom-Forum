
<?php
include 'Temp-Client-Stuff/header.php';
include 'connectType.php';
include 'connect.php';

if(empty($_POST['userId']) == false){
  $userId = mysqli_real_escape_string($connection, $_POST['userId']);
  if($_SESSION['userRank'] < 2 || $_SESSION['userId'] = $userId){
    $changeMade=0;

    $initUserSQL = "SELECT userEmail, firstName, lastName FROM users WHERE userId=".$userId;

    $initUserResult = mysqli_query($connection, $initUserSQL);

    if($initUserResult){
      $initUser = mysqli_fetch_assoc($initUserResult);

      $email = $initUser['userEmail'];
      $firstName  = $initUser['firstName'];
      $lastName = $initUser['lastName'];

      if(empty($_POST['email']) == false){
        $email = mysqli_real_escape_string($connection, $_POST['email']);
        $changeMade=1;
      }
      if(empty($_POST['firstname']) == false){
        $firstName = mysqli_real_escape_string($connection, $_POST['firstname']);
        $changeMade=1;
      }
      if(empty($_POST['lastname']) == false){
        $lastName = mysqli_real_escape_string($connection, $_POST['lastname']);
        $changeMade=1;
      }
      if($changeMade=0){
        echo "No Changes made! Please put values into changes. <br>";
        echo "<a href=user.php?id=".$userId."> Return to user's page. </a>";
      }
      else{
        $updateSQL = "UPDATE users SET userEmail='".$email."', firstName='".$firstName.
          "', lastName='".$lastName."' WHERE userId=".$userId;
        if(mysqli_query($connection, $updateSQL)){
          echo "Success!<br>";
          echo "<a href=user.php?id=".$userId."> Return to user's page. </a>";
        }
        else{
          echo "There was an error updating the user:" . mysqli_error($connection) ." <br>";
          echo "<a href=user.php?id=".$userId."> Return to user's page. </a>";
        }
      }
    }
    else{
      echo "There was an error selecting the user:" . mysqli_error($connection) ." <br>";
      echo "<a href=user.php?id=".$userId."> Return to user's page. </a>";
    }
  }
  else{
    echo "You do not have permission to alter this user's info.<br>";
    echo "<a href=user.php?id=".$userId."> Return to user's page. </a>";
  }
}
else{
  header("Location: ". $_SERVER['HTTP_REFERER']);
}


/*mysqli_free_result($results);*/
mysqli_close($connection);
?>

<?php
include "Temp-Client-Stuff/footer.php";
?>
