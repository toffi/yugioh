<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');

class ECPRoundDetailPage extends AbstractPage {
	public $templateName = 'ECPRoundDetail';

	public function assignVariables() {

		parent::assignVariables();
		include(WCF_DIR.'lib/page/ECPHeadPage.class.php');

		$eventID = intval($_GET['id']);
		$gameID = intval($_GET['gameID']);

				//// Art und Name ////
		$sql = "SELECT	name, art, rules
			FROM		events
					WHERE events.id='$eventID'";
			$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result))
			{
				$event_art = $row['art'];
				$event_name = $row['name'];
				$event_rules = $row['rules'];
			}
				//// Paarungs Details /////
		$sql2 = "SELECT gamenumber, gameday, teamID1, teamID2
			FROM		events_paarungen
			WHERE event_ID='$eventID' && id='$gameID'
			LIMIT 1";
		$result2 = WCF::getDB()->sendQuery($sql2);
		while ($row2 = WCF::getDB()->fetchArray($result2)) {
			$teamID1 = $row2['teamID1'];
			$teamID2 = $row2['teamID2'];
			$spieltag = $row2['gameday'];
					//// Teams /////
			$sql3 = "SELECT teamname
				FROM		events_team
				WHERE id='$teamID1'";
			$result3 = WCF::getDB()->sendQuery($sql3);
			while ($row3 = WCF::getDB()->fetchArray($result3))
				{
					$row2['teamName1'] = $row3['teamname'];
					$teamName1 = $row2['teamName1'];
				}
			if($teamID1 == 0)
				{
					$row2['teamName1'] = 'Freilos';
					$teamName1 = 'Freilos';
				}

			$sql3 = "SELECT teamname
				FROM		events_team
				WHERE id='$teamID2'";
			$result3 = WCF::getDB()->sendQuery($sql3);
			while ($row3 = WCF::getDB()->fetchArray($result3))
				{
					$row2['teamName2'] = $row3['teamname'];
					$teamName2 = $row2['teamName2'];
				}
			if($teamID2 == 0)
				{
					$row2['teamName2'] = 'Freilos';
					$teamName2 = 'Freilos';
				}
					//// Score ////
			$sql5 = "SELECT scoreID_1, scoreID_2
				FROM		events_paarungen
				WHERE event_ID='$eventID' && teamID1='$teamID1' && teamID2='$teamID2'";

			$result5 = WCF::getDB()->sendQuery($sql5);
			while ($row5 = WCF::getDB()->fetchArray($result5)) {
				if(!isset($row2['scoreID_1'])) $row2['scoreID_1'] = 0;
				if(!isset($row2['scoreID_2'])) $row2['scoreID_2'] = 0;
				$row2['scoreID_1'] += $row5['scoreID_1'];
				$row2['scoreID_2'] += $row5['scoreID_2'];
				if(!isset($row2['wins_1'])) $row2['wins_1'] = 0;
				if(!isset($row2['wins_2'])) $row2['wins_2'] = 0;
				if($row5['scoreID_1'] > $row5['scoreID_2']) $row2['wins_1'] += 1;
				elseif($row5['scoreID_1'] < $row5['scoreID_2']) $row2['wins_2'] += 1;
			}

					//// Ansprechpartner /////
			$sql4 = "SELECT	contacts
				FROM		events
				WHERE id='$eventID'";

			$result4 = WCF::getDB()->sendQuery($sql4);
			while ($row4 = WCF::getDB()->fetchArray($result4))
				$contact_ID = $row4['contacts'];
			if(isset($contact_ID))
				{
							$sql3 = "SELECT	wcf1_user.username, wcf1_user.userOnlineGroupID, wcf1_group.userOnlineMarking, wcf1_avatar.avatarID, wcf1_avatar.avatarExtension
								FROM		wcf1_user
								LEFT JOIN   wcf1_group
								  ON (wcf1_group.groupID=wcf1_user.userOnlineGroupID)
								LEFT JOIN   wcf1_avatar
								  ON (wcf1_avatar.userID='$contact_ID')
								WHERE wcf1_user.userID='$contact_ID'
								ORDER BY avatarID DESC LIMIT 1";
					$result3 = WCF::getDB()->sendQuery($sql3);
					while ($row3 = WCF::getDB()->fetchArray($result3)) {
						$contact_name = str_replace ( '%s',$row3['username'], $row3['userOnlineMarking'] );
						$dart = $row3['avatarExtension'];
						$dname = $row3['avatarID'];
						$avartar = "<img src=\"../wcf/images/avatars/avatar-". $dname .".$dart\" />";
					}
				}
			if(!isset($avartar)) $avartar = 5;
			if(!isset($contact_name)) $contact_name = 5;
			if(!isset($contact_ID)) $contact_ID = 5;
			$details[] = $row2;
		}
				//// Allle Paarungen ////
		$sql6 = "SELECT id, userID1, userID2, scoreID_1, scoreID_2, winnerID
			FROM		events_paarungen
			WHERE event_ID='$eventID' && teamID1='$teamID1' && teamID2='$teamID2' && gameday='$spieltag'";
		$result6 = WCF::getDB()->sendQuery($sql6);
		while ($row6 = WCF::getDB()->fetchArray($result6)) {
			$userID1 = $row6['userID1'];
			$userID2 = $row6['userID2'];

					//// User /////
			$sql3 = "SELECT	wcf1_user.username, wcf1_user.userOnlineGroupID, wcf1_group.userOnlineMarking
				FROM		wcf1_user
				LEFT JOIN   wcf1_group
					ON (wcf1_group.groupID=wcf1_user.userOnlineGroupID)
				WHERE wcf1_user.userID='$userID1'";
			$result3 = WCF::getDB()->sendQuery($sql3);
			while ($row3 = WCF::getDB()->fetchArray($result3))
				$row6['username1'] = str_replace ( '%s',$row3['username'], $row3['userOnlineMarking'] );
				if($teamID1 == 0) $row6['username1'] = 'Freilos';

			$sql3 = "SELECT	wcf1_user.username, wcf1_user.userOnlineGroupID, wcf1_group.userOnlineMarking
				FROM		wcf1_user
				LEFT JOIN   wcf1_group
					ON (wcf1_group.groupID=wcf1_user.userOnlineGroupID)
				WHERE wcf1_user.userID='$userID2'";
			$result3 = WCF::getDB()->sendQuery($sql3);
			while ($row3 = WCF::getDB()->fetchArray($result3))
				$row6['username2'] = str_replace ( '%s',$row3['username'], $row3['userOnlineMarking'] );
				if($teamID2 == 0) $row6['username2'] = 'Freilos';

			$username1 = $row6['username1'];
			$username2 = $row6['username2'];
			if($row6['scoreID_1'] > $row6['scoreID_2']) $row6['username1'] = "<b>$username1</b>";
			if($row6['scoreID_1'] < $row6['scoreID_2']) $row6['username2'] = "<b>$username2</b>";

			$row6['team1'] = $teamName1;
			$row6['team2'] = $teamName2;
			$paarungen[] = $row6;
		}

        WCF::getTPL()->assign(array(
            'eventID' => $eventID,
            'event_name' => $event_name,
            'spieltag' => $spieltag,
            'paarungen' => $paarungen,
            'details' => $details,
            'contact_ID' => $contact_ID,
            'avartar' => $avartar,
            'contact_name' => $contact_name,
            'event_rules' => $event_rules
		));
	}
	/**
	 * @see Page::show()
	 */

	public function show() {
		// set active header menu item
		require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');
		HeaderMenu::setActiveMenuItem('wcf.header.menu.portal');
		
		parent::show();
	}
}
?>
