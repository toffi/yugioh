<?php
$n�chster_spieltag = $akt + 1;
$sql = "UPDATE	events
	SET	current_gameday = '$n�chster_spieltag'
	WHERE 	id ='$eventID'";
WCF::getDB()->sendQuery($sql);
?>