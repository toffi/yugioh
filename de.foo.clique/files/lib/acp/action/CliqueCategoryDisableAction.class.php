<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueCategoryDisableAction extends AbstractAction {
	public $categoryID = 0;

	public function readParameters() {
		parent::readParameters();
		if (isset($_GET['categoryID'])) $this->categoryID = intval($_GET['categoryID']);
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

			// Update
		$sql = "UPDATE wcf".WCF_N."_clique_category
						SET disabled = '1'
							WHERE categoryID = ".$this->categoryID;
		WCF::getDB()->sendQuery($sql);

		WCF::getCache()->clear(WCF_DIR . 'cache/', 'cache.CacheBuilderCliqueCategories.php');

		$this->executed();

		// forward to list page
		HeaderUtil::redirect('index.php?page=CliqueCategoryList&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>