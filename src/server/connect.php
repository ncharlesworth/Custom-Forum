<?php

$host = "cosc360.ok.ubc.ca";
$database = "db_49440167";
$user = "49440167";
$password = "49440167";

$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null)
{
  $output = "<p>Unable to connect to database!" . $error ."</p>";
  echo "I don't know what the correct information is! Sorry Scott :/";
  exit($output);
}

?>
