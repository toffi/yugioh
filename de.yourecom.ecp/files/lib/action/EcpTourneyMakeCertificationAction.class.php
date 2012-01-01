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
class EcpTourneyMakeCertificationAction extends AbstractAction {

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
		$sql = "SELECT events.status, events_user.user_ID
      			FROM    events
        		LEFT JOIN   events_user
        		  ON (events_user.event_id=events.id)
            WHERE events.id = ".$this->eventID." && events_user.user_ID = ".WCF::getUser()->userID;
		$this->tourneyList = WCF::getDB()->getFirstRow($sql);
		if($this->tourneyList['status'] == 10 || $this->tourneyList['status'] == 5)
			return;

		if (empty($this->tourneyList))
      throw new NamedUserException(WCF::getLanguage()->get('wcf.ecp.tourney.detail.certified.error'));
		  else {
    			//// Bestätigung Eintragen ////
    		$sql = "UPDATE events_user
    		  			SET status = '1'
                WHERE event_id = '".$this->eventID."' && user_ID = ".WCF::getUser()->userID;
    		WCF::getDB()->sendQuery($sql);
    		$url = "index.php?form=ECPTourneyDetail&eventID=".$this->eventID."".SID_ARG_2ND;
      }

    	WCF::getTPL()->assign(array(
 				'url' => $url,
 				'message' => WCF::getLanguage()->get('wcf.ecp.tourney.detail.certified.sucessful')
    	));
    	WCF::getTPL()->display('redirect');
    	exit;
  }
}
?>