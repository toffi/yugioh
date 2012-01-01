<?php
	//// Teamliga-Team Austausch- Hol die Teams aus der DB ////
$sql2 = "SELECT	teamname,id
	FROM		events_team
	WHERE event_id='$eventID' && bis='0'";

$result2 = WCF::getDB()->sendQuery($sql2);
while ($row15 = WCF::getDB()->fetchArray($result2)) {
	$teams[] = $row15;
}

foreach($teams as $teams)
	{
		$teamsid = $teams['id'];
		if(isset($_POST["teamname_$teamsid"])) $teamname = $_POST["teamname_$teamsid"];
		if(isset($_POST["team1_$teamsid"])) $spieler1 = $_POST["team1_$teamsid"];
		if(isset($_POST["team2_$teamsid"])) $spieler2 = $_POST["team2_$teamsid"];
		if(isset($_POST["ab_$teamsid"])) $ab = $_POST["ab_$teamsid"];
		if(isset($_POST["games_$teamsid"])) $Games = $_POST["games_$teamsid"];
			else $Games = 0;
		if(isset($_POST["points_$teamsid"])) $Points = $_POST["points_$teamsid"];
			else $Points = 0;
		if(isset($_POST["rates_$teamsid"])) $Rates = $_POST["rates_$teamsid"];
			else $Rates = 0;
		if(isset($_POST["S0_$teamsid"])) $S0 = $_POST["S0_$teamsid"];
			else $S0 = 0;
		if(isset($_POST["s_$teamsid"])) $s = $_POST["s_$teamsid"];
			else $s = 0;
		if(isset($_POST["U_$teamsid"])) $U = $_POST["U_$teamsid"];
			else $U = 0;
		if(isset($_POST["N0_$teamsid"])) $N0 = $_POST["N0_$teamsid"];
			else $N0 = 0;
		if(isset($_POST["n_$teamsid"])) $n = $_POST["n_$teamsid"];
			else $n = 0;

		if(isset($spieler1)) {
			$recipient1 = $this->validateRecipient($spieler1);
			$sql2 = "SELECT	username
				FROM		wcf1_user
				WHERE userID='$recipient1'";
			$result2 = WCF::getDB()->sendQuery($sql2);
			while ($row2 = WCF::getDB()->fetchArray($result2))
				$username_existiert = $row2;
		}

		if(isset($spieler2)) {
			$recipient2 = $this->validateRecipient($spieler2);
			$sql2 = "SELECT	username
				FROM		wcf1_user
				WHERE userID='$recipient2'";
			$result3 = WCF::getDB()->sendQuery($sql2);
			while ($row2 = WCF::getDB()->fetchArray($result2))
				$username_existiert = $row2;
		}

		//// Existiert User und wurde ein Spieltag angegeben??? ////
		if(mysql_num_rows($result2) && mysql_num_rows($result3) && isset($teamname) && isset($ab) && $ab >= 0) {
			$sql = "INSERT INTO events_team
				SET	event_id = '$eventID',
					teamname = '$teamname',
					games = '$Games',
					points= '$Points',
					rates = '$Rates',
					S_0 = '$S0',
					s = '$s',
					U = '$U',
					N_0 = '$N0',
					n = '$n',
					ab='$ab'";
			WCF::getDB()->sendQuery($sql);

			$sql2 = "SELECT	id
				FROM		events_team
				WHERE teamname='$teamname'";
			$result2 = WCF::getDB()->sendQuery($sql2);
			while ($row2 = WCF::getDB()->fetchArray($result2))
				$teamid_neu = $row2['id'];

			$sql = "INSERT INTO events_user
					SET	event_id = '$eventID',
						user_ID = '$recipient1',
						games = '$Games',
						team_ID='$teamid_neu',
						points= '$Points',
						rates = '$Rates',
						S_0 = '$S0',
						s = '$s',
						U = '$U',
						N_0 = '$N0',
						n = '$n',
						ab='$ab'";
			WCF::getDB()->sendQuery($sql);

			$sql = "INSERT INTO events_user
					SET	event_id = '$eventID',
						user_ID = '$recipient2',
						games = '$Games',
						team_ID ='$teamid_neu',
						points= '$Points',
						rates = '$Rates',
						S_0 = '$S0',
						s = '$s',
						U = '$U',
						N_0 = '$N0',
						n = '$n',
						ab='$ab'";
			WCF::getDB()->sendQuery($sql);

			$sql2 = "SELECT	user_ID
				FROM		events_user
				WHERE team_ID='$teamsid'";
			$result2 = WCF::getDB()->sendQuery($sql2);
			while ($row2 = WCF::getDB()->fetchArray($result2))
				{
					if(!isset($spieler1_alt1)) $spieler1_alt1 = $row2['user_ID'];
						else $spieler1_alt2 = $row2['user_ID'];
				}

					/// Spieler 1 auswechseln ////
			$sql = "UPDATE	events_paarungen
				SET	userID1 = '$recipient1', teamID1 = '$teamid_neu'
				WHERE 	event_ID = '$eventID' && gameday >= '$ab' && userID1='$spieler1_alt1'";
			WCF::getDB()->sendQuery($sql);

			$sql = "UPDATE	events_paarungen
				SET	userID2 = '$recipient1', teamID2 = '$teamid_neu'
				WHERE 	event_ID = '$eventID' && gameday >= '$ab' && userID2='$spieler1_alt1'";
			WCF::getDB()->sendQuery($sql);

			$sql = "UPDATE	events_paarungen
				SET	winnerID = '$recipient1'
				WHERE 	event_ID = '$eventID' && winnerID='$spieler1_alt1' && gameday >= '$ab'";
			WCF::getDB()->sendQuery($sql);

					/// Spieler 2 auswechseln ////
			$sql = "UPDATE	events_paarungen
				SET	userID1 = '$recipient2', teamID1 = '$teamid_neu'
				WHERE 	event_ID = '$eventID' && gameday >= '$ab' && userID1='$spieler1_alt2'";
			WCF::getDB()->sendQuery($sql);

			$sql = "UPDATE	events_paarungen
				SET	userID2 = '$recipient2', teamID2 = '$teamid_neu'
				WHERE 	event_ID = '$eventID' && gameday >= '$ab' && userID2='$spieler1_alt2'";
			WCF::getDB()->sendQuery($sql);

			$sql = "UPDATE	events_paarungen
				SET	winnerID = '$recipient2'
				WHERE 	event_ID = '$eventID' && winnerID='$spieler1_alt2' && gameday >= '$ab'";
			WCF::getDB()->sendQuery($sql);
			
			$bis = $ab - 1;

			$sql = "UPDATE	events_user
				SET games = '',
					points= '',
					rates = '',
					S_0 = '',
					s = '',
					U = '',
					N_0 = '',
					n = '',
					bis = '$bis'
				WHERE event_id = '$eventID' && (user_ID='$spieler1_alt1' || user_ID='$spieler1_alt2')";
			WCF::getDB()->sendQuery($sql);

			$sql = "UPDATE	events_team
				SET	games = '',
					points= '',
					rates = '',
					S_0 = '',
					s = '',
					U = '',
					N_0 = '',
					n = '',
					bis = '$bis'
				WHERE event_id = '$eventID' && id = '$teamsid'";
			WCF::getDB()->sendQuery($sql);
		}
	}
$text = '&Auml;nderungen wurden eingetragen!';
$this->saveform("$text", "$eventID", "$this->akt");
?>