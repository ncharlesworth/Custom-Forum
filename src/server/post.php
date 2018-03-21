<?php
include "Temp-Client-Stuff/header.php";
?>


<?php
include "connectType.php";

if(validatePOSTInfo($reqPostInfo)){
  include "connect.php";

        /*PLEASE LEARN HOW TRANSASCTIONS WORK, should use one so I don't keep accidentally making statuses and threads without content*/
        /*Also, so that my thread isn't made without a post*/

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

      echo "Your Post has been Created!";
      echo "<a href='display_thread.php?thrid=" . $postThread . "'> Click here to see your thread.</a>";
    }
    else{
      echo "There was an error creating your Post: " . mysqli_error($connection);
    }
  }
  else{
    echo "There was an error creating your Post: " . mysqli_error($connection);
  }
  mysqli_close($connection);
}
else{
  echo "You were missing some data. Please post once fixed.";
}



 ?>


<?php
include "Temp-Client-Stuff/footer.php";
?>
