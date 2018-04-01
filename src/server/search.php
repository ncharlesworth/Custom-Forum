<?php
include "Temp-Client-Stuff/header.php";
?>

<?php

include 'connectType.php';
include 'connect.php';

if(checkGET()){

  /*When searching, exclude modOnly results*/

  /*Search for Keyword in content, search for keyword in */

  /*Also, function to search for list of fPosts*/

  if(empty($_GET['searchArea'])){
    echo "<form id='searchForm' class='baseForm' method='get' action=''>";
    echo "<div id='baseFormButtons'><p> Search Area: </p>";
    echo "<input type='radio' name='searchArea' value='1'> Find User by Name <br>
    <input type='radio' name='searchArea' value='2'> Find User by Email <br>
    <input type='radio' name='searchArea' value='3'> Find Posts by User<br>
    <input type='radio' name='searchArea' value='4'> Posts by Content <br>
    <input type='radio' name='searchArea' value='5'> Threads by Description/Title <br>
    <input type='radio' name='searchArea' value='6'> Search for Staff Favourites! <br></div>";
    echo "Keywords: <input type='text' name='keyword'>";
    echo "<input type='submit' value='Search'> </form>";

    /*Include min length in keyword*/
  }
  else{
    $modOnly = 0;
    $stuffSeen = 0;

    $keyword = mysqli_real_escape_string($connection, $_GET['keyword']);
    if(empty($_GET['searchArea'])){
      echo "You need to search for something!";
      echo "<a href=search.php> Click here to search again </a>";
    }
    else if(strlen($keyword) <3 && mysqli_real_escape_string($connection, $_GET['searchArea']) != 6){
      echo "Your keyword needs to use at least 3 letters.";
      echo "<a href='search.php'> Please try again.</a>";
    }
    else{
      if(mysqli_real_escape_string($connection, $_GET['searchArea']) == 1){
        $inputSQL= "SELECT userName, userId FROM users WHERE `userName`
          LIKE '%".$keyword."%'";

          $result = mysqli_query($connection, $inputSQL);

          if($result){
            if(mysqli_num_rows($result) > 1){
              while($row = mysqli_fetch_assoc($result)){
                echo "<a href='user.php?id=".$row['userId']."'>".$row['userName']."</a><br>";
              }
            }
            else{
              $results = mysqli_fetch_assoc($result);
              header("Location: user.php?id=" . $results['userId']);
            }
          }
          else{
            echo "Could not find User by that name.";
            echo "<a href='search.php'> Click Here to Return to Search page</a>";
          }

      }
      else if(mysqli_real_escape_string($connection, $_GET['searchArea']) == 2){
        $inputSQL= "SELECT userName, userId, userEmail FROM users WHERE `userEmail` LIKE '%".$keyword."%'";

          $result = mysqli_query($connection, $inputSQL);

          if($result){
            if(mysqli_num_rows($result) > 1){
              while($row = mysqli_fetch_assoc($result)){
                echo "<a href='user.php?id=".$row['userId']."'>".$row['userName']."</a> "
                . $row['userEmail'] ."<br>";
              }
            }
            else{
              $results = mysqli_fetch_assoc($result);
              header("Location: user.php?id=" . $results['userId']);
            }
          }
          else{
            echo "Could not find User with that Email.";
            echo "<a href='search.php'> Click Here to Return to Search page</a>";
          }
      }
      else if(mysqli_real_escape_string($connection, $_GET['searchArea']) == 3){

        $inputSQL= "SELECT userName, userId FROM users WHERE `userName`
          LIKE '%".$keyword."%'";

        $userResult = mysqli_query($connection, $inputSQL);

        if($userResult){
          if(mysqli_num_rows($userResult) == 1){
            $userRow = mysqli_fetch_assoc($userResult);

            $postSQL = "SELECT * FROM posts WHERE `postBy`='" . $userRow['userId'] . "' ORDER BY postDate DESC";
            $postResult = mysqli_query($connection, $postSQL);

            if(mysqli_num_rows($postResult) > 0){
              echo "<table id='topics'>";
              while($postThread = mysqli_fetch_assoc($postResult)){
                $threadSQL = "SELECT * FROM thread WHERE `threadId`=".$postThread['postThread'];

                $threadResult = mysqli_fetch_assoc(mysqli_query($connection, $threadSQL));

                if($threadResult['modOnly'] == true && $_SESSION['userRank'] > 1){
                  $modOnly = 1;
                  continue;
                }
                $stuffSeen=1;

                echo "<tr>
                       <td rowspan='2' class='unread'><img src = 'images/unread.png'
                        alt='Unread Messages'></td>";
                echo "<td class='tableTopic'> <h3> <a href='display_thread.php?thrid=" .
                        $threadResult['threadId']. "'>" . $threadResult['threadTitle'] . " </a></h3></td>";
                echo "<td rowspan='2' class='lPost'> <a href=display_thread.php?thrid="
                      . $threadResult['threadId'] . "#" . $postThread['postId']  . ">User's Post</a></td>
                      </tr>";
                echo "<tr>
                       <td class='topicDescription'>" . $threadResult['threadDescription'] ."</td>
                       </tr>";
                   }
              echo "</table>";
            }
            else{
                echo "User Has Not Posted. <br>";
                echo "<a href=search.php> Search Again</a>";
              }
            }
          else if(mysqli_num_rows($userResult) > 1){
            echo "More than 1 user was found. Please Refine your Search <br>";
            echo "<a href=search.php> Search Again</a>";
          }
        }

        else{
          echo "Cannot find this user. <br>";
          echo "<a href=search.php> Search Again</a>";
        }

      }
      else if(mysqli_real_escape_string($connection, $_GET['searchArea']) == 4){

        $inputSQL= "SELECT * FROM posts WHERE `postContent` LIKE '%".$keyword."%'";

        $postResult = mysqli_query($connection, $inputSQL);

        if($postResult){
          if(mysqli_num_rows($postResult) > 0){
            echo "<table id='topics'>";
            while($postThread = mysqli_fetch_assoc($postResult)){
              $threadSQL = "SELECT * FROM thread WHERE `threadId`=".$postThread['postThread'];

              $threadResult = mysqli_fetch_assoc(mysqli_query($connection, $threadSQL));

              if($threadResult['modOnly'] == true && $_SESSION['userRank'] > 1){
                $modOnly = 1;
                continue;
              }
              $stuffSeen=1;

              echo "<tr>
                      <td rowspan='2' class='unread'><img src = 'images/unread.png'
                        alt='Unread Messages'></td>";
              echo "<td class='tableTopic'> <h3> <a href='display_thread.php?thrid=" .
                        $threadResult['threadId']. "'>" . $threadResult['threadTitle'] . " </a></h3></td>";
              echo "<td rowspan='2' class='lPost'> <a href=display_thread.php?thrid="
                        . $threadResult['threadId'] . "#" . $postThread['postId']  . ">Post With Phrase</a></td>
                        </tr>";
              echo "<tr>
               <td class='topicDescription'>" . $threadResult['threadDescription'] ."</td>
               </tr>";
             }
          echo "</table>";
          }
          else{
            echo "Cannot find post content with this phrase. <br>";
            echo "<a href=search.php> Search Again</a>";
          }
        }
        else{
          echo "Cannot find post content with this phrase. <br>";
          echo "<a href=search.php> Search Again</a>";
        }
      }
      else if(mysqli_real_escape_string($connection, $_GET['searchArea']) == 5){
        $inputSQL= "SELECT * FROM thread WHERE `threadDescription`
          LIKE '%".$keyword."%' OR `threadTitle`LIKE '%".$keyword."%'";

        $threadResult = mysqli_query($connection, $inputSQL);

        if(mysqli_num_rows($threadResult) > 0){
            echo "<table id='topics'>";
            while($threads = mysqli_fetch_assoc($threadResult)){
              if($threads['modOnly'] == true && $_SESSION['userRank'] > 1){
                $modOnly = 1;
                continue;
              }
              $lastPostSQL = "SELECT * FROM posts WHERE `postThread`=" . $threads['threadId']
              . " ORDER BY postDate DESC";

              $lastPostResult = mysqli_fetch_assoc(mysqli_query($connection, $lastPostSQL));

              $stuffSeen = 1;

              echo "<tr>
                      <td rowspan='2' class='unread'><img src = 'images/unread.png'
                        alt='Unread Messages'></td>";
              echo "<td class='tableTopic'> <h3> <a href='display_thread.php?thrid=" .
                      $threads['threadId'] . "'>" . $threads['threadTitle'] . " </a></h3></td>";
              echo "<td rowspan='2' class='lPost'> <a href=display_thread.php?thrid="
                      . $threads['threadId'] . "#" . $lastPostResult['postId']  . ">Last Post</a></td>
                      </tr>";
              echo "<tr>
                 <td class='topicDescription'>" . $threads['threadDescription'] ."</td>
                 </tr>";
            }
          echo "</table>";
        }
        else{
          echo "User Has Not Posted. <br>";
          echo "<a href=index.php> Return to Home </a>";
        }
      }
      else if(mysqli_real_escape_string($connection, $_GET['searchArea']) == 6){

        $fPostSQL = "SELECT * FROM posts WHERE fPost=1";

        $fPostList = mysqli_query($connection, $fPostSQL);
        if($fPostList){
          if(mysqli_num_rows($fPostList) > 0){
            echo "<table>";
            while($fpostRow = mysqli_fetch_assoc($fPostList)){
              $fPostThreadSQL = "SELECT * FROM thread WHERE threadId=".$fpostRow['postThread'];
              $fPostThread = mysqli_fetch_assoc(mysqli_query($connection, $fPostThreadSQL));



              echo "<tr>
                      <td rowspan='2' class='unread'><img src = 'images/unread.png'
                        alt='Unread Messages'></td>";
              echo "<td class='tableTopic'> <h3> <a href='display_thread.php?thrid=" .
                      $fPostThread['threadId'] . "'>" . $fPostThread['threadTitle'] . " </a></h3></td>";
              echo "<td rowspan='2' class='lPost'> <a href=display_thread.php?thrid="
                      . $fPostThread['threadId'] . "#" . $fpostRow['postId']  . ">Staff Favourite Post</a></td>
                      </tr>";
              echo "<tr>
                 <td class='topicDescription'>" . $fPostThread['threadDescription'] ."</td>
                 </tr>";


            }
            echo "</table>";
          }
          else{
            echo "There are no staff favourite posts!<br>";
            echo "<a href='search.php'> Click here to search again.</a>";
          }

        }
        else{
          echo "Error finding fPosts:".mysqli_error($connection)." <br>";
          echo "<a href='search.php'> Please try again.</a>";
        }

      }
      else{
        echo "You are missing information or somehow have the wrong category. <br>";
        echo "<a href=search.php> Click here to search again </a>";
      }
      if($modOnly == 1 && $stuffSeen == 0){
        echo "Nothing to display with your permissions. <br>";
        echo "<a href='search.php'> Please try again.</a>";
      }

    }
  }


/*If I was efficient, this page would redirect to display_Thread/Topic after info is given*/


}




?>
<?php
include "Temp-Client-Stuff/footer.php";
?>
