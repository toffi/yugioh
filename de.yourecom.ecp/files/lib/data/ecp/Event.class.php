<?php
// wcf imports
if (!defined('NO_IMPORTS')) {
	require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');
}

class Event extends DatabaseObject {
	protected $sqlJoins = '';
	protected $sqlSelects = '';
	protected $sqlGroupBy = '';

	public function __construct($eventID, $row = null, $eventName = null) {
		// set sql join to user_data table
		$this->sqlSelects .= 'event.*,'; 
		
		// execute sql statement
		$sqlCondition = '';
		if ($eventID !== null) {
			$sqlCondition = "event.eventID = ".$eventID;
		}
		else if ($eventName !== null) {
			$sqlCondition = "event.name = '".escapeString($eventName)."'";
		}
		
		if (!empty($sqlCondition)) {
			$sql = "SELECT 	".$this->sqlSelects."
					event.*
				FROM 	events eventName
					".$this->sqlJoins."
				WHERE 	".$sqlCondition.
					$this->sqlGroupBy;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		
		// handle result set
		parent::__construct($row);
	}
	
	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		if (!$this->eventID) $this->data['eventID'] = 0;
	}
    
    public static function readEvents() {
			// Get Events
		$sql = "SELECT	events.id, events.name, events.current_gameday
			FROM		events
			WHERE events.status != '10' && events.art != '3'
			ORDER BY events.name ASC";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$events[] = $row;
		}
        return $events;
    }
    
    public static function getHeaderOutput() {
        $headerMenuItems = array();
        $sql = "SELECT	events_button.eventID, events.current_gameday, events.name_abbreviation
        		FROM		events_button
        		LEFT JOIN   events
        		  ON (events.id=events_button.eventID)
        		WHERE events_button.1Button = '1' || events_button.2Button = '1' || events_button.3Button = '1' || events_button.4Button = '1' || events_button.5Button = '1'";
        $result = WCF::getDB()->sendQuery($sql);
        while ($row = WCF::getDB()->fetchArray($result)) {
            $headerMenuItems[] = $row;
        }
        
        WCF::getTPL()->assign(array(
            'headerMenuItems' => $headerMenuItems
        ));
    }
}
?>