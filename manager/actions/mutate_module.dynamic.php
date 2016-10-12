<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
switch ((int) $_REQUEST['a']) {
    case 107:
        if(!$modx->hasPermission('new_module')) {
            $modx->webAlertAndQuit($_lang["error_no_privileges"]);
        }
        break;
    case 108:
        if(!$modx->hasPermission('edit_module')) {
            $modx->webAlertAndQuit($_lang["error_no_privileges"]);
        }
        break;
    default:
        $modx->webAlertAndQuit($_lang["error_no_privileges"]);
}
$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
// Get table names (alphabetical)
$tbl_active_users       = $modx->getFullTableName('active_users');
$tbl_membergroup_names  = $modx->getFullTableName('membergroup_names');
$tbl_site_content       = $modx->getFullTableName('site_content');
$tbl_site_htmlsnippets  = $modx->getFullTableName('site_htmlsnippets');
$tbl_site_module_access = $modx->getFullTableName('site_module_access');
$tbl_site_module_depobj = $modx->getFullTableName('site_module_depobj');
$tbl_site_modules       = $modx->getFullTableName('site_modules');
$tbl_site_plugins       = $modx->getFullTableName('site_plugins');
$tbl_site_snippets      = $modx->getFullTableName('site_snippets');
$tbl_site_templates     = $modx->getFullTableName('site_templates');
$tbl_site_tmplvars      = $modx->getFullTableName('site_tmplvars');
// create globally unique identifiers (guid)
function createGUID(){
    srand((double)microtime()*1000000);
    $r = rand() ;
    $u = uniqid(getmypid() . $r . (double)microtime()*1000000,1);
    $m = md5 ($u);
    return $m;
}
// Check to see the editor isn't locked
$rs = $modx->db->select('username', $tbl_active_users, "action=108 AND id='{$id}' AND internalKey!='".$modx->getLoginUserID()."'");
    if ($username = $modx->db->getValue($rs)) {
            $modx->webAlertAndQuit(sprintf($_lang['lock_msg'], $username, $_lang['module']));
    }
// end check for lock
if (isset($_GET['id'])) {
    $rs = $modx->db->select('*', $tbl_site_modules, "id='{$id}'");
    $content = $modx->db->getRow($rs);
    if (!$content) {
        $modx->webAlertAndQuit("Module not found for id '{$id}'.");
    }
    $content['properties'] = str_replace("&", "&amp;", $content['properties']);
    $_SESSION['itemname'] = $content['name'];
    if ($content['locked'] == 1 && $_SESSION['mgrRole'] != 1) {
        $modx->webAlertAndQuit($_lang["error_no_privileges"]);
    }
} else {
    $_SESSION['itemname'] = $_lang["new_module"];
    $content['wrap'] = '1';
}
if ($modx->manager->hasFormValues()) {
    $modx->manager->loadFormValues();
}
?>
<script type="text/javascript">
function loadDependencies() {
    if (documentDirty) {
        if (!confirm("<?php echo $_lang['confirm_load_depends']?>")) {
            return;
        }
    }
    documentDirty = false;
    window.location.href="index.php?id=<?php echo $_REQUEST['id']?>&a=113";
};
function duplicaterecord() {
    if(confirm("<?php echo $_lang['confirm_duplicate_record']?>")==true) {
        documentDirty=false;
        document.location.href="index.php?id=<?php echo $_REQUEST['id']?>&a=111";
    }
}
function deletedocument() {
    if(confirm("<?php echo $_lang['confirm_delete_module']?>")==true) {
        documentDirty=false;
        document.location.href="index.php?id=" + document.mutate.id.value + "&a=110";
    }
}
function setTextWrap(ctrl,b) {
    if(!ctrl) return;
    ctrl.wrap = (b)? "soft":"off";
}
// Current Params/Configurations
// Current Params/Configurations
var currentParams = {};
var internal = <?php echo json_encode($internal); ?>;
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

    t = '<table width="98%" class="displayparams"><thead><tr><td width="1%"><?php echo $_lang['parameter']; ?></td><td width="99%"><?php echo $_lang['value']; ?></td></tr></thead>';

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
                        c = '<textarea name="prop_' + key + '" style="width:98%" rows="4" onchange="setParameter(\'' + key + '\',\'' + type + '\',this)">' + value + '</textarea>';
                        break;
                    default:  // string
                        c = '<input type="text" name="prop_' + key + '" value="' + value + '" style="width:98%" onchange="setParameter(\'' + key + '\',\'' + type + '\',this)" />';
                        break;
                }

                info = '';
                info += desc ? '<br/><small>' + desc + '</small>' : '';
                sd = defaultVal != undefined ? ' <small><a class="btnSetDefault" style="float:right" onclick="setDefaultParam(\'' + key + '\',1);return false;"><?php echo $_lang["set_default"]; ?></a></small>' : '';

                t += '<tr><td class="labelCell" bgcolor="#FFFFFF" width="20%"><span class="paramLabel">' + label + '</span><span class="paramDesc">'+ info + '</span></td><td class="inputCell" bgcolor="#FFFFFF" width="80%">' + c + sd + '</td></tr>';
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
// Resource browser
function OpenServerBrowser(url, width, height ) {
    var iLeft = (screen.width  - width) / 2 ;
    var iTop  = (screen.height - height) / 2 ;
    var sOptions = "toolbar=no,status=no,resizable=yes,dependent=yes" ;
    sOptions += ",width=" + width ;
    sOptions += ",height=" + height ;
    sOptions += ",left=" + iLeft ;
    sOptions += ",top=" + iTop ;
    var oWindow = window.open( url, "FCKBrowseWindow", sOptions ) ;
}
function BrowseServer() {
    var w = screen.width * 0.7;
    var h = screen.height * 0.7;
    OpenServerBrowser("<?php echo MODX_MANAGER_URL;?>media/browser/<?php echo $which_browser;?>/browser.php?Type=images", w, h);
}
function SetUrl(url, width, height, alt) {
    document.mutate.icon.value = url;
}
</script>
<script type="text/javascript" src="media/script/tabpane.js"></script>
<link rel="stylesheet" type="text/css" href="media/style/<?php echo $modx->config['manager_theme']; ?>/style.css?<?php echo $theme_refresher?>" />

