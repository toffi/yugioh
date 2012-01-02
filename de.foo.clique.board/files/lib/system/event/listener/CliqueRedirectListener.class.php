<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */

class CliqueRedirectListener implements EventListener {

	public function execute($eventObj, $className, $eventName){

	switch ($className) {
		case 'BoardPage':
			$this->board = Board::getBoard($eventObj->boardID);
			if($this->board->cliquBoard != 1) {
				return;
			}
            $status = $languageID = $tagID = $daysPrune = '';
            
    		if (isset($_REQUEST['status'])) $status = "?status=".$_REQUEST['status'];
    		if (isset($_REQUEST['languageID'])) $languageID = "?languageID=".intval($_REQUEST['languageID']);
    		if (isset($_REQUEST['tagID'])) $tagID = "?tagID=".intval($_REQUEST['tagID']);
            if (isset($_REQUEST['daysPrune'])) $daysPrune = "?daysPrune=".intval($_REQUEST['daysPrune']);
			$url = 'page=CliqueBoard&cliqueID='.$this->board->cliqueID.'&boardID='.$eventObj->boardID.$status.$languageID.$tagID.$daysPrune;
			break;
		case 'ThreadPage':
			$this->board = Board::getBoard($eventObj->board->boardID);
			if($this->board->cliquBoard != 1) {
				return;
			}
            $messageID = $postID = $action = $highlight = '';
    		if (isset($_REQUEST['messageID'])) $messageID = '&messageID='.intval($_REQUEST['messageID']);
    		else if (isset($_REQUEST['postID'])) $postID = '&postID='.intval($_REQUEST['postID']);
    		else if (isset($_REQUEST['postid'])) $postID = '&postid='.intval($_REQUEST['postid']); // wbb2 style
    		if (isset($_REQUEST['action'])) $action = '&action='.$_REQUEST['action'];
    		if (isset($_REQUEST['highlight'])) $highlight = '&highlight='.$_REQUEST['highlight'];
            
			$url = 'page=CliqueThread&cliqueID='.$this->board->cliqueID.'&threadID='.$eventObj->threadID.$postID.$action.$highlight.$messageID;
			break;
		}

		$url = 'index.php?'.$url.SID_ARG_2ND_NOT_ENCODED;
		HeaderUtil::redirect($url);
		exit;
	}
}
?>