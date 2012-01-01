<?php
// wcf imports
require_once(WCF_DIR.'lib/form/AbstractForm.class.php');
require_once(WCF_DIR.'lib/page/ECPHeadPage.class.php');

/**
 * @author	SpIkE2
 */

class EcpAdminForm extends AbstractForm {
	public $templateName = 'ECPAdmin';
	public $events = array();
    public $button = array();
    public $button0 = 0;
    public $button1 = 0;
    public $button2 = 0;
    public $button3 = 0;
    public $button4 = 0;

	/**
	 * @see Page::readData()
	 */
	public function readParameters() {
		parent::readParameters();
        $this->events = $this->getEvents();
        $this->getButtons();
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		if (isset($_POST['button0'])) $this->button0 = intval($_POST['button0']);
        if (isset($_POST['button1'])) $this->button1 = intval($_POST['button1']);
        if (isset($_POST['button2'])) $this->button2 = intval($_POST['button2']);
        if (isset($_POST['button3'])) $this->button3 = intval($_POST['button3']);
        if (isset($_POST['button4'])) $this->button4 = intval($_POST['button4']);
	}

	/**
	 * @see Form::save()
	 */
	public function save(){
		parent::save();

            // Reset all
		$sql = "UPDATE	events_button
   				SET	1Button = '',
					2Button = '',
					3Button = '',
					4Button = '',
					5Button = ''";
		WCF::getDB()->sendQuery($sql);

            // Set first button
		$sql = "UPDATE	events_button
		      		SET	1Button = '1'
					WHERE eventID='$this->button0'";
		WCF::getDB()->sendQuery($sql);

            // Set second button
		$sql = "UPDATE	events_button
		      		SET	2Button = '1'
					WHERE eventID='$this->button1'";
		WCF::getDB()->sendQuery($sql);

            // Set third button
		$sql = "UPDATE	events_button
		      		SET	3Button = '1'
					WHERE eventID='$this->button2'";
		WCF::getDB()->sendQuery($sql);

            // Set fourth button
		$sql = "UPDATE	events_button
		      		SET	4Button = '1'
					WHERE eventID='$this->button3'";
		WCF::getDB()->sendQuery($sql);

            // Set fifth button
		$sql = "UPDATE	events_button
		      		SET	5Button = '1'
					WHERE eventID='$this->button4'";
		WCF::getDB()->sendQuery($sql);

		$this->saved();

			// Redirect
		HeaderUtil::redirect('index.php?form=EcpAdmin'.SID_ARG_2ND);
		exit;
	}
 
 	/**
	 * @see Form::assignVariables()
	 */
	public function assignVariables() {	   
    	parent::assignVariables();
		WCF::getTPL()->assign(array(
            'events' => $this->events,
            'button' => $this->button
        ));
	}
 
    public function getEvents() {
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

    public function getButtons() {
		$sql = "SELECT * 
                FROM events_button 
                WHERE 1Button='1' || 2Button='1' || 3Button='1' || 4Button='1' || 5Button='1'";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
            if($row['1Button'] == 1) $this->button[0] = $row;
            if($row['2Button'] == 1) $this->button[1] = $row;
            if($row['3Button'] == 1) $this->button[2] = $row;
            if($row['4Button'] == 1) $this->button[3] = $row;
            if($row['5Button'] == 1) $this->button[4] = $row;
		}
    }
}
?>