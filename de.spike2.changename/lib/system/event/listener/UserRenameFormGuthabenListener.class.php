<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/User.class.php');
require_once(WCF_DIR.'lib/util/Guthaben.class.php');

/**
 * Buy a new username
 *
 * @author $foo
 * @license LGPL
 * @package de.spike2.changename
 */

class UserRenameFormGuthabenListener implements EventListener {

	public function execute($eventObj, $className, $eventName) {
		if (!GUTHABEN_ENABLE_GLOBAL || WCF::getUser()->userID == 0 || WCF::getUser()->changenameFlat == 1) {
			return;
		}
		if ($eventName == 'save' && $eventObj->canChangeUsername && $eventObj->username != WCF::getUser()->username && GUTHABEN_PAY_PER_CHANGENAME != 0 && !Guthaben::check(GUTHABEN_PAY_PER_CHANGENAME)) {
				throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.log.changename.nomoney', array('$currency' => WCF::getLanguage()->get('wcf.guthaben.currency'))));
		}
		elseif ($eventName == 'save' && $eventObj->canChangeUsername && $eventObj->username != WCF::getUser()->username) {
			if (GUTHABEN_PAY_PER_CHANGENAME != 0) {
			 Guthaben::sub(GUTHABEN_PAY_PER_CHANGENAME, 'wcf.guthaben.log.changename');
			}
		}
	}
}
?>