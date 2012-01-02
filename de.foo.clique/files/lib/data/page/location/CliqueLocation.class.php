<?php
// wcf imports
require_once(WCF_DIR.'lib/data/page/location/Location.class.php');

/**
 * @author		SpIkE2
 */
class CliqueLocation implements Location {
	public $clique = null;
	
	/**
	 * @see Location::cache()
	 */
	public function cache($location, $requestURI, $requestMethod, $match) {
	}
	
	/**
	 * @see Location::get()
	 */
	public function get($location, $requestURI, $requestMethod, $match) {
		if ($this->clique == null) {
			$this->readClique($match[1]);
		}

		return WCF::getLanguage()->get($location['locationName'], array('$clique' => '<a href="index.php?page=CliqueDetail&cliqueID='.$match[1].SID_ARG_2ND.'">'.StringUtil::encodeHTML($this->clique['name']).'</a>'));
	}
	
	/**
	 * Gets clique.
	 */
	protected function readClique($ID) {
		$sql = "SELECT name
						FROM wcf".WCF_N."_clique
						WHERE cliqueID=".$ID;
		$this->clique = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);
	}
}
?>