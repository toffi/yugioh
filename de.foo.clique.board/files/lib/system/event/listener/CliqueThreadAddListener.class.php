<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */

class CliqueThreadAddListener implements EventListener {

	public function execute($eventObj, $className, $eventName){

		if($className == 'PostEditForm' || $className == 'CliquePostEditForm') {
			$this->post = new PostEditor($eventObj->postID);
			$this->thread = new ThreadEditor($this->post->threadID);
			$this->boardID = $eventObj->thread->boardID;
		}
		elseif($className == 'CliqueThreadAddForm') {
			$this->boardID = $eventObj->boardID;
		}
		elseif($className == 'ThreadAddForm') {
			$this->boardID = $eventObj->boardID;
		}
		else {
			$this->thread = new ThreadEditor($eventObj->threadID);
			$this->boardID = $eventObj->thread->boardID;
		}
		$this->board = new BoardEditor($this->boardID);

		if($this->board->parentID != CLIQUE_BOARD_CATEGORIE && $this->board->cliquBoard != 1) {
			return;
		}

		if (empty($eventObj->disableThread)) {
			// forward to post
			HeaderUtil::redirect('index.php?page=CliqueThread&cliqueID='.$eventObj->cliqueID.'&threadID=' . $eventObj->newThread->threadID . SID_ARG_2ND_NOT_ENCODED);
			exit;
		}
		else {
			WCF::getTPL()->assign(array(
				'url' => 'index.php?page=CliqueBoard&cliqueID='.$eventObj->cliqueID.'&boardID='.$eventObj->boardID.SID_ARG_2ND_NOT_ENCODED,
				'message' => WCF::getLanguage()->get('wbb.threadAdd.moderation.redirect'),
				'wait' => 5
			));
			WCF::getTPL()->display('redirect');
			exit;
		}
	}
}
?>