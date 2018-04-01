
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE DATABASE IF NOT EXISTS Creative_Content;
USE Creative_Content;


/*

  Heading: General, Writing, Poetry, Art, Pictures
  Headings are filled with topics: Announcements, General Discussion, Anime/Video games, etc.
  Topics are filled with threads: "Hey, Just discovered this site"
  Threads are filled with Posts. I think even the initial post in a thread should be a post
    A post has a thread ID. Likely, the thread should have a Post ID. I imagine a thread is a table of posts?
    Although one tutorial had posts connected via threadID

*/



CREATE TABLE `users` (
  `userId` int(8) NOT NULL AUTO_INCREMENT,
  `userName` varchar(25) NOT NULL,
  `userPass` varchar(255) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `creationDate` date NOT NULL,
  `userRank` int(1),
  `postCount` int(8) DEFAULT '0',
  `signature` varchar(255),
  `lastAction` timestamp NOT NULL,
  `user_status` int(8) NOT NULL,
  `unbanDate`   date,
  UNIQUE INDEX userName_unique (userName),
  PRIMARY KEY (userId)
) ENGINE=INNODB DEFAULT CHARSET=latin1;

CREATE TABLE `userimages` (
  `userId` int(8) NOT NULL,
  `userPicture` BLOB NOT NULL,
  `contentType` varchar(255) NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=INNODB DEFAULT CHARSET=latin1;

CREATE TABLE `topic` (
  `topicId`         INT(8) NOT NULL AUTO_INCREMENT,
  `topicName`        VARCHAR(255) NOT NULL,
  `topicDescription`     VARCHAR(255) NOT NULL,
  `modOnly`         boolean NOT NULL DEFAULT 0,
  `super_Topic` INT(8),
/*  `lastPost`        INT(8) NOT NULL,*/
  UNIQUE INDEX topicName_unique (topicName),
  PRIMARY KEY (topicId)
) ENGINE=INNODB DEFAULT CHARSET=latin1;

CREATE TABLE `thread` (
  `threadId`        int(8) NOT NULL AUTO_INCREMENT,
  `threadDescription`   varchar(255) NOT NULL,
  `threadDate`      datetime NOT NULL,
  `threadTopic`     int(8) NOT NULL,
  `threadTitle`     varchar(255) NOT NULL,
  `threadBy`        int(8) NOT NULL,
  `modOnly`         boolean NOT NULL DEFAULT 0,
  `bulleted`        boolean DEFAULT 0,
  `status`          int(8) NOT NULL,
  PRIMARY KEY (threadId)
) ENGINE=INNODB DEFAULT CHARSET=latin1;

CREATE TABLE `posts` (
  `postId`         int(8) NOT NULL AUTO_INCREMENT,
  `postContent`    text NOT NULL,
  `postDate`       datetime NOT NULL,
  `editDate`       datetime,
  `postThread`     int(8) NOT NULL,
  `postBy`         int(8) NOT NULL,
  `editBy`         int(8),
  `status`         int(8) NOT NULL,
  `fPost`          boolean DEFAULT 0,
  `modOnly`        boolean NOT NULL DEFAULT 0,
  PRIMARY KEY (postId)
) ENGINE=INNODB DEFAULT CHARSET=latin1;

CREATE TABLE `user_status`(
  `user_status_id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(100),
  /*Potentially add a another table for each status that lists the articles a user has upvoted*/
  PRIMARY KEY (user_status_id)
) ENGINE=INNODB DEFAULT CHARSET=latin1;

CREATE TABLE `status`(
  `status_id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(100),
  `score` int(8) NOT NULL,
  PRIMARY KEY (status_id)
) ENGINE=INNODB DEFAULT CHARSET=latin1;


ALTER TABLE thread ADD FOREIGN KEY (threadTopic) REFERENCES topic(topicId) ON DELETE CASCADE ON UPDATE CASCADE;
/*add TopicID to thread*/

ALTER TABLE thread ADD FOREIGN KEY (threadBy) REFERENCES users(userId) ON DELETE RESTRICT ON UPDATE CASCADE;
/*Add userId to thread*/

ALTER TABLE posts ADD FOREIGN KEY (postThread) REFERENCES thread(threadId) ON DELETE CASCADE ON UPDATE CASCADE;
/*add threadID to post*/

ALTER TABLE posts ADD FOREIGN KEY (postBy) REFERENCES users(userId) ON DELETE RESTRICT ON UPDATE CASCADE;
/*add userid to post*/

/*ALTER TABLE topic ADD FOREIGN KEY (lastPost) REFERENCES posts(postId) ON DELETE CASCADE ON UPDATE CASCADE;*/
/*adds a thread's lastpost ID to topic's Lastpost ID*/

/*ALTER TABLE thread ADD FOREIGN KEY (lastPost) REFERENCES posts(postId) ON DELETE CASCADE ON UPDATE CASCADE;*/
/*adds a postid to lastpost in thread*/

ALTER TABLE users ADD FOREIGN KEY (user_status) REFERENCES user_status(user_status_id) ON DELETE CASCADE ON UPDATE CASCADE;
/*adds status to user, for stuff like "Blocked" or "unverified" */

ALTER TABLE thread ADD FOREIGN KEY (status) REFERENCES status(status_id) ON DELETE CASCADE ON UPDATE CASCADE;
/*Adds status to threads, for upvotes and "blocked/frozen/admin only" */

ALTER TABLE posts ADD FOREIGN KEY (status) REFERENCES status(status_id) ON DELETE CASCADE ON UPDATE CASCADE;
/*Adds status to posts, for upvotes and probably to support admin functionality */

ALTER TABLE topic ADD FOREIGN KEY (super_Topic) REFERENCES topic(topicId) ON DELETE CASCADE ON UPDATE CASCADE;
/*Add PK from other topic to this topic; used for organizational purposes*/

ALTER TABLE posts ADD FOREIGN KEY (editBy) REFERENCES users(userId) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `userimages`
  ADD CONSTRAINT `userimages_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;


INSERT INTO `user_status`(`name`) VALUES ('testMod');
INSERT INTO `user_status`(`name`) VALUES ('testUser');


INSERT INTO `users` (`userName`,`userPass`,`userEmail`,`firstName`,`lastName`,`creationDate`,`userRank`,`lastAction`,`user_status`)
  VALUES ('testMod','7c6a180b36896a0a8c02787eeafb0e4c','testMod@datmail.com','test','Mod','2018-03-19','1','2018-03-18 19:05:05', '1');

INSERT INTO `users` (`userName`,`userPass`,`userEmail`,`firstName`,`lastName`,`creationDate`,`userRank`,`lastAction`,`user_status`)
VALUES ('testUser','7c6a180b36896a0a8c02787eeafb0e4c','testUser@datmail.com','test','User','2018-03-19','2','2018-03-18 19:05:05', '2');





/*Insert Supertopics into Topics*/
INSERT INTO `topic` (`topicName`,`topicDescription`) VALUES ('General', 'General Discussions');
INSERT INTO `topic` (`topicName`,`topicDescription`) VALUES ('Writing', 'All Things Writing');
INSERT INTO `topic` (`topicName`,`topicDescription`) VALUES ('Poetry', 'All Things Poetry');
INSERT INTO `topic` (`topicName`,`topicDescription`) VALUES ('Art', 'All Things Artsy');
INSERT INTO `topic` (`topicName`,`topicDescription`) VALUES ('Photos', 'All things Photos');

/*Insert Topics into General*/
INSERT INTO `topic` (`topicName`,`topicDescription`,`super_Topic`) VALUES ('Announcements', 'Forum Announcements Go Here', '1');
INSERT INTO `topic` (`topicName`,`topicDescription`,`super_Topic`) VALUES ('General Chat', 'Converse about things, generally', '1');
INSERT INTO `topic` (`topicName`,`topicDescription`,`super_Topic`) VALUES ('Games and Movies', 'Discuss Games and Movies', '1');
INSERT INTO `topic` (`topicName`,`topicDescription`,`super_Topic`) VALUES ('Anime and Manga', 'Discuss Anime/Manga Here', '1');

/*Insert Topics into Writing*/
INSERT INTO `topic` (`topicName`,`topicDescription`,`super_Topic`) VALUES ('Short Stories', 'Submit your Short Stories Here', '2');

/*Insert Topics into Poetry*/
INSERT INTO `topic` (`topicName`,`topicDescription`,`super_Topic`) VALUES ('Short Form', 'Submit Short Form Poetry Here', '3');

/*Insert Topics into Art*/
INSERT INTO `topic` (`topicName`,`topicDescription`,`super_Topic`) VALUES ('Digital Visual Artwork', 'Submit Images of Your Paintings', '4');

/*Insert Topics into Photos*/
INSERT INTO `topic` (`topicName`,`topicDescription`,`super_Topic`) VALUES ('Photos and Stories', 'Submit Your Photos, possibly with stories, here', '5');
