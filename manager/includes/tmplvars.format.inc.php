<?php
/*
 * Template Variable Display Format
 * Created by Raymond Irving Feb, 2005
 */

// Added by Raymond 20-Jan-2005
/**
 * @param string $name
 * @param string $value
 * @param string $format
 * @param string $paramstring
 * @param string $tvtype
 * @param string $docid
 * @param string $sep
 * @return mixed|string
 */
function getTVDisplayFormat($name, $value, $format, $paramstring = "", $tvtype = "", $docid = "", $sep = '') {

    $modx = evolutionCMS();
    $o = '';

    // process any TV commands in value
	$docid = (int)$docid > 0 ? (int)$docid : $modx->documentIdentifier;
	$value = ProcessTVCommand($value, $name, $docid);

	$params = array();
	if($paramstring) {
		$cp = explode("&", $paramstring);
		foreach($cp as $p => $v) {
			$v = trim($v); // trim
			$ar = explode("=", $v);
			if(is_array($ar) && count($ar) == 2) {
				$params[$ar[0]] = decodeParamValue($ar[1]);
			}
		}
	}

	$id = "tv$name";
	switch($format) {
		case 'image':
			$images = parseInput($value, '||', 'array');
			foreach($images as $image) {
				if(!is_array($image)) {
					$image = explode('==', $image);
				}
				$src = $image[0];

				if($src) {
					// We have a valid source
					$attributes = '';
					$attr = array(
						'class' => $params['class'],
						'src' => $src,
						'id' => ($params['id'] ? $params['id'] : ''),
						'alt' => $modx->htmlspecialchars($params['alttext']),
						'style' => $params['style']
					);
					if(isset($params['align']) && $params['align'] != 'none') {
						$attr['align'] = $params['align'];
					}
					foreach($attr as $k => $v) $attributes .= ($v ? ' ' . $k . '="' . $v . '"' : '');
					$attributes .= ' ' . $params['attrib'];

					// Output the image with attributes
					$o .= '<img' . rtrim($attributes) . ' />';
				}
			}
			break;

		case "delim":    // display as delimitted list
			$value = parseInput($value, "||");
			$p = $params['format'] ? $params['format'] : " ";
			if($p == "\\n") {
				$p = "\n";
			}
			$o = str_replace("||", $p, $value);
			break;

		case "string":
			$value = parseInput($value);
			$format = strtolower($params['format']);
			if($format == 'upper case') {
				$o = strtoupper($value);
			} else if($format == 'lower case') {
				$o = strtolower($value);
			} else if($format == 'sentence case') {
				$o = ucfirst($value);
			} else if($format == 'capitalize') {
				$o = ucwords($value);
			} else {
				$o = $value;
			}
			break;

		case "date":
			if($value != '' || $params['default'] == 'Yes') {
				if(empty($value)) {
					$value = 'now';
				}
				$timestamp = getUnixtimeFromDateString($value);
				$p = $params['format'] ? $params['format'] : "%A %d, %B %Y";
				$o = strftime($p, $timestamp);
			} else {
				$value = '';
			}
			break;

		case "hyperlink":
			$value = parseInput($value, "||", "array");
			$o = '';
			$countValue = count($value);
			for($i = 0; $i < $countValue; $i++) {
				list($name, $url) = is_array($value[$i]) ? $value[$i] : explode("==", $value[$i]);
				if(!$url) {
					$url = $name;
				}
				if($url) {
					if($o) {
						$o .= '<br />';
					}
					$attributes = '';
					// setup the link attributes
					$attr = array(
						'href' => $url,
						'title' => $params['title'] ? $modx->htmlspecialchars($params['title']) : $name,
						'class' => $params['class'],
						'style' => $params['style'],
						'target' => $params['target'],
					);
					foreach($attr as $k => $v) $attributes .= ($v ? ' ' . $k . '="' . $v . '"' : '');
					$attributes .= ' ' . $params['attrib']; // add extra

					// Output the link
					$o .= '<a' . rtrim($attributes) . '>' . ($params['text'] ? $modx->htmlspecialchars($params['text']) : $name) . '</a>';
				}
			}
			break;

		case "htmltag":
			$value = parseInput($value, "||", "array");
			$tagid = $params['tagid'];
			$tagname = ($params['tagname']) ? $params['tagname'] : 'div';
			$o = '';
			// Loop through a list of tags
            $countValue = count($value);
			for($i = 0; $i < $countValue; $i++) {
				$tagvalue = is_array($value[$i]) ? implode(' ', $value[$i]) : $value[$i];
				if(!$tagvalue) {
					continue;
				}

				$attributes = '';
				$attr = array(
					'id' => ($tagid ? $tagid : $id),
					// 'tv' already added to id
					'class' => $params['class'],
					'style' => $params['style'],
				);
				foreach($attr as $k => $v) $attributes .= ($v ? ' ' . $k . '="' . $v . '"' : '');
				$attributes .= ' ' . $params['attrib']; // add extra

				// Output the HTML Tag
				$o .= '<' . $tagname . rtrim($attributes) . '>' . $tagvalue . '</' . $tagname . '>';
			}
			break;

		case "richtext":
			$value = parseInput($value);
			$w = $params['w'] ? $params['w'] : '100%';
			$h = $params['h'] ? $params['h'] : '400px';
			$richtexteditor = $params['edt'] ? $params['edt'] : "";
			$o = '<div class="MODX_RichTextWidget"><textarea id="' . $id . '" name="' . $id . '" style="width:' . $w . '; height:' . $h . ';">';
			$o .= $modx->htmlspecialchars($value);
			$o .= '</textarea></div>';
			$replace_richtext = array($id);
			// setup editors
			if(!empty($replace_richtext) && !empty($richtexteditor)) {
				// invoke OnRichTextEditorInit event
				$evtOut = $modx->invokeEvent("OnRichTextEditorInit", array(
					'editor' => $richtexteditor,
					'elements' => $replace_richtext,
					'forfrontend' => 1,
					'width' => $w,
					'height' => $h
				));
				if(is_array($evtOut)) {
					$o .= implode("", $evtOut);
				}
			}
			break;

		case "unixtime":
			$value = parseInput($value);
			$o = getUnixtimeFromDateString($value);
			break;

		case "viewport":
			$value = parseInput($value);
			$id = '_' . time();
			if(!$params['vpid']) {
				$params['vpid'] = $id;
			}
			$sTag = "<iframe";
			$eTag = "</iframe>";
			$autoMode = "0";
			$w = $params['width'];
			$h = $params['height'];
			if($params['stretch'] == 'Yes') {
				$w = "100%";
				$h = "100%";
			}
			if($params['asize'] == 'Yes' || ($params['awidth'] == 'Yes' && $params['aheight'] == 'Yes')) {
				$autoMode = "3";  //both
			} else if($params['awidth'] == 'Yes') {
				$autoMode = "1"; //width only
			} else if($params['aheight'] == 'Yes') {
				$autoMode = "2";    //height only
			}

			$modx->regClientStartupScript(MODX_MANAGER_URL . "media/script/bin/viewport.js", array(
				'name' => 'viewport',
				'version' => '0',
				'plaintext' => false
			));
			$o = $sTag . " id='" . $params['vpid'] . "' name='" . $params['vpid'] . "' ";
			if($params['class']) {
				$o .= " class='" . $params['class'] . "' ";
			}
			if($params['style']) {
				$o .= " style='" . $params['style'] . "' ";
			}
			if($params['attrib']) {
				$o .= $params['attrib'] . " ";
			}
			$o .= "scrolling='" . ($params['sbar'] == 'No' ? "no" : ($params['sbar'] == 'Yes' ? "yes" : "auto")) . "' ";
			$o .= "src='" . $value . "' frameborder='" . $params['borsize'] . "' ";
			$o .= "onload=\"window.setTimeout('ResizeViewPort(\\'" . $params['vpid'] . "\\'," . $autoMode . ")',100);\" width='" . $w . "' height='" . $h . "' ";
			$o .= ">";
			$o .= $eTag;
			break;

		case "datagrid":
			include_once MODX_MANAGER_PATH . "includes/controls/datagrid.class.php";
			$grd = new DataGrid('', $value);

			$grd->noRecordMsg = $params['egmsg'];

			$grd->columnHeaderClass = $params['chdrc'];
			$grd->cssClass = $params['tblc'];
			$grd->itemClass = $params['itmc'];
			$grd->altItemClass = $params['aitmc'];

			$grd->columnHeaderStyle = $params['chdrs'];
			$grd->cssStyle = $params['tbls'];
			$grd->itemStyle = $params['itms'];
			$grd->altItemStyle = $params['aitms'];

			$grd->columns = $params['cols'];
			$grd->fields = $params['flds'];
			$grd->colWidths = $params['cwidth'];
			$grd->colAligns = $params['calign'];
			$grd->colColors = $params['ccolor'];
			$grd->colTypes = $params['ctype'];

			$grd->cellPadding = $params['cpad'];
			$grd->cellSpacing = $params['cspace'];
			$grd->header = $params['head'];
			$grd->footer = $params['foot'];
			$grd->pageSize = $params['psize'];
			$grd->pagerLocation = $params['ploc'];
			$grd->pagerClass = $params['pclass'];
			$grd->pagerStyle = $params['pstyle'];
			$o = $grd->render();
			break;

		case 'htmlentities':
			$value = parseInput($value);
			if($tvtype == 'checkbox' || $tvtype == 'listbox-multiple') {
				// remove delimiter from checkbox and listbox-multiple TVs
				$value = str_replace('||', '', $value);
			}
			$o = htmlentities($value, ENT_NOQUOTES, $modx->config['modx_charset']);
			break;

		case 'custom_widget':
			$widget_output = '';
			$o = '';
			/* If we are loading a file */
			if(substr($params['output'], 0, 5) == "@FILE") {
				$file_name = MODX_BASE_PATH . trim(substr($params['output'], 6));
				if(!file_exists($file_name)) {
					$widget_output = $file_name . ' does not exist';
				} else {
					$widget_output = file_get_contents($file_name);
				}
			} elseif(substr($params['output'], 0, 8) == '@INCLUDE') {
				$file_name = MODX_BASE_PATH . trim(substr($params['output'], 9));
				if(!file_exists($file_name)) {
					$widget_output = $file_name . ' does not exist';
				} else {
					/* The included file needs to set $widget_output. Can be string, array, object */
					include $file_name;
				}
			} elseif(substr($params['output'], 0, 6) == '@CHUNK' && $value !== '') {
				$chunk_name = trim(substr($params['output'], 7));
				$widget_output = $modx->getChunk($chunk_name);
			} elseif(substr($params['output'], 0, 5) == '@EVAL' && $value !== '') {
				$eval_str = trim(substr($params['output'], 6));
				$widget_output = eval($eval_str);
			} elseif($value !== '') {
				$widget_output = $params['output'];
			} else {
				$widget_output = '';
			}
			if(is_string($widget_output)) {
				$_ = $modx->config['enable_filter'];
				$modx->config['enable_filter'] = 1;
				$widget_output = $modx->parseText($widget_output, array('value' => $value));
				$modx->config['enable_filter'] = $_;
				$o = $modx->parseDocumentSource($widget_output);
			} else {
				$o = $widget_output;
			}
			break;

		default:
			$value = parseInput($value);
			if($tvtype == 'checkbox' || $tvtype == 'listbox-multiple') {
				// add separator
				$value = explode('||', $value);
				$value = implode($sep, $value);
			}
			$o = $value;
			break;
	}
	return $o;
}