<form name="mutate" id="mutate" class="module" method="post" action="index.php?a=109">
<?php
    // invoke OnModFormPrerender event
    $evtOut = $modx->invokeEvent('OnModFormPrerender', array('id' => $id));
    if(is_array($evtOut)) echo implode('',$evtOut);

    // Prepare internal params & info-tab via parseDocBlock
    $modulecode = isset($content['modulecode']) ? $modx->db->escape($content['modulecode']) : '';
    $docBlock = $modx->parseDocBlockFromString($modulecode);
    $docBlockList = $modx->convertDocBlockIntoList($docBlock);
    $internal = array();
?>
<input type="hidden" name="id" value="<?php echo $content['id']?>">
<input type="hidden" name="mode" value="<?php echo $_GET['a']?>">

  <h1 class="pagetitle">
  <span class="pagetitle-icon">
    <i class="fa fa-cogs"></i>
  </span>
  <span class="pagetitle-text">
    <?php echo $_lang['module_title']; ?>
  </span>
</h1>
    <div id="actions">
          <ul class="actionButtons">
              <li id="Button1" class="transition">
                <a href="#" onclick="documentDirty=false; document.mutate.save.click();">
                  <img src="<?php echo $_style["icons_save"]?>" /> <?php echo $_lang['save']?>
                </a>
                <span class="plus"> + </span>
                <select id="stay" name="stay">
          <?php if ($modx->hasPermission('new_module')) { ?>
                  <option id="stay1" value="1" <?php echo $_REQUEST['stay']=='1' ? ' selected="selected"' : ''?> ><?php echo $_lang['stay_new']?></option>
          <?php } ?>
                  <option id="stay2" value="2" <?php echo $_REQUEST['stay']=='2' ? ' selected="selected"' : ''?> ><?php echo $_lang['stay']?></option>
                  <option id="stay3" value=""  <?php echo $_REQUEST['stay']=='' ? ' selected="selected"' : ''?>  ><?php echo $_lang['close']?></option>
                </select>
              </li>
          <?php if ($_REQUEST['a'] == '107') { ?>
              <li id="Button3" class="disabled"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"]?>" /> <?php echo $_lang['delete']?></a></li>
          <?php } else { ?>
              <li id="Button3"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"]?>" /> <?php echo $_lang['delete']?></a></li>
          <?php } ?>
              <li id="Button4" class="transition"><a href="#" onclick="documentDirty=false;document.location.href='index.php?a=106';"><img src="<?php echo $_style["icons_cancel"] ?>" /> <?php echo $_lang['cancel']?></a></li>
          </ul>
    </div>
    <!-- end #actions -->

<div class="sectionBody"><p><?php echo $_lang['module_msg']?></p>

