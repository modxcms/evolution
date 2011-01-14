<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

switch((int) $_REQUEST['a']) {
  case 102:
    if(!$modx->hasPermission('edit_plugin')) {
      $e->setError(3);
      $e->dumpError();
    }
    break;
  case 101:
    if(!$modx->hasPermission('new_plugin')) {
      $e->setError(3);
      $e->dumpError();
    }
    break;
  default:
    $e->setError(3);
    $e->dumpError();
}

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;


// check to see the plugin editor isn't locked
$sql = "SELECT internalKey, username FROM $dbase.`".$table_prefix."active_users` WHERE $dbase.`".$table_prefix."active_users`.action=102 AND $dbase.`".$table_prefix."active_users`.id=$id";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit>1) {
    for ($i=0;$i<$limit;$i++) {
        $lock = mysql_fetch_assoc($rs);
        if($lock['internalKey']!=$modx->getLoginUserID()) {
            $msg = sprintf($_lang["lock_msg"],$lock['username'],"plugin");
            $e->setError(5, $msg);
            $e->dumpError();
        }
    }
}
// end check for lock


if(isset($_GET['id'])) {
    $sql = "SELECT * FROM $dbase.`".$table_prefix."site_plugins` WHERE $dbase.`".$table_prefix."site_plugins`.id = $id;";
    $rs = mysql_query($sql);
    $limit = mysql_num_rows($rs);
    if($limit>1) {
        echo "Multiple plugins sharing same unique id. Not good.<p>";
        exit;
    }
    if($limit<1) {
        header("Location: /index.php?id=".$site_start);
    }
    $content = mysql_fetch_assoc($rs);
    $_SESSION['itemname']=$content['name'];
    if($content['locked']==1 && $_SESSION['mgrRole']!=1) {
        $e->setError(3);
        $e->dumpError();
    }
} else {
    $_SESSION['itemname']="New Plugin";
}
?>
<script language="JavaScript">

function duplicaterecord(){
    if(confirm("<?php echo $_lang['confirm_duplicate_record'] ?>")==true) {
        documentDirty=false;
        document.location.href="index.php?id=<?php echo $_REQUEST['id']; ?>&a=105";
    }
}

function deletedocument() {
    if(confirm("<?php echo $_lang['confirm_delete_plugin']; ?>")==true) {
        documentDirty=false;
        document.location.href="index.php?id=" + document.mutate.id.value + "&a=104";
    }
}

function setTextWrap(ctrl,b){
    if(!ctrl) return;
    ctrl.wrap = (b)? "soft":"off";
}

// Current Params/Configurations
var currentParams = {};

