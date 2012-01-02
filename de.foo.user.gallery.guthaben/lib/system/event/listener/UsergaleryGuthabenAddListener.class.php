<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/util/Guthaben.class.php');

/**
 * Buy new avartar
 *
 * @author $foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package de.foo.user.gallery.guthaben
 */

class UsergaleryGuthabenAddListener implements EventListener {

	/**
	 * @see Action::saved()
	 */
	public function execute($eventObj, $className, $eventName) {
		$postingText = '';

		if (!GUTHABEN_ENABLE_GLOBAL || WCF::getUser()->userID == 0){
			return;
		}

		if (GUTHABEN_EARN_PER_GALERIE_UPLOAD != 0) {
			if(count($eventObj->photos) == 1) {
				$postingText = WCF::getLanguage()->get('wcf.guthaben.log.galerieUpload.singulary', array('$countPictures' => count($eventObj->photos)));
			}
			else {
				$postingText = WCF::getLanguage()->get('wcf.guthaben.log.galerieUpload.plural', array('$countPictures' => count($eventObj->photos)));
			}
			Guthaben::add(GUTHABEN_EARN_PER_GALERIE_UPLOAD * count($eventObj->photos), $postingText);
		}
	}
}
?>