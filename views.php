<?php

function getViews($connection, $threadId){

  $viewcountSQL = "SELECT viewCount FROM thread WHERE threadId=" . $threadId;

  $viewcountresults = mysqli_query($connection, $viewcountSQL);

  if($viewcountresults){
    return mysqli_fetch_assoc($viewcountresults);
  }
  else{
    echo "There was an error getting views: " . mysqli_error($connection);
  }
}

function increaseViews($connection, $threadId, $curViewCount){

  $curViewCount += 1;

  $viewcountSQL = "UPDATE thread SET viewCount=" . $curViewCount . " WHERE threadId=" . $threadId;

  $viewcountresults = mysqli_query($connection, $viewcountSQL);

  if($viewcountresults){

    if($curViewCount > 10){

      $hotThreadSQL = "UPDATE thread SET threadPic='images/hot.png' WHERE threadId=".$threadId;
      if(mysqli_query($connection, $hotThreadSQL)){

      }
      else{
        echo "There was an error making this thread HAWT: " . mysqli_error($connection);
      }

    }
  }
  else{
    echo "There was an error getting views: " . mysqli_error($connection);
  }
}

?>