/**
 * @param string $s
 * @return string
 */
function decodeParamValue($s) {
	$s = str_replace("%3D", '=', $s); // =
	return str_replace("%26", '&', $s); // &
}

/**
 * returns an array if a delimiter is present. returns array is a recordset is present
 *
 * @param $src
 * @param string $delim
 * @param string $type
 * @param bool $columns
 * @return array|string
 */
function parseInput($src, $delim = "||", $type = "string", $columns = true) { // type can be: string, array
    $modx = evolutionCMS();
	if($modx->db->isResult($src)) {
		// must be a recordset
		$rows = array();
		while($cols = $modx->db->getRow($src, 'num')) $rows[] = ($columns) ? $cols : implode(" ", $cols);
		return ($type == "array") ? $rows : implode($delim, $rows);
	} else {
		// must be a text
		if($type == "array") {
			return explode($delim, $src);
		} else {
			return $src;
		}
	}
}

/**
 * @param string $value
 * @return bool|false|int
 */
function getUnixtimeFromDateString($value) {
	$timestamp = false;
	// Check for MySQL or legacy style date
	$date_match_1 = '/^([0-9]{2})-([0-9]{2})-([0-9]{4})\ ([0-9]{2}):([0-9]{2}):([0-9]{2})$/';
	$date_match_2 = '/^([0-9]{4})-([0-9]{2})-([0-9]{2})\ ([0-9]{2}):([0-9]{2}):([0-9]{2})$/';
	$matches = array();
	if(strpos($value, '-') !== false) {
		if(preg_match($date_match_1, $value, $matches)) {
			$timestamp = mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[1], $matches[3]);
		} elseif(preg_match($date_match_2, $value, $matches)) {
			$timestamp = mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[3], $matches[1]);
		}
	}
	// If those didn't work, use strtotime to figure out the date
	if($timestamp === false || $timestamp === -1) {
		$timestamp = strtotime($value);
	}
	return $timestamp;
}
