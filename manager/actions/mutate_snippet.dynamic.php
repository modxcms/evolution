<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

switch($modx->manager->action) {
  case 22:
    if(!$modx->hasPermission('edit_snippet')) {
      $modx->webAlertAndQuit($_lang["error_no_privileges"]);
    }
    break;
  case 23:
    if(!$modx->hasPermission('new_snippet')) {
      $modx->webAlertAndQuit($_lang["error_no_privileges"]);
    }
    break;
  default:
    $modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

// Get table Names (alphabetical)
$tbl_site_module_depobj = $modx->getFullTableName('site_module_depobj');
$tbl_site_modules       = $modx->getFullTableName('site_modules');
$tbl_site_snippets      = $modx->getFullTableName('site_snippets');

// check to see the snippet editor isn't locked
if ($lockedEl = $modx->elementIsLocked(4, $id)) {
        $modx->webAlertAndQuit(sprintf($_lang['lock_msg'],$lockedEl['username'],$_lang['snippet']));
}
// end check for lock

// Lock snippet for other users to edit
$modx->lockElement(4, $id);

$content = array();
if(isset($_GET['id'])) {
    $rs = $modx->db->select('*', $tbl_site_snippets, "id='{$id}'");
    $content = $modx->db->getRow($rs);
    if(!$content) {
        header("Location: ".MODX_SITE_URL."index.php?id=".$site_start);
    }
    $_SESSION['itemname']=$content['name'];
    if($content['locked']==1 && $_SESSION['mgrRole']!=1) {
        $modx->webAlertAndQuit($_lang["error_no_privileges"]);
    }
    $content['properties'] = str_replace("&", "&amp;", $content['properties']);
} else {
    $_SESSION['itemname']=$_lang["new_snippet"];
}

if ($modx->manager->hasFormValues()) {
    $modx->manager->loadFormValues();
}

$content = array_merge($content, $_POST);

// Add lock-element JS-Script
$lockElementId = $id;
$lockElementType = 4;
require_once(MODX_MANAGER_PATH.'includes/active_user_locks.inc.php');
?>
<script type="text/javascript">

function duplicaterecord(){
    if(confirm("<?php echo $_lang['confirm_duplicate_record']?>")==true) {
        documentDirty=false;
        document.location.href="index.php?id=<?php echo $_REQUEST['id']?>&a=98";
    }
}

function deletedocument() {
    if(confirm("<?php echo $_lang['confirm_delete_snippet']?>")==true) {
        documentDirty=false;
        document.location.href="index.php?id=" + document.mutate.id.value + "&a=25";
    }
}

function setTextWrap(ctrl,b){
    if(!ctrl) return;
    ctrl.wrap = (b)? "soft":"off";
}

// Current Params/Configurations
var currentParams = {};
var snippetConfig = {};
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
                label = ar[0];	// label
                dt = ar[1];		// data type
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

    t = '<table width="100%" class="displayparams"><thead><tr><td width="1%"><?php echo $_lang['parameter']; ?></td><td width="99%"><?php echo $_lang['value']; ?></td></tr></thead>';

    try {
        
        var type, options, found, info, sd;
        var ll, ls, sets = [];

        Object.keys(currentParams).forEach(function(key) {

                if (key == 'internal' || currentParams[key][0]['label'] == undefined) return;

                cp          = currentParams[key][0];
                type        = cp['type'];
                value       = cp['value'];
                defaultVal  = cp['default'];
                label       = cp['label'] != undefined ? cp['label'] : key;
                desc        = cp['desc']+'';
                options     = cp['options'] != undefined ? cp['options'] : '';

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
                        c = '<select name="prop_' + key + '" style="width:auto" onchange="setParameter(\'' + key + '\',\'' + type + '\',this)">';
                        if (currentParams[key] == options) currentParams[key] = ls[0]; // use first list item as default
                        for (i = 0; i < ls.length; i++) {
                            c += '<option value="' + ls[i] + '"' + ((ls[i] == value) ? ' selected="selected"' : '') + '>' + ll[i] + '</option>';
                        }
                        c += '</select>';
                        break;
                    case 'list':
                        if (currentParams[key] == options) currentParams[key] = ls[0]; // use first list item as default
                        c = '<select name="prop_' + key + '" size="' + ls.length + '" style="width:auto" onchange="setParameter(\'' + key + '\',\'' + type + '\',this)">';
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
                            c += '<label><input type="checkbox" name="prop_' + key + '[]" value="' +  ls[i] + '"' + ((contains(lv, ls[i]) == true) ? ' checked="checked"' : '') + ' onchange="setParameter(\'' + key + '\',\'' + type + '\',this)" />'+ll[i]+'</label>&nbsp;';
                        }
                        break;
                    case 'radio':
                        c = '';
                        for (i = 0; i < ls.length; i++) {
                            c += '<label><input type="radio" name="prop_' + key + '" value="' +  ls[i] + '"' + ((ls[i] == value) ? ' checked="checked"' : '') + ' onchange="setParameter(\'' + key + '\',\'' + type + '\',this)" />'+ll[i]+'</label>&nbsp;';
                        }
                        break;
                    case 'textarea':
                        c = '<textarea name="prop_' + key + '" style="width:80%" rows="4" onchange="setParameter(\'' + key + '\',\'' + type + '\',this)">' + value + '</textarea>';
                        break;
                    default:  // string
                        c = '<input type="text" name="prop_' + key + '" value="' + value + '" style="width:80%" onchange="setParameter(\'' + key + '\',\'' + type + '\',this)" />';
                        break;
                }

                info = '';
                info += desc ? '<br/><small>'+desc+'</small>' : '';
                sd = defaultVal != undefined ? ' <ul class="actionButtons" style="position:absolute;right:0px;bottom:6px;min-height:0;"><li><a href="#" class="primary btn-small btnSetDefault" onclick="setDefaultParam(\'' + key + '\',1);return false;"><?php echo $_lang["set_default"]; ?></a></li></ul>' : '';

            t += '<tr><td class="labelCell" bgcolor="#FFFFFF" width="20%"><span class="paramLabel">' + label + '</span><span class="paramDesc">'+ info + '</span></td><td class="inputCell relative" bgcolor="#FFFFFF" width="80%">' + c + sd + '</td></tr>';
            
        });

        t += '</table>';

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
</script>

