<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/data/user/group/GroupEditor.class.php');

class GuthabenPremiumListener implements EventListener {
	public $groupYears = '0';
    public $groupDescription = '';
	public $group = NULL;
    public $groupYearsArray = array();

	public function execute($eventObj, $className, $eventName) {

		if($className == 'GroupEditForm') {
			$this->group = new GroupEditor($eventObj->group->groupID);
		}

		if($this->group->groupType < 4) {
			return;
		}
		if ($eventName == 'readFormParameters') {
			if (isset($_POST['groupYears'])) {
                foreach($_POST['groupYears'] as $key => $postYear) {
                    $postYear = intval($postYear);                    
                    if(!empty($postYear)) $this->groupYearsArray[$key] = $postYear;                    
                }
            }
            if(!empty($this->groupYearsArray)) $this->groupYears = serialize($this->groupYearsArray);                                                
            if (isset($_POST['groupDescription'])) $this->groupDescription = $_POST['groupDescription'];
		}
		else if ($eventName == 'save') {
			$eventObj->additionalFields['groupYears'] = $this->groupYears;
            $eventObj->additionalFields['groupDescription'] = $this->groupDescription;
			if (!($eventObj instanceof GroupEditForm)) {
				$this->groupYears = 0;
                $this->groupDescription = '';
			}
		}
		else if ($eventName == 'assignVariables') {
            if(!empty($this->groupYears)) $this->groupYears = unserialize($this->groupYears);
			if (!count($_POST) && $eventObj instanceof GroupEditForm) {
                if($eventObj->group->groupYears != '' && $eventObj->group->groupYears != 0) $this->groupYears = unserialize($eventObj->group->groupYears);
                $this->groupDescription = $eventObj->group->groupDescription;
			}
			WCF::getTPL()->assign(array(
				'groupYears' => $this->groupYears,
                'groupDescription' => $this->groupDescription
			));
			WCF::getTPL()->append('additionalFieldSets', WCF::getTPL()->fetch('guthabenAcpPremiumGroup'));
		}
	}
}
?>