function showParameters(ctrl) {
    var c,p,df,cp;
    var ar,desc,value,key,dt;

    currentParams = {}; // reset;

    if (ctrl) {
        f = ctrl.form;
    } else {
        f= document.forms['mutate'];
        if(!f) return;
    }

    // setup parameters
    tr = (document.getElementById) ? document.getElementById('displayparamrow'):document.all['displayparamrow'];
    dp = (f.properties.value) ? f.properties.value.split("&"):"";
    if(!dp) tr.style.display='none';
    else {
        t='<table width="300" style="margin-bottom:3px;margin-left:14px;background-color:#EEEEEE" cellpadding="2" cellspacing="1"><thead><tr><td width="50%"><?php echo $_lang['parameter']; ?></td><td width="50%"><?php echo $_lang['value']; ?></td></tr></thead>';
        for(p = 0; p < dp.length; p++) {
            dp[p]=(dp[p]+'').replace(/^\s|\s$/,""); // trim
            ar = dp[p].split("=");
            key = ar[0]		// param
            ar = (ar[1]+'').split(";");
            desc = ar[0];	// description
            dt = ar[1];		// data type
            value = decode((ar[2])? ar[2]:'');

            // store values for later retrieval
            if (key && (dt=='list' || dt=='list-multi')) currentParams[key] = [desc,dt,value,ar[3]];
            else if (key) currentParams[key] = [desc,dt,value];

            if (dt) {
                switch(dt) {
                case 'int':
                    c = '<input type="text" name="prop_'+key+'" value="'+value+'" size="30" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" />';
                    break;
                case 'menu':
                    value = ar[3];
                    c = '<select name="prop_'+key+'" style="width:168px" onchange="setParameter(\''+key+'\',\''+dt+'\',this)">';
                    ls = (ar[2]+'').split(",");
                    if(currentParams[key]==ar[2]) currentParams[key] = ls[0]; // use first list item as default
                    for(i=0;i<ls.length;i++){
                        c += '<option value="'+ls[i]+'"'+((ls[i]==value)? ' selected="selected"':'')+'>'+ls[i]+'</option>';
                    }
                    c += '</select>';
                    break;
                case 'list':
                    value = ar[3];
                    ls = (ar[2]+'').split(",");
                    if(currentParams[key]==ar[2]) currentParams[key] = ls[0]; // use first list item as default
                    c = '<select name="prop_'+key+'" size="'+ls.length+'" style="width:168px" onchange="setParameter(\''+key+'\',\''+dt+'\',this)">';
                    for(i=0;i<ls.length;i++){
                        c += '<option value="'+ls[i]+'"'+((ls[i]==value)? ' selected="selected"':'')+'>'+ls[i]+'</option>';
                    }
                    c += '</select>';
                    break;
                case 'list-multi':
                    value = typeof ar[3] !== 'undefined' ? (ar[3]+'').replace(/^\s|\s$/,"") : '';
                    arrValue = value.split(",");
                    ls = (ar[2]+'').split(",");

                    if(currentParams[key]==ar[2]) currentParams[key] = ls[0]; // use first list item as default
                    c = '<select name="prop_'+key+'" size="'+ls.length+'" multiple="multiple" style="width:168px" onchange="setParameter(\''+key+'\',\''+dt+'\',this)">';
                    for(i=0;i<ls.length;i++){
                        if(arrValue.length){
                            var found = false;
                            for(j=0;j<arrValue.length;j++){
                                if (ls[i] == arrValue[j]) {
                                    found = true;
                                }
                            }
                            if(found == true){
                                c += '<option value="'+ls[i]+'" selected="selected">'+ls[i]+'</option>';
                            }else{
                                c += '<option value="'+ls[i]+'">'+ls[i]+'</option>';
                            }
                        }else{
                            c += '<option value="'+ls[i]+'">'+ls[i]+'</option>';
                        }
                    }
                    c += '</select>';
                    break;
                case 'textarea':
                    c = '<textarea class="phptextarea" name="prop_'+key+'" cols="50" rows="4" onchange="setParameter(\''+key+'\',\''+dt+'\',this)">'+value+'</textarea>';
                    break;
                default:  // string
                    c = '<input type="text" name="prop_'+key+'" value="'+value+'" size="30" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" />';
                    break;

                }
                t +='<tr><td bgcolor="#FFFFFF" width="50%">'+desc+'</td><td bgcolor="#FFFFFF" width="50%">'+c+'</td></tr>';
            };
        }
        t+='</table>';
        td = (document.getElementById) ? document.getElementById('displayparams'):document.all['displayparams'];
        td.innerHTML = t;
        tr.style.display='';
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
        case 'menu':
            v = ctrl.options[ctrl.selectedIndex].value;
            currentParams[key][3] = v;
            implodeParameters();
            return;
            break;
        case 'list':
            v = ctrl.options[ctrl.selectedIndex].value;
            currentParams[key][3] = v;
            implodeParameters();
            return;
            break;
        case 'list-multi':
            var arrValues = new Array;
            for(var i=0; i < ctrl.options.length; i++){
                if(ctrl.options[i].selected){
                    arrValues.push(ctrl.options[i].value);
                }
            }
            currentParams[key][3] = arrValues.toString();
            implodeParameters();
            return;
            break;
        default:
            v = ctrl.value+'';
            break;
    }
    currentParams[key][2] = v;
    implodeParameters();
}

