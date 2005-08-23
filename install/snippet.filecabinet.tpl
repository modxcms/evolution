/**
 *
 *	FileCabinet for MODx 
 *	Created by Raymond Irving, August 2005
 *
 *	Enable web users to upload files to the document tree (file cabinet)
 *
 *	Parameters:
 *		&action			- set cabinet action to either 'list', 'view' or 'upload' mode. defaults to list. view mode is handled internally. default to list mode
 *
 *	upload mode params	
 *		&filefolder		- (required) comma delimited folder ids use to store uploaded file documents. These are also used as categories.
 *		&postid			- document id to load after uploading file. Defaults to Site Start id.
 *		&canupload		- comma delimited web groups that can upload files during edit mode. leave blank for public uploads
 *		&badwords		- comma delimited list of words not allowed file document
 *		&template		- name of template to use for new file documents
 *		&headertpl		- header template (chunk name) to be inserted at the begining of the file content
 *		&footertpl		- footer template (chunk name) to be inserted at the end of the file content
 *		&formtpl		- file upload form template (chunk name) 
 *		&displaytpl		- file display or layout template (chunk name). 
 *		&showinmenu		- sets the flag to true or false (1|0) as to whether or not it shows in the menu. defaults to 0
 *		&filepath		- physical folder path relative to the site's base path where uploaded files are stored. defaults to assets/cache folder
 *		&filetypes		- comma delimited list of allowed file extensions. defaults to zip,txt
 *		&codecss		- sets the css class used to format code tags
 *
 *	list/default mode params	
 *		&filefolder		- folder id from which to display a list of uploaded files. defaults to current document
 *		&lstitmtpl		- template (chunk name) to be used when displaying list item 
 *
 *	Version 1.0 Beta
 *
 *	Mappped files
 *	----------------
 *	pagetitle		- file title	
 *	content 		- file notes/settings
 *	description		- url, version, license,has screenshot flag, has file flag
 *	introtext		- description	
 *	alias			- alias		
 *	parent			- category selection
 *	createdon		- date created
 *	createdby		- web user id stored as a negative number 
 *	published		- 1 
 *	cacheable		- 0 
 *	deleted			- 0 	
 *	deletedby		- file download count
 *	deletedon		- file views count
 *	richtext		- 0	
 *	hidemenu		- show/hide in menu	
 *	template		- document template 	
 *
 */

// get action
$action = isset($action) ? $action:'list';
 
// get user groups that can upload files
$uplgrp = isset($canupload) ? explode(",",$canupload):array();
$allowAnyUpload = count($uplgrp)==0 ? true : false;

// get file folders id where we should store file docs 
$filefolder = isset($filefolder) ? $filefolder:0;
if($action=='list' && $filefolder==0) $filefolder = $modx->documentIdentifier;
else if($action=='upload' && !$filefolder) { // check for required file folder
	return "No file folder specified.";
}

// get postid
$postid = isset($postid) ? $postid:0;

// get postback status
$isPostBack = isset($_POST['FileCabinetForm']) ? true:false;

// get menu status
$hidemenu = isset($showinmenu) && $showinmenu==1 ? 0 : 1;

// get file path
$filepath = isset($filepath) ? $filepath:'assets/cache';
if($action=='upload' && !is_dir($modx->config['base_path'].'/'.$filepath)){
	return 'You have entered an invalid or missing file path \''.$filepath.'\'';
}
elseif($action=='upload' && !is_writable($modx->config['base_path'].'/'.$filepath)){
	return 'Cannot write the file path \''.$filepath.'\'.';
}

// get code style
$codecss = isset($codecss) ? ' class="'.$codecss.'"' : ' style="background-color:#eeeeee;border-top:2px solid #e0e0e0;margin:0px;"';

// get allowed file types
$filetypes = isset($filetypes) ? explode(',',$filetypes):array('doc','xls','zip','txt');

// set allowed picture types
$pictypes = array('gif','png','jpg');

