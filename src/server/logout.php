<?php
include "Temp-Client-Stuff/header.php";
?>

<?php

include 'connect.php';

$time = date("Y-m-d H:i:s");
$updateLastActionSQL = "UPDATE users SET lastAction='".$time."' WHERE userId=".$_SESSION['userId'];
mysqli_query($connection, $updateLastActionSQL);


$_SESSION['loggedIn'] = false;
$_SESSION['userId'] = NULL;
$_SESSION['userName'] = "unregistered";
$_SESSION['userRank'] = 4;

mysqli_close($connection);

header("Location: " . $_SERVER['HTTP_REFERER']);

?>

<?php
include "Temp-Client-Stuff/footer.php";
?>
