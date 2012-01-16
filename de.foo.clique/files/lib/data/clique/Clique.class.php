<?php
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
 
class Clique extends DatabaseObject {
	public function __construct($cliqueID, $row = null, $cliqueName = null) {
		// execute sql statement
		$sqlCondition = '';
		if ($cliqueID !== null) {
			$sqlCondition = "clique.cliqueID = ".$cliqueID;
		}
		else if ($cliqueName !== null) {
			$sqlCondition = "clique.name = '".escapeString($cliqueName)."'";
		}
		
		if (!empty($sqlCondition)) {
			$sql = "SELECT 	clique.*, user_table.username
				FROM 	wcf".WCF_N."_clique clique
				LEFT JOIN 	wcf".WCF_N."_user user_table
					ON		(user_table.userID = clique.raiserID)
					".$this->sqlJoins."
				WHERE 	".$sqlCondition.
					$this->sqlGroupBy;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		$row['description'] = self::getFormattedMessage($row['description']);
		
		// handle result set
		parent::__construct($row);
	}

	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		if (!$this->cliqueID) $this->data['cliqueID'] = 0;
	}

	/**
	 * Is the current user a member
	 */
	public function isMember($userID = 0) {
		$members = Clique::getCacheClique($this->cliqueID, 'members');				
		if($userID == 0) $userID = WCF::getUser()->userID;
				
		if(isset($members[$userID])) {
					
		}
		else {
			return 0;
			
		}return 1;
		return $isMember;
	}

	/**
	 * Have the clique a not answered invite?
	 */
	public function haveApplay() {
		$haveApplay = 0;
		$sql = "SELECT userID
			FROM wcf".WCF_N."_clique_application
			WHERE		cliqueID = ".$this->cliqueID."
				AND userID=".WCF::getUser()->userID;
		$result = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);
		if(!empty($result)) $haveApplay = 1;
		return $haveApplay;
	}

	public static function getFormattedMessage($text) {
		require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');
		MessageParser::getInstance()->setOutputType('text/html');
		return MessageParser::getInstance()->parse($text, true, false, true, false);
	}

	/**
	 * Return the league cache.
	 */
	public static function resetCacheClique($cliqueID) {
		// reset cache
		WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.cacheBuilderClique'.$cliqueID.'-'.PACKAGE_ID.'.php');
	}

	/**
	 * Resets the league cache.
	 */
	public static function getCacheClique($cliqueID, $key = 'infos') {
		WCF::getCache()->addResource('cacheBuilderClique'.$cliqueID.'-'.PACKAGE_ID, WCF_DIR.'cache/cache.cacheBuilderClique'.$cliqueID.'-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderClique.class.php');
		return WCF::getCache()->get('cacheBuilderClique'.$cliqueID.'-'.PACKAGE_ID, $key);
	}


	/**
	 * Return the league cache.
	 */
	public static function resetCacheCliqueInvites() {
		// reset cache
		WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.cacheBuilderCliqueInvites-'.PACKAGE_ID.'.php');
	}

	/**
	 * Resets the league cache.
	 */
	public static function getCacheCliqueInvites() {
		WCF::getCache()->addResource('cacheBuilderCliqueInvites-'.PACKAGE_ID, WCF_DIR.'cache/cache.cacheBuilderCliqueInvites-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderCliqueInvites.class.php');
		return WCF::getCache()->get('cacheBuilderCliqueInvites-'.PACKAGE_ID);
	}
}
?>