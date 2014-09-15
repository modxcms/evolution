<?php
//---------------------------------------------------------------------------------
//   Utility functions
// 
//--------------------------------------------------------------------------------- 

// Pass useThisRule a comma separated list of allowed roles and templates, and it will
// return TRUE or FALSE to indicate whether this rule should be run on this page
function useThisRule($roles = '', $templates = ''){
	global $mm_current_page, $modx;
	$e = &$modx->Event;
	
	$exclude_roles = false;
	$exclude_templates = false;
	
	// Are they negative roles?
	if (substr($roles, 0, 1) == '!'){
		$roles = substr($roles, 1);
		$exclude_roles = true;
	}
	
	// Are they negative templates?
	if (substr($templates, 0, 1) == '!'){
		$templates = substr($templates, 1);
		$exclude_templates = true;
	}
	
	// Make the lists into arrays
	$roles = makeArray($roles);
	$templates = makeArray($templates);
	
	// Does the current role match the conditions supplied?
	$match_role_list = ($exclude_roles) ? !in_array($mm_current_page['role'], $roles) : in_array($mm_current_page['role'], $roles);
	
	// Does the current template match the conditions supplied?
	$match_template_list = ($exclude_templates) ? !in_array($mm_current_page['template'], $templates) : in_array($mm_current_page['template'], $templates);
	
	// If we've matched either list in any way, return true	
	if (($match_role_list || count($roles) == 0) && ($match_template_list || count($templates) == 0)){
		return true;
	} 
	
	return false;
}

// Makes a commas separated list into an array
function makeArray($csv){
	// If we've already been supplied an array, just return it
	if (is_array($csv)){
		return $csv;
	}
	
	// Else if we have an empty string
	if (trim($csv) == ''){
		return array();
	}
	
	// Otherwise, turn it into an array
	$return = explode(',', $csv);
	// Remove any whitespace
	array_walk($return, create_function('$v, $k', 'return trim($v);'));
	
	return $return;
}

// Make an output JS safe
function jsSafe($str){
	global $modx;
	
	// Only PHP versions > 5.2.3 allow us to prevent double_encoding
	// If you are using an older version of PHP, and use characters which require 
	// HTML entity encoding in new label names, etc you will have to specify the
	// actual character, not a pre-encoded version
	if (version_compare(PHP_VERSION, '5.2.3') >= 0){
		return htmlentities($str, ENT_QUOTES, $modx->config['modx_charset'], false);
	}else{
		return htmlentities($str, ENT_QUOTES, $modx->config['modx_charset']);
	}
}

/**
 * tplUseTvs
 * @version 1.2.1 (2014-03-29)
 * 
 * @desc Does the specified template use the specified TVs?
 * 
 * @param $tpl_id {integer} - Template ID.
 * @param $tvs {comma separated string; array} - TV names. Default: ''.
 * @param $types {comma separated string; array} - TV types, e.g. image. Default: ''.
 * @param $dbFields {somma separated string} - DB fields which get from 'site_tmplvars' table. Default: 'id'.
 * @param $resultKey {string; false} - DB field, which values are keys of result array. Keys of result array will be numbered if the parameter equals false. Default: false.
 * 
 * @return {array; false}
 */
