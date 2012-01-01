<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');

class ECPBeendetlPage extends AbstractPage {
	public $templateName = 'ECPBeendetl';

	public function assignVariables() {

		parent::assignVariables();
		include(WCF_DIR.'lib/page/ECPHeadPage.class.php');

				//// Art und Name ////
		$sql = "SELECT	events.id, events.name, events.art, events.contacts, events.current_gameday, events_arten.arten_name
				FROM		events
				LEFT JOIN   events_arten
				  ON (events_arten.id=events.art)
				WHERE events.status='10'
				ORDER BY events.id DESC";
			$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result))
			{
				$Gamer1New = new User($row['contacts']);
				$row['contacts_name'] = $Gamer1New->username;
				$old_events[] = $row;
			}
		if(!isset($old_events)) $old_events = '';

        WCF::getTPL()->assign(array(
            'old_events' => $old_events
		));
	}
	/**
	 * @see Page::show()
	 */

	public function show() {
		// set active header menu item
		require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');
		HeaderMenu::setActiveMenuItem('wcf.header.menu.portal');
		
		parent::show();
	}
}
?>