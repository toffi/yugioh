<?PHP
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */

class CacheBuilderCliqueBoxes implements CacheBuilder {
	public $cliques = array('newest' => array());
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$sql = "SELECT clique.*, user.username
			FROM wcf".WCF_N."_clique clique
			LEFT JOIN   wcf".WCF_N."_user user
			  ON (user.userID = clique.raiserID)
			ORDER BY cliqueID DESC
			LIMIT 5";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->cliques['newest'][] = $row;
		}
		
		return $this->cliques;
	}

}

?>