<?php
$sql = "UPDATE	events
		SET	status= '4'
		WHERE 	id ='$eventID'";
WCF::getDB()->sendQuery($sql);
$status_aktuell = 4;

include(WCF_DIR . 'lib/form/EcpPaarungen.class.php');

$text = 'Die Vorrunde wurde erfolgreich beendet!';
$this->saveform("$text", "$eventID", "");
?>