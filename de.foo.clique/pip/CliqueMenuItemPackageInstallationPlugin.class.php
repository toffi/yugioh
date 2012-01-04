<?php
require_once (WCF_DIR . 'lib/acp/package/plugin/AbstractXMLPackageInstallationPlugin.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueMenuItemPackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin {
	public $tagName = 'cliquemenuitem';
	public $tableName = 'clique_menu_item';

	/**
	 * @see PackageInstallationPlugin::install()
	 */
	public function install () {
		parent :: install();
		
		if (!$xml = $this->getXML()) {
			return;
		}
				
		// Create an array with the data blocks (import or delete) from the xml file.
		$headerMenuXML = $xml->getElementTree('data');
		
		// Loop through the array and install or uninstall items.
		foreach ($headerMenuXML['children'] as $block) {
			if (count($block['children'])) {
				// Handle the import instructions
				if ($block['name'] == 'import') {
					// Loop through items and create or update them.
					foreach ($block['children'] as $item) {
						// Extract item properties.
						foreach($item['children'] as $child) {
							if (!isset($child['cdata']))
								continue;
							$item[$child['name']] = $child['cdata'];
						}

						// check required attributes
						if (!isset($item['attrs']['name'])) {
							throw new SystemException("Required 'name' attribute for clique menu item tag is missing.", 13023);
						}
						
						// default values
						$menuItemLink = $menuItemIcon = $permissions = $groupoption = '';

						// get values
						$menuItem = $item['attrs']['name'];
						
						if (isset($item['link'])) {
							$menuItemLink = $item['link'];
						}
						if (isset($item['icon'])) {
							$menuItemIcon = $item['icon'];
						}
						if (isset($item['permissions'])) {
							$permissions = $item['permissions'];
						}
						if (isset($item['groupoption'])) {
							$groupoption = $item['groupoption'];
						}

							
						// Insert or update items. 
						// Update through the mysql "ON DUPLICATE KEY"-syntax. 
						$sql = "INSERT INTO	wcf" . WCF_N . "_".$this->tableName." 
											(packageID, menuItem, menuItemLink, menuItemIcon, permissions, groupoption)
								VALUES		(".$this->installation->getPackageID().",
											'".escapeString($menuItem)."',
											'".escapeString($menuItemLink)."',
											'".escapeString($menuItemIcon)."',
											'".escapeString($permissions)."',
											'".escapeString($groupoption)."')
								ON DUPLICATE KEY UPDATE 	menuItemLink = '".escapeString($menuItemLink)."',
											menuItemIcon = '".escapeString($menuItemIcon)."',
											permissions = '".escapeString($permissions)."',
                                            groupoption = '".escapeString($groupoption)."'";
						WCF :: getDB()->sendQuery($sql);
					}
				} 
				elseif ($block['name'] == 'delete' && $this->installation->getAction() == 'update') {
					// Loop through items and delete them.
					$itemNames = '';
					foreach ($block['children'] as $menuItem) {
						// check required attributes
						if (!isset($menuItem['attrs']['name'])) {
							throw new SystemException("Required 'name' attribute for clique menu item tag is missing.", 13023);
						}
						// Create a string with all item names which should be deleted (comma seperated).
						if (!empty($itemNames)) {
							$itemNames .= ',';
						}
						$itemNames .= "'".escapeString($menuItem['attrs']['name'])."'";
					}
					
					// Delete items.
					if (!empty($itemNames)) {
						$sql = "DELETE FROM	wcf".WCF_N."_".$this->tableName." 
								WHERE		packageID = ".$this->installation->getPackageID()."
											AND menuItem IN (".$itemNames.")";
						WCF::getDB()->sendQuery($sql);
					}
				}
			}
		}
	}
	
	public function uninstall() 
	{
		parent::uninstall();
		
		// clear cache immediately
		WCF::getCache()->clear(WCF_DIR.'cache', 'cache.cliqueMenuItem-*.php');
	}
}
?>