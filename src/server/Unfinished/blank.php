<?php

include 'connectType.php';
include 'arrays.php';

if(validatePOSTInfo()){ //IMPORTANT: Include necessary ReqField from Arrays.php in validatePOSTInfo or it won't work
  include 'connect.php'; //$connection
  //Not sure how clean it is, but if there's an error nothing below would proceed. So it should be fine to write whatever.
  //Otherwise, I should include the Error check here


}










?>
