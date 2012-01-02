<?php
require_once(WCF_DIR.'lib/form/AbstractForm.class.php');
require_once (WCF_DIR . 'lib/page/util/menu/PageMenu.class.php');

class GuthabenPremiumForm extends AbstractForm {
	public $templateName = 'guthabenPremium';

	public $groups, $buy, $usergroupnames = array();
    public $menuItems = null;
    public $payment = 0;
    public $action = 'premiumPage';

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		WCF::getCache()->addResource('guthabenMainPage-' . PACKAGE_ID, WCF_DIR . 'cache/cache.guthabenMainPage-' . PACKAGE_ID . '.php', WCF_DIR . 'lib/system/cache/CacheBuilderGuthabenMainpage.class.php');
		$this->menuItems = WCF :: getCache()->get('guthabenMainPage-' . PACKAGE_ID);
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
        if(!GUTHABEN_ENABLE_GLOBAL) {
            throw new IllegalLinkException();
        }
        if(!GUTHABEN_ENABLE_PREMIUM) {
            throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.log.avatar.nomoney'));
        }

		$query = "SELECT	*
				FROM	wcf".WCF_N."_group
				WHERE	groupYears != ''
                    AND groupYears != '0'";
        $result = WCF::getDB()->sendQuery($query);
		while($row = WCF::getDB()->fetchArray($result)) {
            $row['groupYears'] = unserialize($row['groupYears']);
			if(!empty($row['groupYears']) && count($row['groupYears'])) {
                if(in_array($row['groupID'], WCF::getUser()->getGroupIDs())) {
                    $row['isMember'] = 1;
                }
                else {
                    $row['isMember'] = 0;
                }
                $row['groupDescription'] = self::getFormattedMessage($row['groupDescription']);
                $this->groups[$row['groupID']] = $row;
			} 
		}
        parent::readData();
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// show page
		WCF::getTPL()->assign(array (
			'groups' => $this->groups,
            'parentItems' => $this->menuItems['parents'],
            'childItems' => $this->menuItems['items'][$this->action],
            'activeParent' => $this->action
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		// set active header menu item
		PageMenu::setActiveMenuItem('wcf.header.menu.guthabenmain');
		
		// check permission
		WCF::getUser()->checkPermission('guthaben.canuse');
		
		parent::show();
	}

	public function readFormParameters() {
		parent::readFormParameters();

        foreach($this->groups as $group) {
    		if (isset($_POST['group'.$group['groupID']]) && !empty($_POST['group'.$group['groupID']])) {
    			$this->buy[$group['groupID']] = intval($_POST['group'.$group['groupID']]);
            }
       }
	}

    public function validate() {
        parent::validate();
        if(!count($this->buy)) {
            throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.premium.noPremiumChoose'));
        }
        foreach($this->buy as $groupID => $years) {
            $this->payment += $this->groups[$groupID]['groupYears'][$years-1];
            $this->usergroupnames[] = $this->groups[$groupID]['groupName'];
        }
		if(!Guthaben::check($this->payment)) {
			throw new NamedUserException(WCF::getLanguage()->get('wcf.guthaben.premium.notEnoughMoney'));
		}
    }

	public function save() {
		parent::save();
        $textGroups = $text = '';
        foreach($this->usergroupnames as $groups) {
            if(!empty($text)) $textGroups .= ', ';
            $textGroups .= $groups;
        }
        if(count($this->usergroupnames) > 1) $text = WCF::getLanguage()->get('wcf.guthaben.premium.boughtPlural').' '.$textGroups;
        else $text = WCF::getLanguage()->get('wcf.guthaben.premium.boughtSingular').' '.$textGroups;;
 
        Guthaben::sub($this->payment, $text);
        require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');
        $user = new UserEditor(WCF::getUser()->userID);
        foreach($this->buy as $groupID => $years) {
            $user->addToGroup($groupID);
     		$sql = "SELECT		rankID
    			FROM		wcf".WCF_N."_user_rank
    			WHERE		groupID = ".$groupID;
    		$row = WCF::getDB()->getFirstRow($sql);
        
            $user->update('', '', '', null, null, array('rankID' => $row['rankID'], 'userOnlineGroupID' => $groupID));

            // Bereits Mitglied
            if(in_array($groupID, WCF::getUser()->getGroupIDs())) {
    			$sql = "UPDATE wcf".WCF_N."_user_to_groups
    					SET premiumEndTime = premiumEndTime + ". $years * 31536000 ."
    					WHERE userID = ".WCF::getUser()->userID." AND groupID = ".$groupID;
            }
            // Kein Mitglied
            else {
                $time = TIME_NOW + $years * 31536000;
    			$sql = "UPDATE wcf".WCF_N."_user_to_groups
    					SET premiumEndTime = ".$time."
    					WHERE userID = ".WCF::getUser()->userID." AND groupID = ".$groupID;
            }
            WCF::getDB()->sendQuery($sql);
        }
        $user->resetSession();
        $this->saved();

		// forward to index page
		WCF::getTPL()->assign(array(
			'url' => 'index.php?form=GuthabenPremium'.SID_ARG_2ND,
			'message' => WCF::getLanguage()->get('wcf.guthaben.premium.succes', array('$textGroups' => $textGroups))
		));
		WCF::getTPL()->display('redirect');
		exit;
    }

	/**
	 * Returns the formatted message.
	 *
	 * @return	string
	 */
	public static function getFormattedMessage($text) {
		require_once(WCF_DIR.'lib/data/message/bbcode/MessageParser.class.php');
		MessageParser::getInstance()->setOutputType('text/html');
		return MessageParser::getInstance()->parse($text, true, true, true, false);
	}
}
?>