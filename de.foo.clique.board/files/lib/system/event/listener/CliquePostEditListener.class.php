<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */

class CliquePostEditListener implements EventListener {

	public function execute($eventObj, $className, $eventName){

		$this->post = new PostEditor($eventObj->postID);
		$this->thread = new ThreadEditor($eventObj->post->threadID);
		$this->board = new BoardEditor($eventObj->thread->boardID);

		if($this->board->parentID != CLIQUE_BOARD_CATEGORIE && $this->board->cliquBoard != 1) {
			return;
		}

		$url = 'index.php?page=CliqueThread&cliqueID='.$eventObj->cliqueID.'&postID='.$eventObj->postID. SID_ARG_2ND_NOT_ENCODED . '#post'.$eventObj->postID;
		HeaderUtil::redirect($url);
		exit;
	}
}
?>