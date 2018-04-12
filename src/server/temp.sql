ALTER TABLE `thread` ADD `threadPic` VARCHAR(255) NOT NULL DEFAULT "images/default.png";

ALTER TABLE `thread` ADD `viewCount` int(8) NOT NULL DEFAULT 0;

ALTER TABLE `topic` ADD `topicPic` VARCHAR(255) NOT NULL;
