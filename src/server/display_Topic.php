<?php
include "Temp-Client-Stuff/header.php";
?>

<?php
  include "connect.php";


  if(empty($_GET['topid'])){
        echo "You did not access this page by choosing a topic to display";
  }
  else{

    $sql = "SELECT * FROM thread WHERE `threadTopic`='" . mysqli_real_escape_string($connection, $_GET['topid']) . "' ORDER BY `threadId` DESC";

    $sql2 = "SELECT * FROM topic WHERE `topicId`='" . mysqli_real_escape_string($connection, $_GET['topid']) . "'";

    $results = mysqli_query($connection, $sql);

    $results2 = mysqli_query($connection, $sql2);

    include "curNav.php";
    echo curTopicNav($connection, mysqli_real_escape_string($connection, $_GET['topid']));

    $isSupertopic = mysqli_fetch_assoc($results2);

    /*
          if($isSupertopic['modOnly'] == true && $_SESSION['userRank'] >1){
            echo "You do not have permission to view this section";
          }*/

    if($isSupertopic['modOnly'] == true && $_SESSION['userRank'] > 1){
      echo "You do not have permission to view this page";
    }
    else if($isSupertopic['super_Topic'] == NULL){
      if($isSupertopic['modOnly']){
        echo "Viewing Moderated Topic";
      }

     if( $_SESSION['userRank'] < 2){
       echo "<a href='topic.php?topid=".  mysqli_real_escape_string($connection, $_GET['topid']) ."' id='newTopic'> Create New Topic </a>";
     }

     echo "<table>";

     $topSQL = "SELECT * FROM topic WHERE `super_Topic`='" . mysqli_real_escape_string($connection, $_GET['topid']) . "'";
     $topResult = mysqli_query($connection, $topSQL);
     while($row = mysqli_fetch_assoc($topResult)){

      $threadSQL = "SELECT * FROM thread WHERE `threadTopic`='". $row['topicId'] ."' ORDER BY `threadId` DESC";
      $threadResult = mysqli_fetch_assoc(mysqli_query($connection, $threadSQL));

      $postidSQL = "SELECT * FROM posts WHERE `postThread`='" . $threadResult['threadId'] . "' ORDER BY `postId` desc";
      $postResult = mysqli_fetch_assoc(mysqli_query($connection, $postidSQL));

      echo "<tr>";
      echo "<td rowspan='2' class='unread'><img src = 'images/unread.png' alt='Unread Messages'></td>";
      echo "<td class='tableTopic'> <h3><a href='display_Topic.php?topid=" . $row['topicId'] . "'>" . $row['topicName'] . "</a></h3></td>";

      echo "<td rowspan='2' class='lPost'>";
      if($threadResult != null){
        echo "<a href='display_thread.php?thrid=" . $postResult['postThread'] . "#" . $postResult['postId'] . "'>Last Post</a></td>";
      }
      else{
        echo "No Posts!";
      }
      echo "</tr>";
      echo "<tr>
           <td class='topicDescription'>" . $row['topicDescription'] .  "</td>
           </tr>";
    }
     echo "</table>";
     mysqli_free_result($topResult);
    }
    else{
      if($isSupertopic['modOnly']){
        echo "Viewing Moderated Topic";
      }
      if($_SESSION['userRank'] <3){
        echo "<a href='thread.php?topid=". mysqli_real_escape_string($connection, $_GET['topid']) ."' id='newThread'> Create New Thread </a>";
      }
      if(mysqli_num_rows($results) > 0){

         /*threadId, threadDescription, threadDate, threadTitle*/



         /*So, this is where the stuff to display a The Pinned Threads would go*/
         /*------ this is now the topic threads*/


         echo "<table id='topics'>";
         while($row = mysqli_fetch_assoc($results)){


           $postSQL = "SELECT * FROM posts WHERE `postThread`='" . $row['threadId']
             . "' ORDER BY `postId` DESC";
           $curPostResult = mysqli_query($connection, $postSQL);
           $curPost =  mysqli_fetch_assoc($curPostResult);

           echo "<tr>
                  <td rowspan='2' class='unread'><img src = 'images/unread.png'
                   alt='Unread Messages'></td>";
           echo "<td class='tableTopic'> <h3> <a href='display_thread.php?thrid=" .
                   $row['threadId'] . "'>" . $row['threadTitle'] . " </a></h3></td>";
           echo "<td rowspan='2' class='lPost'> <a href=display_thread.php?thrid="
                 . $row['threadId'] . "#" . $curPost['postId']  . ">Last Post</a></td>
                 </tr>";
           echo "<tr>
                  <td class='topicDescription'>" . $row['threadDescription'] ."</td>
                  </tr>";
              }
          echo "</table>";



          mysqli_free_result($curPostResult);
        }
        else{
          echo "There are no threads associated to this topic";
        }
      }

      mysqli_free_result($results);
      mysqli_free_result($results2);
    }

/**/


  mysqli_close($connection);

?>

<?php
include "Temp-Client-Stuff/footer.php";
?>
