<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/util/Guthaben.class.php');

/**
 * Buy a signature
 *
 * @author $foo
 * @license LGPL
 * @package de.spike2.guthaben.signature
 */

class UserChangeSignatureFormGuthabenListener implements EventListener {

	/**
	* @see EventListener::execute()
	*/

	public function execute($eventObj, $className, $eventName) {
		if (!GUTHABEN_ENABLE_GLOBAL || WCF::getUser()->userID == 0 || WCF::getUser()->signatureFlat == 1) {
			return;
		}

		switch ($eventName) {
			case 'readData':
			if (GUTHABEN_PAY_PER_SIGNATURE != 0 && !Guthaben::check(GUTHABEN_PAY_PER_SIGNATURE)) {
				throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.log.signature.nomoney', array('$currency' => WCF::getLanguage()->get('wcf.guthaben.currency'))));
			}
			break;
			case 'save':
				if (GUTHABEN_PAY_PER_SIGNATURE != 0) {
					Guthaben::sub(GUTHABEN_PAY_PER_SIGNATURE, 'wcf.guthaben.log.signature');
				}
			break;
		}
	}
}
?>