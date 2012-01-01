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
class EcpTourneyJoinAction extends AbstractAction {

	/**
	 * @see Action::readParameters()
	 */
  public function readParameters()  {
		parent::readParameters();
		if(isset($_GET['eventID'])) $this->eventID = intval($_GET['eventID']);
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		WCF::getUser()->checkPermission('user.ecp.canJoinTourney');

		// Hole die Tunierdetails
		$sql = "SELECT events.participants, events.status
						FROM events 
						WHERE events.id = ".$this->eventID;
		$this->tourneyList = WCF::getDB()->getFirstRow($sql);
		if($this->tourneyList['status'] == 10 || $this->tourneyList['status'] == 5)
			return;

		// Wieviele angemeldet?
			$sql = "SELECT *
							FROM events_user 
							WHERE event_id = ".$this->eventID;
		WCF::getDB()->sendQuery($sql);
		$this->Number = WCF::getDB()->countRows();

		/// Alte Anmeldung löschen ////

		$loeschen = "DELETE FROM events_user WHERE user_ID= ".WCF::getUser()->userID." && event_id = ".$this->eventID;
		$loeschen2 = WCF::getDB()->sendQuery($loeschen);

			// Trage die Anmeldung ein
		$sql = "INSERT INTO events_user
		  			SET user_ID = ".WCF::getUser()->userID.",
						event_id = ".$this->eventID;
		WCF::getDB()->sendQuery($sql);

		if($this->Number >= $this->tourneyList['participants']) $redirect_text = 'wcf.ecp.tourney.logout.sucessful';
			else $redirect_text = 'wcf.ecp.tourney.join.sucessful';
		$url = "index.php?form=ECPTourneyDetail&eventID=".$this->eventID."".SID_ARG_2ND;
		WCF::getTPL()->assign(array(
				'url' => $url,
				'message' => WCF::getLanguage()->get('wcf.ecp.tourney.join.sucessful')
		));
		WCF::getTPL()->display('redirect');
		exit;
	}
}
?>