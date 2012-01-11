<?php
// wcf imports
require_once(WCF_DIR.'lib/page/AbstractCliqueSortablePage.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueCommentsPage extends AbstractCliqueSortablePage {
	public $neededPermissions = 'user.clique.general.canSee';
	public $templateName = 'cliqueComments';
	public $comments = array();
	public $itemsPerPage = CLIQUE_COMMENTS_PER_PAGE;
	public $sortableOrder = 0;
	public $haveApplay = 0;

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		WCF::getCache()->addResource('CacheBuilderCliqueCategories', WCF_DIR.'cache/cache.CacheBuilderCliqueCategories.php', WCF_DIR.'lib/system/cache/CacheBuilderCliqueCategories.class.php');
		$this->categoriesArray = WCF::getCache()->get('CacheBuilderCliqueCategories');
		$this->comments = self::readComments($this->cliqueID, $this->pageNo, $this->itemsPerPage);
	}
    
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		// mark Clique as active
		require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
		PageMenu::setActiveMenuItem('wcf.header.menu.clique');

		// mark Comments as active and get menu items
		require_once(WCF_DIR.'lib/data/clique/CliqueMenuItems.class.php');
		CliqueMenuItems::setActiveMenuItem('wcf.clique.comment');

		WCF::getTPL()->assign(array(
			'comments' => $this->comments
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		if (!CliqueEditor::getCliquePermission('canSeeComments')) {
			throw new PermissionDeniedException();
		}

		parent::show();
	}
	
	/**
	 * Gets a list of all memberships of the active user.
	 */
	public static function readComments($cliqueID, $pageNo, $itemsPerPage, $removeBreaks = 0) {
		$comments = array();
		$sql = "SELECT user_table.username, avatar.*, comments.*
						FROM		wcf".WCF_N."_clique_comments comments
						LEFT JOIN wcf".WCF_N."_user user_table
							ON		(user_table.userID = comments.userID)
						LEFT JOIN	wcf".WCF_N."_avatar avatar
							ON (avatar.avatarID = user_table.avatarID)
						WHERE		comments.cliqueID = ".$cliqueID."
						ORDER BY	comments.commentID DESC";
		$result = WCF::getDB()->sendQuery($sql, $itemsPerPage, ($pageNo - 1) * $itemsPerPage);

		while ($row = WCF::getDB()->fetchArray($result)) {
			$row['message'] = self::getFormattedMessage($row['message']);
			if($removeBreaks == 1) {
				$row['message'] = str_replace('<br />', '', $row['message']);
			}
			$comments[] = new UserProfile(null, $row);
		}

		return $comments;
	}

	/**
		@see MultipleLinkPage::countItems()
	*/
	public function countItems($cliqueID=0) {
		parent::countItems();

		if(empty($cliqueID)) $cliqueID = $this->cliqueID;
		$sql = "SELECT comments.*
						FROM		wcf".WCF_N."_clique_comments comments
						WHERE		comments.cliqueID = ".$cliqueID;
		$result = WCF::getDB()->sendQuery($sql);
		$this->countComments = WCF::getDB()->countRows($result);
		return $this->countComments;
	}

	/**
	 * Returns the formatted message.
	 *
	 * @return	string
	 */
	public static function getFormattedMessage($text) {
		require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');
		MessageParser::getInstance()->setOutputType('text/html');
		return MessageParser::getInstance()->parse($text, true, false, true, false);
	}
}
?>