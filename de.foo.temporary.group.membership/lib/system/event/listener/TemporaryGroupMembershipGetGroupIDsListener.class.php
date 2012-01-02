<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Save old groups.
 *
 * @author $foo
 * @license LGPL
 * @package de.foo.temporary.group.membership
 */
class TemporaryGroupMembershipGetGroupIDsListener implements EventListener {

	public function execute($eventObj, $className, $eventName) {
		$user = new UserEditor($eventObj->user->userID);
		WCF::getSession()->register('olgGroupIDs', $user->getGroupIDs());
	}
}
?>