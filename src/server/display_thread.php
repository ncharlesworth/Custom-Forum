<?php
include "Temp-Client-Stuff/header.php";
?>


<?php
include "connect.php"; //$connection

$sql = "SELECT * FROM thread WHERE `threadId`='" . mysqli_real_escape_string($connection, $_GET['thrid']) . "'";

$idResult=mysqli_query($connection, $sql);

if($idResult){
  include "curNav.php";

  $curThreadId = mysqli_fetch_assoc($idResult);

  curThreadNav($connection, $curThreadId);


  if($curThreadId['modOnly'] == true && $_SESSION['userRank'] > 1 ){
    echo "You do not have permission to view this page";
  }
  else{
    if($curThreadId['modOnly'] == true){
     echo "Viewing Moderated Thread";
    }

    $postsSQL = "SELECT * FROM posts WHERE `postThread`='" . $curThreadId['threadId'] . "'";

    $postsResults = mysqli_query($connection, $postsSQL);

    if($postsResults){
      echo "<div class='thread'>";
      echo "<h2 id='threadTitle'>" .  $curThreadId['threadTitle'] . "</h2>";
      while($row =  mysqli_fetch_assoc($postsResults)){

        $postUserSQL = "SELECT * FROM users WHERE `userId`='" . $row['postBy'] . "'";
        $postUserResult = mysqli_query($connection, $postUserSQL);
        $postUser = mysqli_fetch_assoc($postUserResult);

        echo "<article class='post' id ='#" . $row['postId'] . "'>
          <table>
            <tr>
              <td rowspan='3' class='userThread' >
                <p> <img class='userImage' src='" . $postUser['userPicture'] . "' alt='user1 Image'></p>
                <a href='user.php?userid=". $postUser['userId'] ."'>" . $postUser['userName'] . "</a>
                <p> Posts:" . $postUser['postCount'] . "</p>
                <p> Joined: " . $postUser['creationDate'] . "</p>";
                if($_SESSION['userRank'] <3){
                  echo "<a href='mail.php?userid=". $postUser['userId'] . "'>Message User</a>";
                  echo "<a href='#' id='vote'>Upvote Post</a>";
                  echo "<a href='#' id='vote'>Downvote Post</a>";
                }
                if( $_SESSION['userId'] == $row['postBy'] || $_SESSION['userRank'] <2){
                  echo "<a id='editPost' href='edit_Post.php?pid=" . $row['postId'] . "'>Edit Post</a>";
                  echo "<a id='deletePost' href='del_Post.php?pid=" . $row['postId'] . "' >Delete Post</a>";
                }
                echo "</td>
                    <td class='postContent'>" . $row['postContent'] . "</td>
                  </tr>
                  <tr>
                    <td class='Signature'>" . $postUser['signature'] . "</td>
                  </tr>
                  <tr>
                    <td class='postDate'><p> Date Posted: " . $row['postDate']. "  </p> <p>Share this post! (Include actual links later) </p></td>
                  </tr>
                  </table>
                </article>";
      }
    if($_SESSION['userRank'] < 3){
      /* postContent*/
      echo "<form id='createPost' method='post' action='post.php'>
          <input type='hidden' name='postThread' value='". $curThreadId['threadId']  ."'>
          Leave a Reply!  <textarea class='threadForm' name='postContent' ></textarea>";
      echo "<input id='threadSubmit' class='threadForm' type='submit' value='Create Post'>";
    }


    echo "</div>";
    mysqli_free_result($postUserResult);
  }
  else{
    echo "There was an error displaying the posts: " . mysqli_error($connection);
  }
mysqli_free_result($idResult);
mysqli_free_result($postsResults);
mysqli_close($connection);
}
}
else{
  echo "There was an error Displaying the Thread: " . mysqli_error($connection);
}


?>

<?php
include "Temp-Client-Stuff/footer.php";
?>