// implode parameters
function implodeParameters(){
    var v, p, s='';
    for(p in currentParams){
        if(currentParams[p]) {
            v = currentParams[p].join(";");
            if(s && v) s+=' ';
            if(v) s += '&'+p+'='+ v;
        }
    }
    document.forms['mutate'].properties.value = s;
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

<form name="mutate" method="post" action="index.php?a=103">
<?php
// invoke OnPluginFormPrerender event
$evtOut = $modx->invokeEvent("OnPluginFormPrerender",array("id" => $id));
if(is_array($evtOut)) echo implode("",$evtOut);
?>
    <input type="hidden" name="id" value="<?php echo $content['id'];?>">
    <input type="hidden" name="mode" value="<?php echo $_GET['a'];?>">

    <h1><?php echo $_lang['plugin_title']; ?></h1>

    <div id="actions">
          <ul class="actionButtons">
              <li id="Button1">
                <a href="#" onclick="documentDirty=false; document.mutate.save.click();saveWait('mutate');">
                  <img src="<?php echo $_style["icons_save"]?>" /> <?php echo $_lang['save']?>
                </a>
                  <span class="and"> + </span>
                <select id="stay" name="stay">
                  <option id="stay1" value="1" <?php echo $_REQUEST['stay']=='1' ? ' selected=""' : ''?> ><?php echo $_lang['stay_new']?></option>
                  <option id="stay2" value="2" <?php echo $_REQUEST['stay']=='2' ? ' selected="selected"' : ''?> ><?php echo $_lang['stay']?></option>
                  <option id="stay3" value=""  <?php echo $_REQUEST['stay']=='' ? ' selected=""' : ''?>  ><?php echo $_lang['close']?></option>
                </select>
              </li>
              <?php
                if ($_GET['a'] == '102') { ?>
              <li id="Button2"><a href="#" onclick="duplicaterecord();"><img src="<?php echo $_style["icons_resource_duplicate"] ?>" /> <?php echo $_lang["duplicate"]; ?></a></li>
              <li id="Button3" class="disabled"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"] ?>" /> <?php echo $_lang['delete']?></a></li>
              <?php } else { ?>
              <li id="Button3"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"] ?>" /> <?php echo $_lang['delete']?></a></li>
              <?php } ?>
              <li id="Button5"><a href="#" onclick="documentDirty=false;document.location.href='index.php?a=76';"><img src="<?php echo $_style["icons_cancel"] ?>" /> <?php echo $_lang['cancel']?></a></li>
          </ul>
    </div>

<div class="sectionBody">
<p><?php echo $_lang['plugin_msg']; ?></p>
<script type="text/javascript" src="media/script/tabpane.js"></script>
<div class="tab-pane" id="snipetPane">
    <script type="text/javascript">
        tpSnippet = new WebFXTabPane( document.getElementById( "snipetPane"), <?php echo $modx->config['remember_last_tab'] == 1 ? 'true' : 'false'; ?> );
    </script>

<!-- General -->
<div class="tab-page" id="tabSnippet">
    <h2 class="tab"><?php echo $_lang["settings_general"] ?></h2>
    <script type="text/javascript">tpSnippet.addTabPage( document.getElementById( "tabSnippet" ) );</script>
        <table border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left"><?php echo $_lang['plugin_name']; ?>:</td>
            <td align="left"><input name="name" type="text" maxlength="100" value="<?php echo htmlspecialchars($content['name']);?>" class="inputBox" style="width:150px;" onChange='documentDirty=true;'><span class="warning" id='savingMessage'>&nbsp;</span></td>
          </tr>
          <tr>
            <td align="left"><?php echo $_lang['plugin_desc']; ?>:&nbsp;&nbsp;</td>
            <td align="left"><input name="description" type="text" maxlength="255" value="<?php echo $content['description'];?>" class="inputBox" style="width:300px;" onChange='documentDirty=true;'></td>
          </tr>
          <tr>
            <td align="left" valign="top" colspan="2"><input name="disabled" type="checkbox" <?php echo $content['disabled']==1 ? "checked='checked'" : "";?> value="on" class="inputBox"> <?php echo  $content['disabled']==1 ? "<span class='warning'>".$_lang['plugin_disabled']."</span>":$_lang['plugin_disabled']; ?></td>
          </tr>
          <tr>
            <td align="left" valign="top" colspan="2"><input name="locked" type="checkbox" <?php echo $content['locked']==1 ? "checked='checked'" : "" ;?> value="on" class="inputBox"> <?php echo $_lang['lock_plugin']; ?> <span class="comment"><?php echo $_lang['lock_plugin_msg']; ?></span></td>
          </tr>
        </table>
        <!-- PHP text editor start -->
        <div style="width:100%;position:relative">
            <div style="padding:1px; width:100%; height:16px;background-color:#eeeeee; border-top:1px solid #e0e0e0;margin-top:5px">
                <span style="float:left;color:#707070;font-weight:bold; padding:3px">&nbsp;<?php echo $_lang['plugin_code']; ?></span>
                <span style="float:right;color:#707070;"><?php echo $_lang['wrap_lines']; ?><input name="wrap" type="checkbox" <?php echo $content['wrap']== 1 ? "checked='checked'" : "" ;?> class="inputBox" onclick="setTextWrap(document.mutate.post,this.checked)" /></span>
        </div>
            <textarea dir="ltr" name="post" class="phptextarea" style="width:100%; height:370px;" wrap="<?php echo $content['wrap']== 1 ? "soft" : "off" ;?>" onchange="documentDirty=true;"><?php echo htmlspecialchars($content['plugincode']); ?></textarea>
        </div>
        <!-- PHP text editor end -->
        </div>

<!-- Configuration/Properties -->
<div class="tab-page" id="tabProps">
    <h2 class="tab"><?php echo $_lang["settings_config"] ?></h2>
    <script type="text/javascript">tpSnippet.addTabPage( document.getElementById( "tabProps" ) );</script>
        <table width="90%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left"><?php echo $_lang['existing_category']; ?>:&nbsp;&nbsp;</td>
            <td align="left"><select name="categoryid" style="width:300px;" onChange='documentDirty=true;'>
                <option>&nbsp;</option>
                <?php
                    include_once "categories.inc.php";
                    $ds = getCategories();
                    if($ds) foreach($ds as $n=>$v){
                        echo "<option value='".$v['id']."'".($content["category"]==$v["id"]? " selected='selected'":"").">".htmlspecialchars($v["category"])."</option>";
                    }
                ?>
            </select>
            </td>
          </tr>
          <tr>
            <td align="left" valign="top" style="padding-top:5px;"><?php echo $_lang['new_category']; ?>:</td>
            <td align="left" valign="top" style="padding-top:5px;"><input name="newcategory" type="text" maxlength="45" value="" class="inputBox" style="width:300px;" onChange='documentDirty=true;'></td>
          </tr>
          <tr>
            <td align="left"><?php echo $_lang['import_params']; ?>:&nbsp;&nbsp;</td>
            <td align="left"><select name="moduleguid" style="width:300px;" onChange='documentDirty=true;'>
                <option>&nbsp;</option>
                <?php
                    $sql =	"SELECT sm.id,sm.name,sm.guid " .
                            "FROM ".$modx->getFullTableName("site_modules")." sm ".
                            "INNER JOIN ".$modx->getFullTableName("site_module_depobj")." smd ON smd.module=sm.id AND smd.type=30 ".
                            "INNER JOIN ".$modx->getFullTableName("site_plugins")." sp ON sp.id=smd.resource ".
                            "WHERE smd.resource='$id' AND sm.enable_sharedparams='1' ".
                            "ORDER BY sm.name ";
                    $ds = $modx->dbQuery($sql);
                    if($ds) while($row = $modx->fetchRow($ds)){
                        echo "<option value='".$row['guid']."'".($content["moduleguid"]==$row["guid"]? " selected='selected'":"").">".htmlspecialchars($row["name"])."</option>";
                    }
                ?>
            </select>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td align="left" valign="top"><span style="width:300px;" ><span class="comment"><?php echo $_lang['import_params_msg']; ?></span></span><br /><br /></td>
          </tr>
          <tr>
            <td align="left" valign="top"><?php echo $_lang['plugin_config']; ?>:</td>
            <td align="left" valign="top"><textarea class="phptextarea" name="properties" onChange='showParameters(this);documentDirty=true;'><?php echo $content['properties'];?></textarea><br /><input type="button" value="<?php echo $_lang['update_params']; ?>" /></td>
          </tr>
          <tr id="displayparamrow">
            <td valign="top" align="left">&nbsp;</td>
            <td align="left" id="displayparams">&nbsp;</td>
          </tr>
        </table>
        </div>

<!-- System Events -->
<div class="tab-page" id="tabEvents">
    <h2 class="tab"><?php echo $_lang["settings_events"] ?></h2>
    <script type="text/javascript">tpSnippet.addTabPage( document.getElementById( "tabEvents" ) );</script>
        <table width="90%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="left" valign="top" colspan="2"><?php echo $_lang['plugin_event_msg']; ?><br />&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2">
                <table border="0">
                    <tr>
                        <td valign="top">&nbsp;&nbsp;</td>
                        <td>
    <table width="100%" border="0">
<?php

    // get selected events
    if(is_numeric($id) && $id > 0) {
        $sql = "
            SELECT evtid, pluginid
            FROM $dbase.`".$table_prefix."site_plugin_events`
            WHERE pluginid='$id'
        ";
        $evts = array();
        $rs = mysql_query($sql);
        $limit = mysql_num_rows($rs);
        for ($i=0; $i<$limit; $i++) {
           $row = mysql_fetch_assoc($rs);
           $evts[] = $row['evtid'];
        }
    } else {
        if(isset($content['sysevents']) && is_array($content['sysevents'])) {
            $evts = $content['sysevents'];
        } else {
            $evts = array();
        }
    }

    // display system events
    $evtnames = array();
    $services = array(
        "Parser Service Events",
        "Manager Access Events",
        "Web Access Service Events",
        "Cache Service Events",
        "Template Service Events",
        "User Defined Events"
    );
            $sql = "SELECT * FROM $dbase.`".$table_prefix."system_eventnames` ORDER BY service DESC, groupname, name";
    $rs = mysql_query($sql);
    $limit = mysql_num_rows($rs);
    if($limit==0) echo "<tr><td>&nbsp;</td></tr>";
    else for ($i=0; $i<$limit; $i++) {
        $row = mysql_fetch_assoc($rs);
        // display records
        if($srv!=$row['service']){
            $srv=$row['service'];
            if(count($evtnames)>0) echoEventRows($evtnames);
                echo "<tr><td colspan='2'><div class='split' style='margin:10px 0;'></div></td></tr>";
                echo "<tr><td colspan='2'><b>".$services[$srv-1]."</b></td></tr>";
        }
        // display group name
        if($grp!=$row['groupname']){
            $grp=$row['groupname'];
            if(count($evtnames)>0) echoEventRows($evtnames);
                echo "<tr><td colspan='2'><div class='split' style='margin:10px 0;'></div></td></tr>";
                echo "<tr><td colspan='2'><b>".$row['groupname']."</b></td></tr>";
        }
        $evtnames[] = '<input name="sysevents[]" type="checkbox"'.(in_array($row['id'],$evts) ? " checked='checked' " : "").'class="inputBox" value="'.$row['id'].'" />'.$row['name'];
        if(count($evtnames)==2) echoEventRows($evtnames);
    }
    if(count($evtnames)>0) echoEventRows($evtnames);

    function echoEventRows(&$evtnames) {
        echo "<tr><td>".implode("</td><td>",$evtnames)."</td></tr>";
        $evtnames = array();
    }
?>
    </table>
                        </td>
                    </tr>
                </table>
                &nbsp;
            </td>
          </tr>
        </table>
</div>
</div>
<input type="submit" name="save" style="display:none">
</div>
<?php
// invoke OnPluginFormRender event
$evtOut = $modx->invokeEvent("OnPluginFormRender",array("id" => $id));
if(is_array($evtOut)) echo implode("",$evtOut);
?>
</form>
<script type="text/javascript">
setTimeout('showParameters()',10);
</script>