<form name="mutate" method="post" action="index.php?a=24">
<?php
    // invoke OnSnipFormPrerender event
    $evtOut = $modx->invokeEvent("OnSnipFormPrerender",array("id" => $id));
    if(is_array($evtOut)) echo implode("",$evtOut);

    // Prepare info-tab via parseDocBlock
    $snippetcode = isset($content['snippet']) ? $modx->db->escape($content['snippet']) : '';
    $parsed = $modx->parseDocBlockFromString($snippetcode);
    $docBlockList = $modx->convertDocBlockIntoList($parsed);
?>
    <input type="hidden" name="id" value="<?php echo $content['id']?>">
    <input type="hidden" name="mode" value="<?php echo $modx->manager->action;?>">
    
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
          <?php if ($modx->manager->action == '23') { ?>
              <li id="Button6" class="disabled"><a href="#" onclick="duplicaterecord();"><img src="<?php echo $_style["icons_resource_duplicate"]?>" /> <?php echo $_lang["duplicate"]; ?></a></li>
              <li id="Button3" class="disabled"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"] ?>" /> <?php echo $_lang['delete']?></a></li>
          <?php } else { ?>
              <li id="Button6"><a href="#" onclick="duplicaterecord();"><img src="<?php echo $_style["icons_resource_duplicate"]?>" /> <?php echo $_lang["duplicate"]; ?></a></li>
              <li id="Button3"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"] ?>" /> <?php echo $_lang['delete']?></a></li>
          <?php } ?>
              <li id="Button5" class="transition"><a href="#" onclick="documentDirty=false;document.location.href='index.php?a=76';"><img src="<?php echo $_style["icons_cancel"]?>" /> <?php echo $_lang['cancel']?></a></li>
          </ul>
    </div>

    <h1 class="pagetitle">
      <span class="pagetitle-icon">
        <i class="fa fa-code"></i>
      </span>
      <span class="pagetitle-text">
        <?php echo $_lang['snippet_title']; ?>
      </span>
    </h1>

<div class="sectionBody">
  
<link type="text/css" rel="stylesheet" href="media/style/<?php echo $modx->config['manager_theme']; ?>/style.css<?php echo '?'.$theme_refresher?>" />
<script type="text/javascript" src="media/script/tabpane.js"></script>
<div class="tab-pane" id="snipetPane">
    <script type="text/javascript">
        tpSnippet = new WebFXTabPane( document.getElementById( "snipetPane"), <?php echo $modx->config['remember_last_tab'] == 1 ? 'true' : 'false'; ?> );
    </script>

    <!-- General -->
    <div class="tab-page" id="tabSnippet">
        <h2 class="tab"><?php echo $_lang['settings_general']?></h2>
        <script type="text/javascript">tpSnippet.addTabPage( document.getElementById( "tabSnippet" ) );</script>

        <p class="element-edit-message">
          <?php echo $_lang['snippet_msg']?>
        </p>
       
        <table>
          <tr>
            <th><?php echo $_lang['snippet_name']?></th>
            <td>[[&nbsp;<input name="name" type="text" maxlength="100" value="<?php echo $modx->htmlspecialchars($content['name'])?>" class="inputBox" style="width:250px;" onchange="documentDirty=true;">&nbsp;]]<span class="warning" id="savingMessage">&nbsp;</span>
            <script>document.getElementsByName("name")[0].focus();</script></td>
          </tr>
          <tr>
            <th><?php echo $_lang['snippet_desc']?></th>
            <td><input name="description" type="text" maxlength="255" value="<?php echo $content['description']?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;"></td>
          </tr>
          <tr>
            <th><?php echo $_lang['existing_category']?></th>
            <td><select name="categoryid" style="width:300px;" onchange="documentDirty=true;">
                    <option>&nbsp;</option>
                <?php
                    include_once(MODX_MANAGER_PATH.'includes/categories.inc.php');
                    foreach(getCategories() as $n=>$v){
                        echo '<option value="'.$v['id'].'"'.($content['category']==$v['id']? ' selected="selected"':'').'>'.$modx->htmlspecialchars($v['category']).'</option>';
                    }
                ?>
                </select>
            </td>
          </tr>
          <tr>
            <th><?php echo $_lang['new_category']?></th>
            <td><input name="newcategory" type="text" maxlength="45" value="" class="inputBox" style="width:300px;" onchange="documentDirty=true;"></td>
          </tr>
