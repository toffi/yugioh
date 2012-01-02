<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CacheBuilderCliquePageMenu implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $packageID) = explode('-', $cacheResource['cache']); 
		$data = array();

		// get all menu items
			// get needed menu items and build item tree
			$sql = "SELECT		menuItemID, menuItem, menuItemLink, menuItemIcon, permissions, groupoption
				FROM		wcf".WCF_N."_clique_menu_item
				ORDER BY	menuItemID ASC";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$data[] = $row;
			}
		return $data;
	}
}
?>