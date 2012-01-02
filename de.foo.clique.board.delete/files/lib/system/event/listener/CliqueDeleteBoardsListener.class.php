<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/clique/Clique.class.php');
require_once(WBB_DIR.'lib/data/board/BoardEditor.class.php');
require_once(WBB_DIR.'lib/data/thread/ThreadEditor.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueDeleteBoardsListener implements EventListener {
	public $cliquBoard = 0;
	public $cliqueID = 0;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
        if(!CLIQUE_BOARD_DELETETHREADS) return;

        $clique = new Clique($eventObj->cliqueID);
        
        // clique havnt a board
        if($clique->boardID == 0) return;

        $boardStructure = WCF::getCache()->get('board', 'boardStructure');
        $boardStructure[$clique->boardID][] = $clique->boardID;

        //Delete-Move Boards boards
		if (isset($boardStructure[$clique->boardID])) {
            foreach ($boardStructure[$clique->boardID] as $child) {
                if(CLIQUE_BOARD_MOVETHREADS != 0) {
            		// get alle thread ids
            		$threadIDs = '';
            		$sql = "SELECT	threadID
            			FROM	wbb".WBB_N."_thread
            			WHERE	boardID = ".$child;
            		$result = WCF::getDB()->sendQuery($sql);
            		while ($row = WCF::getDB()->fetchArray($result)) {
            			if (!empty($threadIDs)) $threadIDs .= ',';
                		$sql2 = "UPDATE	wbb".WBB_N."_thread
                			SET 	isSticky = 0
                			WHERE	threadID = ".$row['threadID']."
                				AND isSticky = 1";
                		WCF::getDB()->registerShutdownUpdate($sql2);
            			$threadIDs .= $row['threadID'];
            		}
                    ThreadEditor::moveAll($threadIDs, CLIQUE_BOARD_MOVETHREADS);
                }
                $board = new BoardEditor($child);
                $board->delete();
            }
		}
        
        // Set last Posts
        if(CLIQUE_BOARD_MOVETHREADS != 0) {
            $board = new BoardEditor(CLIQUE_BOARD_MOVETHREADS);
            $board->setLastPosts();
	   }
    }
}
?>