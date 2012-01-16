<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/util/Guthaben.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

/**
 * Buy a usertitle
 *
 * @author $foo
 * @license LGPL
 * @package de.spike2.guthaben.benutzertitle
 */

class UserChangeUserTitleFormGuthabenListener implements EventListener {

	/**
	* @see EventListener::execute()
	*/

	public function execute($eventObj, $className, $eventName) {
		if (!GUTHABEN_ENABLE_GLOBAL || WCF::getUser()->userID == 0 || WCF::getUser()->useritleFlat == 1) {
			return;
		}
		$this->user = new UserProfile(WCF::getUser()->userID);
		$this->titel = $this->user->getUserTitle();

		switch ($eventName) {
			case 'readFormParameters':
				if(!empty($_POST['userTitle']) && isset($_POST['userTitle']) && $this->user->getUserTitle() != htmlspecialchars($_POST['userTitle'])) {
					if (GUTHABEN_PAY_PER_USERTITLE != 0 && !Guthaben::check(GUTHABEN_PAY_PER_USERTITLE)) {
						throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.log.userTitle.nomoney', array('$currency' => WCF::getLanguage()->get('wcf.guthaben.currency'))));
					}
				}
			break;

			case 'save':
				if (!empty($this->titel) && isset($_POST['userTitle']) && !empty($_POST['userTitle']) && $this->titel != htmlspecialchars($_POST['userTitle'])) {
					if (GUTHABEN_PAY_PER_USERTITLE != 0) {
						Guthaben::sub(GUTHABEN_PAY_PER_USERTITLE, 'wcf.guthaben.log.userTitle');
					}
				}
				break;
		}
	}
}
?>