<?php
// wcf imports
require_once(WCF_DIR.'lib/data/cronjobs/Cronjob.class.php');

/**
 * Deelete expired group memberships.
 *
 * @author $foo
 * @license LGPL
 * @package de.foo.temporary.group.membership
 */
class TemporaryGroupMembershipCronjob implements Cronjob {

// 			CASE
//				WHEN wcf".WCF_N."_user_to_groups.addTime != 0  && wcf".WCF_N."_user_to_groups.addTime + wcf".WCF_N."_user_to_groups.groupTime * 24 * 60 * 60< ".TIME_NOW."
//			END

	public function execute($data) {
		$sql = "SELECT wcf".WCF_N."_user_to_groups.userID, wcf".WCF_N."_user_to_groups.groupID, wcf".WCF_N."_user_to_groups.addTime, wcf".WCF_N."_group.groupTime
						FROM wcf".WCF_N."_user_to_groups
			LEFT JOIN wcf".WCF_N."_group
				ON (wcf".WCF_N."_group.groupID=wcf".WCF_N."_user_to_groups.groupID)
			WHERE wcf".WCF_N."_user_to_groups.addTime > 0
				AND wcf".WCF_N."_group.groupTime > 0";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if(TIME_NOW > $row['addTime'] + $row['groupTime'] * 24 * 60 * 60) {
				$sql = "DELETE FROM wcf".WCF_N."_user_to_groups
					WHERE userID = ".$row['userID']." && groupID = ".$row['groupID'];
				WCF::getDB()->sendQuery($sql);
			}
		}
	}
}
?>