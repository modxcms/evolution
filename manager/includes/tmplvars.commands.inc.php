<?php
/*
 * Template Variable Data Source @Bindings 
 * Created by Raymond Irving Feb, 2005
 */

global $BINDINGS; // Array of supported bindings. must be upper case
$BINDINGS = array('FILE','CHUNK','DOCUMENT','SELECT','EVAL','INHERIT','DIRECTORY');

function ProcessTVCommand($value, $name=''){
	global $modx;	
	$etomite = &$modx;
	
	$nvalue = trim($value);
	if (substr($nvalue,0,1)!='@') return $value;
	else {

		list($cmd,$param) = ParseCommand($nvalue);		
		switch ($cmd) {
			case "FILE":
				$output = ProcessFile($param);
				break;

			case "CHUNK":		// retrieve a chunk and process it's content
				$chunk = $modx->getChunk($param);
				$output = $chunk;
				break;

			case "DOCUMENT":	// retrieve a document and process it's content
				$rs = $modx->getDocument($param);
				if (is_array($rs)) $output = $rs['content'];
				else $output = "Unable to locate document $param";
				break;

			case "SELECT":		// selects a record from the cms database
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
				
			case "EVAL":		// evaluates text as php codes return the results
				$output = eval($param);
				break;

			case "INHERIT":		
				$output = $param; // Default to param value if no content from parents
				$doc = $modx->getDocument($modx->documentIdentifier,'id,parent');
				
				while($doc['parent'] != 0) {
					
					$parent_id = $doc['parent'];
					
					if($doc = $modx->getDocument($parent_id, 'id,parent')) {
						
						$tv = $modx->getTemplateVar($name, '*', $doc['id']);
						if($tv['value'] && substr($tv['value'],0,1) != '@') {
							$output = $tv['value'];
							break 2;
						}
						
					} else {
			
						// Get unpublished document
						$doc = $modx->getDocument($parent_id, 'id,parent',0);
						
					}
					
				}
				break;

                        case 'DIRECTORY':
                                $files = array();
                                $path = $modx->config['base_path'].$param;
                                if(substr($path,-1,1)!='/') { $path.='/'; }
                                if(!is_dir($path)) { die($path); break;}
                                $dir = dir($path);
                                while(($file = $dir->read())!==false) {
                                        if(substr($file,0,1)!='.') {
                                                $files[] = "{$file}=={$param}{$file}";
                                        }
                                }
                                asort($files);
                                $output = implode('||',$files);
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
function ParseCommand($binding_string) {

	global $BINDINGS;
	$match = array();
  $binding_string = trim($binding_string);
	$regexp = '/@('.implode('|',$BINDINGS).')\s*(.*)/i'; // Split binding on whitespace
	
	if(preg_match($regexp, $binding_string, $match)) {
	
	 // We can't return the match array directly because the first element is the whole string
	 $binding_array = array(strtoupper($match[1]), $match[2]); // Make command uppercase
	 
	 return $binding_array;
	 
	}
	
}

?>
