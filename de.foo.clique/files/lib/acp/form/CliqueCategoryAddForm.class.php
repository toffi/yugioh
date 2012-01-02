<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');

/**
 * Shows the smiley add form.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	WoltLab Burning Board License <http://www.woltlab.com/products/burning_board/license.php>
 * @package	com.woltlab.wcf.acp.content.smiley
 * @subpackage	acp.form
 * @category 	Community Framework (commercial)
 */
class CliqueCategoryAddForm extends ACPForm {
	public $templateName = 'cliqueCategoryAdd';
	public $activeMenuItem = 'wcf.acp.menu.link.user.clique.category';

	public $showOrder = 0;
	public $category = array();

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['category'])) $this->category = StringUtil::trim($_POST['category']);
		if (isset($_POST['showOrder'])) $this->showOrder = intval($_POST['showOrder']);
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		if(!empty($this->category)) $this->category = StringUtil::trim($this->category);
			else throw new UserInputException('category','empty');
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();

			// Eintragen
		$sql = "INSERT INTO
							wcf".WCF_N."_clique_category(category, showOrder)
						VALUES
							('".escapeString($this->category)."',
							".$this->showOrder.")";
		WCF::getDB()->sendQuery($sql);
		
		// reset cache
		WCF::getCache()->clear(WCF_DIR . 'cache/', 'cache.CacheBuilderCliqueCategories.php');

		// show success message
		WCF::getTPL()->assign(array(
			'success' => true
		));

		// reset values
        $this->category = '';
        $this->showOrder = 0;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'category' => $this->category,
			'showOrder' => $this->showOrder
		));
	}
}
?>