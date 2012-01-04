<?php
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/clique/Clique.class.php');
require_once(WCF_DIR.'lib/data/clique/CliqueEditor.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueDeleteAction extends AbstractSecureAction {
	public $cliqueID = 0;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if (isset($_GET['cliqueID'])) $this->cliqueID = intval($_GET['cliqueID']);
		$this->clique = new Clique($this->cliqueID);
		
				// Check if clique exist
		if (!$this->clique->cliqueID) {
				throw new IllegalLinkException();
		}
		
		// check permission
		if (!CliqueEditor::getCliquePermission('canDeleteClique') && !WCF::getUser()->getPermission('mod.clique.general.canEditEveryClique')) {
			throw new PermissionDeniedException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		// Delete Memberships
		$this->deleteClique($this->cliqueID, $this->clique->image);
		
		$this->executed();
		
		// reset cache
		WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.CacheBuilderCliqueBoxes.php');
		HeaderUtil::redirect('index.php?page=Clique'.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}

	/**
	 * @see Action::deleteMemberships()
	 */
	public function deleteClique($cliqueID, $picture) {
		CliqueDeleteAction::deleteCliquedetails($cliqueID);
		CliqueDeleteAction::deleteMemberships($cliqueID);
		CliqueDeleteAction::deleteVisitors($cliqueID);
		CliqueDeleteAction::deleteRaiting($cliqueID);
		CliqueDeleteAction::deleteInvites($cliqueID);
		CliqueDeleteAction::deleteComments($cliqueID);
		CliqueDeleteAction::deletePhoto($picture);
	}

	/**
	 * @see Action::deleteMemberships()
	 */
	public function deleteCliquedetails($cliqueID) {
		$delete = "DELETE FROM
								wcf".WCF_N."_clique
							WHERE cliqueID = ".$cliqueID;
		WCF::getDB()->sendQuery($delete);
	}

	/**
	 * @see Action::deleteMemberships()
	 */
	public function deleteMemberships($cliqueID) {
		$delete = "DELETE FROM
								wcf".WCF_N."_user_to_clique
							WHERE cliqueID = ".$cliqueID;
		WCF::getDB()->sendQuery($delete);
	}

	/**
	 * @see Action::deleteVisitors()
	 */
	public function deleteVisitors($cliqueID) {
		$delete = "DELETE FROM
								wcf".WCF_N."_clique_visitor
							WHERE cliqueID = ".$cliqueID;
		WCF::getDB()->sendQuery($delete);
	}

	/**
	 * @see Action::deleteRaiting()
	 */
	public function deleteRaiting($cliqueID) {
		$delete = "DELETE FROM
								wcf".WCF_N."_clique_rating
							WHERE cliqueID = ".$cliqueID;
		WCF::getDB()->sendQuery($delete);
	}

	/**
	 * @see Action::deleteInvites()
	 */
	public function deleteInvites($cliqueID) {
		$delete = "DELETE FROM
								wcf".WCF_N."_clique_invite
							WHERE cliqueID = ".$cliqueID;
		WCF::getDB()->sendQuery($delete);
	}

	/**
	 * @see Action::deleteComments()
	 */
	public function deleteComments($cliqueID) {
		$delete = "DELETE FROM
								wcf".WCF_N."_clique_comments
							WHERE cliqueID = ".$cliqueID;
		WCF::getDB()->sendQuery($delete);
	}

	/**
	 * @see Action::deletePhoto()
	 */
	public function deletePhoto($picture) {
		if(file_exists(WCF_DIR.'images/clique/'.$picture)) {
			@unlink(WCF_DIR.'images/clique/'.$picture);
		}
	}
}