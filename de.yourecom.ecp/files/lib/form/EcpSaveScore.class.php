<?php
if($event_art == 2 && $status_aktuell == 4) {
	$sql2 = "SELECT	id
		FROM		events_paarungen
		WHERE event_id='$eventID' && event_round > 2";
}
else {
$sql2 = "SELECT	id
	FROM		events_paarungen
	WHERE event_id='$eventID' && gameday='$akt'";
}

$result2 = WCF::getDB()->sendQuery($sql2);
while ($row2 = WCF::getDB()->fetchArray($result2)) {
		$game_id2[] = $row2['id'];
}

foreach($game_id2 as $game_id){
	if(isset($_POST["1_$game_id"]) || isset($_POST["2_$game_id"])) {
			/// Doubledefloose ???/
		if($_POST["1_$game_id"] == 'x' && $_POST["2_$game_id"] == 'x') {
			$Score1 = $_POST["1_$game_id"];
			$Score2 = $_POST["2_$game_id"];
		}
		else {
			$Score1 = intval($_POST["1_$game_id"]);
			$Score2 = intval($_POST["2_$game_id"]);
		}
		$sql = "UPDATE	events_paarungen
				SET	scoreID_1 = '$Score1', scoreID_2 = '$Score2'
				WHERE 	event_ID = '$eventID' && id = '$game_id'";
		WCF::getDB()->sendQuery($sql);
	}
}
	//// Gewinner Bestimmung ////
if($event_art == 2)
	$sql2 = "SELECT	userID1, userID2, scoreID_1, scoreID_2
			FROM		events_paarungen
			WHERE event_ID = '$eventID' && event_round > 2";
else $sql2 = "SELECT	userID1, userID2, scoreID_1, scoreID_2
			FROM		events_paarungen
			WHERE event_ID = '$eventID' && gameday = '$akt'";

$result2 = WCF::getDB()->sendQuery($sql2);
while ($row2 = WCF::getDB()->fetchArray($result2)) {
	if($row2['scoreID_1'] > $row2['scoreID_2']) $winner = $row2['userID1'];
	elseif($row2['scoreID_1'] < $row2['scoreID_2']) $winner = $row2['userID2'];
	$u1 = $row2['userID1'];
	$u2 = $row2['userID2'];
	if(isset($winner)) {
		if($event_art == 2 && $status_aktuell == 4)
			$sql = "UPDATE	events_paarungen
					SET	winnerID = '$winner'
					WHERE 	event_ID = '$eventID' && event_round > 2 && userID1 = '$u1' && userID2 = '$u2'";
		else
			$sql = "UPDATE	events_paarungen
					SET	winnerID = '$winner'
					WHERE 	event_ID = '$eventID' && gameday = '$akt' && userID1 = '$u1' && userID2 = '$u2'";
		WCF::getDB()->sendQuery($sql);
	}
	unset($winner);
}
$text = '&Auml;nderungen wurden eingetragen!';
$this->saveform("$text", "$eventID", "$akt");
?>