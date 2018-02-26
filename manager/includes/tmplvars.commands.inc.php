<?php
/*
 * Template Variable Data Source @Bindings
 * Created by Raymond Irving Feb, 2005
 */
global $BINDINGS; // Array of supported bindings. must be upper case
$BINDINGS = array (
    'FILE',
    'CHUNK',
    'DOCUMENT',
    'SELECT',
    'EVAL',
    'INHERIT',
    'DIRECTORY'
);

/**
 * @param string $value
 * @param string $name
 * @param string $docid
 * @param string $src
 * @param array $tvsArray
 * @return string
 */
function ProcessTVCommand($value, $name = '', $docid = '', $src='docform', $tvsArray = array()) {
    $modx = evolutionCMS();
    $docid = (int)$docid > 0 ? (int)$docid : $modx->documentIdentifier;
    $nvalue = trim($value);
    if (substr($nvalue, 0, 1) != '@')
        return $value;
    elseif(isset($modx->config['enable_bindings']) && $modx->config['enable_bindings']!=1 && $src==='docform') {
        return '@Bindings is disabled.';
    }
    else {
        list ($cmd, $param) = ParseCommand($nvalue);
        $cmd = trim($cmd);
        $param = parseTvValues($param, $tvsArray);
        switch ($cmd) {
            case "FILE" :
                $output = $modx->atBindFileContent($nvalue);
                break;

            case "CHUNK" : // retrieve a chunk and process it's content
                $chunk = $modx->getChunk(trim($param));
                $output = $chunk;
                break;

            case "DOCUMENT" : // retrieve a document and process it's content
                $rs = $modx->getDocument($param);
                if (is_array($rs))
                    $output = $rs['content'];
                else
                    $output = "Unable to locate document $param";
                break;

            case "SELECT" : // selects a record from the cms database
                $rt = array ();
                $replacementVars = array (
                    'DBASE' => $modx->db->config['dbase'],
                    'PREFIX' => $modx->db->config['table_prefix']
                );
                foreach ($replacementVars as $rvKey => $rvValue) {
                    $modx->setPlaceholder($rvKey, $rvValue);
                }
                $param = $modx->mergePlaceholderContent($param);
                $rs = $modx->db->query("SELECT $param;");
                $output = $rs;
                break;

            case "EVAL" : // evaluates text as php codes return the results
                $output = eval ($param);
                break;

            case "INHERIT" :
                $output = $param; // Default to param value if no content from parents
                $doc = $modx->getPageInfo($docid, 0, 'id,parent');

                while ($doc['parent'] != 0) {
                    $parent_id = $doc['parent'];

                    // Grab document regardless of publish status
                    $doc = $modx->getPageInfo($parent_id, 0, 'id,parent,published');
                    if ($doc['parent'] != 0 && !$doc['published'])
                        continue; // hide unpublished docs if we're not at the top

                    $tv = $modx->getTemplateVar($name, '*', $doc['id'], $doc['published']);

                    // if an inherited value is found and if there is content following the @INHERIT binding
                    // remove @INHERIT and output that following content. This content could contain other
                    // @ bindings, that are processed in the next step
                    if ((string) $tv['value'] !== '' && !preg_match('%^@INHERIT[\s\n\r]*$%im', $tv['value'])) {
                        $output = trim(str_replace('@INHERIT', '', (string) $tv['value']));
                        break 2;
                    }
                }
                break;

            case 'DIRECTORY' :
                $files = array ();
                $path = $modx->config['base_path'] . $param;
                if (substr($path, -1, 1) != '/') {
                    $path .= '/';
                }
                if (!is_dir($path)) {
                    die($path);
                    break;
                }
                $dir = dir($path);
                while (($file = $dir->read()) !== false) {
                    if (substr($file, 0, 1) != '.') {
                        $files[] = "{$file}=={$param}{$file}";
                    }
                }
                asort($files);
                $output = implode('||', $files);
                break;

            default :
                $output = $value;
                break;

        }
        // support for nested bindings
        return is_string($output) && ($output != $value) ? ProcessTVCommand($output, $name, $docid, $src, $tvsArray) : $output;
    }
}

/**
 * @param $file
 * @return string
 */
function ProcessFile($file) {
    // get the file
	$buffer = @file_get_contents($file);
	if ($buffer === false) $buffer = " Could not retrieve document '$file'.";
    return $buffer;
}

/**
 * ParseCommand - separate @ cmd from params
 *
 * @param string $binding_string
 * @return array
 */
function ParseCommand($binding_string)
{
    global $BINDINGS;
    $binding_array = array();
    foreach($BINDINGS as $cmd)
    {
        if(strpos($binding_string,'@'.$cmd)===0)
        {
            $code = substr($binding_string,strlen($cmd)+1);
            $binding_array = array($cmd,trim($code));
            break;
        }
    }
    return $binding_array;
}

/**
 * Parse MODX Template-Variables
 *
 * @param string $param
 * @param array $tvsArray
 * @return mixed
 */
function parseTvValues($param, $tvsArray)
{
    $modx = evolutionCMS();
	$tvsArray = is_array($modx->documentObject) ? array_merge($tvsArray, $modx->documentObject) : $tvsArray;
	if (strpos($param, '[*') !== false) {
		$matches = $modx->getTagsFromContent($param, '[*', '*]');
		foreach ($matches[0] as $i=>$match) {
			if(isset($tvsArray[ $matches[1][$i] ])) {
				if(is_array($tvsArray[ $matches[1][$i] ])) {
					$value = $tvsArray[$matches[1][$i]]['value'];
					$value = $value === '' ? $tvsArray[$matches[1][$i]]['default_text'] : $value;
				} else {
					$value = $tvsArray[ $matches[1][$i] ];
				}
				$param = str_replace($match, $value, $param);
			}
		}
	}
	return $param;
}
