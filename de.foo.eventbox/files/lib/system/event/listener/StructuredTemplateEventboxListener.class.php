<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');

class StructuredTemplateEventboxListener implements EventListener {
	public $templateName = 'radio';

	public function execute($eventObj, $className, $eventName){

			// Permission to see the Radiobox
		if(WCF::getUser()->getPermission('user.board.canSeeEvent') && !EVENT_ENABLE) {
			$radiotext = MessageParser::getInstance()->parse(EVENT_MESSAGE, 1, 0, 1, FALSE);

			WCF::getTPL()->assign('global_radio_text', $radiotext);
			WCF::getTPL()->assign('event_art_show', EVENT_ART);
			WCF::getTPL()->assign('permission_global_announce_text', 1);

			if (!empty($radiotext)) {
				WCF::getTPL()->append('userMessages', WCF::getTPL()->fetch('radio'));
			}
		}

			// Permission to make/edit the message
		if(WCF::getUser()->getPermission('mod.ecp.canSeeECP')) {
			WCF::getTPL()->assign(array(
				'additionalUserMenuItems' => WCF::getTPL()->fetch('userMenuEventbox')
			));
		}
	}
}
?>