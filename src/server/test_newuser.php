
<?php
include "Temp-Client-Stuff/header.php"
 ?>

<form method="post" action="new_User.php" id="mainForm" >
  First Name:<br>
  <input type="text" name="firstname" id="firstname" class="required">
  <br>
  Last Name:<br>
  <input type="text" name="lastname" id="lastname" class="required">
  <br>
  Username:<br>
  <input type="text" name="username" id="username" class="required">
  <br>
  email:<br>
  <input type="text" name="email" id="email" class="required">
  <br>
  Password:<br>
  <input type="password" name="password" id="password" class="required">
  <br>
  Re-enter Password:<br>
  <input type="password" name="password-check" id="password-check" class="required">
  <br><br>
  <input type="submit" value="Create New User">
</form>

<?php
include "Temp-Client-Stuff/footer.php"
 ?>
