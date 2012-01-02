<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/group/GroupEditor.class.php');

/**
 * Save Entered Time.
 *
 * @author $foo
 * @license LGPL
 * @package de.foo.temporary.group.membership
 */
class TemporaryGroupMembershipAddedListener implements EventListener {
	public $groupIDs = array();
	public $userIDs = array();

	public function execute($eventObj, $className, $eventName) {
		switch ($className) {
			case 'UserAddForm':
				$username = StringUtil::trim($eventObj->username);
				$user = new UserEditor(null, null, $username);
				$this->userIDs[] = $user->userID;
				$this->groupIDs = $eventObj->groupIDs;
				break;
			case 'UserEditForm':
				$user = new UserEditor($eventObj->user->userID);
				$a = $user->getGroupIDs();
				foreach ($a as $groupIDsNew) {
					if (!in_array($groupIDsNew, WCF::getSession()->getVar('olgGroupIDs'))) {
						$this->groupIDs[] = array('groupIDs' => $groupIDsNew);
					}
				}
				$this->userIDs[] = $eventObj->userID;
				break;
			case 'UserGroupJoinAction':
				$this->userIDs[] = WCF::getUser()->userID;
				$this->groupIDs[] = array('groupIDs' => $eventObj->groupID);
				break;
			case 'UserGroupLeaderApplicationEditForm':
				if($eventObj->applicationStatus == 3) {
					$this->userIDs[] = $eventObj->application->userID;
					$this->groupIDs[] = $eventObj->application->groupID;
				}
				break;
			case 'UserGroupAdministrateForm':
				$usernameArray = explode(',', $eventObj->usernames);
				foreach ($usernameArray as $username) {
					$username = StringUtil::trim($username);
					$user = new UserEditor(null, null, $username);
					$this->userIDs[] = $user->userID;
					$this->groupIDs[] = array('groupIDs' => $eventObj->groupID);
				}
				break;
		}

		foreach ($this->userIDs as $userID) {
			foreach ($this->groupIDs as $groupID) {
				$this->group = new GroupEditor($groupID['groupIDs']);
				if ($this->group->groupType > 3) {
					$sql = "UPDATE wcf".WCF_N."_user_to_groups
									SET addTime = ".TIME_NOW."
									WHERE userID = ".$userID." && groupID = ".$groupID['groupIDs'];
					WCF::getDB()->sendQuery($sql);
				}
			}
		}
	}
}
?>