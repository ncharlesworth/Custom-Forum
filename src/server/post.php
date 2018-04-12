<?php
include "Temp-Client-Stuff/header.php";
?>


<?php
include "connectType.php";

if(validatePOSTInfo($reqPostInfo)){
  include "connect.php";

  mysqli_autocommit($connection, false);

  $postContent = $_POST['postContent'];
  $time = date("Y-m-d h:i:s");
  $tempUser = $_SESSION['userId'];

  $statSQL = "INSERT INTO status (score) VALUES ('1')";

  if(mysqli_query($connection, $statSQL)){

    $statusId = mysqli_insert_id($connection);
    $postThread = $_POST['postThread'];


    $postSQL = "INSERT INTO posts (postContent, postDate, postThread, postBy, status)
      VALUES ('$postContent','$time','$postThread','$tempUser','$statusId')";

    if(mysqli_query($connection, $postSQL)){

      $userPostCountSQL = "SELECT postCount FROM users WHERE `userId`='".$tempUser."'";
      $UserPostCount = mysqli_fetch_assoc(mysqli_query($connection, $userPostCountSQL));

      $userNewPostCount = 1 + $UserPostCount['postCount'];

      $toUpdateSQL = "UPDATE users SET `postCount`='" . $userNewPostCount . "' WHERE `userId`='".$tempUser."'";

      mysqli_query($connection, $toUpdateSQL);

      mysqli_commit($connection);


      header("Location: display_thread.php?thrid=" .  $postThread);
    }
    else{
	mysqli_rollback($connection);
      echo "There was an error creating your Post: " . mysqli_error($connection);
      echo "<a href='display_thread.php?thrid=" . $postThread . "'> Click here to return to your thread.</a>";
    }
  }
  else{
	mysqli_rollback($connection);
    echo "There was an error creating your Post: " . mysqli_error($connection);
    echo "<a href='display_thread.php?thrid=" . $postThread . "'> Click here to return to your thread.</a>";
  }
  mysqli_close($connection);
}
else{
  echo "You were missing some data. Please post once fixed.";
  echo "<a href='" . $_SERVER[HTTP_REFERER] . "'> Click here to return to your thread.</a>";
}



 ?>


<?php
include "Temp-Client-Stuff/footer.php";
?>
