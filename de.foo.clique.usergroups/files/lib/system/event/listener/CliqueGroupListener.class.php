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
class CliqueGroupListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
        switch ($className) {
            case 'CliqueAddForm':
                $group = GroupEditor::create($eventObj->name);

    			$sql = "UPDATE wcf".WCF_N."_clique
    							SET groupID = ".$group->groupID."
    							WHERE cliqueID = ".$eventObj->cliqueID;
    			WCF::getDB()->sendQuery($sql);

                $userEditor = new UserEditor(WCF::getUser()->userID);
                $userEditor->addToGroup($group->groupID);

                $userEditor = new UserEditor(WCF::getUser()->userID);
                $userEditor->addToGroup(73);
                break;
            case 'CliqueEditForm':
                if(empty($eventObj->clique->groupID)) return;
                $groupEditor = new GroupEditor($eventObj->clique->groupID);
                $groupEditor->update($eventObj->name, array());
                break;
            case 'CliqueDeleteAction':
                if(empty($eventObj->clique->groupID)) return;
                $groupEditor = new GroupEditor($eventObj->clique->groupID);
                $groupEditor->deleteGroups(array($eventObj->clique->groupID));
                break;
        }
    }
}
?>