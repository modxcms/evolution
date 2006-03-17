#::::::::::::::::::::::::::::::::::::::::
#
#  Snippet Name: NewsPublisher 
#  Short Desc: Create articles directly from front end (news, blogs, PR, etc.)
#  Created By: Raymond Irving (xwisdom@yahoo.com), August 2005
#
#  Version: 1.4
#  Modified: December 13, 2005
#
#  Changelog: 
#	 Mar 05, 06 -- modx_ prefix removed [Mark]
#    Dec 13, 05 -- Now inherrits web/manager docgroups thanks to Jared Carlow
#
#::::::::::::::::::::::::::::::::::::::::
#  Description: 	
#    Checks to see if users belong to a certain group and 
#    displays the specified chunk if they do. Performs several
#    sanity checks and allows to be used multiple times on a page.
#    Only meant to be used once per page.
#::::::::::::::::::::::::::::::::::::::::
#  
#  Parameters:
#    &folder      - folder id where comments are stored
#    &makefolder  - set to 1 to automatically convert the parent document to a folder. Defaults to 0
#    &postid      - document id to load after posting news item. Defaults to the page created
#    &canpost     - comma delimitted web groups that can post comments. leave blank for public posting
#    &badwords    - comma delimited list of words not allowed in post
#    &template    - name of template to use for news post
#    &headertpl   - header template (chunk name) to be inserted at the begining of the news content
#    &footertpl   - footer template (chunk name) to be inserted at the end of the news content
#    &formtpl     - form template (chunk name)
#    &rtcontent   - name of a richtext content form field 
#    &rtsummary   - name of a richtext summary form field 
#    &showinmenu  - sets the flag to true or false (1|0) as to whether or not it shows in the menu. defaults to false (0)
#    &aliastitle  - set to 1 to use page title as alias suffix. Defaults to 0 - date created.
#    &clearcache  - when set to 1 the system will automatically clear the site cache after publishing an article.
#  
#::::::::::::::::::::::::::::::::::::::::

// get user groups that can post articles
$postgrp = isset($canpost) ? explode(",",$canpost):array();
$allowAnyPost = count($postgrp)==0 ? true : false;

// get clear cache
$clearcache	 = isset($clearcache) ? 1:0;

// get alias title
$aliastitle	 = isset($aliastitle) ? 1:0;

// get folder id where we should store articles
// else store in current document
$folder = isset($folder) ? intval($folder):$modx->documentIdentifier;

// set rich text content field
$rtcontent = isset($rtcontent) ? $rtcontent:'content';

// set rich text summary field
$rtsummary = isset($rtsummary) ? $rtsummary:'introtext';

// get header
$header = isset($headertpl) ? "{{".$headertpl."}}":'';

// get footer
$footer = isset($footertpl) ? "{{".$footertpl."}}":'';

// get postback status
$isPostBack = isset($_POST['NewsPublisherForm']) ? true:false;

// get badwords
if(isset($badwords)) {
    $badwords = str_replace(' ','', $badwords);
    $badwords = "/".str_replace(',','|', $badwords)."/i";
}

// get menu status
$hidemenu = isset($showinmenu) && $showinmenu==1 ? 0 : 1;

// get template
$template = isset($template) ? $modx->db->getValue('SELECT id FROM '.$modx->getFullTableName('site_templates').' WHERE templatename=\''.mysql_escape_string($template).'\''):$modx->config['default_template'];

$message = '';

