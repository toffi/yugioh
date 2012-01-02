<?php
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/page/CliqueDetailPage.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueApplicationCancelAction extends AbstractSecureAction {
	public $cliqueID = 0;
	public $userID = 0;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if (isset($_GET['cliqueID'])) $this->cliqueID = intval($_GET['cliqueID']);
		if (isset($_GET['userID'])) $this->userID = intval($_GET['userID']);

		$this->clique = CliqueDetailPage::readClique($this->cliqueID);

				// Check if clique exist
		if (!$this->clique['cliqueID'] || (!CliqueEditor::getCliquePermission('canAttendInvites') && $this->userID != WCF::getUser()->userID)) {
				throw new IllegalLinkException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// Delete Memberships
		$this->deleteApplication($this->cliqueID, $this->userID);

		$this->executed();

		HeaderUtil::redirect('index.php?page=CliqueDetail&cliqueID='.$this->cliqueID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}

	/**
	 * @see Action::deleteMemberships()
	 */
	public function deleteApplication($cliqueID, $userID) {
		$delete = "DELETE FROM
								wcf".WCF_N."_clique_application
							WHERE cliqueID = ".$cliqueID."
								AND userID = ".$userID;
		WCF::getDB()->sendQuery($delete);
	}
}