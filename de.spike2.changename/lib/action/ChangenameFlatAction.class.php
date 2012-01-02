<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');

/**
 * Buy a username flatrate
 *
 * @author $foo
 * @license LGPL
 * @package de.spike2.changename
 */

class ChangenameFlatAction extends AbstractAction {


	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (!GUTHABEN_ENABLE_GLOBAL || WCF::getUser()->userID == 0) {
			return;
		}

		if(WCF::getUser()->changenameFlat == 1) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.log.changenameflat.alreadyexist'));
		}

		if (GUTHABEN_PAY_PER_CHANGENAMEFLAT != 0 && !Guthaben::check(GUTHABEN_PAY_PER_CHANGENAMEFLAT)) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.log.changenameflat.nomoney', array('$currency' => WCF::getLanguage()->get('wcf.guthaben.currency'))));
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		if (GUTHABEN_PAY_PER_CHANGENAMEFLAT != 0) {
			Guthaben::sub(GUTHABEN_PAY_PER_CHANGENAMEFLAT, 'wcf.guthaben.log.changenameflat');
			$user = new UserEditor(WCF::getUser()->userID);
			$user->updateOptions(array (
				'changenameFlat' => 1
			));
		}

		$this->executed();

		WCF::getTPL()->assign(array(
			'url' => "index.php?page=guthabenMain&action=shopPage&s=".SID_ARG_2ND,
			'message' => WCF::getLanguage()->get('wcf.guthaben.log.changenameflat')
		));
		WCF::getTPL()->display('redirect');
		exit;
	}
}
?>