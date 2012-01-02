<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');

/**
 * Buy a usertitle flatrate
 *
 * @author $foo
 * @license LGPL
 * @package de.spike2.guthaben.benutzertitle
 */

class UserTitleFlatAction extends AbstractAction {


	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (!GUTHABEN_ENABLE_GLOBAL || WCF::getUser()->userID == 0 ) {
			return;
		}
		if(WCF::getUser()->useritleFlat == 1) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.log.userTitleflat.alreadyexist'));
		}

		if (GUTHABEN_PAY_PER_USERTITLEFLAT != 0 && !Guthaben::check(GUTHABEN_PAY_PER_USERTITLEFLAT)) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.log.userTitleflat.nomoney'));
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		if (GUTHABEN_PAY_PER_USERTITLEFLAT != 0) {
			Guthaben::sub(GUTHABEN_PAY_PER_USERTITLEFLAT, 'wcf.guthaben.log.userTitleflat');
			$user = new UserEditor(WCF::getUser()->userID);
			$user->updateOptions(array (
				'useritleFlat' => 1
			));
		}

		$this->executed();

		WCF::getTPL()->assign(array(
			'url' => "index.php?page=guthabenMain&action=shopPage&s=".SID_ARG_2ND,
			'message' => WCF::getLanguage()->get('wcf.guthaben.log.userTitleflat')
		));
		WCF::getTPL()->display('redirect');
		exit;
	}
}
?>