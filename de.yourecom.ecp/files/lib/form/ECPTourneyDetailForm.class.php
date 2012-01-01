<?php
require_once(WCF_DIR.'lib/form/AbstractForm.class.php');
require_once(WCF_DIR.'lib/page/ECPHeadPage.class.php');

/**
 * @author	SpIkE2
 */
 
class ECPTourneyDetailForm extends AbstractForm {
	public $templateName = 'ECPTourneyDetail';
	public $Joiner = array();
	public $maxColspan = '';
	public $end = 0;

	public function readParameters() {
		parent::readParameters();
		if(isset($_GET['eventID'])) $this->eventID = intval($_GET['eventID']);
	}

	public function readData() {
		parent::readData();
				// Wieviele angemeldet?
		$sql = "SELECT *
								FROM events_user 
								WHERE event_id = ".$this->eventID;
		WCF::getDB()->sendQuery($sql);
		$this->Number = WCF::getDB()->countRows();

			// Ansehender angemeldet?
		$sql = "SELECT user_ID, status
							FROM		events_user 
							WHERE event_id = '".$this->eventID."' && user_ID = ".WCF::getUser()->userID;
		if(WCF::getDB()->getFirstRow($sql)) {
			$this->Joiner[] = WCF::getDB()->getFirstRow($sql);
		}
		else {
			$this->Joiner[] = NULL;
		}

			// Hole Turnierteilnehmer
		$sql = "SELECT events_user.user_ID, events_user.status, wcf".WCF_N."_user.username, wcf".WCF_N."_user.userOnlineGroupID,
									 wcf".WCF_N."_group.userOnlineMarking
							FROM events_user
							LEFT JOIN wcf1_user
								ON (wcf".WCF_N."_user.userID=events_user.user_ID)
							LEFT JOIN wcf".WCF_N."_group
								ON (wcf".WCF_N."_group.groupID = wcf".WCF_N."_user.userOnlineGroupID)
							WHERE event_id = ".$this->eventID;
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$this->userList[] = $row;
			}
		if (!isset($this->userList)) $this->userList = NULL;

			// Hole die Tunierdetails
		$sql = "SELECT
								events.id,
								events.name,
								events.time,
								events.status,
								events.contacts,
								events.art,
								events.participants,
								events.description,
								events.lobby,
								wcf1_user.username
							FROM events 
							LEFT JOIN wcf".WCF_N."_user
								ON (wcf".WCF_N."_user.userID=events.contacts)
							WHERE events.id = ".$this->eventID;
		$this->tourneyList = WCF::getDB()->getFirstRow($sql);

			// Zeilenumbrüche bei Beschreibung akzeptieren.
		$this->tourneyList['description'] = nl2br($this->tourneyList['description']);

			// Ermittel Lobby
		switch ($this->tourneyList['lobby']) {
			case 1:
				$this->tourneyList['lobbyName'] = WCF::getLanguage()->get('wcf.ecp.tourney.lobby.germany');
				break;
			case 2:
				$this->tourneyList['lobbyName'] = WCF::getLanguage()->get('wcf.ecp.tourney.lobby.english');
				break;
			case 3:
				$this->tourneyList['lobbyName'] = WCF::getLanguage()->get('wcf.ecp.tourney.lobby.france');
				break;
			case 4:
				$this->tourneyList['lobbyName'] = WCF::getLanguage()->get('wcf.ecp.tourney.lobby.japanese');
				break;
			case 5:
				$this->tourneyList['lobbyName'] = WCF::getLanguage()->get('wcf.ecp.tourney.lobby.spanish');
				break;
			case 6:
				$this->tourneyList['lobbyName'] = WCF::getLanguage()->get('wcf.ecp.tourney.lobby.korea');
				break;
			case 7:
				$this->tourneyList['lobbyName'] = WCF::getLanguage()->get('wcf.ecp.tourney.lobby.zh');
				break;
			case 8:
				$this->tourneyList['lobbyName'] = WCF::getLanguage()->get('wcf.ecp.tourney.lobby.world');
				break;
		}
 
