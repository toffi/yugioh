<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');
require_once(WCF_DIR.'lib/system/io/File.class.php');

/**
 * @author	$foo
 * @license	>Creative Commons Namensnennung 3.0 Deutschland License
 * @package	de.foo.clique
 */
class StyleCompressCssListener implements EventListener {
    public $aplications = array();

	public function execute($eventObj, $className, $eventName){
        $dir = WCF_DIR."style/";
        $handle = opendir($dir);
        
        while ($datei = readdir ($handle)) {
            if(file_exists($dir.$datei) && !is_dir($dir.$datei)) {
                 $fp = @fopen($dir.$datei, "r") or die ("Kann Datei nicht lesen.");
                 $payload = '';
                 // datei zeilenweise auslesen
                 while($line = fgets($fp, 1024)){
                    if(empty($line)) return;
                    $payload .= $line;
                 }
                 
                $newFileContent = $this->cssCleanUp($payload);

                if(empty($newFileContent)) return;
                
        		// open file
        		require_once(WCF_DIR.'lib/system/io/File.class.php');
        		$file = new File($dir.$datei);
        		
        		// write buffer
        		$file->write($newFileContent);
        		unset($newFileContent);
        		
        		// close file
        		$file->close();
        		@$file->chmod(0777);
            }
        }
        closedir($handle);
	}

    public function cssCleanUp($payload) {
        //  Kommentare entfernen
        $payload = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $payload);
        // Tabs, Leerzeichen und Zeilenumbrüche entfernen
        $payload = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $payload);
        return $payload;
    }
}
?>