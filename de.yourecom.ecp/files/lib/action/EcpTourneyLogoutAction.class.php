<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

/**
 * @author	SpIkE2
 */
class EcpTourneyLogoutAction extends AbstractAction {

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
		$sql = "SELECT events.status
						FROM    events 
						WHERE events.id = ".$this->eventID;
		$this->tourneyList = WCF::getDB()->getFirstRow($sql);
		if($this->tourneyList['status'] == 10 || $this->tourneyList['status'] == 5)
			return;

/// Alte Anmeldung löschen ////
		$loeschen = "DELETE FROM events_user WHERE user_ID= ".WCF::getUser()->userID." && event_id = ".$this->eventID;
		$loeschen2 = WCF::getDB()->sendQuery($loeschen);

		$url = "index.php?form=ECPTourneyDetail&eventID=".$this->eventID."".SID_ARG_2ND;
	
		WCF::getTPL()->assign(array(
				'url' => $url,
				'message' => WCF::getLanguage()->get('wcf.ecp.tourney.logout.sucessful')
		));
		WCF::getTPL()->display('redirect');
		exit;
	}
}
?>