<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/notification/object/AbstractNotificationObjectType.class.php');
require_once(WCF_DIR.'lib/data/user/gallery/comment/UserGalleryPhotoSubscripeCommentNotificationObject.class.php');

/**
 * An implementation of NotificationObjectType.
 *
 * @author $foo
 * @license LGPL
 * @package de.foo.user.gallery.subscribe.comment
 */

class UserGalleryPhotoSubscripeCommentNotificationObjectType extends AbstractNotificationObjectType {

	/**
	 * @see NotificationObjectType::getObjectByID()
	 */
	public function getObjectByID($objectID) {
		// get object
		$comment = new UserGalleryPhotoSubscripeCommentNotificationObject($objectID);
			if (!$comment->commentID) return null;

			// return object
			return $comment;
	}

	/**
	 * @see NotificationObjectType::getObjectByObject()
	 */
	public function getObjectByObject($object) {
		// build object using its data array
		$comment = new UserGalleryPhotoSubscripeCommentNotificationObject(null, $object);
			if (!$comment->commentID) return null;

			// return object
			return $comment;
	}

	/**
	 * @see NotificationObjectType::getObjectsByIDArray()
	 */
	public function getObjectsByIDArray($objectIDArray) {
		$comments = array();
		$sql = "SELECT		*
		FROM 		wcf".WCF_N."_user_gallery_comment
		WHERE 		commentID IN (".implode(',', $objectID).")";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$comments[$row['entryID']] = new UserGalleryPhotoSubscripeCommentNotificationObject(null, $row);
		}
		
		// return objects
		return $comments;
	}

	/**
	 * @see NotificationObjectType::getPackageID()
	 */
	public function getPackageID() {
		return WCF::getPackageID('com.woltlab.wcf.user.gallery');
	}
}
?>