<?php
require_once(WCF_DIR.'lib/data/cronjobs/Cronjob.class.php');
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.birthdayonindex
 */
class IndexPageBirthdayResetCacheCronjob implements Cronjob {
	/**
	 * @see Cronjob::execute()
	 */
	public function execute($data) {
        if (BIRTHDAYONINDEX_ENABLE) {
            WCF::getCache()->clear(WBB_DIR . 'cache/', 'cache.BirthdayIndex.php');
        }
	}
}
?>