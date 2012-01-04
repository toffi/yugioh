<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractCliqueSortablePage.class.php');
require_once(WCF_DIR.'lib/page/CliqueCommentsPage.class.php');


/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */

class CliqueDetailPage extends AbstractCliqueSortablePage {
	public $neededPermissions = 'user.clique.general.canSee';
	public $templateName = 'cliqueDetail';
	public $itemsPerPage = CLIQUE_COMMENTS_PER_DETAIL_PAGE;
	public $commentsPerPage = 5;
	public $cliqueVisitors = array();
	public $cliqueComments = array();
	public $memberships = array();
	public $countComments = 0;

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		$this->readMemberships();
		if(CliqueEditor::getCliquePermission('canSeeComments') && $this->clique->commentEnable == 1) {
			$this->cliqueComments = CliqueCommentsPage::readComments($this->cliqueID, 1, $this->commentsPerPage, 1);
			$this->countComments = count($this->cliqueComments);
		}
		if(CLIQUE_VISITORS_ENABLE) {
			$this->cliqueVisitors = CliqueEditor::getCliqueVisitors($this->cliqueID);
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		// mark Overview as active and get menu items
		require_once(WCF_DIR.'lib/data/clique/CliqueMenuItems.class.php');
		CliqueMenuItems::setActiveMenuItem('wcf.clique.comment.overview');

		WCF::getTPL()->assign(array(
			'memberships' => $this->memberships,
			'countMemberships' => $this->count,
			'cliqueVisitors' => $this->cliqueVisitors,
			'cliqueComments' => $this->cliqueComments,
			'countComments' => $this->countComments,
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
			//save visitors
		if(CLIQUE_VISITORS_ENABLE && WCF::getUser()->userID && !WCF::getUser()->invisible) {
			CliqueEditor::insertVisitor($this->cliqueID);
			CliqueEditor::cleanVisitors($this->cliqueID);
		}

		parent::show();
	}

	/**
	 * Gets a list for the clique details.
	 */
	public static function readClique($cliqueDetailID) {
		WCF::getCache()->addResource('CacheBuilderCliqueCategories', WCF_DIR.'cache/cache.CacheBuilderCliqueCategories.php', WCF_DIR.'lib/system/cache/CacheBuilderCliqueCategories.class.php');
		$categoriesArray = WCF::getCache()->get('CacheBuilderCliqueCategories');

		$cliqueDetail = Clique::getCacheClique($cliqueDetailID, 'infos');
		foreach ($categoriesArray as $value) {
			if($value['categoryID'] == $cliqueDetail['categorieID']) {
				$cliqueDetail['categorie'] = $value['category'];
				break;
			}
		}
		$cliqueDetail->description = self::getFormattedMessage($cliqueDetail->description);
		return $cliqueDetail;
	}

	/**
	 * Gets a list of all memberships of the clique.
	 */
	public function readMemberships() {
		$members = Clique::getCacheClique($this->cliqueID, 'members');
		for($a = ($this->pageNo - 1) * $this->itemsPerPage; $a < $this->pageNo * $this->itemsPerPage; $a++) {
			if(isset($this->gamers[$a])) {
				$this->memberships[$a] = $members[$a];
			}
		}
	}

	/**
		@see MultipleLinkPage::countItems()
	*/
		public function countItems() {
			parent::countItems();

			$sql = "SELECT *
							FROM wcf".WCF_N."_user_to_clique
							WHERE cliqueID = ".$this->cliqueID;
			$result = WCF::getDB()->sendQuery($sql);
			$this->count = WCF::getDB()->countRows($result);

			return $this->count;
		}


	/**
	 * Returns the formatted message.
	 *
	 * @return	string
	 */
	public static function getFormattedMessage($text) {
		require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');
		MessageParser::getInstance()->setOutputType('text/html');
		return MessageParser::getInstance()->parse($text, true, false, true, false);
	}
}
?>