function tplUseTvs($tpl_id, $tvs = '', $types = '', $dbFields = 'id', $resultKey = false){
	// If it's a blank template, it can't have TVs
	if($tpl_id == 0){return false;}
	
	global $modx;
	
	//Make the TVs, field types and DB fields into an array
	$fields = makeArray($tvs);
	$types = makeArray($types);
	$dbFields = makeArray($dbFields);
	
	//Add the result key in DB fields if return of an associative array is required & result key is absent there
	if ($resultKey !== false && !in_array($resultKey, $dbFields)){
		$dbFields[] = $resultKey;
	}
	
	//Get the DB table names
	$tv_table = $modx->getFullTableName('site_tmplvars');	
	$rel_table = $modx->getFullTableName('site_tmplvar_templates');
	
	$where = array();
	//Are we looking at specific TVs, or all?
	if (!empty($fields)){$where[] = 'tvs.name IN '.makeSqlList($fields);}
	
	//Are we looking at specific TV types, or all?
	if (!empty($types)){$where[] = 'type IN '.makeSqlList($types);}
	
	//Make the SQL for this template
	if (!empty($tpl_id)){$where[] = 'rel.templateid = '.$tpl_id;}
	
	//Execute the SQL query
	$result = $modx->db->select(
		implode(',', $dbFields),
		$tv_table.' AS tvs LEFT JOIN '.$rel_table.' AS rel ON rel.tmplvarid = tvs.id',
		implode(' AND ', $where)
	);
	
	$recordCount = $modx->db->getRecordCount($result);
	
	// If we have results, return them, otherwise return false
	if ($recordCount == 0){
		return false;
	}else{
		//If return of an associative array is required
		if ($resultKey !== false){
			$rsArray = array();
			
			while ($row = $modx->db->getRow($result)){
				//If result contains the result key
				if (array_key_exists($resultKey, $row)){
					$rsArray[$row[$resultKey]] = $row;
				}else{
					$rsArray[] = $row;
				}
			}
			
			return $rsArray;
		}else{
			return $modx->db->makeArray($result);
		}
	}
}

/**
 * getTplMatchedFields
 * @version 1.0.2 (2014-03-27)
 * 
 * @desc Returns the array that contains only those of passed fields/TVs which are used in the template.
 * 
 * @param $fields {comma separated string; array} - Document fields or TVs names. @required
 * @param $tvTypes {comma separated string; array} - TVs types, e.g. image, text. Default: ''.
 * @param $tempaleId {integer} - Template ID. Default: $mm_current_page['template'].
 * 
 * @return {array; false}
 */
function getTplMatchedFields($fields, $tvTypes = '', $tempaleId = ''){
	$fields = makeArray($fields);
	
	//$fields is required
	if (empty($fields)){return false;}
	
	global $mm_fields;
	
	//Template of current document by default
	if (empty($tempaleId)){
		global $mm_current_page;
		
		$tempaleId = $mm_current_page['template'];
	}
	
	$docFields = array();
	
	//Only document fields
	foreach ($fields as $field){
		if (isset($mm_fields[$field]) && !$mm_fields[$field]['tv']){
			$docFields[] = $field;
		}
	}
	
	//If $fields contains no TVs
	if (count($docFields) == count($fields)){
		$fields = $docFields;
	}else{
		//Get specified TVs for this template
		$fields = tplUseTvs($tempaleId, $fields, $tvTypes, 'name', 'name');
		
		//If there are no appropriate TVs
		if ($fields == false){
			if (!empty($docFields)){
				$fields = $docFields;
			}
		}else{
			$fields = array_merge(array_keys($fields), $docFields);
		}
	}
	
	return $fields;
}

/**
 * makeSqlList
 * @version 1.0.2 (2014-03-29)
 * 
 * @desc Create a MySQL-safe list from an array.
 * 
 * @param $arr {array; comma separated string} - Values.
 */
function makeSqlList($arr){
	global $modx;
	
	$arr = makeArray($arr);
	
	foreach($arr as $k => $tv){
        //if (substr($tv, 0, 2) == 'tv'){$tv=substr($tv,2);}
		// Escape them for MySQL
		$arr[$k] = "'".$modx->db->escape($tv)."'";
	}
	
	$sql = " (".implode(',', $arr).") ";
	
	return $sql;
}

/**
 * includeJsCss
 * @version 1.3.1 (2013-12-10)
 * 
 * @desc Generates the code needed to include an external script file.
 * 
 * @param $source {string} - The URL of the external script or code (if $plaintext == true). @required
 * @param $output_type {'js'; 'html'} - Either js or html - depending on where the output is appearing. Default: 'js'.
 * @param $name {string} - Script name. Default: ''.
 * @param $version {string} - Script version. Default: ''.
 * @param $plaintext {boolean} - Is this plaintext? Default: false.
 * @param $type {''; 'js'; 'css'} - Type of source (required if $plaintext == true). Default: ''.
 * 
 * @return {string} - Code.
 */
