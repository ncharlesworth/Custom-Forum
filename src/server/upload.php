<?php

/*Call this page by checking a file was sent first, in an if statement*/

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["userImage"]["name"]);
$upload = false;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
$check = getimagesize($_FILES["userImage"]["tmp_name"]);

if($check == true){
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif" ) {
    echo "You may only upload: JPG, PNG, and  GIF files.";
  }
  else if ($_FILES["userImage"]["size"] > 1000000) {
    echo "Your image can only be less than 1mb.";
  }
  else{
    $upload = true;
  }
}
else{
  echo "Upload a real an image please.";
}


/*I should edit this so it calls a function that automatically alters an image
    to be PNG and a specific size. That way it doesn't matter. Should still have
      a max UPLOAD size of 5mb?*/

?>
