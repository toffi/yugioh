<?php
// wcf imports
require_once(WCF_DIR.'lib/page/CliqueDetailPage.class.php');
require_once(WCF_DIR.'lib/data/clique/CliqueEditor.class.php');
// wbb imports
require_once(WBB_DIR.'lib/page/ThreadPage.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueThreadPage extends ThreadPage {
	public $templateName = 'cliqueThread';
	public $cliqueID = 0;
    public $isMember = 0;
    public $applicationOpen = 0;

	/**
	 * @see Page::readData()
	 */
	public function readParameters() {
		if(isset($_GET['cliqueID'])) $this->cliqueID = intval($_GET['cliqueID']);
        if(isset($_GET['application'])) $this->applicationOpen = intval($_GET['application']);
        $this->clique = new Clique($this->cliqueID);
  
		if (isset($_REQUEST['threadID'])) $this->threadID = intval($_REQUEST['threadID']);
		else if (isset($_REQUEST['threadid'])) $this->threadID = intval($_REQUEST['threadid']); // wbb2 style
		else if (isset($_REQUEST['postID'])) $this->postID = intval($_REQUEST['postID']);
		else if (isset($_REQUEST['postid'])) $this->postID = intval($_REQUEST['postid']); // wbb2 style
        if (isset($_REQUEST['action'])) $this->action = $_REQUEST['action'];

		// get thread
		$this->thread = new ViewableThread($this->threadID, null, $this->postID);
		$this->threadID = $this->thread->threadID;

		// get board
		$this->board = Board::getBoard($this->thread->boardID);
		if ($this->board->postSortOrder) $this->sortOrder = $this->board->postSortOrder;
		if ($this->board->enableRating != -1) $this->enableRating = $this->board->enableRating;

		// init post list
		$this->postList = new ThreadPostList($this->thread, $this->board);
		$this->postList->sqlOrderBy = 'post.time '.$this->sortOrder;

		// handle jump to
		if ($this->action == 'lastPost') $this->goToLastPost();
		if ($this->action == 'firstNew') $this->goToFirstNewPost();

        parent::readParameters();
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
						// Check if clique exist
		if (!$this->clique->cliqueID) {
				throw new IllegalLinkException();
		}
        $this->isMember = $this->clique->isMember();
        $this->haveApplay = $this->clique->haveApplay();

		WCF::getCache()->addResource('CacheBuilderCliqueCategories', WCF_DIR.'cache/cache.CacheBuilderCliqueCategories.php', WCF_DIR.'lib/system/cache/CacheBuilderCliqueCategories.class.php');
		$this->categoriesArray = WCF::getCache()->get('CacheBuilderCliqueCategories');
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		

		WCF::getTPL()->assign(array(
			'cliqueID' => $this->clique->cliqueID,
			'clique' => $this->clique,
			'userRating' => ($this->clique->ratings != 0) ? round($this->clique->ratings / $this->clique->ratings) : 0,
			'isMember' => $this->isMember,
			'cliquePermissions' => new CliqueEditor($this->clique->cliqueID),
            'haveApplay' => $this->haveApplay,
            'applicationOpen' => $this->applicationOpen
		));
        parent::assignVariables();
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		if (!CliqueEditor::getCliquePermission('canSeeBoards') && !WCF::getUser()->getPermission('mod.clique.general.cannSeeAllBoards')) {
			throw new PermissionDeniedException();
		}
		// mark Clique as active
		require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
		PageMenu::setActiveMenuItem('wcf.header.menu.clique');

		// mark Overview as active and get menu items
		require_once(WCF_DIR.'lib/data/clique/CliqueMenuItems.class.php');
		CliqueMenuItems::setActiveMenuItem('wbb.board.name.clique.board');

		parent::show();
	}

	/**
	 * Gets the post id of the last post in this thread and forwards the user to this post.
	 */
	protected function goToLastPost() {
		$sql = "SELECT		postID
			FROM 		wbb".WBB_N."_post
			WHERE 		threadID = ".$this->threadID.
					$this->postList->sqlConditionVisible."
			ORDER BY 	time ".($this->sortOrder == 'ASC' ? 'DESC' : 'ASC');
		$result = WCF::getDB()->getFirstRow($sql);
		HeaderUtil::redirect('index.php?page=CliqueThread&cliqueID='.$this->cliqueID.'&postID=' . $result['postID'] . SID_ARG_2ND_NOT_ENCODED . '#post' . $result['postID'], true, true);
		exit;
	}
	
	/**
	 * Forwards the user to the first new post in this thread.
	 */
	protected function goToFirstNewPost() {
		$lastVisitTime = intval($this->thread->lastVisitTime);
		$sql = "SELECT		postID
			FROM 		wbb".WBB_N."_post
			WHERE 		threadID = ".$this->threadID.
					$this->postList->sqlConditionVisible."
					AND time > ".$lastVisitTime."
			ORDER BY 	time ASC";
		$result = WCF::getDB()->getFirstRow($sql);
		if (isset($result['postID'])) {
			HeaderUtil::redirect('index.php?page=CliqueThread&cliqueID='.$this->cliqueID.'&postID=' . $result['postID'] . SID_ARG_2ND_NOT_ENCODED . '#post' . $result['postID'], true, true);
			exit;
		}
		else $this->goToLastPost();
	}
}
?>