<?php
// wcf imports
require_once(WCF_DIR.'lib/page/CliqueDetailPage.class.php');
require_once(WCF_DIR.'lib/data/clique/CliqueEditor.class.php');
// wbb imports
require_once(WBB_DIR.'lib/form/ThreadAddForm.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueThreadAddForm extends ThreadAddForm {
	// system
	public $templateName = 'cliqueThreadAdd';
	public $cliqueID = 0;
    public $isMember = 0;
    public $applicationOpen = 0;

	/**
	 * @see Page::readData()
	 */
	public function readParameters() {
		parent::readParameters();
		if(isset($_GET['cliqueID'])) $this->cliqueID = intval($_GET['cliqueID']);
        if(isset($_GET['application'])) $this->applicationOpen = intval($_GET['application']);
        $this->clique = new Clique($this->cliqueID);
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
	public function save() {
		// search for double posts
		if ($postID = PostEditor::test($this->subject, $this->text, WCF::getUser()->userID, $this->username)) {
			HeaderUtil::redirect('index.php?page=CliqueThread&postID=' . $postID . SID_ARG_2ND_NOT_ENCODED . '#post' . $postID);
			exit;
		}
		parent::save();
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'cliqueID' => $this->clique->cliqueID,
			'clique' => $this->clique,
			'userRating' => ($this->clique->ratings != 0) ? round($this->clique->ratings / $this->clique->ratings) : 0,
			'isMember' => $this->isMember,
			'cliquePermissions' => new CliqueEditor($this->clique->cliqueID),
            'haveApplay' => $this->haveApplay,
            'applicationOpen' => $this->applicationOpen
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		if (!CliqueEditor::getCliquePermission('canMakePosts') && !WCF::getUser()->getPermission('mod.clique.general.cannStartPostsAllBoards')) {
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
}
?>