			// Ermittel Usernamen
		$this->tourneyList['contacts_name'] = $this->tourneyList['username'];
			
			// Ermittel Turnierart
		switch ($this->tourneyList['art']) {
			case 3:
				$this->tourneyList['artName'] = 'wcf.ecp.tourney.insert.tourney.kosystem';
				break;
			case 4:
				$this->tourneyList['artName'] = 'wcf.ecp.tourney.insert.tourney.vorrunde';
				break;
		}

		if (!isset($this->tourneyList)) $this->tourneyList = NULL;

			// Hole Paarungen
		$sql = "SELECT events_paarungen.id, events_paarungen.event_round, events_paarungen.gamenumber, 
									 events_paarungen.userID1, events_paarungen.userID2, events_paarungen.scoreID_1, 
									 events_paarungen.scoreID_2
						FROM	events_paarungen
						WHERE event_id = ".$this->eventID."
						ORDER BY id ASC";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$sql2 = "SELECT wcf".WCF_N."_user.userID, wcf".WCF_N."_user.username, wcf".WCF_N."_user.userOnlineGroupID,
												wcf".WCF_N."_group.userOnlineMarking, wcf".WCF_N."_user_option_value.userOption44
									FROM	wcf".WCF_N."_user
									LEFT JOIN 	wcf".WCF_N."_group
										ON		(wcf".WCF_N."_group.groupID = wcf".WCF_N."_user.userOnlineGroupID)
									LEFT JOIN 	wcf".WCF_N."_user_option_value
										ON		(wcf".WCF_N."_user_option_value.userID = wcf".WCF_N."_user.userID)
									WHERE wcf".WCF_N."_user.userID = ".$row['userID1']." || wcf".WCF_N."_user.userID = ".$row['userID2'];
					$result2 = WCF::getDB()->sendQuery($sql2);
					while ($row2 = WCF::getDB()->fetchArray($result2)) {
						if($row2['userID'] == $row['userID1']) {
							$row['username1'] = $row2['username'];
							$row['userOnlineMarking1'] = $row2['userOnlineMarking'];
							$row['ygoNick1'] = $row2['userOption44'];
						}
						elseif($row2['userID'] == $row['userID2']) {
							$row['username2'] = $row2['username'];
							$row['userOnlineMarking2'] = $row2['userOnlineMarking'];
							$row['ygoNick2'] = $row2['userOption44'];
						}
					}

					if($row['event_round'] == 2 && count($this->userList) == 3) {
						if (empty($row['userID1']) || empty($row['userID2'])) {
							if(empty($row['userID1'])) {
								$row['username1'] = '---';
								$row['userOnlineMarking1'] = '%s';
							}
							if(empty($row['userID2'])) {
								$row['username2'] = 'Freilos';
								$row['userOnlineMarking2'] = '%s';
							}
						}
					}
					else {
						if ((empty($row['userID1']) && empty($row['userID2']) && $row['event_round'] > 1) ||
						 		$row['event_round'] > 1 && (empty($row['username1']) || empty($row['username2']))) {
							if(empty($row['userID1'])) {
								$row['username1'] = '--';
								$row['userOnlineMarking1'] = '%s';
							}
							if(empty($row['userID2'])) {
								$row['username2'] = '--';
								$row['userOnlineMarking2'] = '%s';
								}
						}
						elseif (empty($row['userID1']) || empty($row['userID2'])) {
							if (empty($row['userID1']) || empty($row['userID2'])) {
								$row['username1'] = 'Freilos';
								$row['userOnlineMarking1'] = '%s';
							}
							if (empty($row['userID2'])) {
								$row['username2'] = 'Freilos';
								$row['userOnlineMarking2'] = '%s';
							}
						}
					}
					// Bestimme colspan Größe
				if($row['event_round'] == 1) $row['colspan'] = 1;
				elseif($row['event_round'] == 2) $row['colspan'] = 2;
				elseif($row['event_round'] == 3) $row['colspan'] = 4;
				elseif($row['event_round'] == 4) $row['colspan'] = 8;
				elseif($row['event_round'] == 5) $row['colspan'] = 16;
				elseif($row['event_round'] == 6) $row['colspan'] = 32;
				elseif($row['event_round'] == 7) $row['colspan'] = 64;

