<?PHP
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */

class CacheBuilderCliqueCategories implements CacheBuilder {
	public $categoryList = array();
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$sql = "SELECT *
			FROM wcf".WCF_N."_clique_category
			WHERE disabled= 0
			ORDER BY showOrder ASC, category ASC";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->categoryList[] = $row;
		}

		return $this->categoryList;
	}

}

?>