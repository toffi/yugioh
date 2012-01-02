<?PHP
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 *  Create Cachefile
 *
 * @author $foo
 * @license LGPL
 * @package de.foo.userjubilee
 */

class CacheBuilderUserjubileeIndex implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {

		$data = array();
		$timeCoulBeAway = 0;

		// 86.400 sek ist one day
		if(USERJUBILEE_DAYS_AWAY != 0){
			$timeCoulBeAway = TIME_NOW - USERJUBILEE_DAYS_AWAY * 86400;
		}
		$sql = "SELECT userID, username, registrationDate
						FROM wcf".WCF_N."_user
						WHERE lastActivityTime >=".$timeCoulBeAway." AND DATE_FORMAT(FROM_UNIXTIME(registrationDate),'%m') = ".date("m")." AND DATE_FORMAT(FROM_UNIXTIME(registrationDate),'%d') = ".date("d");
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$row['years'] = date("Y") - date("Y", $row['registrationDate']);
			if($row['years'] >= 1) $data[] = $row;
		}

		return $data;
	}

}

?>