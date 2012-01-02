<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');

/**
 * Buy a avartar flatrate
 *
 * @author $foo
 * @license LGPL
 * @package de.spike2.avartar
 */

class AvatarFlatAction extends AbstractAction {

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (!GUTHABEN_ENABLE_GLOBAL || WCF::getUser()->userID == 0) return;

		if(WCF::getUser()->avatarFlat == 1) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.log.avatarflat.alreadyexist'));
		}

		if (GUTHABEN_PAY_PER_AVATARFLAT < 0 && !Guthaben::check(GUTHABEN_PAY_PER_AVATARFLAT)) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.log.avatarflat.nomoney', array('$currency' => WCF::getLanguage()->get('wcf.guthaben.currency'))));
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		Guthaben::sub(GUTHABEN_PAY_PER_AVATARFLAT, 'wcf.guthaben.log.avatarflat');
		$user = new UserEditor(WCF::getUser()->userID);
		$user->updateOptions(array (
			'avatarFlat' => 1
		));

		$this->executed();

		WCF::getTPL()->assign(array(
			'url' => "index.php?page=GuthabenMain&action=shopPage&s=".SID_ARG_2ND,
			'message' => WCF::getLanguage()->get('wcf.guthaben.log.avatarflat')
		));
		WCF::getTPL()->display('redirect');
		exit;
	}
}
?>