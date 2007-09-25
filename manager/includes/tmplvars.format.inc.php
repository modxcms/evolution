<?php
/*
 * Template Variable Display Format
 * Created by Raymond Irving Feb, 2005
 */

	// Added by Raymond 20-Jan-2005
	function getTVDisplayFormat($name,$value,$format,$paramstring="",$tvtype="",$docid="") {

		global $modx;

		// set media path for js scripts
		$modx->regClientStartupScript('<script type="text/javascript">var MODX_MEDIA_PATH = "'.(IN_MANAGER_MODE ? 'media':'manager/media').'";</script>');

		// process any TV commands in value
		$docid= intval($docid) ? intval($docid) : $modx->documentIdentifier;
		$value = ProcessTVCommand($value, $name, $docid);

		$param = array();
		if($paramstring){
			$cp = split("&",$paramstring);
			foreach($cp as $p => $v){
				$v = trim($v); // trim
				$ar = split("=",$v);
				if (is_array($ar) && count($ar)==2) {
					$params[$ar[0]] = decodeParamValue($ar[1]);
				}
			}
		}

		$id = "tv$name";
		switch($format){
			case 'image':
				$images = parseInput($value, '||', 'array');
				$o = '';
				foreach($images as $image){
					if(!is_array($image)) { $image = explode('==',$image); }
					$src = $image[0];

					if($src) {
						// We have a valid source
						$attributes = '';
						$attr = array(
							'class' => $params['class'],
							'src' => $src,
							'id' => ($params['id'] ? $params['id'] : ''),
							'alt' => htmlspecialchars($params['alttext']),
							'style' => $params['style']
						);
						foreach ($attr as $k => $v) $attributes.= ($v ? ' '.$k.'="'.$v.'"' : '');
						$attributes .= ' '.$params['attrib'];

						// Output the image with attributes
						$o .= '<img'.rtrim($attributes).' />';
					}
				}
			break;

			case "delim":	// display as delimitted list
				$value = parseInput($value,"||");
				$p = $params['format'] ? $params['format']:",";
				if ($p=="\\n") $p = "\n";
				$o = str_replace("||",$p,$value);
				break;

			case "string":
				$value = parseInput($value);
				$format = strtolower($params['format']);
				if($format=='upper case') $o = strtoupper($value);
				else if($format=='lower case') $o = strtolower($value);
				else if($format=='sentence case') $o = ucfirst($value);
				else if($format=='capitalize') $o = ucwords($value);
				else $o = $value;
				break;

			case "date":
				if ($value !='' || $params['default']=='Yes') {
					$value = parseInput($value);
					// Check for MySQL style date - Adam Crownoble 8/3/2005
					$date_match = '^([0-9]{2})-([0-9]{2})-([0-9]{4})\ ([0-9]{2}):([0-9]{2}):([0-9]{2})$';
					$matches= array();
					if(strpos($value,'-')!==false && ereg($date_match, $value, $matches)) {
						$timestamp = mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[1], $matches[3]);
					}
					else { // If it's not a MySQL style date, then use strtotime to figure out the date
						$timestamp = strtotime($value);
					}
					$p = $params['format'] ? $params['format']:"%A %d, %B %Y";
					$o = strftime($p,$timestamp);
				} else {
					$value='';
				}
				break;

			case "floater":
				$value = parseInput($value," ");
				$modx->regClientStartupScript("manager/media/script/mootools/mootools.js");
				$modx->regClientStartupScript("manager/media/script/mootools/moodx.js");
				$class = (!empty($params['class']) ? " class=\"".$params['class']."\"" : "");
				$style = (!empty($params['style']) ? " style=\"".$params['style']."\"" : "");
				$o .= "\n<div id=\"".$id."\"".$class.$style.">".$value."</div>\n";
				$o .= "<script type=\"text/javascript\">\n";
				$o .= "	window.addEvent('domready', function(){\n";
				$o .= "		var modxFloat = new MooFloater(\$(\"".$id."\"),{\n";
				$o .= "			width: '".$params['width']."',\n";
				$o .= "			height: '".$params['height']."',\n";
				$o .= "			position: '".$params['pos']."',\n";
				$o .= "			glidespeed: ".$params['gs'].",\n";
				$o .= "			offsetx: ".intval($params['x']).",\n";
				$o .= "			offsety: ".intval($params['y'])."\n";
				$o .= "		});\n";
				$o .= "	});\n";
				$o .= "</script>\n";
				break;

			case "marquee":
				$value = parseInput($value," ");
				$modx->regClientStartupScript("manager/media/script/mootools/mootools.js");
				$modx->regClientStartupScript("manager/media/script/mootools/moodx.js");
				$class = (!empty($params['class']) ? " class=\"".$params['class']."\"" : "");
				$style = (!empty($params['style']) ? " style=\"".$params['style']."\"" : "");
				$o .= "\n<div id=\"".$id."\"".$class.$style."><div id=\"marqueeContent\">".$value."</div></div>\n";
				$o .= "<script type=\"text/javascript\">\n";
				$o .= "	window.addEvent('domready', function(){\n";
				$o .= "		var modxMarquee = new MooMarquee(\$(\"".$id."\"),{\n";
				$o .= "			width: '".$params['width']."',\n";
				$o .= "			height: '".$params['height']."',\n";
				$o .= "			speed: ".$params['speed'].",\n";
				$o .= "			modifier: ".$params['modifier'].",\n";
				$o .= "			mousepause: '".$params['pause']."',\n";
				$o .= "			direction: '".$params['tfx']."'\n";
				$o .= "		});\n";
				$o .= "	});\n";
				$o .= "</script>\n";
				break;

			case "ticker":
				$modx->regClientStartupScript("manager/media/script/mootools/mootools.js");
				$modx->regClientStartupScript("manager/media/script/mootools/moodx.js");
				$class = (!empty($params['class']) ? " class=\"".$params['class']."\"" : "");
				$style = (!empty($params['style']) ? " style=\"".$params['style']."\"" : "");
				$o .= "\n<div id=\"".$id."\"".$class.$style.">\n";
				if(!empty($value)){
					$delim = ($params['delim'])? $params['delim']:"||";
					if ($delim=="\\n") $delim = "\n";
					$value = parseInput($value,$delim,"array");
					if(count($value)>0){
						for($i=0;$i<count($value);$i++){
							$o.= "    <div class=\"mooticker\">".$value[$i]."</div>\n";
						}
					}
				}
				$o .= "</div>\n";
				$o .= "<script type=\"text/javascript\">\n";
				$o .= "	window.addEvent('domready', function(){\n";
				$o .= "		var modxTicker = new MooTicker(\$(\"".$id."\"),{\n";
				$o .= "			width: '".$params['width']."',\n";
				$o .= "			height: '".$params['height']."',\n";
				$o .= "			interval: ".$params['delay']."\n";
				$o .= "		});\n";
				$o .= "	});\n";
				$o .= "</script>\n";
				break;

			case "hyperlink":
				$value = parseInput($value,"||","array");
				for ($i = 0; $i < count($value); $i++) {
					list($name,$url) = is_array($value[$i]) ? $value[$i]: explode("==",$value[$i]);
					if (!$url) $url = $name;
					if ($url) {
						if($o) $o.='<br />';
						$attributes = '';
						// setup the link attributes
						$attr = array(
							'href' => $url,
							'title' => $params['title'] ? htmlspecialchars($params['title']) : $name,
							'class' => $params['class'],
							'style' => $params['style'],
							'target' => $params['target'],
						);
						foreach ($attr as $k => $v) $attributes .= ($v ? ' '.$k.'="'.$v.'"' : '');
						$attributes .= ' '.$params['attrib']; // add extra

						// Output the link
						$o .= '<a'.rtrim($attributes).'>'. ($params['text'] ? htmlspecialchars($params['text']) : $name) .'</a>';
					}
				}
				break;

			case "htmltag":
				$value = parseInput($value,"||","array");
				$tagid = $params['tagid'];
				$tagname = ($params['tagname'])? $params['tagname']:'div';
				// Loop through a list of tags
				for ($i = 0; $i < count($value); $i++) {
					$tagvalue = is_array($value[$i]) ? implode(' ', $value[$i]) : $value[$i];
					if (!$tagvalue) continue;

					$attributes = '';
					$attr = array(
						'id' => ($tagid ? $tagid : $id), // 'tv' already added to id
						'class' => $params['class'],
						'style' => $params['style'],
					);
					foreach ($attr as $k => $v) $attributes.= ($v ? ' '.$k.'="'.$v.'"' : '');
					$attributes .= ' '.$params['attrib']; // add extra 

					// Output the HTML Tag
					$o .= '<'.$tagname.rtrim($attributes).'>'.$tagvalue.'</'.$tagname.'>';
				}
				break;

			case "richtext":
				$value = parseInput($value);
				$w = $params['w']? $params['w']:'100%';
				$h = $params['h']? $params['h']:'400px';
				$richtexteditor = $params['edt']? $params['edt']: "";
				$o = '<div class="MODX_RichTextWidget"><textarea id="'.$id.'" name="'.$id.'" style="width:'.$w.'; height:'.$h.';">';
				$o.= htmlspecialchars($value);
				$o.= '</textarea></div>';
				$replace_richtext = array($id);
				// setup editors
				if (!empty($replace_richtext) && !empty($richtexteditor)) {
					// invoke OnRichTextEditorInit event
					$evtOut = $modx->invokeEvent("OnRichTextEditorInit", array(
						'editor'		=> $richtexteditor,
						'elements'		=> $replace_richtext,
						'forfrontend'		=> 1,
						'width'			=> $w,
						'height'		=> $h
					));
					if(is_array($evtOut)) $o.= implode("",$evtOut);
				}
				break;

			case "unixtime":
				$value = parseInput($value);
				// Check for MySQL style date - Adam Crownoble 8/3/2005
				$date_match = '^([0-9]{2})-([0-9]{2})-([0-9]{4})\ ([0-9]{2}):([0-9]{2}):([0-9]{2})$';
				$matches= array();
				if(strpos($value,'-')!==false && ereg($date_match, $value, $matches)) {
					$timestamp = mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[1], $matches[3]);
				}
				else { // If it's not a MySQL style date, then use strtotime to figure out the date
					$timestamp = strtotime($value);
				}
				$o = $timestamp;
				break;

			case "viewport":
				$value = parseInput($value);
				$id = '_'.time();
				if(!$params['vpid']) $params['vpid'] = $id;
				if($_SESSION['browser']=='ns' && $_SESSION['browser_version']<'5.0') {
					$sTag = "<ilayer"; $eTag = "</ilayer>";
				}
				else {
					$sTag = "<iframe"; $eTag = "</iframe>";
				}
				$autoMode = "0";
				$w = $params['width'];
				$h = $params['height'];
				if ($params['stretch']=='Yes') {
					$w = "100%";
					$h = "100%";
				}
				if ($params['asize']=='Yes' || ($params['awidth']=='Yes' && $params['aheight']=='Yes')) {
					$autoMode = "3";  //both
				}
				else if ($params['awidth']=='Yes') {
					$autoMode = "1"; //width only
				}
				else if ($params['aheight']=='Yes') {
					$autoMode = "2";	//height only
				}

				$modx->regClientStartupScript("manager/media/script/bin/viewport.js");
				$o =  $sTag." id='".$params['vpid']."' name='".$params['vpid']."' ";
				if ($params['class']) $o.= " class='".$params['class']."' ";
				if ($params['style']) $o.= " style='".$params['style']."' ";
				if ($params['attrib']) $o.= $params['attrib']." ";
				$o.= "scrolling='".($params['sbar']=='No' ? "no":($params['sbar']=='Yes' ? "yes":"auto"))."' ";
				$o.= "src='".$value."' frameborder='".$params['borsize']."' ";
				$o.= "onload=\"window.setTimeout('ResizeViewPort(\\\\'".$params['vpid']."\\\\',".$autoMode.")',100);\" width='".$w."' height='".$h."' ";
				$o.= ">";
				$o.= $eTag;
				break;

			case "datagrid":
				include_once MODX_BASE_PATH."manager/includes/controls/datagrid.class.php";
				$grd = new DataGrid('',$value);

				$grd->noRecordMsg		=$params['egmsg'];

				$grd->columnHeaderClass	=$params['chdrc'];
				$grd->cssClass			=$params['tblc'];
				$grd->itemClass			=$params['itmc'];
				$grd->altItemClass		=$params['aitmc'];

				$grd->columnHeaderStyle	=$params['chdrs'];
				$grd->cssStyle			=$params['tbls'];
				$grd->itemStyle			=$params['itms'];
				$grd->altItemStyle		=$params['aitms'];

				$grd->columns			=$params['cols'];
				$grd->fields			=$params['flds'];
				$grd->colWidths			=$params['cwidth'];
				$grd->colAligns			=$params['calign'];
				$grd->colColors			=$params['ccolor'];
				$grd->colTypes			=$params['ctype'];

				$grd->cellPadding		=$params['cpad'];
				$grd->cellSpacing		=$params['cspace'];
				$grd->header			=$params['head'];
				$grd->footer			=$params['foot'];
				$grd->pageSize			=$params['psize'];
				$grd->pagerLocation		=$params['ploc'];
				$grd->pagerClass		=$params['pclass'];
				$grd->pagerStyle		=$params['pstyle'];
				$o = $grd->render();
				break;

			case 'htmlentities':
				$value= parseInput($value);
				if($tvtype=='checkbox'||$tvtype=='listbox-multiple') {
					// remove delimiter from checkbox and listbox-multiple TVs
					$value = str_replace('||','',$value);
				}
				$o = htmlentities($value, ENT_NOQUOTES, $modx->config['modx_charset']);
				break;

			default:
				$value = parseInput($value);
				if($tvtype=='checkbox'||$tvtype=='listbox-multiple') {
					// remove delimiter from checkbox and listbox-multiple TVs
					$value = str_replace('||','',$value);
				}
				$o = $value;
				break;
		}
		return $o;
	}

	function decodeParamValue($s){
		$s = str_replace("%3D",'=',$s); // =
		$s = str_replace("%26",'&',$s); // &
		return $s;
	}

	// returns an array if a delimiter is present. returns array is a recordset is present
	function parseInput($src, $delim="||", $type="string") { // type can be: string, array
		if (is_resource($src)) {
			// must be a recordset
			$rows = array();
			$nc = mysql_num_fields($src);
			while ($cols = mysql_fetch_row($src)) $rows[] = ($type=="array")? $cols : implode(" ",$cols);
			return ($type=="array")? $rows : implode($delim,$rows);
		}
		else {
			// must be a text
			if($type=="array") return explode($delim,$src);
			else return $src;
		}
	}

?>
