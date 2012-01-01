<?php
require_once (WCF_DIR . 'lib/form/AbstractForm.class.php');
require_once (WCF_DIR . 'lib/data/user/UserProfile.class.php');
require_once (WCF_DIR . 'lib/page/util/menu/HeaderMenu.class.php');
require_once(WBB_DIR.'lib/data/board/BoardEditor.class.php');
require_once(WBB_DIR.'lib/data/thread/ThreadEditor.class.php');

/**
 * @author	SpIkE2 1282
 * Status 1 = Eintragungsphase
 * Status 2 = 
 * Status 3 = Spielrunde
 */

class EcpAdminDetailForm extends AbstractForm {
	public $templateName = 'ECPAdminDetail';
	public $events = array();
	public $user_ID = array();
	public $user_username = array();
	private $recipient = '';
	private $userID = 0;
	public $errorField = '';
    public $akt = 0;
	/**
	 * @see Form::Form für Erfolgreiches absenden()
	 */
	function saveform($text,$eventID,$nächster_spieltag)
		{
			$sid = SID_ARG_2ND;
			WCF::getTPL()->assign(array(
				'url' => "index.php?form=EcpAdminDetail&id=$eventID&akt=$nächster_spieltag".$sid,
				'message' => WCF::getLanguage()->get($text)
			));
			WCF::getTPL()->display('redirect');
			exit;
		}

	/**
	 * @see Enough Gamer
	 */
	function enoughGamer($tabellemysql,$eventID,$events_participants) {
		$sql3 = "SELECT COUNT(*) AS WieOft
				FROM    ".$tabellemysql."
				WHERE event_id = '$eventID'";
		$result3 = WCF::getDB()->sendQuery($sql3);
		while ($row3 = WCF::getDB()->fetchArray($result3)) {
			$WievieleSpielerAngemeldet = $row3['WieOft'];
		}
		$DifferenzDerSpieler = $events_participants - $WievieleSpielerAngemeldet;
		if($DifferenzDerSpieler == 0) {
			$this->Status2 = 1;
		}
		else
			$this->Status2 = 0;
		return $this->Status2;
	}

