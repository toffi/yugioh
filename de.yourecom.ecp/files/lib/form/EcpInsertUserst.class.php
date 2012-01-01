<?php
		//// Spieler Eintragen ////
		//// Single Liga ///
if($event_art == 1) {
	$anzahl = addslashes($_POST['anzahl']);
	for ($j=1; $j < ($anzahl + 1); $j++) {
		if(!empty($_POST["recipient_$j"])) {
			$Username = StringUtil::trim($_POST["recipient_$j"]);
			$userID = $this->validateRecipient($Username);
			if($userID != 0) {
				$sql = "INSERT INTO events_user
					SET	user_ID = '$userID',
					event_id = '$eventID'";
				WCF::getDB()->sendQuery($sql);
			}
		}
	}
}
		//// Team Liga ////
elseif($event_art == 2) {
	$anzahl = StringUtil::trim($_POST['anzahl']);
	for ($j=1; $j < ($anzahl + 1); $j++) {
		if(!empty($_POST["recipient_$j"])) {
			$l = $j + 1;
			$Username_1 = StringUtil::trim($_POST["recipient_$j"]);
			$Username_2 = StringUtil::trim($_POST["recipient_$l"]);
			$Teamname = StringUtil::trim($_POST["team_$j"]);

			if(isset($_POST["team_$j"]) && $_POST["team_$j"] == 'Freilos') {
				$userID_1 = 0;
				$userID_2 = 0;
			}
			else {
				$userID_1 = $this->validateRecipient($Username_1);
				$userID_2 = $this->validateRecipient($Username_2);
			}

			if(!empty($_POST["team_$j"]) && !empty($_POST["recipient_$l"]) && !empty($_POST["recipient_$j"])) {
				$sql = "INSERT INTO events_team
					SET	event_id = '$eventID',
					teamname = '$Teamname'";
				WCF::getDB()->sendQuery($sql);
				
				$sql = "SELECT	id
					FROM events_team
					WHERE teamname='$Teamname' && event_id='$eventID'";
				$result = WCF::getDB()->sendQuery($sql);
				while ($row = WCF::getDB()->fetchArray($result))
					$team_id = $row['id'];
				
				$sql = "INSERT INTO events_user
					SET	user_ID = '$userID_1',
					team_ID = '$team_id',
					event_id = '$eventID'";
				WCF::getDB()->sendQuery($sql);
				
				$sql = "INSERT INTO events_user
					SET	user_ID = '$userID_2',
					team_ID = '$team_id',
					event_id = '$eventID'";
				WCF::getDB()->sendQuery($sql);
			}
		}
		$j++;
	}
}

$sid = SID_ARG_2ND;
WCF::getTPL()->assign(array(
	'url' => "index.php?form=EcpAdminDetail&id=$eventID".$sid,
	'message' => WCF::getLanguage()->get('Der/Die Spieler wurden erfolgreich &uuml;bernommen!')
));
WCF::getTPL()->display('redirect');
exit;
?>