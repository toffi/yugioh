<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

/**
 * Marks all boards as read.
 * 
 * @author 	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	WoltLab Burning Board License <http://www.woltlab.com/products/burning_board/license.php>
 * @package	com.woltlab.wbb
 * @subpackage	action
 * @category 	Burning Board
 */
class EcpEditResultAction extends AbstractAction {
	public $gameID = '';

	/**
	 * @see Action::readParameters()
	 */
  public function readParameters() {
		parent::readParameters();
		if(isset($_GET['eventID'])) $this->eventID = intval($_GET['eventID']);
		if(isset($_GET['gameID'])) $this->gameID = intval($_GET['gameID']);

				// Get T-Admin
			$sql = "SELECT contacts
									FROM events 
									WHERE id = ".$this->eventID;
			WCF::getDB()->sendQuery($sql);
			$TAdmin[] = WCF::getDB()->getFirstRow($sql);
		if (!WCF::getUser()->getPermission('mod.ecp.canEditEveryResult') && WCF::getUser()->userID != $TAdmin[0]['contacts']) {
			throw new PermissionDeniedException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

			$sql = "SELECT userID1, userID2
									FROM events_paarungen 
									WHERE id = ".$this->gameID;
			WCF::getDB()->sendQuery($sql);
			$userIDs[] = WCF::getDB()->getFirstRow($sql);

			$sql = "UPDATE events_paarungen
							SET scoreID_1 = '',
									userID1 = ''
							WHERE (userID1='".$userIDs[0]['userID1']."' || userID1='".$userIDs[0]['userID2']."') && 
												id > '".$this->gameID."' && event_ID=".$this->eventID;
			WCF::getDB()->sendQuery($sql);

			$sql = "UPDATE events_paarungen
			  			SET scoreID_2 = '',
									userID2 = ''
							WHERE 	(userID2='".$userIDs[0]['userID2']."' || userID2 = '".$userIDs[0]['userID1']."') && 
												id > '".$this->gameID."' && event_ID=".$this->eventID;
			WCF::getDB()->sendQuery($sql);

			$sql = "UPDATE events_paarungen
							SET scoreID_1 = '',
									scoreID_2 = '',
									winnerID = ''
							WHERE 	id = ".$this->gameID;
			WCF::getDB()->sendQuery($sql);

			$url = "index.php?form=ECPTourneyDetail&&eventID=".$this->eventID."".SID_ARG_2ND;
			WCF::getTPL()->assign(array(
				'url' => $url,
				'message' => WCF::getLanguage()->get('wcf.ecp.tourney.detail.editResult.sucessful')
			));
			WCF::getTPL()->display('redirect');
			exit;
	}
}
?>