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
class PremiumGroupMembershipCronjob implements Cronjob {

	public function execute($data) {
		$sql = "SELECT userID, groupID
						FROM wcf".WCF_N."_user_to_groups
			WHERE premiumEndTime <= ".TIME_NOW."
                AND premiumEndTime != 0";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
            $sql = "DELETE FROM wcf".WCF_N."_user_to_groups
                    WHERE userID = ".$row['userID']." && groupID = ".$row['groupID'];
            WCF::getDB()->sendQuery($sql);
            require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');
            $user = new UserEditor($row['userID']);
            $user->update('', '', '', null, null, array('rankID' => 0, 'userOnlineGroupID' => 0));
		}       
	}
}
?>