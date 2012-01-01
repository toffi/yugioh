<?php
require_once(WCF_DIR.'lib/page/SortablePage.class.php');
require_once(WCF_DIR.'lib/page/ECPHeadPage.class.php');

class EcpAbbortTourneyPage extends SortablePage {
	public $templateName = 'EcpAbbortTourney';
	public $defaultSortField = NULL;
	public $sortOrder = 'DESC';

	public function validateSortField() {
		parent::validateSortField();
		switch ($this->sortField) {
			case 'name':
			case 'art':
			case 'time':
			case 'contacts';
			break;
			default: $this->sortField = $this->defaultSortField;
		}
	}

	public function readParameters() {
		parent::readParameters();
		
	}

	public function readData() {
		parent::readData();
		$sortierung = '';

			// Welche Sortierung für das Pack
		if($this->sortField != NULL)
			$sortierung = "ORDER BY events.".$this->sortField." ".$this->sortOrder;

			// Hole die Tuniere
			$sql = "SELECT
								events.id,
								events.name,
								events.time,
								events.contacts,
								events.art,
								wcf1_user.username
							FROM		events 
							LEFT JOIN	 wcf1_user
								ON (wcf1_user.userID=events.contacts)
							WHERE 	events.art = '3'	&& events.status = '5'". $sortierung;
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray()) {
			// Ermittel Usernamen
			$row['contacts_name'] = $row['username'];
			
			// Ermittel Turnierart
			switch ($row['art']) {
					case 3:
							$row['artName'] = 'wcf.ecp.tourney.insert.tourney.kosystem';
							break;
			}
			$this->tourneyList[] = $row;
		}
		if (!isset($this->tourneyList)) $this->tourneyList = NULL;
	}



	public function assignVariables() {
		parent::assignVariables();
		// mark ECP as active
		require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
		PageMenu::setActiveMenuItem('wcf.header.menu.ecp');

		// Weise Variabeln dem Template zu
		WCF::getTPL()->assign(array(
			'tourneyList' => $this->tourneyList,
		));
	}

}
?>
