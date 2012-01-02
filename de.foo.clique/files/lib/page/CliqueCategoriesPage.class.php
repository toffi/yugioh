<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
require_once(WCF_DIR.'lib/page/CliqueDetailPage.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueCategoriesPage extends AbstractPage {
	public static $defaultLetters = 'wcf.clique.general.letters';
	public $neededPermissions = 'user.clique.general.canSee';
	public $templateName = 'cliqueCategories';
	public $sqlConditions = '';
	public $cliqueCategories = array();

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		WCF::getCache()->addResource('CacheBuilderCliqueCategories', WCF_DIR.'cache/cache.CacheBuilderCliqueCategories.php', WCF_DIR.'lib/system/cache/CacheBuilderCliqueCategories.class.php');
		$categoriesCache = WCF::getCache()->get('CacheBuilderCliqueCategories');

		foreach ($categoriesCache as $key => $categorie) {
			if($categorie['disabled'] == 0) {
				$sql = "SELECT Count(*) AS countCliquen
								FROM wcf".WCF_N."_clique
								WHERE categorieID =".$categorie['categoryID'];
				$result = WCF::getDB()->getFirstRow($sql);
				$categorie['countCliquen'] = $result['countCliquen'];
				$this->cliqueCategories[] = $categorie;
			}
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// show page
		WCF::getTPL()->assign(array(
			'cliqueCategories' => $this->cliqueCategories,
			'left' => '',
			'right' => ''
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
}
?>