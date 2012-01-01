<?php
$sql2 = "SELECT	events_user.user_ID
	FROM		events_user
	WHERE events_user.event_id='$eventID'";
$result2 = WCF::getDB()->sendQuery($sql2);
while ($row2 = WCF::getDB()->fetchArray($result2))
	$spieler[] = $row2;

foreach($spieler as $spieler)
	{
		$spielerid = $spieler['user_ID'];
		if(isset($_POST["recipient_$spielerid"])) $recipient = $_POST["recipient_$spielerid"];
        if(isset($recipient) && empty($recipient)) continue;

		if(isset($_POST["ab_$spielerid"])) $ab = $_POST["ab_$spielerid"];
		if(isset($_POST["games_$spielerid"])) $Games = $_POST["games_$spielerid"];
			else $Games = 0;
		if(isset($_POST["points_$spielerid"])) $Points = $_POST["points_$spielerid"];
			else $Points = 0;
		if(isset($_POST["rates_$spielerid"])) $Rates = $_POST["rates_$spielerid"];
			else $Rates = 0;
		if(isset($_POST["S0_$spielerid"])) $S0 = $_POST["S0_$spielerid"];
			else $S0 = 0;
		if(isset($_POST["s_$spielerid"])) $s = $_POST["s_$spielerid"];
			else $s = 0;
		if(isset($_POST["U_$spielerid"])) $U = $_POST["U_$spielerid"];
			else $U = 0;
		if(isset($_POST["N0_$spielerid"])) $N0 = $_POST["N0_$spielerid"];
			else $N0 = 0;
		if(isset($_POST["n_$spielerid"])) $n = $_POST["n_$spielerid"];
			else $n = 0;

		if(isset($_POST["recipient_$spielerid"])) {
			$recipientID = $this->validateRecipient($recipient);
			$sql2 = "SELECT	username
				FROM		wcf1_user
				WHERE userID=".$recipientID;
			$result2 = WCF::getDB()->sendQuery($sql2);
			while ($row2 = WCF::getDB()->fetchArray($result2))
				$username_existiert = $row2;

            // Check if user already in the event
			$sql3 = "SELECT	user_ID
				FROM		events_user
				WHERE user_ID=".$recipientID."
                    AND event_id=".$eventID;
			$result3 = WCF::getDB()->sendQuery($sql3);
			while ($row3 = WCF::getDB()->fetchArray($result3))
			     $istImEvent = $row3['user_ID'];
		}

		//// Existiert User und wurde ein Spieltag angegeben??? ////
		if(isset($username_existiert['username']) && !isset($istImEvent) && isset($ab) && $ab > 0) {
			$sql = "UPDATE	events_paarungen
				SET	userID1 = ".$recipientID."
				WHERE 	event_ID = ".$eventID." && userID1=".$spielerid." && gameday >= ".$ab;
			WCF::getDB()->sendQuery($sql);
			
			$sql = "UPDATE	events_paarungen
				SET	userID2 = ".$recipientID."
				WHERE 	event_ID = ".$eventID." && userID2=".$spielerid." && gameday >= ".$ab;
			WCF::getDB()->sendQuery($sql);
			
			$sql = "UPDATE	events_paarungen
				SET	winnerID = ".$recipientID."
				WHERE 	event_ID = ".$eventID." && winnerID=".$spielerid." && gameday >= ".$ab;
			WCF::getDB()->sendQuery($sql);
			
			$bis = $ab - 1;

					//// Teamliga /////
			if($event_art == 2) {
					$sql2 = "SELECT	team_ID
						FROM		events_user
						WHERE event_ID = ".$eventID." && user_ID = ".$spielerid;
					$result2 = WCF::getDB()->sendQuery($sql2);
					while ($row2 = WCF::getDB()->fetchArray($result2))
						$team_ID = $row2['team_ID'];
			}

			$sql = "UPDATE	events_user
				SET	team_ID = '',
					games = '',
					points= '',
					rates = '',
					S_0 = '',
					s = '',
					U = '',
					N_0 = '',
					n = '',
					bis = ".$bis."
				WHERE event_id = ".$eventID." && user_ID=".$spielerid;
			WCF::getDB()->sendQuery($sql);

			$sql = "INSERT INTO events_user
				SET	event_id = ".$eventID.",
					user_ID = ".$recipientID.",
					games = ".$Games.",";
			if($event_art == 2)
				$sql .= " team_ID=".$team_ID.", ";
			$sql .= "points= ".$Points.",
				rates = ".$Rates.",
				S_0 = ".$S0.",
				s = ".$s.",
				U = ".$U.",
				N_0 = ".$N0.",
				n = ".$n.",
				ab=".$ab;
			WCF::getDB()->sendQuery($sql);
		}
        if(!mysql_num_rows($result2)) {
            throw new NamedUserException("Der Benutzer ".$recipient." existiert nicht!");
        }
        elseif(isset($istImEvent)) {
            throw new NamedUserException("Der Benutzer ".$username_existiert['username']." ist bereits im Event vorhanden!");
        }
        elseif(isset($ab) && $ab == 0) {
            throw new NamedUserException('Die Auswechslung benötigt einen Spieltag, ab den sie gültig ist!');
        }
        unset($username_existiert);
        unset($istImEvent);
        unset($ab);
	}
$text = '&Auml;nderungen wurden eingetragen!';
$this->saveform($text, $eventID, $this->akt);
?>