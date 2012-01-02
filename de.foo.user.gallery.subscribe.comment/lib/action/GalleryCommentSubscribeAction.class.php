<?php
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');

/**
 * Insert the Gallery Subscriptions
 * @author	SpIkE2
 */

class GalleryCommentSubscribeAction extends AbstractSecureAction {

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
		$sql = "INSERT INTO wcf".WCF_N."_user_gallery_subscription (userID, photoID) VALUES ('".WCF::getUser()->userID."', '".$this->photoID."')";
		WCF::getDB()->sendQuery($sql);

		if (empty($_REQUEST['ajax'])) HeaderUtil::redirect('index.php?page=UserGalleryPhoto&photoID='.$this->photoID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>