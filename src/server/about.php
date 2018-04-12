<?php
include "Temp-Client-Stuff/header.php";
?>
<?php
echo "This is the page about my page! <br>";

echo "Default Users: tempMod, tempUser, banUser. Both have password1 as password.<br>

tempUser does not have moderator permissions, and therefore cannot delete threads, create or delete topics, or edit other user's information.<br> <br>
If the testUser tries to access pages they do not have permission to view they may get a thread title or section, but the content of posts is hidden and the threads and such are hard to access.<br><br>
Also, if they try to access controls they do not have access to, they should be stopped before changes are started.

<br> Also, banUser is banned. If you log in, you can change your account info but can't change anything else.";

echo "Login form, user creation, and password changes all have Javascrip to test the forms. Password change is viewed on a user's page when they're logged in (Or when a user with mod permissions views the page.<br><br> 
All forms (that I can think of) have mandatory php testing to make sure they're fufilled.<br>";

echo "You can create threads if a registered user, and can create topics if a registered mod. <br><br>
Also, some options (Delete and edit thread, and user information) appear for the user and thread pages only shows if the user has the appropriate rank or the current user matches the target user. <br>";

echo "Search is working! That's a pretty big thing. Search by User's to find a user, then click on the user to get a specific link that auto-searches for their posts. <br>";

echo "Users can store a custom image by viewing their profile and uploading one in the 'post Flair' section.<br>";

echo "Admins can ban users by viewing the 'admin controls' section of the user's page.<br><br>
While banned, users can view posts(and ideally they'd be able to send mail to mods) but otherwise have no more functionality than an unregistered user.

<br> Also, when you create a topic from index.php, you can choose the icon image that your subtopics will use. Otherwise, the topic uses the parent topic's icon. Same for threads.";

echo "No Ajax, and the mail.php isn't functional. It probably won't be, and neither will upvotes. But I wantd to show that I had them planned, so I've left them in.";

?>
<?php
include "Temp-Client-Stuff/footer.php";
?>
