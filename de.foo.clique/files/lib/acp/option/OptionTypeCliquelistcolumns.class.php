<?php
// wcf imports
require_once(WCF_DIR.'lib/acp/option/OptionTypeUseroptions.class.php');
require_once(WCF_DIR.'lib/acp/option/OptionTypeCliquelistsortfield.class.php');
require_once(WCF_DIR.'lib/system/event/EventHandler.class.php');

/**
 * OptionTypeMemberslistcolumns lets you configure the displayed columns in the members list.
 *
 * @author	Marcel Werk
 * @copyright	2001-2009 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltlab.wcf.page.user.profile
 * @subpackage	acp.option
 * @category 	Community Framework
 */
class OptionTypeCliquelistcolumns extends OptionTypeUseroptions {
	protected static $selectedColumns = array();

	public $staticColumns = array('name' => 'wcf.clique.add.name',
																'time' => 'wcf.clique.general.creationDate',
																'status' => 'wcf.clique.add.status',
																'shortDescription' => 'wcf.clique.overview.shortDescription',
																'description' => 'wcf.clique.add.description',
																'image' => 'wcf.clique.add.picture',
																'rating' => 'wcf.clique.overview.raiting'
															);
	public $templateName = 'optionTypeCliquelistColumns';
	
	/**
	 * Creates a new OptionTypeMemberslistcolumns object.
	 * Calls the construct event.
	 */
	public function __construct() {
		// call construct event
		EventHandler::fireAction($this, 'construct');
	}
	
	protected function getUserOptions(&$optionData) {
		$this->readCache();
		$options = $this->staticColumns;
		
		// sort options
		self::$selectedColumns = explode(',', $optionData['optionValue']);
		uksort($options, array('self', 'compareOptions'));
		
		// update sort field options
		$selectedOptions = explode(',', $optionData['optionValue']);
		$selectOptions = '';
		foreach ($selectedOptions as $selectedOption) {
			if (isset($options[$selectedOption])) {
				$selectOptions .= $selectedOption . ':' . $options[$selectedOption] . "\n";
			}
		}
		
		OptionTypeCliquelistsortfield::$selectOptions = StringUtil::trim($selectOptions);
		
		return $options;
	}
	
	protected static function compareOptions($optionA, $optionB) {
		$keyA = array_search($optionA, self::$selectedColumns);
		$keyB = array_search($optionB, self::$selectedColumns);
		
		if ($keyA !== false && $keyB !== false) {
			if ($keyA < $keyB) return -1;
			else return 1;
		}
		else if ($keyA !== false) {
			return -1;
		}
		else if ($keyB !== false) {
			return 1;
		}
		else {
			return 0;
		}
	}
}
?>