	/**
	 * @see Create Gamer Input
	 */
	function createInput($username_url,$i) {
		$sid_arg = SID_ARG_2ND_NOT_ENCODED;
		$this->Spieler = '<input type="text" class="inputText" id="recipient_'.$i.'" name="recipient_'.$i.'" value="'.$username_url.'" />
			<script type="text/javascript">
				//<![CDATA[
					suggestion.setSource(\'index.php?page=PublicUserSuggest'.$sid_arg.'\');
					suggestion.init(\'recipient_'.$i.'\');
				//]]>
			</script>';
		return $this->Spieler;
	}

	/**
	 * Checks the given recipients.
	 */
	protected function validateRecipient($recipient) {
			// get recipient's profile
			$user = new UserProfile(null, null, $recipient);
			return $user->userID;
	}

	/**
	 * Insert Users.
	 */
	function InsertUserst($event_art, $eventID) {
		include(WCF_DIR.'lib/form/EcpInsertUserst.class.php');
	}

	/**
	 * Start Event
	 */
	function startEvent($eventID, $event_art, $events_participants, $status_aktuell) {
		include(WCF_DIR.'lib/form/EcpStartEvent.class.php');
	}

	/**
	 * Save Score
	 */
	function saveScore($eventID, $event_art, $akt, $status_aktuell) {
		include(WCF_DIR.'lib/form/EcpSaveScore.class.php');
	}

	/**
	 * Gameday End
	 */
	function gamedayEnd($eventID, $akt) {
		include(WCF_DIR.'lib/form/EcpGamedayEnd.class.php');
	}

	/**
	 * Create Matchplaner.
	 */
	function createMatchplaner($eventID, $contacts, $boardID, $current_gameday, $event_art) {
		include(WCF_DIR.'lib/form/EcpCreateMatchplaner.class.php');
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables()
	{

		$eventID = intval($_GET['id']);
		if(isset($_GET['akt'])) $this->akt = intval($_GET['akt']);
		if(isset($_GET['aktion'])) $aktion = $_GET['aktion'];
		if(isset($_GET['gamenr'])) $gamenr = intval($_GET['gamenr']);
		if(isset($_GET['gameid'])) $gameid = intval($_GET['gameid']);
		$sid = SID_ARG_2ND;
		$errorField = '';
		$errorType = '';
		$error = array();

					//// Editieren ////
		if(isset($aktion) && $aktion == 'edit' && isset($gamenr) && isset($eventID)) {
			$sql = "UPDATE events_paarungen
							SET	scoreID_1 = '',
									scoreID_2 = '',
									winnerID = ''
							WHERE 	event_ID = '".$eventID."' && id = '".$gamenr."'";
			WCF::getDB()->sendQuery($sql);
		}

				//// Liest das Event aus ////
		$sql = "SELECT	name, art, status, boardID, contacts, participants, current_gameday
			FROM		events
			WHERE id='$eventID'";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$event_art = $row['art'];
			$boardID = $row['boardID'];
			$contacts = $row['contacts'];
			$name = $row['name'];
			$status_aktuell = $row['status'];
			$events_participants = $row['participants'];
			$current_gameday = $row['current_gameday'];
			$events[] = $row;
		}


				//// InsertUser ////
		if(isset($_POST['absenden']))
			$this->InsertUserst("$event_art", "$eventID");
				//// InsertUser ////
		elseif(isset($_POST['start'])) {
			$this->startEvent("$eventID", "$event_art", "$events_participants", $status_aktuell);
			$this->createMatchplaner("$eventID", "$contacts", "$boardID", "$current_gameday", "$event_art");
		}
				//// Ergebnisse eintragen ////
		elseif(isset($_POST['ergeintrag'])) {
			$this->saveScore("$eventID", "$event_art", "$this->akt", "$status_aktuell");
		}
				//// Spieltagsbeendigung ////
		elseif(isset($_POST['beenden']) && isset($this->akt)) {
			$this->gamedayEnd("$eventID", "$this->akt");
			$this->createMatchplaner("$eventID", "$contacts", "$boardID", "$current_gameday", "$event_art");
		}
				//// Spieler austausch ////
		elseif(isset($_POST['austausch']) && $status_aktuell == 3) {
			include(WCF_DIR.'lib/form/EcpAustausch.class.php');
		}
				//// Team austausch ////
		elseif(isset($_POST['teamaustausch']) && $status_aktuell == 3) {
			include(WCF_DIR.'lib/form/EcpTeamAustausch.class.php');
		}
				//// Gruppenphase beenden ////
		elseif(isset($_POST['gruppenphasebeenden']) && isset($akt)) {
			include(WCF_DIR.'lib/form/EcpGroupEnd.class.php');
		}
				//// Event beenden ////
		elseif(isset($_POST['eventbeenden'])) {
			include(WCF_DIR.'lib/form/EcpEventEnd.class.php');
		}
				//// Ausgabe //// 
		if(!isset($_POST['absenden']))
			{
					//// Spieler Eintragung Single Liga ////
				if($status_aktuell == 1 && $event_art == 1)
					{
							//// Genügend Spieler??? ////
						$status2 = $this->enoughGamer("events_user","$eventID","$events_participants");
		
							//// Liest die angemeldeten Spieler aus ////
						$sql2 = "SELECT	events_user.user_ID, wcf1_user.username
							FROM		events_user
							LEFT JOIN   wcf1_user
							  ON (wcf1_user.userID=events_user.user_ID)
							WHERE events_user.event_id='$eventID'
							ORDER BY events_user.ID ASC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							{
								$user_ID[] = $row2['user_ID'];
								$user_username[] = $row2['username'];
							}
							//// Erstelle die Auflistung der Spieler-Anmeldefelder ////
						$teilnehmer_html = '';
						$array_zahl = 0;
						for ($i=1; $i < ($events_participants + 1); $i++)
							{
								if(isset($_GET['userid']))$userid_url = intval($_GET['userid']);
								if(isset($_GET['akt'])) $akt_url = $_GET['akt'];
								if(isset($user_username[$array_zahl])) $username_url = $user_username[$array_zahl];
								
												//// Alle User Liste ////
								if(!isset($user_ID[$array_zahl]))
									{
										$Spieler = $this->createInput("","$i");
									}
								elseif(isset($userid_url) && $userid_url == $user_ID[$array_zahl] && $akt_url == "edit")
									{
										$Spieler = $this->createInput("$username_url","$i");
									}
								elseif(isset($userid_url) && $userid_url == $user_ID[$array_zahl] && $akt_url == "delete")
									{
										$Spieler = $this->createInput("","$i");
										$sql = "DELETE FROM events_user
											WHERE user_ID='$userid_url' && event_id='$eventID'";
										WCF::getDB()->sendQuery($sql);
									}
								else
									{
										$Spieler = $user_username[$array_zahl];
									}
								if(isset($user_ID[$array_zahl])) {
										$userid = $user_ID[$array_zahl];
										$teilnehmer_html .="
											<tr class=\"container-1\">
												<td class=\"statsnormal\">$i</td>
												<td class=\"statsnormal\"><div align=\"center\">$Spieler</div></td>
												<td class=\"statsnormal\"><a href=\"index.php?form=EcpAdminDetail&id=".$eventID."&akt=edit&userid=".$userid."$sid\"><img src=\"../wcf/icon/editS.png\" title=\"Editieren\" /></a>
													/<a href=\"index.php?form=EcpAdminDetail&id=".$eventID."&akt=delete&userid=".$userid."$sid\"><img src=\"../wcf/icon/deleteS.png\" title=\"L&ouml;schen\" /></a></td>
											</tr>";
								}
								else
									{
										$teilnehmer_html .="
											<tr class=\"container-1\">
												<td class=\"statsnormal\">$i</td>
												<td class=\"statsnormal\"><div align=\"center\">$Spieler</div></td>
												<td class=\"statsnormal\"> </td>
											</tr>";
									}
								$array_zahl++;
								unset($Spieler);
							}

						parent::assignVariables();
						if(mysql_num_rows($result) && isset($Status2))
							{
								WCF::getTPL()->assign(array(
									'status_aktuell' => $status_aktuell,
									'name' => $name,
									'id' => $eventID,
									'Status2' => $Status2,
									'i' => $i,
									'recipient' => $this->recipient,
									'events' => $events,
								));
							}
						elseif(mysql_num_rows($result))
							{
								WCF::getTPL()->assign(array(
									'status_aktuell' => $status_aktuell,
									'name' => $name,
									'id' => $eventID,
									'i' => $i,
									'recipient' => $this->recipient,
									'events' => $events,
									'teilnehmer_html' => $teilnehmer_html
								));
							}
					}
					//// Spieler Eintragung Team Liga ////
				elseif($status_aktuell == 1 && $event_art == 2)
					{
							//// Genügend Spieler??? ////
						$status2 = $this->enoughGamer("events_team","$eventID","$events_participants");
		
							//// Liest die angemeldeten Spieler aus ////
						$sql2 = "SELECT	events_user.user_ID, wcf1_user.username, events_team.teamname, events_team.id
							FROM		events_user
							LEFT JOIN   wcf1_user
							  ON (wcf1_user.userID=events_user.user_ID)
							LEFT JOIN   events_team
							  ON (events_team.id=events_user.team_ID)
							WHERE events_user.event_id='$eventID' && events_team.event_id = '$eventID'
							ORDER BY events_team.teamname ASC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							{
								if($row2['teamname'] == 'Freilos')
									{
										$row2['user_ID'] = 0;
										$row2['username'] = 'Freilos';
									}
								$user_ID[] = $row2['user_ID'];
								$user_username[] = $row2['username'];
								$team_name[] = $row2['teamname'];
								$team_id[] = $row2['id'];
							}
							//// Erstelle die Auflistung der Team-Anmeldefelder ////
						$teilnehmer_html = '';
						$array_zahl_1 = 0;
						$array_zahl_2 = 1;
						$array_zahl_3 = 0;
						$k = 0;

						if(isset($_GET['userid_1'])) $userid_1_url = intval($_GET['userid_1']);
						if(isset($_GET['userid_2'])) $userid_2_url = intval($_GET['userid_2']);
						if(isset($_GET['team'])) $team_url = intval($_GET['team']);
						if(isset($_GET['akt'])) $akt_url = $_GET['akt'];
							else $akt_url = 0;
						if(isset($user_username[$array_zahl_1])) $username_url_1 = $user_username[$array_zahl_1];
						if(isset($user_username[$array_zahl_2])) $username_url_2 = $user_username[$array_zahl_2];

						for ($i=1; $i < ($events_participants * 2 + 1); $i++)
							{

								$j = $i + 1;
								$k++;
								$sid_arg = SID_ARG_2ND_NOT_ENCODED;
												//// Alle User Listen Spieler 1////
								if(!isset($user_ID[$array_zahl_1]) || (isset($userid_1_url) && $userid_1_url == $user_ID[$array_zahl_1] && $akt_url == "edit")) {
										$Spieler_1 = $this->createInput("","$i");
								}
											//// Löschen ////
								elseif(isset($userid_1_url) && $userid_1_url == $user_ID[$array_zahl_1] && isset($team_url) && $akt_url == "delete") {
										$Spieler_1 = $this->createInput("","$i");
										$sql = "DELETE FROM events_user
											WHERE (user_ID='$userid_1_url' || user_ID='$userid_2_url') && event_id='$eventID'";
										WCF::getDB()->sendQuery($sql);
										$sql2 = "DELETE FROM events_team
											WHERE id='$team_url' && event_id='$eventID'";
										WCF::getDB()->sendQuery($sql2);
								}
								else $Spieler_1 = $user_username[$array_zahl_1];

												//// Alle User Listen Spieler 2 ////
								if(!isset($user_ID[$array_zahl_2]) || (isset($userid_2_url) && $userid_2_url == $user_ID[$array_zahl_2] && $akt_url == 'edit')) {
										$Spieler_2 = $this->createInput("","$j");
								}
								elseif(isset($userid_2_url) && $userid_2_url == $user_ID[$array_zahl_2] && $akt_url == "delete") {
										$Spieler_2 = $this->createInput("","$j");
								}
								else $Spieler_2 = $user_username[$array_zahl_2];

// Hier FEHLER
								if(($akt_url == 'delete' || $akt_url == 'edit') && $akt_url != '0' && $userid_2_url == $user_ID[$array_zahl_2])
									$team_name[$array_zahl_3] = "<input type=\"text\" class=\"inputText\" name=\"team_$i\" value=\"\"/>";
								elseif(!isset($team_name[$array_zahl_3]))
									$team_name[$array_zahl_3] = "<input type=\"text\" class=\"inputText\" name=\"team_$i\" value=\"\"/>";
								
								if(isset($user_ID[$array_zahl_1]) && isset($user_ID[$array_zahl_2]))
									{
										$userid_1 = $user_ID[$array_zahl_1];
										$userid_2 = $user_ID[$array_zahl_2];
									}
								$teilnehmer_html .="
									<tr class=\"container-1\">
										<td class=\"statsnormal\">$k</td>
										<td class=\"statsnormal\"><div align=\"center\">$team_name[$array_zahl_3]</div></td>
										<td class=\"statsnormal\"><div align=\"center\">$Spieler_1</div></td>
										<td class=\"statsnormal\"><div align=\"center\">$Spieler_2</div></td>
										<td class=\"statsnormal\">";
								if(isset($user_ID[$array_zahl_1]) && isset($user_ID[$array_zahl_2]))
									$teilnehmer_html .="<a href=\"index.php?form=EcpAdminDetail&id=".$eventID."&akt=edit&userid_1=".$userid_1."&userid_2=".$userid_2."$sid\"><img src=\"wcf/icon/editS.png\" title=\"Editieren\" /></a>
											/<a href=\"index.php?form=EcpAdminDetail&id=".$eventID."&akt=delete&userid_1=".$userid_1."&userid_2=".$userid_2."&team=".$team_id[$array_zahl_3]."$sid\"><img src=\"wcf/icon/deleteS.png\" title=\"L&ouml;schen\" /></a>";
								$teilnehmer_html .= '</td>
													</tr>';

								$array_zahl_1 = $array_zahl_1 + 2;
								$array_zahl_2 = $array_zahl_2 + 2;
								$array_zahl_3 = $array_zahl_3 + 2;
								unset($Spieler);
								$i++;
							}
						
						parent::assignVariables();
						WCF::getTPL()->assign(array(
							'status_aktuell' => $status_aktuell,
							'name' => $name,
							'id' => $eventID,
							'Status2' => $this->Status2,
							'i' => $i,
							'recipient' => $this->recipient,
							'events' => $events,
							'teilnehmer_html' => $teilnehmer_html
						));
				}
							//// Ausgabe Status 3 ////
				elseif($status_aktuell == 3 && !isset($_POST['ergeintrag']) && !isset($_POST['austausch']))
					{
								//// Spieler Austausch - Ausgabe /////
						$sql2 = "SELECT	events_user.user_ID
							FROM		events_user
							WHERE events_user.event_id='$eventID' && bis='0'";
						if($event_art == 2)
							$sql2 .= " && events_user.user_ID != '0'";
						
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2)) {
							$postOwner = new User($row2['user_ID']);
							$row2['username'] = $postOwner->username;
							$spieler[] = $row2;
						}

							//// Teamliga-Team Austausch- Hol die Teams aus der DB ////
						if($event_art == 2){
							$sql2 = "SELECT	teamname,id
								FROM		events_team
								WHERE event_id='$eventID' && bis='0'";
							
							$result2 = WCF::getDB()->sendQuery($sql2);
							while ($row15 = WCF::getDB()->fetchArray($result2)) {
								$teams[] = $row15;
							}
						}
						else $teams = '';

						$sql2 = "SELECT events_paarungen.id, events_paarungen.userID1, events_paarungen.userID2,
							events_paarungen.scoreID_1, events_paarungen.scoreID_2, events_paarungen.winnerID, events_paarungen.gamenumber";
						
						if($event_art == 2)
							$sql2 .= ", events_paarungen.teamID1, events_paarungen.teamID2, events_paarungen.event_group";
						$sql2 .= " FROM		events
							LEFT JOIN   events_paarungen
							  ON (events_paarungen.gameday='$this->akt')
							WHERE events.id='$eventID' && events_paarungen.event_ID='$eventID'";
						if($event_art == 1) $sql2 .= "ORDER BY events_paarungen.id ASC";
						elseif($event_art == 2) $sql2 .= "ORDER BY event_group ASC, teamID1 ASC";

						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							{	
								$userid1 = $row2['userID1'];
								$userid1Array = new User($userid1);
								$row2['username_1'] = $userid1Array->username;
								$userid2 = $row2['userID2'];
								$userid2Array = new User($userid2);
								$row2['username_2'] = $userid2Array->username;

										//// Eingabefeld ////
								if($row2['scoreID_1'] == 0 && $row2['scoreID_2'] == 0 && $row2['userID1'] != 0 && $row2['userID2'] != 0 && $row2['scoreID_1'] != 'x')
									{
										$game_id = $row2['id'];
										$row2['Eingabefeld_1'] = "<input type=\"text\" name=\"1_$game_id\" size=\"1\" maxlength=\"1\">";
										$row2['Eingabefeld_2'] = "<input type=\"text\" name=\"2_$game_id\" size=\"1\" maxlength=\"1\">";
									}
								else
									{
										$scoreID_1 = $row2['scoreID_1'];
										$scoreID_2 = $row2['scoreID_2'];
										$row2['Eingabefeld_1'] = $scoreID_1;
										$row2['Eingabefeld_2'] = $scoreID_2;
									}


								$sql3 = "SELECT	wcf1_user.userID, wcf1_user.userOnlineGroupID, wcf1_group.userOnlineMarking
									FROM		wcf1_user
									LEFT JOIN   wcf1_group
									  ON (wcf1_group.groupID=wcf1_user.userOnlineGroupID)
									WHERE wcf1_user.userID='$userid1' || wcf1_user.userID='$userid2'";
								$result3 = WCF::getDB()->sendQuery($sql3);
								while ($row3 = WCF::getDB()->fetchArray($result3))
									{
										if($row3['userID'] == $userid1) {
											$row2['online_1'] = $row3['userOnlineMarking'];
											$row2['username_1'] = str_replace ( '%s',$row2['username_1'], $row2['online_1'] );
										}
										elseif($row3['userID'] == $userid2) {
											$row2['online_2'] = $row3['userOnlineMarking'];
											$row2['username_2'] = str_replace ( '%s',$row2['username_2'], $row2['online_2'] );
										}
									}
	
									//// Freilose Team Liga////
								if($row2['userID1'] == 0 && $event_art == 2) $row2['username_1'] = 'Freilos';
								if($row2['userID2'] == 0 && $event_art == 2) $row2['username_2'] = 'Freilos';

								$paarungen[] = $row2;
							}

								//// Letzter Spieltag ////
						$sql2 = "SELECT	gameday
							FROM		events_paarungen
							WHERE event_ID='$eventID'
							ORDER BY gameday DESC LIMIT 1";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							$last_gameday = $row2['gameday'];
						if(!isset($last_gameday)) $last_gameday = 0;

				        WCF::getTPL()->assign(array(
				            'last_gameday' => $last_gameday,
							'spieler' => $spieler,
							'teams' => $teams,
							'akt' => $this->akt,
							'status_aktuell' => $status_aktuell,
							'id' => $eventID,
							'name' => $name,
							'paarungen' => $paarungen,
							'aktuellerSpieltag' => $current_gameday,
							'spieltag' => $current_gameday,
							'event_art' => $event_art
						));
					}

							//// Teambewerb KO Runde ////
				elseif($status_aktuell == 4 && !isset($_POST['ergeintrag']) && !isset($_POST['austausch']) && $event_art == 2)
					{
								//// Spieler Austausch - Ausgabe /////
						$sql2 = "SELECT	events_user.user_ID, wcf1_user.username
							FROM		events_user
							LEFT JOIN   wcf1_user
							  ON (wcf1_user.userID=events_user.user_ID)
							WHERE events_user.event_id='$eventID'
							ORDER BY wcf1_user.username  ASC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							$spieler[] = $row2;

						$sql2 = "SELECT	events.name, events.current_gameday, events_paarungen.userID1, events_paarungen.id, events_paarungen.userID2,
								events_paarungen.scoreID_1, events_paarungen.scoreID_2, events_paarungen.winnerID, events_paarungen.gamenumber, events_paarungen.teamID1, 
								events_paarungen.teamID2, events_paarungen.event_group
							FROM		events
							LEFT JOIN   events_paarungen
							  ON (events_paarungen.event_round > 2)
							WHERE events.id='$eventID' && events_paarungen.event_ID='$eventID'
							ORDER BY events_paarungen.id ASC";

						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							{	
								$userid1 = $row2['userID1'];
								$userid2 = $row2['userID2'];

								if($row2['scoreID_1'] == 0 && $row2['scoreID_2'] == 0)
									{
										$game_id = $row2['id'];
										$row2['Eingabefeld_1'] = "<input type=\"text\" name=\"1_$game_id\" size=\"1\" maxlength=\"1\">";
										$row2['Eingabefeld_2'] = "<input type=\"text\" name=\"2_$game_id\" size=\"1\" maxlength=\"1\">";
									}
								else
									{
										$scoreID_1 = $row2['scoreID_1'];
										$scoreID_2 = $row2['scoreID_2'];
										$row2['Eingabefeld_1'] = $scoreID_1;
										$row2['Eingabefeld_2'] = $scoreID_2;
									}
	
								$sql3 = "SELECT	wcf1_user.username, wcf1_user.userOnlineGroupID, wcf1_group.userOnlineMarking
									FROM		wcf1_user
									LEFT JOIN   wcf1_group
									  ON (wcf1_group.groupID=wcf1_user.userOnlineGroupID)
									WHERE wcf1_user.userID='$userid1'";
								$result3 = WCF::getDB()->sendQuery($sql3);
								while ($row3 = WCF::getDB()->fetchArray($result3))
									{
										$row2['username_1'] = $row3['username'];
										$row2['online_1'] = $row3['userOnlineMarking'];
										$row2['username_1'] = str_replace ( '%s',$row2['username_1'], $row2['online_1'] );
									}
	
								$sql4 = "SELECT	wcf1_user.username, wcf1_user.userOnlineGroupID, wcf1_group.userOnlineMarking
									FROM		wcf1_user
								LEFT JOIN   wcf1_group
									  ON (wcf1_group.groupID=wcf1_user.userOnlineGroupID)
									WHERE wcf1_user.userID='$userid2'";
								$result4 = WCF::getDB()->sendQuery($sql4);
								while ($row4 = WCF::getDB()->fetchArray($result4))
									{
										$row2['username_2'] = $row4['username'];
										$row2['online_2'] = $row4['userOnlineMarking'];
										$row2['username_2'] = str_replace ( '%s',$row2['username_2'], $row2['online_2'] );
									}
	
								$paarungen[] = $row2;
							}

				        WCF::getTPL()->assign(array(
							'spieler' => $spieler,
							'akt' => $this->akt,
							'status_aktuell' => $status_aktuell,
							'id' => $eventID,
							'name' => $name,
							'paarungen' => $paarungen,
							'event_art' => $event_art
						));
					}

			}
		}

	/**
	 * @see Page::show()
	 */
	public function show()
		{
			include(WCF_DIR.'lib/page/ECPHeadPage.class.php');
			if (!WCF :: getUser()->userID || !WCF :: getUser()->getPermission('mod.ecp.canSeeECP'))
				throw new PermissionDeniedException();
	
			// set active header menu item
			require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');
			HeaderMenu::setActiveMenuItem('wcf.header.menu.zahlenraten');
			
			parent::show();
		}
}
?>