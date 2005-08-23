/**
 *
 *	UserComments for MODx
 *	Created by Raymond Irving, July 2005
 *
 *	Add user comments to documents
 *
 *	Parameters:
 *		&canpost		- comma delimitted web groups that can post comments. leave blank for public posting
 *		&canview		- comma delimitted web groups that can view comments. leave blank for public viewing
 *		&badwords		- comma delimited list of words not allowed in post
 *		&makefolder		- set to 1 to automatically convert the parent document to a folder. Defaults to 0
 *		&folder			- folder id where comments are stored
 *		&displaytpl		- display template (chunk name)
 *		&formtpl		- form template (chunk name)
 *		&tagid			- a unique id used to identify or tag user comments on a page where multiple comments are required. 
 *		&freeform		- set this option to 1 to use the [+UserComments.Form+] placholder to relocate the comment form. 
 *		&codecss		- sets the css class used to format code tags
 *		&authorcss		- sets the css class used identify author's comments
 *		&ownercss		- sets the css class used identify the owner's comments
 *		&altrowcss		- sets the css class used identify author's comments
 *		&dateformat		- sets php date format for new comments
 *		&sortorder		- sort the comments in either ascending order (when set to 0) or descending order (when set to 1). Defaults to descending (1)
 *		&recentposts	- set the number of recent posts to be displayed. set to 0 to show all post. Defaults to 0
 
 *
 *	Version 1.0 Beta
 *
 */
 
// redirect to host document if an attempt was 
// made to display the document containing the comments
if(isset($hostid)) {
	$url = $modx->makeUrl($hostid);
	$modx->sendRedirect($url);
	exit;
}

// get user groups that can post comments
$postgrp = isset($canpost) ? explode(",",$canpost):array();
$viewgrp = isset($canview) ? explode(",",$canview):array();
$allowAnyPost = count($postgrp)==0 ? true : false;
$allowAnyView = count($viewgrp)==0 ? true : false;

// get folder id where we should store comments 
// else store in current document
$folder = isset($folder) ? intval($folder):$modx->documentIdentifier;

// get current document id
$docid = $modx->documentIdentifier;

// get free form option
$freeform = isset($freeform) && $freeform==1 ? 1:0;

// get tagid
$tagid = isset($tagid) ? preg_replace("/[^A-z0-9_\-]/",'',$tagid):'';

// set alias name of document used to store comments
$alias = 'usrcmt-'.$docid.($tagid ? '-'.$tagid:'');

// get sort order
$sortorder = isset($sortorder) ? $sortorder : 1;

// get code style/class
$codecss = isset($codecss) ? ' class="'.$codecss.'"' : ' style="background-color:#eeeeee;border-top:2px solid #e0e0e0;margin:0px;"';

// get author class
$authorcss = isset($authorcss) ? $authorcss : '';

// get owner's class
$ownercss = isset($ownercss) ? $ownercss : '';

// get alt row style/class
$altrowcss = isset($altrowcss) ? $altrowcss : '';

// get date format
$dateformat = isset($dateformat) ? $dateformat : '%d-%b-%Y %H:%M';

// set recent post value
$recentposts = isset($recentposts) ? $recentposts : 0;


// get badwords
if(isset($badwords)) {
	$badwords = str_replace(' ','', $badwords);
	$badwords = "/".str_replace(',','|', $badwords)."/i";
}

// set splitter
$splitter = '<!--Comment-Spliter:'.$docid.'-->';

// get postback status
$isPostBack = isset($_POST['UserCommentForm'.$tagid]) ? true:false;

// get display template
if(isset($displaytpl)) $displayTpl = $modx->getChunk($displaytpl);
if(empty($displaytpl)) $displayTpl = '[+UID:[+uid+]+]<div style="font-size:11px;line-height: 17px;white-space:normal;width:100%;background-color:#eee;color: #111;padding:5px;margin-bottom:10px;" class="[+authorclass+] [+altrowclass+]">
	<div style="width:100%;background-color:#c0c0c0;padding:2px;margin-bottom:5px">
     <strong>[+subject+]</strong> by [+user+] @ [+createdon+]
   </div>
   <div style="float:right; padding: 0 0 20px 20px;font-size:24px;color:#ccc;font-weight:bold">
     [+postnumber+]
   </div>
	[+comment+]
</div>';

// get form template
if(isset($formtpl)) $formTpl = $modx->getChunk($formtpl);
if(empty($formTpl)) $formTpl = '<form method="post">
	<input name="[+tagname+]" type="hidden" value="on" />
	Subject:<br /><input name="subject" type="text" size="40" value="" /><br />
	Comment:<br /><textarea name="comment" cols="50" rows="8"></textarea><br />
	<input name="send" type="submit" value="Submit" />
</form>';


