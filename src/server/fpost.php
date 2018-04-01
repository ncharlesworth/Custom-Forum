<?php
include 'Temp-Client-Stuff/header.php';
include 'connectType.php';
include 'connect.php';


if($_SESSION['userRank'] >1){
  echo "You don't have permission to be here... Shoo! <br>";
  echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page.</a>";
}

else if(checkGet()){
  echo "What are you doing here? I don't understand what you hope to GET out of this. GET it!?!?!?!?<br>";
  echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page.</a>";
}

else if(empty($_POST['postId']) == false){

  $newFpost = mysqli_real_escape_string($connection, $_POST['postId']);

  $newFpostSQL = "UPDATE posts SET fPost=1 WHERE postId=".$newFpost;

  if(mysqli_query($connection, $newFpostSQL)){
    echo "Post has been favourited!<br>";
    echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page.</a>";
  }
  else{
    echo "There was an error: ".mysqli_error($connection)."<br>";
    echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page.</a>";
  }
}
else{
  echo "Yeah. I don't know how you got to this part. <br>";
  echo "<a href='".$_SERVER['HTTP_REFERER']."'> Return to previous page.</a>";
}

mysqli_close($connection);




?>
<?php
include "Temp-Client-Stuff/footer.php";
?>
