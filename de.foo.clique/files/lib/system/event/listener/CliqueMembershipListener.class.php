<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueMembershipListener implements EventListener {
	public $memberships = array();

	public function execute($eventObj, $className, $eventName){

        if($className == 'MessageSidebarFactory' && isset($eventObj->container->threadID) && CLIQUE_SIDEBAR_ENABLE) {
            $sql = "SELECT	 post.postID, post.userID, memberships.cliqueID, clique.name
                    FROM    wbb".WBB_N."_post post
            		LEFT JOIN 	wcf".WCF_N."_user_to_clique memberships
            			ON		(memberships.userID = post.userID)
        			LEFT JOIN 	wcf".WCF_N."_clique clique
        				ON		(clique.cliqueID = memberships.cliqueID)
                    WHERE post.threadID=".$eventObj->container->threadID;
            $result = WCF::getDB()->sendQuery($sql);
            while ($row = WCF::getDB()->fetchArray($result)) {
                $clique[$row['postID']] = '<br /><a href="index.php?page=CliqueDetail&amp;cliqueID='.$row['cliqueID'].'" title="" class="smallFont"><strong>'.$row['name'].'</strong></a>';
            }
    		if (count($clique) >= 1) {
    			WCF::getTPL()->append('additionalSidebarUsernameInformation', $clique);
    		}
        }
        elseif($className == 'UserPage') {
            $userID = $eventObj->frame->userID;
    		$sql = "SELECT memberships.cliqueID, memberships.enteredTime, clique.name, clique.image
    			FROM wcf".WCF_N."_user_to_clique memberships
    			LEFT JOIN 	wcf".WCF_N."_clique clique
    				ON		(clique.cliqueID = memberships.cliqueID)
    			WHERE		userID = ".$userID."
    			ORDER BY clique.name ASC";
    		$result = WCF::getDB()->sendQuery($sql);
    
    		while ($row = WCF::getDB()->fetchArray($result)) {
    			$this->memberships[] = $row;
    		}
    
    		if (count($this->memberships) >= 1) {
    			WCF::getTPL()->assign('memberships', $this->memberships);
    			WCF::getTPL()->append('additionalBoxes2', WCF::getTPL()->fetch('cliqueMembership'));
    		}
        }
	}
}
?>