<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */

class CliquePostAddListener implements EventListener {

	public function execute($eventObj, $className, $eventName){
		$this->board = Board::getBoard($eventObj->thread->boardID);

		if($this->board->parentID != CLIQUE_BOARD_CATEGORIE && $this->board->cliquBoard != 1) {
			return;
		}

		if(!isset($eventObj->cliqueID) && isset($_GET['cliqueID'])) {
            $eventObj->cliqueID = intval($_GET['cliqueID']);
        }

		if(!$eventObj->board->getPermission('canReplyThreadWithoutModeration') && $eventObj->disablePost) {
			WCF::getTPL()->assign(array(
				'url' => 'index.php?page=CliqueThread&cliqueID='.$eventObj->cliqueID.'&threadID='.$eventObj->threadID.SID_ARG_2ND_NOT_ENCODED,
				'message' => WCF::getLanguage()->get('wbb.postAdd.moderation.redirect'),
				'wait' => 5
			));
			WCF::getTPL()->display('redirect');
			exit;
		}
		else {
			$url = 'index.php?page=CliqueThread&cliqueID='.$eventObj->cliqueID.'&postID='.$eventObj->newPost->postID. SID_ARG_2ND_NOT_ENCODED . '#post'.$eventObj->newPost->postID;
			HeaderUtil::redirect($url);
			exit;
		}
	}
}
?>