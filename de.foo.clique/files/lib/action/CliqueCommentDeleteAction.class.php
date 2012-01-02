<?php
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/page/CliqueDetailPage.class.php');
require_once(WCF_DIR.'lib/page/CliqueDetailPage.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueCommentDeleteAction extends AbstractSecureAction {
	public $commentID = 0;
	public $cliqueID = 0;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if (isset($_GET['commentID'])) $this->commentID = intval($_GET['commentID']);
		if (isset($_GET['cliqueID'])) $this->cliqueID = intval($_GET['cliqueID']);

		$this->clique = CliqueDetailPage::readClique($this->cliqueID);

				// Check if clique exist
		if (!$this->clique['cliqueID']) {
			throw new IllegalLinkException();
		}

		// check permission
		if (!CliqueEditor::getCliquePermission('canDeleteComments') && !WCF::getUser()->getPermission('user.clique.comments.canDeleteOwnComments')) {
			throw new PermissionDeniedException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// Delete Memberships
		$this->deleteComment($this->commentID);

		$this->executed();

		HeaderUtil::redirect('index.php?page=CliqueComments&cliqueID='.$this->cliqueID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}

	/**
	 * @see Action::deleteMemberships()
	 */
	public function deleteComment($cliqueID) {
		$delete = "DELETE FROM
								wcf".WCF_N."_clique_comments
							WHERE commentID = ".$this->commentID."
								AND cliqueID = ".$this->cliqueID;
		WCF::getDB()->sendQuery($delete);
	}
}