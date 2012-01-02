<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/group/GroupEditor.class.php');

/**
 * Option in acp.
 *
 * @author $foo
 * @license LGPL
 * @package de.foo.temporary.group.membership
 */
class TemporaryGroupMembershipListener implements EventListener {
	public $groupTime = '0';
	public $group = NULL;

	public function execute($eventObj, $className, $eventName) {

		if($className == 'GroupEditForm') {
			$this->group = new GroupEditor($eventObj->group->groupID);
		}

		if($className == 'GroupEditForm' && ($eventObj->group->neededPoints != 0 || $eventObj->group->neededAge != 0 || $this->group->groupType < 4)) {
			return;
		}
		if ($eventName == 'readFormParameters') {
			if (isset($_POST['groupTime'])) $this->groupTime = intval($_POST['groupTime']);
		}
		else if ($eventName == 'save') {
			$eventObj->additionalFields['groupTime'] = $this->groupTime;
			if (!($eventObj instanceof GroupEditForm)) {
				$this->groupTime = $this->groupTime = 0;
			}
		}
		else if ($eventName == 'assignVariables') {
			if (!count($_POST) && $eventObj instanceof GroupEditForm) {
				$this->groupTime = $eventObj->group->groupTime;
			}
			WCF::getTPL()->assign(array(
				'groupTime' => $this->groupTime
			));
			WCF::getTPL()->append('additionalFields', WCF::getTPL()->fetch('temporaryGroupMembership'));
		}
	}
}
?>