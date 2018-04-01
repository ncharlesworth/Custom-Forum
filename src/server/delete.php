<?php
include "Temp-Client-Stuff/header.php";
?>
<?php

include 'connectType.php';
include 'connect.php';


if($_SESSION['userRank'] >1){
  echo "You do not have permission to be here.<br>";
  echo "<a href='index.php'> Return to Home. </a>";
}
else if(checkGet()){

  /*Display thing that lets user choose what to delete*/


  echo "<form method='post' action='' name='deleteForm' id='form'>";
  echo "<p> Delete Topic or Thread? </p><br>";
  echo "<p> If the topic has topic children, those will all be deleted. </p><br>";
  echo "<p> All posts and threads will be deleted as well. </p><br>";
  echo "Topic <input type='radio' value='1' name='toDelete'><br>";
  echo "Thread <input type='radio' value='2' name='toDelete'><br>";
  echo "Id to Delete (can be found when viewing said thread/topic)<br>";
  echo "<input type=text name='id'><br>";

  echo "<br><input type='submit' value='Delete'>";
  echo "</form>";
  mysqli_close($connection);

}
else if(checkPost()){
  mysqli_autocommit($connection, FALSE);
  /*Display Thread or Topic. if topic, deletes all */

  if(empty($_POST['thrid']) == false){
    /*thread deletiong*/

    if(deleteThread($connection, mysqli_real_escape_string($connection, $_POST['thrid']))){
      echo "Thread has been deleted!<br>";
      echo "<a href=index.php> Return to home. </a>";
      mysqli_commit($connection);
    }
    else{
      echo "Unknown error! " . mysqli_error($connection);
      echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page. </a>";
      mysqli_rollback($connection);
    }
    mysqli_close($connection);
  }
  else if(empty($_POST['topid']) == false){
    /*topic deletion*/

    if(deleteTopic($connection, mysqli_real_escape_string($connection, $_POST['topid']))){
      echo "Topic(s) and associated Threads have been deleted!<br>";
      echo "<a href=index.php> Return to home. </a>";
      mysqli_commit($connection);
    }
    else{
      echo "Unknown error! " . mysqli_error($connection);
      echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page. </a>";
      mysqli_rollback($connection);
    }
    mysqli_close($connection);
  }
  else if(empty($_POST['id']) == false){
    if(empty($_POST['toDelete']) == false){
      /*delete thread or post*/

      if($_POST['toDelete']==1){
        /*Delete Topic*/
        if(deleteTopic($connection, mysqli_real_escape_string($connection, $_POST['toDelete']))){
          echo "Topic(s) and associated Threads have been deleted!<br>";
          echo "<a href=index.php> Return to home. </a>";
          mysqli_commit($connection);
        }
        else{
          echo "Unknown error! " . mysqli_error($connection);
          echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page. </a>";
          mysqli_rollback($connection);
        }
        mysqli_close($connection);
      }
      else if($_POST['toDelete'] ==2){
        /*DELETE thread*/
        if(deleteThread($connection, mysqli_real_escape_string($connection, $_POST['toDelete']))){
          echo "Thread has been deleted!<br>";
          echo "<a href=index.php> Return to home. </a>";
          mysqli_commit($connection);
        }
        else{
          echo "Unknown error! " . mysqli_error($connection);
          echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page. </a>";
          mysqli_rollback($connection);
        }
        mysqli_close($connection);

      }
      else{
        echo "Did you mess with my forms?";
        echo "<a href='delete.php'> Return to deletion page. </a>";
      }
    }
    else{
      echo "You didn't select whether to delete a topic or thread!<br>";
      echo "<a href='delete.php'> Return to deletion page. </a>";
    }

  }
  else{
    echo "I'm not sure what to delete!<br>";
    echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page. </a>";
  }
}
else{
  echo "I'm a little tea pot, here's my spout. <br>";
  echo "Oh, sorry. Not sure how you got here.<br>";
  echo "<a href='index.php'> Try again, maybe? </a>";
}


