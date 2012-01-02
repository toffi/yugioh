<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/notification/object/NotificationObject.class.php');
require_once(WCF_DIR.'lib/data/user/gallery/comment/ViewableUserGalleryPhotoComment.class.php');

/**
 * An implementation of NotificationObject.
 *
 * @author $foo
 * @license LGPL
 * @package de.foo.user.gallery.subscribe.comment
 */

class UserGalleryPhotoSubscripeCommentNotificationObject extends ViewableUserGalleryPhotoComment implements NotificationObject {

	/**
	 * @see ViewableUserGalleryPhotoComment:__construct
	 */
	public function __construct($commentID, $row = null) {
		// construct from old data if possible
		if (is_object($row)) {
			$row = $row->data;
		}
		parent::__construct($commentID, $row);
	}
		
	/**
	 * @see NotificationObject::getObjectID()
	 */
	public function getObjectID() {
		return $this->commentID;
	}

	/**
	 * @see NotificationObject::getTitle()
	 */
	public function getTitle() {
		return $this->getExcerpt();
	}

	/**
	 * @see NotificationObject::getURL()
	 */
	public function getURL() {
		return 'index.php?page=UserGalleryPhoto&photoID='.$this->photoID.'&commentID='.$this->commentID.'#comment'.$this->commentID;
	}

	/**
	 * @see NotificationObject::getIcon()
	 */
	public function getIcon() {
		return 'gallery';
	}

	/**
	 * @see ViewableUserGalleryPhotoComment::getFormattedComment()
	 */
	public function getFormattedMessage($outputType = 'text/html') {
		require_once(WCF_DIR.'lib/data/message/bbcode/SimpleMessageParser.class.php');
		return SimpleMessageParser::getInstance()->parse($this->comment);
	}

}
?>