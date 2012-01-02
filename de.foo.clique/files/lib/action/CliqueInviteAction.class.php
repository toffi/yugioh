<?php
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/clique/Clique.class.php');
require_once(WCF_DIR.'lib/action/CliqueJoinAction.class.php');
require_once(WCF_DIR.'lib/data/clique/CliqueEditor.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueInviteAction extends AbstractSecureAction {
	public $cliqueID = 0;
	public $accept = 0;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_GET['cliqueID'])) $this->cliqueID = intval($_GET['cliqueID']);
		if (isset($_GET['accept'])) $this->accept = $_GET['accept'];
		if (isset($_GET['decline'])) $this->decline = $_GET['decline'];

		$this->clique = new Clique($this->cliqueID);

				// Check if clique exist
		if (!$this->clique->cliqueID) {
				throw new IllegalLinkException();
		}
				// Check if user hasn't an invite
		if($this->clique->status == 1) {
			$sql = "SELECT cliqueID
							FROM wcf".WCF_N."_clique_invite
							WHERE		cliqueID = ".$this->cliqueID."
								AND inviteeID = ".WCF::getUser()->userID;
			$cliqueInvite = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);
			if(empty($cliqueInvite)) {
				throw new IllegalLinkException();
			}
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// add user
		if($this->accept == 1) {
			CliqueJoinAction::addUser(WCF::getUser()->userID, $this->cliqueID);
		}

		self::declineUser(WCF::getUser()->userID, $this->cliqueID);


		$this->executed();

		HeaderUtil::redirect('index.php?page=Clique'.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}

	/**
	 * @see Action::declineUser()
	 */
	public static function declineUser($userID, $cliqueID) {
		$sql = "DELETE FROM	wcf".WCF_N."_clique_invite
						WHERE cliqueID = ".$cliqueID."
							AND inviteeID = ".$userID;
		WCF::getDB()->registerShutdownUpdate($sql);
	}
}