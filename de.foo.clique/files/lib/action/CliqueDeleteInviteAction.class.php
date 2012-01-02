<?php
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/page/CliqueDetailPage.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueDeleteInviteAction extends AbstractSecureAction {
	public $inviteeID = 0;
	public $cliqueID = 0;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if (isset($_GET['inviteeID'])) $this->inviteeID = intval($_GET['inviteeID']);
		if (isset($_GET['cliqueID'])) $this->cliqueID = intval($_GET['cliqueID']);

		$this->clique = CliqueDetailPage::readClique($this->cliqueID);

				// Check if clique exist
		if (!$this->clique['cliqueID']) {
				throw new IllegalLinkException();
		}

		// check permission
		if (!CliqueEditor::getCliquePermission('canDeleteClique')) {
			throw new PermissionDeniedException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// Delete Memberships
		$this->deleteInvite($this->cliqueID, $this->inviteeID);

		$this->executed();

		HeaderUtil::redirect('index.php?form=CliqueAdministrate&cliqueID='.$this->cliqueID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}

	/**
	 * @see Action::deleteMemberships()
	 */
	public function deleteInvite($cliqueID, $inviteeID) {
		$delete = "DELETE FROM
								wcf".WCF_N."_clique_invite
							WHERE cliqueID = ".$cliqueID."
								AND inviteeID = ".$inviteeID;
		WCF::getDB()->sendQuery($delete);
	}
}