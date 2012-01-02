<?php
// wbb imports
require_once(WBB_DIR.'lib/data/board/Board.class.php');

// wcf imports
require_once(WCF_DIR.'lib/data/page/location/Location.class.php');

/**
 * @author		SpIkE2
 */
class CliqueBoardLocation implements Location {
	public $clique = null;
	public $boards = null;

	/**
	 * @see Location::cache()
	 */
	public function cache($location, $requestURI, $requestMethod, $match) {}
	
	/**
	 * @see Location::get()
	 */
	public function get($location, $requestURI, $requestMethod, $match) {

		if ($this->clique == null) {
			$this->readClique($match[1]);
		}

		if ($this->boards == null) {
			$this->readBoards();
		}

		$boardID = $this->clique['boardID'];
		if (!isset($this->boards[$boardID]) || !$this->boards[$boardID]->getPermission()) {
			return '';
		}

		return WCF::getLanguage()->get($location['locationName'], array('$board' => '<a href="index.php?page=CliqueBoard&amp;boardID='.$this->boards[$boardID]->boardID.SID_ARG_2ND.'">'.WCF::getLanguage()->get(StringUtil::encodeHTML($this->boards[$boardID]->title)).'</a>'));
	}
	
	/**
	 * Gets clique.
	 */
	protected function readClique($ID) {
		$sql = "SELECT boardID
						FROM wcf".WCF_N."_clique
						WHERE cliqueID=".$ID;
		$this->clique = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);
	}

	/**
	 * Gets boards from cache.
	 */
	protected function readBoards() {
		$this->boards = WCF::getCache()->get('board', 'boards');
	}
}
?>