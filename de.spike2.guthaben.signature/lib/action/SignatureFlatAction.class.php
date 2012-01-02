<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');
require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');

/**
 * Buy a signature flatrate
 *
 * @author $foo
 * @license LGPL
 * @package de.spike2.guthaben.signature
 */

class SignatureFlatAction extends AbstractAction {


	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (!GUTHABEN_ENABLE_GLOBAL || WCF::getUser()->userID == 0) {
			return;
		}
		if(WCF::getUser()->signatureFlat == 1) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.log.signatureflat.alreadyexist'));
		}

		if (GUTHABEN_PAY_PER_SIGNATUREFLAT != 0 && !Guthaben::check(GUTHABEN_PAY_PER_SIGNATUREFLAT))
			throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.log.signatureflat.nomoney', array('$currency' => WCF::getLanguage()->get('wcf.guthaben.currency'))));

	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		if (GUTHABEN_PAY_PER_SIGNATUREFLAT != 0) {
			Guthaben::sub(GUTHABEN_PAY_PER_SIGNATUREFLAT, 'wcf.guthaben.log.signatureflat');
			$user = new UserEditor(WCF::getUser()->userID);
			$user->updateOptions(array (
				'signatureFlat' => 1
			));
		}

		$this->executed();

		WCF::getTPL()->assign(array(
			'url' => "index.php?page=guthabenMain&action=shopPage&s=".SID_ARG_2ND,
			'message' => WCF::getLanguage()->get('wcf.guthaben.log.signatureflat')
		));
		WCF::getTPL()->display('redirect');
		exit;
	}
}
?>