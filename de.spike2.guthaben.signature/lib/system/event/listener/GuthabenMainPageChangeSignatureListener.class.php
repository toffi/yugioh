<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Buy new avartar
 *
 * @author $foo
 * @license LGPL
 * @package de.spike2.avartar
 */

class GuthabenMainPageChangeSignatureListener implements EventListener {

	/**
	 * @see Action::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		$newEventObj = array();

		if(WCF::getUser()->signatureFlat == 1) {
			foreach ($eventObj->menuItems['items']['shopPage'] as $value) {
				if($value['menuItem'] != 'wcf.guthaben.mainpage.signatureflat' && $value['menuItem'] != 'wcf.guthaben.mainpage.signature') {
					$newEventObj[] = $value;
				}
			}
			$eventObj->menuItems['items']['shopPage'] = $newEventObj;
		}
	}
}
?>