// get form template
if(isset($formtpl)) $formTpl = $modx->getChunk($formtpl);
if(empty($formTpl)) $formTpl = '
    <form name="NewsPublisher" method="post">
        <input name="NewsPublisherForm" type="hidden" value="on" />
        Page title:<br /><input name="pagetitle" type="text" size="40" value="[+pagetitle+]" /><br />
        Long title:<br /><input name="longtitle" type="text" size="40" value="[+longtitle+]" /><br />
        Description:<br /><input name="description" type="text" size="40" value="[+description+]" /><br />
        Published date:<br /><input name="pub_date" type="text" value="[+pub_date+]" size="40" readonly="readonly" />
        <a onClick="nwpub_cal1.popup();" onMouseover="window.status=\'Select date\'; return true;" onMouseout="window.status=\'\'; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="manager/media/images/icons/cal.gif" width="16" height="16" border="0" alt="Select date" /></a>
        <a onClick="document.NewsPublisher.pub_date.value=\'\'; return true;" onMouseover="window.status=\'Remove date\'; return true;" onMouseout="window.status=\'\'; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="manager/media/images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="Remove date"></a><br />
        Unpublished date:<br /><input name="unpub_date" type="text" value="[+unpub_date+]" size="40" readonly="readonly" />
        <a onClick="nwpub_cal2.popup();" onMouseover="window.status=\'Select date\'; return true;" onMouseout="window.status=\'\'; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="manager/media/images/icons/cal.gif" width="16" height="16" border="0" alt="Select date" /></a>
        <a onClick="document.NewsPublisher.unpub_date.value=\'\'; return true;" onMouseover="window.status=\'Remove date\'; return true;" onMouseout="window.status=\'\'; return true;" style="cursor:pointer; cursor:hand"><img align="absmiddle" src="manager/media/images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="Remove date"></a><br />
        Summary:<br /><textarea name="introtext" cols="50" rows="5">[+introtext+]</textarea><br />
        Content:<br /><textarea name="content" cols="50" rows="8">[+content+]</textarea><br />
        <input name="send" type="submit" value="Submit" />
    </form>
    <script language="JavaScript" src="manager/media/script/datefunctions.js"></script>
    <script type="text/javascript">
        var elm_txt = {}; // dummy
        var pub = document.forms["NewsPublisher"].elements["pub_date"];
        var nwpub_cal1 = new calendar1(pub,elm_txt);
        nwpub_cal1.path="[(base_url)]manager/media/";
        nwpub_cal1.year_scroll = true;
        nwpub_cal1.time_comp = true;	

        var unpub = document.forms["NewsPublisher"].elements["unpub_date"];
        var nwpub_cal2 = new calendar1(unpub,elm_txt);
        nwpub_cal2.path="[(base_url)]manager/media/";
        nwpub_cal2.year_scroll = true;
        nwpub_cal2.time_comp = true;	
    </script>';


