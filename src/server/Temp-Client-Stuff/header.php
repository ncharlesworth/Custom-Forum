
<?php
include "sessionInfo.php";
?>

<!DOCTYPE html>
<html>
  <head lang="en">
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/reset.css" />
    <link rel='stylesheet' type='text/css' href='css/styling.css' />
    <script type="text/javascript" src="js/creative_content.js"></script>
    <?php
    /*  <script type="text/javascript" src="js/fieldCheck.js"></script>
    <script type="text/javascript" src="js/userInfoBoxReveal.js"></script> */?>
    <title> Creative Content</title>
  </head>
  <body>
    <header>
      <div id=topBar>
        <a id=home href="index.php"><img src = "images/logo.png" alt="Home"></a>
        <?php
        /*  <form id=search method="get" action="search.php">
        <input type="text" name="Search"/>
        </form>*/
        ?>
        <nav>
          <ul>
            <?php
            echo "<li><a id='search' href=search.php> Search </a></li>"
            ?>
            <li><a href="about.php">About</a></li>
            <li><a href="display_Topic.php?topid=6">News</a></li>
            <li id="liButtonBackground"><button type="button" id="loginButton">
              <?php
              if($_SESSION['loggedIn']) {
                echo $_SESSION['userName'];
              }
              else{
                echo 'Login';
              }
             ?></li>
          </ul>
        </nav>
      </div>

      <article id="loggedUser">
        <div id="userinfobox">
        <?php
          if($_SESSION['loggedIn']){
            echo '<div>';
              echo '<figure>';
                echo "<a href=user.php?id=" . $_SESSION['userId'] . "><img src = 'images/user.png' alt='Go to your User Page'></a>";
              echo '</figure>';
            echo '</div>';
            echo '<div>';
              echo "<a href='mail.php'><img src = 'images/mail.png' alt='Go to your Mail'></a>";
            echo '</div>';
            echo 'Not you? <a href="logout.php">Sign out</a>';
          }
          else{
            echo  '<form id="loginInput" method="post" action="login.php">';
              echo  '<fieldset>';
                echo  '<p>';
                 echo  '<label> Username: </label>';
                 echo  '<input type="text" class="required" name="username" pattern="[a-zA-Z0-9!@#$%^*_|]{6-20}" placeholder="Username">';
                echo  '</p>';
                echo  '<p>';
                 echo  '<label> Password: </label>';
                 echo  '<input type="password" class="required" name="password" pattern="[a-zA-Z0-9!@#$%^*_|]{8-40}" placeholder="Password">';
                echo  '</p>';
                echo  '<p>';
                 echo  '<input type="submit" value="Submit" >';
                echo  '</p>';
                echo '<a href="new_User.php">Create an Account</a>.';
              echo  '</fieldset>';
            echo  '</form>';
          }
        ?>
        </div>
      </article>
    </header>
    <div id="main">
