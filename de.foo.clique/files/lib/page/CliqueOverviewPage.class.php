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
class CliqueOverviewPage extends SortablePage {
	public static $defaultLetters = 'wcf.clique.general.letters';
	public $neededPermissions = 'user.clique.general.canSee';
	public $templateName = 'cliqueOverview';
	public $letter = '';
	public $sqlConditions = '';
	public $letters = array();
	public $allCliquen = array();
	public $defaultSortField = CLIQUE_LIST_DEFAULT_SORT_FIELD;
	public $sortOrder = CLIQUE_LIST_DEFAULT_SORT_ORDER;
	public $itemsPerPage = CLIQUE_PER_PAGE;
	public $selectData = CLIQUE_LIST_COLUMNS;
	public $columns = array();
    public $newestCliques = array();

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		WCF::getCache()->addResource('CacheBuilderCliqueBoxes', WCF_DIR.'cache/cache.CacheBuilderCliqueBoxes.php', WCF_DIR.'lib/system/cache/CacheBuilderCliqueBoxes.class.php');
		$categoriesCache = WCF::getCache()->get('CacheBuilderCliqueBoxes');
        $this->newestCliques = $categoriesCache['newest'];
  
		$cliqueColumns = explode(",", CLIQUE_LIST_COLUMNS);

		foreach ($cliqueColumns as $columns) {
			$this->columns[] = $columns;
		}

		// get available letters
		$defaultLetters = WCF::getLanguage()->get('wcf.clique.general.letters');
		if (!empty($defaultLetters) && $defaultLetters != 'wcf.clique.general.letters') self::$defaultLetters = $defaultLetters;

		// letter
		if (isset($_GET['letter']) && StringUtil::length($_GET['letter']) == 1 && StringUtil::indexOf(self::$defaultLetters, $_GET['letter']) !== false) {
			$this->letter = $_GET['letter'];
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

		$this->buildSqlConditions();
		$this->readAllCliquen();
		$this->loadLetters();

	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		WCF::getCache()->addResource('CacheBuilderCliqueCategories', WCF_DIR.'cache/cache.CacheBuilderCliqueCategories.php', WCF_DIR.'lib/system/cache/CacheBuilderCliqueCategories.class.php');

		// show page
		WCF::getTPL()->assign(array(
			'cliquen' => $this->allCliquen,
			'countCliquen' => $this->countCliquen,
			'letters' => $this->letters,
			'letter' => rawurlencode($this->letter),
			'columns' => $this->columns,
            'cliqueCategories' => WCF::getCache()->get('CacheBuilderCliqueCategories'),
            'newestCliques' => $this->newestCliques
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
	public function readAllCliquen() {
		$sql = "SELECT clique.cliqueID, width, height, ".$this->selectData.", user_table.username, (SELECT Count(*) FROM wcf".WCF_N."_user_to_clique WHERE cliqueID = clique.cliqueID) AS countMemberships, toClique.groupType
			FROM wcf".WCF_N."_clique clique
			LEFT JOIN	wcf".WCF_N."_user user_table
				ON (user_table.userID = clique.raiserID)
			LEFT JOIN	wcf".WCF_N."_user_to_clique toClique
				ON (toClique.cliqueID = clique.cliqueID && toClique.userID = ".WCF::getUser()->userID.")            
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '')."
			ORDER BY ".$this->sortField." ".$this->sortOrder;
		$result = WCF::getDB()->sendQuery($sql, $this->itemsPerPage, ($this->pageNo - 1) * $this->itemsPerPage);
		$this->countCliquen = WCF::getDB()->countRows($result);
		while ($row = WCF::getDB()->fetchArray($result)) {
			if(!empty($row['cliqueID'])) {
				if(isset($row['description'])) {
                    $row['description'] = self::getFormattedMessage($row['description']);
                }
				$this->allCliquen[] = $row;
			}
		}
	}

	/**
	 * Builds sql conditions.
	 */
	protected function buildSqlConditions() {
		if (!empty($this->letter)) {
			if (!empty($this->sqlConditions)) {
				$this->sqlConditions .= ' AND ';
			}
			if ($this->letter == '#') {
				$this->sqlConditions .= " SUBSTRING(name,1,1) IN ('0', '1', '2', '3', '4', '5', '6', '7', '8', '9')";
			}
			else {
				$this->sqlConditions .= " BINARY UPPER(SUBSTRING(name,1,1)) = '".escapeString($this->letter)."'";
			}
		}
	}

	/**
	 * Gets the list of available letters.
	 */
	protected function loadLetters() {
		for ($i = 0, $j = StringUtil::length(self::$defaultLetters); $i < $j; $i++) {
			$this->letters[] = StringUtil::substring(self::$defaultLetters, $i, 1);
		}
	}

	/**
		@see MultipleLinkPage::countItems()
	*/
	public function countItems() {
		parent::countItems();

		$sql = "SELECT clique.cliqueID
			FROM wcf".WCF_N."_clique clique
			".(!empty($this->sqlConditions) ? "WHERE ".$this->sqlConditions : '');
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