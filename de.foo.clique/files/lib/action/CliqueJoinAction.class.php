<?php
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/clique/Clique.class.php');
require_once(WCF_DIR.'lib/action/CliqueDeleteInviteAction.class.php');
require_once(WCF_DIR.'lib/system/event/EventHandler.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueJoinAction extends AbstractSecureAction {
	public $cliqueID = 0;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_GET['cliqueID'])) $this->cliqueID = intval($_GET['cliqueID']);
		$this->clique = new Clique($this->cliqueID);

				// Check if clique exist and if clique is closed
		if (!$this->clique->cliqueID || $this->clique->status == 1 || WCF::getUser()->userID == 0 || $this->clique->isMember() == 1) {
            throw new IllegalLinkException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// add user
		$this->addUser(WCF::getUser()->userID, $this->cliqueID);
        CliqueDeleteInviteAction::deleteInvite($this->cliqueID, WCF::getUser()->userID);
		$this->executed();

		HeaderUtil::redirect('index.php?page=CliqueDetail&cliqueID='.$this->cliqueID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}

	/**
	 * @see Action::addUser()
	 */
	public function addUser($userID, $cliqueID, $groupType = 2) {
        EventHandler::fireAction($this, 'addUserToClique');
		$sql = "INSERT INTO
							wcf".WCF_N."_user_to_clique(userID, cliqueID, enteredTime, groupType)
						VALUES
							(".$userID.",
							".$cliqueID.",
							".TIME_NOW.",
							".$groupType.")";
		WCF::getDB()->sendQuery($sql);
	}
}