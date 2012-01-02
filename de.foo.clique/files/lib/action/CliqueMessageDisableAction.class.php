<?php
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueMessageDisableAction extends AbstractSecureAction {
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		$user = new UserEditor(WCF::getUser()->userID);
		$user->updateOptions(array (
			'cliqueMessage' => TIME_NOW
		));
        
        $this->executed();
	}
}