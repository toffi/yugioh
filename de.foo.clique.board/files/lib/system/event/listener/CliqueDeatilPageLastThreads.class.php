<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/clique/CliqueEditor.class.php');
require_once(WCF_DIR.'lib/page/CliqueDetailPage.class.php');
require_once(WBB_DIR.'lib/data/thread/ViewableThread.class.php');
require_once(WBB_DIR.'lib/data/board/Board.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueDeatilPageLastThreads implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
        $boardIDArray = Board::getAccessibleBoardIDArray(array('canViewBoard', 'canEnterBoard', 'canReadThread'));
		$lastThreads = array();
		$sql = "SELECT thread.threadID, thread.topic, thread.userID, thread.username, thread.time
						FROM wbb".WBB_N."_thread thread
						WHERE thread.boardID IN (SELECT boardID FROM wbb".WBB_N."_board board WHERE board.cliqueID=".$eventObj->clique->cliqueID.")
                            AND thread.boardID IN (".implode(',', $boardIDArray).")
						ORDER BY thread.threadID DESC
						LIMIT ".CLIQUE_THREADS_PER_DETAIL_PAGE;
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
            $lastThreads[] = new ViewableThread(null, $row);
		}

		WCF::getTPL()->assign(array(
			'lastThreads' => $lastThreads,
			'cliqueID' => $eventObj->clique->cliqueID,
			'cliquePermissions' => new CliqueEditor($eventObj->clique->cliqueID),
			'clique' => CliqueDetailPage::readClique($eventObj->clique->cliqueID)
		));

		WCF::getTPL()->append('additionalMainframeBottom', WCF::getTPL()->fetch('cliqueLastThreads'));
	}
}
?>