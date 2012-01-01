<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

/**
 * Marks all boards as read.
 * 
 * @author 	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	WoltLab Burning Board License <http://www.woltlab.com/products/burning_board/license.php>
 * @package	com.woltlab.wbb
 * @subpackage	action
 * @category 	Burning Board
 */
class EcpTourneyStatusAction extends AbstractAction {
  public $minTeilnehmer = 3;
  public $userList = array();
  public $gameNr = 0;
  public $final1 = '';
  public $final2 = '';
  public $thrid1 = '';
  public $thrid2 = '';
	public $insert1 = '';
	public $insert2 = '';
	public $groupSize = 4;
	/**
	 * @see Action::readParameters()
	 */
	public function readParameters()  {
		parent::readParameters();
		if(isset($_GET['eventID'])) $this->eventID = intval($_GET['eventID']);
		if(isset($_GET['status'])) $this->status = intval($_GET['status']);

			// Get T-Admin
		$sql = "SELECT contacts
								FROM events 
								WHERE id = ".$this->eventID;
		WCF::getDB()->sendQuery($sql);
		$TAdmin[] = WCF::getDB()->getFirstRow($sql);
		if (!WCF::getUser()->getPermission('mod.ecp.canEditEveryResult') && WCF::getUser()->userID != $TAdmin[0]['contacts']) {
			throw new PermissionDeniedException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		// Hole die Tunierdetails
		$sql = "SELECT events.status, events.participants, events.art
						FROM events
						WHERE events.id = ".$this->eventID;
		$this->tourneyList = WCF::getDB()->getFirstRow($sql);
		if($this->tourneyList['status'] == 10)
			return;

				// Wieviele angemeldet?
		if($this->tourneyList['status'] == 2) {
			$sql = "SELECT *
							FROM		events_user
							WHERE event_id = '".$this->eventID."' && status = '1'";
			WCF::getDB()->sendQuery($sql);
			$this->certificatedUser = WCF::getDB()->countRows();
		}
		else {
			$sql = "SELECT *
							FROM		events_user
							WHERE event_id = ".$this->eventID;
			WCF::getDB()->sendQuery($sql);
			$this->Number = WCF::getDB()->countRows();
		}

		if((isset($this->certificatedUser) && $this->certificatedUser < $this->minTeilnehmer) ||
			(isset($this->Number) && $this->Number < $this->minTeilnehmer && $this->status != 4))
			throw new NamedUserException(WCF::getLanguage()->get('wcf.ecp.tourney.detail.certificatet.status'));
		elseif (empty($this->tourneyList))
			throw new NamedUserException(WCF::getLanguage()->get('wcf.ecp.tourney.detail.status'));
		else {
					//// Updaten ////
			$this->tourneyList['status']++;

			if($this->tourneyList['status'] == 3) $this->paarungen();
			elseif($this->tourneyList['status'] == 4) $this->tourneyList['status'] = 10;
			elseif(isset($this->status) && $this->status == 4) $this->tourneyList['status'] = 5;
		
			$sql = "UPDATE events
							SET status = '".$this->tourneyList['status']."'
							WHERE 	id = ".$this->eventID;
			WCF::getDB()->sendQuery($sql);
			$url = "index.php?form=ECPTourneyDetail&eventID=".$this->eventID."".SID_ARG_2ND;

				// Ermittel Status
			switch ($this->tourneyList['status']) {
				case 2:
					$this->tourneyList['status_name'] = 'wcf.ecp.tourney.detail.certification.sucessful';
					break;
				case 3:
					$this->tourneyList['status_name'] = 'wcf.ecp.tourney.detail.start.sucessful';
					break;
				case 5:
					$this->tourneyList['status_name'] = 'wcf.ecp.tourney.detail.stop.sucessful';
					break;
				case 10:
					$this->tourneyList['status_name'] = 'wcf.ecp.tourney.detail.end.sucessful';
					break;
			}
			WCF::getTPL()->assign(array(
				'url' => $url,
				'message' => WCF::getLanguage()->get($this->tourneyList['status_name'])
			));
			WCF::getTPL()->display('redirect');
			exit;
		}
	}

	/**
	 * @see Erstelle Paarungen()
	 */
	public function paarungen() {
		$sql = "SELECT events_user.user_ID
						FROM events_user
						WHERE event_id = ".$this->eventID."	&& status = '1'
						ORDER BY ID ASC
						LIMIT ".$this->tourneyList['participants'];
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->userList[] = $row;
		}

				// Ermittel Freilose
		if(count($this->userList) == 2) $frei = 0; elseif(count($this->userList) < 4) $frei = count($this->userList) - 2; 
			elseif(count($this->userList) == 4) $frei = 0; elseif(count($this->userList) < 8) $frei = 8 - count($this->userList); 
			elseif(count($this->userList) == 8) $frei = 0; elseif(count($this->userList) < 16) $frei = 16 - count($this->userList); 
			elseif(count($this->userList) == 16) $frei = 0; elseif(count($this->userList) < 32) $frei = 32 - count($this->userList);
			elseif(count($this->userList) == 32) $frei = 0; elseif(count($this->userList) < 64) $frei = 64 - count($this->userList);
			elseif(count($this->userList) == 64) $frei = 0;

			// Mische die Teilnehmer
		shuffle($this->userList);

			// Füge userList die Freilose hinzu
		for($j = 0; $j < $frei; $j++) {
		  if(!isset($position)) $position = 1;
		  else $position += 2;
		  $array = array();
		  $array['user_ID'] = '0';
			array_splice($this->userList,$position,0,$array);
		}
			// Vorrunde ??
		if($this->tourneyList['art'] == 4) $this->paarungenVor($this->userList);

		// Bestimme die Runden
		if(count($this->userList) == 4) $tourneyRound = 1;
			elseif(count($this->userList) == 8) $tourneyRound = 2;
			elseif(count($this->userList) == 16) $tourneyRound = 3;
			elseif(count($this->userList) == 32) $tourneyRound = 4;
			elseif(count($this->userList) == 64) $tourneyRound = 5;

		for($j = 0; $j < (count($this->userList) - 1); $j++) {
			$j2 = $j++;
			$userID1 = $this->userList[$j]['user_ID'];
			$userID2 = $this->userList[$j2]['user_ID'];
			$this->gameNr++;

			if($userID1 == 0) $winner = $userID2;
			elseif($userID2 == 0) $winner = $userID1;
			else $winner = '';

			// Speicher Looser und Winner bei unter 4 Spielern
			if(count($this->userList) == 4 && $this->gameNr%2!=0 && ($userID1 == 0 || $userID2 == 0)) {
				$this->thrid1 = 0;
				$this->thrid2 = '';
				$this->final1 = $winner;
				$this->final2 = '';
			}
			elseif(count($this->userList) == 4 && $this->gameNr%2==0 && ($userID1 == 0 || $userID2 == 0)) {
				$this->thrid1 = '';
				$this->thrid2 = 0;
				$this->final1 = '';
				$this->final2 = $winner;
			}

							// Trage die Paarungen ein
			$sql = "INSERT INTO events_paarungen
							SET userID1 = ".$userID1.",
									userID2 = ".$userID2.",
									gamenumber = ".$this->gameNr.",
									event_round = '1',
									winnerID = '".$winner."',
									event_ID = ".$this->eventID;
			WCF::getDB()->sendQuery($sql);
		}

			// Paarungen bis zum Finale
		$userListHalf = count($this->userList) / 2;
		for($i = 2; $i <= $tourneyRound; $i++) {
			$userListHalf = $userListHalf / 2;
			for($k = 0; $k < $userListHalf; $k++) {
				$this->gameNr++;
								// Trage die Paarungen ein
				$sql = "INSERT INTO events_paarungen
								SET userID1 = '',
										userID2 = '',
										gamenumber = ".$this->gameNr.",
										event_round = '$i',
										winnerID = '',
										event_ID = ".$this->eventID;
				WCF::getDB()->sendQuery($sql);
			}
		}

			// Finale + Platz 3
		$i--;
		for($m = 0; $m < 2; $m++) {
			$this->gameNr++;

			if(count($this->userList) == 4 && array_search('0', $this->userList) && $m == 0) {
				$this->insert1 = $this->thrid1;
				$this->insert2 = $this->thrid2;
			}
			elseif(count($this->userList) == 4 && array_search('0', $this->userList) && $m == 1) {
				$this->insert1 = $this->final1;
				$this->insert2 = $this->final2;
			}

				$i++;
				$sql = "INSERT INTO events_paarungen
								SET userID1 = '".$this->insert1."',
										userID2 = '".$this->insert2."',
										gamenumber = ".$this->gameNr.",
										event_round = '".$i."',
										winnerID = '',
										event_ID = ".$this->eventID;
				WCF::getDB()->sendQuery($sql);
		}

			// Trage die Gewinner von Freilosen in die nächste Runde ein

		$sql = "SELECT winnerID, gamenumber
						FROM events_paarungen
						WHERE event_ID = ".$this->eventID."	&& 	event_round = '1' && (userID1 = '0' || userID2 = '0')
						ORDER BY id ASC";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {

			$sql = "SELECT gamenumber
							FROM events_paarungen
							WHERE event_id = '".$this->eventID."' && event_round = '2'
							ORDER BY gamenumber ASC";
			$select2[] = WCF::getDB()->getFirstRow($sql);
			$gamenrNexTRound = $select2[0]['gamenumber'] - 1 + ceil($row['gamenumber'] / 2);

			if($row['gamenumber']%2!=0) {
				$sql = "UPDATE events_paarungen
								SET userID1 = '".$row['winnerID']."'
								WHERE 	event_ID = '".$this->eventID."' && gamenumber = ".$gamenrNexTRound;
				WCF::getDB()->sendQuery($sql);
			}
			else {
				$sql = "UPDATE events_paarungen
								SET userID2 = '".$row['winnerID']."'
								WHERE 	event_ID = '".$this->eventID."' && gamenumber = ".$gamenrNexTRound;
				WCF::getDB()->sendQuery($sql);
			}
		}
	}
		// Vorrunde
	public function paarungenVor($userListVor) {
		// Bestimme die Gruppen
		$groupsNumber = ceil(count($userListVor) / $this->groupSize);

		// Paarungen müssen noch pro Gruppe angelehgt werden. for Schleifen funktionieren.

	$paare = $this->groupSize / 2;			// Anzahl der möglichen Spielpaare
	$tage = $this->groupSize-1;					// Anzahl der Spieltage pro Runde
	$plan = array();										// Array für den kompletten Spielplan
	$xpos = $this->groupSize-1;					// höchster Key im Array $users
	$tag = 0;														// Zähler für Spieltag
	$spnr = 0;													// Zähler für Spielnummer
	$sppaar = 0;												// Zähler für Spielpaar

	for ($group=1; $group<= $groupsNumber; $group++) {
		for ($tag=1; $tag< $this->groupSize; $tag++) {
			array_splice ($userListVor, 1, 1, array(array_pop($userListVor),$userListVor[1]));
			for ($sppaar=0; $sppaar < $paare; $sppaar++) {
				$spnr++;
				// wechseln zwischen G und H -Spiel:
				if (($spnr%$this->groupSize != 1) and ($sppaar%2==0)) {
					$hteam = $userListVor[$sppaar]['user_ID'];
					$gteam = $userListVor[$xpos-$sppaar]['user_ID'];
				}
				else {
						$gteam = $userListVor[$sppaar]['user_ID'];
						$hteam = $userListVor[$xpos-$sppaar]['user_ID'];
				}
				$plan[$tag][$spnr]['Group'] = $group;
				$plan[$tag][$spnr]['G'] = $gteam;
				$plan[$tag][$spnr]['H'] = $hteam;
			}
		}
	}




print_r($plan);
die;
		for($j = 0; $j < ($groupsNumber - 1); $j++) {
			for($k = 0; $k < ($this->groupSize - 1); $k++) {
				$j2 = $j++;
				$userID1 = $userListVor[$j]['user_ID'];
				$userID2 = $userListVor[$j2]['user_ID'];
				$this->gameNr++;

				if($userID1 == 0) $winner = $userID2;
				elseif($userID2 == 0) $winner = $userID1;
				else $winner = '';

								// Trage die Paarungen ein
				$sql = "INSERT INTO events_paarungen
								SET userID1 = ".$userID1.",
										userID2 = ".$userID2.",
										gamenumber = ".$this->gameNr.",
										event_round = '1',
										winnerID = '".$winner."',
										event_ID = ".$this->eventID;
				WCF::getDB()->sendQuery($sql);
			}
		}



		die;
	}
}
?>