<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

class GuthabenPremiumRedirectListener implements EventListener {
	public function execute($eventObj, $className, $eventName) {
        if(isset($_GET['action']) && $_GET['action'] == 'premiumPage') {
			// Redirect
    		HeaderUtil::redirect('index.php?form=GuthabenPremium'.SID_ARG_2ND);
    		exit;
        }
	}
}
?>