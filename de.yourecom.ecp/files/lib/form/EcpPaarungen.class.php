<?php
					//// Single Liga ////
if($event_art == 1) {
	$sql4 = "SELECT	user_ID
		FROM		events_user
		WHERE event_id='$eventID'";
	$result4 = WCF::getDB()->sendQuery($sql4);
	
	while ($row4 = WCF::getDB()->fetchArray($result4)) $users[] = $row4;
						
	shuffle ($users);
	
	// Testen ob die Anzahl der Teams gerade ist, wenn nicht das Team "frei" hinzufügen.
	if(count($users) % 2 ){
		array_push($users , 'FREI');
	}
	
	$anz    = count($users);      // Anzahl der User im Array $users
	$paare  = $anz/2;            // Anzahl der möglichen Spielpaare
	$tage  = $anz-1;            // Anzahl der Spieltage pro Runde
	$spiele = $paare*$tage;    // Anzahl der Spiele pro Hin-/Rück-Runde
	$plan  = array();            // Array für den kompletten Spielplan
	$xpos  = $anz-1;            // höchster Key im Array $users
	$tag    = 0;                  // Zähler für Spieltag
	$spnr  = 0;                  // Zähler für Spielnummer
	$sppaar = 0;                // Zähler für Spielpaar
	$i      = 0;                    // Schleifenzähler
	
	for ($tag=1; $tag<$anz; $tag++) {
		array_splice ($users, 1, 1, array(array_pop($users),$users[1]));
		for ($sppaar=0; $sppaar<$paare; $sppaar++) {
			$spnr++;
			// wechseln zwischen G und H -Spiel:
			if (($spnr%$anz!=1) and ($sppaar%2==0)) {
				$hteam = $users[$sppaar]['user_ID'];
				$gteam = $users[$xpos-$sppaar]['user_ID'];
			}
			else {
					$gteam = $users[$sppaar]['user_ID'];
					$hteam = $users[$xpos-$sppaar]['user_ID'];
			}
			$plan[$tag][$spnr]["G"] = $gteam;                // für Hin-Runde
			$plan[$tag][$spnr]["H"] = $hteam;                // für Hin-Runde
			$plan[$tag+$tage][$spnr+$spiele]["G"] = $hteam;  // für Rück-Runde
			$plan[$tag+$tage][$spnr+$spiele]["H"] = $gteam;  // für Rück-Runde
		}
	}
	ksort($plan); // nach Spieltagen sortieren
	$rueck = count($plan)/2 ;
	$Runde = 1;
	
	foreach($plan as $spieltag => $v1) {
		foreach($v1 as $spielnummer => $v2) {
			$H = $plan[$spieltag][$spielnummer]['H'];
			$G = $plan[$spieltag][$spielnummer]['G'];
	
			if ($spieltag == $rueck)
				$Runde = 2;
	
			$sql = "INSERT INTO events_paarungen
				SET	event_ID = '$eventID',
				gamenumber = '$spielnummer',
				event_round = '$Runde',
				gameday = '$spieltag',
				userID1 = '$H',
				userID2 = '$G'";
			WCF::getDB()->sendQuery($sql);
		}
	}
}
				//// Teamliga /////
