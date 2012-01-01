<?php
$sql = "UPDATE	events
	SET	status='10'
	WHERE 	id ='$eventID'";
WCF::getDB()->sendQuery($sql);
?>