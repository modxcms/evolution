<?php
if (IN_MANAGER_MODE != "true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

// check permissions
switch($_REQUEST['a']) {
  case 27:
    if(!$modx->hasPermission('edit_document')) {
      $e->setError(3);
      $e->dumpError();
    }
    break;
  case 85:
  case 72:
  case 4:
    if(!$modx->hasPermission('new_document')) {
      $e->setError(3);
      $e->dumpError();
    }     
    break;
 default:
    $e->setError(3);
   $e->dumpError();
}


if (!isset ($_REQUEST['id'])) {
    $id = 0;
} else {
    $id = !empty ($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
}


if ($action == 27) {
    //editing an existing document
    // check permissions on the document
    include_once "./processors/user_documents_permissions.class.php";
    $udperms = new udperms();
    $udperms->user = $modx->getLoginUserID();
    $udperms->document = $id;
    $udperms->role = $_SESSION['mgrRole'];

    if (!$udperms->checkPermissions()) {
        ?><br /><br /><div class="sectionHeader"><?php echo $_lang['access_permissions']; ?></div><div class="sectionBody">
        <p><?php echo $_lang['access_permission_denied']; ?></p>
        <?php

        include ("footer.inc.php");
        exit;
    }
} 

// check to see the document isn't locked
$sql =	"SELECT internalKey, username FROM ".$modx->getFullTableName('active_users') .
	"WHERE action='27' AND id='$id'";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if ($limit > 1) {
    for ($i = 0; $i < $limit; $i++) {
        $lock = mysql_fetch_assoc($rs);
        if ($lock['internalKey'] != $modx->getLoginUserID()) {
            $msg = sprintf($_lang["lock_msg"], $lock['username'], "document");
            $e->setError(5, $msg);
            $e->dumpError();
        }
    }
}
// end check for lock

// get document groups for current user
if ($_SESSION['mgrDocgroups']) {
    $docgrp = implode(",", $_SESSION['mgrDocgroups']);
}

if (!empty ($id)) {
    $tblsc = $dbase . ".`" . $table_prefix . "site_content`";
    $tbldg = $dbase . ".`" . $table_prefix . "document_groups`";
    $access = "1='" . $_SESSION['mgrRole'] . "' OR sc.privatemgr=0" .
     (!$docgrp ? "" : " OR dg.document_group IN ($docgrp)");
    $sql = "SELECT DISTINCT sc.*
            FROM $tblsc sc
            LEFT JOIN $tbldg dg on dg.document = sc.id
            WHERE sc.id = $id
            AND ($access);";
    $rs = mysql_query($sql);
    $limit = mysql_num_rows($rs);
    if ($limit > 1) {
            $e->setError(6);
            $e->dumpError();
    }
    if ($limit < 1) {
            $e->setError(3);
            $e->dumpError();
    }
    $content = mysql_fetch_assoc($rs);
} else {
    $content = array ();
}

// restore saved form
$formRestored = false;
if ($modx->manager->hasFormValues()) {
    $modx->manager->loadFormValues();
    $formRestored = true;
}

// retain form values if template was changed
// edited to convert pub_date and unpub_date
// sottwell 02-09-2006
if ($formRestored == true || isset ($_REQUEST['newtemplate'])) {
    $content = array_merge($content, $_POST);
    $content["content"] = $_POST["ta"];
    if (empty ($content["pub_date"])) {
        unset ($content["pub_date"]);
    } else {
        $pub_date = $content['pub_date'];
        list ($d, $m, $Y, $H, $M, $S) = sscanf($pub_date, "%2d-%2d-%4d %2d:%2d:%2d");
        $pub_date = strtotime("$m/$d/$Y $H:$M:$S");
        $content['pub_date'] = $pub_date;
}
    if (empty ($content["unpub_date"])) {
        unset ($content["unpub_date"]);
    } else {
        $unpub_date = $content['unpub_date'];
        list ($d, $m, $Y, $H, $M, $S) = sscanf($unpub_date, "%2d-%2d-%4d %2d:%2d:%2d");
        $unpub_date = strtotime("$m/$d/$Y $H:$M:$S");
        $content['unpub_date'] = $unpub_date;
    }
}

// increase menu index if this is a new document
if (!isset ($_REQUEST["id"])) {
    if (!isset ($auto_menuindex) || $auto_menuindex) {
        $pid = intval($_REQUEST["pid"]);
        $tbl = $modx->getFullTableName("site_content");
        $sql = "SELECT count(*) as 'cnt' FROM $tbl WHERE parent='$pid'";
        $content["menuindex"] = $modx->db->getValue($sql);
    } else {
        $content['menuindex'] = 0;
    }
}

if (isset ($_POST['which_editor'])) {
    $which_editor = $_POST['which_editor'];
}
?>
<script type="text/javascript" src="media/script/datefunctions.js"></script>
<script type="text/javascript">

// save tree folder state
parent.tree.saveFolderState();

function changestate(element) {
    currval = eval(element).value;
    if(currval==1) {
        eval(element).value=0;
    } else {
        eval(element).value=1;
    }
    documentDirty=true;
}

function deletedocument() {
    if(confirm("<?php echo $_lang['confirm_delete_document']; ?>")==true) {
        document.location.href="index.php?id=" + document.mutate.id.value + "&a=6";
    }
}

function previewdocument() {
    var win = window.frames['preview'];
    url = "../index.php?id=" + document.mutate.id.value + "&manprev=z";
    nQ = "id=" + document.mutate.id.value + "&manprev=z"; // new querysting
    oQ = (win.location.href.split("?"))[1]; // old querysting
    if (nQ != oQ) {
        win.location.href = url;
        win.alreadyPreviewed = true;
    }
}

function saveRefreshPreview(){
    var f = document.forms['mutate'];
    documentDirty=false;
    f.target = "preview";
    f.refresh_preview.value=1;
    f.save.click();
    setTimeout("document.forms['mutate'].target='';document.forms['mutate'].refresh_preview.value=0",100);
}
// end modifications

var allowParentSelection = false;
var allowLinkSelection = false;

function enableLinkSelection(b){
  parent.tree.ca = "link";
    var closed = "media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/folder.gif";
    var opened = "media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/folderopen.gif";
    if(b) {
        document.images["llock"].src = opened;
        allowLinkSelection = true;
    }
    else {
        document.images["llock"].src = closed;
        allowLinkSelection = false;
    }
}

function setLink(lId) {
    if (!allowLinkSelection) {
        window.location.href="index.php?a=3&id="+lId;
        return;
    }
    else {  
            documentDirty=true;
            document.mutate.ta.value=lId;
    }
}

function enableParentSelection(b){
  parent.tree.ca = "parent";
    var closed = "media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/folder.gif";
    var opened = "media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/folderopen.gif";
    if(b) {
        document.images["plock"].src = opened;
        allowParentSelection = true;
    }
    else {
        document.images["plock"].src = closed;
        allowParentSelection = false;
    }
}

function setParent(pId, pName) {
    if (!allowParentSelection) {
        window.location.href="index.php?a=3&id="+pId;
        return;
    }
    else {
        if(pId==0 || checkParentChildRelation(pId, pName)){
            documentDirty=true;
            document.mutate.parent.value=pId;
            var elm = document.getElementById('parentName');
            if(elm) {
                elm.innerHTML = (pId + " (" + pName + ")");
            }
        }
    }
}

// check if the selected parent is a child of this document
function checkParentChildRelation(pId, pName) {
    var sp;
    var id = document.mutate.id.value;
    var tdoc = parent.tree.document;
    var pn = (tdoc.getElementById) ? tdoc.getElementById("node"+pId) : tdoc.all["node"+pId];
    if(!pn) return;
    if (pn.id.substr(4)==id) {
        alert("<?php echo $_lang['illegal_parent_self']; ?>");
        return;
    }
    else {
        while (pn.getAttribute("p")>0) {
            pId = pn.getAttribute("p");
            pn = (tdoc.getElementById) ? tdoc.getElementById("node"+pId) : tdoc.all["node"+pId];
            if (pn.id.substr(4)==id) {
                alert("<?php echo $_lang['illegal_parent_child']; ?>");
                return;
            }
        }
    }
    return true;
}

function clearKeywordSelection() {
    var opt = document.mutate.elements["keywords[]"].options;
    for(i = 0; i < opt.length; i++) {
        opt[i].selected = false;
    }
}

function clearMetatagSelection() {
    var opt = document.mutate.elements["metatags[]"].options;
    for(i = 0; i < opt.length; i++) {
        opt[i].selected = false;
    }
}

// ADDED BY S BRENNAN
var curTemplate = -1;
var curTemplateIndex = 0;
function storeCurTemplate(){
    var dropTemplate = document.getElementById('template');
    if (dropTemplate){ 
        for (var i=0; i<dropTemplate.length; i++){
            if (dropTemplate[i].selected){
                curTemplate = dropTemplate[i].value;
                curTemplateIndex = i;
            }
        }
    }
}
function templateWarning(){
    var dropTemplate = document.getElementById('template');
    if (dropTemplate){ 
        for (var i=0; i<dropTemplate.length; i++){
            if (dropTemplate[i].selected){
                newTemplate = dropTemplate[i].value;
                break;
            }
        }
    }
    if (curTemplate == newTemplate){return;}

    if (confirm('<?php echo $_lang['tmplvar_change_template_msg']?>')){
        documentDirty=false;
        document.mutate.a.value = <?php echo $action; ?>;
        document.mutate.newtemplate.value = newTemplate;
        document.mutate.submit();
    }
    else{
        dropTemplate[curTemplateIndex].selected = true;
    }
}
// END ADDED BY S BRENNAN

// Added for RTE selection
function changeRTE(){
    var whichEditor = document.getElementById('which_editor');
    if (whichEditor){
        for (var i=0; i<whichEditor.length; i++){
            if (whichEditor[i].selected){
                newEditor = whichEditor[i].value;
                break;
            }
        }
    }
    var dropTemplate = document.getElementById('template');
    if (dropTemplate){ 
        for (var i=0; i<dropTemplate.length; i++){
            if (dropTemplate[i].selected){
                newTemplate = dropTemplate[i].value;
                break;
            }
         }          
    }

    documentDirty=false;
    document.mutate.a.value = <?php echo $action; ?>;
    document.mutate.newtemplate.value = newTemplate;
    document.mutate.which_editor.value = newEditor;
    document.mutate.submit();
}

/** 
 * Snippet properties 
 */

var snippetParams = {};     // Snippet Params
var currentParams = {};     // Current Params
var lastsp, lastmod = {};

function showParameters(ctrl) {
    var c,p,df,cp;
    var ar,desc,value,key,dt;

    cp = {};
    currentParams = {}; // reset;

    if (ctrl) {
        f = ctrl.form;
    } else {
        f= document.forms['mutate'];
        ctrl = f.snippetlist;
    }

    // get display format
    df = "";//lastsp = ctrl.options[ctrl.selectedIndex].value;

    // load last modified param values
    if (lastmod[df]) cp = lastmod[df].split("&");
    for(p = 0; p < cp.length; p++) {
        cp[p]=(cp[p]+'').replace(/^\s|\s$/,""); // trim
        ar = cp[p].split("=");
        currentParams[ar[0]]=ar[1];
    }

    // setup parameters
    dp = (snippetParams[df]) ? snippetParams[df].split("&"):[""];
    if(dp) {
        t='<table width="100%" style="margin-bottom:3px;margin-left:14px;background-color:#EEEEEE" cellpadding="2" cellspacing="1"><thead><tr><td width="50%"><?php echo $_lang['parameter']; ?><\/td><td width="50%"><?php echo $_lang['value']; ?><\/td><\/tr><\/thead>';
        for(p = 0; p < dp.length; p++) {
            dp[p]=(dp[p]+'').replace(/^\s|\s$/,""); // trim
            ar = dp[p].split("=");
            key = ar[0]     // param
            ar = (ar[1]+'').split(";");
            desc = ar[0];   // description
            dt = ar[1];     // data type
            value = decode((currentParams[key]) ? currentParams[key]:(dt=='list') ? ar[3] : (ar[2])? ar[2]:'');
            if (value!=currentParams[key]) currentParams[key] = value;
            value = (value+'').replace(/^\s|\s$/,""); // trim
            if (dt) {
                switch(dt) {
                case 'int':
                    c = '<input type="text" name="prop_'+key+'" value="'+value+'" size="30" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" \/>';
                    break;
                case 'list':
                    c = '<select name="prop_'+key+'" height="1" style="width:168px" onchange="setParameter(\''+key+'\',\''+dt+'\',this)">';
                    ls = (ar[2]+'').split(",");
                    if(currentParams[key]==ar[2]) currentParams[key] = ls[0]; // use first list item as default
                    for(i=0;i<ls.length;i++){
                        c += '<option value="'+ls[i]+'"'+((ls[i]==value)? ' selected="selected"':'')+'>'+ls[i]+'<\/option>';
                    }
                    c += '<\/select>';
                    break;
                default:  // string
                    c = '<input type="text" name="prop_'+key+'" value="'+value+'" size="30" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" \/>';
                    break;

                }
                t +='<tr><td bgcolor="#FFFFFF" width="50%">'+desc+'<\/td><td bgcolor="#FFFFFF" width="50%">'+c+'<\/td><\/tr>';
            };
        }
        t+='<\/table>';
        td = (document.getElementById) ? document.getElementById('snippetparams'):document.all['snippetparams'];
        td.innerHTML = t;
    }
    implodeParameters();
}

function setParameter(key,dt,ctrl) {
    var v;
    if(!ctrl) return null;
    switch (dt) {
        case 'int':
            ctrl.value = parseInt(ctrl.value);
            if(isNaN(ctrl.value)) ctrl.value = 0;
            v = ctrl.value;
            break;
        case 'list':
            v = ctrl.options[ctrl.selectedIndex].value;
            break;
        default:
            v = ctrl.value+'';
            break;
    }
    currentParams[key] = v;
    implodeParameters();
}

function resetParameters() {
    document.mutate.params.value = "";
    lastmod[lastsp]="";
    showParameters();
}
// implode parameters
function implodeParameters(){
    var v, p, s='';
    for(p in currentParams){
        v = currentParams[p];
        if(v) s += '&'+p+'='+ encode(v);
    }
    //document.forms['mutate'].params.value = s;
    if (lastsp) lastmod[lastsp] = s;
}

function encode(s){
    s=s+'';
    s = s.replace(/\=/g,'%3D'); // =
    s = s.replace(/\&/g,'%26'); // &
    return s;
}

function decode(s){
    s=s+'';
    s = s.replace(/\%3D/g,'='); // =
    s = s.replace(/\%26/g,'&'); // &
    return s;
}

</script>

     <form name="mutate" method="post" enctype="multipart/form-data" action="index.php">
<?php

    // invoke OnDocFormPrerender event
$evtOut = $modx->invokeEvent("OnDocFormPrerender", array (
    "id" => $id
));
if (is_array($evtOut))
    echo implode("", $evtOut);
?>
<input type="hidden" name="a" value="5" />
<input type="hidden" name="id" value="<?php echo $content['id'];?>" />
<input type="hidden" name="mode" value="<?php echo $_REQUEST['a'];?>" />
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo isset($upload_maxsize)? $upload_maxsize:1048576; ?>" />
<input type="hidden" name="refresh_preview" value="0" />
<input type="hidden" name="newtemplate" value="" />

<div class="subTitle">
    <span class="right"><?php echo $_lang['edit_document_title']; ?></span>

    <table cellpadding="0" cellspacing="0" class="actionButtons">
        <tr>
            <td id="Button1"><a href="#" onclick="documentDirty=false; document.mutate.save.click();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" /> <?php echo $_lang['save']; ?></a></td>
            <td id="Button2"><a href="#" onclick="deletedocument();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/delete.gif" /> <?php echo $_lang['delete']; ?></a></td>
            <td id="Button5"><a href="#" onclick="documentDirty=false;<?php echo $id==0 ? "document.location.href='index.php?a=2';" : "document.location.href='index.php?a=3&amp;id=$id';"; ?>"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" /> <?php echo $_lang['cancel']; ?></a></td>
        </tr>
    </table>
    <script type="text/javascript">
        <?php if($_REQUEST['a']=='4' || $_REQUEST['a']==72) { ?>document.getElementById("Button2").className='disabled';<?php } ?>
    </script>
    <div class="stay">
    <table border="0" cellspacing="1" cellpadding="1">
    <tr>
        <td><span class="comment">&nbsp;<?php echo $_lang["after_saving"];?>:</span></td>
        <?php if ($modx->hasPermission('new_document')) { ?>
            <td><input name="stay" id="stay1" type="radio" class="radio" value="1" <?php echo $_REQUEST['stay']=='1' ? "checked='checked'":'' ?> /></td><td><label for="stay1" class="comment"><?php echo $_lang['stay_new']; ?></label></td>
        <?php } ?>
        <td><input name="stay" id="stay2" type="radio" class="radio" value="2" <?php echo $_REQUEST['stay']=='2' ? "checked='checked'":'' ?> /></td><td><label for="stay2" class="comment"><?php echo $_lang['stay']; ?></label></td>
        <td><input name="stay" id="stay3" type="radio" class="radio" value="" <?php echo $_REQUEST['stay']=='' ? "checked='checked'":'' ?> /></td><td><label for="stay3" class="comment"><?php echo $_lang['close']; ?></label></td>
    </tr>
    </table>
    </div>
</div>

<div class="sectionHeader"><?php echo $_lang['document_setting']; ?></div><div class="sectionBody">
    <script type="text/javascript" src="media/script/tabpane.js"></script>   

    <div class="tab-pane" id="documentPane">
        <script type="text/javascript">
            tpSettings = new WebFXTabPane( document.getElementById( "documentPane" ) );
        </script>

        <!-- General -->
        <div class="tab-page" id="tabGeneral">
            <h2 class="tab"><?php echo $_lang["settings_general"] ?></h2>
            <script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabGeneral" ) );</script>
            <?php

if ($content['type'] == "reference" || $_REQUEST['a'] == 72) {
                echo $_lang['weblink_message'];
}
?>
            <table width="450" border="0" cellspacing="0" cellpadding="0">
              <tr style="height: 24px;">
                <td width='100' align="left"><span class='warning'><?php echo $_lang['document_title']; ?></span></td>
                <td><input name="pagetitle" type="text" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($content['pagetitle']));?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;" spellcheck="true" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_title_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
              <tr style="height: 24px;">
                <td align="left"><span class='warning'><?php echo $_lang['long_title']; ?></span></td>
                <td><input name="longtitle" type="text" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($content['longtitle']));?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;" spellcheck="true" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_long_title_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['document_description']; ?></span></td>
                <td><input name="description" type="text" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($content['description']));?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;" spellcheck="true" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_description_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['document_alias']; ?></span></td>
                <td><input name="alias" type="text" maxlength="100" value="<?php echo stripslashes($content['alias']);?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_alias_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['link_attributes']; ?></span></td>
                <td><input name="link_attributes" type="text" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($content['link_attributes']));?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['link_attributes_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
            <?php if($content['type']=="reference" || $_REQUEST['a']==72) { ?>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['weblink']; ?></span> <img name="llock" src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/folder.gif" width="18" height="18" onclick="enableLinkSelection(!allowLinkSelection);" style="cursor:pointer;" /></td>
                <td><input name="ta" type="text" maxlength="255" value="<?php echo !empty($content['content']) ? stripslashes($content['content']) : "http://" ;?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_weblink_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
            <?php } else { ?>
              <tr style="height: 24px;">
                <td valign="top" width="100" align="left"><span class='warning'><?php echo $_lang['document_summary']; ?></span></td>
                <td valign="top"><textarea name="introtext" class="inputBox" rows="3" style="width:300px;" onchange="documentDirty=true;"><?php echo htmlspecialchars(stripslashes($content['introtext']));?></textarea>&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_summary_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" spellcheck="true"/></td>
              </tr>
             <?php } ?>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['page_data_template']; ?></span></td>
                <td>
            <?php

$sql = "select templatename, id from $dbase.`".$table_prefix."site_templates` ORDER BY templatename ASC";
                $rs = mysql_query($sql);
            ?>
            <select id="template" name="template" class="inputBox" onchange='templateWarning();' style="width:300px">
                <option value="0">(blank)</option>
            <?php

            while ($row = mysql_fetch_assoc($rs)) {
    if (isset ($_REQUEST['newtemplate'])) {
        $selectedtext = $row['id'] == $_REQUEST['newtemplate'] ? "selected='selected'" : "";
    } else
        if (isset ($content['template'])) {
            $selectedtext = $row['id'] == $content['template'] ? "selected='selected'" : "";
                } else {
            $selectedtext = $row['id'] == $default_template ? "selected='selected'" : "";
                }
            ?>
                <option value="<?php echo $row['id']; ?>" <?php echo $selectedtext; ?>><?php echo $row['templatename']; ?></option>
            <?php

            }
            ?>
                </select>
                &nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_template_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" />
                </td>
              </tr>
              <tr style="height: 24px;">
                <td align="left" style="width:100px;"><span class='warning'><?php echo $_lang['document_opt_menu_title']; ?></span></td>
                <td><input name="menutitle" type="text" maxlength="255" value="<?php echo htmlspecialchars(stripslashes($content['menutitle']));?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_menu_title_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
              <tr style="height: 24px;">
                <td align="left" style="width:100px;"><span class='warning'><?php echo $_lang['document_opt_menu_index']; ?></span></td>
                <td>
                <table border="0" cellspacing="0" cellpadding="0" style="width:325px;"><tr>
                <td><input name="menuindex" type="text" maxlength="3" value="<?php echo $content['menuindex'];?>" class="inputBox" style="width:30px;" onchange="documentDirty=true;" /><input type="button" class="button" value="&lt;" onclick="var elm = document.mutate.menuindex;var v=parseInt(elm.value+'')-1;elm.value=v>0? v:0;elm.focus();documentDirty=true;" /><input type="button" class="button" value="&gt;" onclick="var elm = document.mutate.menuindex;var v=parseInt(elm.value+'')+1;elm.value=v>0? v:0;elm.focus();documentDirty=true;" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_menu_index_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
                <td align="right"><span class='warning'><?php echo $_lang['document_opt_show_menu']; ?></span>&nbsp;<input name="hidemenucheck" type="checkbox" class="checkbox" <?php echo $content['hidemenu']!=1 ? 'checked="checked"':''; ?> onclick="changestate(document.mutate.hidemenu);" /><input type="hidden" name="hidemenu" class="hidden" value="<?php echo ($content['hidemenu']==1) ? 1 : 0 ;?>" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_show_menu_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
                </tr>
                </table>
                </td>
              </tr>
              <tr>
                <td colspan="2"><div class="split"></div></td>
              </tr>
              <tr style="height: 24px;">
                <td valign="top"><span class='warning'><?php echo $_lang['document_parent']; ?></span></td>
                <td valign="top"><?php

if (isset ($_REQUEST['id'])) {
    if ($content['parent'] == 0) {
                    $parentname = $site_name;
                } else {
        $sql = "SELECT pagetitle FROM $dbase.`" . $table_prefix . "site_content` WHERE $dbase.`" . $table_prefix . "site_content`.id = " . $content['parent'] . ";";
                    $rs = mysql_query($sql);
                    $limit = mysql_num_rows($rs);
        if ($limit != 1) {
                        $e->setError(8);
                        $e->dumpError();
                    }
                    $parentrs = mysql_fetch_assoc($rs);
                    $parentname = $parentrs['pagetitle'];
                }
} else
    if (isset ($_REQUEST['pid'])) {
        if ($_REQUEST['pid'] == 0) {
                    $parentname = $site_name;
                } else {
            $sql = "SELECT pagetitle FROM $dbase.`" . $table_prefix . "site_content` WHERE $dbase.`" . $table_prefix . "site_content`.id = " . $_REQUEST['pid'] . ";";
                    $rs = mysql_query($sql);
                    $limit = mysql_num_rows($rs);
            if ($limit != 1) {
                        $e->setError(8);
                        $e->dumpError();
                    }
                    $parentrs = mysql_fetch_assoc($rs);
                    $parentname = $parentrs['pagetitle'];
                }
            } else {
                    $parentname = $site_name;
        $content['parent'] = 0;
            }
            ?>&nbsp;<img name="plock" src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/tree/folder.gif" width="18" height="18" onclick="enableParentSelection(!allowParentSelection);" style="cursor:pointer;" /><b><span id="parentName"><?php echo isset($_REQUEST['pid']) ? $_REQUEST['pid'] : $content['parent']; ?> (<?php echo $parentname; ?>)</span></b><br />
            <span class="comment" style="width:300px;display:block;"><?php echo $_lang['document_parent_help'];?></span>
            <input type="hidden" name="parent" value="<?php echo isset($_REQUEST['pid']) ? $_REQUEST['pid'] : $content['parent']; ?>" onchange="documentDirty=true;" />
                </td>
              </tr>
            </table>
        </div>

        <!-- Settings -->
        <div class="tab-page" id="tabSettings">
            <h2 class="tab"><?php echo $_lang["settings_page_settings"] ?></h2>
            <script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabSettings" ) );</script>
            <table width="450" border="0" cellspacing="0" cellpadding="0">
              <tr style="height: 24px;">
                <td width="150"><span class='warning'><?php echo $_lang['document_opt_folder']; ?></span></td>
                <td><input name="isfoldercheck" type="checkbox" class="checkbox" <?php echo ($content['isfolder']==1||$_REQUEST['a']==85) ? "checked" : "" ;?> onclick="changestate(document.mutate.isfolder);" /><input type="hidden" name="isfolder" value="<?php echo ($content['isfolder']==1||$_REQUEST['a']==85) ? 1 : 0 ;?>" onchange="documentDirty=true;" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_folder_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
            <?php if($content['type']!="reference" && $_REQUEST['a']!=72) { ?>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['document_opt_richtext']; ?></span></td>
                <td><input name="richtextcheck" type="checkbox" class="checkbox" <?php echo $content['richtext']==0 && $_REQUEST['a']==27 ? "" : "checked" ;?> onclick="changestate(document.mutate.richtext);" /><input type="hidden" name="richtext" value="<?php echo $content['richtext']==0 && $_REQUEST['a']==27 ? 0 : 1 ;?>" onchange="documentDirty=true;" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_richtext_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
              <tr style="height: 24px;">
                <td width="150"><span class='warning'><?php echo $_lang['track_visitors_title']; ?></span></td>
                <td><input name="donthitcheck" type="checkbox" class="checkbox" <?php echo ($content['donthit']!=1) ? 'checked="checked"' : "" ;?> onclick="changestate(document.mutate.donthit);" /><input type="hidden" name="donthit" value="<?php echo ($content['donthit']==1) ? 1 : 0 ;?>" onchange="documentDirty=true;" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_trackvisit_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
            <?php } ?>
            <?php if($modx->hasPermission('publish_document')): // Publish permission set?>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['document_opt_published']; ?></span></td>
                <td><input name="publishedcheck" type="checkbox" class="checkbox" <?php echo (isset($content['published']) && $content['published']==1) || (!isset($content['published']) && $publish_default==1) ? "checked" : "" ;?> onclick="changestate(document.mutate.published);" /><input type="hidden" name="published" value="<?php echo (isset($content['published']) && $content['published']==1) || (!isset($content['published']) && $publish_default==1) ? 1 : 0 ;?>" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_published_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['page_data_publishdate']; ?></span></td>
                <td><input name="pub_date" value="<?php echo $content['pub_date']=="0" || !isset($content['pub_date']) ? "" : strftime("%d-%m-%Y %H:%M:%S", $content['pub_date']);?>" onblur="documentDirty=true;" />
                        <a onclick="documentDirty=false; cal1.popup();" onmouseover="window.status='<?php echo $_lang['select_date']; ?>'; return true;" onmouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal.gif" width="16" height="16" border="0" alt="<?php echo $_lang['select_date']; ?>" /></a>
                        <a onclick="document.mutate.pub_date.value=''; document.getElementById('pub_date_show').innerHTML='(<?php echo $_lang['not_set']?>)'; return true;" onmouseover="window.status='<?php echo $_lang['remove_date']?>'; return true;" onmouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="<?php echo $_lang['remove_date']; ?>" /></a>
                        &nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_publishdate_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" />
                </td>
              </tr>
              <tr>
                  <td></td>
                  <td style="color: #555;font-size:10px"><em> dd-mm-YYYY HH:MM:SS</em></td>
              </tr>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['page_data_unpublishdate']; ?></span></td>
                <td><input name="unpub_date" value="<?php echo $content['unpub_date']=="0" || !isset($content['unpub_date']) ? "" : strftime("%d-%m-%Y %H:%M:%S", $content['unpub_date']); ?>" onblur="documentDirty=true;" />
                        <a onclick="documentDirty=false; cal2.popup();" onmouseover="window.status='Select a date'; return true;" onmouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal.gif" width="16" height="16" border="0" /></a>
                        <a onclick="document.mutate.unpub_date.value=''; document.getElementById('unpub_date_show').innerHTML = '(not set)'; return true;" onmouseover="window.status='Don\'t set an unpublish date'; return true;" onmouseout="window.status=''; return true;" style="cursor:pointer; cursor:hand"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cal_nodate.gif" width="16" height="16" border="0" alt="No date" /></a>
                        &nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_unpublishdate_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" />
                </td>
              </tr>
              <tr>
                  <td></td>
                  <td style="color: #555;font-size:10px"><em> dd-mm-YYYY HH:MM:SS</em></td>
              </tr>
              <?php else: // No publish permission ?>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['document_opt_published']; ?></span></td>
                <td><input disabled="disabled" name="publishedcheck" type="checkbox" class="checkbox" <?php echo (isset($content['published']) && $content['published']==1) ? "checked" : "" ;?> /><input type="hidden" name="published" value="<?php echo (isset($content['published']) && $content['published']==1) ? 1 : 0 ;?>" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_published_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['page_data_publishdate']; ?></span></td>
                <td><input disabled="disabled" name="pub_date" value="<?php echo $content['pub_date']=="0" || !isset($content['pub_date']) ? "" : strftime("%d-%m-%Y %H:%M:%S", $content['pub_date']);?>" />
                        &nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_publishdate_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" />
                </td>
              </tr>
              <tr>
                  <td></td>
                  <td style="color: #555;font-size:10px"><em> dd-mm-YYYY HH:MM:SS</em></td>
              </tr>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['page_data_unpublishdate']; ?></span></td>
                <td><input disabled="disabled" name="unpub_date" value="<?php echo $content['unpub_date']=="0" || !isset($content['unpub_date']) ? "" : strftime("%d-%m-%Y %H:%M:%S", $content['unpub_date']); ?>" />
                        &nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_unpublishdate_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" />
                </td>
              </tr>
              <tr>
                  <td></td>
                  <td style="color: #555;font-size:10px"><em> dd-mm-YYYY HH:MM:SS</em></td>
              </tr>                                      
              <?php endif; // End publish?>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['page_data_searchable']; ?></span></td>
                <td><input name="searchablecheck" type="checkbox" class="checkbox" <?php echo (isset($content['searchable']) && $content['searchable']==1) || (!isset($content['searchable']) && $search_default==1) ? "checked" : "" ;?> onclick="changestate(document.mutate.searchable);" /><input type="hidden" name="searchable" value="<?php echo (isset($content['searchable']) && $content['searchable']==1) || (!isset($content['searchable']) && $search_default==1) ? 1 : 0 ;?>" onchange="documentDirty=true;" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_searchable_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
            <?php if($content['type']!="reference" && $_REQUEST['a']!=72) { ?>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['page_data_cacheable']; ?></span></td>
                <td><input name="cacheablecheck" type="checkbox" class="checkbox" <?php echo (isset($content['cacheable']) && $content['cacheable']==1) || (!isset($content['cacheable']) && $cache_default==1) ? "checked" : "" ;?> onclick="changestate(document.mutate.cacheable);" /><input type="hidden" name="cacheable" value="<?php echo (isset($content['cacheable']) && $content['cacheable']==1) || (!isset($content['cacheable']) && $cache_default==1) ? 1 : 0 ;?>" onchange="documentDirty=true;" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_cacheable_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['document_opt_emptycache']; ?></span></td>
                <td><input name="syncsitecheck" type="checkbox" class="checkbox" checked onclick="changestate(document.mutate.syncsite);" /><input type="hidden" name="syncsite" value="1" />&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_emptycache_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
            <?php if($_SESSION['mgrRole']==1) { ?>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['page_data_contentType']; ?></span></td>
                <td>
                    <select name="contentType" class="inputBox" onchange='documentDirty=true;' style="width:200px">
                    <?php

if (!$content['contentType'])
    $content['contentType'] = 'text/html';
$custom_contenttype = (isset ($custom_contenttype) ? $custom_contenttype : "text/html,text/plain,text/xml");
$ct = explode(",", $custom_contenttype);
for ($i = 0; $i < count($ct); $i++) {
    echo "<option value=\"" . $ct[$i] . "\"" . ($content['contentType'] == $ct[$i] ? "selected='selected'" : "") . ">" . $ct[$i] . "</option>";
                        }
                    ?>
                    </select>&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['page_data_contentType_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" />
                </td>
              </tr>
              <tr style="height: 24px;">
                <td><span class='warning'><?php echo $_lang['document_opt_contentdispo']; ?></span></td>
                <td><select name="content_dispo" size="1" onchange="documentDirty=true;" style="width:200px">
                <option value="0"<?php echo !$content["content_dispo"] ? ' selected="selected"':''; ?>><?php echo $_lang['inline']; ?></option>
                <option value="1"<?php echo $content["content_dispo"]==1 ? ' selected="selected"':''; ?>><?php echo $_lang['attachment']; ?></option>
                </select>&nbsp;&nbsp;<img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif" onmouseover="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02.gif';" onmouseout="this.src='media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/b02_trans.gif';" alt="<?php echo $_lang['document_opt_contentdispo_help']; ?>" onclick="alert(this.alt);" style="cursor:help;" /></td>
              </tr>
            <?php } else { ?>
            <input type="hidden" name="contentType" value="<?php echo isset($content['contentType']) ? $content['contentType'] : "text/html"; ?>" />
            <?php } ?>
            <input type="hidden" name="type" value="document" />
            <?php } else { ?>
            <input type="hidden" name="contentType" value="text/html" />
            <input type="hidden" name="cacheable" value="0" />
            <input type="hidden" name="syncsite" value="1" />
            <input type="hidden" name="richtext" value="0" />
            <input type="hidden" name="type" value="reference" />
            <?php } ?>
            </table>
        </div>

<?php if($modx->hasPermission('edit_doc_metatags')) { ?>
        <!-- META Keywords -->
<?php

    // get list of site keywords - code by stevew! modified by Raymond
$keywords = array ();
    $tbl = $modx->getFullTableName("site_keywords");
$ds = $modx->db->select("*", $tbl, "", "keyword ASC");
    $limit = $modx->db->getRecordCount($ds);
if ($limit > 0) {
    for ($i = 0; $i < $limit; $i++) {
            $row = $modx->db->getRow($ds);
            $keywords[$row['id']] = $row['keyword'];
        }
    }
    // get selected keywords using document's id
if (isset ($content['id']) && count($keywords) > 0) {
    $keywords_selected = array ();
        $tbl = $modx->getFullTableName("keyword_xref");
    $ds = $modx->db->select("keyword_id", $tbl, "content_id='" . $content['id'] . "'");
        $limit = $modx->db->getRecordCount($ds);
    if ($limit > 0) {
        for ($i = 0; $i < $limit; $i++) {
                $row = $modx->db->getRow($ds);
                $keywords_selected[$row['keyword_id']] = " selected=\"selected\"";
            }
        }
    }

    // get list of site META tags
$metatags = array ();
    $tbl = $modx->getFullTableName("site_metatags");
$ds = $modx->db->select("*", $tbl);
    $limit = $modx->db->getRecordCount($ds);
if ($limit > 0) {
    for ($i = 0; $i < $limit; $i++) {
            $row = $modx->db->getRow($ds);
            $metatags[$row['id']] = $row['name'];
        }
    }
    // get selected META tags using document's id
if (isset ($content['id']) && count($keywords) > 0) {
    $metatags_selected = array ();
        $tbl = $modx->getFullTableName("site_content_metatags");
    $ds = $modx->db->select("metatag_id", $tbl, "content_id='" . $content['id'] . "'");
        $limit = $modx->db->getRecordCount($ds);
    if ($limit > 0) {
        for ($i = 0; $i < $limit; $i++) {
                $row = $modx->db->getRow($ds);
                $metatags_selected[$row['metatag_id']] = " selected=\"selected\"";
            }
        }
    }
?>
        <div class="tab-page" id="tabMeta">
            <h2 class="tab"><?php echo $_lang["meta_keywords"]; ?></h2>
            <script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabMeta" ) );</script>
            <table width="450" border="0" cellspacing="0" cellpadding="0">
              <tr style="height: 24px;">
                <td>
                <?php echo $_lang['document_metatag_help']; ?><br /><br />
                <table border="0" style="width:inherit;">
                <tr>
                <td>
                    <span class='warning'><?php echo $_lang['keywords']; ?></span><br />
                    <select name="keywords[]" multiple="multiple" size="16" class="inputBox" style="width: 200px;" onchange="documentDirty=true;">
                        <?php

                        $keys = array_keys($keywords);
for ($i = 0; $i < count($keys); $i++) {
                            $key = $keys[$i];
                            $value = $keywords[$key];
                            $selected = $keywords_selected[$key];
                            echo "<option value=\"$key\"$selected>$value\n";
                        }
                        ?>
                    </select>&nbsp;&nbsp;
                    <br />
                    <input type="button" value="<?php echo $_lang['deselect_keywords']; ?>" onclick="clearKeywordSelection();" />
                </td>
                <td>
                    <span class='warning'><?php echo $_lang['metatags']; ?></span><br />
                    <select name="metatags[]" multiple="multiple" size="16" class="inputBox" style="width: 220px;" onchange="documentDirty=true;">
                        <?php

                        $keys = array_keys($metatags);
for ($i = 0; $i < count($keys); $i++) {
                            $key = $keys[$i];
                            $value = $metatags[$key];
                            $selected = $metatags_selected[$key];
                            echo "<option value=\"$key\"$selected>$value\n";
                        }
                        ?>
                    </select>
                    <br />
                    <input type="button" class="button" value="<?php echo $_lang['deselect_metatags']; ?>" onclick="clearMetatagSelection();" />
                </td>
                </table>
                </td>
              </tr>
             </table>
        </div>
<?php } ?>


    <?php if($_REQUEST['a']!='4' && $_REQUEST['a']!=72) { ?>
        <!-- Preview -->
        <div class="tab-page" id="tabPreview">
            <h2 class="tab"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/preview.gif" height="12" /> <?php echo $_lang['preview']; ?></h2>
            <script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabPreview" ), previewdocument );</script>
            <table width="96%" border="0"><tr><td><?php echo $_lang['preview_msg'];?></td></tr>
            <tr><td><iframe name="preview" frameborder="0" width="100%" height="400" id="previewIframe"></iframe></td></tr>
            </table>
        </div>
    <?php } ?>


    </div>
</div>

<!-- Content -->
<?php if($content['type']=="document" || $_REQUEST['a']==4) { ?>
<div class="sectionHeader"><?php echo $_lang['document_content']; ?></div><div class="sectionBody">
    <?php

if (($content['richtext'] == 1 || $_REQUEST['a'] == 4) && $use_editor == 1) {
        // replace image path
        $htmlContent = $content['content'];
    if (!empty ($htmlContent)) {
        if (substr($rb_base_url, -1) != '/') {
                $im_base_url = $rb_base_url . '/';
            } else {
                $im_base_url = $rb_base_url;
            }
            $elements = parse_url($im_base_url);
            $image_path = $elements['path'];
            // make sure image path ends with a /
        if (substr($image_path, -1) != '/') {
                $image_path .= '/';
            }
            $modx_root = dirname(dirname($_SERVER['PHP_SELF']));
            $image_prefix = substr($image_path, strlen($modx_root));
        if (substr($image_prefix, -1) != '/') {
                $image_prefix .= '/';
            }
            // escape / in path
            $image_prefix = str_replace('/', '\/', $image_prefix);
            $newcontent = preg_replace("/(<img[^>]+src=['\"])($image_prefix)([^'\"]+['\"][^>]*>)/", "\${1}$im_base_url\${3}", $content['content']);
            $htmlContent = $newcontent;
        }
        ?>
        <div style="width:100%">
            <textarea id="ta" name="ta" style="width:100%; height: 400px;" onchange="documentDirty=true;"><?php echo htmlspecialchars($htmlContent); ?></textarea> 
            <span class='warning'><?php echo $_lang["which_editor_title"]?></span>
            <select id="which_editor" name="which_editor" onchange="changeRTE();">
                <?php

                    // invoke OnRichTextEditorRegister event
                    $evtOut = $modx->invokeEvent("OnRichTextEditorRegister");
    echo "<option value='none'" . ($which_editor == 'none' ? " selected='selected'" : "") . ">" . $_lang["none"] . "</option>\n";
    if (is_array($evtOut))
        for ($i = 0; $i < count($evtOut); $i++) {
                        $editor = $evtOut[$i];
            echo "<option value='$editor'" . ($which_editor == $editor ? " selected='selected'" : "") . ">$editor</option>\n";
        }
                ?>
            </select>
        </div>
        <?php

    $replace_richtexteditor = array (
        "ta"
    );
    } else {
        ?>
        <div style="width:100%"><textarea id="ta" name="ta" style="width:100%; height: 400px;" onchange="documentDirty=true;"><?php echo htmlspecialchars($content['content']); ?></textarea> </div>
        <?php

    }
    ?>
</div>
<?php } ?>

<!-- Template Variables -->
<?php if (($content['type'] == "document" || $_REQUEST['a'] == 4) || ($content['type']=="reference" || $_REQUEST['a']==72)) { ?>
<div class='sectionHeader'><?php echo $_lang["settings_templvars"]; ?></div>
<div class="sectionBody tmplvars">
<?php

    $template = $default_template;
    if (isset ($_REQUEST['newtemplate'])) {
        $template = $_REQUEST['newtemplate'];
    } else {
        if (isset ($content['template'])) {
                $template = $content['template'];
        }
    }

    $sql = "SELECT DISTINCT tv.*, IF(tvc.value!='',tvc.value,tv.default_text) as value ";
    $sql .= "FROM $dbase.`" . $table_prefix . "site_tmplvars` tv ";
    $sql .= "INNER JOIN $dbase.`" . $table_prefix . "site_tmplvar_templates` tvtpl ON tvtpl.tmplvarid = tv.id ";
    $sql .= "LEFT JOIN $dbase.`" . $table_prefix . "site_tmplvar_contentvalues` tvc ON tvc.tmplvarid=tv.id AND tvc.contentid = $id ";
    $sql .= "LEFT JOIN $dbase.`" . $table_prefix . "site_tmplvar_access` tva ON tva.tmplvarid=tv.id  ";
    $sql .= "WHERE tvtpl.templateid = " . $template . " AND (1='" . $_SESSION['mgrRole'] . "' OR ISNULL(tva.documentgroup)" . ((!$docgrp) ? "" : " OR tva.documentgroup IN ($docgrp)") . ") ORDER BY tvtpl.rank,tv.rank;";
        $rs = mysql_query($sql);
        $limit = mysql_num_rows($rs);
    if ($limit > 0) {
            echo "<table style='position:relative' border='0' cellspacing='0' cellpadding='3' width='96%'>";
        require ('tmplvars.inc.php');
        require ('tmplvars.commands.inc.php');
        for ($i = 0; $i < $limit; $i++) {
                // go through and display all the document variables
                $row = mysql_fetch_assoc($rs);
            if ($row['type'] == 'richtext' || $row['type'] == 'htmlarea') { // htmlarea for backward compatibility
                    if (is_array($replace_richtexteditor))
                    $replace_richtexteditor = array_merge($replace_richtexteditor, array (
                        "tv" . $row['name']
                    ));
                    else
                    $replace_richtexteditor = array (
                        "tv" . $row['name']
                    );
                }
            // splitter
            if ($i > 0 && $i < $limit)
                echo '<tr><td colspan="2"><div class="split"></div></td></tr>';
        ?>
              <tr style="height: 24px;">
                <td align="left" valign="top" width="150">
                    <span class='warning'><?php echo $row['caption']; ?></span><br /><span class='comment'><?php echo $row['description']; ?></span>
                </td>
                <td valign="top" style="position:relative">
                <?php

            $tvPBV = $_POST['tv' . $row['name']]; // post back value
            echo renderFormElement($row['type'], $row['name'], $row['default_text'], $row['elements'], ($tvPBV ? $tvPBV : $row['value']), ' style="width:300px;"');
                ?>
                </td>
              </tr>
        <?php

        } //loop through all template variables
        ?>
        </table>
        <?php

    } else {
            echo $_lang['tmplvars_novars'];
    } //end check to see if there are template variables to display
?>
</div>
<?php
    } //end check to make sure it is not a weblink
?>

<?php
if ($use_udperms == 1) {
    $groupsarray = array ();

    if ($_REQUEST['a'] == '27') { // fetch permissions on the document from the database
        $sql = "SELECT * FROM $dbase.`" . $table_prefix . "document_groups` where document=" . $id;
    $rs = mysql_query($sql);
    $limit = mysql_num_rows($rs);
    for ($i = 0; $i < $limit; $i++) {
            $currentgroup = mysql_fetch_assoc($rs);
        $groupsarray[$i] = $currentgroup['document_group'];
    }
} else { // set permissions on the document based on the permissions of the parent document
        if (!empty ($_REQUEST['pid'])) {
            $sql = "SELECT * FROM $dbase.`" . $table_prefix . "document_groups` where document=" . $_REQUEST['pid'];
        $rs = mysql_query($sql);
        $limit = mysql_num_rows($rs);
        for ($i = 0; $i < $limit; $i++) {
                $currentgroup = mysql_fetch_assoc($rs);
            $groupsarray[$i] = $currentgroup['document_group'];
        }
    }
}

// retain selected doc groups between post
    if (isset ($_POST['docgroups'])) {
        $groupsarray = array_merge($groupsarray, $_POST['docgroups']);
}
?>

<!-- Access Permissions -->
<?php if($modx->hasPermission('web_access_permissions')) { ?>
<div class="sectionHeader"><?php echo $_lang['access_permissions']; ?></div><div class="sectionBody">
<script type="text/javascript">
    function makePublic(b){
        var notPublic=false;
        var f=document.forms['mutate'];
        var chkpub = f['chkalldocs'];
        var chks = f['docgroups[]'];
        if(!chks && chkpub) {
            chkpub.checked=true;
            return false;
        }
        else if (!b && chkpub) {
            if(!chks.length) notPublic=chks.checked;
            else for(i=0;i<chks.length;i++) if(chks[i].checked) notPublic=true;
            chkpub.checked=!notPublic;
        }
        else {
            if(!chks.length) chks.checked = (b)? false:chks.checked;
            else for(i=0;i<chks.length;i++) if (b) chks[i].checked=false;
            chkpub.checked=true;
        }
    }
</script>
<p><?php echo $_lang['access_permissions_docs_message']; ?></p>
<?php

    }
$sql = "SELECT name, id FROM $dbase.`" . $table_prefix . "documentgroup_names` ORDER BY name";
$rs = mysql_query($sql);
    $limit = mysql_num_rows($rs);
for ($i = 0; $i < $limit; $i++) {
    $row = mysql_fetch_assoc($rs);
        $checked = in_array($row['id'], $groupsarray);
    if ($modx->hasPermission('access_permissions')) {
        if ($checked)
            $notPublic = true;
        $chks .= "<input type='checkbox' class='checkbox' name='docgroups[]' value='" . $row['id'] . "' " . ($checked ? "checked='checked'" : '') . " onclick=\"makePublic(false)\" />" . $row['name'] . "<br />";
        } else {
        if ($checked)
            echo "<input type='hidden' name='docgroups[]'  value='" . $row['id'] . "' />";
        }
    }
if ($modx->hasPermission('access_permissions')) {
    $chks = "<input type='checkbox' class='checkbox' name='chkalldocs' " . (!$notPublic ? "checked='checked'" : '') . " onclick=\"makePublic(true)\" /><span class='warning'>" . $_lang['all_doc_groups'] . "</span><br />" . $chks;
    }
    echo $chks;
?>
</div>
<?php

}
?>

<input type="submit" name="save" style="display:none" />
<?php

    // invoke OnDocFormRender event
$evtOut = $modx->invokeEvent("OnDocFormRender", array (
    "id" => $id
));
if (is_array($evtOut))
    echo implode("", $evtOut);
?>
</form>
<script type="text/javascript">//setTimeout('showParameters()',10);</script>

<?php


/**
 *  Initialize RichText Editor
 *  orig MODIFIED BY S.BRENNAN for DocVars
 */
if ($content['type'] == "document" || $_REQUEST['a'] == 4) {
    if (($content['richtext'] == 1 || $_REQUEST['a'] == 4) && $use_editor == 1) {
        if (is_array($replace_richtexteditor)) {
            // invoke OnRichTextEditorInit event
            $evtOut = $modx->invokeEvent("OnRichTextEditorInit", array (
                'editor' => $which_editor,
                'elements' => $replace_richtexteditor
                                            ));
            if (is_array($evtOut))
                echo implode("", $evtOut);
        }
    }
}
?>

<script type="text/javascript">
    var cal1 = new calendar1(document.forms['mutate'].elements['pub_date'], document.getElementById("pub_date_show"));
    cal1.path="<?php echo str_replace("index.php", "media/", $_SERVER["PHP_SELF"]); ?>";
    cal1.year_scroll = true;
    cal1.time_comp = true;


    var cal2 = new calendar1(document.forms['mutate'].elements['unpub_date'], document.getElementById("unpub_date_show"));
    cal2.path="<?php echo str_replace("index.php", "media/", $_SERVER["PHP_SELF"]); ?>";
    cal2.year_scroll = true;
    cal2.time_comp = true;

</script>
