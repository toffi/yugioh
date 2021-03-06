<?php
// wcf imports
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/data/clique/CliqueEditor.class.php');
require_once(WCF_DIR.'lib/data/clique/Clique.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class AbstractCliqueSortablePage extends SortablePage {
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
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		$this->clique = Clique::getCacheClique($this->cliqueID);

				// Check if clique exist
		if (!$this->clique->cliqueID) {
				throw new IllegalLinkException();
		}
		$this->isMember = $this->clique->isMember();
		$this->haveApplay = $this->clique->haveApplay();
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		// mark Clique as active
		require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
		PageMenu::setActiveMenuItem('wcf.header.menu.clique');

		WCF::getTPL()->assign(array(
			'cliqueID' => $this->clique->cliqueID,
			'clique' => $this->clique,
			'userRating' => ($this->clique->ratings != 0) ? round($this->clique->rating / $this->clique->ratings) : 0,
			'isMember' => $this->isMember,
			'cliquePermissions' => new CliqueEditor($this->clique->cliqueID),
			'haveApplay' => $this->haveApplay,
			'applicationOpen' => $this->applicationOpen
		));
	}
}  
?>