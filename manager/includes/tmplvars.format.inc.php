<?php
/*
 * Template Variable Display Format
 * Created by Raymond Irving Feb, 2005
 */

	// Added by Raymond 20-Jan-2005
	function getTVDisplayFormat($name,$value,$format,$paramstring="",$tvtype="") {

		global $modx;
		global $base_path;
		
		// set media path for js scripts
		$modx->regClientStartupScript('<script type="text/javascript">var MODX_MEDIA_PATH = "'.(IN_MANAGER_MODE ? 'media':'manager/media').'";</script>');

		// process any TV commands in value
		$value = ProcessTVCommand($value, $name);
		
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

						$id = ($params['id'] ? 'id="'.$params['id'].'"' : '');
						$alt = htmlspecialchars($params['alttext']);
						$class = $params['class'];
						$style = $params['style'];
						$attributes = $params['attrib'];

$o .= <<<EOD
<img {$id} src="{$src}" alt="{$alt}" class="{$class}" style="{$style}" {$attributes} />
EOD;

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
				break;

			case "floater":
				$value = parseInput($value," ");
				$modx->regClientStartupScript("manager/media/script/bin/webelm.js");
				$o = "<script type=\"text/javascript\">";
				$o.= "	document.setIncludePath('manager/media/script/bin/');";					
				$o.= "	document.addEventListener('oninit',function(){document.include('dynelement');document.include('floater');});";
				$o.= "	document.addEventListener('onload',function(){var o = new Floater('$id','".addslashes(mysql_escape_string($value))."','".$params['x']."','".$params['y']."','".$params['pos']."','".$params['gs']."');});";
				$o.= "</script>";
				$o.= "<script type=\"text/javascript\">Floater.Render('$id','".$params['width']."','".$params['height']."','".$params['class']."','".$params['style']."');</script>";				
				break;
								
			case "marquee":
				$transfx = ($params['tfx']=='Horizontal') ? 2:1;
				$value = parseInput($value," ");
				$modx->regClientStartupScript("manager/media/script/bin/webelm.js");
				$o = "<script type=\"text/javascript\">";
				$o.= "	document.setIncludePath('manager/media/script/bin/');";					
				$o.= "	document.addEventListener('oninit',function(){document.include('dynelement');document.include('marquee');});";
				$o.= "	document.addEventListener('onload',function(){var o = new Marquee('$id','".addslashes(mysql_escape_string($value))."','".$params['speed']."','".($params['pause']=='Yes'? 1:0)."','".$transfx."'); o.start()});";
				$o.= "</script>";
				$o.= "<script type=\"text/javascript\">Marquee.Render('$id','".$params['width']."','".$params['height']."','".$params['class']."','".$params['style']."');</script>";
				break;

			case "ticker":
				$transfx = ($params['tfx']=='Fader') ? 2:1;
				$delim = ($params['delim'])? $params['delim']:"||";
				if ($delim=="\\n") $delim = "\n";
				$value = parseInput($value,$delim,"array");
				$modx->regClientStartupScript("manager/media/script/bin/webelm.js");
				$o = '<script type="text/javascript">';
				$o.= "	document.setIncludePath('manager/media/script/bin/');";
				$o.= "	document.addEventListener('oninit',function(){document.include('dynelement');document.include('ticker');});";
				$o.= "	document.addEventListener('onload',function(){";
				$o.= "	var o = new Ticker('$id','".$params['delay']."','".$transfx."'); ";
				for($i=0;$i<count($value);$i++){
					$o.= "	o.addMessage('".addslashes(mysql_escape_string($value[$i]))."');";
				}
				$o.= "	});";
				$o.= "</script>";
				$o.= "<script type=\"text/javascript\">Ticker.Render('$id','".$params['width']."','".$params['height']."','".$params['class']."','".$params['style']."');</script>";
				break;
				
			case "hyperlink":
				$value = parseInput($value,"||","array");
				for($i = 0;$i<count($value); $i++){
					list($name,$url) = is_array($value[$i]) ? $value[$i]: explode("==",$value[$i]);
					if(!$url) $url = $name;
					if($o) $o.='<br />';
					$o.= "<a href='$url'"." title='".($params["title"] ? mysql_escape_string($params["title"]):$name)."'".($params["class"] ? " class='".$params["class"]."'":"").($params["style"] ? " style='".$params["style"]."'":"").($params["target"] ? " target='".$params["target"]."'":"").($params["attrib"] ? " ".mysql_escape_string($params["attrib"]) : "").">".($params["text"] ? mysql_escape_string($params["text"]) : $name)."</a>";
				}
				break;				
				
			case "htmltag":
				$value = parseInput($value,"||","array");
				$tagid = $params['tagid'];
				$tagname = ($params['tagname'])? $params['tagname']:'div';
				for($i = 0;$i<count($value); $i++){
					$tagvalue = is_array($value[$i]) ? implode(" ",$value[$i]): $value[$i];
					if(!$url) $url = $name;
					$o.= "<$tagname id='".($tagid ? $tagid:"tv".$id)."'".($params["class"] ? " class='".$params["class"]."'":"").($params["style"] ? " style='".$params["style"]."'":"").($params["attrib"]? " ".$params["attrib"]:"").">".$tagvalue."</$tagname>";
				}				
				break;

			case "richtext":
				$value = parseInput($value);
				$w = $params['w']? $params['w']:'100%';
				$h = $params['h']? $params['h']:'400px';
				$richtexteditor = $params['edt']? $params['edt']: "";
				$modx->regClientStartupScript("manager/media/script/bin/webelm.js");
				$o = '<div class="MODX_RichTextWidget"><textarea id="'.$id.'" name="'.$id.'" style="width:'.$w.'; height:'.$h.';">';
				$o.= htmlspecialchars($value);
				$o.= '</textarea></div>';
				$replace_richtext = array($id);
				// setup editors
				if (!empty($replace_richtext) && !empty($richtexteditor)) {
					// invoke OnRichTextEditorInit event
					$evtOut = $modx->invokeEvent("OnRichTextEditorInit",
													array(
														'editor'		=> $richtexteditor,
														'elements'		=> $replace_richtext,
														'forfrontend'=> 1,
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
				include_once $base_path."/manager/includes/controls/datagrid.class.php";
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
				print_r($params);
				break;
				
			default:
				$value = parseInput($value);
				if($tvtype=='checkbox'||$tvtype=='listbox-multiple') {
					// remove delimiter from checkbox and listbox-multiple TVs
					$value = str_replace('||','',$value);
					
                // fix FS 307 to preserve html entities in text/textarea input fields
                } elseif($tvtype=='rawtext'||$tvtype=='rawtextarea') {
                    $value = $value;
                } elseif($tvtype=='text'||$tvtype=='textarea'||$tvtype=='textareamini') {
                    $value = htmlentities($value);
                }
                // end FS 307 fix
                
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
