<?php
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/action/CliqueJoinAction.class.php');
require_once(WCF_DIR.'lib/action/CliqueApplicationCancelAction.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueApplicationAcceptAction extends AbstractSecureAction {
	public $cliqueID = 0;
	public $userID = 0;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if (isset($_GET['cliqueID'])) $this->cliqueID = intval($_GET['cliqueID']);
		if (isset($_GET['userID'])) $this->userID = intval($_GET['userID']);

		$this->clique = new Clique($this->cliqueID);

				// Check if clique exist and if clique is closed
		if (!$this->clique->cliqueID || WCF::getUser()->userID == 0 || $this->clique->isMember($this->userID) == 1) {
            throw new IllegalLinkException();
		}

	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		CliqueJoinAction::addUser($this->userID, $this->cliqueID);
		CliqueApplicationCancelAction::deleteApplication($this->cliqueID, $this->userID);
		$this->executed();

		HeaderUtil::redirect('index.php?form=CliqueAdministrate&cliqueID='.$this->cliqueID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}