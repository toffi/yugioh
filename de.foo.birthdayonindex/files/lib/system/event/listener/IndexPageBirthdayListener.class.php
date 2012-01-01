<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.birthdayonindex
 */
class IndexPageBirthdayListener implements EventListener {
    protected $birthdays = array();

    /**
      * @see EventListener::execute()
      * @param IndexPage $eventObj
      */
    public function execute($eventObj, $className, $eventName) {
      if (BIRTHDAYONINDEX_ENABLE) {
        $this->birthdays = WCF::getCache()->addResource('BirthdayIndex', WBB_DIR.'cache/cache.BirthdayIndex.php', WBB_DIR.'lib/system/cache/CacheBuilderBirthdayIndex.class.php');
        $this->birthdays = WCF::getCache()->get('BirthdayIndex');
        if (count($this->birthdays) > 0) {
            WCF::getTPL()->assign('birthdayData', $this->birthdays);
            WCF::getTPL()->append('additionalBoxes', WCF::getTPL()->fetch('geburtstagindex'));
        }
      }
    }
}
?>