<?php if($modx->hasPermission('save_role')):?>
          <tr>
            <th valign="top" colspan="2"><label style="display:block;"><input name="locked" type="checkbox" <?php echo $content['locked']==1 ? "checked='checked'" : ""?> class="inputBox"> <?php echo $_lang['lock_snippet']?></label> <span class="comment"><?php echo $_lang['lock_snippet_msg']?></span></th>
          </tr>
          <tr>
            <th valign="top" colspan="2"><label style="display:block;"><input name="parse_docblock" type="checkbox" <?php echo $modx->manager->action == 23 ? 'checked="checked"' : ''; ?> value="1" class="inputBox"> <?php echo $_lang['parse_docblock']; ?></label> <span class="comment"><?php echo $_lang['parse_docblock_msg']; ?></span></th>
          </tr>
<?php endif;?>
        </table>
        <!-- PHP text editor start -->
        <div class="section">
            <div class="sectionHeader">
                <span style="float:right;"><?php echo $_lang['wrap_lines']?><input name="wrap" type="checkbox" <?php echo $content['wrap']== 1 ? "checked='checked'" : ""?> class="inputBox" onclick="setTextWrap(document.mutate.post,this.checked)" /></span>
                <?php echo $_lang['snippet_code']?>
            </div>
            <div class="sectionBody">
            <textarea dir="ltr" name="post" class="phptextarea" style="width:100%; height:370px;" wrap="<?php echo $content['wrap']== 1 ? "soft" : "off"?>" onchange="documentDirty=true;"><?php echo isset($content['post']) ? trim($modx->htmlspecialchars($content['post'])) : "<?php"."\n". trim($modx->htmlspecialchars($content['snippet'])) ."\n"; ?></textarea>
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
        <h2 class="tab"><?php echo $_lang['settings_properties']?></h2>
        <script type="text/javascript">tpSnippet.addTabPage( document.getElementById( "tabProps" ) );</script>
        <table>
          <tr>
            <th><?php echo $_lang['import_params']?></th>
            <td><select name="moduleguid" style="width:300px;" onchange="documentDirty=true;">
                    <option>&nbsp;</option>
                <?php
                    $ds = $modx->db->select(
						'sm.id,sm.name,sm.guid',
						"{$tbl_site_modules} AS sm 
							INNER JOIN {$tbl_site_module_depobj} AS smd ON smd.module=sm.id AND smd.type=40 
							INNER JOIN {$tbl_site_snippets} AS ss ON ss.id=smd.resource",
						"smd.resource='{$id}' AND sm.enable_sharedparams=1",
						'sm.name'
						);
                    while($row = $modx->db->getRow($ds)){
                        echo "<option value='".$row['guid']."'".($content['moduleguid']==$row['guid']? " selected='selected'":"").">".$modx->htmlspecialchars($row['name'])."</option>";
                    }
                ?>
                </select>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><span class="comment" ><?php echo $_lang['import_params_msg']?></span></td>
          </tr>
          <tr>
            <td colspan="2"><textarea name="properties" class="phptextarea" style="width:300px;" onChange='showParameters(this);documentDirty=true;'><?php echo $content['properties']?></textarea><br />
                <ul class="actionButtons" style="min-height:0;"><li><a href="#" class="primary" onclick='tpSnippet.pages[1].select();showParameters(this);return false;'><?php echo $_lang['update_params']; ?></a></li></ul>
            </td>
          </tr>
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
<?php
// invoke OnSnipFormRender event
$evtOut = $modx->invokeEvent("OnSnipFormRender",array("id" => $id));
if(is_array($evtOut)) echo implode("",$evtOut);
?>
</form>

<script type="text/javascript">
setTimeout('showParameters();',10);
</script>