// switch block
switch ($isPostBack) {
	case true:	// process post backs
		if($_POST['comment']!='') {
			
			// get user's id and name
			$uid = $modx->getLoginUserID();
			$user = $modx->getLoginUserName();
			if(!$user && $allowAnyPost) $user = 'anonymous';

			// check if user has rights
			if(!$allowAnyPost && !$modx->isMemberOfWebGroup($postgrp)) {
				return 'You are not allowed to post comments';
			}

			$createdon = time();

			// format comment
			$comment = str_replace('[+user+]',$user,$displayTpl);
			$comment = str_replace('[+uid+]',$uid,$comment);
			$comment = str_replace('[+createdon+]',strftime($dateformat,$createdon),$comment);
			// check for author's comments
			if($uid && ($uid*-1)==$modx->documentObject['createdby']) {
				$comment = str_replace('[+authorclass+]',$authorcss,$comment);
			}
			foreach($_POST as $n=>$v) {
				if(!empty($badwords)) $v = preg_replace($badwords,'[Filtered]',$v); // remove badwords
				$v = $modx->stripTags(htmlspecialchars($v));
				if($n=='comment' && strpos($v,'[code]')!==false){
					// preserve code
					preg_match_all("|\[code\](.*)\[/code\]|Uis",$v,$matches);
					for ($i=0;$i<count($matches[0]);$i++) {
						$tag = $matches[0][$i];
						$text = $matches[1][$i];
						$v = str_replace($tag,'<pre'.$codecss.'>'.$text.'</pre>',$v);
					}
				}
				$v = str_replace("\r",'',$v);
				$v = str_replace("\n",'<br />',$v);
				$comment = str_replace('[+'.$n.'+]',$v,$comment);
			}
			$comment = str_replace($splitter,'',$comment); // remove splitter from comment

			// save comment
			$sql = 'SELECT pagetitle FROM '.$modx->getFullTableName('site_content').' WHERE parent=\''.$folder.'\' AND alias=\''.$alias.'\' LIMIT 1';
			if($modx->db->getValue($sql)) {
				// update comments
				$sql = 	' UPDATE '.$modx->getFullTableName('site_content').
						' SET content = CONCAT(\''.$comment.$splitter.'\',content)'.
						' WHERE parent=\''.$folder.'\' AND alias=\''.$alias.'\'';
				$modx->db->query($sql);
			}
			else {
				// create new comment page
				$doc = $modx->getDocument($docid);
				$title = mysql_escape_string($doc['pagetitle']).' - User comments';
				$flds = array(
					'pagetitle'	=> $title,
					'alias'		=> $alias,
					'parent'	=> $folder, 
					'createdon' => $createdon,
					'createdby' => '0',
					'editedon' 	=> '0',
					'editedby' 	=> '0',
					'published' => '1',
					'deleted' 	=> '0',
					'hidemenu' 	=> '1',
					'template' 	=> '0',
					'content' 	=> $comment.$splitter.'[['.$modx->getSnippetName().'? &hostid=`'.$docid.'`]]'
				);
				$modx->db->insert($flds,$modx->getFullTableName('site_content'));
				if(!empty($makefolder)) {
					// convert parent into folder
					$modx->db->update(array('isfolder'=>'1'),$modx->getFullTableName('site_content'),'id=\''.$folder.'\'');
				}
			}
		}
	
	default:	// display comments
		// check if user has rights to view comments
		if(!$allowAnyView && !$modx->isMemberOfWebGroup($viewgrp)) {
			$comment = '';
		}
		else {

			$uid = $modx->getLoginUserID();

			// get comments
			$ds = $modx->db->select('content',$modx->getFullTableName('site_content'),' parent=\''.$folder.'\' AND alias=\''.$alias.'\'');
			$content = $modx->db->getValue($ds);

			// split content into separate comments
			$parts = explode($splitter,$content);
			array_pop($parts);
			
			// count comments
			$count = count($parts);			
			$modx->setPlaceholder("UserComments.Count",$count);

			// handle recent post
			$offset = 0;
			if($recentposts>0) {
				$parts = array_slice($parts,0,$recentposts);
				$offset = $count-$recentposts;
				$count = $recentposts;
			}
			
			// prepare comments
			if($sortorder==0) $parts = array_reverse($parts);
			for($i=0;$i<$count;$i++) {
				$part = $parts[$i];
				$num = ($sortorder==1) ? $count-$i:$i+1;
				$part = str_replace('[+postnumber+]',$num+$offset,$part);
				if($altrowcss && ($i % 2)==0) $part = str_replace('[+altrowclass+]',$altrowcss,$part);
				// check for owner's comments
				if($uid && strpos($part,'[+UID:'.$uid.'+]')!==false) $part = str_replace('[+authorclass+]',$ownercss,$part);
				$parts[$i] = $part;
			}
			
			$comments = implode("",$parts);
			
		}
		// check if user has rights to post comments
		if(!$allowAnyPost && !$modx->isMemberOfWebGroup($postgrp)) {
			$formTpl = '';
		}
		else{
			$formTpl = str_replace('[+tagname+]','UserCommentForm'.$tagid,$formTpl);
		}

		// return comments along with form
		return $comments.($freeform ? $modx->setPlaceholder('UserComments.Form',$formTpl):$formTpl);
		break;
}
