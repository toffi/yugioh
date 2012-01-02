<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/util/Guthaben.class.php');

/**
 * Buy new avartar
 *
 * @author $foo
 * @license LGPL
 * @package de.spike2.avartar
 */

class UserChangeAvatarFormGuthabenListener implements EventListener {

	/**
	 * @see Action::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (!GUTHABEN_ENABLE_GLOBAL || WCF::getUser()->userID == 0 || WCF::getUser()->avatarFlat == 1) {
			return;
		}

		switch ($eventName) {
			case 'readParameters':
				if (GUTHABEN_PAY_PER_AVATAR != 0 && !Guthaben::check(GUTHABEN_PAY_PER_AVATAR)) {
					throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.log.avatar.nomoney', array('$currency' => WCF::getLanguage()->get('wcf.guthaben.currency'))));
				}
				break;
			case 'save':
				if (GUTHABEN_PAY_PER_AVATAR != 0) {
					Guthaben::sub(GUTHABEN_PAY_PER_AVATAR, 'wcf.guthaben.log.avatar');
				}
				break;
		}
	}
}
?>