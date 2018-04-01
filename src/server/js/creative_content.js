window.onload = boxFunk;

function boxFunk(){
  document.getElementById('loginButton').addEventListener('click', boxReveal);

  var newUserForm = document.getElementById('newUserForm');
  if(newUserForm){
    newUserForm.addEventListener('submit', checkNewUserFields);
  }

  var loginInput = document.getElementById('loginInput');
  if(loginInput){
    loginInput.addEventListener('submit', fieldCheck);
  }

  var changePassForm = document.getElementById('changePassForm');
  if(changePassForm){
    changePassForm.addEventListener('submit', passCheck);
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

function checkNewUserFields(e){
  var n = document.forms["newUserForm"]["username"].value;
  var p = document.forms["newUserForm"]["password"].value;
  var q = document.forms["newUserForm"]["password-check"].value;
  var f = document.forms["newUserForm"]["firstname"].value;
  var l = document.forms["newUserForm"]["lastname"].value;
  var e = document.forms["newUserForm"]["email"].value;
  var nums = /\d/;
  if(n == "" || n.length < 6){
    alert("Username must be six characters long.");
    event.preventDefault();
  }
  else if(p.length <8){
    alert("Password must be at least 8 characters long");
    event.preventDefault();
  }
  else if(nums.test(p) === false){
    alert("Password must have at least 1 number.")
    event.preventDefault();
  }
  else if(q.length <8){
    alert("Second Password must be at least 8 characters long");
    event.preventDefault();
  }
  else if(nums.test(q) === false){
    alert("Second Password must have at least 1 number.")
    event.preventDefault();
  }
  else if(f == "" || f.length < 4){
    alert("FirstName must be four characters long.");
    event.preventDefault();
  }
  else if(l == "" || l.length < 4){
    alert("LastName must be four characters long.");
    event.preventDefault();
  }
  else if(e == ""){
    alert("Email cannot be empty.");
    event.preventDefault();
  }
}

function passCheck(event){
  var a = document.forms["changePassForm"]["oldPass"].value;
  var b = document.forms["changePassForm"]["newPass"].value;
  var nums = /\d/;
  if(a == "" || a.length <8){
    alert("Old Password must be at least 8 characters long.");
    event.preventDefault();
  }
  else if(nums.test(a) === false){
    alert("Old Password must have at least 1 number.")
    event.preventDefault();
  }
  else if(b == "" || b.length <8){
    alert("New Password must be at least 8 characters long.");
    event.preventDefault();
  }
  else if(nums.test(b) === false){
    alert("New Password must have at least 1 number.")
    event.preventDefault();
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
