<?php
$nächster_spieltag = $akt + 1;
$sql = "UPDATE	events
	SET	current_gameday = '$nächster_spieltag'
	WHERE 	id ='$eventID'";
WCF::getDB()->sendQuery($sql);
?>