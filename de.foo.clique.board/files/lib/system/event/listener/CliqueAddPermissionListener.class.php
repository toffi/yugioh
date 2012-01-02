<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */

class CliqueAddPermissionListener implements EventListener {

	public function execute($eventObj, $className, $eventName){
		$eventObj->groupRights['canSeeBoards'] = CLIQUE_GENERAL_RIGHTS_CANSEEBOARDS;
		$eventObj->groupRights['canMakePosts'] = CLIQUE_GENERAL_RIGHTS_CANMAKEPOSTS;
	}
}
?>