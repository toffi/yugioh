<?php
require_once(WCF_DIR.'lib/data/cronjobs/Cronjob.class.php');
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 *  Do cronjob to create the cache file
 *
 * @author $foo
 * @license LGPL
 * @package de.foo.userjubilee
 */

class UserjubileeCronjob implements Cronjob {
	/**
	 * @see Cronjob::execute()
	 */
	public function execute($data) {
		WCF::getCache()->clear(WBB_DIR . 'cache/', 'cache.UserjubileeIndex.php');
	}
}
?>