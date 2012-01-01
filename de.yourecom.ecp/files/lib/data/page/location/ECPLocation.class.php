<?php
// wcf imports
require_once(WCF_DIR.'lib/data/page/location/Location.class.php');

/**
 * @author		SpIkE2
 */
class ECPLocation implements Location {
	public $tourney = null;
	
	/**
	 * @see Location::cache()
	 */
	public function cache($location, $requestURI, $requestMethod, $match) {
	}
	
	/**
	 * @see Location::get()
	 */
	public function get($location, $requestURI, $requestMethod, $match) {
		if ($this->tourney == null) {
			$this->readTourneys();
		}

		$id = $match[1];
		if (!isset($this->tourney[$id])) {
			return '';
		}

		return WCF::getLanguage()->get($location['locationName'], array('$tourney' => '<a href="index.php?form=ECPTourneyDetail&eventID='.$id.SID_ARG_2ND.'">'.StringUtil::encodeHTML($this->tourney[$id]).'</a>'));
	}
	
	/**
	 * Gets users.
	 */
	protected function readTourneys() {
		$this->tourney = array();

		$sql = "SELECT id, name
			FROM events";

		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->tourney[$row['id']] = $row['name'];

		}
	}
}
?>