<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WBB_DIR.'lib/data/board/BoardEditor.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueDeleteListener implements EventListener {

	public function execute($eventObj, $className, $eventName){

		$sql = "UPDATE wbb".WBB_N."_board
						SET cliquBoard = 0,
                            cliqueID = 0
						WHERE cliqueID = ".$eventObj->cliqueID;
		WCF::getDB()->sendQuery($sql);
        Board::resetCache();
	}
}
?>