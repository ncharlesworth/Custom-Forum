<?php
include "Temp-Client-Stuff/header.php";
?>


<?php

  if($_SESSION['loggedIn'] == true && $_SESSION['userRank'] < 3){
    include "connectType.php";
    include "connect.php";


    if(checkGET()){
      echo "<form id='threadCreation' method='post' action=''>
          <input type='hidden' name='threadTopic' value='". mysqli_real_escape_string($connection, $_GET['topid'])  ."'>
          Title <input class='threadForm' type='text' name='threadTitle'>
          Thread Description <textarea class='threadForm' name='threadDescription' ></textarea>
          Thread Content  <textarea class='threadForm' name='postContent' ></textarea>";

      if($_SESSION['userRank'] < 2){
          echo "Mods Only? <input type='checkbox' class='threadForm' name='modOnly' value='1'>";
        }

      echo "<input id='threadSubmit' class='threadForm' type='submit' value='Create Thread'>";

    }
    else{
      if(validatePOSTInfo($reqThreadInfo)){

        /*PLEASE LEARN HOW TRANSASCTIONS WORK, should use one so I don't keep accidentally making statuses and threads without content*/
        /*Also, so that my thread isn't made without a post*/

        mysqli_autocommit($connection, FALSE);

        $threadTopic = $_POST['threadTopic']; /*This is supposed to link to the part of the forum it's in*/
        $threadDescription = $_POST['threadDescription'];
        $threadTitle = $_POST['threadTitle'];
        $postContent = $_POST['postContent'];
        $time = date("Y-m-d h:i:s");
        $tempUser = $_SESSION['userId'];

        $statSQL = "INSERT INTO status (score) VALUES ('1')";

        if(mysqli_query($connection, $statSQL)){

          $statusId = mysqli_insert_id($connection);

          if(mysqli_query($connection, $statSQL)){

            $statusId2 = mysqli_insert_id($connection);

            if(empty($_POST['modOnly'])){
              $threadSQL = "INSERT INTO thread (threadDescription, threadDate, threadTopic, threadTitle, threadBy, status)
              VALUES ('$threadDescription','$time','$threadTopic', '$threadTitle', '$tempUser', '$statusId2')";
            }
            else{
              $modOnly = 1;
              $threadSQL = "INSERT INTO thread (threadDescription, threadDate, threadTopic, threadTitle, threadBy, modOnly, status)
              VALUES ('$threadDescription','$time','$threadTopic', '$threadTitle', '$tempUser', '$modOnly', '$statusId2')";
            }

            if(mysqli_query($connection, $threadSQL)){
              $threadId=mysqli_insert_id($connection);

              $postSQL = "INSERT INTO posts (postContent, postDate, postThread, postBy, status)
              VALUES ('$postContent','$time','$threadId','$tempUser','$statusId')";

             if(mysqli_query($connection, $postSQL)){

               $userPostCountSQL = "SELECT postCount FROM users WHERE `userId`='".$tempUser."'";
               $UserPostCount = mysqli_fetch_assoc(mysqli_query($connection, $userPostCountSQL));

               $userNewPostCount = 1 + $UserPostCount['postCount'];

               $toUpdateSQL = "UPDATE users SET `postCount`='" . $userNewPostCount . "' WHERE `userId`='".$tempUser."'";

               mysqli_query($connection, $toUpdateSQL);


               mysqli_commit($connection);
                echo "Your Thread has been Created!";
                echo "<a href='display_thread.php?thrid=" . $threadId . "'> Click here to see your thread.</a>";
             }
             else{
               mysqli_rollback($connection);
               echo "There was an error creating Post: " . mysqli_error($connection);
             }
            }
            else{
              mysqli_rollback($connection);
              echo "There was an error creating Thread: " . mysqli_error($connection);
            }
          }
          else{
            mysqli_rollback($connection);
            echo "There was an error creating Thread: " . mysqli_error($connection);
          }

        }
        else{
          mysqli_rollback($connection);
          echo "There was an error creating Thread: " . mysqli_error($connection);
        }
      }
      else{
        mysqli_rollback($connection);
        echo "You were missing some data. Please post once fixed.";
      }

    }
    mysqli_close($connection);
  }
  else{
    echo "Please login to create Thread";
  }

 ?>


<?php
include "Temp-Client-Stuff/footer.php";
?>