<div class="tab-pane" id="modulePane">
    <script type="text/javascript">
    tp = new WebFXTabPane( document.getElementById( "modulePane"), <?php echo $modx->config['remember_last_tab'] == 1 ? 'true' : 'false'; ?> );
    </script>

    <!-- General -->
    <div class="tab-page" id="tabModule">
    <h2 class="tab"><?php echo $_lang['settings_general']?></h2>
    <script type="text/javascript">tp.addTabPage( document.getElementById( "tabModule" ) );</script>

    <table border="0" cellspacing="0" cellpadding="1">
        <tr><td align="left"><?php echo $_lang['module_name']?>:</td>
            <td align="left"><input name="name" type="text" maxlength="100" value="<?php echo $modx->htmlspecialchars($content['name'])?>" class="inputBox" style="width:150px;" onchange="documentDirty=true;">&nbsp;<span class="warning" id="savingMessage">&nbsp;</span></td></tr>
    </table>

    <!-- PHP text editor start -->
        <div class="sectionHeader">
            <span style="float:right;><?php echo $_lang['wrap_lines']?><input name="wrap" type="checkbox"<?php echo $content['wrap']== 1 ? ' checked="checked"' : ''?> class="inputBox" onclick="setTextWrap(document.mutate.post,this.checked)" /></span>
            <?php echo $_lang['module_code']?>
        </div>
        <div class="sectionBody">
        <textarea dir="ltr" class="phptextarea" name="post" style="width:100%; height:370px;" wrap="<?php echo $content['wrap']== 1 ? 'soft' : 'off'?>" onchange="documentDirty=true;"><?php echo $modx->htmlspecialchars($content['modulecode'])?></textarea>
        </div>
    <!-- PHP text editor end -->
    </div>
    <!-- Configuration -->
    <div class="tab-page" id="tabConfig">
        <h2 class="tab"><?php echo $_lang['settings_config']?></h2>
        <script type="text/javascript">tp.addTabPage( document.getElementById( "tabConfig" ) );</script>

        <table width="90%" border="0" cellspacing="0" cellpadding="0">
            <tr><td align="left" valign="top"><?php echo $_lang['guid']?>:</td>
                <td align="left" valign="top"><input name="guid" type="text" maxlength="32" value="<?php echo (int) $_REQUEST['a'] == 107 ? createGUID() : $content['guid']?>" class="inputBox" onchange="documentDirty=true;" /><br /><br /></td></tr>
            <tr><td align="left" valign="top"><input name="enable_sharedparams" type="checkbox"<?php echo $content['enable_sharedparams']==1 ? ' checked="checked"' : ''?> class="inputBox" onclick="documentDirty=true;" /> <span style="cursor:pointer" onclick="document.mutate.enable_sharedparams.click();"><?php echo $_lang['enable_sharedparams']?>:</span></td>
                <td align="left" valign="top"><span ><span class="comment"><?php echo $_lang['enable_sharedparams_msg']?></span></span><br /><br /></td></tr>
            <tr>
                <th valign="top"><?php echo $_lang['parse_docblock']; ?>:</th>
                <td valign="top"><label style="display:block;"><input name="parse_docblock" type="checkbox" <?php echo $_REQUEST['a'] == 107 ? 'checked="checked"' : ''; ?> value="1" class="inputBox"> <?php echo $_lang['parse_docblock']; ?></label> <span class="comment"><?php echo $_lang['parse_docblock_msg']; ?></span><br/><br/></td>
            </tr>
            <tr><td align="left" valign="top"><?php echo $_lang['module_config']?>:</td>
                <td align="left" valign="top"><textarea name="properties" maxlength="65535" class="phptextarea" style="width:280px;" onchange="showParameters(this);documentDirty=true;"><?php echo $content['properties']?></textarea><br />
                    <input type="button" onclick="showParameters(this);" value="<?php echo $_lang['update_params'] ?>" style="width:16px; margin-left:2px;" title="<?php echo $_lang['update_params']?>" />
                    <input type="button" onclick="setDefaults(this)" value="<?php echo $_lang['set_default_all']; ?>" />
                </td>
            </tr>
            <tr id="displayparamrow"><td valign="top" align="left">&nbsp;</td>
                <td align="left" id="displayparams">&nbsp;</td></tr>
        </table>
    </div>