				$this->maxColspan = $row['colspan'];

				if(($row['scoreID_1'] != 0 || $row['scoreID_2'] != 0) && !empty($row['userID1']))
					$this->end = 1;
				else
					$this->end = 0;
				$this->paarungenList[] = $row;
			}
		if (!isset($this->paarungenList)) $this->paarungenList = NULL;
	}



	public function assignVariables() {
		parent::assignVariables();
		// mark ECP as active
		require_once(WCF_DIR.'lib/page/util/menu/PageMenu.class.php');
		PageMenu::setActiveMenuItem('wcf.header.menu.ecp');

		// Weise Variabeln dem Template zu
		WCF::getTPL()->assign(array(
			'Number' => $this->Number,
			'tourneyList' => $this->tourneyList,
			'userList' => $this->userList,
			'eventID' => $this->eventID,
			'Joiner' => $this->Joiner[0],
			'paarungenList' => $this->paarungenList,
			'maxColspan' => $this->maxColspan,
			'end' => $this->end
		));
	}

	/**
	 * @see Page::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
			if (isset($_POST["erg0"])) $this->ergebnis0 = $_POST['erg0'];
				else $this->ergebnis0 = 0;
			if (isset($_POST['erg1'])) $this->ergebnis1 = $_POST['erg1'];
				else $this->ergebnis1 = 0;
			if (isset($_POST['erg2'])) $this->ergebnis2 = $_POST['erg2'];
				else $this->ergebnis2 = 0;
			if (isset($_POST['user1'])) $this->user1 = $_POST['user1'];
				else $this->user1 = 0;
			if (isset($_POST['user2'])) $this->user2 = $_POST['user2'];
				else $this->user2 = 0;
	}

	/**
	 * @see Page::validate()
	 */
	public function validate() {
		parent::validate();
		for ($i = 0; $i < count($_POST['erg0']); $i++) {
			if(isset($this->ergebnis0[$i])) $this->ergebnis0[$i] = intval($this->ergebnis0[$i]);
			if(isset($this->ergebnis1[$i])) $this->ergebnis1[$i] = intval($this->ergebnis1[$i]);
			if(isset($this->ergebnis2[$i])) $this->ergebnis2[$i] = intval($this->ergebnis2[$i]);
			if(isset($this->user1[$i])) $this->user1[$i] = intval($this->user1[$i]);
			if(isset($this->user2[$i])) $this->user2[$i] = intval($this->user2[$i]);
		}
	}

	/**
	 * @see Form::save()
	 */
	public function save(){
		parent::save();
			// Get T-Admin
		$sql = "SELECT contacts
								FROM events 
								WHERE id = ".$this->eventID;
		WCF::getDB()->sendQuery($sql);
		$TAdmin[] = WCF::getDB()->getFirstRow($sql);
		if (!WCF::getUser()->getPermission('mod.ecp.canEditEveryResult') && WCF::getUser()->userID != $TAdmin[0]['contacts']) {
			throw new PermissionDeniedException();
		}

		for ($i = 0; $i < count($_POST['erg0']); $i++) {
			if(isset($this->ergebnis1[$i]) && isset($this->ergebnis2[$i])) {
				if($this->ergebnis1[$i] > $this->ergebnis2[$i]) {
					$winner = $this->user1[$i];
					$looser = $this->user2[$i];
				}
				elseif($this->ergebnis1[$i] < $this->ergebnis2[$i]) {
					$winner = $this->user2[$i];
					$looser = $this->user1[$i];
				}
				if(isset($winner)) {
					$sql = "UPDATE events_paarungen
									SET scoreID_1 = '".$this->ergebnis1[$i]."',
											scoreID_2 = '".$this->ergebnis2[$i]."',
											winnerID = '".$winner."'
									WHERE 	id = '".$this->ergebnis0[$i]."'";
					WCF::getDB()->sendQuery($sql);

						// In welcher Runde befinden sich FInale und Platz 3?
					$sql = "SELECT event_round
										FROM		events_paarungen 
										WHERE event_ID = ".$this->eventID."
										ORDER BY event_round DESC";
					$last[] = WCF::getDB()->getFirstRow($sql);
					$finaleRound = $last[0]['event_round'];
					$littleFinaleRound = $finaleRound - 1;

					$sql = "SELECT event_round
										FROM		events_paarungen 
										WHERE id = ".$this->ergebnis0[$i];
					$select[] = WCF::getDB()->getFirstRow($sql);
					$event_round = $select[0]['event_round'];

							// Wievielter ist es in der Übersicht?
					$counter = 0;
					$sql = "SELECT userID1, userID2
											FROM events_paarungen 
											WHERE event_id = '".$this->eventID."' && event_round= '".$event_round."'
											ORDER BY gamenumber ASC";
					$result = WCF::getDB()->sendQuery($sql);
					while ($row = WCF::getDB()->fetchArray($result)) {
						$counter++;
						if($row['userID1'] == $winner) $gamenr = $counter;
						elseif($row['userID2'] == $winner) $gamenr = $counter;
					}

						// Eine Runde vorm Fnale?
					if($event_round == $littleFinaleRound - 1) {
						if($gamenr%2!=0) {
								// Platz 3
							$sql = "UPDATE events_paarungen
											SET userID1 = '".$looser."'
											WHERE 	event_ID = '".$this->eventID."' && event_round = ".$littleFinaleRound;
							WCF::getDB()->sendQuery($sql);
								// Finale
							$sql = "UPDATE events_paarungen
											SET userID1 = '".$winner."'
											WHERE 	event_ID = '".$this->eventID."' && event_round = ".$finaleRound;
							WCF::getDB()->sendQuery($sql);
						}
						else {
								// Platz 3
							$sql = "UPDATE events_paarungen
											SET userID2 = '".$looser."'
											WHERE 	event_ID = '".$this->eventID."' && event_round = ".$littleFinaleRound;
							WCF::getDB()->sendQuery($sql);
								// Finale
							$sql = "UPDATE events_paarungen
											SET userID2 = '".$winner."'
											WHERE 	event_ID = '".$this->eventID."' && event_round = ".$finaleRound;
							WCF::getDB()->sendQuery($sql);
						}
					}
					elseif($event_round < $littleFinaleRound) {
								// Wieviele angemeldet?
						$event_roundNext = $event_round + 1;
						$sql = "SELECT gamenumber
												FROM events_paarungen 
												WHERE event_id = '".$this->eventID."' && event_round= '".$event_roundNext."'
												ORDER BY gamenumber ASC";
						$select2[] = WCF::getDB()->getFirstRow($sql);
						$gamenrNexTRound = $select2[0]['gamenumber'] - 1 + ceil($gamenr / 2);
	
						if($gamenr%2!=0) {
							$sql = "UPDATE events_paarungen
											SET userID1 = '".$winner."'
											WHERE 	event_ID = '".$this->eventID."' && gamenumber = ".$gamenrNexTRound;
							WCF::getDB()->sendQuery($sql);
						}
						else {
							$sql = "UPDATE events_paarungen
											SET userID2 = '".$winner."'
											WHERE 	event_ID = '".$this->eventID."' && gamenumber = ".$gamenrNexTRound;
							WCF::getDB()->sendQuery($sql);
						}
					}
					unset($winner);
				}
			}
		}

      // Redirect
		WCF::getTPL()->assign(array(
			'url' => "index.php?form=ECPTourneyDetail&eventID=".$this->eventID."".SID_ARG_2ND,
			'message' => WCF::getLanguage()->get('wcf.ecp.tourney.detail.result.sucessful')
		));
		WCF::getTPL()->display('redirect');
		exit;
	}
	
}
?>