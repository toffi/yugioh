<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/clique/CliqueEditor.class.php');
require_once(WCF_DIR.'lib/data/clique/Clique.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueProfileInviteListener implements EventListener {
	public $invitableCliques = array();

	public function execute($eventObj, $className, $eventName){
    	$sql = "SELECT memberships.cliqueID, clique.name
        		FROM wcf".WCF_N."_user_to_clique memberships
    			LEFT JOIN 	wcf".WCF_N."_clique clique
    				ON		(clique.cliqueID = memberships.cliqueID)
    			WHERE		memberships.userID = ".WCF::getUser()->userID."
                    AND
						memberships.cliqueID NOT IN (
							SELECT	cliqueID
							FROM wcf".WCF_N."_clique_invite
							WHERE inviteeID = ".$eventObj->frame->userID."
						)
    			ORDER BY clique.name ASC";
    	$result = WCF::getDB()->sendQuery($sql);

    	while ($row = WCF::getDB()->fetchArray($result)) {
            if(CliqueEditor::getCliquePermission('canAttendInvites', $row['cliqueID'])) {
                $clique = new Clique($row['cliqueID']);
                if($clique->isMember($eventObj->frame->userID) == 0) {
        		    //$this->invitableClique .= '<li id="cliqueInvite"><a href="index.php?action=CliqueInviteProfile&amp;cliqueID='.$row['cliqueID'].'&amp;userID='.$eventObj->frame->userID.'&amp;t='.SECURITY_TOKEN.@SID_ARG_2ND.'" title="'.WCF::getLanguage()->get('wcf.clique.administrate.invite', array('$cliqueName' => $row['name'])).'"><img src="'.RELATIVE_WCF_DIR.'icon/groupAddM.png" alt="" /> <span>'.WCF::getLanguage()->get('wcf.clique.administrate.invite', array('$cliqueName' => $row['name'])).'</span></a></li>';
                    $this->invitableCliques[] = $row;
                }
            }
    	}
 
    	if (count($this->invitableCliques) >= 1) {
            WCF::getTPL()->assign('invitableCliques', $this->invitableCliques);
    		WCF::getTPL()->assign('additionalBoxes1', WCF::getTPL()->fetch('cliqueProfileInvite'));
    	}
	}
}
?>