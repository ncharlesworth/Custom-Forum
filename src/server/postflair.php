<?php
include "Temp-Client-Stuff/header.php";
include "connect.php";
include "connectType.php";


if(checkPOST()){

  $errorNum = 0;

  if(empty($_POST['newSig']) && empty($_POST['userImage'])){
    echo "You are missing any information that you wish altered.<br>";
    echo "<a href='" . $_SERVER['HTTP_REFERER'] . "'> Return to User's page. </a>";
    /*Doesn't have prereq info. For now, must upload image and sig*/
  }


  else if($_SESSION['userId'] == $_POST['userId'] || $_SESSION['userRank'] < 2){

    /*Check to see what's uploaded. If both sig and user image, upldate them
        separately. Essentially, 2 updates here depending on what's sent
        through POST*/

    mysqli_autocommit($connection, FALSE);

    $sigsuccess = 0;
    $imgsuccess = 0;

    if(empty($_POST['newSig']) == false){

      $sigSQL = "UPDATE users SET signature='" . mysqli_real_escape_string($connection, $_POST['newSig']) . "' WHERE `userId`='".$_POST['userId']."'";

      $result = mysqli_query($connection, $sigSQL);

      if($result){
        mysqli_commit($connection);
        echo "Your signature has been changed!<br>";
        $sigsuccess=1;
      }
      else{
        echo "There was an error updating signature: ". mysqli_error($connection) . "<br>";
        echo "<a href='" . $_SERVER['HTTP_REFERER'] . "'> Return to User's page. </a>";

        mysqli_rollback($connection);
        mysqli_free_result($result);
      }
    }

    if($_FILES['userImage']['size'] > 0){

      /*I ONLY need to come here if I actually uploaded an image*/

      include "upload.php";

      if($upload){

        $imagedata = file_get_contents($_FILES['userImage']['tmp_name']);
        $contentType =  basename($_FILES["userImage"]["name"]);

        $userImageSQL = "INSERT INTO userimages (userId, contentType, userPicture)
          VALUES(?,?,?)";

        $stmt = mysqli_stmt_init($connection);

        mysqli_stmt_prepare($stmt, $userImageSQL);

        $null = NULL;
        mysqli_stmt_bind_param($stmt, "isb", $_POST['userId'], $imageFileType, $null);
        mysqli_stmt_send_long_data($stmt, 2, $imagedata);

        $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
        if($result){
          echo "Your signature has been changed!<br>";
          $imgsuccess=1;
          mysqli_commit($connection);
          mysqli_free_result($result);
        }
        else{
          echo "There was an error changing image: ". mysqli_error($connection) . "<br>";
          echo "<a href='" . $_SERVER['HTTP_REFERER'] . "'> Return to User's page. </a>";
          die(mysqli_stmt_error($stmt));
          mysqli_rollback($connection);
          mysqli_free_result($result);
        }
      }
      else{
        echo "<a href='" . $_SERVER['HTTP_REFERER'] . "'> Return to User's page. </a>";
      }
    }

    mysqli_close($connection);
  }
  else {
    echo "You do not have permission to change this.<br>";
    echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to User's Page. </a>";
    /*Doesn't have Permissions*/
  }
}

else{
  mysqli_close($connection);
  echo "How did you GET here. Hahaha.";
  echo "<a href='index.php'> Return to home.</a>";
  /*Didn't access through POST*/
}

 ?>
 <?php
 include "Temp-Client-Stuff/footer.php";
 ?>
