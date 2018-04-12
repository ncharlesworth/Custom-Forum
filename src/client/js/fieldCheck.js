

window.onload = fieldLoad;

function fieldLoad(){
  document.getElementById("loginInput").addEventListener('submit', fieldCheck);
}

function fieldCheck(){
  var n = document.forms["loginInput"]["username"].value;
  var p = document.forms["loginInput"]["password"].value;
  var nums = /\d/;
  if(n == "" || n.length < 6){
    alert("Name must be six characters long.");
    return false;
  }
  else if(p.length <8){
    alert("Error:Password must be at least 8 characters long");
    return false;
  }
  else if(nums.test(p) === false){
    alert("Error: Password must have at least 1 number.")
    return false;
  }
  else{
    return submit;
  }
}
