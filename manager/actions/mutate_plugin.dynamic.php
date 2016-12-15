<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

switch($modx->manager->action) {
  case 102:
    if(!$modx->hasPermission('edit_plugin')) {
      $modx->webAlertAndQuit($_lang["error_no_privileges"]);
    }
    break;
  case 101:
    if(!$modx->hasPermission('new_plugin')) {
      $modx->webAlertAndQuit($_lang["error_no_privileges"]);
    }
    break;
  default:
      $modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

$tbl_site_plugins       = $modx->getFullTableName('site_plugins');
$tbl_site_plugin_events = $modx->getFullTableName('site_plugin_events');
$tbl_system_eventnames  = $modx->getFullTableName('system_eventnames');

// check to see the plugin isn't locked
if ($lockedEl = $modx->elementIsLocked(5, $id)) {
	$modx->webAlertAndQuit(sprintf($_lang['lock_msg'],$lockedEl['username'],$_lang['plugin']));
}
// end check for lock

// Lock plugin for other users to edit
$modx->lockElement(5, $id);

if(isset($_GET['id']))
{
    $rs = $modx->db->select('*',$tbl_site_plugins,"id='{$id}'");
    $content = $modx->db->getRow($rs);
    if(!$content) {
        header("Location: {$modx->config['site_url']}");
    }
    $_SESSION['itemname']=$content['name'];
    if($content['locked']==1 && $modx->hasPermission('save_role')!=1)
    {
        $modx->webAlertAndQuit($_lang["error_no_privileges"]);
    }
    $content['properties'] = str_replace("&", "&amp;", $content['properties']);
}
else
{
    $_SESSION['itemname']=$_lang["new_plugin"];
}

if ($modx->manager->hasFormValues()) {
    $modx->manager->loadFormValues();
}

// Add lock-element JS-Script
$lockElementId = $id;
$lockElementType = 5;
require_once(MODX_MANAGER_PATH.'includes/active_user_locks.inc.php');
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
var first = true;

function showParameters(ctrl) {
    var c,p,df,cp;
    var ar,label,value,key,dt,defaultVal;

    currentParams = {}; // reset;

    if (ctrl) {
        f = ctrl.form;
    } else {
        f= document.forms['mutate'];
        if(!f) return;
    }

    tr = (document.getElementById) ? document.getElementById('displayparamrow') : document.all['displayparamrow'];

    // check if codemirror is used
    var props = typeof myCodeMirrors != "undefined" && typeof myCodeMirrors['properties'] != "undefined" ? myCodeMirrors['properties'].getValue() : f.properties.value;
    
    // convert old schemed setup parameters
    if( !IsJsonString(props) ) {
        dp = props ? props.match(/([^&=]+)=(.*?)(?=&[^&=]+=|$)/g) : ""; // match &paramname=
        if (!dp) tr.style.display = 'none';
        else {
            for (p = 0; p < dp.length; p++) {
                dp[p] = (dp[p] + '').replace(/^\s|\s$/, ""); // trim
                ar = dp[p].match(/(?:[^\=]|==)+/g); // split by =, not by ==
                key = ar[0];        // param
                ar = (ar[1] + '').split(";");
                label = ar[0];	    // label
                dt = ar[1];	    // data type
                value = decode((ar[2]) ? ar[2] : '');

                // convert values to new json-format
                if (key && (dt == 'menu' || dt == 'list' || dt == 'list-multi' || dt == 'checkbox' || dt == 'radio')) {
                    defaultVal = decode((ar[4]) ? ar[4] : ar[3]);
                    desc = decode((ar[5]) ? ar[5] : "");
                    currentParams[key] = [];
                    currentParams[key][0] = {"label":label, "type":dt, "value":ar[3], "options":value, "default":defaultVal, "desc":desc };
                } else if (key) {
                    defaultVal = decode((ar[3]) ? ar[3] : ar[2]);
                    desc = decode((ar[4]) ? ar[4] : "");
                    currentParams[key] = [];
                    currentParams[key][0] = {"label":label, "type":dt, "value":value, "default":defaultVal, "desc":desc };
                }
            }
        }
    } else {
        currentParams = JSON.parse(props);
    }

    t = '<table width="100%" class="displayparams"><thead><tr><td><?php echo $_lang['parameter']; ?></td><td><?php echo $_lang['value']; ?></td><td style="text-align:right;"><?php echo $_lang["set_default"]; ?> </td></tr></thead>';

    try {
        var type, options, found, info, sd;
        var ll, ls, sets = [];

        Object.keys(currentParams).forEach(function(key) {

                if (key == 'internal' || currentParams[key][0]['label'] == undefined) return;

                cp = currentParams[key][0];
                type = cp['type'];
                value = cp['value'];
                defaultVal = cp['default'];
                label = cp['label'] != undefined ? cp['label'] : key;
                desc = cp['desc'] + '';
                options = cp['options'] != undefined ? cp['options'] : '';

                ll = []; ls = [];
                if(options.indexOf('==') > -1) {
                    // option-format: label==value||label==value
                    sets = options.split("||");
                    for (i = 0; i < sets.length; i++) {
                        split = sets[i].split("==");
                        ll[i] = split[0];
                        ls[i] = split[1] != undefined ? split[1] : split[0];
                    }
                } else {
                    // option-format: value,value
                    ls = options.split(",");
                    ll = ls;
                }

                switch(type) {
                    case 'int':
                        c = '<input type="text" name="prop_' + key + '" value="' + value + '" size="30" onchange="setParameter(\'' + key + '\',\'' + type + '\',this)" />';
                        break;
                    case 'menu':
                        c = '<select name="prop_' + key + '" style="width:100%" onchange="setParameter(\'' + key + '\',\'' + type + '\',this)">';
                        if (currentParams[key] == options) currentParams[key] = ls[0]; // use first list item as default
                        for (i = 0; i < ls.length; i++) {
                            c += '<option value="' + ls[i] + '"' + ((ls[i] == value) ? ' selected="selected"' : '') + '>' + ll[i] + '</option>';
                        }
                        c += '</select>';
                        break;
                    case 'list':
                        if (currentParams[key] == options) currentParams[key] = ls[0]; // use first list item as default
                        c = '<select name="prop_' + key + '" size="' + ls.length + '" style="width:100%" onchange="setParameter(\'' + key + '\',\'' + type + '\',this)">';
                        for (i = 0; i < ls.length; i++) {
                            c += '<option value="' + ls[i] + '"' + ((ls[i] == value) ? ' selected="selected"' : '') + '>' + ll[i] + '</option>';
                        }
                        c += '</select>';
                        break;
                    case 'list-multi':
                        // value = typeof ar[3] !== 'undefined' ? (ar[3] + '').replace(/^\s|\s$/, "") : '';
                        arrValue = value.split(",");
                        if (currentParams[key] == options) currentParams[key] = ls[0]; // use first list item as default
                        c = '<select name="prop_' + key + '" size="' + ls.length + '" multiple="multiple" style="width:auto" onchange="setParameter(\'' + key + '\',\'' + type + '\',this)">';
                        for (i = 0; i < ls.length; i++) {
                            if (arrValue.length) {
                                found = false;
                                for (j = 0; j < arrValue.length; j++) {
                                    if (ls[i] == arrValue[j]) {
                                        found = true;
                                    }
                                }
                                if (found == true) {
                                    c += '<option value="' + ls[i] + '" selected="selected">' + ll[i] + '</option>';
                                } else {
                                    c += '<option value="' + ls[i] + '">' + ll[i] + '</option>';
                                }
                            } else {
                                c += '<option value="' + ls[i] + '">' + ll[i] + '</option>';
                            }
                        }
                        c += '</select>';
                        break;
                    case 'checkbox':
                        lv = (value + '').split(",");
                        c = '';
                        for (i = 0; i < ls.length; i++) {
                            c += '<label><input type="checkbox" name="prop_' + key + '[]" value="' +  ls[i] + '"' + ((contains(lv, ls[i]) == true) ? ' checked="checked"' : '') + ' onchange="setParameter(\'' + key + '\',\'' + type + '\',this)" />&nbsp;';
                        }
                        break;
                    case 'radio':
                        c = '';
                        for (i = 0; i < ls.length; i++) {
                            c += '<label><input type="radio" name="prop_' + key + '" value="' +  ls[i] + '"' + ((ls[i] == value) ? ' checked="checked"' : '') + ' onchange="setParameter(\'' + key + '\',\'' + type + '\',this)" />'+ll[i]+'</label>&nbsp;';
                        }
                        break;
                    case 'textarea':
                        c = '<textarea name="prop_' + key + '" style="width:100%" rows="4" onchange="setParameter(\'' + key + '\',\'' + type + '\',this)">' + value + '</textarea>';
                        break;
                    default:  // string
                        c = '<input type="text" style="width:100%" name="prop_' + key + '" value="' + value + '" style="width:80%" onchange="setParameter(\'' + key + '\',\'' + type + '\',this)" />';
                        break;
                }

                info = '';
                info += desc ? '<br/><small>' + desc + '</small>' : '';
                sd = defaultVal != undefined ? '<ul class="actionButtons" style="float:right;margin-top:12px;"><li><a title="<?php echo $_lang["set_default"]; ?>" href="#" class="btnSetDefault" onclick="setDefaultParam(\'' + key + '\',1);return false;"><i class="fa fa-refresh"></i></a></li></ul>' : '';

                t += '<tr><td class="labelCell" bgcolor="#FFFFFF" width="20%"><span class="paramLabel">' + label + '</span><span class="paramDesc">'+ info + '</span></td><td class="inputCell relative" bgcolor="#FFFFFF" width="74%">' + c + '</td><td style="align:center" bgcolor="#FFFFFF" >' + sd + '</td></tr>';
            });

        t += '</table>';
        
        createAssignEventsButton();
        
    } catch (e) {
        t = e + "\n\n" + props;
    }

    td = (document.getElementById) ? document.getElementById('displayparams') : document.all['displayparams'];
    td.innerHTML = t;
    tr.style.display = '';

    implodeParameters();
}

function setParameter(key,dt,ctrl) {
    var v;
    var arrValues, cboxes = [];
    if(!ctrl) return null;
    switch (dt) {
        case 'int':
            ctrl.value = parseInt(ctrl.value);
            if(isNaN(ctrl.value)) ctrl.value = 0;
            v = ctrl.value;
            break;
        case 'menu':
        case 'list':
            v = ctrl.options[ctrl.selectedIndex].value;
            break;
        case 'list-multi':
            arrValues = [];
            for(var i=0; i < ctrl.options.length; i++){
                if(ctrl.options[i].selected){
                    arrValues.push(ctrl.options[i].value);
                }
            }
            v = arrValues.toString();
            break;
        case 'checkbox':
            arrValues = [];
            cboxes = document.getElementsByName(ctrl.name);
            for(var i=0; i < cboxes.length; i++){
                if(cboxes[i].checked){
                    arrValues.push(cboxes[i].value);
                }
            }
            v = arrValues.toString();
            break;
        default:
            v = ctrl.value+'';
            break;
    }
    currentParams[key][0]['value'] = v;
    implodeParameters();
}

// implode parameters
function implodeParameters(){
    var stringified = JSON.stringify(currentParams, null, 2);
    if(typeof myCodeMirrors != "undefined") {
        myCodeMirrors['properties'].setValue(stringified);
    } else {
        f.properties.value = stringified;
    }
    if(first) { documentDirty = false; first = false; };
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

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function getEventsList() {
    var cboxes = document.getElementsByName('sysevents[]');
    var len = cboxes.length;
    var s = [];
    for (var i=0; i<len; i++) {
        if(cboxes[i].checked) s.push(cboxes[i].id);
    }
    return s.join();
}

function createAssignEventsButton() {
    if(document.getElementById('assignEvents') === null) {
        var button = document.createElement("div");
        button.setAttribute('id', 'assignEvents');
        button.innerHTML = '<ul class="actionButtons"><li><a class="primary" href="#" onclick="assignEvents();return false;"><?php echo $_lang["set_automatic"]; ?></a></li></ul>';
        var tab = document.getElementById("tabEvents");
        tab.insertBefore(button, tab.firstChild);
    }
}

function assignEvents() {
    // remove all events first
    var sysevents = document.getElementsByName("sysevents[]");
    for(var i = 0; i < sysevents.length; i++) { sysevents[i].checked = false; }
    // set events
    var events = internal[0]['events'];
    events = events.split(',');
    for(var i = 0; i < events.length; i++) { document.getElementById(events[i]).checked = true; }
}

function setDefaultParam(key, show) {
    if (typeof currentParams[key][0]['default'] != 'undefined') {
        currentParams[key][0]['value'] = currentParams[key][0]['default'];
        if(show) { implodeParameters(); showParameters(); }
    }
}

function setDefaults() {
    var keys = Object.keys(currentParams);
    var last = keys[keys.length-1],
        show;
    Object.keys(currentParams).forEach(function(key) {
        show = key == last ? 1 : 0;
        setDefaultParam(key, show);
    });
}

function contains(a, obj) {
    var i = a.length;
    while (i--) {
        if (a[i] === obj) {
            return true;
        }
    }
    return false;
}
<?php
// invoke OnPluginFormPrerender event
$evtOut = $modx->invokeEvent("OnPluginFormPrerender",array("id" => $id));
if(is_array($evtOut)) echo implode("",$evtOut);

// Prepare internal params & info-tab via parseDocBlock
$plugincode = isset($content['plugincode']) ? $modx->db->escape($content['plugincode']) : '';
$parsed = $modx->parseDocBlockFromString($plugincode);
$docBlockList = $modx->convertDocBlockIntoList($parsed);
$internal = array();
$internal[0]['events'] = isset($parsed['events']) ? $parsed['events'] : '';
?>
var internal = <?php echo json_encode($internal); ?>;
</script>

<form name="mutate" method="post" action="index.php?a=103" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?php echo $content['id'];?>">
    <input type="hidden" name="mode" value="<?php echo $modx->manager->action;?>">

    <h1 class="pagetitle">
      <span class="pagetitle-icon">
        <i class="fa fa-plug"></i>
      </span>
      <span class="pagetitle-text">
        <?php echo $_lang['plugin_title']; ?>
      </span>
    </h1>

    <div id="actions">
          <ul class="actionButtons">
              <li id="Button1" class="transition">
                <a href="#" onclick="documentDirty=false; form_save=true; document.mutate.save.click();saveWait('mutate');">
                  <img src="<?php echo $_style["icons_save"]?>" /> <?php echo $_lang['save']?>
                </a>
                <span class="plus"> + </span>
                <select id="stay" name="stay">
                  <option id="stay1" value="1" <?php echo $_REQUEST['stay']=='1' ? ' selected="selected"' : ''?> ><?php echo $_lang['stay_new']?></option>
                  <option id="stay2" value="2" <?php echo $_REQUEST['stay']=='2' ? ' selected="selected"' : ''?> ><?php echo $_lang['stay']?></option>
                  <option id="stay3" value=""  <?php echo $_REQUEST['stay']=='' ? ' selected="selected"' : ''?>  ><?php echo $_lang['close']?></option>
                </select>
              </li>
          <?php if ($modx->manager->action == '101') { ?>
              <li id="Button6" class="disabled"><a href="#" onclick="duplicaterecord();"><img src="<?php echo $_style["icons_resource_duplicate"] ?>" /> <?php echo $_lang["duplicate"]; ?></a></li>
              <li id="Button3" class="disabled"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"] ?>" /> <?php echo $_lang['delete']?></a></li>
          <?php } else { ?>
              <li id="Button6"><a href="#" onclick="duplicaterecord();"><img src="<?php echo $_style["icons_resource_duplicate"] ?>" /> <?php echo $_lang["duplicate"]; ?></a></li>
              <li id="Button3"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"] ?>" /> <?php echo $_lang['delete']?></a></li>
          <?php } ?>
              <li id="Button5" class="transition"><a href="#" onclick="documentDirty=false;document.location.href='index.php?a=76';"><img src="<?php echo $_style["icons_cancel"] ?>" /> <?php echo $_lang['cancel']?></a></li>
          </ul>
    </div>

<div class="sectionBody">
  
<script type="text/javascript" src="media/script/tabpane.js"></script>
<div class="tab-pane" id="pluginPane">
    <script type="text/javascript">
        tpSnippet = new WebFXTabPane( document.getElementById( "pluginPane"), <?php echo $modx->config['remember_last_tab'] == 1 ? 'true' : 'false'; ?> );
    </script>

<!-- General -->
<div class="tab-page" id="tabPlugin">
    <h2 class="tab"><?php echo $_lang["settings_general"] ?></h2>
    <script type="text/javascript">tpSnippet.addTabPage( document.getElementById( "tabPlugin" ) );</script>

    <p class="element-edit-message">
      <?php echo $_lang['plugin_msg']; ?>
    </p>
   
    <table>
      <tr>
        <th><?php echo $_lang['plugin_name']; ?></th>
        <td><input name="name" type="text" maxlength="100" value="<?php echo $modx->htmlspecialchars($content['name']);?>" class="inputBox" style="width:250px;" onchange="documentDirty=true;"><span class="warning" id="savingMessage">&nbsp;</span>
            <script>document.getElementsByName("name")[0].focus();</script></td>
      </tr>
      <tr>
        <th><?php echo $_lang['plugin_desc']; ?></th>
        <td><input name="description" type="text" maxlength="255" value="<?php echo $content['description'];?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;"></td>
      </tr>
      <tr>
        <th><?php echo $_lang['existing_category']; ?></th>
        <td><select name="categoryid" style="width:300px;" onchange="documentDirty=true;">
            <option>&nbsp;</option>
            <?php
                include_once(MODX_MANAGER_PATH.'includes/categories.inc.php');
                foreach(getCategories() as $n=>$v){
                    echo "<option value='".$v['id']."'".($content["category"]==$v["id"]? " selected='selected'":"").">".$modx->htmlspecialchars($v["category"])."</option>";
                }
            ?>
        </select>
        </td>
      </tr>
      <tr>
        <th><?php echo $_lang['new_category']; ?></th>
        <td><input name="newcategory" type="text" maxlength="45" value="" class="inputBox" style="width:300px;" onchange="documentDirty=true;"></td>
      </tr>
       <tr>
        <th colspan="2"><label><input name="disabled" type="checkbox" <?php echo $content['disabled']==1 ? "checked='checked'" : "";?> value="on" class="inputBox"> <?php echo  $content['disabled']==1 ? "<span class='warning'>".$_lang['plugin_disabled']."</span>":$_lang['plugin_disabled']; ?></label></th>
      </tr>
<?php if($modx->hasPermission('save_role')):?>
      <tr>
        <th colspan="2"><label style="display:block;"><input name="locked" type="checkbox" <?php echo $content['locked']==1 ? "checked='checked'" : "" ;?> value="on" class="inputBox"> <?php echo $_lang['lock_plugin']; ?></label> <span class="comment"><?php echo $_lang['lock_plugin_msg']; ?></span></th>
      </tr>
      <tr>     
        <th colspan="2"><label style="display:block;"><input name="parse_docblock" type="checkbox" <?php echo $modx->manager->action == 101 ? 'checked="checked"' : ''; ?> value="1" class="inputBox"> <?php echo $_lang['parse_docblock']; ?></label> <span class="comment"><?php echo $_lang['parse_docblock_msg']; ?></span></th>
      </tr>
<?php endif;?>
    </table>
    <!-- PHP text editor start -->
    <div class="section">
        <div class="sectionHeader">
            <span style="float:right;">&nbsp;<?php echo $_lang['wrap_lines']; ?><input name="wrap" type="checkbox" <?php echo $content['wrap']== 1 ? "checked='checked'" : "" ;?> class="inputBox" onclick="setTextWrap(document.mutate.post,this.checked)" /></span>
            <?php echo $_lang['plugin_code']; ?>
        </div>
        <div class="sectionBody">
        <textarea dir="ltr" name="post" class="phptextarea" style="width:100%;" wrap="<?php echo $content['wrap']== 1 ? "soft" : "off" ;?>" onchange="documentDirty=true;"><?php echo $modx->htmlspecialchars($content['plugincode']); ?></textarea>
        </div>
    </div>
    <!-- PHP text editor end -->
</div>

<!-- Config -->
<div class="tab-page" id="tabConfig">
    <h2 class="tab"><?php echo $_lang["settings_config"] ?></h2>
    <script type="text/javascript">tpSnippet.addTabPage( document.getElementById( "tabConfig" ) );</script>    
    <table border="0" cellspacing="0" cellpadding="6" width="100%">
        <tr>
          <td colspan="2">
            <ul class="actionButtons"
              <li><a href="#" class="primary" onclick='setDefaults(this);return false;'><?php echo $_lang['set_default_all']; ?></a></li>
            </ul>
          </td>
        </tr>
        <tr id="displayparamrow">
           <td valign="top" colspan="2" width="100%" id="displayparams">&nbsp;</td>
        </tr>   
    </table>
</div>        

<!-- Properties -->
<div class="tab-page" id="tabProps">
    <h2 class="tab"><?php echo $_lang["settings_properties"] ?></h2>
    <script type="text/javascript">tpSnippet.addTabPage( document.getElementById( "tabProps" ) );</script>
        <table border="0" cellspacing="0" cellpadding="6">
          <tr>
            <th><?php echo $_lang['import_params']; ?></th>
            <td><select name="moduleguid" style="width:300px;" onchange="documentDirty=true;">
                <option>&nbsp;</option>
                <?php
                    $ds = $modx->db->select(
						'sm.id,sm.name,sm.guid',
						$modx->getFullTableName("site_modules")." sm 
							INNER JOIN ".$modx->getFullTableName("site_module_depobj")." smd ON smd.module=sm.id AND smd.type=30
							INNER JOIN ".$modx->getFullTableName("site_plugins")." sp ON sp.id=smd.resource",
						"smd.resource='{$id}' AND sm.enable_sharedparams='1'",
						'sm.name'
						);
                    while($row = $modx->db->getRow($ds)){
                        echo "<option value='".$row['guid']."'".($content["moduleguid"]==$row["guid"]? " selected='selected'":"").">".$modx->htmlspecialchars($row["name"])."</option>";
                    }
                ?>
                </select>
            </td>
          </tr>
	        <tr>
		        <td></td>
		        <td><span class="comment"><?php echo $_lang['import_params_msg']; ?></span></td>
	        </tr>
          <tr>
            <td colspan="2" valign="top" width="900" id="displayproperties"><textarea class="phptextarea" style="width:98%;" name="properties" onChange='showParameters(this);documentDirty=true;'><?php echo $content['properties'];?></textarea><br />
                <ul class="actionButtons" style="min-height:0;"><li><a href="#" class="primary" onclick='tpSnippet.pages[1].select();showParameters(this);return false;'><?php echo $_lang['update_params']; ?></a></li></ul>
            </td>
          </tr>
        </table>
</div>

<!-- System Events -->
<div class="tab-page" id="tabEvents">
    <h2 class="tab"><?php echo $_lang["settings_events"] ?></h2>
    <script type="text/javascript">tpSnippet.addTabPage( document.getElementById( "tabEvents" ) );</script>
    <p><?php echo $_lang['plugin_event_msg']; ?></p>
    <table>
<?php

    // get selected events
    if(is_numeric($id) && $id > 0) {
        $rs = $modx->db->select('evtid',$tbl_site_plugin_events,"pluginid='{$id}'");
        $evts = $modx->db->getColumn('evtid', $rs);
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
    $rs = $modx->db->select('*',$tbl_system_eventnames,'','service DESC, groupname, name');
    $limit = $modx->db->getRecordCount($rs);
    if($limit==0) echo "<tr><td>&nbsp;</td></tr>";
    else while ($row = $modx->db->getRow($rs)) {
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
        $evtnames[] = '<input name="sysevents[]" id="' . $row['name'] . '" type="checkbox"'.(in_array($row['id'],$evts) ? " checked='checked' " : "").'class="inputBox" value="'.$row['id'].'" /><label for="'.$row['name']. '"' . bold(in_array($row[id],$evts)) . '>'.$row['name'].'</label>'."\n";
        if(count($evtnames)==2) echoEventRows($evtnames);
    }
    if(count($evtnames)>0) echoEventRows($evtnames);

    function echoEventRows(&$evtnames) {
        echo "<tr><td>".implode("</td><td>",$evtnames)."</td></tr>";
        $evtnames = array();
    }
?>
    </table>
</div>

<!-- docBlock Info -->
<div class="tab-page" id="tabDocBlock">
<h2 class="tab"><?php echo $_lang['information'];?></h2>
<script type="text/javascript">tpSnippet.addTabPage( document.getElementById( "tabDocBlock" ) );</script>
<div class="section">
        <?php echo $docBlockList; ?>
</div>
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
<?php
function bold($cond=false)
{
    if($cond!==false) return ' style="background-color:#777;color:#fff;"';
    else return;
}

