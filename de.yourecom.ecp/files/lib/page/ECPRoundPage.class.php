<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/page/ECPHeadPage.class.php');

    // Sortiere die Single Liga
    function sortiere($a,$b) {
        if(isset($a['points']) && isset($b['points'])) {
            if ($a['points'] == $b['points']) {
                return ( $a['rates'] > $b['rates'] ) ? -1 : 1;
            }
            return ( $a['points'] > $b['points'] ) ? -1 : 1;
        }
    }

    // Sortiere den Teambewerb
    function sortiere2($a, $b) {
        if(isset($a['event_group']) && isset($b['event_group'])) {
            if ($a['event_group'] == $b['event_group']) {
                if(isset($a['points']) && isset($b['points'])) {
                    if ($a['points'] == $b['points']) {
                        if(isset($a['rates']) && isset($b['rates'])) {
                            if ($a['rates'] == $b['rates']) {
                                return ( $a['saetze'] > $b['saetze'] ) ? -1 : 1;
                            }
                        }
                        return ( $a['rates'] > $b['rates'] ) ? -1 : 1;
                    }
                    return ( $a['points'] > $b['points'] ) ? -1 : 1;
                }
            }
        return ( $a['event_group'] < $b['event_group'] ) ? -1 : 1;
        }
    }

