<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

class IndexUserjubileeListener implements EventListener {
	protected $birthdays = array();

	/**
		* @see EventListener::execute()
		* @param IndexPage $eventObj
	*/

	public function execute($eventObj, $className, $eventName) {
		if (USERJUBILEE_ENABLE) {
			$this->jubilees = WCF::getCache()->addResource('UserjubileeIndex', WBB_DIR.'cache/cache.UserjubileeIndex.php', WBB_DIR.'lib/system/cache/CacheBuilderUserjubileeIndex.class.php');
			$this->jubilees = WCF::getCache()->get('UserjubileeIndex');
			if (count($this->jubilees) > 0) {
				WCF::getTPL()->assign('jubileeData', $this->jubilees);
				WCF::getTPL()->append('additionalBoxes', WCF::getTPL()->fetch('indexUserJubilee'));
			}
		}
	}
}
?>