// switch block
switch ($isPostBack) {
    case true:
        // process post back
        // remove magic quotes from POST
        if(get_magic_quotes_gpc()){
            $_POST = array_map("stripslashes", $_POST);
        }	
        if(trim($_POST['pagetitle'])=='') $modx->webAlert('Missing page title.');
        elseif($_POST[$rtcontent]=='') $modx->webAlert('Missing news content.');
        else {
            // get created date
            $createdon = time();

            // set alias name of document used to store articles
            if(!$aliastitle) $alias = 'article-'.$createdon;
            else {
                $alias = $modx->stripTags($_POST['pagetitle']);
                $alias = strtolower($alias);
                $alias = preg_replace('/&.+?;/', '', $alias); // kill entities
                $alias = preg_replace('/[^\.%a-z0-9 _-]/', '', $alias);
                $alias = preg_replace('/\s+/', '-', $alias);
                $alias = preg_replace('|-+|', '-', $alias);
                $alias = trim($alias, '-');			
                $alias = 'article-'.$modx->db->escape($alias);
            }

            $user = $modx->getLoginUserName();
            $userid = $modx->getLoginUserID();
            if(!$user && $allowAnyPost) $user = 'anonymous';

            // check if user has rights
            if(!$allowAnyPost && !$modx->isMemberOfWebGroup($postgrp)) {
                return 'You are not allowed to publish articles';
            }

            $allowedTags = '<p><br><a><i><em><b><strong><pre><table><th><td><tr><img><span><div><h1><h2><h3><h4><h5><font><ul><ol><li><dl><dt><dd>';

            // format content
            $content = $modx->stripTags($_POST[$rtcontent],$allowedTags);
            $content = str_replace('[+user+]',$user,$content);
            $content = str_replace('[+createdon+]',strftime('%d-%b-%Y %H:%M',$createdon),$content);
            foreach($_POST as $n=>$v) {
                if(!empty($badwords)) $v = preg_replace($badwords,'[Filtered]',$v); // remove badwords
                $v = $modx->stripTags(htmlspecialchars($v));
                $v = str_replace("\n",'<br />',$v);
                $content = str_replace('[+'.$n.'+]',$v,$content);
            }

            $title = mysql_escape_string($modx->stripTags($_POST['pagetitle']));
            $longtitle = mysql_escape_string($modx->stripTags($_POST['longtitle']));
            $description = mysql_escape_string($modx->stripTags($_POST['description']));
            $introtext = mysql_escape_string($modx->stripTags($_POST[$rtsummary],$allowedTags));
            $pub_date = $_POST['pub_date'];
            $unpub_date = $_POST['unpub_date'];
            $published = 1;

            // check published date
            if($pub_date=="") {
                $pub_date="0";
            } else {
                list($d, $m, $Y, $H, $M, $S) = sscanf($pub_date, "%2d-%2d-%4d %2d:%2d:%2d");
                $pub_date = strtotime("$m/$d/$Y $H:$M:$S");

                if($pub_date < $createdon) {
                    $published = 1;
                }    elseif($pub_date > $createdon) {
                    $published = 0;	
                }
            }

            // check unpublished date
            if($unpub_date=="") {
                $unpub_date="0";
            } else {
                list($d, $m, $Y, $H, $M, $S) = sscanf($unpub_date, "%2d-%2d-%4d %2d:%2d:%2d");
                $unpub_date = strtotime("$m/$d/$Y $H:$M:$S");
                if($unpub_date < $createdon) {
                    $published = 0;
                }
            }

            // set menu index
            $mnuidx = $modx->db->getValue('SELECT MAX(menuindex)+1 as \'mnuidx\' FROM '.$modx->getFullTableName('site_content').' WHERE parent=\''.$folder.'\'');
            if($mnuidx<1) $mnuidx = 0;

            // post news content
            $flds = array(
                'pagetitle'     => $title,
                'longtitle'     => $longtitle,
                'description' => $description,
                'introtext'     => $introtext,
                'alias'             => $alias,
                'parent'            => $folder, 
                'createdon'     => $createdon,
                'createdby'     => ($userid>0 ? $userid * -1:0),
                'editedon'        => '0',
                'editedby'        => '0',
                'published'     => $published,
                'pub_date'        => $pub_date,
                'unpub_date'    => $unpub_date,
                'deleted'         => '0',
                'hidemenu'        => $hidemenu,
                'menuindex'     => $mnuidx,
                'template'        => $template,
                'content'         => mysql_escape_string($header.$content.$footer)
            );
            $redirectid = $modx->db->insert($flds,$modx->getFullTableName('site_content'));

            // Doc group thing
            // look in save_content.processor.php for tips (or below)
            $lastInsertId = $modx->db->getInsertId();

            // Get doc groups based on $folder (parent id)
            $parentDocGroupsSql = "SELECT * FROM " . $modx->getFullTableName('document_groups'). " where document=".$folder;
            $parentDocGroupsRs = $modx->db->query($parentDocGroupsSql);
            $parentDocGroupsLimit = $modx->db->getRecordCount($parentDocGroupsRs);
            for ($pdgi = 0; $pdgi < $parentDocGroupsLimit; $pdgi++) { 
                $currentDocGroup = $modx->db->getRow($parentDocGroupsRs);
                $parentDocGroupsArray[$pdgi] = $currentDocGroup['document_group'];
            }

            // put the document in the document_groups it should be in
            // check that up_perms are switched on!
            if($modx->config['use_udperms']==1) {
                if(is_array($parentDocGroupsArray)) {
                    foreach ($parentDocGroupsArray as $dgKey=>$dgValue) {
                        $insertDocSql = "INSERT INTO ".$modx->getFullTableName('document_groups')."(document_group, document) values(".stripslashes($dgValue).", $lastInsertId)";
                        $insertDocRs = $modx->db->query($insertDocSql);
                        if(!$insertDocRs){
                            exit;
                        }
                    } // end foreach
                } // end if doc group array exists
            } // end if perms are used

            // Handle privateweb
            $modx->db->query("UPDATE ".$modx->getFullTableName("site_content")." SET privateweb = 0 WHERE id='$lastInsertId';");
            $privatewebSql =    "
                SELECT DISTINCT ".$modx->getFullTableName('document_groups').".document_group 
                FROM ".$modx->getFullTableName('document_groups').", ".$modx->getFullTableName('webgroup_access')." 
                WHERE 
                ".$modx->getFullTableName('document_groups').".document_group = ".$modx->getFullTableName('webgroup_access').".documentgroup 
                AND 
                ".$modx->getFullTableName('document_groups').".document = $lastInsertId;";
                $privatewebIds = $modx->db->getColumn("document_group",$privatewebSql);
                if(count($privatewebIds)>0) {
                    $modx->db->query("UPDATE ".$modx->getFullTableName("site_content")." SET privateweb = 1 WHERE id = $lastInsertId;");	
                }

                // And privatemgr
                $modx->db->query("UPDATE ".$modx->getFullTableName("site_content")." SET privatemgr = 0 WHERE id='$lastInsertId';");
                $privatemgrSql =    "
                    SELECT DISTINCT ".$modx->getFullTableName('document_groups').".document_group 
                    FROM ".$modx->getFullTableName('document_groups').", ".$modx->getFullTableName('membergroup_access')." 
                    WHERE 
                    ".$modx->getFullTableName('document_groups').".document_group = ".$modx->getFullTableName('membergroup_access')." .documentgroup 
                    AND 
                    ".$modx->getFullTableName('document_groups').".document = $lastInsertId;";
                    $privatemgrIds = $modx->db->getColumn("document_group",$privatemgrSql);
                    if(count($privatemgrIds)>0) {
                        $modx->db->query("UPDATE ".$modx->getFullTableName("site_content")." SET privatemgr = 1 WHERE id = $lastInsertId;");	
                    }
            // end of document_groups stuff!

            if(!empty($makefolder)) {
                // convert parent into folder
                $modx->db->update(array('isfolder'=>'1'),$modx->getFullTableName('site_content'),'id=\''.$folder.'\'');
            }

            // empty cache
            if($clearcache==1){
                include_once $modx->config['base_path']."manager/processors/cache_sync.class.processor.php";
                $sync = new synccache();
                $sync->setCachepath("assets/cache/");
                $sync->setReport(false);
                $sync->emptyCache(); // first empty the cache		
            }

            // get redirect/post id
            $redirectid = $modx->db->getValue('SELECT id as \'redirectid\' FROM '.$modx->getFullTableName('site_content').' WHERE createdon=\''.$createdon.'\'');
            $postid = isset($postid) ? $postid:$redirectid;

            // redirect to post id
            $modx->sendRedirect($modx->makeUrl($postid));
        }

    default: 
        // display news form
        // check if user has rights to post comments
        if(!$allowAnyPost && !$modx->isMemberOfWebGroup($postgrp)) {
            $formTpl = '';
        } else {
            foreach($_POST as $n=>$v) {
                $formTpl = str_replace('[+'.$n.'+]',$v,$formTpl);
            }
        }
        // return form
        return $message.$formTpl;
        break;
}