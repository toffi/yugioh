<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/notification/NotificationHandler.class.php');


/**
 * The Eventlistener for the subscripes of the user galerie.
 *
 * @author $foo
 * @license LGPL
 * @package de.foo.user.gallery.subscribe.comment
 */

class GallerySubscripeCommentListener implements EventListener {
	public function execute($eventObj, $className, $eventName) {
		switch ($className) {
			case 'UserGalleryPhotoCommentAddForm':
				if (!MODULE_USER_NOTIFICATION) return;
					// Get  Users && commentID
				$sql = "SELECT wcf".WCF_N."_user_gallery_subscription.userID, wcf".WCF_N."_user_gallery_comment.commentID
								FROM wcf".WCF_N."_user_gallery_subscription
								LEFT JOIN wcf".WCF_N."_user_gallery_comment
									ON (wcf".WCF_N."_user_gallery_comment.userID = ".WCF::getUser()->userID.")
								WHERE wcf".WCF_N."_user_gallery_subscription.photoID = '".$eventObj->photo->photoID."' && wcf".WCF_N."_user_gallery_comment.time = ".TIME_NOW;
				$result = WCF::getDB()->sendQuery($sql);
				while ($row = WCF::getDB()->fetchArray($result)) {
						// FireEvent
					if (WCF::getUser()->userID != $row['userID']) {
						$commentID = $row['commentID'];
						NotificationHandler::fireEvent('galleryComment', 'UserGalleryComment', $commentID, $row['userID']);
					}
				}
				break;

			case 'UserGalleryPhotoPage':
				if(!WCF::getUser()->getPermission ('user.profile.gallery.canSubscripeComments') || !MODULE_USER_NOTIFICATION)
					return;
					// Get Subscription Status
				$sql = "SELECT * FROM wcf".WCF_N."_user_gallery_subscription
								WHERE userID = '".WCF::getUser()->userID."' && photoID = '".$eventObj->photoID."'";
				$result = WCF::getDB()->sendQuery($sql);
				$isSubscription = WCF::getDB()->countRows($result);

					// Create subscripeList
				$this->subscripeList = array();
				$objectIDScope = array();
				$sql = "SELECT commentID
								FROM wcf".WCF_N."_user_gallery_comment
								WHERE photoID = '".$eventObj->photo->photoID."'";
				$result = WCF::getDB()->sendQuery($sql);
				while ($row = WCF::getDB()->fetchArray($result,Database::SQL_ASSOC)) {
					$objectIDScope[] = $row['commentID'];
					$this->subscripeList[] = $row;
				}

					// Remove seen notifications
				$user = new NotificationUser(null, WCF::getUser(), false);
				$objectTypeObject = NotificationHandler::getNotificationObjectTypeObject ('UserGalleryComment');
				if (isset($user->notificationFlags[$objectTypeObject->getPackageID()]) && $user->notificationFlags[$objectTypeObject->getPackageID()] > 0) {
					$count = NotificationEditor::markConfirmedByObjectVisit($user->userID, array('galleryComment'), 'UserGalleryComment', $objectIDScope);
					$user->removeOutstandingNotification($objectTypeObject->getPackageID(),$count );
				}

					// Show buttons
				WCF::getTPL()->assign(array(
					'photoID' => $eventObj->photoID,
					'isSubscription' => $isSubscription
				));
				WCF::getTPL()->append('additionalSmallButtons', WCF::getTPL()->fetch('gallerySubscripeComment'));
				break;

				// revoke events
			case 'UserGalleryPhotoCommentDeleteAction':
				NotificationHandler::revokeEvent(array('galleryComment'), 'UserGalleryComment', $eventObj->comment);
			break;
		}
	}
}
?>