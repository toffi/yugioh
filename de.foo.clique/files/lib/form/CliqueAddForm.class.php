<?php// wcf importsrequire_once(WCF_DIR.'lib/form/MessageForm.class.php');require_once(WCF_DIR.'lib/action/CliqueJoinAction.class.php');require_once(WCF_DIR.'lib/data/clique/CliqueEditor.class.php');/** * @author	$foo * @license	>Creative Commons Namensnennung 3.0 Deutschland License * @package	de.foo.clique */class CliqueAddForm extends MessageForm {	// system	public $templateName = 'cliqueAdd';	public $name = '';	public $shortDescription = '';	public $text = '';	public $status = 0;	public $upload = null;	public $photos = array();	public $photoSqlField = '';	public $photoSqlValue = '';	public $categorieID = 0;	public $pictureURL = 'http://';	public $categoriesArray = array();	public $showAttachments = false;	public $showPoll = false;	public $rightsMysql = '';	public $rightsMysqlValue = '';	public $groupRights = array(		'canEditClique' => CLIQUE_GENERAL_RIGHTS_CANEDITCLIQUE,		'canDeleteClique' => CLIQUE_GENERAL_RIGHTS_CANDELETECLIQUE,		'canEditRights' => CLIQUE_GENERAL_RIGHTS_CANEDITRIGHTS,		'canInviteUsers' => CLIQUE_GENERAL_RIGHTS_CANINVITEUSERS,		'canAttendInvites' => CLIQUE_GENERAL_RIGHTS_CANATTENDINVITES,		'canSeeComments' => CLIQUE_GENERAL_RIGHTS_CANSEECOMMENTS,		'canMakeComments' => CLIQUE_GENERAL_RIGHTS_CANMAKECOMMENTS,		'canEditComments' => CLIQUE_GENERAL_RIGHTS_CANEDITCOMMENTS,		'canDeleteComments' => CLIQUE_GENERAL_RIGHTS_CANDELETECOMMENTS,		'canActivateModules' => CLIQUE_GENERAL_RIGHTS_CANACTIVATEMODULES	);	/**	 * @see Page::assignVariables()	 */	public function readFormParameters() {		parent::readFormParameters();		if (isset($_POST['name'])) $this->name = StringUtil::trim($_POST['name']);		if (isset($_POST['status'])) $this->status = $_POST['status'];		if (isset($_POST['shortDescription'])) $this->shortDescription = StringUtil::trim($_POST['shortDescription']);		if (isset($_POST['text'])) $this->text = StringUtil::trim($_POST['text']);		if (isset($_POST['categorie'])) $this->categorieID = $_POST['categorie'];		if (isset($_FILES['upload'])) $this->upload = $_FILES['upload'];		if (isset($_POST['pictureURL'])) $this->pictureURL = StringUtil::trim($_POST['pictureURL']);	}	/**	 * @see Page::validate()	 */	public function validate() {		parent::validate();		if(!empty($this->name)) $this->name = StringUtil::trim($this->name);			else throw new UserInputException('name','empty');		$categorieSecure = 0;		foreach ($this->categoriesArray as $categorie) {			if($this->categorieID == $categorie['categoryID']) {				$this->categorieID = intval($this->categorieID);				$categorieSecure = 1;				break;			}		}		if($categorieSecure != 1 && !empty($this->categoriesArray))			throw new UserInputException('categories','empty');		if(isset($this->statusArray[$this->status])) $this->status = intval($this->status);			else throw new UserInputException('status','empty');		$this->validateUpload();	}	/**	 * @see Form::save()	 */	public function save(){			// Eintragen		$sql = "INSERT INTO							wcf".WCF_N."_clique(name, time, shortDescription, description, status, raiserID, categorieID".$this->photoSqlField.")						VALUES							('".escapeString($this->name)."',							".TIME_NOW.",							'".escapeString($this->shortDescription)."',							'".escapeString($this->text)."',							'".$this->status."',							".WCF::getUser()->userID.",							".$this->categorieID."							".$this->photoSqlValue.")";		WCF::getDB()->sendQuery($sql);		$this->cliqueID = WCF::getDB()->getInsertID($sql);		$sql = "INSERT INTO							wcf".WCF_N."_clique_group_rights(cliqueID".$this->rightsMysql.")						VALUES							(".$this->cliqueID.$this->rightsMysqlValue.")";		WCF::getDB()->sendQuery($sql);		parent::save();				CliqueJoinAction::addUser(WCF::getUser()->userID, $this->cliqueID, 5);		WCF::getSession()->register('rulesAgree', 0);				$this->saved();				// reset cache		WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.CacheBuilderCliqueBoxes.php');					// Redirect		HeaderUtil::redirect('index.php?page=CliqueDetail&cliqueID='.$this->cliqueID.SID_ARG_2ND);		exit;	}	/**	 * @see Page::readData()	 */	public function readParameters() {		if(isset($_POST['rulesAgree'])) {			$_POST = array();			WCF::getSession()->register('rulesAgree', 1);		}		parent::readParameters();		WCF::getCache()->addResource('CacheBuilderCliqueCategories', WCF_DIR.'cache/cache.CacheBuilderCliqueCategories.php', WCF_DIR.'lib/system/cache/CacheBuilderCliqueCategories.class.php');		$this->categoriesArray = WCF::getCache()->get('CacheBuilderCliqueCategories');		$this->statusArray = array(			0 => WCF::getLanguage()->get('wcf.clique.general.status.0'),			1 => WCF::getLanguage()->get('wcf.clique.general.status.1'),		);		//Build sql for default permission		foreach ($this->groupRights as $key => $groupRight) {			$defaultGroupRightsArray = explode(chr(10),$groupRight);			if(empty($groupRight)) continue;			foreach ($defaultGroupRightsArray as $groupID) {				$this->rightsMysql .= ',';				$this->rightsMysql .= $key.$groupID;				$this->rightsMysqlValue .= ',';				$this->rightsMysqlValue .= 1;			}		}	}	/**	 * @see Page::assignVariables()	 */	public function assignVariables(){		parent::assignVariables();		// mark Clique as active		require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');		PageMenu::setActiveMenuItem('wcf.header.menu.clique');			//// Assign to template ////		WCF::getTPL()->assign(array(			'name' => $this->name,			'text' => $this->text,			'shortDescription' => $this->shortDescription,			'status' => $this->status,			'categoriesArray' => $this->categoriesArray,			'categorieID' => $this->categorieID,			'pictureURL' => $this->pictureURL,			'statusArray' => $this->statusArray		));	}	/**	 * @see Page::show()	 */	public function show() {		// check permission		if (!WCF::getUser()->userID || !WCF::getUser()->getPermission('user.clique.general.canRaise')) {			throw new PermissionDeniedException();		}		// Regelbestätigung		if(!WCF::getSession()->getVar('rulesAgree')) {			$this->templateName = 'cliqueRulesAgree';		}		parent::show();	}	/**	 * Validates the uploaded files.	 */	public function validateUpload() {		if ($this->pictureURL != 'http://') {			if (StringUtil::indexOf($this->pictureURL, 'http://') !== 0) {				throw new UserInputException('pictureURL', 'downloadFailed');			}			try {				$tmpName = FileUtil::downloadFileFromHttp($this->pictureURL, 'cliquenPicture');			}			catch (SystemException $e) {				throw new UserInputException('pictureURL', 'downloadFailed');			}			$this->photo = CliqueEditor::create(WCF::getUser()->userID, $tmpName, $this->upload['name']);			$this->photoSqlField = ", image, width, height";			$this->photoSqlValue = ",'".$this->photo['photoName']."',".$this->photo['photoWidth'].",".$this->photo['photoHight'];		}		elseif (isset($this->upload['name']) && count($this->upload['name'])) {			$errors = array();			if (!empty($this->upload['name'])) {				try {					// save photo					$this->photo = CliqueEditor::create(WCF::getUser()->userID, $this->upload['tmp_name'], $this->upload['name']);					$this->photoSqlField = ", image, width, height";					$this->photoSqlValue = ",'".$this->photo['photoName']."',".$this->photo['photoWidth'].",".$this->photo['photoHight'];				}				catch (UserInputException $e) {					$errors[] = array('errorType' => $e->getType(), 'filename' => $this->upload['name']);					$this->photoSql = '';					$this->photoSqlValue = '';				}			}			// show error message			if (count($errors)) {				throw new UserInputException('upload', $errors);			}			// show success message			WCF::getTPL()->assign('photos', $this->photos);			if (count($this->photos) > 0) WCF::getTPL()->assign('success', true);		}	}	/**	 * Does nothing.	 */	protected function validateSubject() {}}?>