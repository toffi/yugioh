<?php
// wcf imports
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/page/util/menu/UserCPMenu.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliquePage extends SortablePage {
	public $templateName = 'clique';
	public $neededPermissions = 'user.clique.general.canSee';
	public $memberships = array();
	public $groupLeaders = array();
	public $itemsPerPage = 5;
	public $sortableOrder = 0;
	public $applications = array();
    public $invites2 = array();
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		$this->readMemberships();
		$this->readApplication();
		$this->getInvites();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'memberships' => $this->memberships,
			'countMemberships' => $this->countMemberships,
			'applications' => $this->applications,
			'invites2' => $this->invites2
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// check permission
		if (!WCF::getUser()->userID) {
			throw new PermissionDeniedException();
		}
		
		// set active tab
		UserCPMenu::getInstance()->setActiveMenuItem('wcf.user.usercp.menu.link.clique.overview');

		parent::show();
	}
	
	/**
	 * Gets a list of all memberships of the active user.
	 */
	protected function readMemberships() {
		$sql = "SELECT		*
			FROM		wcf".WCF_N."_clique
			WHERE		cliqueID IN (SELECT	cliqueID
						FROM wcf".WCF_N."_user_to_clique
						WHERE userID = ".WCF::getUser()->userID.")
			ORDER BY	name";
		$result = WCF::getDB()->sendQuery($sql, $this->itemsPerPage, ($this->pageNo - 1) * $this->itemsPerPage);
		$this->countMemberships = WCF::getDB()->countRows($result);

		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->memberships[] = $row;
		}
	}

	/**
	 * Gets a list of all memberships of the active user.
	 */
	protected function readApplication() {
		$sql = "SELECT		*
			FROM		wcf".WCF_N."_clique_application application
			LEFT JOIN wcf".WCF_N."_clique clique
				ON(clique.cliqueID=application.cliqueID)
			WHERE		userID = ".WCF::getUser()->userID."
			ORDER BY	name";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->applications[] = $row;
		}
	}

	/**
	 * Gets a list of all memberships of the active user.
	 */
	protected function getInvites() {
		$sql = "SELECT invites.*, user_table.username, clique.name
			FROM wcf".WCF_N."_clique_invite invites
			LEFT JOIN 	wcf".WCF_N."_user user_table
				ON		(user_table.userID = invites.inviterID)
			LEFT JOIN 	wcf".WCF_N."_clique clique
				ON		(clique.cliqueID = invites.cliqueID)
			WHERE		inviteeID = ".WCF::getUser()->userID;
		$result = WCF::getDB()->sendQuery($sql);
		$this->countInvites = WCF::getDB()->countRows($result);

		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->invites2[] = $row;
		}
	}

	/**
		@see MultipleLinkPage::countItems()
	*/
		public function countItems() {
			if(!empty($_GET['sortableOrder'])) $this->sortableOrder = intval($_GET['sortableOrder']);

			if($this->sortableOrder == 0) {
				$sql = "SELECT		*
								FROM		wcf".WCF_N."_clique
								WHERE		cliqueID IN (SELECT	cliqueID
									FROM wcf".WCF_N."_user_to_clique
									WHERE userID = ".WCF::getUser()->userID.")
								ORDER BY	name";
			}
			else {
				$sql = "SELECT clique.*, user_table.username
								FROM wcf".WCF_N."_clique clique
								LEFT JOIN 	wcf".WCF_N."_user user_table
									ON		(user_table.userID = clique.raiserID)
								WHERE clique.status = 0 
                                    AND
										clique.cliqueID NOT IN (
											SELECT	cliqueID
											FROM wcf".WCF_N."_user_to_clique
											WHERE userID = ".WCF::getUser()->userID."
										)
								ORDER BY name";
			}
			$result = WCF::getDB()->sendQuery($sql);
			$this->countCliquen = WCF::getDB()->countRows($result);

			return $this->countCliquen;
		}
}
?>