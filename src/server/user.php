<?php
include "Temp-Client-Stuff/header.php";
?>

<?php
include "connectType.php";
include "connect.php";


  if(checkGET()){
    $user = mysqli_real_escape_string($connection, $_GET['id']);

    $userGetSQL = "SELECT * FROM users WHERE `userId`='" . $user . "'";

    $userResult = mysqli_query($connection, $userGetSQL);

    $potentialUsers = mysqli_fetch_assoc($userResult);

    $settings = false;
    $admin = false;

    if($potentialUsers != null){
      if($_SESSION['userRank'] <2 || $_SESSION['userId'] == $potentialUsers['userId']){
        echo "<div id='buttons'>
        <button onclick=myFunction('Account_Info')> Account Info </button>
        <button onclick=myFunction('Post_Flair')> Post Flair </button>";
        echo "<button onclick=myFunction('Account_Settings')> Account Settings </button>";
        $settings = true;
      }
      if($_SESSION['userRank'] <2){
        echo  "<button onclick=myFunction('Admin')> Admin </button>";
        $admin = true;
        echo "</div>";
      }
      else if($settings == true){
        echo "</div>";
      }


      $userSQL = "SELECT * FROM users WHERE `userId`=" . $user;

      $userInfo = mysqli_fetch_assoc(mysqli_query($connection, $userSQL));

      $userImgSQL ="SELECT * FROM userimages WHERE `userid`=" . $user;

      $userImg =  mysqli_fetch_assoc(mysqli_query($connection, $userImgSQL));


      echo "<article class='settings' id='Account_Info'>";
      echo "<p> Username:" . $userInfo['userName'] . "</p>";
      echo "<p> Email:" . $userInfo['userEmail'] . "</p>";
      echo "<p> First Name: " . $userInfo['firstName'] . " </p>";
      echo "<p> Last Name: " . $userInfo['lastName'] . "</p>";
      echo "<p> Account Creation Date: " . $userInfo['creationDate'] . "</p>";
      echo "<p> Post Count: " . $userInfo['postCount'] . "</p>";
      echo "<p> Last Action: " . $userInfo['lastAction'] . "</p>";
      echo "<p> Account Status: ";
      if($userInfo['userRank'] <2){
        echo "Server Moderator </p>";
      }
      else if($userInfo['userRank'] ==4){
        echo "Banned User </p>";
      }
      else{
        echo "Registered User </p>";
      }
      echo "<p><a href='search.php?searchArea=3&keyword=". $userInfo['userName']. "'>
        Search posts by this user</a></p>";
      if($userImg){
        $imgSQL = "SELECT contentType, userPicture FROM userimages where userID=?";
        $stmt = mysqli_stmt_init($connection);
        mysqli_stmt_prepare($stmt, $imgSQL);
        mysqli_stmt_bind_param($stmt, "i", $user);
        $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
        mysqli_stmt_bind_result($stmt, $type, $image);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        echo '<img src="data:image/'.$type.';base64,'.base64_encode($image).'"/>';
      }
      else{
        echo "<figure>No Image Set for this User </figure>";
      }



      if($userInfo['signature'] == NULL){
        echo "<p> No Signature has been Set for this User";
      }
      else{
       echo "<p> Signature: " . $userInfo['signature'] . "</p>";
      }
      echo "</article>";

      if($settings=true){
        echo "<article class='settings' id='Post_Flair'>";
        echo "<form name='postFlair' id='userPostInfo' method='post' action='postflair.php' enctype='multipart/form-data'>
          <input type='hidden' name='userId' value='". $user ."'>
          <p>Signature: <textarea name='newSig'>" . $userInfo['signature'] . "</textarea><p>
          <p>Upload User Picture: <input type='file' name='userImage' id='userImage'><p>
          <input type='submit' name='submitPostFlair' value='Submit'>
            </form>";
        echo "</article>";

        echo "<article class='settings' id='Account_Settings'>
        <form name='accountSettings' method='post' action='updateAccountInfo.php'>
        <input type='hidden' name='userId' value='". $user ."'>
        <p>
          <label> Update Email: </label>
          <input type='text' name='email' value='".$userInfo['userEmail']."'>
        </p>
        <p>
        First Name:
        <input type='text' name='firstname' id='firstname' value='".$userInfo['firstName']."'>
        </p>
        <p>
        Last Name:
        <input type='text' name='lastname' id='lastname' value='".$userInfo['lastName']."'>
        </p>
        <p><input type='submit' value='Submit Info Change'></p>
        </form><br>
        <form name='changePass' id='changePassForm' method='post' action='changepass.php'>
        <p>Change Password:</p>
        <input type='hidden' name='userId' value=".$user.">
        Old Password: <input type='password' name='oldPass'><br>
        New Password: <input type='password' name='newPass'><br>
        <input type='submit' value='Submit Password Change'>
        </form>
        </article>";
      }
      if($admin ==true){
        echo "<article class='settings' id='Admin'>";
        echo "<form id='adminControls' class='admin' method='post' action='adminControls.php'>";
        echo "<input type='hidden' name='userId' value='". $user ."'>";

        if($userInfo['userRank'] ==4){
          echo "User is banned until:" . $userInfo['unbanDate'] . "<br>";
          echo "Unban User? <input type='checkbox' name='unBan1' value='checked'><br>";
          echo "Are you certain? <input type='checkbox' name='unBan2' value='checked'><br>";
          echo "<input type='submit' name='banButton' value='Unban'>";
        }
        else{
          echo "Ban User Until: <input type='date' name='unbanDate'><br>";
          echo "Confirm! <input type='checkbox' name='confirmBan' value='checked'><br>";
          echo "<input type='submit' name='banButton' value='Submit Ban'>";
        }
        echo "</form>";
        if($userInfo['userRank'] == 2){
          echo "<br><br><form class='admin' method='post' action='adminControls.php'>";
          echo "<input type='hidden' name='userId' value='". $user ."'>";
          echo "Make user a Moderator? <input type='checkbox' name='modUpgrade' value='checked'><br>";
          echo "<input type='submit' name='modGradeButton' value='modUpgrade'>";
          echo "</form>";
        }
        else if ($userInfo['userRank'] == 1){
          echo "<br><br><form class='admin' method='post' action='adminControls.php'>";
          echo "<input type='hidden' name='userId' value='". $user ."'>";
          echo "Make user into a Regular User? <input type='checkbox' name='moddowngrade' value='checked'><br>";
          echo "<input type='submit' name='modGradeButton' value='modDowngrade'>";
          echo "</form>";
        }
        echo "</article>";
      }
    }
    else{
      echo "This user does not exist. Unless it does, then consider this an error. But it probably doesn't. Probably.";
    }
  }

?>

<?php
include "Temp-Client-Stuff/footer.php";
?>
