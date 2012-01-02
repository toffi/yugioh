DROP TABLE IF EXISTS wcf1_user_gallery_subscription;
CREATE TABLE wcf1_user_gallery_subscription (
 userID INT(10) NOT NULL,
 photoID INT(10) NOT NULL,
 PRIMARY KEY (userID,photoID),
 INDEX(photoID)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;