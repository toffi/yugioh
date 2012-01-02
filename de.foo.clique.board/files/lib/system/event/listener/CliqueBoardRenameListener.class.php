<?php
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/system/language/LanguageEditor.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */

class CliqueBoardRenameListener implements EventListener {

	public function execute($eventObj, $className, $eventName){
		$this->languages = WCF::getCache()->get('languages', 'languages');
        $this->packageID = WCF::getPackageID('de.foo.clique.board');
    	foreach (array_keys($this->languages) as $languageID) {
            $language = new Language($languageID);
            $this->boardTitleValue = $language->get('wbb.board.name.clique.once');
			$this->boardDescriptionValue = $language->get('wbb.board.name.clique.once');
			$this->boardTitleName = 'wbb.clique.board.name.';
			$this->boardDescription = 'wbb.clique.board.name.';

			$this->boardTitleName .= $eventObj->cliqueID;
			$this->boardTitleValue .= '»'.$eventObj->name.'«';
			$this->boardDescription .= $eventObj->cliqueID.'.description';
			$this->boardDescriptionValue .= '»'.$eventObj->name.'«';
			$language = new LanguageEditor($languageID);
			$language->updateItems(array($this->boardTitleName => $this->boardTitleValue), 0, $this->packageID, array($this->boardTitleName => 1));
			$language->updateItems(array($this->boardDescription => $this->boardDescriptionValue), 0, $this->packageID, array($this->boardDescription => 1));
			$language->deleteLanguageFiles($languageID, 'wbb.clique.board', PACKAGE_ID);
        }

	}
}
?>