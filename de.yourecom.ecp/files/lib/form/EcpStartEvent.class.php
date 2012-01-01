<?php
	//// Genügend Spieler??? ////
		//// Single Liga /////
if($event_art == 1)
	$sql3 = "SELECT COUNT(*) AS WieOft
		FROM    events_user
		WHERE event_id = '$eventID'";

		//// Team Liga ////
elseif($event_art == 2)
	$sql3 = "SELECT COUNT(*) AS WieOft
		FROM    events_team
		WHERE event_id = '$eventID'";

$result3 = WCF::getDB()->sendQuery($sql3);
while ($row3 = WCF::getDB()->fetchArray($result3))
	$WievieleSpielerAngemeldet = $row3['WieOft'];

		//// Single Liga /////
if($events_participants - $WievieleSpielerAngemeldet == 0  && $event_art == 1)
	{
		$sql = "UPDATE	events
			SET	status = '3',
			current_gameday = '1'
			WHERE 	id = '$eventID'";
		WCF::getDB()->sendQuery($sql);

		require_once (WCF_DIR . 'lib/form/EcpPaarungen.class.php');
    }
			//// Team Liga ////
elseif($events_participants - $WievieleSpielerAngemeldet == 0  && $event_art == 2)
	{
		require_once (WCF_DIR . 'lib/form/EcpPaarungen.class.php');

		$sql = "UPDATE	events
			SET	status = '3',
			current_gameday = '1'
			WHERE 	id = '$eventID'";
		WCF::getDB()->sendQuery($sql);
    }
?>