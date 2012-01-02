<?php
// wcf imports
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/CliqueDetailPage.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliquenOverviewCategoryPage extends SortablePage {
	public $templateName = 'cliquenOverviewCategory';
	public $category = 0;
	public $allCliquen = array();
	public $defaultSortField = CLIQUE_LIST_DEFAULT_SORT_FIELD;
	public $sortOrder = CLIQUE_LIST_DEFAULT_SORT_ORDER;
	public $itemsPerPage = CLIQUE_PER_PAGE;
	public $selectData = CLIQUE_LIST_COLUMNS;
	public $columns = array();

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		$this->category = intval($_GET['categoryID']);

		$cliqueColumns = explode(",", CLIQUE_LIST_COLUMNS);

		foreach ($cliqueColumns as $columns) {
			$this->columns[] = $columns;
		}

	}

	/**
	 * @see SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();
		switch ($this->sortField) {
			case 'image':
			case 'name':
			case 'countMemberships':
			case 'time';
			case 'status';
			case 'shortDescription';
			case 'description';
			case 'rating';
			break;
			default: $this->sortField = $this->defaultSortField;
		}
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		if (empty($this->selectData)) {
			$this->selectData = 'name';
		}
		if (empty($this->sortField)) {
			$this->sortField = 'name';
		}

		$this->readCliquen();
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// show page
		WCF::getTPL()->assign(array(
			'cliquen' => $this->allCliquen,
			'countCliquen' => $this->countCliquen,
			'categoryID' => $this->category,
			'columns' => $this->columns
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// set active header menu item
		PageMenu::setActiveMenuItem('wcf.header.menu.clique');
		parent::show();
	}

	/**
	 * Gets a list of all cliquen.
	 */
	public function readCliquen() {
		$sql = "SELECT clique.*, user_table.username, (SELECT Count(*) FROM wcf".WCF_N."_user_to_clique WHERE cliqueID = clique.cliqueID) AS countMemberships
			FROM wcf".WCF_N."_clique clique
			LEFT JOIN 	wcf".WCF_N."_user user_table
				ON (user_table.userID = clique.raiserID)
			WHERE clique.categorieID = ".$this->category."
			ORDER BY ".$this->sortField." ".$this->sortOrder;
		$result = WCF::getDB()->sendQuery($sql, $this->itemsPerPage, ($this->pageNo - 1) * $this->itemsPerPage);
		$this->countCliquen = WCF::getDB()->countRows($result);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if(!empty($row['cliqueID'])) {
				$row['description'] = self::getFormattedMessage($row['description']);
				$this->allCliquen[] = $row;
			}
		}
	}

	/**
		@see MultipleLinkPage::countItems()
	*/
	public function countItems() {
		parent::countItems();

		$sql = "SELECT clique.cliqueID
			FROM wcf".WCF_N."_clique clique
			WHERE clique.categorieID = ".$this->category;
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