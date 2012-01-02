<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractCliqueMessageForm.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueAddCommentForm extends AbstractCliqueMessageForm {
	// system
	public $templateName = 'cliqueAddComment';
	public $neededPermissions = 'user.clique.general.canSee';
	public $text = '';
	public $preview, $send;
	public $showAttachments = false;
	public $showPoll = false;
	public $showSignatureSetting = false;

	/**
	 * @see Page::assignVariables()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		if (isset($_POST['text'])) $this->text = $_POST['text'];
		if (isset($_POST['preview'])) $this->preview = (boolean) $_POST['preview'];
		if (isset($_POST['send'])) $this->send = (boolean) $_POST['send'];

	}

	/**
	 * @see Form::submit()
	 */
	public function submit() {
		// call submit event
		EventHandler::fireAction($this, 'submit');

		$this->readFormParameters();

		require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');
		MessageParser::getInstance()->setOutputType('text/html');
		$this->previewText = MessageParser::getInstance()->parse($this->text, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes, false);

		try {
			// preview
			if ($this->preview) {
				WCF::getTPL()->assign('previewText', $this->previewText);
			}
			// save message
			if ($this->send) {
				$this->validate();
				// no errors
				$this->save();
			}
		}
		catch (UserInputException $e) {
			$this->errorField = $e->getField();
			$this->errorType = $e->getType();
		}
	}

	/**
	 * @see Page::validate()
	 */
	public function validate() {
		parent::validate();
		if(!empty($this->text)) $this->text = StringUtil::trim($this->text);
			else throw new UserInputException('text','empty');
	}

	/**
	 * @see Form::save()
	 */
	public function save(){
		parent::save();

			// Eintragen
		$sql = "INSERT INTO
							wcf".WCF_N."_clique_comments(cliqueID, userID, message, time)
						VALUES
							('".$this->cliqueID."',
							".WCF::getUser()->userID.",
							'".escapeString($this->text)."',
							".TIME_NOW.")";
		WCF::getDB()->sendQuery($sql);

		$this->saved();

			// Redirect
        HeaderUtil::redirect('index.php?page=CliqueComments&cliqueID='.$this->cliqueID.SID_ARG_2ND);
		exit;
	}

	public function assignVariables(){
		parent::assignVariables();

		// mark Comments as active and get menu items
		require_once(WCF_DIR.'lib/data/clique/CliqueMenuItems.class.php');
		CliqueMenuItems::setActiveMenuItem('wcf.clique.comment');

			//// Assign to template ////
		WCF::getTPL()->assign(array(
			'text' => $this->text,
			'preview' => $this->preview
		));
	}

	public function show() {
		if (!CliqueEditor::getCliquePermission('canMakeComments') || !WCF::getUser()->getPermission('user.clique.comments.canMakeComment')) {
			throw new PermissionDeniedException();
		}

		parent::show();
	}

	/**
	 * Does nothing.
	 */
	protected function validateSubject() {}
}
?>