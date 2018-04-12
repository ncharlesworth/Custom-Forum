<?php


$reqLoginArray = array("username","password");
$reqNewUserArray = array("firstname","lastname","username","email","password");
$reqSearchArray = array("username");
$reqChangePassArray = array("userId","oldPass","newPass");
$reqPassCheck = array("password", "otherPass");
$reqTopicInfo = array("topicName", "topicDescription");
$reqThreadInfo = array("threadTopic", "threadTitle", "threadDescription", "postContent");
$reqPostEditInfo = array("postChange", "postId");
$reqPostInfo = array("postThread","postContent");
$reqBanInfo = array("userId","unbanDate","confirmBan");


/* I think I want these to change a value when run, and if that value is
    >0 then the code deosn't run, and returns that Error number*/

function validatePOSTInfo($checkField){
  if ($_SERVER["REQUEST_METHOD"] == "GET"){
    echo "Please do not alter the HTML to send GET Requests.";
    echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Click here to return to previous page.";
    return false;
  }

  else if ($_SERVER["REQUEST_METHOD"] == "POST"){

    for($i = 0; $i < count($checkField); $i++){
      if ( empty($_POST["$checkField[$i]"])){
        echo "You are missing information. Please fill all fields and try again.";
        echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Click here to return to previous page.";
        return false;
        break;
      }
    }
    return true;
  }
}


function checkGET(){
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    return false;
  }
  else{
    return true;
  }
}

function checkPOST(){
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    return true;
  }
  else{
    return false;
  }
}


function validateGETInfo($checkField){
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    echo "Please do not alter the HTML to send POST Requests.";
    echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Click here to return to previous page.";
    return false;
  }

  else if ($_SERVER["REQUEST_METHOD"] == "POST"){

    for($i = 0; $i < count($checkField); $i++){
      if ( empty($_GET["$checkField[$i]"])){
        echo "You are missing information. Please fill all fields and try again.";
        echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Click here to return to previous page.";
        return false;
        break;
      }
    }
    return true;
  }
}
function passCheck($pass1, $pass2){
  if($pass1 == $pass2){
    return true;
  }
  else{
    echo "The passwords are not the same. Please correct the info.";
    echo "<a href='". $_SERVER['HTTP_REFERER'] ."'> Click here to return to previous page.";
    return false;
  }
}
?>