<?php if ($_REQUEST['a'] == '108'): ?>
    <!-- Dependencies -->
    <div class="tab-page" id="tabDepend">
    <h2 class="tab"><?php echo $_lang['settings_dependencies']?></h2>
    <script type="text/javascript">tp.addTabPage( document.getElementById( "tabDepend" ) );</script>
    <table width="95%" border="0" cellspacing="0" cellpadding="0">
    <tr><td align="left" valign="top"><p><?php echo $_lang['module_viewdepend_msg']?><br /><br />
        <a class="searchtoolbarbtn" href="#" style="float:left" onclick="loadDependencies();return false;"><img src="<?php echo $_style["icons_save"]?>" align="absmiddle" /> <?php echo $_lang['manage_depends']?></a><br /><br /></p></td></tr>
    <tr><td valign="top" align="left">
<?php
$ds = $modx->db->select(
    "smd.id, COALESCE(ss.name,st.templatename,sv.name,sc.name,sp.name,sd.pagetitle) AS name, 
	CASE smd.type
		WHEN 10 THEN 'Chunk'
		WHEN 20 THEN 'Document'
		WHEN 30 THEN 'Plugin'
		WHEN 40 THEN 'Snippet'
		WHEN 50 THEN 'Template'
		WHEN 60 THEN 'TV'
	END AS type",
	"{$tbl_site_module_depobj} AS smd 
		LEFT JOIN {$tbl_site_htmlsnippets} AS sc ON sc.id = smd.resource AND smd.type = 10 
		LEFT JOIN {$tbl_site_content} AS sd ON sd.id = smd.resource AND smd.type = 20
		LEFT JOIN {$tbl_site_plugins} AS sp ON sp.id = smd.resource AND smd.type = 30
		LEFT JOIN {$tbl_site_snippets} AS ss ON ss.id = smd.resource AND smd.type = 40
		LEFT JOIN {$tbl_site_templates} AS st ON st.id = smd.resource AND smd.type = 50
		LEFT JOIN {$tbl_site_tmplvars} AS sv ON sv.id = smd.resource AND smd.type = 60",
	"smd.module='{$id}'",
	'smd.type,name');
    include_once MODX_MANAGER_PATH."includes/controls/datagrid.class.php";
    $grd = new DataGrid('', $ds, 0); // set page size to 0 t show all items
    $grd->noRecordMsg = $_lang['no_records_found'];
    $grd->cssClass = 'grid';
    $grd->columnHeaderClass = 'gridHeader';
    $grd->itemClass = 'gridItem';
    $grd->altItemClass = 'gridAltItem';
    $grd->columns = $_lang['element_name']." ,".$_lang['type'];
    $grd->fields = "name,type";
    echo $grd->render();
?>
        </td></tr>
    </table>
    </div>
<?php endif; ?>

<!-- Properties -->
<div class="tab-page" id="tabInfo">
<h2 class="tab"><?php echo $_lang['settings_properties'];?></h2>
<script type="text/javascript">tp.addTabPage( document.getElementById( "tabInfo" ) );</script>
<div class="section">
<table>
        <tr><td align="left" valign="top" colspan="2"><input name="disabled" type="checkbox" <?php echo $content['disabled'] == 1 ? 'checked="checked"' : ''?> value="on" class="inputBox" />
            <span style="cursor:pointer" onclick="document.mutate.disabled.click();"><?php echo  $content['disabled'] == 1 ? '<span class="warning">'.$_lang['module_disabled'].'</span>' : $_lang['module_disabled']?></span></td></tr>
        <tr><td align="left"><?php echo $_lang['module_desc']?>:&nbsp;&nbsp;</td>
            <td align="left"><input name="description" type="text" maxlength="255" value="<?php echo $content['description']?>" class="inputBox" onchange="documentDirty=true;"></td></tr>
        <tr><td align="left"><?php echo $_lang['existing_category']?>:&nbsp;&nbsp;</td>
            <td align="left">
            <select name="categoryid" onchange="documentDirty=true;">
                <option>&nbsp;</option>
<?php
                include_once(MODX_MANAGER_PATH.'includes/categories.inc.php');
                foreach(getCategories() as $n => $v) {
                    echo "\t\t\t".'<option value="'.$v['id'].'"'.($content['category'] == $v['id'] ? ' selected="selected"' : '').'>'.$modx->htmlspecialchars($v['category'])."</option>\n";
                }
