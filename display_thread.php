<?php
include "Temp-Client-Stuff/header.php";
?>


<?php
include "connect.php"; //$connection
include "views.php";

$sql = "SELECT * FROM thread WHERE `threadId`='" . mysqli_real_escape_string($connection, $_GET['thrid']) . "'";

$idResult=mysqli_query($connection, $sql);

if($idResult){
  include "curNav.php";

  $curThreadId = mysqli_fetch_assoc($idResult);

  if($curThreadId['modOnly'] == true && $_SESSION['userRank'] > 1 ){
    curTopicNav($connection, $curThreadId);
    echo "You do not have permission to view this page";
  }
  else{

    curThreadNav($connection, $curThreadId);

    increaseViews($connection, $curThreadId['threadId'], $curThreadId['viewCount']);

    if($curThreadId['modOnly'] == true){
     echo "Viewing Moderated Thread";
    }
    if($_SESSION['userRank'] <2){
      echo "<form method='post' action='delete.php'><input type=hidden name='thrid' value=".mysqli_real_escape_string($connection, $_GET['thrid']).
         "><input type='submit' name='deletebutton' value='Delete This Thread'></form>";

      echo "<form name='bulletForm' method='post' action='bullet.php'>";
      echo "<input type='hidden' name='threadId' value=".$curThreadId['threadId'].">";
      if($curThreadId['bulleted'] == 1){
        echo "<input type='submit' name='bullet' value='Unbullet Thread?'>";
      }
      else{
        echo "<input type='submit' name='bullet' value='Bullet Thread?'>";
      }
      echo "</form>";
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

        $userImgSQL ="SELECT * FROM userimages WHERE `userId`=" . $row['postBy'];
        $userImg =  mysqli_fetch_assoc(mysqli_query($connection, $userImgSQL));

        echo "<article class='post' id ='" . $row['postId'] . "'>
          <table>
            <tr>
              <td rowspan='3' class='userThread' >";

              if($userImg){
                $imgSQL = "SELECT contentType, userPicture FROM userimages where userID=?";
                $stmt = mysqli_stmt_init($connection);
                mysqli_stmt_prepare($stmt, $imgSQL);
                mysqli_stmt_bind_param($stmt, "i", $row['postBy']);
                $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
                mysqli_stmt_bind_result($stmt, $type, $image);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
                echo '<p><img class="userImage" src="data:image/' . $type
                . ';base64,' . base64_encode($image).'" alt="User Image"/></p>';
              }
              else{
                echo "<figure>No Image Set for this User </figure>";
              }
                echo "<a href='user.php?userid=". $postUser['userId'] ."'>" . $postUser['userName'] . "</a>
                <p> Posts:" . $postUser['postCount'] . "</p>
                <p> Joined: " . $postUser['creationDate'] . "</p>";
                if($_SESSION['userRank'] <3){
                  echo "<a href='mail.php?userid=". $postUser['userId'] . "'>Message User</a>";
                  echo "<a href='#' id='vote'>Upvote Post</a>";
                  echo "<a href='#' id='vote'>Downvote Post</a>";
                }
                if($_SESSION['userRank'] < 2){
                  echo "<form name='fPostForm' method='post' action='fpost.php'>";
                  echo "<input type='hidden' name='postId' value=".$row['postId'].">";
                  echo "<input type='submit' name='fPostSubmit' value='Favourite Post?'>";
                  echo "</form>";
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
                    <td class='postDate'><p> Date Posted: " . $row['postDate'];

                    if($row['editDate'] != null){
                      echo " (Last Edit On " . $row['editDate'] . " by " . $row['editBy'] . ")";
                    }

                    echo "  </p> <p>Share this post! (Include actual links later) </p></td>
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