elseif($event_art == 2 && $status_aktuell != 4) {
	$sql7 = "SELECT	id
		FROM		events_team
		WHERE event_id='$eventID'";
	$result7 = WCF::getDB()->sendQuery($sql7);
	
	while ($row7 = WCF::getDB()->fetchArray($result7))
		$users[] = $row7;

	// Testen ob die Anzahl der Teams gerade ist, wenn nicht das Team "frei" hinzufügen.
	if(count($users) % 2 ){
		array_push($users , 'FREI');
	}

	shuffle ($users);
	$users_A = array_splice ( $users, count($users) / 2 );
	if(count($users_A) % 2 ){
		array_push($users_A, 'FREI');
	}
	$users_B = $users;
	if(count($users_B) % 2 ){
		array_push($users_B, 'FREI');
	}

					//// Gruppe A ////
	$anz    = count($users_A);      // Anzahl der Teams im Array $users
	$paare  = $anz/2;            // Anzahl der möglichen Spielpaare
	$tage  = $anz-1;            // Anzahl der Spieltage pro Runde
	$spiele = $paare*$tage;    // Anzahl der Spiele pro Hin-/Rück-Runde
	$plan  = array();            // Array für den kompletten Spielplan
	$xpos  = $anz-1;            // höchster Key im Array $users
	$tag    = 0;                  // Zähler für Spieltag
	$spnr  = 0;                  // Zähler für Spielnummer
	$sppaar = 0;                // Zähler für Spielpaar
	$i      = 0;                    // Schleifenzähler
	
	for ($tag=1; $tag<$anz; $tag++) {
		array_splice ($users_A, 1, 1, array(array_pop($users_A),$users_A[1]));
		for ($sppaar=0; $sppaar<$paare; $sppaar++) {
			$spnr++;
			// wechseln zwischen G und H -Spiel:
			if (($spnr%$anz!=1) and ($sppaar%2==0)) {
				$hteam = $users_A[$sppaar]['id'];
				$gteam = $users_A[$xpos-$sppaar]['id'];
			}
			else {
					$gteam = $users_A[$sppaar]['id'];
					$hteam = $users_A[$xpos-$sppaar]['id'];
			}
			$plan[$tag][$spnr]["G"] = $gteam;                // für Hin-Runde
			$plan[$tag][$spnr]["H"] = $hteam;                // für Hin-Runde
			$plan[$tag+$tage][$spnr+$spiele]["G"] = $hteam;  // für Rück-Runde
			$plan[$tag+$tage][$spnr+$spiele]["H"] = $gteam;  // für Rück-Runde
		}
	}
	ksort($plan); // nach Spieltagen sortieren
	$rueck = count($plan)/2 ;
	$Runde = 1;
	
	foreach($plan as $spieltag => $v1) {
		foreach($v1 as $spielnummer => $v2) {
			$H = $plan[$spieltag][$spielnummer]['H'];
			$G = $plan[$spieltag][$spielnummer]['G'];
	
			if ($spieltag == $rueck)
				$Runde = 2;
			$group = 'A';

			$sql8 = "SELECT	user_ID
				FROM		events_user
				WHERE event_id='$eventID' && team_ID = '$H'";
			$result8 = WCF::getDB()->sendQuery($sql8);
			while ($row8 = WCF::getDB()->fetchArray($result8))
				$team1[] = $row8;

			$Team1_Spieler1 = $team1[0]['user_ID'];
			$Team1_Spieler2 = $team1[1]['user_ID'];
			unset($team1);
			$sql9 = "SELECT	user_ID
				FROM		events_user
				WHERE event_id='$eventID' && team_ID = '$G'";
			$result9 = WCF::getDB()->sendQuery($sql9);
			while ($row9 = WCF::getDB()->fetchArray($result9))
				$team2[] = $row9;

			$Team2_Spieler1 = $team2[0]['user_ID'];
			$Team2_Spieler2 = $team2[1]['user_ID'];
			unset($team2);

			$sql = "INSERT INTO events_paarungen
				SET	event_ID = '$eventID',
				event_round = '$Runde',
				event_group = '$group',
				gamenumber = '$spielnummer',
				gameday = '$spieltag',
				teamID1 = '$H',
				userID1 = '$Team1_Spieler1',
				teamID2 = '$G',
				userID2 = '$Team2_Spieler1'";
			WCF::getDB()->sendQuery($sql);

			$sql = "INSERT INTO events_paarungen
				SET	event_ID = '$eventID',
				event_round = '$Runde',
				event_group = '$group',
				gamenumber = '$spielnummer',
				gameday = '$spieltag',
				teamID1 = '$H',
				userID1 = '$Team1_Spieler2',
				teamID2 = '$G',
				userID2 = '$Team2_Spieler2'";
			WCF::getDB()->sendQuery($sql);

			$sql = "INSERT INTO events_paarungen
				SET	event_ID = '$eventID',
				event_round = '$Runde',
				event_group = '$group',
				gamenumber = '$spielnummer',
				gameday = '$spieltag',
				teamID1 = '$H',
				userID1 = '$Team1_Spieler1',
				teamID2 = '$G',
				userID2 = '$Team2_Spieler2'";
			WCF::getDB()->sendQuery($sql);

			$sql = "INSERT INTO events_paarungen
				SET	event_ID = '$eventID',
				event_round = '$Runde',
				event_group = '$group',
				gamenumber = '$spielnummer',
				gameday = '$spieltag',
				teamID1 = '$H',
				userID1 = '$Team1_Spieler2',
				teamID2 = '$G',
				userID2 = '$Team2_Spieler1'";
			WCF::getDB()->sendQuery($sql);
		}
	}
					//// Gruppe B ////
	$anz    = count($users_B);      // Anzahl der Teams im Array $users
	$paare  = $anz/2;            // Anzahl der möglichen Spielpaare
	$tage  = $anz-1;            // Anzahl der Spieltage pro Runde
	$spiele = $paare*$tage;    // Anzahl der Spiele pro Hin-/Rück-Runde
	$plan  = array();            // Array für den kompletten Spielplan
	$xpos  = $anz-1;            // höchster Key im Array $users
	$tag    = 0;                  // Zähler für Spieltag
	$spnr  = 0;                  // Zähler für Spielnummer
	$sppaar = 0;                // Zähler für Spielpaar
	$i      = 0;                    // Schleifenzähler
	
	for ($tag=1; $tag<$anz; $tag++) {
		array_splice ($users_B, 1, 1, array(array_pop($users_B),$users_B[1]));
		for ($sppaar=0; $sppaar<$paare; $sppaar++) {
			$spnr++;
			// wechseln zwischen G und H -Spiel:
			if (($spnr%$anz!=1) and ($sppaar%2==0)) {
				$hteam = $users_B[$sppaar]['id'];
				$gteam = $users_B[$xpos-$sppaar]['id'];
			}
			else {
					$gteam = $users_B[$sppaar]['id'];
					$hteam = $users_B[$xpos-$sppaar]['id'];
			}
			$plan[$tag][$spnr]["G"] = $gteam;                // für Hin-Runde
			$plan[$tag][$spnr]["H"] = $hteam;                // für Hin-Runde
			$plan[$tag+$tage][$spnr+$spiele]["G"] = $hteam;  // für Rück-Runde
			$plan[$tag+$tage][$spnr+$spiele]["H"] = $gteam;  // für Rück-Runde
		}
	}
	ksort($plan); // nach Spieltagen sortieren
	$rueck = count($plan)/2 ;
	$Runde = 1;
	
	foreach($plan as $spieltag => $v1) {
		foreach($v1 as $spielnummer => $v2) {
			$H = $plan[$spieltag][$spielnummer]['H'];
			$G = $plan[$spieltag][$spielnummer]['G'];
	
			if ($spieltag == $rueck)
				$Runde = 2;
			$group = 'B';

			$sql8 = "SELECT	user_ID
				FROM		events_user
				WHERE event_id='$eventID' && team_ID = '$H'";
			$result8 = WCF::getDB()->sendQuery($sql8);
			while ($row8 = WCF::getDB()->fetchArray($result8))
				$team1[] = $row8;

			$Team1_Spieler1 = $team1[0]['user_ID'];
			$Team1_Spieler2 = $team1[1]['user_ID'];
			unset($team1);

			$sql9 = "SELECT	user_ID
				FROM		events_user
				WHERE event_id='$eventID' && team_ID = '$G'";
			$result9 = WCF::getDB()->sendQuery($sql9);
			while ($row9 = WCF::getDB()->fetchArray($result9))
				$team2[] = $row9;

			$Team2_Spieler1 = $team2[0]['user_ID'];
			$Team2_Spieler2 = $team2[1]['user_ID'];
			unset($team2);

			$sql = "INSERT INTO events_paarungen
				SET	event_ID = '$eventID',
				event_round = '$Runde',
				event_group = '$group',
				gamenumber = '$spielnummer',
				gameday = '$spieltag',
				teamID1 = '$H',
				userID1 = '$Team1_Spieler1',
				teamID2 = '$G',
				userID2 = '$Team2_Spieler1'";
			WCF::getDB()->sendQuery($sql);

			$sql = "INSERT INTO events_paarungen
				SET	event_ID = '$eventID',
				event_round = '$Runde',
				event_group = '$group',
				gamenumber = '$spielnummer',
				gameday = '$spieltag',
				teamID1 = '$H',
				userID1 = '$Team1_Spieler2',
				teamID2 = '$G',
				userID2 = '$Team2_Spieler2'";
			WCF::getDB()->sendQuery($sql);

			$sql = "INSERT INTO events_paarungen
				SET	event_ID = '$eventID',
				event_round = '$Runde',
				event_group = '$group',
				gamenumber = '$spielnummer',
				gameday = '$spieltag',
				teamID1 = '$H',
				userID1 = '$Team1_Spieler1',
				teamID2 = '$G',
				userID2 = '$Team2_Spieler2'";
			WCF::getDB()->sendQuery($sql);

			$sql = "INSERT INTO events_paarungen
				SET	event_ID = '$eventID',
				event_round = '$Runde',
				event_group = '$group',
				gamenumber = '$spielnummer',
				gameday = '$spieltag',
				teamID1 = '$H',
				userID1 = '$Team1_Spieler2',
				teamID2 = '$G',
				userID2 = '$Team2_Spieler1'";
			WCF::getDB()->sendQuery($sql);
		}
	}
}
elseif($event_art == 2 && $status_aktuell == 4) {
	$gruppen_zähler = array();

			//// Letzter Spieltag ////
	$sql2 = "SELECT	gameday, gamenumber
		FROM		events_paarungen
		WHERE event_ID='$eventID'
		ORDER BY gameday DESC LIMIT 1";
	$result2 = WCF::getDB()->sendQuery($sql2);
	while ($row2 = WCF::getDB()->fetchArray($result2))
			$spieltag = $row2['gameday'];

			//// Anzahl der Final Teilnehmer ////
	$sql3 = "SELECT	participants_final, group_final
		FROM		events
		WHERE id='$eventID'";
	$result3 = WCF::getDB()->sendQuery($sql3);
	while ($row3 = WCF::getDB()->fetchArray($result3)) {
		$participants_final = $row3['participants_final'];
		$Gruppen_Anzahl = $row3['group_final'];
	}

				//// Platzierung berechnen ////
	include(WCF_DIR.'lib/page/ECPTabelle.php');

	$top8 = array();
	for ($h=0; $h < count($tabelle) - 1; $h++) {
		if(!isset($event_group)) {
			$k1 = 0;
			$k2 = 1;
			$h2 = $h + 1;
			$top8[$k1]['teamID'] = 
			$tabelle[$h]['teamID'];
			$top8[$k2]['teamID'] = $tabelle[$h2]['teamID'];
		}
		elseif($event_group != $tabelle[$h]['event_group']) {
			$k1 += 2;
			$k2 += 2;
			$h2 = $h + 1;
			$top8[$k1]['teamID'] = $tabelle[$h]['teamID'];
			$top8[$k2]['teamID'] = $tabelle[$h2]['teamID'];
		}
		$event_group = $tabelle[$h]['event_group'];
	}

	for ($i=0; $i < $Gruppen_Anzahl / 2; $i++) {
		$j1 = $i;
		$j2 = $i + 1;
		$j3 = $i + 2;
		$j4 = $i + 3;
		$i += 3;

		$team1 = $top8[$j1]['teamID'];
		$team2 = $top8[$j2]['teamID'];
		$team3 = $top8[$j3]['teamID'];
		$team4 = $top8[$j4]['teamID'];
		$Runde = 3;

				//// Letzter Spieltag ////
		$sql2 = "SELECT	gameday, gamenumber
			FROM		events_paarungen
			WHERE event_ID='$eventID'
			ORDER BY gameday DESC LIMIT 1";
		$result2 = WCF::getDB()->sendQuery($sql2);
		while ($row2 = WCF::getDB()->fetchArray($result2)) {
			$gamenumber1 = $row2['gamenumber'] + 1;
			$gamenumber2 = $row2['gamenumber'] + 2;
		}

				//// User Team 1 ////
		$sql2 = "SELECT	user_ID
			FROM		events_user
			WHERE team_ID ='$team1'";
		$result2 = WCF::getDB()->sendQuery($sql2);
		while ($row2 = WCF::getDB()->fetchArray($result2)) {
			if(!isset($user_ID_1_1)) $user_ID_1_1 = $row2['user_ID'];
			else $user_ID_1_2 = $row2['user_ID'];
		}

				//// User Team 2 ////
		$sql2 = "SELECT	user_ID
			FROM		events_user
			WHERE team_ID ='$team2'";
		$result2 = WCF::getDB()->sendQuery($sql2);
		while ($row2 = WCF::getDB()->fetchArray($result2)) {
				if(!isset($user_ID_2_1)) $user_ID_2_1 = $row2['user_ID'];
				else $user_ID_2_2 = $row2['user_ID'];
		}

				//// User Team 3 ////
		$sql2 = "SELECT	user_ID
			FROM		events_user
			WHERE team_ID ='$team3'";
		$result2 = WCF::getDB()->sendQuery($sql2);
		while ($row2 = WCF::getDB()->fetchArray($result2)) {
			if(!isset($user_ID_3_1)) $user_ID_3_1 = $row2['user_ID'];
			else $user_ID_3_2 = $row2['user_ID'];
		}

				//// User Team 4 ////
		$sql2 = "SELECT	user_ID
			FROM		events_user
			WHERE team_ID ='$team4'";
		$result2 = WCF::getDB()->sendQuery($sql2);
		while ($row2 = WCF::getDB()->fetchArray($result2)) {
			if(!isset($user_ID_4_1)) $user_ID_4_1 = $row2['user_ID'];
			else $user_ID_4_2 = $row2['user_ID'];
		}

					//// Team 1 vs Team 2 ////
		$sql = "INSERT INTO events_paarungen
			SET	event_ID = '$eventID',
			event_round = '$Runde',
			gamenumber = '$gamenumber1',
			gameday = '',
			teamID1 = '$team1',
			userID1 = '$user_ID_1_1',
			teamID2 = '$team2',
			userID2 = '$user_ID_2_2'";
		WCF::getDB()->sendQuery($sql);		

		$sql = "INSERT INTO events_paarungen
			SET	event_ID = '$eventID',
			event_round = '$Runde',
			gamenumber = '$gamenumber1',
			gameday = '',
			teamID1 = '$team1',
			userID1 = '$user_ID_1_2',
			teamID2 = '$team2',
			userID2 = '$user_ID_2_1'";
		WCF::getDB()->sendQuery($sql);

		$sql = "INSERT INTO events_paarungen
			SET	event_ID = '$eventID',
			event_round = '$Runde',
			gamenumber = '$gamenumber1',
			gameday = '',
			teamID1 = '$team1',
			userID1 = '$user_ID_1_1',
			teamID2 = '$team2',
			userID2 = '$user_ID_2_1'";
		WCF::getDB()->sendQuery($sql);

		$sql = "INSERT INTO events_paarungen
			SET	event_ID = '$eventID',
			event_round = '$Runde',
			gamenumber = '$gamenumber1',
			gameday = '',
			teamID1 = '$team1',
			userID1 = '$user_ID_1_2',
			teamID2 = '$team2',
			userID2 = '$user_ID_2_2'";
		WCF::getDB()->sendQuery($sql);

					//// Team 3 vs Team 4 ////
		$sql = "INSERT INTO events_paarungen
			SET	event_ID = '$eventID',
			event_round = '$Runde',
			gamenumber = '$gamenumber2',
			gameday = '',
			teamID1 = '$team3',
			userID1 = '$user_ID_3_1',
			teamID2 = '$team4',
			userID2 = '$user_ID_4_2'";
		WCF::getDB()->sendQuery($sql);		

		$sql = "INSERT INTO events_paarungen
			SET	event_ID = '$eventID',
			event_round = '$Runde',
			gamenumber = '$gamenumber2',
			gameday = '',
			teamID1 = '$team3',
			userID1 = '$user_ID_3_2',
			teamID2 = '$team4',
			userID2 = '$user_ID_4_1'";
		WCF::getDB()->sendQuery($sql);

		$sql = "INSERT INTO events_paarungen
			SET	event_ID = '$eventID',
			event_round = '$Runde',
			gamenumber = '$gamenumber2',
			gameday = '',
			teamID1 = '$team3',
			userID1 = '$user_ID_3_1',
			teamID2 = '$team4',
			userID2 = '$user_ID_4_1'";
		WCF::getDB()->sendQuery($sql);

		$sql = "INSERT INTO events_paarungen
			SET	event_ID = '$eventID',
			event_round = '$Runde',
			gamenumber = '$gamenumber2',
			gameday = '',
			teamID1 = '$team3',
			userID1 = '$user_ID_3_2',
			teamID2 = '$team4',
			userID2 = '$user_ID_4_2'";
		WCF::getDB()->sendQuery($sql);
	}
}
?>