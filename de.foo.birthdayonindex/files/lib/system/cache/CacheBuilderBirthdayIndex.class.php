<?PHP
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');
require_once(WCF_DIR.'lib/data/user/User.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.birthdayonindex
 */
class CacheBuilderBirthdayIndex implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {

		$data = array();

        if (WCF::getLanguage()->get('wcf.global.dateMethod') == 'strftime') {
          $formatedTime = DateUtil::formatDate("%m-%d", TIME_NOW);
        }
        else {
          $formatedTime = DateUtil::formatDate("m-d", TIME_NOW);
        }
        $optionIDBirthday = User::getUserOptionID('birthday');
        $lastMonth = TIME_NOW-2592000;
        $sql = "SELECT        user_options.userID, user_options.userOption".User::getUserOptionID('birthday')." , user.username
                FROM        wcf".WCF_N."_user_option_value user_options
                LEFT JOIN    wcf".WCF_N."_user user
                 USING (userID)
                WHERE user_options.userOption".$optionIDBirthday." LIKE '____-".$formatedTime."'
                    AND user.lastActivityTime >=".$lastMonth; // Letzten 30 Tage
         $birthday = WCF::getDB()->sendQuery($sql);
         while ($row = WCF::getDB()->fetchArray($birthday)) {
          $row['userOption'] = date("Y") - substr($row['userOption'.$optionIDBirthday], 0, 4);
          $data[] = $row;
        }
    
        return $data;
	}

}

?>