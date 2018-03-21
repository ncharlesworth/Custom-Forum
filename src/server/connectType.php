<?php

include "arrays.php";

function validatePOSTInfo($checkField){
  if ($_SERVER["REQUEST_METHOD"] == "GET"){
    echo "Please do not alter the HTML to send GET Requests.";
    return false;
  }

  else if ($_SERVER["REQUEST_METHOD"] == "POST"){

    for($i = 0; $i < count($checkField); $i++){
      if ( empty($_POST["$checkField[$i]"])){
        echo "You are missing information. Please fill all fields and try again.";
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


function validateGETInfo($checkField){
  if ($_SERVER["REQUEST_METHOD"] == "POST"){
    echo "Please do not alter the HTML to send POST Requests.";
    return false;
  }

  else if ($_SERVER["REQUEST_METHOD"] == "POST"){

    for($i = 0; $i < count($checkField); $i++){
      if ( empty($_GET["$checkField[$i]"])){
        echo "You are missing information. Please fill all fields and try again.";
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
    return false;
  }
}
?>
