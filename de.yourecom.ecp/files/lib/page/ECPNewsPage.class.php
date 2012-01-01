<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WBB_DIR.'lib/data/post/ViewablePost.class.php');
require_once(WBB_DIR.'lib/data/thread/ViewableThread.class.php');
require_once(WCF_DIR.'lib/page/ECPHeadPage.class.php');

class ECPNewsPage extends AbstractPage {
	public $templateName = 'ECPNews';
	public $boardIDsLeft = NEWSBOXECP_LEFT_BOARDID;
	public $boardIDsMiddle = NEWSBOXECP_MIDDLE_BOARDID;
	public $boardIDsRight = NEWSBOXECP_RIGHT_BOARDID;
	public $limitLeft = NEWSBOXECP_LEFT_LENGTH;
	public $limitMiddle = NEWSBOXECP_MIDDLE_LENGTH;
	public $limitRight = NEWSBOXECP_RIGHT_LENGTH;

		/**
			* @see Page::readData()
		*/
	public function readData() {
		parent::readData();

			// See Infoslot left
		$sql = "SELECT		wbb".WBB_N."_thread.replies, wbb".WBB_N."_thread.threadID, wbb".WBB_N."_thread.topic, wbb".WBB_N."_thread.time, wbb".WBB_N."_thread.userID,
						wbb".WBB_N."_thread.username, wbb".WBB_N."_thread.firstPostID, wbb".WBB_N."_post.message
					FROM		wbb".WBB_N."_thread
					LEFT JOIN   wbb".WBB_N."_post
						ON (wbb".WBB_N."_post.postID=wbb".WBB_N."_thread.firstPostID)
					WHERE wbb".WBB_N."_thread.boardID = '" . $this->boardIDsLeft . "'
					ORDER BY wbb".WBB_N."_thread.threadID DESC";
			$messageLeft[] = WCF::getDB()->getFirstRow($sql);

			if(isset($messageLeft)) {
				$messageLeft['message'] = MessageParser::getInstance()->parse($messageLeft[0]['message'], 1, 0, 1, false);
				$messageLeft['message'] = wordwrap($messageLeft[0]['message'], $this->limitLeft, "#");
				$data = explode ("#", $messageLeft[0]['message']);
				$messageLeft['message'] = "$data[0] ...";
				$this->newsListLeft[] = $messageLeft[0];
			}
			else $this->newsListLeft[] = '---';

			// See Infoslot middle
			$sql = "SELECT		wbb".WBB_N."_thread.replies, wbb".WBB_N."_thread.threadID, wbb".WBB_N."_thread.topic, wbb".WBB_N."_thread.time, wbb".WBB_N."_thread.userID,
					wbb".WBB_N."_thread.username, wbb".WBB_N."_thread.firstPostID, wbb".WBB_N."_post.message
				FROM		wbb".WBB_N."_thread
						LEFT JOIN	 wbb".WBB_N."_post
							 ON (wbb".WBB_N."_post.postID=wbb".WBB_N."_thread.firstPostID)
						WHERE wbb".WBB_N."_thread.boardID = '$this->boardIDsMiddle'
				ORDER BY wbb".WBB_N."_thread.threadID DESC";
			$messageMiddle[] = WCF::getDB()->getFirstRow($sql);
			if(isset($messageLeft)) {
				$messageLeft['message'] = MessageParser::getInstance()->parse($messageLeft[0]['message'], 1, 0, 1, false);
				$messageLeft['message'] = wordwrap($messageLeft[0]['message'], $this->limitMiddle, "#");
				$data = explode ("#", $messageLeft[0]['message']);
				$messageLeft['message'] = "$data[0] ...";
				$this->newsListMiddle[] = $messageLeft[0];
			}
			else $this->newsListMiddle[] = '---';

			// See Infoslot right
			$sql = "SELECT		wbb".WBB_N."_thread.replies, wbb".WBB_N."_thread.threadID, wbb".WBB_N."_thread.topic, wbb".WBB_N."_thread.time, wbb".WBB_N."_thread.userID,
					wbb".WBB_N."_thread.username, wbb".WBB_N."_thread.firstPostID, wbb".WBB_N."_post.message
				FROM		wbb".WBB_N."_thread
						LEFT JOIN	 wbb".WBB_N."_post
							 ON (wbb".WBB_N."_post.postID=wbb".WBB_N."_thread.firstPostID)
						WHERE wbb".WBB_N."_thread.boardID = '$this->boardIDsRight'
				ORDER BY wbb".WBB_N."_thread.threadID DESC";
			$messageRight[] = WCF::getDB()->getFirstRow($sql);
			if(isset($messageRight)) {
				$messageRight['message'] = MessageParser::getInstance()->parse($messageRight[0]['message'], 1, 0, 1, false);
				$messageRight['message'] = wordwrap($messageRight[0]['message'], $this->limitRight, "#");
				$data = explode ("#", $messageRight[0]['message']);
				$messageRight['message'] = "$data[0] ...";
				$this->newsListRight[] = $messageRight[0];
			}
			else $this->newsListRight[] = '---';
		}

		/**
		 * @see Page::assignVariables()
		 */
		public function assignVariables() {
			parent::assignVariables();

		// mark ECP as active
		require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
		PageMenu::setActiveMenuItem('wcf.header.menu.ecp');

			WCF::getTPL()->assign(array(
				'itemLeft' => $this->newsListLeft,
				'itemMiddle' => $this->newsListMiddle,
				'itemRight' => $this->newsListRight
			));
		}

		/**
		 * @see Page::show()
		 */
		public function show() {
				parent::show();
		}
}
?>