class ECPRoundPage extends AbstractPage {
    //system
	public $templateName = 'ECPRound';
    //ecp
    public $eventID = 0;
    public $gameday = 0;
    public $event = array();
    public $contact = array();
    public $pairings = array();
    public $changeNew = array();
    public $changeOld = array();
    public $tabelle = array();

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
        parent::readParameters();
		// call readParameters event
        if(isset($_GET['id'])) $this->eventID = intval($_GET['id']);
		if(isset($_GET['akt'])) $this->gameday = intval($_GET['akt']);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
        parent::readData();
        $sql = "SELECT	*
                FROM    events
                WHERE events.id=".$this->eventID;
        $this->event = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);
        if(empty($this->event)) {
            throw new IllegalLinkException();
        }
        if($this->event['status'] <= 3) {
            $this->getContact();
            $this->getPairings();
            if($this->event['art'] == 1) $this->creatTableSingleLeague();
            elseif($this->event['art'] == 2) $this->creatTableTeamLeague();
            $this->getChanges();
        }
        else {
            throw new NamedUserException('Das Event wurde noch nicht gestartet!');
        }
        
        // Wenn ein ungültiger SPieltag angegeben wurde
        if(empty($this->pairings)) {
            throw new IllegalLinkException();
        }
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
        parent::assignVariables();

		// assign parameters
		WCF::getTPL()->assign(array(
			'eventID' => $this->eventID,
			'gameday' => $this->gameday,
            'event' => $this->event,
            'contact' => $this->contact,
            'pairings' => $this->pairings,
            'changeNew' => $this->changeNew,
            'changeOld' => $this->changeOld,
            'last_gameday' => (count($this->pairings) * 2 * 2) - 2,
            'tabelle' => $this->tabelle
		));
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
        parent::show();
	}


	/**
	 * get the users marking
	 */
    public static function getUserOnlineMarking($userID){
        $sql = "SELECT	wcf".WCF_N."_user.username, wcf".WCF_N."_user.userOnlineGroupID, wcf".WCF_N."_group.userOnlineMarking
                    FROM		wcf".WCF_N."_user
                    LEFT JOIN   wcf".WCF_N."_group
                        ON (wcf".WCF_N."_group.groupID=wcf".WCF_N."_user.userOnlineGroupID)
                    WHERE wcf1_user.userID=".$userID;
        $result = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);
        $result['username'] = str_replace('%s',$result['username'], $result['userOnlineMarking']);
        return $result['username'];
    }

	/**
	 * get the contact of the current event
	 */
    public function getContact() {
        $sql = "SELECT	user.username, user.userID, user.userOnlineGroupID, wcf".WCF_N."_group.userOnlineMarking
                FROM		wcf".WCF_N."_user user
                LEFT JOIN   wcf".WCF_N."_group
                    ON (wcf".WCF_N."_group.groupID=user.userOnlineGroupID)
                WHERE user.userID=".$this->event['contacts'];
        $this->contact = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);
    }
	/**
	 * get the pairings of the current Event
	 */
    public function getPairings() {
        $sql = "SELECT	events_paarungen.*
                FROM    events_paarungen
				WHERE   event_ID=".$this->eventID."
                    AND gameday = ".$this->gameday;
        if($this->event['art'] == 2) {
            $sql .= " ORDER BY events_paarungen.event_group ASC, events_paarungen.gamenumber ASC";
        }
        $result = WCF::getDB()->sendQuery($sql);
        while ($row = WCF::getDB()->fetchArray($result)) {
            $row['username_1'] = self::getUserOnlineMarking($row['userID1']);
            $row['username_2'] = self::getUserOnlineMarking($row['userID2']);

            if($this->event['art'] == 2) {
                $sql2 = "SELECT  id, teamname
                        FROM    events_team
                        WHERE   id=".$row['teamID1']." OR id=".$row['teamID2'];
                $result2 = WCF::getDB()->sendQuery($sql2);
                while ($row2 = WCF::getDB()->fetchArray($result2)) {
                    if ($row2['id'] == $row['teamID1']) {
                        $row['teamname1'] = $row2['teamname'];
                    }
                    elseif ($row2['id'] == $row['teamID2']) {
                        $row['teamname2'] = $row2['teamname'];
                    }
                }
                if(!isset($row['teamname1'])) $row['teamname1'] = 'Freilos';
                if(!isset($row['teamname2'])) $row['teamname2'] = 'Freilos';

                if(!isset($row['matches_1'])) {
                    $row['matches_1'] = 0;
                }
				if(!isset($row['matches_2'])) {
				    $row['matches_2'] = 0;
                }

    			if($row['scoreID_1'] > $row['scoreID_2']) {
                    $row['matches_1'] += 1;
                    $row['matches_2'] += 0;
                }
                elseif($row['scoreID_1'] < $row['scoreID_2']) {
                    $row['matches_1'] += 0;
                    $row['matches_2'] += 1;
                }
                else {
                    $row['matches_1'] += 0;
                    $row['matches_2'] += 0;
    			}
            }
            $this->pairings[] = $row;
        }

        if($this->event['art'] == 2) {
            $this->removeDoubles();
        }
    }

	/**
	 * Remove double entries for the teambewerb
	 */
    public function removeDoubles() {
        $countArray = count($this->pairings);
        for($i = 0; $i < $countArray ; $i++){
            if(!empty($this->pairings[$i]) && isset($this->pairings[$i])){
                for( $a = $i + 1; $a <= $countArray; $a++ ){
                    if(isset($this->pairings[$a]) && $this->pairings[$i]['teamID1'] == $this->pairings[$a]['teamID1'] && $this->pairings[$i]['teamID2'] == $this->pairings[$a]['teamID2']){
                        $this->pairings[$i]['matches_1'] += $this->pairings[$a]['matches_1'];
                        $this->pairings[$i]['matches_2'] += $this->pairings[$a]['matches_2'];
                        unset($this->pairings[$a]);
                    }
                }
            }
        }
    }

	/**
	 * Create the tables for the single league
	 */
    public function creatTableSingleLeague() {
		$sql = "SELECT	user_ID, games, points, rates, S_0, s, U, N_0, n
                FROM    events_user
					WHERE event_id=".$this->eventID." && ab <= ".$this->gameday." && (bis >= ".$this->gameday." || bis ='0')";
        $result = WCF::getDB()->sendQuery($sql);
        while ($row = WCF::getDB()->fetchArray($result)) {
            $row['username'] = self::getUserOnlineMarking($row['user_ID']);
            $sql2 = "SELECT     scoreID_1, scoreID_2, userID1, userID2
                        FROM    events_paarungen
						WHERE event_id=".$this->eventID." && (userID1 = ".$row['user_ID']." || userID2 = ".$row['user_ID'].") && 
                                gameday <= ".$this->gameday." && (scoreID_1 != 0 || scoreID_2 != 0) && scoreID_1 != '' && 
                                scoreID_2 != ''";
            $result2 = WCF::getDB()->sendQuery($sql2);
            while ($row2 = WCF::getDB()->fetchArray($result2)) {
    			$row['games'] = $row['games'] + 1;
						// Spieler 1
                if($row2['userID1'] == $row['user_ID']) {
    				// Double defloose
                    if($row2['scoreID_2'] == 'x' && $row2['scoreID_1'] == 'x') {
                        $row['N_0'] += 1;
                        $row['rates'] -= 2;
                        $row['scoreID_1'] = 0;
                        $row['scoreID_2'] = 0;
                        $row['points'] = $row['points'] + POINTS_DOUBELEDEFLOOSE;
                    }
                    // Draw
                    elseif($row2['scoreID_1'] == $row2['scoreID_2']) {
                        $row['U']++;
                        $row['points'] = $row['points'] + POINTS_DRAW;
                    }
                    // 2:0 Sieg
                    elseif($row2['scoreID_1'] - $row2['scoreID_2'] == $row2['scoreID_1']) {
                        $row['S_0']++;
                        $row['points'] = $row['points'] + POINTS_WIN20;
                        $row['rates'] = $row['rates'] + 2;
                    }
    				// 2:1 Sieg
                    elseif($row2['scoreID_1'] - $row2['scoreID_2'] == 1) {
                        $row['s']++;
                        $row['points'] = $row['points'] + POINTS_WIN21;
                        $row['rates'] = $row['rates'] + 1;
                    }
                    // 1:2 Niederlage
                    elseif($row2['scoreID_2'] - $row2['scoreID_1'] == 1) {
                        $row['n']++;
                        $row['rates'] = $row['rates'] - 1;
                        $row['points'] = $row['points'] + POINTS_LOOSE21;
                    }
                    // 0:2 Niederlage
                    elseif($row2['scoreID_2'] - $row2['scoreID_1'] == $row2['scoreID_2']) {
                        $row['N_0']++;
                        $row['rates'] = $row['rates'] - 2;
                        $row['points'] = $row['points'] + POINTS_LOOSE20;
                    }
    				// Double defloose
                    elseif($row2['scoreID_2'] == 10 && $row2['scoreID_1'] == 10) {
                        $row['N_0']++;
                        $row['rates'] = $row['rates'] - 2;
                        $row['points'] = $row['points'] + POINTS_DOUBELEDEFLOOSE;
                    }
                }
                // Spieler 2
                elseif($row2['userID2'] == $row['user_ID']) {
                    // Double defloose
                    if($row2['scoreID_2'] == 'x' && $row2['scoreID_1'] == 'x') {
                        $row['N_0'] += 1;
                        $row['rates'] -= 2;
                        $row['scoreID_1'] = 0;
                        $row['scoreID_2'] = 0;
                        $row['points'] = $row['points'] + POINTS_DOUBELEDEFLOOSE;
                    }
                    // Draw
                    elseif($row2['scoreID_2'] == $row2['scoreID_1']) {
                        $row['U']++;
                        $row['points'] = $row['points'] + POINTS_DRAW;
                    }
                    // 2:0 Niederlage
                    elseif($row2['scoreID_1'] - $row2['scoreID_2'] == $row2['scoreID_1']) {
                        $row['N_0']++;
                        $row['rates'] = $row['rates'] - 2;
                        $row['points'] = $row['points'] + POINTS_LOOSE20;
                    }
                    // 2:1 Niederlage
                    elseif($row2['scoreID_1'] - $row2['scoreID_2'] == 1) {
                        $row['n']++;
                        $row['rates'] = $row['rates'] - 1;
                        $row['points'] = $row['points'] + POINTS_LOOSE21;
                    }
                    // 1:2 Sieg
                    elseif($row2['scoreID_2'] - $row2['scoreID_1'] == 1) {
                        $row['s']++;
                        $row['points'] = $row['points'] + POINTS_WIN21;
                        $row['rates'] = $row['rates'] + 1;
                    }
                    // 0:2 Sieg
                    elseif($row2['scoreID_2'] - $row2['scoreID_1'] == $row2['scoreID_2']) {
                        $row['S_0']++;
                        $row['points'] = $row['points'] + POINTS_WIN20;
                        $row['rates'] = $row['rates'] + 2;
                    }
                    // Double defloose
                    elseif($row2['scoreID_2'] == 10 && $row2['scoreID_1'] == 10) {
                        $row['N_0']++;
                        $row['rates'] = $row['rates'] - 2;
                        $row['points'] = $row['points'] + POINTS_DOUBELEDEFLOOSE;
                    }
                }
            }
			$this->tabelle[] = $row;
            usort($this->tabelle, 'sortiere');
        }
    }

	/**
	 * Create the tables for the team league
	 */
    public function creatTableTeamLeague() {
        $sql = "SELECT  *
                FROM    events_team
                WHERE event_id=".$this->eventID." 
                    AND teamname != 'Freilos'
                    AND ab <= ".$this->gameday." 
                    AND (bis >=".$this->gameday." || bis = 0)";
        $result = WCF::getDB()->sendQuery($sql);
        while ($row = WCF::getDB()->fetchArray($result)) {
        	$row['Unentschieden'] = 0;
        	$row['gespielte_spiele'] = 0;
        	$row['saetze'] = 0;
        	$row['Sieg'] = 0;
        	$row['Niederlage'] = 0;
        	$row['score_anzahl_1'] = 0;
        	$row['score_anzahl_2'] = 0;
        
            for($count = 1; $count <= $this->gameday; $count++) {
                if(!isset($row['Niederlage2'])) $row['Niederlage2'] = 0;
        		if(!isset($row['Sieg2'])) $row['Sieg2'] = 0;
        		if(!isset($row['Unentschieden2'])) $row['Unentschieden2'] = 0;
        		$sql2 = "SELECT       scoreID_1, scoreID_2, teamID1, teamID2, event_group
                            FROM      events_paarungen
                            WHERE     event_id=".$this->eventID."
                                AND (teamID1 = ".$row['id']." || teamID2 = ".$row['id'].")
                                AND gameday = ".$count."
        						ORDER BY gameday ASC";
        		$result2 = WCF::getDB()->sendQuery($sql2);
        		while ($row2 = WCF::getDB()->fetchArray($result2)) {
        			$row['event_group'] = $row2['event_group'];
        
        			if((($row2['scoreID_1'] != '0' && $row2['scoreID_1'] != '') || ($row2['scoreID_2'] != '0' && $row2['scoreID_2'] != '')) && $row2['teamID1'] != '0' && $row2['teamID2'] != '0') {
        				$row['games'] = $row['games'] + 1;
        				$row['gespielte_spiele'] = $count;
        
        				// Team 1
        				if($row2['teamID1'] == $row['id']) {
        				    $row['score_anzahl_1'] += $row2['scoreID_1'];
        				    $row['score_anzahl_2'] += $row2['scoreID_2'];
            				// Double defloose
                            if($row2['scoreID_2'] == 'x' && $row2['scoreID_1'] == 'x') {
            				    $row['Niederlage2'] += 1;
            				    $row['rates'] -= 2;
            				    $row2['scoreID_1'] = 0;
            				    $row2['scoreID_2'] = 0;
            				}
            				// Unentschieden
            				elseif($row2['scoreID_1'] == $row2['scoreID_2']) {
            				    $row['Unentschieden2']++;
                            }
            				// Sieg
            				elseif($row2['scoreID_1'] > $row2['scoreID_2']) {
            					$row['Sieg2'] += 1;
            					$row['rates'] += 1;
            				}
            				// Niederlage
            				elseif($row2['scoreID_2'] > $row2['scoreID_1']) {
            					$row['Niederlage2'] += 1;
            					$row['rates'] -= 1;
            				}
        			     	$row['saetze'] += $row2['scoreID_1'] - $row2['scoreID_2'];
                        }
                        // Team 2
        				elseif($row2['teamID2'] == $row['id']) {
        					$row['score_anzahl_1'] += $row2['scoreID_2'];
        					$row['score_anzahl_2'] += $row2['scoreID_1'];
            				// Double defloose
        					if($row2['scoreID_2'] == 'x' && $row2['scoreID_1'] == 'x') {
        						$row['Niederlage2'] += 1;
        						$row['rates'] = $row['rates'] - 2;
        						$row2['scoreID_1'] = 0;
        						$row2['scoreID_2'] = 0;
        					}
            				// Unentschieden
        					elseif($row2['scoreID_2'] == $row2['scoreID_1']) {
        						$row['Unentschieden2']++;
                            }
        					// Niederlage
        					elseif($row2['scoreID_1'] > $row2['scoreID_2']) {
        						$row['Niederlage2'] += 1;
        						$row['rates'] -= 1;
        					}
        					// 1:2 Sieg
        					elseif($row2['scoreID_2'] > $row2['scoreID_1']) {
        						$row['Sieg2'] += 1;
        						$row['rates'] += 1;
        					}
        					$row['saetze'] += $row2['scoreID_2'] - $row2['scoreID_1'];
        				}
        			}
        		}
                if($row['Sieg2'] == 0 && $row['Niederlage2'] == 0) {
                    $row['points'] += 0;
                }
                elseif($row['Sieg2'] > $row['Niederlage2'] && ($row['score_anzahl_1'] != 0 || $row['score_anzahl_2'] != 0)) {
        			$row['points'] += POINTS_WIN20;
        			$row['Sieg'] += 1;
        		}
        		elseif($row['Sieg2'] < $row['Niederlage2'] && ($row['score_anzahl_1'] != 0 || $row['score_anzahl_2'] != 0)) {
        			$row['points'] += POINTS_LOOSE20;
        			$row['Niederlage'] += 1;
        		}
        		elseif($row['Sieg2'] == $row['Niederlage2'] && $row['score_anzahl_1'] > $row['score_anzahl_2'] && ($row['score_anzahl_1'] != 0 || $row['score_anzahl_2'] != 0)) {
        			$row['points'] += POINTS_WIN21;
        			$row['Unentschieden']++;
        		}
        		elseif($row['Sieg2'] == $row['Niederlage2'] && $row['score_anzahl_1'] < $row['score_anzahl_2'] && ($row['score_anzahl_1'] != 0 || $row['score_anzahl_2'] != 0)) {
            		$row['points'] += POINTS_LOOSE_SCORE;
        			$row['Unentschieden']++;
        		}
        		elseif($row['Sieg2'] == $row['Niederlage2'] && $row['score_anzahl_1'] == $row['score_anzahl_2'] && ($row['score_anzahl_1'] != 0 || $row['score_anzahl_2'] != 0)) {
            		$row['points'] += POINTS_DRAW;
        			$row['Unentschieden']++;
        		}
                $row['Niederlage2'] = 0;
                $row['Sieg2'] = 0;
                $row['score_anzahl_1'] = 0;
        		$row['score_anzahl_2'] = 0;
            }
            unset($count);
        	$this->tabelle[] = $row;
        	usort ($this->tabelle, 'sortiere2');

        }
    }

	/**
	 * Get the changes of the event
	 */
    public function getChanges() {
        $bis = $this->gameday - 1;
        if($bis > 0) $bisSql = "|| bis =".$bis;
        else $bisSql = '';
  
        $sql = "SELECT	user_ID, ab, bis
                FROM    events_user
                WHERE   event_id=".$this->eventID."
                    AND (ab=".$this->gameday.$bisSql.")";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
            $row['username'] = self::getUserOnlineMarking($row['user_ID']);
 
            if(!empty($row['ab'])) {
                $this->changeNew[] = $row;
            }
            if(!empty($row['bis'])) {
                $this->changeOld[] = $row;
            }
		}
    }
}
?>
