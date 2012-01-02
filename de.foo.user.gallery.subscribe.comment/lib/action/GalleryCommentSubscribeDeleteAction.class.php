<?php
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');

/**
 * Delete the Gallery Subscriptions
 * @author	SpIkE2
 */
class GalleryCommentSubscribeDeleteAction extends AbstractSecureAction {

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
	  parent::readParameters();
		if(isset($_GET['photoID'])) $this->photoID = intval($_GET['photoID']);
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		$delete = "DELETE FROM wcf".WCF_N."_user_gallery_subscription WHERE userID='".WCF::getUser()->userID."' && photoID = '".$this->photoID."'";
		WCF::getDB()->sendQuery($delete);

		if (empty($_REQUEST['ajax'])) HeaderUtil::redirect('index.php?page=UserGalleryPhoto&photoID='.$this->photoID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>