function deleteThread($connection, $threadId){

  $deleteThreadSQL = "DELETE FROM thread WHERE threadId=".$threadId;

  $selectPostSQL = "SELECT * FROM posts WHERE postThread=".$threadId;

  $selectPostResults = mysqli_query($connection, $selectPostSQL);

  if($selectPostResults){
    while($row = mysqli_fetch_assoc($selectPostResults)){
      $deletePostSQL = "DELETE FROM posts WHERE postId=".$row['postId'];
      $deletePostResult = mysqli_query($connection, $deletePostSQL);
      if($deletePostResult){
        $userPostCountSQL = "SELECT * FROM users WHERE userId=".$row['postBy'];
        $userPostCountResult = mysqli_query($connection, $userPostCountSQL);

        if($userPostCountResult){
          $userPostCountRow = mysqli_fetch_assoc($userPostCountResult);
          $newPostCount = $userPostCountRow['postCount'] - 1;

          $postCountSQL = "UPDATE users SET postCount=".$newPostCount." WHERE userId=".$userPostCountRow['userId'];

          if(mysqli_query($connection, $postCountSQL)){
            continue;
          }
          else{
            echo "Error altering post count: ".mysqli_error($connection);
            echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page. </a>";
            return false;
            break;
          }

        }
        else{
          echo "Error selecting user to change post count: ".mysqli_error($connection);
          echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page. </a>";
          return false;
          break;
        }
      }
      else{
        echo "There was an error deleting a post: ".mysqli_error($connection);
        echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page. </a>";
        return false;
        break;
      }
    }
  }
  else{
    echo "There was an error selecting a post: ".mysqli_error($connection);
    echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page. </a>";
    return false;
  }

  $deleteThreadResult = mysqli_query($connection, $deleteThreadSQL);

  if($deleteThreadResult){
    return true;
  }
  else{
    echo "There was an error deleting the Thread: ".mysqli_error($connection);
    echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page. </a>";
    return false;
  }

}

function deleteTopic($connection, $topicId){

  $topicDeleteSQL = "DELETE FROM topic WHERE topicId=".$topicId;

  $isTopicAParentTopic = "SELECT * FROM topic WHERE topicId=".$topicId;

  $superTopicResult = mysqli_query($connection, $isTopicAParentTopic);

  if($superTopicResult){
    $superTopicRow = mysqli_fetch_assoc($superTopicResult);

    if($superTopicRow['super_Topic'] != NULL){
      /*Is not a parent of a topic. I.E., do a while loop that sends off
          values to deleteThread() until there are no more threads, then
            delete this topic*/

      $threadsToDelete = "SELECT * FROM thread WHERE threadTopic=".$topicId;
      $threadToDeleteResult = mysqli_query($connection, $threadsToDelete);

      if($threadToDeleteResult){

        $successfulPostDeletion = 1;
        while($row = mysqli_fetch_assoc($threadToDeleteResult)){
          if(deleteThread($connection, $row['threadId'])){
            continue;
          }
          else{
            $successfulPostDeletion = 0;
            return false;
            break;
          }
        }
        if($successfulPostDeletion = 1){
          $deleteTopicResult = mysqli_query($connection, $topicDeleteSQL);
          if($deleteTopicResult){
            return true;
          }
          else{
            echo "There was an error deleting topic: ".mysqli_error($connection);
            echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page. </a>";
            return false;
          }
        }
        else{
          return false;
        }

      }
      else{
        echo "There was an error selecting topic: ".mysqli_error($connection);
        echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page. </a>";
        return false;
      }
    }
    else{
      /*Is a super topic. Recursive call to this function for each child topic
        to be deleted, then delete this one*/

      $childTopicsToDelete = "SELECT * FROM topic WHERE super_Topic=".$topicId;

      $childTopicResult = mysqli_query($connection, $childTopicsToDelete);

      if($childTopicResult){

        while($row = mysqli_fetch_assoc($childTopicResult)){
          if(deleteTopic($connection, $row['topicId'])){
            continue;
          }
          else{
            return false;
            break;
          }
        }

        if(mysqli_query($connection, $topicDeleteSQL)){
            return true;
        }
        else{
          return false;
        }
      }
      else{
        echo "There was an error selecting child topics: ".mysqli_error($connection);
        return false;
      }
    }
  }
  else{
    echo "There was an error selecting topic: ".mysqli_error($connection);
    return false;
  }

}

?>

<?php
include "Temp-Client-Stuff/footer.php";
?>
