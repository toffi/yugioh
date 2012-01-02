<?php
require_once(WCF_DIR.'lib/form/AbstractCliqueForm.class.php');

/**
 * Shows the thread quick reply form.
 *
 * @author 	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	WoltLab Burning Board License <http://www.woltlab.com/products/burning_board/license.php>
 * @package	com.woltlab.wbb
 * @subpackage	form
 * @category 	Burning Board
 */
class CliqueApplicationForm extends AbstractCliqueForm {
	public $cliqueID = 0;

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['applicationMessage'])) $this->applicationMessage = StringUtil::trim($_POST['applicationMessage']);
	}

	/**
	 * @see Page::validate()
	 */
	public function validate() {
		parent::validate();
        
        if(WCF::getUser()->userID == 0) {
            throw new NamedUserException(WCF::getLanguage()->get('wcf.clique.detail.application.error', array('$message' => $this->applicationMessage)));
        }
    }
  
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		$sql = "INSERT INTO
							wcf".WCF_N."_clique_application(cliqueID, userID, message, time)
						VALUES
							(".intval($this->cliqueID).",
							".WCF::getUser()->userID.",
							'".escapeString($this->applicationMessage)."',
							".TIME_NOW.")";
		WCF::getDB()->sendQuery($sql);

		$this->saved();

		HeaderUtil::redirect('index.php?page=CliqueDetail&cliqueID='.$this->cliqueID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}

}
?>