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
class CacheBuilderCliqueInvites implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$data = array('invites' => array(), 'aplications' => array());

		$sql = "SELECT invites.*, user_table.username, clique.name
			FROM wcf".WCF_N."_clique_invite invites
			LEFT JOIN 	wcf".WCF_N."_user user_table
				ON		(user_table.userID = invites.inviterID)
			LEFT JOIN 	wcf".WCF_N."_clique clique
				ON		(clique.cliqueID = invites.cliqueID)
			WHERE	invites.time >=".WCF::getUser()->cliqueMessage;
		$result = WCF::getDB()->sendQuery($sql);
		$this->countInvites = WCF::getDB()->countRows($result);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$data['invites'][$row['inviteeID']][] = $row;
		}

		return $data;
	}
}
?>