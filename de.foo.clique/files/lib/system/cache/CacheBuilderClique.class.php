<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');
require_once(WCF_DIR.'lib/data/clique/Clique.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CacheBuilderClique implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$data = array();
		$data['infos'] = $data['members'] = $data['permission'] = array();

		// Get eventID
		preg_match("/\d+/", $cacheResource['cache'], $result);
		$cliqueID = $result[0];
		
		// read clique
		$data['infos'] = new Clique($cliqueID);
		
		//read membership
		$sql = "SELECT *
			FROM wcf".WCF_N."_user_to_clique
			WHERE		cliqueID = ".$cliqueID."
			ORDER BY enteredTime DESC";
		$result = WCF::getDB()->sendQuery($sql);
		$this->countMemberships = WCF::getDB()->countRows($result);
		while ($row = WCF::getDB()->fetchArray($result)) {
			//if($row['userID'] == WCF::getUser()->userID) $this->isMember = 1;
			$data['members'][$row['userID']] = array('user' => new UserProfile($row['userID']),
										'groupType' => $row['groupType']);
		}
		
		//read permissions
		$sql = "SELECT permission.*
			FROM wcf".WCF_N."_clique_group_rights permission
			WHERE permission.cliqueID =".$cliqueID;
		$data['permission'] = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);
		
		return $data;
	}
}
?>