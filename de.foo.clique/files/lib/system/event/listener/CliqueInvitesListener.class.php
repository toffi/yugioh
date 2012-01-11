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
class CliqueInvitesListener implements EventListener {
	public $templateName = 'cliqueInvites';
	public $invites = array();
	public $aplications = array();
	public $countInvites = 0;

	public function execute($eventObj, $className, $eventName){
		if(WCF::getUser()->userID == 0) return;
		
		//handle invites
		$news = Clique::getCacheCliqueInvites();
		if(isset($news['invites'][WCF::getUser()->userID])) {
			foreach ($news['invites'][WCF::getUser()->userID] as $invite) {
				if($invite['time'] >= WCF::getUser()->cliqueMessage) {
					$this->invites[] = $invite;
				}
			}
			$this->countInvites = count($this->invites);
		}

		$sql = "SELECT application.userID, application.cliqueID, application.message, application.time, user_table.username, clique.name
				FROM wcf".WCF_N."_clique_application application
				LEFT JOIN 	wcf".WCF_N."_user user_table
					ON (user_table.userID = application.userID)
				LEFT JOIN 	wcf".WCF_N."_clique clique
					ON		(clique.cliqueID = application.cliqueID)
				WHERE application.cliqueID IN (SELECT	cliqueID
						FROM wcf".WCF_N."_user_to_clique
						WHERE userID = ".WCF::getUser()->userID.")
						AND	 application.time >=".WCF::getUser()->cliqueMessage."
				ORDER BY application.time ASC";
		$result = WCF::getDB()->sendQuery($sql);
		$this->countAplications = WCF::getDB()->countRows($result);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->cliqueID = $row['cliqueID'];
			if(CliqueEditor::getCliquePermission('canInviteUsers', $row['cliqueID'])) $this->aplications[] = $row;
		}

		if ($this->countInvites >= 1 || $this->countAplications >= 1) {
			WCF::getTPL()->assign('invites', $this->invites);
			WCF::getTPL()->assign('aplications', $this->aplications);
			WCF::getTPL()->append('userMessages', WCF::getTPL()->fetch("$this->templateName"));
		}
	}
}
?>