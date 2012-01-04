<?php
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/clique/Clique.class.php');
require_once(WCF_DIR.'lib/action/CliqueDeleteAction.class.php');
require_once(WCF_DIR.'lib/action/CliqueLeaveAction.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueKickUserAction extends AbstractSecureAction {
	public $userID = 0;
	public $cliqueID = 0;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_GET['cliqueID'])) $this->cliqueID = intval($_GET['cliqueID']);
		$this->clique = new Clique($this->cliqueID);

		if (isset($_GET['userID'])) $this->userID = intval($_GET['userID']);

				// Check if clique exist
		if(!$this->clique->cliqueID) {
			throw new IllegalLinkException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

			// User is raiser; Delete Clique
		if($this->clique->raiserID == $this->userID) {
			CliqueDeleteAction::deleteClique($this->cliqueID, $this->clique['image']);
		}
			// delete user
		else {
			CliqueLeaveAction::deleteUser($this->userID, $this->cliqueID);
		}
		$this->executed();

		HeaderUtil::redirect('index.php?form=CliqueAdministrate&cliqueID='.$this->cliqueID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}