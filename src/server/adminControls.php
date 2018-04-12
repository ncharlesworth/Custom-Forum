<?php
include 'Temp-Client-Stuff/header.php';
include 'connectType.php';
include 'connect.php';


if($_SESSION['userRank'] > 2){
  echo "You do not have permission to ban or unban.";
  echo "<a href=index.php> Click here to return home. </a>";
}

/*Maybe do 2 issets to see if it finds either button, or an empty check*/

else if(empty($_POST['banButton']) == false){
  if($_POST['banButton'] == 'Unban'){
    if(empty($_POST['unBan1']) == false && empty($_POST['unBan1']) == false && empty($_POST['userId']) == false){
      $userId = mysqli_real_escape_string($connection, $_POST['userId']);
      $unbanDate = date("Y m d");

      $sql = "UPDATE users SET userRank=2, unbanDate='".$unbanDate."' WHERE `userId`=".$userId;

      if(mysqli_query($connection, $sql)){
        echo "Success: User has been unbanned!";
        echo "<a href=user.php?id=".$userId."> Return to User's Page";
      }
      else{
        echo "Failure: " . mysqli_error($connection);
        echo "<a href=user.php?id=".$userId."> Return to User's Page";
      }
    }
    else{
      echo "It doesn't seem you were certain about unbanning!.";
      echo "<a href=user.php?id=".$_POST['userId']."> Return to User's Page";
    }



  }
  else if($_POST['banButton'] == 'Submit Ban'){
    if(validatePOSTInfo($reqBanInfo)){
      if(empty($_POST['confirmBan']) == false){

        $userId = mysqli_real_escape_string($connection, $_POST['userId']);
        $unbanDate= mysqli_real_escape_string($connection, $_POST['unbanDate']);

        $sql = "SELECT userRank, userName, unbanDate FROM users WHERE `userId`=".$userId;

        $results = mysqli_query($connection, $sql);

        if($results){
          $banResult =  mysqli_fetch_assoc($results);

          $banSQL = "UPDATE users SET userRank=4, unbanDate='".$unbanDate."' WHERE `userId`=".$userId;

          if(mysqli_query($connection, $banSQL)){
            echo "User " . $banResult['userName'] . " has been banned until " . $unbanDate;
            echo "<a href=user.php?id=".$userId."> Return to User's Page";
          }
          else{
            echo "There was an error banning this user.";
            echo mysqli_error($connection);
            echo "<a href='user.php?id=".$userId."'> Return to User Page. </a>";
          }
        }
        else{
          echo "There was an error selecting this user.<br>";
          echo mysqli_error($connection);
          echo "<a href='user.php?id=".$userId."'> Return to User Page. </a>";
        }
      }
      else{
        echo "You seem to be uncertain of this change!";
        echo "<a href=user.php?id=".$_POST['userId']."> Return to User's Page";
      }
    mysqli_free_result($results);

    }
  }
  else{
    echo "How the heck did you get here friend?";
    echo "<a href=user.php?id=".$_POST['userId']."> Return to User's Page";
  }
}
else if(empty($_POST['modGradeButton']) != true){
  if($_POST['modGradeButton'] == 'modUpgrade'){
    if(empty($_POST['modUpgrade']) == false && empty($_POST['userId']) == false){
      $userId = mysqli_real_escape_string($connection, $_POST['userId']);

      $updateSQL = "UPDATE users SET userRank=1 WHERE `userId`=" . $userId;

      if(mysqli_query($connection, $updateSQL)){
        echo "User has been made into a mod!";
        echo "<a href=user.php?id=".$_POST['userId']."> Return to User's Page";
      }
      else{
        echo "Failure: " . mysqli_error($connection);
        echo "<a href=user.php?id=".$userId."> Return to User's Page";
      }

    }
    else{
      echo "You seem to be uncertain of this change!";
      echo "<a href=user.php?id=".$_POST['userId']."> Return to User's Page";
    }
  }
  else if($_POST['modGradeButton'] == 'modDowngrade'){
    if(empty($_POST['moddowngrade']) == false && empty($_POST['userId']) == false){
      $userId = mysqli_real_escape_string($connection, $_POST['userId']);

      $updateSQL = "UPDATE users SET userRank=2 WHERE userId=" . $userId;

      if(mysqli_query($connection, $updateSQL)){
        echo "User has been made into a mod!";
        echo "<a href=user.php?id=".$_POST['userId']."> Return to User's Page";
      }
      else{
        echo "Failure: " . mysqli_error($connection);
        echo "<a href=user.php?id=".$userId."> Return to User's Page";
      }
    }
    else{
      echo "You seem to be uncertain of this change!";
      echo "<a href=user.php?id=".$_POST['userId']."> Return to User's Page";
    }
  }
  else{
    echo "How the heck did you get here friend?";
    echo "<a href=user.php?id=".$_POST['userId']."> Return to User's Page";
  }
}

else{
  header("Location: index.php");
}

mysqli_close($connection);
?>

<?php
include "Temp-Client-Stuff/footer.php";
?>