?>
            </select></td></tr>
        <tr><td align="left" valign="top" style="padding-top:5px;"><?php echo $_lang['new_category']?>:</td>
            <td align="left" valign="top" style="padding-top:5px;"><input name="newcategory" type="text" maxlength="45" value="" class="inputBox" onchange="documentDirty=true;"></td></tr>
        <tr><td align="left"><?php echo $_lang['icon']?> <span class="comment">(32x32)</span>:&nbsp;&nbsp;</td>
            <td align="left"><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 235px;" name="icon" value="<?php echo $content['icon']?>" /> <input type="button" value="<?php echo $_lang['insert']?>" onclick="BrowseServer();" /></td></tr>
        <tr><td align="left"><input name="enable_resource" title="<?php echo $_lang['enable_resource']?>" type="checkbox"<?php echo $content['enable_resource']==1 ? ' checked="checked"' : ''?> class="inputBox" onclick="documentDirty=true;" /> <span style="cursor:pointer" onclick="document.mutate.enable_resource.click();" title="<?php echo $_lang['enable_resource']?>"><?php echo $_lang["element"]?></span>:</td>
            <td align="left"><input name="resourcefile" type="text" maxlength="255" value="<?php echo $content['resourcefile']?>" class="inputBox" onchange="documentDirty=true;" /></td></tr>
        <tr><td align="left" valign="top" colspan="2"><input name="locked" type="checkbox"<?php echo $content['locked'] == 1 ? ' checked="checked"' : ''?> class="inputBox" />
            <span style="cursor:pointer" onclick="document.mutate.locked.click();"><?php echo $_lang['lock_module']?></span> <span class="comment"><?php echo $_lang['lock_module_msg']?></span></td></tr>
</table>
</div>

<?php if ($use_udperms == 1) : ?>
<?php
    // fetch user access permissions for the module
    $rs = $modx->db->select('usergroup', $tbl_site_module_access, "module='{$id}'");
    $groupsarray = $modx->db->getColumn('usergroup', $rs);

    if($modx->hasPermission('access_permissions')) { ?>
<!-- User Group Access Permissions -->
<div class="section">
<div class="sectionHeader"><?php echo $_lang['group_access_permissions']?></div>
<div class="sectionBody">
    <script type="text/javascript">
    function makePublic(b) {
        var notPublic=false;
        var f=document.forms['mutate'];
        var chkpub = f['chkallgroups'];
        var chks = f['usrgroups[]'];
        if (!chks && chkpub) {
            chkpub.checked=true;
            return false;
        } else if (!b && chkpub) {
            if(!chks.length) notPublic=chks.checked;
            else for(i=0;i<chks.length;i++) if(chks[i].checked) notPublic=true;
            chkpub.checked=!notPublic;
        } else {
            if(!chks.length) chks.checked = (b) ? false : chks.checked;
            else for(i=0;i<chks.length;i++) if (b) chks[i].checked=false;
            chkpub.checked=true;
        }
    }
    </script>
    <p><?php echo $_lang['module_group_access_msg']?></p>
<?php
    }
    $chk = '';
    $rs = $modx->db->select('name, id', $tbl_membergroup_names);
    while ($row = $modx->db->getRow($rs)) {
        $groupsarray = is_numeric($id) && $id > 0 ? $groupsarray : array();
        $checked = in_array($row['id'], $groupsarray);
        if($modx->hasPermission('access_permissions')) {
            if ($checked) $notPublic = true;
            $chks .= '<input type="checkbox" name="usrgroups[]" value="'.$row['id'].'"'.($checked ? ' checked="checked"' : '').' onclick="makePublic(false)" />'.$row['name']."<br />\n";
        } else {
            if ($checked) $chks = '<input type="hidden" name="usrgroups[]"  value="'.$row['id'].'" />' . "\n" . $chks;
        }
    }
    if($modx->hasPermission('access_permissions')) {
        $chks = '<input type="checkbox" name="chkallgroups"'.(!$notPublic ? ' checked="checked"' : '').' onclick="makePublic(true)" /><span class="warning">'.$_lang['all_usr_groups'].'</span><br />' . "\n" . $chks;
    }
    echo $chks;
?>
</div>
</div>
<?php endif; ?>
</div>
    
<!-- docBlock Info -->
<div class="tab-page" id="tabDocBlock">
<h2 class="tab"><?php echo $_lang['information'];?></h2>
<script type="text/javascript">tp.addTabPage( document.getElementById( "tabDocBlock" ) );</script>
<div class="section">
        <?php echo $docBlockList; ?>
</div>
</div>
  
<input type="submit" name="save" style="display:none;">
<?php
// invoke OnModFormRender event
$evtOut = $modx->invokeEvent('OnModFormRender', array('id' => $id));
if(is_array($evtOut)) echo implode('',$evtOut);
?>
</form>
<script type="text/javascript">setTimeout('showParameters();',10);</script>