function includeJsCss($source, $output_type = 'js', $name = '', $version = '', $plaintext = false, $type = ''){
	global $modx, $mm_includedJsCss;
	
	$useThisVer = true;
	$result = '';
	
	if ($plaintext){
		if (empty($name) || empty($version) || empty($type)){
			return $result;
		}
		
		$nameVersion = array(
			'name' => $name,
			'version' => $version,
			'extension' => $type
		);
	}else{
		if (empty($name) || empty($version)){
			$nameVersion = ddTools::parseFileNameVersion($source);
		}else{
			$temp = pathinfo($source);
			
			$nameVersion = array(
				'name' => $name,
				'version' => $version,
				'extension' => !empty($type) ? $type : ($temp['extension'] ? $temp['extension'] : 'js')
			);
		}
	}
	
	//If this script is already included
	if (isset($mm_includedJsCss[$nameVersion['name']])){
		//If old < new, use new, else — old
		$useThisVer = version_compare($mm_includedJsCss[$nameVersion['name']]['version'], $nameVersion['version'], '<');
	}else{
		//Add
		$mm_includedJsCss[$nameVersion['name']] = array();
	}
	
	//If the new version is used
	if ($useThisVer){
		//Save the new version
		$mm_includedJsCss[$nameVersion['name']]['version'] = $nameVersion['version'];
		
		$result = $source;
		
		if ($nameVersion['extension'] == 'css'){
			if ($plaintext){
				$result = '<style type="text/css">'.$result.'</sty\'+\'le>';
			}else{
				$result = '<link href="'.$result.'" rel="stylesheet" type="text/css" />';
			}
		}else{
			if ($plaintext){
				$result = '<script type="text/javascript" charset="'.$modx->config['modx_charset'].'">'.$result.'</script>';
			}else{
				$result = '<script src="'.$result.'" type="text/javascript"></script>';
			}
			
			if ($output_type == 'js'){
				$result = str_replace('</script>', '</scr\'+\'ipt>', $result);
			}
		}
		
		if ($output_type == 'js'){
			$result = '$j("head").append(\''.$result.'\');';
		}
		
		$result = $result."\n";
	}
	
	return $result;
}

/**
 * @deprecated, use the includeJsCss()
 */
function includeJs($url, $output_type = 'js', $name = '', $version = ''){
	return includeJsCss($url, $output_type, $name, $version);
}

/**
 * @deprecated, use the includeJsCss()
 */
function includeCss($url, $output_type = 'js'){
	return includeJsCss($url, $output_type);
}

/**
 * prepareTabId
 * @version 1.0 (2013-05-21)
 * 
 * @desc Prepare id of a tab.
 * 
 * @param $id {string} - Tab id.
 * 
 * @return {string} - Tab id.
 */
function prepareTabId($id){
	//General tab by default
	if ($tab == ''){$tab = 'general';}
	
	//If it's one of the default tabs, we need to get the capitalisation right
	switch ($id){
		case 'general':
		case 'settings':
		case 'access':
		case 'meta': // version 1.0.0 only, removed in 1.0.1
			$id = ucfirst($id);
		break;
	}
	
	return 'tab'.$id;
}

/**
 * prepareSectionId
 * @version 1.1 (2014-05-25)
 * 
 * @desc Prepare id of a section.
 * 
 * @param $id {string} - Section id.
 * 
 * @return {string} - Section id.
 */
function prepareSectionId($id){
	switch ($id){
		case 'content':
			$id = 'content';
		break;
		
		case 'tvs':
			$id = 'tv';
		break;
		
		default:
			$id = 'ddSection'.$id;
		break;
	}
	
	return $id;
}
?>