// get badwords
if(isset($badwords)) {
	$badwords = str_replace(' ','', $badwords);
	$badwords = "/".str_replace(',','|', $badwords)."/i";
}

// get header
$header = isset($headertpl) ? $modx->getChunk($headertpl):'';

// get footer
$footer = isset($footertpl) ? $modx->getChunk($footertpl):'';

// get template
$template = isset($template) ? $modx->db->getVal('SELECT id FROM '.$modx->getFullTableName('templates').' WHERE templatename=\''.mysql_escape_string($template).'\''):$modx->config['default_template'];

// set list item template
global $lstitmtpl;
if(isset($lstitmtpl)) $lstitmtpl = $modx->getChunk($lstitmtpl);
if(empty($lstitmtpl)) $lstitmtpl = '<div class="FileCabinet_list_item">
	<div class="FileCabinet_list_image"><a href="[+viewurl+]"><img src="[+image+]" border="0" /></a></div>
	<div class="FileCabinet_list_info">
		<span class="FileCabinet_list_title"><h2><a href="[+viewurl+]">[+title+]</a></h2></span>
		<span class="FileCabinet_list_version">Version: [+version+]</span>&nbsp; 
		<span class="FileCabinet_list_license">License: [+license+]</span>
		<div class="FileCabinet_list_desc">[+description+]</div>
		<span class="FileCabinet_list_author">Author: [+author+]</span>&nbsp;
		<span class="FileCabinet_list_date">Date: [+createdon+]</span>
	</div>
</div>';

// set display template
if(isset($displaytpl)) $displaytpl = $modx->getChunk($displaytpl);
if(empty($displaytpl)) $displaytpl = '<style>
	.FileCabinet_label_value {
		color:#505050;
		display:block;
		margin-left:100px;
		margin-bottom:5px;
	}
	.FileCabinet_label {
		width:300px;
		display:block;
		border-bottom:1px dotted #eeeeee;
		font-weight:bold;
		color:#A0A0A0;
		margin-bottom:2px;
	}
	.FileCabinet_Note_Title {
		color:#A0A0A0;
		font-size:16px;
		font-weight:bold;
		margin-top:10px;
		margin-bottom:10px;
	}
	.FileCabinet_Note {
		color:#707070;
	}
</style>
<script> 
	function loadScreenshot() {window.open("[+limage+]","_blank","width=430,height=430,status=yes,toolbar=no,menubar=no,location=no");} 	
	function downloadFile() {var fl="[+downurl+]"; if (fl) window.location.href = fl;} 	
</script>
<div>
	<div><h2>[+title+]</h2></div>
	<div><a href="javascript:history.go(-1);">&lt;&lt; Back</a>&nbsp;[+editlink+]</div><br />
	<div"><span class="FileCabinet_label">Author:</span> <span class="FileCabinet_label_value">[+author+]</span></div>
	<div><span class="FileCabinet_label">Version:</span> <span class="FileCabinet_label_value">[+version+]</span></div>
	<div><span class="FileCabinet_label">License:</span> <span class="FileCabinet_label_value">[+license+]</span></div>
	<div><span class="FileCabinet_label">Date created:</span> <span class="FileCabinet_label_value">[+createdon+]</span></div>
	<div><span class="FileCabinet_label">Website:</span> <span class="FileCabinet_label_value"><a id="extLink" href="[+website+]" target="_blank">[+website+]</a></span></div>
	<div><span class="FileCabinet_label">Cateory:</span> <span class="FileCabinet_label_value">[+category+]</span></div>
	<div><span class="FileCabinet_label">Screenshot:</span> <span class="FileCabinet_label_value"><a href="javascript:;" onclick="loadScreenshot();return false;"><img src="[+image+]" border="0" /></a></span></div>
	<div><span class="FileCabinet_label">Attached file:</span> <span class="FileCabinet_label_value"><a href="javascript:;" onclick="downloadFile();return false;"><img src="[+fimage+]" align="absmiddle" border="0"  />[+filename+]</a><br />Views:[+views+] &nbsp;|&nbsp; Downloads:[+downloads+]</span></div>
	<div class="FileCabinet_Note_Title">Notes</div>
	<div class="FileCabinet_Note" style="padding:5px">[+content+]</div>	
