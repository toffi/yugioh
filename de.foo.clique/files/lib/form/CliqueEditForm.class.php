<?php
// wcf imports
require_once(WCF_DIR.'lib/form/CliqueAddForm.class.php');
require_once(WCF_DIR.'lib/data/clique/Clique.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueEditForm extends CliqueAddForm {
	public $templateName = 'cliqueEdit';
	public $cliqueID = 0;
	public $categoriesArray = array();
	public $photoSql = '';

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if (isset($_REQUEST['cliqueID'])) $this->cliqueID = intval($_REQUEST['cliqueID']);
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		$this->clique = new Clique($this->cliqueID);
				// Check if clique exist
		if (!$this->clique->cliqueID) {
				throw new IllegalLinkException();
		}
		parent::readData();
	}

	/**
	 * @see Form::save()
	 */
	public function save(){
		AbstractForm::save();

			// Update
		$sql = "UPDATE wcf".WCF_N."_clique
						SET name = '".escapeString($this->name)."',
								status = ".$this->status.",
								shortDescription = '".escapeString($this->shortDescription)."',
								description = '".escapeString($this->text)."',
								categorieID = ".$this->categorieID."
								".$this->photoSql."
						WHERE cliqueID = ".$this->cliqueID;
		WCF::getDB()->sendQuery($sql);
		
		$this->saved();
		
		// reset cache
		WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.CacheBuilderCliqueBoxes.php');
		Clique::resetCacheClique($this->cliqueID);
		
			// Redirect
		WCF::getTPL()->assign(array(
			'url' => 'index.php?form=CliqueEdit&cliqueID='.$this->cliqueID.SID_ARG_2ND,
			'message' => WCF::getLanguage()->get('wcf.clique.edit.successful', array('$name' => $this->name))
		));
		WCF::getTPL()->display('redirect');
		exit;
	}

	public function assignVariables(){
		parent::assignVariables();

		// mark Clique as active
		require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
		PageMenu::setActiveMenuItem('wcf.header.menu.clique');

			//// Assign to template ////
		WCF::getTPL()->assign(array(
			'name' => $this->clique->name,
			'shortDescription' => $this->clique->shortDescription,
			'text' => $this->clique->description,
			'status' => $this->clique->status,
			'cliqueID' => $this->cliqueID,
			'categoriesArray' => $this->categoriesArray,
			'categorieID' => $this->clique->categorieID,
			'cliquePermissions' => new CliqueEditor($this->cliqueID)
		));
	}

	/**
	 * @see Page::show()
	 */

	public function show() {
		// check permission
		if (!WCF::getUser()->userID || (!CliqueEditor::getCliquePermission('canEditClique') && !WCF::getUser()->getPermission('mod.clique.general.canEditEveryClique'))) {
			throw new PermissionDeniedException();
		}

		parent::show();
	}

	/**
	 * Validates the uploaded files.
	 */
	public function validateUpload() {
		parent::validateUpload();
		if (!empty($this->photo['photoName'])) {
			$this->photoSql = ", image = '".$this->photo['photoName']."', width = ".$this->photo['photoWidth'].", height =".$this->photo['photoHight'];
		}
	}
}
?>