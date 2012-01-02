<?php
require_once(WCF_DIR.'lib/data/message/Message.class.php');
require_once(WCF_DIR.'lib/data/message/util/SearchResultTextParser.class.php');

/**
 * This class extends the UserMemo class by functions for a search result output.
 *
 * @author	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	WoltLab Burning Board License <http://www.woltlab.com/products/burning_board/license.php>
 * @package	com.woltlab.wcf.form.user.memo
 * @subpackage	data.user.memo
 * @category 	Community Framework (commercial)
 */
class CliqueSearchResult extends Message {
	/**
	 * Creates a new clique object.
	 *
	 * @param	array		$row
	 */
	public function __construct($row = null) {
		parent::__construct($row);
	}

	/**
	 * Returns a formatted message.
	 * 
	 * @return 	string
	 */
	public function getFormattedMessage($text) {
		require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');
		MessageParser::getInstance()->setOutputType('text/html');
		$message = MessageParser::getInstance()->parse($text, 1, 0, 1, false);
		return SearchResultTextParser::parse($message);
	}
}
?>