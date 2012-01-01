<?php
require_once (WCF_DIR.'lib/form/AbstractForm.class.php');
require_once(WCF_DIR.'lib/page/util/InlineCalendar.class.php');
require_once(WCF_DIR.'lib/page/ECPHeadPage.class.php');
require_once(WBB_DIR.'lib/data/boxes/PortalBox.class.php');

/**
 * @author	SpIkE2
 */

class EcpInsertTourneyForm extends AbstractForm {
	public $templateName = 'EcpInsertTourney';
	public $neededPermissions = 'mod.ecp.canCreateTourney';
	public $eventDay = '';
	public $eventMonth = '';
	public $eventYear = '';
	public $eventHour = '';
	public $eventMinute = 0;	
	public $name = '';
	public $lobby = '';
	public $art = '';
	public $calender = '';
	public $participiants = '';
	public $description = '';
	public $officialEvent = 0;

		// Calender
	public $subject = '';
	public $text = '';
	public $enableSmilies = TRUE;
	public $enableHtml = TRUE;
	public $enableBBCodes = TRUE;
	public $showSignature = FALSE;
	public $parseURL = TRUE;
	public $calenderID = '';
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
			//Calender

		$this->participiantsArray = array (
			'4' => "4 ".WCF::getLanguage()->get('wcf.ecp.tourney.insert.tourney.gamer'),
			'8' => "8 ".WCF::getLanguage()->get('wcf.ecp.tourney.insert.tourney.gamer'),
			'16' => "16 ".WCF::getLanguage()->get('wcf.ecp.tourney.insert.tourney.gamer'),
			'32' => "32 ".WCF::getLanguage()->get('wcf.ecp.tourney.insert.tourney.gamer'),
			'64' => "64 ".WCF::getLanguage()->get('wcf.ecp.tourney.insert.tourney.gamer')
		);

		$this->lobbyArray = array('1' => WCF::getLanguage()->get('wcf.ecp.tourney.lobby.germany'),
			'2' => WCF::getLanguage()->get('wcf.ecp.tourney.lobby.english'),
			'3' => WCF::getLanguage()->get('wcf.ecp.tourney.lobby.france'),
			'4' => WCF::getLanguage()->get('wcf.ecp.tourney.lobby.japanese'),
			'5' => WCF::getLanguage()->get('wcf.ecp.tourney.lobby.spanish'),
			'6' => WCF::getLanguage()->get('wcf.ecp.tourney.lobby.korea'),
			'7' => WCF::getLanguage()->get('wcf.ecp.tourney.lobby.zh'),
			'8' => WCF::getLanguage()->get('wcf.ecp.tourney.lobby.world')
		);

		$this->artArray = array (
			'3' => WCF::getLanguage()->get('wcf.ecp.tourney.insert.tourney.kosystem'),
		);
// 			'4' => WCF::getLanguage()->get('wcf.ecp.tourney.insert.tourney.vorrunde')
		$this->calenderArray = array (
			'1' => WCF::getLanguage()->get('wcf.ecp.tourney.insert.tourney.calender.options')
		);

		$this->officialEventArray = array (
			'0' => '-',
			'1' => WCF::getLanguage()->get('wcf.ecp.tourney.insert.tourney.MNW'),
			'2' => WCF::getLanguage()->get('wcf.ecp.tourney.insert.tourney.WDFT'),
			'3' => WCF::getLanguage()->get('wcf.ecp.tourney.insert.tourney.BF')
		);
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function readFormParameters() {

		if (isset($_POST['name'])) $this->name = $_POST['name'];
			else $this->name = '';
		if (isset($_POST['lobby'])) $this->lobby = $_POST['lobby'];
			else $this->lobby = '';
		if (isset($_POST['art'])) $this->art = $_POST['art'];
			else $this->art = '';
		if (isset($_POST['calender'])) $this->calender = $_POST['calender'];
			else $this->calender = 0;
		if (isset($_POST['participiants'])) $this->participiants = $_POST['participiants'];
			else $this->participiants = '';
		if (!empty($_POST['officialEvent'])) $this->officialEvent = $_POST['officialEvent'];
			else $this->officialEvent = '';
		if (isset($_POST['description'])) $this->description = $_POST['description'];
			else $this->description = '';

		if (isset($_POST['eventDay'])) {
			if ($_POST['eventDay'] == '0') {
					$this->eventDay = '';
				}
				else {
					$this->eventDay = intval($_POST['eventDay']);
				}
		}
		if (isset($_POST['eventMonth'])) {
			if ($_POST['eventMonth'] == '0') {
					$this->eventMonth = '';
				}
				else {			
					$this->eventMonth = intval($_POST['eventMonth']);
				}
		}
		if (isset($_POST['eventYear'])) {
				if (($_POST['eventYear'] == '0') || ($_POST['eventYear'] == '')) {
					$this->eventYear = '';
				}
				else {
					$this->eventYear = intval($_POST['eventYear']);
				}
		}	 				
		if (isset($_POST['eventHour'])) {
				if ($_POST['eventHour'] != '') {
					$this->eventHour = intval($_POST['eventHour']);
				}
		}	 
		if (isset($_POST['eventMinute'])) {
				if ($_POST['eventMinute'] != '') {
					$this->eventMinute = intval($_POST['eventMinute']);
				}
		}
	}

