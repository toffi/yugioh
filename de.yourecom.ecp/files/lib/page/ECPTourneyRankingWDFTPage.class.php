<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/page/ECPHeadPage.class.php');

class ECPTourneyRankingWDFTPage extends AbstractPage {
	public $templateName = 'ECPTourneyRankingWDFT';
	public $tourneyList = array();
	public $userList2 = array();

	public function readParameters() {
		parent::readParameters();
		
	}

	public function sortiere($a,$b) {
		if(isset($a['points']) && isset($b['points'])) {
			return ($a['points'] > $b['points']) ? -1 : 1;
		}
	}

	public function readData() {
		parent::readData();
			$year = date('Y');

			// Hole die Turniere
			$sql = "SELECT events.id, events.officialEvent, COUNT(events_paarungen.userID1) AS userID1, 
								COUNT(events_paarungen.userID2) AS userID2
							FROM events
							LEFT JOIN events_paarungen
								ON (events_paarungen.event_ID=events.id)
							WHERE events.art = '3' && events_paarungen.event_round = '1' && events_paarungen.userID1 != '0' && 
								events_paarungen.userID2 != '0' && events.status = '10' && events.officialEvent = '2' && 
							events.time	>='".mktime ( 0, 0, 0, 1, 1, $year)."'
							GROUP BY events.id";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray()) {
				$tourneyList2['id'] = $row['id'];
				$tourneyList2['number'] = $row['userID1'] + $row['userID2'];
				$tourneyList2['officialEvent'] = $row['officialEvent'];
				$this->tourneyList[] = $tourneyList2;
			}

			// Hole die User
			$a = array();
			foreach ($this->tourneyList as $tourneyList) {
				$sql = "SELECT events_user.user_ID, wcf1_user.username
									FROM events_user
									LEFT JOIN wcf1_user
										ON (wcf1_user.userID=events_user.user_ID)
								WHERE events_user.event_ID='".$tourneyList['id']."'";
				$result = WCF::getDB()->sendQuery($sql);
				while ($row = WCF::getDB()->fetchArray()) {
					$row['points'] = 0;
					if(!in_array($row['user_ID'], $a)) {
						$a[] = $row['user_ID'];
						$this->userList2[] = $row;
					}
				}
			}

			foreach ($this->userList2 as $userList) {

					// Matchpoints + ACP
				foreach ($this->tourneyList as $tourneyList) {
					$sql = "SELECT userID1, userID2, scoreID_1, scoreID_2
									FROM events_paarungen
									WHERE event_ID='".$tourneyList['id']."'";
					$result = WCF::getDB()->sendQuery($sql);
					while ($row = WCF::getDB()->fetchArray()) {
						if($row['userID1'] == $userList['user_ID']) {
									// Draw
								if($row['scoreID_1'] == $row['scoreID_2'])
										$userList['points'] += POINTS_DRAW;
									// 2:0 Sieg
								elseif($row['scoreID_1'] - $row['scoreID_2'] == $row['scoreID_1'])
										$userList['points'] += POINTS_WIN20;
									// 2:1 Sieg
								elseif($row['scoreID_1'] - $row['scoreID_2'] == 1)
									$userList['points'] += POINTS_WIN21;
									// 1:2 Niederlage
								elseif($row['scoreID_2'] - $row['scoreID_1'] == 1)
									$userList['points'] += POINTS_LOOSE21;
									// 0:2 Niederlage
								elseif($row['scoreID_2'] - $row['scoreID_1'] == $row['scoreID_2'])
									$userList['points'] += POINTS_LOOSE20;
						}
						elseif($row['userID2'] == $userList['user_ID']) {
									// Draw
								if($row['scoreID_2'] == $row['scoreID_1'])
									$userList['points'] += POINTS_DRAW;
									// 2:0 Niederlage
								elseif($row['scoreID_1'] - $row['scoreID_2'] == $row['scoreID_1'])
									$userList['points'] += POINTS_LOOSE20;
									// 2:1 Niederlage
								elseif($row['scoreID_1'] - $row['scoreID_2'] == 1)
									$userList['points'] += POINTS_LOOSE21;
									// 1:2 Sieg
								elseif($row['scoreID_2'] - $row['scoreID_1'] == 1)
									$userList['points'] += POINTS_WIN21;
									// 0:2 Sieg
								elseif($row['scoreID_2'] - $row['scoreID_1'] == $row['scoreID_2'])
									$userList['points'] += POINTS_WIN20;
						}
					}

					// Punkte der ersten drei Platzierungen
					$sql = "SELECT userID1, userID2, winnerID
									FROM events_paarungen 
									WHERE event_ID = '".$tourneyList['id']."'
									ORDER BY event_round DESC 
									LIMIT 2";
					$result = WCF::getDB()->sendQuery($sql);
					while ($row = WCF::getDB()->fetchArray()) {
						if(!isset($first) && !isset($second) && !isset($third) && !isset($fourth)) $first = $row['winnerID'];
						if(!isset($second) && $row['userID1'] == $first) $second = $row['userID2'];
							elseif(!isset($second) && $row['userID2'] == $first) $second = $row['userID1'];
						if(isset($first) && isset($second) && !isset($third) && $row['winnerID'] != $first) $third = $row['winnerID'];
						if(!isset($fourth) && isset($third) && $row['userID1'] == $third) $fourth = $row['userID2'];
							elseif(!isset($fourth) && isset($third) && $row['userID2'] == $third) $fourth = $row['userID1'];
					}
							// WDFT
					if($tourneyList['officialEvent'] == 2) {
						if($userList['user_ID'] == $first) $userList['points'] += 5;
						elseif($userList['user_ID'] == $second) $userList['points'] += 3;
						elseif($userList['user_ID'] == $third) $userList['points'] += 1;
					}

					unset($first);
					unset($second);
					unset($third);
					unset($fourth);
				}
				$this->userList[] = $userList;
				
				usort($this->userList, array($this,'sortiere'));
		  }
		if (!isset($this->userList)) $this->userList = NULL;
	}



	public function assignVariables() {
		parent::assignVariables();

		// mark ECP as active
		require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
		PageMenu::setActiveMenuItem('wcf.header.menu.ecp');

		// Weise Variabeln dem Template zu
		WCF::getTPL()->assign(array(
			'userList' => $this->userList
		));
	}

}
?>
