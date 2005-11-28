<?php
/*
 * Template Variable Data Source @Bindings 
 * Created by Raymond Irving Feb, 2005
 */

global $BINDINGS; // list of supported bindings. must be upper case
$BINDINGS = "FILE,CHUNK,DOCUMENT,SELECT,EVAL"; 

function ProcessTVCommand($value){
	global $modx;	
	$etomite = &$modx;
	
	$nvalue = trim($value);
	if (substr($nvalue,0,1)!='@') return $value;
	else {
		list($cmd,$param) = ParseCommand($nvalue);
		$cmd = trim($cmd);
		switch (strtoupper($cmd)) {
			case "@FILE":
				$output = ProcessFile($param);
				break;

			case "@CHUNK":		// retrieve a chunk and process it's content
				$chunk = $modx->getChunk($param);
				$output = $chunk;
				break;

			case "@DOCUMENT":	// retrieve a document and process it's content
				$rs = $modx->getDocument($param);
				if (is_array($rs)) $output = $rs['content'];
				else $output = "Unable to locate document $param";
				break;

			case "@SELECT":		// selects a record from the cms database
				$rt = array();
				$replacementVars= array(
					'DBASE'=> $modx->db->config['dbase'],
					'PREFIX'=> $modx->db->config['table_prefix']
				);
				foreach($replacementVars as $key=> $value) {
					$modx->setPlaceholder($key, $value);
				}
				$param = $modx->mergePlaceholderContent($param); 
				$rs = $modx->db->query("SELECT $param;");
				$output = $rs;
				break;
				
			case "@EVAL":		// evaluates text as php codes return the results
				$output = eval($param);
				break;

			default:
				$output = $value;
				break;

		}
		// support for nested bindings
		return ($output!=$value) ? ProcessTVCommand($output):$output;
	}
}

function ProcessFile($file){
	// get the file
	if(file_exists($file) && @$handle = fopen($file, "r")) {
		$buffer = "";
		while (!feof ($handle)) {
		   $buffer .= fgets($handle, 4096);
		}
		fclose ($handle);
	}
	else {
		$buffer =  " Could not retrieve document '$file'.";
	}
	return $buffer;
}

// ParseCommand - separate @ cmd from params
function ParseCommand($nvalue){	
	global $BINDINGS;
	$a = split(" ",$nvalue,2); // try splitting by space ( )	
	if (strpos($BINDINGS,trim(strtoupper(substr($a[0],1,10))))===false) $a = split("\n",$nvalue,2); // try splitting by \n	
	return $a;
}

?>