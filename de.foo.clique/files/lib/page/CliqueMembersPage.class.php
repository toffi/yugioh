<?php
require_once(WCF_DIR.'lib/page/AbstractCliqueSortablePage.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
require_once(WCF_DIR.'lib/data/user/option/UserOptions.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class CliqueMembersPage extends AbstractCliqueSortablePage {
	public $templateName = 'cliqueTeam';
	public $membership = array();
    public $sortField = '';
    public $sortOrder = 'DESC';        
    public $defaultSortField = 'enteredTime';            


	/**
	 * @see Page::readData()
	 */
	public function readData() {
	    parent::readData();
        $this->userOptions = new UserOptions('medium');
                                
        $this->readMembers();
	}
	
	/**
	 * @see SortablePage::validateSortField()
	 */
	public function validateSortField() {
		parent::validateSortField();

		switch ($this->sortField) {
			case 'username':
			case 'avatarName':
			case 'enteredTime':
            case 'posts':                        
			break;
			default: $this->sortField = $this->defaultSortField;
		}
	}
	
	/**
	 * @see MembersListPage::getMembers()
	 */
	protected function readMembers() {       	   	   
		$sql = "SELECT clique.*, avatar.*, user.*, rank.*, board.*
			FROM wcf".WCF_N."_user_to_clique clique
            LEFT JOIN 	wcf".WCF_N."_user user 
			ON		(user.userID = clique.userID)
 			LEFT JOIN	wcf".WCF_N."_avatar avatar
			ON		(avatar.avatarID = user.avatarID)
			LEFT JOIN 	wcf".WCF_N."_user_rank rank
			ON		(rank.rankID = user.rankID)
			LEFT JOIN 	wbb".WBB_N."_user board
			ON		(board.userID = user.userID)                        
			WHERE		clique.cliqueID = ".$this->cliqueID."
            ORDER BY groupType DESC, ".$this->sortField." ".$this->sortOrder;
		$result = WCF::getDB()->sendQuery($sql);
        $this->countMembers = WCF::getDB()->countRows($result);                
		while ($row = WCF::getDB()->fetchArray($result)) {
    		$user = new UserProfile(null, $row);
            $protectedProfile = ($user->protectedProfile && WCF::getUser()->userID != $user->userID);                        
    		$username = StringUtil::encodeHTML($row['username']);
 
            // Username                         
			$userData['username'] = '<div class="containerIconSmall">';
			if ($user->isOnline()) {
				$title = WCF::getLanguage()->get('wcf.user.online', array('$username' => $username));
				$userData['username'] .= '<img src="'.StyleManager::getStyle()->getIconPath('onlineS.png').'" alt="'.$title.'" title="'.$title.'" />';
			}
			else {
				$title = WCF::getLanguage()->get('wcf.user.offline', array('$username' => $username));
				$userData['username'] .= '<img src="'.StyleManager::getStyle()->getIconPath('offlineS.png').'" alt="'.$title.'" title="'.$title.'" />';
			}
			$userData['username'] .= '</div><div class="containerContentSmall">';
			$title = WCF::getLanguage()->get('wcf.user.viewProfile', array('$username' => $username));
			$userData['username'] .= '<p><a href="index.php?page=User&amp;userID='.$row['userID'].SID_ARG_2ND.'" title="'.$title.'">'.$username.'</a></p>';
			
            //Avatar
            if (MODULE_USER_RANK == 1 && $user->getUserTitle()) {
				$userData['username'] .= '<p class="smallFont">'.$user->getUserTitle().' '.($user->getRank() ? $user->getRank()->getImage() : '').'</p>';
			}
			$userData['username'] .= '</div>';

			if ($user->getAvatar() && ($row['userID'] == WCF::getUser()->userID || WCF::getUser()->getPermission('user.profile.avatar.canViewAvatar'))) {
				$user->getAvatar()->setMaxHeight(50);
				$title = WCF::getLanguage()->get('wcf.user.viewProfile', array('$username' => $username));
				$userData['avatar'] = '<a href="index.php?page=User&amp;userID='.$row['userID'].SID_ARG_2ND.'" title="'.$title.'">'.$user->getAvatar()->__toString().'</a>';
			}
			else $userData['avatar'] = '';                        		  		  

            // Posts
            $userData['posts'] = '<a href="index.php?form=Search&amp;types[]=post&amp;userID='.$user->userID.'" title="'.WCF::getLanguage()->get('wcf.user.profile.search', array('$username' => StringUtil::encodeHTML($user->username))).'">'.$user->posts.'</a>';

            // enteredTime
            $userData['enteredTime'] = DateUtil::formatDate(null, $user->enteredTime);                                    
            
            $this->membership[$row['groupType']]['user'][] = $userData;
            
        }
                    //GroupType
        if(isset($this->membership[5])) $this->membership[5]['groupName'] = WCF::getLanguage()->get('wcf.clique.administrate.raiser');
        if(isset($this->membership[4])) $this->membership[4]['groupName'] = WCF::getLanguage()->get('wcf.clique.administrate.administrator');
        if(isset($this->membership[3])) $this->membership[3]['groupName'] = WCF::getLanguage()->get('wcf.clique.administrate.moderator');
        if(isset($this->membership[2])) $this->membership[2]['groupName'] = WCF::getLanguage()->get('wcf.clique.administrate.member');
        if(isset($this->membership[1])) $this->membership[1]['groupName'] = WCF::getLanguage()->get('wcf.clique.administrate.guest');
            
           // die('<pre>'.print_r($this->membership, true));
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		// mark Comments as active and get menu items
		require_once(WCF_DIR.'lib/data/clique/CliqueMenuItems.class.php');
        CliqueMenuItems::setActiveMenuItem('wcf.clique.membership');

		// show page
		WCF::getTPL()->assign(array(
			'members' => $this->membership
		));
	}        
}
?>