	/**
	 * This method does additional time checks of the deadlineTime.
	 * @param time - entered deadline time
	 */
	protected function additionalDeadlineTimeCheck($time) {
		return true;
	}

	/**
	 * @see Page::validate()
	 */
	public function validate() {
		parent::validate();

		if(!empty($this->name)) $this->name = StringUtil::trim($this->name);
			else throw new UserInputException('name','empty');

		$this->time = @gmmktime($this->eventHour, 
			$this->eventMinute,
			0, 
			$this->eventMonth, 
			$this->eventDay, 
			$this->eventYear);

		// get utc time
		$time = DateUtil::getUTC($this->time);

		if (($time <= TIME_NOW) && $this->additionalDeadlineTimeCheck($time))
			throw new UserInputException('date', 'invalid.future');
		if (!empty($this->art)) $this->art = StringUtil::trim($this->art);
			else throw new UserInputException('art','empty');
		if (!empty($this->calender)) $this->calender = intval($this->calender);
		if (!empty($this->officialEvent)) $this->officialEvent = intval($this->officialEvent);
		if (!empty($this->lobby)) $this->lobby = StringUtil::trim($this->lobby);
			else throw new UserInputException('lobby','empty');
		if (!empty($this->participiants)) $this->participiants = StringUtil::trim($this->participiants);
			else throw new UserInputException('participiants','empty');
		if (!empty($this->description)) $this->description = StringUtil::trim($this->description);
			else throw new UserInputException('description','empty');

		$this->time = DateUtil::getUTC($this->time);
	}

	/**
	 * @see Form::save()
	 */
	public function save(){
		parent::save();

			// Eintragen
		$sql = "INSERT INTO events
						SET name = '".escapeString($this->name)."',
								art = '$this->art',
								contacts = '".WCF::getUser()->userID."',
								participants = '$this->participiants',
								description = '".escapeString($this->description)."',
								time = '$this->time',
								lobby = '$this->lobby',
								officialEvent ='$this->officialEvent'";
		WCF::getDB()->sendQuery($sql);

			// Redirect
		WCF::getTPL()->assign(array(
			'url' => "index.php?page=ECPTourney".SID_ARG_2ND,
			'message' => WCF::getLanguage()->get('wcf.ecp.tourney.insert.tourney.succesful')
		));
		WCF::getTPL()->display('redirect');
		exit;
	}

	public function assignVariables(){
		// init calendar data
		InlineCalendar::assignVariables();

		parent::assignVariables();
		// mark ECP as active
		require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
		PageMenu::setActiveMenuItem('wcf.header.menu.ecp');

			//// Give Template ////
		WCF::getTPL()->assign(array(
			'eventDay' 		=> $this->eventDay,
			'eventMonth' 	=> $this->eventMonth,
			'eventYear' 		=> $this->eventYear,
			'eventHour' 		=> $this->eventHour,
			'eventMinute' 	=> $this->eventMinute,
			'calenderArray' 	=> $this->calenderArray,
			'name' 	=> $this->name,
			'lobby' 	=> $this->lobby,
			'art' 	=> $this->art,
			'participiants' 	=> $this->participiants,
			'description' 	=> $this->description,
			'officialEvent' 	=> $this->officialEvent,
			'lobbyArray' 	=> $this->lobbyArray,
			'artArray' 	=> $this->artArray,
			'participiantsArray' 	=> $this->participiantsArray,
			'officialEventArray' 	=> $this->officialEventArray
		));
	}
	public function show() {
		parent::show();
	}
}
?>