<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.birthdayonindex
 */
class IndexPageBirthdayResetCacheListener implements EventListener {

  public function execute($eventObj, $className, $eventName) {
    if ($eventName == 'save' && BIRTHDAYONINDEX_ENABLE) {
      WCF::getCache()->clear(WBB_DIR . 'cache/', 'cache.BirthdayIndex.php');
    }
  }
}
?>