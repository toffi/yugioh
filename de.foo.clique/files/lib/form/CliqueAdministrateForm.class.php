<?php
require_once(WCF_DIR.'lib/form/AbstractCliqueForm.class.php');
require_once(WCF_DIR.'lib/page/util/menu/UserCPMenu.class.php');
require_once(WCF_DIR.'lib/data/user/UserEditor.class.php');
require_once(WCF_DIR.'lib/page/CliqueDetailPage.class.php');
require_once(WCF_DIR.'lib/data/clique/CliqueEditor.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueAdministrateForm extends AbstractCliqueForm {
	public $templateName = 'cliqueAdministrate';
	public $usernames = '';
	public $notAnswerdInvites = array();
	public $rightSql= '';
	public $groupRights = '';
	public $memberships = array();
	public $commentsEnable = 0;
	public $menuItemForm = array();
	public $updateCliqueFunctions = '';
	public $aplications = array();
    public $newRaiser = NULL;
    public $changeRaiserSure = 0;

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		if (CliqueEditor::getCliquePermission('canAttendInvites') || WCF::getUser()->getPermission('mod.clique.general.canAdministrateEveryClique')) {
			$this->getNotAnswerdInvites();
			$this->getAplication();
		}

		$this->cliqueRights[0]['rightName'] = 'canEditClique';
		$this->cliqueRights[0]['rightLanguage'] = WCF::getLanguage()->get('wcf.clique.permission.canEditClique');
		$this->cliqueRights[1]['rightName'] = 'canDeleteClique';
		$this->cliqueRights[1]['rightLanguage'] = WCF::getLanguage()->get('wcf.clique.permission.canDeleteClique');
		$this->cliqueRights[2]['rightName'] = 'canEditRights';
		$this->cliqueRights[2]['rightLanguage'] = WCF::getLanguage()->get('wcf.clique.permission.canEditRights');
		$this->cliqueRights[3]['rightName'] = 'canInviteUsers';
		$this->cliqueRights[3]['rightLanguage'] = WCF::getLanguage()->get('wcf.clique.permission.canInviteUsers');
		$this->cliqueRights[4]['rightName'] = 'canAttendInvites';
		$this->cliqueRights[4]['rightLanguage'] = WCF::getLanguage()->get('wcf.clique.permission.canAttendInvites');
		$this->cliqueRights[5]['rightName'] = 'canSeeComments';
		$this->cliqueRights[5]['rightLanguage'] = WCF::getLanguage()->get('wcf.clique.permission.canSeeComments');
		$this->cliqueRights[6]['rightName'] = 'canMakeComments';
		$this->cliqueRights[6]['rightLanguage'] = WCF::getLanguage()->get('wcf.clique.permission.canMakeComments');
		$this->cliqueRights[7]['rightName'] = 'canEditComments';
		$this->cliqueRights[7]['rightLanguage'] = WCF::getLanguage()->get('wcf.clique.permission.canEditComments');
		$this->cliqueRights[8]['rightName'] = 'canDeleteComments';
		$this->cliqueRights[8]['rightLanguage'] = WCF::getLanguage()->get('wcf.clique.permission.canDeleteComments');
		$this->cliqueRights[11]['rightName'] = 'canActivateModules';
		$this->cliqueRights[11]['rightLanguage'] = WCF::getLanguage()->get('wcf.clique.permission.canActivateModules');

		$sql = "SELECT *
						FROM wcf".WCF_N."_clique_group_rights
						WHERE cliqueID = ".$this->cliqueID;
		$this->groupRights = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);

		$this->groups = array(
			2 => WCF::getLanguage()->get('wcf.clique.administrate.member'),
			3 => WCF::getLanguage()->get('wcf.clique.administrate.moderator'),
			4 => WCF::getLanguage()->get('wcf.clique.administrate.administrator'),
		);

		$sql = "SELECT membership.groupType, membership.userID, user_table.username
			FROM wcf".WCF_N."_user_to_clique membership
			LEFT JOIN 	wcf".WCF_N."_user user_table
				ON		(user_table.userID = membership.userID)
			WHERE		membership.cliqueID = ".$this->cliqueID."
				AND		membership.groupType != 5";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->memberships[] = $row;
		}

			// Read Cache of CliqueFunctions
		WCF::getCache()->addResource('cliquePageMenu-'.PACKAGE_ID, WCF_DIR.'cache/cache.cliquePageMenu-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderCliquePageMenu.class.php');
		$this->cliqueMenuItems = WCF::getCache()->get('cliquePageMenu-'.PACKAGE_ID);

		parent::readData();
	}

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if (isset($_POST['usernames'])) $this->usernames = StringUtil::trim($_POST['usernames']);

			// Read clique Functions from Form
        if (CliqueEditor::getCliquePermission('canActivateModules')) {
    		foreach ($this->cliqueMenuItems as $menuItem) {
    			$explodeMenuItem = explode(".",$menuItem['menuItem']);
    			if (isset($_POST[end($explodeMenuItem).'Enable'])) {
    				$this->menuItemForm[end($explodeMenuItem).'Enable'] = (bool) $_POST[end($explodeMenuItem).'Enable'];
    			}
    			elseif (end($explodeMenuItem).'Enable' != 'overviewEnable') {
    				$this->menuItemForm[end($explodeMenuItem).'Enable'] = 0;
    			}
    		}
        }

		if (CliqueEditor::getCliquePermission('canEditClique') || WCF::getUser()->getPermission('mod.clique.general.canAdministrateEveryClique')) {
			foreach ($this->cliqueRights as $right) {
				for ($i = 1; $i <= 4; $i++) {
					if(!isset($_POST[$right['rightName'].$i])) {
						$_POST[$right['rightName'].$i] = 0;
					}
					if(!empty($this->rightSql)) $this->rightSql .= ', ';
					$this->rightSql .= escapeString($right['rightName']).$i." = ".intval($_POST[$right['rightName'].$i]);
				}
			}
		}

        // Cliquengründer wechsel
        if(($this->clique->raiserID == WCF::getUser()->userID || WCF::getUser()->getPermission('mod.clique.general.canAdministrateEveryClique')) && isset($_POST['changeRaiser'])) {
            if(isset($_POST['changeRaiserSure'])) {
                $this->changeRaiserSure = 1;
            }
            $this->newRaiser = StringUtil::trim($_POST['changeRaiser']);
        }
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();

        $error = array();
		// Invite User
		if (!empty($this->usernames)) {
			// explode multiple usernames to an array
			$usernameArray = explode(',', $this->usernames);

		// loop through users
			foreach ($usernameArray as $username) {
				$username = StringUtil::trim($username);
				if (empty($username)) continue;

				try {
					// get user
					$user = new UserEditor(null, null, $username);
					if (!$user->userID) {
						throw new UserInputException('username', 'notFound');
					}
						//user already a member
					$sql = "SELECT userID
						FROM wcf".WCF_N."_user_to_clique
						WHERE		cliqueID = ".$this->cliqueID." AND userID = ".$user->userID;
					$this->isMember = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);

					if (!empty($this->isMember)) {
						throw new UserInputException('username', 'alreadyExist');
					}

						//user already has an invite
					$sql = "SELECT inviteeID
						FROM wcf".WCF_N."_clique_invite
						WHERE		cliqueID = ".$this->cliqueID." AND inviteeID = ".$user->userID;
					$this->isInvitet = WCF::getDB()->getFirstRow($sql, Database::SQL_ASSOC);

					if (!empty($this->isInvitet)) {
						throw new UserInputException('username', 'alreadyInvitet');
					}

					// no error
					$this->users[] = $user;
				}
				catch (UserInputException $e) {
					$error[] = array('type' => $e->getType(), 'username' => $username);
				}
			}

			if (count($error)) {
				throw new UserInputException('usernames', $error);
			}
		}
		try {
					// get user
            if(!empty($this->newRaiser)) {
    			$user = new UserEditor(null, null, $this->newRaiser);
                $username = $this->newRaiser;
    			if(!$user->userID) {
    				throw new UserInputException('changeRaiser', 'notFound');
    			}
                if($this->changeRaiserSure == 0) {
    				throw new UserInputException('changeRaiser', 'changeRaiserSure');
    			}
    
    			// no error
    			$this->newRaiser = $user;
            }
        }
        catch (UserInputException $e) {
            $error[] = array('type' => $e->getType(), 'username' => $username);
        }
		if (count($error)) {
			throw new UserInputException('changeRaiser', $error);
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();

		// save invites
		if(!empty($this->users) && (CliqueEditor::getCliquePermission('canInviteUsers') || WCF::getUser()->getPermission('mod.clique.general.canAdministrateEveryClique'))) {
			foreach ($this->users as $user) {
                self::setInvite($this->cliqueID, WCF::getUser()->userID, $user->userID, $inviteTime = TIME_NOW);
			}
		}

				// Update permissions
		if (CliqueEditor::getCliquePermission('canEditRights') || WCF::getUser()->getPermission('mod.clique.general.canAdministrateEveryClique')) {
			$sql = "UPDATE wcf".WCF_N."_clique_group_rights
							SET ".$this->rightSql."
							WHERE cliqueID = ".intval($this->cliqueID);
			WCF::getDB()->sendQuery($sql);

				// Member groups
			foreach ($this->memberships as $membership) {
				$sql = "UPDATE wcf".WCF_N."_user_to_clique
								SET groupType = ".intval($_POST[$membership['userID']])."
								WHERE cliqueID = ".intval($this->cliqueID)."
									AND userID=".$membership['userID'];
				WCF::getDB()->sendQuery($sql);
			}
		}

			// Update cliquefunctions
        if (CliqueEditor::getCliquePermission('canActivateModules')) {
    		foreach ($this->menuItemForm as $menuItemKey => $menuItem) {
    			if(!empty($this->updateCliqueFunctions)) $this->updateCliqueFunctions .= ', ';
    			$this->updateCliqueFunctions .= $menuItemKey." = ".intval($menuItem);
    		}
    
    		$sql = "UPDATE wcf".WCF_N."_clique
    						SET ".$this->updateCliqueFunctions."
    						WHERE cliqueID = ".intval($this->cliqueID);
    		WCF::getDB()->sendQuery($sql);
        }

        // Cliquengründer wechsel
        if(($this->clique->raiserID == WCF::getUser()->userID || WCF::getUser()->getPermission('mod.clique.general.canAdministrateEveryClique'))&& !empty($this->newRaiser)) {
    		$sql = "UPDATE wcf".WCF_N."_clique
    						SET raiserID = ".$this->newRaiser->userID."
    						WHERE cliqueID = ".intval($this->cliqueID);
    		WCF::getDB()->sendQuery($sql);

    		$sql = "UPDATE wcf".WCF_N."_user_to_clique
    						SET groupType = 4
    						WHERE cliqueID = ".intval($this->cliqueID)."
                                AND userID=".$this->clique->raiserID;
    		WCF::getDB()->sendQuery($sql);

    		$sql = "UPDATE wcf".WCF_N."_user_to_clique
    						SET groupType = 5
    						WHERE cliqueID = ".intval($this->cliqueID)."
                                AND userID=".$this->newRaiser->userID;
    		WCF::getDB()->sendQuery($sql);
        }

		$this->saved();

			// Redirect
		WCF::getTPL()->assign(array(
			'url' => 'index.php?form=CliqueAdministrate&cliqueID='.$this->cliqueID.SID_ARG_2ND,
			'message' => WCF::getLanguage()->get('wcf.clique.administrate.successful'),
			'wait' => 3
		));
		WCF::getTPL()->display('redirect');
		exit;
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'usernames' => $this->usernames,
			'notAnswerdInvites' => $this->notAnswerdInvites,
			'aplications' => $this->aplications,
			'cliqueRights' => $this->cliqueRights,
			'groupRights' => $this->groupRights,
			'groups' => $this->groups,
			'memberships' => $this->memberships,
			'cliquePermissions' => new CliqueEditor($this->cliqueID),
			'cliqueMenuItems' => $this->cliqueMenuItems
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		// check permission
		if (!WCF::getUser()->userID || (!CliqueEditor::getCliquePermission('canInviteUsers') && !CliqueEditor::getCliquePermission('canEditRights') && !CliqueEditor::getCliquePermission('canAttendInvites') && !WCF::getUser()->getPermission('mod.clique.general.canAdministrateEveryClique'))) {
			throw new PermissionDeniedException();
		}
		
		// set active tab
		UserCPMenu::getInstance()->setActiveMenuItem('wcf.user.usercp.menu.link.clique.raiser');
		
		parent::show();
	}

	/**
	 * getNotAnswerdInvites()
	 */
	public function getNotAnswerdInvites() {
		$sql = "SELECT invites.inviteeID, invites.time, user_table.username
			FROM wcf".WCF_N."_clique_invite invites
			LEFT JOIN 	wcf".WCF_N."_user user_table
				ON (user_table.userID = invites.inviteeID)
			WHERE invites.cliqueID = ".$this->cliqueID."
			ORDER BY invites.time ASC";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->notAnswerdInvites[] = $row;
		}
	}

	/**
	 * getAplication()
	 */
	public function getAplication() {
		$sql = "SELECT application.userID, application.message, application.time, user_table.username
			FROM wcf".WCF_N."_clique_application application
			LEFT JOIN 	wcf".WCF_N."_user user_table
				ON (user_table.userID = application.userID)
			WHERE application.cliqueID = ".$this->cliqueID."
			ORDER BY application.time ASC";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->aplications[] = $row;
		}
	}
    
    public static function setInvite($cliqueID, $inviterID, $inviteeID, $inviteTime = TIME_NOW) {
	   $sql = "INSERT INTO
				wcf".WCF_N."_clique_invite(cliqueID, inviterID, inviteeID, time)
				VALUES
					(".intval($cliqueID).",
					".$inviterID.",
					".$inviteeID.",
					".$inviteTime.")";
	   WCF::getDB()->sendQuery($sql);
    }
}
?>