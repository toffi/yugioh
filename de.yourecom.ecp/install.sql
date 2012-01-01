ALTER TABLE `events` CHANGE `name` `eventName` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `events` CHANGE `name_abbreviation` `nameAbbreviation` VARCHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `events` DROP `calender;
ALTER TABLE `events` CHANGE `officialEvent` `officialEvent` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `events` CHANGE `id` `eventID` INT( 11 ) NOT NULL AUTO_INCREMENT;
ALTER TABLE `events_paarungen` CHANGE `event_ID` `eventID` INT( 11 ) NOT NULL;
ALTER TABLE `events_team` CHANGE `event_id` `eventID` INT( 11 ) NOT NULL;
ALTER TABLE `events_user` CHANGE `event_id` `eventID` INT( 11 ) NOT NULL;
ALTER TABLE `events_user` CHANGE `user_ID` `userID` INT( 11 ) NOT NULL;
ALTER TABLE `events` CHANGE `current_gameday` `currentGameday` INT( 10 ) NOT NULL;
ALTER TABLE `events_paarungen` CHANGE `scoreID_2` `scoreID2` VARCHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0';
ALTER TABLE `events_user`
  DROP `rates`,
  DROP `S_0`,
  DROP `s`,
  DROP `U`,
  DROP `N_0`,
  DROP `n`;
ALTER TABLE `events` DROP `participants_final;
ALTER TABLE `events` DROP `group_participants`;
ALTER TABLE `events` DROP `group_final`;
ALTER TABLE `events` ADD `totalRanking` TINYINT( 0 ) NOT NULL;
ALTER TABLE `events_user` ADD `replacementUserID` INT( 11 ) NOT NULL AFTER `eventID`;
DROP TABLE `events_button`;
ALTER TABLE `events_paarungen` CHANGE `scoreID1` `scoreID1` VARCHAR( 1 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
ALTER TABLE `events` DROP `nameAbbreviation`;
ALTER TABLE `events_paarungen` CHANGE `scoreID2` `scoreID2` SMALLINT(2) NULL DEFAULT NULL;
ALTER TABLE `events_paarungen` CHANGE `scoreID1` `scoreID1` SMALLINT( 2 ) NULL DEFAULT NULL;
ALTER TABLE `events_user` DROP `ID`;
ALTER TABLE `events_user` DROP `team_ID`;
DROP TABLE `events_team`;
DELETE FROM `wcf`.`events_arten` WHERE `events_arten`.`id` = 2;
ALTER TABLE `events_user` CHANGE `eventID` `eventID` INT( 11 ) NOT NULL;
ALTER TABLE `events_paarungen` CHANGE `eventID` `eventID` INT( 11 ) NOT NULL;
ALTER TABLE `events_user` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER TABLE `events_paarungen` DROP `event_group`;
ALTER TABLE `events_paarungen` DROP `teamID1`;
ALTER TABLE `events_paarungen` DROP `teamID2`;
ALTER TABLE `events_paarungen` CHANGE `matchPlanerID` `matchPlanerID` INT( 10 ) NOT NULL;
ALTER TABLE `events_paarungen` CHANGE `event_round` `eventRound` INT( 20 ) NOT NULL;
ALTER TABLE `events_user` CHANGE `ab` `s` TINYINT( 2 ) NOT NULL;
ALTER TABLE `events_user` CHANGE `bis` `till` TINYINT( 2 ) NOT NULL;
ALTER TABLE `wbb1_1_events` CHANGE `art` `mode` INT( 1 ) NOT NULL;

RENAME TABLE events TO wbb1_1_events;
RENAME TABLE events_arten TO wbb1_1_events_mode;
RENAME TABLE events_paarungen TO wbb1_1_events_paarungen;
RENAME TABLE wbb1_1_events_paarungen TO wbb1_1_events_fixtures;
RENAME TABLE events_user TO wbb1_1_events_user;

DROP TABLE `wbb1_1_events_mode`;
ALTER TABLE `wbb1_1_events` DEFAULT COLLATE utf8_general_ci;
ALTER TABLE `wbb1_1_events_paarungen` DEFAULT COLLATE utf8_general_ci;
ALTER TABLE `wbb1_1_events_user` DEFAULT COLLATE utf8_general_ci;
ALTER TABLE `wbb1_1_events` CHANGE `rules` `rules` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `wbb1_1_events` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `wbb1_1_events` CHANGE `lobby` `lobby` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `wbb1_1_events_paarungen` CHANGE `winnerID` `winnerID` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `wbb1_1_events_user` ADD `wins` TINYINT( 3 ) NOT NULL DEFAULT '0' AFTER `points` ,
ADD `loos` TINYINT( 3 ) NOT NULL DEFAULT '0' AFTER `wins`;