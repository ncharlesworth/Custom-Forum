<?php
include "Temp-Client-Stuff/header.php";
?>

<?php
include "connectType.php";
include "connect.php";


  if(checkGET()){
    $user = mysqli_real_escape_string($connection, $_GET['userid']);

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


      $userSQL = "SELECT userName, userEmail, firstName, lastName, creationDate, postCount, signature, userPicture
        FROM users WHERE `userId`='" . $user . "'";

      $userInfo = mysqli_fetch_assoc(mysqli_query($connection, $userSQL));

      echo "<article class='settings' id='Account_Info'>";
      echo "<p> Username:" . $userInfo['userName'] . "</p>";
      echo "<p> Email:" . $userInfo['userEmail'] . "</p>";
      echo "<p> First Name: " . $userInfo['firstName'] . " </p>";
      echo "<p> Last Name: " . $userInfo['lastName'] . "</p>";
      echo "<p> Account Creation Date: " . $userInfo['creationDate'] . "</p>";
      echo "<p> Post Count: " . $userInfo['postCount'] . "</p>";
      echo "<figure><img src='" . $userInfo['userPicture'] . "' alt='User Picture'> </figure>";
      echo "<p> Signature: " . $userInfo['signature'] . "</p>";
      echo "</article>";

      if($settings=true){
        echo "<article class='settings' id='Post_Flair'>";
        echo "<form name='postFlair' id='userPostInfo' action=''>
          <p>Signature: <textarea name='newSig'>" . $userInfo['signature'] . "</textarea><p>
          <p>Upload User Picture: I gotta figure out how to let you do this, man.
            The database supports it though.<p>
            </form>";
        echo "</article>";

        echo "<article class='settings' id='Account_Settings'>
        <p>
          <label> Update Email: </label>
          <input type='text' name='email' placeholder='Enter Name'>
        </p>
        <p>
          <label>Reveal Account Info? </label>
          <input type='checkbox' name='first' >
        </p>
        <p>
          <label>Gender: </label>
          <input type='radio' name='gender' value='Male'> Male
          <input type='radio' name='gender' value='Female' > Female
          <input type='radio' name='gender' value='Other' > Other
        </p>
        <p>
          <input type='submit' >
          <input type='reset' >
        </p>
        </article>";
      }
      if($admin ==true){
        echo "<article class='settings' id='Admin'>";
        echo "So this is where you could ban someone, demote someone, or turn them into a moderator.";
        echo "As I'm in a crunch, and it's 3am the night before this is due, you have this, sorry.";
        echo "but it shouldn't be too hard to do. I've coded the rest with the expectation of this.";
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
