<?php
include "Temp-Client-Stuff/header.php";
?>

<?php
include 'connectType.php';
include 'arrays.php';

if(validatePOSTInfo($reqPostEditInfo)){ //IMPORTANT: Include necessary ReqField from Arrays.php in validatePOSTInfo or it won't work
  include 'connect.php'; //$connection

  if(empty($_POST['postChange']) == false){
    /*postBy, userId*/

    $currentUser = $_SESSION['userId'];
    $postAuthor = $_POST['postAuthor'];


  /*The user chooses a post they want to edit. Then, javascript puts out a
      form? I Guess Edit Post could be a button that could be used to display
      a form that already exists?

      Either way, that form has 'postContent' as a variable, which gets sent Here

      I nede to make sure the user is the same as the poster, or that the rank of
        the user is <2. Before that, make sure the user actually submitted changes.
        BEFORE THAT, make sure it's post and not get.
        After those, I guess edit the post?


      NOTE: The POST needs to send the current postId, as well as

      */


    if($currentUser == $postAuthor || $_SESSION['userRank']<2){

      $date = date("Y-m-d");


    }
    else{
      echo "You do not have the authority to change this post.";
    }
  }
  else{
    echo "You have left the content blank. Don't do that."
  }


}

?>
<?php
include "Temp-Client-Stuff/footer.php"
 ?>
