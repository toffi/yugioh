<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/CliqueCategoryAddForm.class.php');

/**
 * Shows the form for editing smiley categories.
 *
 * @author	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	WoltLab Burning Board License <http://www.woltlab.com/products/burning_board/license.php>
 * @package	com.woltlab.wcf.acp.content.smiley
 * @subpackage	acp.form
 * @category 	Community Framework (commercial)
 */
class CliqueCategoryEditForm extends CliqueCategoryAddForm {
	public $activeMenuItem = 'wcf.acp.menu.link.user.clique';
	public $templateName = 'cliqueCategoryEdit';
	public $category = '';
	public $showOrder = 0;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['categoryID'])) $this->categoryID = intval($_REQUEST['categoryID']);
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		ACPForm::save();
		
			// Update
		$sql = "UPDATE wcf".WCF_N."_clique_category
						SET	category = '".escapeString($this->category)."',
								showOrder = '".$this->showOrder."'
						WHERE categoryID = ".$this->categoryID;
		WCF::getDB()->sendQuery($sql);

		WCF::getCache()->clear(WCF_DIR . 'cache/', 'cache.CacheBuilderCliqueCategories.php');
		$this->saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		$sql = "SELECT	categoryID, category, showOrder
						FROM		wcf".WCF_N."_clique_category
						WHERE		categoryID = ".$this->categoryID;
		$this->categoryList = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'categoryID' => $this->categoryList['categoryID'],
			'category' => $this->categoryList['category'],
			'showOrder' => $this->categoryList['showOrder']
		));
	}
}
?>