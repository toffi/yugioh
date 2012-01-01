<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');

/**
 * @author	SpIkE2
 */

class EcpTeambewerbEintragForm extends AbstractPage {
	public $templateName = 'ECPTeambewerbEintrag';

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables()
	{
		include(WCF_DIR.'lib/page/ECPHeadPage.class.php');

		if(!isset($_POST['beenden']))
			{
		
				$sql = "SELECT	id, teamname FROM events_team WHERE event_id='15' ORDER BY teamname ASC";
				$result = WCF::getDB()->sendQuery($sql);
				while ($row = WCF::getDB()->fetchArray($result)) {
					$teamid = $row['id'];
					$teamname = $row['teamname'];
					if(!isset($team)) $team = "<option value=\"0\">Freilos</option>";
					$team .= "<option value=\"$teamid\">$teamname</option>";
				}
		
				WCF::getTPL()->assign(array(
					'team' => $team
				));
			}
		elseif(isset($_POST['beenden']))
			{
				if(isset($_POST['HRrunde'])) $HRrunde = addslashes($_POST['HRrunde']);
				if(isset($_POST['group'])) $group = addslashes($_POST['group']);
				if(isset($_POST['gameday'])) $gameday = addslashes($_POST['gameday']);
				if(isset($_POST['gamenumber'])) $gamenumber = addslashes($_POST['gamenumber']);
				if(isset($_POST['team1'])) $team1 = addslashes($_POST['team1']);
				if(isset($_POST['team2'])) $team2 = addslashes($_POST['team2']);

				if($_POST['team1'] != 0 && $_POST['team2'] != 0)
					{
						$sql2 = "SELECT user_ID
							FROM events_user 
							WHERE event_id = '15' && team_ID='$team1'
							ORDER BY user_ID DESC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							$user1 = $row2['user_ID'];
		
						$sql2 = "SELECT user_ID
							FROM events_user 
							WHERE event_id = '15' && team_ID='$team1'
							ORDER BY user_ID ASC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							$user2 = $row2['user_ID'];
		
						$sql2 = "SELECT user_ID
							FROM events_user 
							WHERE event_id = '15' && team_ID='$team2'
							ORDER BY user_ID DESC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							$user3 = $row2['user_ID'];
		
						$sql2 = "SELECT user_ID
							FROM events_user 
							WHERE event_id = '15' && team_ID='$team2'
							ORDER BY user_ID ASC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							$user4 = $row2['user_ID'];
		
						$sql2 = "SELECT gamenumber
							FROM events_paarungen
							WHERE event_id = '15' && event_group='$group'
							ORDER BY gamenumber DESC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							$gamenumber = $row2['gamenumber'];
						if(!isset($gamenumber)) $gamenumber = 0;
						$gamenumber += 1;
		
						$sql = "INSERT INTO events_paarungen
							SET	event_ID = '15',
							event_round = '$HRrunde',
							event_group = '$group',
							gameday = '$gameday',
							gamenumber = '$gamenumber',
							teamID1 = '$team1',
							teamID2 = '$team2',
							userID1 = '$user1',
							userID2 = '$user3'";
						WCF::getDB()->sendQuery($sql);
		
						$sql = "INSERT INTO events_paarungen
							SET	event_ID = '15',
							event_round = '$HRrunde',
							event_group = '$group',
							gameday = '$gameday',
							gamenumber = '$gamenumber',
							teamID1 = '$team1',
							teamID2 = '$team2',
							userID1 = '$user2',
							userID2 = '$user4'";
						WCF::getDB()->sendQuery($sql);
		
						$sql = "INSERT INTO events_paarungen
							SET	event_ID = '15',
							event_round = '$HRrunde',
							event_group = '$group',
							gameday = '$gameday',
							gamenumber = '$gamenumber',
							teamID1 = '$team1',
							teamID2 = '$team2',
							userID1 = '$user1',
							userID2 = '$user4'";
						WCF::getDB()->sendQuery($sql);
		
						$sql = "INSERT INTO events_paarungen
							SET	event_ID = '15',
							event_round = '$HRrunde',
							event_group = '$group',
							gameday = '$gameday',
							gamenumber = '$gamenumber',
							teamID1 = '$team1',
							teamID2 = '$team2',
							userID1 = '$user2',
							userID2 = '$user3'";
						WCF::getDB()->sendQuery($sql);
		
						$sid = SID_ARG_2ND;
						WCF::getTPL()->assign(array(
							'url' => "index.php?form=EcpTeambewerbEintrag".$sid,
							'message' => WCF::getLanguage()->get('Die Paarung wurde erfolgreich &uuml;bernommen!')
						));
						WCF::getTPL()->display('redirect');
						exit;
				}
				elseif($_POST['team1'] == 0)
					{
						$user1 = 0;
		
						$user2 = 0;

						$sql2 = "SELECT user_ID
							FROM events_user 
							WHERE event_id = '15' && team_ID='$team2'
							ORDER BY user_ID DESC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						$sql2 = "SELECT user_ID
							FROM events_user 
							WHERE event_id = '15' && team_ID='$team2'
							ORDER BY user_ID DESC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							$user3 = $row2['user_ID'];
		
						$sql2 = "SELECT user_ID
							FROM events_user 
							WHERE event_id = '15' && team_ID='$team2'
							ORDER BY user_ID ASC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							$user4 = $row2['user_ID'];

						$sql2 = "SELECT gamenumber
							FROM events_paarungen
							WHERE event_id = '15' && event_group='$group'
							ORDER BY gamenumber DESC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							$gamenumber = $row2['gamenumber'];
						if(!isset($gamenumber)) $gamenumber = 0;
						$gamenumber += 1;
		
						$sql = "INSERT INTO events_paarungen
							SET	event_ID = '15',
							event_round = '$HRrunde',
							event_group = '$group',
							gameday = '$gameday',
							gamenumber = '$gamenumber',
							teamID1 = '$team1',
							teamID2 = '$team2',
							userID1 = '$user1',
							userID2 = '$user3'";
						WCF::getDB()->sendQuery($sql);
		
						$sql = "INSERT INTO events_paarungen
							SET	event_ID = '15',
							event_round = '$HRrunde',
							event_group = '$group',
							gameday = '$gameday',
							gamenumber = '$gamenumber',
							teamID1 = '$team1',
							teamID2 = '$team2',
							userID1 = '$user2',
							userID2 = '$user4'";
						WCF::getDB()->sendQuery($sql);
		
						$sql = "INSERT INTO events_paarungen
							SET	event_ID = '15',
							event_round = '$HRrunde',
							event_group = '$group',
							gameday = '$gameday',
							gamenumber = '$gamenumber',
							teamID1 = '$team1',
							teamID2 = '$team2',
							userID1 = '$user1',
							userID2 = '$user4'";
						WCF::getDB()->sendQuery($sql);
		
						$sql = "INSERT INTO events_paarungen
							SET	event_ID = '15',
							event_round = '$HRrunde',
							event_group = '$group',
							gameday = '$gameday',
							gamenumber = '$gamenumber',
							teamID1 = '$team1',
							teamID2 = '$team2',
							userID1 = '$user2',
							userID2 = '$user3'";
						WCF::getDB()->sendQuery($sql);
		
						$sid = SID_ARG_2ND;
						WCF::getTPL()->assign(array(
							'url' => "index.php?form=EcpTeambewerbEintrag".$sid,
							'message' => WCF::getLanguage()->get('Die Paarung wurde erfolgreich &uuml;bernommen!')
						));
						WCF::getTPL()->display('redirect');
						exit;
				}

				elseif($_POST['team2'] == 0)
					{
						$sql2 = "SELECT user_ID
							FROM events_user 
							WHERE event_id = '15' && team_ID='$team1'
							ORDER BY user_ID DESC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						$sql2 = "SELECT user_ID
							FROM events_user 
							WHERE event_id = '15' && team_ID='$team1'
							ORDER BY user_ID DESC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							$user1 = $row2['user_ID'];
		
						$sql2 = "SELECT user_ID
							FROM events_user 
							WHERE event_id = '15' && team_ID='$team1'
							ORDER BY user_ID ASC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							$user2 = $row2['user_ID'];
		
						$user3 = 0;
		
						$user4 = 0;

						$sql2 = "SELECT gamenumber
							FROM events_paarungen
							WHERE event_id = '15' && event_group='$group'
							ORDER BY gamenumber DESC";
						$result2 = WCF::getDB()->sendQuery($sql2);
						while ($row2 = WCF::getDB()->fetchArray($result2))
							$gamenumber = $row2['gamenumber'];
						if(!isset($gamenumber)) $gamenumber = 0;
						$gamenumber += 1;
		
						$sql = "INSERT INTO events_paarungen
							SET	event_ID = '15',
							event_round = '$HRrunde',
							event_group = '$group',
							gameday = '$gameday',
							gamenumber = '$gamenumber',
							teamID1 = '$team1',
							teamID2 = '$team2',
							userID1 = '$user1',
							userID2 = '$user3'";
						WCF::getDB()->sendQuery($sql);
		
						$sql = "INSERT INTO events_paarungen
							SET	event_ID = '15',
							event_round = '$HRrunde',
							event_group = '$group',
							gameday = '$gameday',
							gamenumber = '$gamenumber',
							teamID1 = '$team1',
							teamID2 = '$team2',
							userID1 = '$user2',
							userID2 = '$user4'";
						WCF::getDB()->sendQuery($sql);
		
						$sql = "INSERT INTO events_paarungen
							SET	event_ID = '15',
							event_round = '$HRrunde',
							event_group = '$group',
							gameday = '$gameday',
							gamenumber = '$gamenumber',
							teamID1 = '$team1',
							teamID2 = '$team2',
							userID1 = '$user1',
							userID2 = '$user4'";
						WCF::getDB()->sendQuery($sql);
		
						$sql = "INSERT INTO events_paarungen
							SET	event_ID = '15',
							event_round = '$HRrunde',
							event_group = '$group',
							gameday = '$gameday',
							gamenumber = '$gamenumber',
							teamID1 = '$team1',
							teamID2 = '$team2',
							userID1 = '$user2',
							userID2 = '$user3'";
						WCF::getDB()->sendQuery($sql);
		
						$sid = SID_ARG_2ND;
						WCF::getTPL()->assign(array(
							'url' => "index.php?form=EcpTeambewerbEintrag".$sid,
							'message' => WCF::getLanguage()->get('Die Paarung wurde erfolgreich &uuml;bernommen!')
						));
						WCF::getTPL()->display('redirect');
						exit;
				}
			else 
				{
					die('Fehler');
				}
			}
	}

	/**
	 * @see Page::show()
	 */
	public function show()
		{
			if (!WCF :: getUser()->userID || !WCF :: getUser()->getPermission('mod.ecp.canSeeECP'))
				throw new PermissionDeniedException();
	
			// set active header menu item
			require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');
			HeaderMenu::setActiveMenuItem('wcf.header.menu.zahlenraten');
			
			parent::show();
		}
}
?>