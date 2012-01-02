<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */

class CliqueBoardPermissionAdministrateListener implements EventListener {

	public function execute($eventObj, $className, $eventName){
		$eventObj->cliqueRights[9]['rightName'] = 'canSeeBoards';
		$eventObj->cliqueRights[9]['rightLanguage'] = WCF::getLanguage()->get('wcf.clique.permission.canSeeBoards');
		$eventObj->cliqueRights[10]['rightName'] = 'canMakePosts';
		$eventObj->cliqueRights[10]['rightLanguage'] = WCF::getLanguage()->get('wcf.clique.permission.canMakePosts');
	}
}
?>