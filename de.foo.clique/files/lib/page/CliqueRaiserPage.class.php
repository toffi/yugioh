<?php
// wcf imports
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/page/util/menu/UserCPMenu.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueRaiserPage extends SortablePage {
	public $templateName = 'cliqueRaiser';
	public $neededPermissions = 'user.clique.general.canSee';
	public $memberships = array();
	public $openCliquen = array();
	public $itemsPerPage = 5;

	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		$this->readCliquen();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'openCliquen' => $this->openCliquen
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
		UserCPMenu::getInstance()->setActiveMenuItem('wcf.user.usercp.menu.link.clique.raiser');
		
		parent::show();
	}
	

	/**
		@see MultipleLinkPage::countItems()
	*/
		public function countItems() {
			parent::countItems();

			$sql = "SELECT		*
							FROM		wcf".WCF_N."_clique
							WHERE raiserID = ".WCF::getUser()->userID;
			$result = WCF::getDB()->sendQuery($sql);
			$this->countCliquen = WCF::getDB()->countRows($result);

			return $this->countCliquen;
		}

	/**
	 * Gets a list of all available cliquen.
	 */
	public function readCliquen() {
		$sql = "SELECT clique.cliqueID, clique.name, clique.status, clique.shortDescription,
							(SELECT Count(*) FROM wcf".WCF_N."_user_to_clique WHERE cliqueID = clique.cliqueID) AS countMemberships
						FROM wcf".WCF_N."_clique clique
						WHERE raiserID = ".WCF::getUser()->userID."
						ORDER BY name";
		$result = WCF::getDB()->sendQuery($sql, $this->itemsPerPage, ($this->pageNo - 1) * $this->itemsPerPage);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->openCliquen[] = $row;
		}
	}
}
?>