<?php
// wcf imports
require_once(WCF_DIR.'lib/form/CliqueAddCommentForm.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueEditCommentForm extends CliqueAddCommentForm {
	// system
	public $templateName = 'cliqueEditComment';
	public $neededPermissions = 'user.clique.comments.canEditOwnComments';

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if (isset($_REQUEST['commentID'])) $this->commentID = intval($_REQUEST['commentID']);
	}

	/**
	 * @see Form::save()
	 */
	public function save(){
			// Update
		$sql = "UPDATE wcf".WCF_N."_clique_comments
						SET message = '".escapeString($this->text)."'
						WHERE cliqueID = ".$this->cliqueID."
							AND commentID = ".$this->commentID;
		WCF::getDB()->sendQuery($sql);

		$this->saved();

			// Redirect
        HeaderUtil::redirect('index.php?page=CliqueComments&cliqueID='.$this->cliqueID.SID_ARG_2ND);
		exit;
	}

	/**
	 * @see Form::readData()
	 */
	public function readData(){
		parent::readData();
		$sql = "SELECT message, commentID, userID
						FROM wcf".WCF_N."_clique_comments
						WHERE commentID = ".$this->commentID;
		$this->comment = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);
	}

	/**
	 * @see Form::assignVariables()
	 */
	public function assignVariables(){
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'comment' => $this->comment
		));
	}

	/**
	 * @see Form::show()
	 */
	public function show() { $this->readData();
		if (!CliqueEditor::getCliquePermission('canEditComments') || (!WCF::getUser()->getPermission('user.clique.comments.canEditOwnComments') && $this->comment['userID'] == WCF::getUser()->userID)) {
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