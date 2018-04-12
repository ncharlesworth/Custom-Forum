<?php
include "Temp-Client-Stuff/header.php";
?>


<?php

include "connect.php";
include "connectType.php";

/*send the postId via GET*/

if(checkGET()){

  $tempSQL = "SELECT postBy FROM posts WHERE `postId`=" . mysqli_real_escape_string($connection, $_GET['pid']);
  $tempResult = mysqli_fetch_assoc(mysqli_query($connection, $tempSQL));

  if($_SESSION['userRank'] < 2 || $_SESSION['userId'] == $tempResult['postBy']){

    $post5QL = "SELECT postContent FROM posts WHERE `postId`=" .  mysqli_real_escape_string($connection, $_GET['pid']);

    $postResult = mysqli_fetch_assoc(mysqli_query($connection, $post5QL));

    echo "<form class='postForm' id='editPost' method='post' action=''>
        <input type='hidden' name='postId' value='". mysqli_real_escape_string($connection, $_GET['pid'])  ."'>
        Post Description: <textarea class='postForm' name='postChange' >" . $postResult['postContent'] . "</textarea>";

    echo "<input id='postSubmit' class='postForm' type='submit' value='Edit Post'>";
    }
  else{
    /*What happens if a user accesses this page without permissions? Redirect or tell them "no"?*/

    echo "You do not have permission to edit this post.";
    echo "<a href='index.php'> Click here to return to index. </a>";
  }


}

else {
  if(validatePOSTInfo($reqPostEditInfo)){
    include "sessionInfo.php";

    if(empty($_POST['postChange']) == false){

      $tempSQL = "SELECT postBy, postThread FROM posts WHERE `postId`=" . mysqli_real_escape_string($connection, $_POST['postId']);
      $tempResult = mysqli_fetch_assoc(mysqli_query($connection, $tempSQL));

      $time = date("Y-m-d h:i:s");


      if($_SESSION['userRank'] < 2 || $_SESSION['userId'] == $tempResult['postBy']){

        $insertSQL = "UPDATE posts SET postContent='". mysqli_real_escape_string($connection, $_POST['postChange']) .
          "', editDate='". $time ."', editBy='". $_SESSION['userId'] ."' WHERE `postId`='". mysqli_real_escape_string($connection, $_POST['postId']) . "'";

        $insertResult = mysqli_query($connection, $insertSQL);

        if($insertResult){
          header("Location: display_thread.php?thrid=" . $tempResult['postThread']);
        }
        else{
          echo "There was an error uploading post: ".mysqli_error($connection);
	 echo "<a href='display_thread.php?thrid=" . $_SERVER['HTTP_REFERER'];
        }
      }
      else{
	echo "You do not have the authority to change this post.";
	echo "<a href='display_thread.php?thrid=" . $_SERVER['HTTP_REFERER'];
      }
    }
    else{
      echo "You have left the content blank. Don't do that.";
      echo "<a href='display_thread.php?thrid=" . $_SERVER['HTTP_REFERER'];
    }
  }
}


mysqli_close($connection);
?>
<?php
include "Temp-Client-Stuff/footer.php";
?>