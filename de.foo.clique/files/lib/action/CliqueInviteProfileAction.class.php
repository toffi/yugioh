<?php
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/clique/Clique.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueInviteProfileAction extends AbstractSecureAction {
	public $cliqueID = 0;
	public $userID = 0;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_POST['cliqueID'])) $this->cliqueID = intval($_POST['cliqueID']);
		if (isset($_GET['userID'])) $this->userID = intval($_GET['userID']);

		$this->clique = new Clique($this->cliqueID);

				// Check if clique exist
		if (!$this->clique->cliqueID) {
			throw new IllegalLinkException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		require_once(WCF_DIR.'lib/form/CliqueAdministrateForm.class.php');
		CliqueAdministrateForm::setInvite($this->cliqueID, WCF::getUser()->userID, $this->userID);
		
		$this->executed();
		Clique::resetCacheCliqueInvites();
		
		HeaderUtil::redirect('index.php?page=User&userID='.$this->userID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}

	/**
	 * @see Action::addUser()
	 */
	public function declineUser($userID, $cliqueID) {
		$sql = "DELETE FROM	wcf".WCF_N."_clique_invite
						WHERE cliqueID = ".$cliqueID."
							AND inviteeID = ".$userID;
		WCF::getDB()->registerShutdownUpdate($sql);
	}
}