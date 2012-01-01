<?php
$nächster_spieltag = $current_gameday + 1;

if((isset($_POST['matchplaner_start']) && $_POST['matchplaner_start'] == 1) || (isset($_POST['matchplaner']) && $_POST['matchplaner'] == 1)) {
			//// Button für Matchplaner Text/////
	$sql = "SELECT	*
			FROM		events_button
			WHERE eventID='$eventID'";
	$result = WCF::getDB()->sendQuery($sql);
	while ($row = WCF::getDB()->fetchArray($result)) {
		$Button1 = $row['1Button'];
		$Button2 = $row['2Button'];
		$Button3 = $row['3Button'];
		$Button4 = $row['4Button'];
		$Button5 = $row['5Button'];
	}

	if($Button1 == 1)
		$post = ECP_MATCHPLANER_OFL_TEXT;
	elseif($Button2 == 1)
		$post = ECP_MATCHPLANER_1FD_TEXT;
	elseif($Button3 == 1)
		$post = ECP_MATCHPLANER_2FD_TEXT;
	elseif($Button4 == 1)
		$post = ECP_MATCHPLANER_3FD_TEXT;
	elseif($Button5 == 1)
		$post = ECP_MATCHPLANER_TEAM_TEXT;

			//// Matchplaner erstellen ////
	$postOwner = new User($contacts);
	$contact_name = $postOwner->username;

	if($event_art == 1)
		$sql2 = "SELECT	userID1, userID2, gameday, gamenumber
			FROM		events_paarungen
			WHERE event_ID = '$eventID' && gameday = '$nächster_spieltag'";
	elseif($event_art == 2)
		$sql2 = "SELECT	DISTINCT events_paarungen.teamID1, events_paarungen.gameday, events_paarungen.gamenumber, events_paarungen.teamID2
			FROM		events_paarungen
			WHERE event_ID = '$eventID' && gameday = '$nächster_spieltag' && teamID1 != '0' && teamID2 != '0'";
	$result2 = WCF::getDB()->sendQuery($sql2);
	while ($row2 = WCF::getDB()->fetchArray($result2)){
		if($event_art == 1) {
			$userid1 = $row2['userID1'];
			$userid2 = $row2['userID2'];
			$Gamer1New = new User($userid1);
			$username1 = $Gamer1New->username;
	
			$Gamer2New = new User($userid2);
			$username2 = $Gamer2New->username;
		}
		elseif($event_art == 2) {
			$userid1 = $row2['teamID1'];
			$userid2 = $row2['teamID2'];
			$sql3 = "SELECT	teamname
					FROM		events_team
					WHERE id='$userid1'";
			$result3 = WCF::getDB()->sendQuery($sql3);
			while ($row3 = WCF::getDB()->fetchArray($result3))
				$username1 = $row3['teamname'];

			$sql3 = "SELECT	teamname
					FROM		events_team
					WHERE id='$userid2'";
			$result3 = WCF::getDB()->sendQuery($sql3);
			while ($row3 = WCF::getDB()->fetchArray($result3))
				$username2 = $row3['teamname'];

		}
		$gameday = $row2['gameday'];

		$topic = "Spieltag: $gameday - $username1 vs $username2";
		$post = ECP_MATCHPLANER_OFL_TEXT;
	
		$moderationThread = ThreadEditor::create($boardID, WCF::getLanguage()->getLanguageID(), '',
		WCF::getLanguage()->get($topic, array('$topic' => $topic)),$post, $contacts,
			$contact_name, 0, 0, 0, array('enableSmilies' => true, 'enableHtml' => true, 'enableBBCodes' => true, 'showSignature' => true)
		);
		$board = new BoardEditor($boardID);
		$board->addThreads();
		$board->setLastPost($moderationThread);

		WCF::getCache()->clearResource('stat');
		WCF::getCache()->clearResource('boardData');
		WCF::getCache()->clearResource('board');
	}
}

$text = 'Die Parungen wurden erstellt!';
$this->saveform("$text", "$eventID", "$nächster_spieltag");
?>