
window.onload = boxFunk;

function boxFunk(){
  document.getElementById('userInfo').addEventListener('click', boxReveal);

}

function boxReveal(){
  if(document.getElementById('loggedUser').style.display != 'block'){
    document.getElementById('loggedUser').style.display = 'block';
  }
  else{
    document.getElementById('loggedUser').style.display = 'none';
  }
}
