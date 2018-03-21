window.onload = boxFunk;

function boxFunk(){
  document.getElementById('userInfo').addEventListener('click', boxReveal);

  var loginInput = document.getElementById('loginInput');
  if(loginInput){
    loginInput.addEventListener('submit', fieldCheck);
  }

}

function boxReveal(){
  if(document.getElementById('loggedUser').style.display != 'block'){
    document.getElementById('loggedUser').style.display = 'block';
  }
  else{
    document.getElementById('loggedUser').style.display = 'none';
  }
}



function fieldCheck(e){
  var n = document.forms["loginInput"]["username"].value;
  var p = document.forms["loginInput"]["password"].value;
  var nums = /\d/;
  if(n == "" || n.length < 6){
    alert("Name must be six characters long.");
    e.preventDefault();
  }
  else if(p.length <8){
    alert("Password must be at least 8 characters long");
    e.preventDefault();
  }
  else if(nums.test(p) === false){
    alert("Password must have at least 1 number.")
    e.preventDefault();
  }
}

var x = "Account_Info";
function myFunction(y){
  var i = document.getElementById(y);
  if(x !== y){
    (document.getElementById(x)).style.display = "none";
    i.style.display = "table-cell";
    x = y;
  }
}
function displayAccount(){
  var p = document.getElementById('loggedUser');
  if(p.style.display == "none"){
    p.style.display = "table-cell";
  }
  else if(p.style.display === "table-cell"){
    p.style.display = "none";
  }
}
