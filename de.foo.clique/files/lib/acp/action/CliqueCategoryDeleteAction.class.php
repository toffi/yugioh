<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueCategoryDeleteAction extends AbstractAction {
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

		$delete = "DELETE FROM
								wcf".WCF_N."_clique_category
							WHERE categoryID = ".$this->categoryID;
		WCF::getDB()->sendQuery($delete);

		WCF::getCache()->clear(WCF_DIR . 'cache/', 'cache.CacheBuilderCliqueCategories.php');

		$this->executed();
		
		// forward to list page
		HeaderUtil::redirect('index.php?page=CliqueCategoryList&deletedCliqueCategory=1&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>