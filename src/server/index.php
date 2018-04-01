<?php
include "Temp-Client-Stuff/header.php";
?>

<?php
  include "connect.php";


  /*Could change this so if there's no ID value in GET, it just shows the home
    page. Otherwise, it shows the topic board requested.*/

  $topicSQL = "SELECT * FROM topic WHERE `super_Topic` IS NULL";
  $results = mysqli_query($connection, $topicSQL);


  if(mysqli_num_rows($results) > 0){

    $fPostSQL = "SELECT * FROM posts WHERE fPost=1 ORDER BY postId DESC";
    $fPostResults = mysqli_query($connection, $fPostSQL);
    if($fPostResults){
      $fpostPlaceholders = 4 - mysqli_num_rows($fPostResults);
      $fPosts = 0;

      for($i = 0; $i < $fpostPlaceholders; $i++){
        $fPosts += 1;
        echo "<figure class='fPost'>";
        echo "<a href='#'><img src = 'images/default_fpost.png' alt='Go to a Staff favourite post'></a>";
        echo "<figcaption> fPost ".$fPosts."</figcaption>" ;
        echo "</figure>" ;
      }

      while($fPostRows = mysqli_fetch_assoc($fPostResults)){

        if($fPosts >3){
          break;
        }
        if($fPostRows['modOnly'] == true){
          continue;
        }

        $fpostThreadSQL = "SELECT threadTitle, threadTopic FROM thread WHERE threadId=".$fPostRows['postThread'];
        $fPostThreadTitle = mysqli_fetch_assoc(mysqli_query($connection, $fpostThreadSQL));

        $fpostTopicSQL = "SELECT super_Topic FROM topic WHERE topicId=".$fPostThreadTitle['threadTopic'];
        $fpostTopic = mysqli_fetch_assoc(mysqli_query($connection, $fpostTopicSQL));

        echo "<figure class='fPost'>";
        echo "<a href=display_Thread.php?thrid=".$fPostRows['postThread']."#".$fPostRows['postId']."'><img src='images/";
        if($fpostTopic['super_Topic'] == 1){
          echo "book.png";
        }
        else if($fpostTopic['super_Topic'] == 2){
          echo "poetry.png";
        }
        else if($fpostTopic['super_Topic'] == 3){
          echo "art.png";
        }
        else if($fpostTopic['super_Topic'] == 4){
          echo "photo.png";
        }
        else{
          echo "default_fpost.png";
        }
        echo "' alt='".$fPostThreadTitle['threadTitle']."'></a>";
        echo "<figcaption>".$fPostThreadTitle['threadTitle']."</figcaption>";
        echo "</figure>" ;
        $fPosts += 1;
      }
    }
    else{
      echo "<figure class='fPost'>";
      echo "<a href='#'><img src = 'images/book.png' alt='Go to a Staff favourite post'></a>";
      echo "<figcaption> fPost 1</figcaption>" ;
      echo "</figure>" ;
      echo "<figure class='fPost'>" ;
      echo "<a href='#'><img src = 'images/book.png' alt='Go to a Staff favourite post'></a>" ;
      echo "<figcaption> fPost 2</figcaption>" ;
      echo "</figure>" ;
      echo "<figure class='fPost'>" ;
      echo "<a href='#'><img src = 'images/book.png' alt='Go to a Staff favourite post'></a>";
      echo "<figcaption> fPost 3</figcaption>";
      echo "</figure>";
      echo "<figure class='fPost'>";
      echo "<a href='#'><img src = 'images/book.png' alt='Go to a Staff favourite post'></a>";
      echo "<figcaption> fPost 4</figcaption>";
      echo "</figure>";
    }

    if( $_SESSION['userRank'] < 2){
      echo "<a href='topic.php?topid=0' id='newTopic'> Create New Topic </a>";
    }

    while ($row = mysqli_fetch_assoc($results)){

      if($row['modOnly'] == true && $_SESSION['userRank'] > 1){
        continue;
      }

      echo "<div class='heading'>";
      echo "<table>";
      echo "<tr>";
      echo "<th colspan='3'><a href='display_Topic.php?topid=". $row['topicId'] ."'>" . $row['topicName'] . "</a></h2></th>";
      echo "</tr>";

      $tempSQL = "SELECT * FROM topic WHERE `super_Topic`='" . $row['topicId'] . "'";
      $results2 = mysqli_query($connection, $tempSQL);

      while($row2 = mysqli_fetch_assoc($results2)){

        if($row2['modOnly'] == true && $_SESSION['userRank'] > 1){
          continue;
        }

        echo "<tr>";
        echo "<td rowspan='2' class='unread'><img src = 'images/unread.png' alt='Unread Messages'></td>";
        echo "<td class='tableTopic'> <h3><a href='display_Topic.php?topid=". $row2['topicId'] ."'>" . $row2['topicName'] . "</a></h3></td>";
        echo "<td rowspan='2' class='lPost'>";

        $threadSQL = "SELECT * FROM thread WHERE `threadTopic`='". $row2['topicId']."' ORDER BY `threadId` DESC";
        $threadResult = mysqli_fetch_assoc(mysqli_query($connection, $threadSQL));

        $postidSQL = "SELECT * FROM posts WHERE `postThread`='" . $threadResult['threadId'] . "' ORDER BY `postId` desc";
        $postResult = mysqli_fetch_assoc(mysqli_query($connection, $postidSQL));

        if($threadResult != null){
          echo "<a href='display_thread.php?thrid=" . $postResult['postThread'] . "#" . $postResult['postId'] . "'>Last Post</a></td>";
        }
        else{
          echo "No Posts!";
        }
        echo "</td>";
        echo "</tr>";
        echo "<tr>
              <td class='topicDescription'>" . $row2['topicDescription'] .  "</td>
              </tr>";
      }
      echo "</table>";
      echo "</div>";
    }
    mysqli_free_result($results2);
    mysqli_free_result($results);
    mysqli_close($connection);
  }
  else{
    echo "There was an error collecting topic tables:" . mysqli_error($connection);
  }
 ?>


<?php
include "Temp-Client-Stuff/footer.php";
?>
