<?php
// wcf imports
require_once(WCF_DIR.'lib/page/SortablePage.class.php');

/**
 * Shows a list of installed smilies.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	WoltLab Burning Board License <http://www.woltlab.com/products/burning_board/license.php>
 * @package	com.woltlab.wcf.acp.content.smiley
 * @subpackage	acp.page
 * @category 	Community Framework (commercial)
 */
class CliqueCategoryListPage extends SortablePage {
	// system
	public $templateName = 'cliqueCategoryList';
	public $activeMenuItem = 'wcf.acp.menu.link.user.clique.category';
	public $defaultSortField = 'showOrder';
	public $categoryList = array();
	public $countCategories = 0;
	public $deletedCliqueCategory = 0;

	/**
	 * @see Page::readParameters() TODO ALLES ^^
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_GET['deletedCliqueCategory'])) $this->deletedCliqueCategory = intval($_GET['deletedCliqueCategory']);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		$sql = "SELECT *
			FROM wcf".WCF_N."_clique_category
			ORDER BY ".$this->sortField." ".$this->sortOrder;
		$result = WCF::getDB()->sendQuery($sql, $this->itemsPerPage, ($this->pageNo - 1) * $this->itemsPerPage);
		$this->countCategories = WCF::getDB()->countRows($result);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->categoryList[] = $row;
		}
	}
	
	/**
	 * @see SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();
		
		switch ($this->sortField) {
			case 'categoryID':
			case 'category':
			case 'showOrder': break;
			default: $this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->countCategories;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'categoryList' => $this->categoryList,
			'countCategories' => $this->countCategories,
			'deletedCliqueCategory' => $this->deletedCliqueCategory
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.user.clique.category.list');

		parent::show();
	}
}
?>