</div>';

// set form template
if(isset($formtpl)) $formTpl = $modx->getChunk($formtpl);
if(empty($formTpl)) $formTpl = '<form name="FileCabinet" method="post" enctype="multipart/form-data">
	<script> function cancelEdit(){ location.href="[+cancelink+]";}</script>
	<style>.FileCabinet_Comments {color:#808080}</style>
	<input name="FileCabinetForm" type="hidden" value="on" />
	<input name="filecabdocid" type="hidden" value="[+filecabdocid+]" />
	Category:<br /><select name="category"[+category_disabled+]>[+categories+]</select><br />
	<span class="FileCabinet_Comments">Choose a category for your file.</span><br /><br />
	<div style="float:left">Version:<br /><input name="version" type="text" size="10" value="[+version+]" />&nbsp;</div>
	<div style="float:left">License:<br /><select name="license">
		<option value="0"[+LIC0+]>&nbsp;</option>
		<option value="1"[+LIC1+]>GPL - GNU Public</option>
		<option value="2"[+LIC2+]>LGPL</option>
		<option value="3"[+LIC3+]>Creative Commons</option>
		<option value="4"[+LIC4+]>Artistic</option>
		<option value="5"[+LIC5+]>Free</option>
		<option value="6"[+LIC6+]>Demo</option>
		<option value="7"[+LIC7+]>Shareware</option>
		<option value="8"[+LIC8+]>Commercial</option>
	</select></div>
	<div style="float:clear">&nbsp;</div><br/>
	<div style="float:clear">&nbsp;</div>
	<span class="FileCabinet_Comments">Enter the version number and select a license type.</span><br /><br />
	Author:<br /><input name="author" type="text" size="40" value="[+author+]" /><br />
	<span class="FileCabinet_Comments">Enter the name of the author.</span><br /><br />
	Title:<br /><input name="title" type="text" size="40" value="[+title+]" /><br />
	<span class="FileCabinet_Comments">Enter the title for the file.</span><br /><br />
	Website url:<br /><input name="website" type="text" size="40" value="[+website+]" /><br />
	<span class="FileCabinet_Comments">If you have a web site  you can enter it here.</span><br /><br />
	Description:<br /><textarea name="description" cols="50" rows="4">[+description+]</textarea><br />
	<span class="FileCabinet_Comments">Brief description of the file.</span><br /><br />
	Details:<br /><textarea name="details" cols="50" rows="8">[+details+]</textarea><br />
	<span class="FileCabinet_Comments">Detailed description of the file.</span><br /><br />
	Screenshot image:<br /><input name="screenshot" type="file" size="50" /><br />
	<span class="FileCabinet_Comments">Click the browse button to upload a screenshot.</span><br /><br />
	Attached file:<br /><input name="attachedfile" type="file" size="50" /><br />
	<span class="FileCabinet_Comments">Click the browse button to upload a file attachment.</span><br /><br />
	<input name="send" type="submit" value="Save" /> <input name="cancel" type="button" onclick="cancelEdit();" value="Cancel" />
</form>';

// set spliter
global $splitter;
$splitter = '<!--FileCabinet-Spliter-->';

// set license information
global $licenses;
$licenses = array(
	'1' => 'GPL',
	'2' => 'LGPL',
	'3' => 'Creative Common',
	'4' => 'Artistic',
	'5' => 'Free',
	'6' => 'Demo',
	'7' => 'Shareware',
	'8' => 'Commercial',
);

			
// switch block
switch ($isPostBack) {
	case true:	// process post back		
	/*********** Upload Action (Save Form) ************/

		if($_POST['title']=='') $modx->webAlert('Missing title.');
		elseif($_POST['description']=='') $modx->webAlert('Missing description.');
		elseif($_POST['details']=='') $modx->webAlert('Missing details.');
		elseif ($action='upload') {
			// get rid of magic quotes
			@set_magic_quotes_runtime(0);
			// include_once the magic_quotes_gpc workaround
			include_once $modx->config['base_path'].'manager/includes/quotes_stripper.inc.php'; 

			$err = 0; // set error level
			// check if user has rights to upload files
			if(!$allowAnyUpload && !$modx->isMemberOfWebGroup($uplgrp)) {
				return 'You\'re not allowed to upload files';
			}

			// get created date
			$createdon = time();

			// set alias name of document used to store comments
			$alias = 'filecab-'.$createdon;

			$user = $modx->getLoginUserName();
			$userid = $modx->getLoginUserID();
			if(!$user && $allowAnyUpload) $user = isset($_POST['author']) ? $_POST['author']:'anonymous';

			// load settings from existing document
			$hasSrc = 0;
			$hasFile = 0;
			$vars = array();
			$docid = intval($_POST['filecabdocid']);
			if($docid>0){
				$doc = $modx->getDocument($docid);
				// separate content from variables
				list($content,$snip,$vars) = explode($splitter,$doc['content']);
				// separate url,version,license,has screenshot 
				list($url,$ver,$lic,$hasSrc,$hasFile) = explode('|',$doc['description']);
				$vars = substr($vars,6,-5); // remove <!--[++]--> tags				
				$vars = unserialize($vars);
			}

			// setup variables for storage
			$vars['_epg']=$modx->documentIdentifier; // edit page
			$vars['_usr']=$user;
			$vars['_fpth']=$filepath;
			$vars['_lic']='|+l.'.$_POST['license']; // license search code (for internal use) . example to search for all the GPL files use |+l.1 in any search snippet
			$vars['_uc']='|+u.'.$_POST['license']; // user search code (for internal use). example, to search for all files uploaded by mary use |+u.mary in any search snippet

			$pagetitle = mysql_escape_string($modx->stripTags($_POST['title']));
			$parent = intval($_POST['category']);
			$introtext = htmlspecialchars($_POST['description']);			
			$introtext = str_replace("\r",'',$introtext);
			$introtext = str_replace("\n",'<br />',$introtext);
			$introtext = mysql_escape_string($introtext);
						
			$hasSrc = is_uploaded_file($_FILES['screenshot']['tmp_name']) ? 1:$hasSrc;
			$hasFile = is_uploaded_file($_FILES['attachedfile']['tmp_name']) ? 1:$hasFile;
			
			// url, version, license,screenshot and file flags stored inside the description field
			$tmp = $modx->stripTags($_POST['website']).'|'.
				 substr(str_replace('|','',$_POST['version']),0,10).'|'.
				 $_POST['license'].'|'.
				 $hasSrc.'|'.$hasFile;				 
			$description = mysql_escape_string($modx->stripTags($tmp));

			$content = htmlspecialchars($_POST['details']);			
			// preserve code tags
			preg_match_all("|\[code\](.*)\[/code\]|Uis",$content,$matches);
			for ($i=0;$i<count($matches[0]);$i++) {
				$tag = $matches[0][$i];
				$text = $matches[1][$i];
				$content = str_replace($tag,'<pre'.$codecss.'>'.$text.'</pre>',$content);
			}
			$content = str_replace('[','&#91;',$content); // prevent snippets from being inserted
			$content = str_replace("\r",'',$content);
			$content = str_replace("\n",'<br />',$content);
			$content = '<!--clip/-->'.$content.'<!--/clip-->';
			
			// merge content with display template
			$content = str_replace("[+content+]",$content,$displaytpl);

			// process screenshot
			if($hasSrc==1 && $_FILES['screenshot']['tmp_name']) {
				$pinfo = pathinfo(strtolower($_FILES['screenshot']['name']));
				$imgtype = $pinfo['extension'];
				if(!in_array($imgtype,$pictypes)) {
					$modx->webAlert('This type of screenshot image is not allowed. Allowed image types are \''.implode(',',$pictypes).'\'');
					$err = 1;
				}
				else {
					$tmp = $_FILES['screenshot']['tmp_name'];
					$jpgThumb = FileCabinet_makeJPEG($tmp,$imgtype,60,60);
					if($jpgThumb!=false) $jpgLarge = FileCabinet_makeJPEG($tmp,$imgtype,400,400);
					else {
						$modx->webAlert('The screenshot image failed to upload.');
						$err = 1;
					}
				}
			}
			
			
			// process file
			if($hasFile==1 && $_FILES['attachedfile']['size']) {
				$pinfo = pathinfo(strtolower($_FILES['attachedfile']['name']));
				$filetype = $pinfo['extension'];
				if(!in_array($filetype,$filetypes)) {
					$modx->webAlert('This type of file is not allowed. Allowed file types are \''.implode(',',$filetypes).'\'');
					$err = 1;
				}
				else {
					$tmpFileName = $_FILES['attachedfile']['tmp_name'];
					$fileName = trim($_FILES['attachedfile']['name']);
					if($_FILES['attachedfile']['size']==0) {
						$modx->webAlert('An error occured while trying to upload your file attachment.');
						$err = 1;
					}
				}				
			}

			// save settings
			if($err==0){
				// remove existing file
				if ($hasFile && $fileName && isset($vars['_fext'])) {
					$oldfile = $modx->config['base_path'].$filepath.'/filecab-'.$docid.'.'.$vars['_fext'];
					if (file_exists($oldfile)) {
						unlink($oldfile);
					}
				}
				
				// set new upload file settings
				if($hasFile && $fileName) {
					$vars['_fname']=$fileName;
					$vars['_fext']=$filetype;
				}
				// save file content
				$content = mysql_escape_string($header.$content.$footer.$splitter.'[['.$modx->getSnippetName().'? &action=`view`]]'.$splitter.'<!--[+'.serialize($vars).'+]-->');
				if($docid>0){
					// update
					$flds = array(
						'pagetitle'	=> $pagetitle,
						'description'=> $description,
						'introtext'	=> $introtext,
						'alias'		=> $alias,
						'editedon' 	=> time(),
						'editedby' 	=> ($userid>0 ? $userid * -1:0),
						'content' 	=> $content
					);
					$modx->db->update($flds,$modx->getFullTableName('site_content'),'id=\''.$docid.'\'');
				}
				else {
					// insert
					$flds = array(
						'pagetitle'	=> $pagetitle,
						'description'=> $description,
						'introtext'	=> $introtext,
						'alias'		=> $alias,
						'parent'	=> $parent, 
						'createdon' => $createdon,
						'createdby' => ($userid>0 ? $userid * -1:0),
						'editedon' 	=> '0',
						'editedby' 	=> '0',
						'published' => '1',
						'cacheable' => '0',
						'deleted' 	=> '0',
						'richtext'	=> '0',
						'hidemenu' 	=> $hidemenu,
						'template' 	=> $template,
						'content' 	=> $content
					);
					$docid = $modx->db->insert($flds,$modx->getFullTableName('site_content'));
				}
				
				// convert parent into folder
				if(!empty($makefolder)) {
					$modx->db->update(array('isfolder'=>'1'),$modx->getFullTableName('site_content'),'id=\''.$folder.'\'');
				}

				// save screenshot
				if($jpgThumb) {
					$thumb = $modx->config['base_path'].$filepath.'/filecab-'.$docid.'-thumb.jpg';
					$large = $modx->config['base_path'].$filepath.'/filecab-'.$docid.'-large.jpg';
					$hnd = fopen($thumb,'w');
						fwrite($hnd,$jpgThumb);
					fclose($hnd);
					$hnd = fopen($large,'w');
						fwrite($hnd,$jpgLarge);
					fclose($hnd);
				}
				
				// save file
				if($fileName && $tmpFileName){
					$fileName = $modx->config['base_path'].$filepath.'/filecab-'.$docid.'.'.$filetype;
					move_uploaded_file($tmpFileName,$fileName);					
				}
				
				// redirect to postid
				$postid = $postid? $postid : $docid;
				$modx->sendRedirect($modx->makeUrl($postid));
			}
		}
	
	default:	// display news form
		if($action=='upload') {
		/*********** Upload Action (Display Form) ************/
			
			// check for file types
			if(!$filetypes) {
				return "No file types specified.";
			}

			// check for picture types
			if(!$pictypes) {
				return "No picture types specified.";
			}

			// check for file path
			if(!$filepath) {
				return "No file path specified.";
			}

			// check if user has rights to upload files
			if(!$allowAnyUpload && !$modx->isMemberOfWebGroup($uplgrp)) {
				$formTpl = '';
			}
			else {
				$arr = $_POST;

				$arr['author'] = isset($_POST['author']) ? $_POST['author']:'anonymous';

				// set license selection
				if($arr['license']) $arr['LIC'.$arr['license']] = ' selected="selected"';

				// check if we are suppose to edit a document
				$docid = isset($_REQUEST['filecabdocid'])? $_REQUEST['filecabdocid']:0;
				if($docid>0){
					$doc = $modx->getDocument($docid);
					$uid = ($modx->getLoginUserID()*-1); // web user ids are stored as negative numbers
					if($uid<0 && $uid==$doc['createdby']){
						// separate content from variables
						list($content,$snip,$vars) = explode($splitter,$doc['content']);
						preg_match("|\<\!\-\-clip\/\-\-\>(.*)\<\!\-\-\/clip\-\-\>|Uis",$content,$match);
						$content = $match[1];

						// separate url,version,license,has screenshot 
						list($url,$ver,$lic,$hasScr) = explode('|',$doc['description']);
						
						// convert from html to text
						$introtext = str_replace('<br />',"\n",$doc['introtext']);
						$introtext = FileCabinet_htmldecode($introtext);

						// convert from html to text
						$content = str_replace('<br />',"\n",$content);
						$content = preg_replace('|<pre[^>]*>|i','[code]',$content);
						$content = preg_replace('|</pre[^>]*>|i','[/code]',$content);
						$content = FileCabinet_htmldecode(str_replace('<br />',"\n",$content));

						$arr['filecabdocid'] = $docid;
						$arr['title'] = $doc['pagetitle'];
						$arr['hassrc'] = $hasSrc;
						$arr['hasfile'] = $hasFile;
						$arr['website'] = $url;
						$arr['version'] = $ver;
						$arr['cancelink'] = '[~'.($docid ? $docid:$postid).'~]';;
						$arr['description'] = $introtext;
						$arr['details'] = $content;
						$arr['category'] = $doc['parent'];
						$arr['category_disabled'] = ' disabled="disabled"';
						if($lic) $arr['LIC'.$lic] = ' selected="selected"';
						if($vars) {
							$vars = substr($vars,6,-5); // remove <!--[++]--> tags
							$vars = unserialize($vars);
							$arr['author'] = $vars['_usr'];
						}
					}
				}
				//load categories
				$cats = '';
				$ds = $modx->db->select('id,pagetitle',$modx->getFullTableName('site_content'),'id IN ('.$filefolder.')', 'pagetitle');
				while($rec=$modx->db->getRow($ds)){
					$selected = ($rec['id']==$arr['category']) ? ' selected="selected"':'';
					$cats .= '<option value="'.$rec['id'].'"'.$selected.'>'.$rec['pagetitle'].'</option>';
				}
				$arr['categories'] = $cats;
				
				foreach($arr as $n=>$v) {
					$formTpl = str_replace('[+'.$n.'+]',$v,$formTpl);
				}	
			}
			// return form
			return $formTpl;
		}
		elseif($action=='view') {				
		/************ View Action *************/

			// get document
			$id = $modx->documentIdentifier;
			$doc = $modx->getDocument($id);
			// separate content from variables
			list($content,$snip,$vars) = explode($splitter,$doc['content']);
			$vars = substr($vars,6,-5); // remove <!--[++]--> tags

			// separate url,version,license,hasScreenshot,hasFile
			list($url,$ver,$lic,$hasScr,$hasFile) = explode('|',$doc['description']);

			//load categories
			$cat = $modx->db->getValue('SELECT pagetitle FROM '.$modx->getFullTableName('site_content').' WHERE id=\''.$doc['parent'].'\'');

			if($vars) {
				$vars = unserialize($vars);
				// check if we should send file download info
				if($hasFile==1 && $_REQUEST['filecab-dwnld']=='on') {
					header('Content-type: application/download');
					header('Content-Disposition: attachment; filename='.$vars['_fname']);
					$filename = $modx->config['base_path'].$vars['_fpth'].'/filecab-'.$id.'.'.$vars['_fext'];
					$fp = fopen($filename, "rb");
					$buffer = fread($fp, filesize($filename));
					fclose($fp);					
					// update download count 
					$modx->db->query('UPDATE '.$modx->getFullTableName('site_content').' SET deletedby = deletedby+1 WHERE id=\''.$id.'\'');
					echo $buffer;
					exit;
				}			

				// update view count 
				$modx->db->query('UPDATE '.$modx->getFullTableName('site_content').' SET deletedon = deletedon+1 WHERE id=\''.$id.'\'');

				// check for edit access
				$canedit=false;
				$uid = ($modx->getLoginUserID()*-1); // web user ids are stored as negative numbers
				if($uid<0 && $doc['createdby']==$uid) $canedit=true;
				
				$arr['filecabdocid'] = $docid;
				$arr['title'] = $doc['pagetitle'];
				$arr['createdon'] = strftime('%d-%b-%Y %H:%M',$doc['createdon']);
				$arr['website'] = !$url ? '': (strpos($url,'://')!==false ?  $url:'http://'.trim($url));
				$arr['version'] = $ver;
				$arr['license'] = $licenses[$lic];
				$arr['description'] = $doc['introtext'];
				$arr['category'] = $cat;
				$arr['category'] = $cat;
				$arr['downloads'] = $doc['deletedby'] ? $doc['deletedby'] : "0 ";
				$arr['views'] = $doc['deletedon'] ? $doc['deletedon']+1 : "1 ";
				$arr['viewurl'] = $modx->makeUrl($doc['id'],'','&filecabdocid='.$doc['id']);
				$arr['author'] = $vars['_usr'];
				$arr['image'] = $hasScr==1 ? $modx->config['base_url'].$vars['_fpth'].'/filecab-'.$doc['id'].'-thumb.jpg':$modx->config['base_url'].'/manager/media/images/_tx_.gif';
				$arr['limage'] = $hasScr==1 ? $modx->config['base_url'].$vars['_fpth'].'/filecab-'.$doc['id'].'-large.jpg':$modx->config['base_url'].'/manager/media/images/_tx_.gif';
				$arr['downurl'] = $hasFile==1 ? $modx->makeUrl($doc['id'],'','&filecab-dwnld=on'):'';
				$arr['fimage'] = $hasFile==1 ? $modx->config['base_url'].'/manager/media/images/misc/ed_save.gif':$modx->config['base_url'].'/manager/media/images/_tx_.gif';
				$arr['filename'] = $vars['_fname'];
				$arr['editlink'] = $canedit ? ' | <a href="'.$modx->makeUrl($vars['_epg'],'','&filecabdocid='.$doc['id']).'" title="Click here to edit">Edit Download</a>':'';
				// set values in template
				$tmp = $lstitmtpl;
				foreach($arr as $n=>$v) {
					$modx->setPlaceholder($n,($v ? $v:($n=='filename'||$n=='website' ? '':'&nbsp;')));
				}					
			}			
		}
		else{				
		/************* List Action ************/
		
			//load categories
			$cats = array();
			$ds = $modx->db->select('id,pagetitle',$modx->getFullTableName('site_content'),'id IN ('.$filefolder.')', 'pagetitle');
			while($rec=$modx->db->getRow($ds)){
				$cats[$rec['id']]=$rec['pagetitle'];
			}

			// get documents
			$ds = $modx->db->select('id',$modx->getFullTableName('site_content'),'parent IN ('.$filefolder.')');
			$ids = $modx->db->getColumn('id',$ds);
			$docs = $modx->getDocuments($ids,1,0,'*','','createdon','DESC');
			
			include_once $modx->config['base_path'].'/manager/includes/controls/datasetpager.class.php';
			$dp = new DataSetPager('',$docs);
			$dp->setRenderRowFnc('FileCabinet_RenderRow');
			$dp->render();
			$rows = $dp->getRenderedRows();
			$pages = $dp->getRenderedPager();
			return (!$pages ? '': '<p align="right">Page: '.$pages.'</span><hr size="1" />').$rows;
		}
	break;
}

return '';

// Render Row
function FileCabinet_RenderRow($i,$doc){
	global $modx;
	global $splitter,$licenses,$lstitmtpl;
	
	// separate content from variables
	list($content,$snip,$vars) = explode($splitter,$doc['content']);
	$vars = substr($vars,6,-5); // remove <!--[++]--> tags

	// separate url,version,license,hasScreenshot,hasFile
	list($url,$ver,$lic,$hasScr,$hasFile) = explode('|',$doc['description']);

	if($vars) $vars = unserialize($vars);	
	$arr['author'] = $vars['_usr'];
	$arr['title'] = $doc['pagetitle'];
	$arr['createdon'] = strftime('%d-%b-%Y %H:%M',$doc['createdon']);
	$arr['website'] = $url;
	$arr['version'] = $ver;
	$arr['license'] = $licenses[$lic];
	$arr['description'] = $doc['introtext'];
	$arr['category'] = $cats[$doc['parent']];
	$arr['viewurl'] = $modx->makeUrl($doc['id']);
	$arr['image'] = $hasScr==1 ? $modx->config['base_url'].$vars['_fpth'].'/filecab-'.$doc['id'].'-thumb.jpg':$modx->config['base_url'].'/manager/media/images/_tx_.gif';
	// set values in template
	$tmp = $lstitmtpl;
	foreach($arr as $n=>$v) {
		$tmp = str_replace('[+'.$n.'+]',$v,$tmp);
	}	
	return $tmp;
}

// Make JPEG
function FileCabinet_makeJPEG($file,$type,$w,$h) {

	// get image dimensions		
	list($width, $height) = @getimagesize($file);
	if(!$width) return false;

	// set aspect ratio
	if ($width != $height) {
		if ($width>$height) $h = $w * ($height/$width);
		else $w = $h * ($width/$height);
	}

	// check if image functions exists
	if(!function_exists('imagecreatetruecolor')) return false;
	
	// create image canvas
	$canvas = @imagecreatetruecolor($w, $h);
	if(!$canvas) return false;

	if($type=='gif') $pic = @imagecreatefromgif($file);
	elseif($type=='png') $pic = @imagecreatefrompng($file);
	elseif($type=='jpg') $pic = @imagecreatefromjpeg($file);
	if (!$pic) return false;

	@imagecopyresampled($canvas,$pic,0,0,0,0,$w,$h,$width,$height);

	ob_start();
		@imageJPEG($canvas);
		$jpg = ob_get_contents();
	ob_end_clean();

	return $jpg;
}

function FileCabinet_htmldecode($encoded){
	return strtr($encoded,array_flip(get_html_translation_table(HTML_ENTITIES)));
}
