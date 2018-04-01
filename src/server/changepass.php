
<?php
include 'Temp-Client-Stuff/header.php';
include 'connectType.php';
include 'connect.php';

if(validatePOSTInfo($reqChangePassArray)){
  //IMPORTANT: Include necessary ReqField from Arrays.php in validatePOSTInfo or it won't work


  $userId = mysqli_real_escape_string($connection, $_POST['userId']);
  $userpassword = md5(mysqli_real_escape_string($connection, $_POST['oldPass']));
  $newpassword = md5(mysqli_real_escape_string($connection, $_POST['newPass']));

  if($userId == $_SESSION['userId'] || $_SESSION['userRank'] < 2){
    if(strlen(mysqli_real_escape_string($connection, $_POST['newPass'])) >= 8){
      if(preg_match('~[0-9]+~', mysqli_real_escape_string($connection, $_POST['newPass']))){

        $sql = "SELECT userId, userPass FROM users WHERE `userId`=".$userId;

        $results = mysqli_query($connection, $sql);

        if($results){
          $userResult= mysqli_fetch_assoc($results);
          if($userResult['userPass'] == $userpassword){
            $updateSQL = "UPDATE users SET userPass='$newpassword' WHERE `userId`=".$userId;
            if(mysqli_query($connection,$updateSQL)){
              echo "Success: You have updated your password";
              echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Click here to return to user page.";
            }
            else{
              echo "Failure:" . mysqli_error($connection);;
              echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Click here to return to user page.";
            }
          }
          else{
            echo "Old Password does not match the saved one.";
            echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Click here to return to user page.";
          }
        }
        else{
          echo "Could not find this user.";
          echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Yet, click here to return to user page.";
        }

        mysqli_free_result($results);
      }
      else{
        echo "Your New Password must include a number.";
        echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Click here to return to user page.";
      }

    }
    else{
      echo "Your New Password must be at least 8 characters long and include a number.";
      echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Click here to return to user page.";
    }

  }
  else{
    echo "You do not have permission to change this password.";
    echo "<a href='index.php'> Click here to return to home.";
  }



}

mysqli_close($connection);
?>

<?php
include "Temp-Client-Stuff/footer.php";
?>
