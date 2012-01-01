<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');
require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');

/**
* @author	SpIkE2
*/

class EcpAuslosungForm extends AbstractPage {
public $templateName = 'EcpAuslosungForm';

/**
* @see Page::assignVariables()
*/
public function assignVariables()
{
include(WCF_DIR.'lib/page/ECPHeadPage.class.php');

if(!isset($_POST['sent']))
{

$sql = "SELECT	id, teamname FROM events_team ORDER BY teamname ASC";
$result = WCF::getDB()->sendQuery($sql);
while ($row = WCF::getDB()->fetchArray($result)) {
$teamid = $row['id'];
$teamname = $row['teamname'];
if(!isset($team)) $team = "<option value=\"0\">Freilos</option>";
$team .= "<option value=\"$teamid\">$teamname</option>";
}

WCF::getTPL()->assign(array(
'team' => $team
));
}
elseif(!isset($_POST['sent']))
{
if(isset($_POST["group"])) $group = intval($_POST["group"]);
if(isset($_POST["gamer"])) $teams = intval($_POST["gamer"]);
$teams_html = '';

for ($i=1; $i < ($gamer + 1);$i++)
{
$teams_html .= "<input type=\"text\" name=\"gamer\" size=\"5\">";
}
WCF::getTPL()->assign(array(
'team' => $teams_html
));
}
}

/**
* @see Page::show()
*/
public function show() {
if (!WCF :: getUser()->userID || !WCF :: getUser()->getPermission('mod.ecp.canSeeECP'))
	throw new PermissionDeniedException();


	// set active header menu item
	require_once(WCF_DIR.'lib/page/util/menu/HeaderMenu.class.php');
	HeaderMenu::setActiveMenuItem('wcf.header.menu.ecp');
	
	parent::show();
}
}
?>