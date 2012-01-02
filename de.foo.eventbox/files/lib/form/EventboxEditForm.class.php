<?php
require_once (WCF_DIR.'lib/form/MessageForm.class.php');
require_once (WCF_DIR.'lib/acp/option/Options.class.php');

/**
 * @author	SpIkE2
 */

class EventboxEditForm extends MessageForm {
	public $templateName = 'eventboxEdit';
	public $eventEnable = 0;
	public $eventMessage = '';
	public $eventArt = 1;

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		if(isset($_POST['event_enable'])) {
			$this->eventEnable = (boolean) $_POST['event_enable'];
		}

		if(isset($_POST['event_art'])) {
			$this->eventArt = intval($_POST['event_art']);
		}
		if(isset($_POST['event_message'])) {
			$this->eventMessage = StringUtil::trim($_POST['event_message']);
		}
	}

	/**
	 * @see Form::submit()
	 */
	public function submit() {
		// call submit event
		EventHandler::fireAction($this, 'submit');

		$this->readFormParameters();

		try {
			// save message
			$this->save();
		}
		catch (UserInputException $e) {
			$this->errorField = $e->getField();
			$this->errorType = $e->getType();
		}
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		$sql = "UPDATE	wcf".WCF_N."_option
						SET	optionValue = ".$this->eventEnable."
						WHERE 	optionName = 'event_enable'";
		WCF::getDB()->sendQuery($sql);

		$sql = "UPDATE	wcf".WCF_N."_option
						SET	optionValue = ".$this->eventArt."
						WHERE 	optionName = 'event_art'";
		WCF::getDB()->sendQuery($sql);

		$sql = "UPDATE	wcf".WCF_N."_option
						SET	optionValue = '".escapeString($this->eventMessage)."'
						WHERE 	optionName = 'event_message'";
		WCF::getDB()->sendQuery($sql);

			// delete relevant options.inc.php's
		Options::resetFile();

		$this->saved();

		HeaderUtil::redirect('index.php?form=EventboxEdit'.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'event_message_show' => EVENT_MESSAGE,
			'event_art_show' => EVENT_ART,
			'event_enable_show' => EVENT_ENABLE
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		if (!WCF :: getUser()->userID || !WCF :: getUser()->getPermission('mod.board.canEditEvent')) {
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