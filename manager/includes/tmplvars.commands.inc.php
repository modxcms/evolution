<?php
/*
 * Template Variable Data Source @Bindings 
 * Created by Raymond Irving Feb, 2005
 */

global $BINDINGS; // list of supported bindings. must be upper case
$BINDINGS = "FILE,CHUNK,DOCUMENT,SELECT,EVAL"; 

function ProcessTVCommand($etomite,$value){
	if(empty($etomite)) {
		include_once dirname(__FILE__)."/document.parser.class.inc.php";
		$etomite = new DocumentParser;	// initiate a new document parser
		$etomite->getSettings(); // load settings
	}
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
				$chunk = $etomite->getChunk($param);
				$output = $chunk;
				break;

			case "@DOCUMENT":	// retrieve a document and process it's content
				$rs = $etomite->getDocument($param);
				if (is_array($rs)) $output = $rs['content'];
				else $output = "Unable to locate document $param";
				break;

			case "@SELECT":		// selects a record from the cms database
				$rt = array();
				$pre = $etomite->dbConfig['dbase'].".".$etomite->dbConfig['table_prefix'];
				$param = str_replace("{PREFIX}",$pre,$param);
				mysql_select_db(str_replace("`","",$etomite->dbConfig['dbase'])); // select default database
				$rs = $etomite->dbQuery("SELECT $param;");
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
		return ($output!=$value) ? ProcessTVCommand($etomite,$output):$output;
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