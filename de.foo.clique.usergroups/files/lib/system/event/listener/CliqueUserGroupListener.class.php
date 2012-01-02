<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/clique/Clique.class.php');
require_once(WCF_DIR.'lib/data/user/group/GroupEditor.class.php');
require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueUserGroupListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
        $userID = WCF::getUser()->userID;
        switch ($className) {
            case 'CliqueInviteAction':
            case 'CliqueJoinAction':
                $userEditor = new UserEditor($userID);
                $userEditor->addToGroup($eventObj->clique->groupID);
                break;

            case 'CliqueKickUserAction':
            case 'CliqueApplicationAcceptAction':
                $userID = $eventObj->userID;
            case 'CliqueLeaveAction':
                $userEditor = new UserEditor($userID);
                $userEditor->removeFromGroup($eventObj->clique->groupID);
                break;
        }
    }
}
?>