<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueACPListener implements EventListener {
	public $cliquBoard = 0;
	public $cliqueID = 0;
	public $isSave = false;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		switch ($eventName) {
			case 'readFormParameters':
				if (isset($_POST['cliquBoard'])) {
					$this->cliquBoard = 1;
				}
				if (isset($_POST['cliqueID'])) {
					$this->cliqueID = intval($_POST['cliqueID']);
				}
			break;

			case 'save':
				if(!empty($this->cliqueID)) {
				    
					/*$sql = "SELECT	boardID
									FROM		wcf".WCF_N."_clique
									WHERE		cliqueID = ".$this->cliqueID;
					$clique = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);
					if(isset($clique['boardID']) && isset($eventObj->boardID) && $eventObj->boardID != $clique['boardID']) {
						$eventObj->parentID = $clique['boardID'];
					}
					elseif($className == 'BoardAddForm' && isset($clique['boardID'])) {
						$eventObj->parentID = $clique['boardID'];
					}
					else {
						$eventObj->parentID = CLIQUE_BOARD_CATEGORIE;
					}*/
					$eventObj->additionalFields['cliquBoard'] = $this->cliquBoard;
					$eventObj->additionalFields['cliqueID'] = $this->cliqueID;
					$this->isSave = true;
				}
			break;

			case 'assignVariables':
				if (is_object($eventObj->board) && !$this->isSave) {
					WCF::getTPL()->assign(array(
						'cliquBoard' => $eventObj->board->cliquBoard,
						'cliqueID' => $eventObj->board->cliqueID,
					));
				}
				else {
					WCF::getTPL()->assign(array(
						'cliquBoard' => $this->cliquBoard,
						'cliqueID' => $this->cliqueID
					));
				}
				WCF::getTPL()->append('additionalSettings', WCF::getTPL()->fetch('cliqueACP'));
			break;
		}
	}
}
?>