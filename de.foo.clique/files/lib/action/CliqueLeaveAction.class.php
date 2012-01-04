<?php
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/clique/Clique.class.php');
require_once(WCF_DIR.'lib/action/CliqueDeleteAction.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueLeaveAction extends AbstractSecureAction {
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
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

			// User is raiser; Delete Clique
		if($this->clique->raiserID == WCF::getUser()->userID) {
			CliqueDeleteAction::deleteClique($this->cliqueID, $this->clique->image);
		}
			// delete user
		else {
			$this->deleteUser(WCF::getUser()->userID, $this->cliqueID);
		}
		$this->executed();

		HeaderUtil::redirect('index.php?page=Clique'.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}

	/**
	 * @see Action::addUser()
	 */
	public function deleteUser($userID, $cliqueID) {
		EventHandler::fireAction($this, 'deleteUserFromClique');
		$delete = "DELETE FROM
								wcf".WCF_N."_user_to_clique
							WHERE userID=".$userID." && cliqueID = ".$cliqueID;
		WCF::getDB()->sendQuery($delete);
		
		// reset cache
		WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.CacheBuilderCliqueBoxes.php');
	}
}