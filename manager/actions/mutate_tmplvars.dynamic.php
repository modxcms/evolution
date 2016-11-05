<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('edit_template') && $_REQUEST['a']=='301') {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}
if(!$modx->hasPermission('new_template') && $_REQUEST['a']=='300') {
	$modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$origin = isset($_REQUEST['or']) ? intval($_REQUEST['or']) : 76;
$originId = isset($_REQUEST['oid']) ? intval($_REQUEST['oid']) : NULL;

$tbl_site_tmplvars          = $modx->getFullTableName('site_tmplvars');
$tbl_site_templates         = $modx->getFullTableName('site_templates');
$tbl_site_tmplvar_templates = $modx->getFullTableName('site_tmplvar_templates');
$tbl_documentgroup_names    = $modx->getFullTableName('documentgroup_names');

// check to see the snippet editor isn't locked
if ($lockedEl = $modx->elementIsLocked(2, $id)) {
        $modx->webAlertAndQuit(sprintf($_lang['lock_msg'],$lockedEl['username'],$_lang['tmplvar']));
}
// end check for lock

// Lock snippet for other users to edit
$modx->lockElement(2, $id);

global $content;
$content = array();
if(isset($_GET['id']))
{
	$rs = $modx->db->select('*',$tbl_site_tmplvars,"id='{$id}'");
	$content = $modx->db->getRow($rs);
	if(!$content) {
		header("Location: ".MODX_SITE_URL."index.php?id={$site_start}");
	}
	
	$_SESSION['itemname'] = $content['caption'];
	if($content['locked']==1 && $modx->hasPermission('save_role')!=1)
	{
		$modx->webAlertAndQuit($_lang["error_no_privileges"]);
	}
}
else
{
    $_SESSION['itemname']=$_lang["new_tmplvars"];
}

if ($modx->manager->hasFormValues()) {
    $modx->manager->loadFormValues();
}

$content = array_merge($content, $_POST);

// Add lock-element JS-Script
$lockElementId = $id;
$lockElementType = 2;
require_once(MODX_MANAGER_PATH.'includes/active_user_locks.inc.php');

// get available RichText Editors
$RTEditors = '';
$evtOut = $modx->invokeEvent('OnRichTextEditorRegister',array('forfrontend' => 1));
if(is_array($evtOut)) $RTEditors = implode(',',$evtOut);

?>
<script language="JavaScript">

function check_toggle() {
    var el =  document.getElementsByName("template[]");
    var count = el.length;             
    for(i=0;i<count;i++) el[i].checked = !el[i].checked; 
};

function check_none() {
    var el =  document.getElementsByName("template[]");
    var count = el.length;             
    for(i=0;i<count;i++) el[i].checked = false; 
};

function check_all() {
    var el =  document.getElementsByName("template[]");
    var count = el.length;             
    for(i=0;i<count;i++) el[i].checked = true; 
};

function duplicaterecord(){
    if(confirm("<?php echo $_lang['confirm_duplicate_record'] ?>")==true) {
        documentDirty=false;
        document.location.href="index.php?id=<?php echo $_REQUEST['id']; ?>&a=304";
    }
}

function deletedocument() {
    if(confirm("<?php echo $_lang['confirm_delete_tmplvars']; ?>")==true) {
        documentDirty=false;
        document.location.href="index.php?id=" + document.mutate.id.value + "&a=303";
    }
}

// Widget Parameters
var widgetParams = {};          // name = description;datatype;default or list values - datatype: int, string, list : separated by comma (,)
    widgetParams['date']        = '&format=Date Format;string;%A %d, %B %Y &default=If no value, use current date;list;Yes,No;No';
    widgetParams['string']      = '&format=String Format;list;Upper Case,Lower Case,Sentence Case,Capitalize';
    widgetParams['delim']       = '&format=Delimiter;string;,';
    widgetParams['hyperlink']   = '&text=Display Text;string; &title=Title;string; &class=Class;string &style=Style;string &target=Target;string &attrib=Attributes;string';
    widgetParams['htmltag']     = '&tagname=Tag Name;string;div &tagid=Tag ID;string &class=Class;string &style=Style;string &attrib=Attributes;string';
    widgetParams['viewport']    = '&vpid=ID/Name;string &width=Width;string;100 &height=Height;string;100 &borsize=Border Size;int;1 &sbar=Scrollbars;list;,Auto,Yes,No &asize=Auto Size;list;,Yes,No &aheight=Auto Height;list;,Yes,No &awidth=Auto Width;list;,Yes,No &stretch=Stretch To Fit;list;,Yes,No &class=Class;string &style=Style;string &attrib=Attributes;string';
    widgetParams['datagrid']    = '&cols=Column Names;string &flds=Field Names;string &cwidth=Column Widths;string &calign=Column Alignments;string &ccolor=Column Colors;string &ctype=Column Types;string &cpad=Cell Padding;int;1 &cspace=Cell Spacing;int;1 &rowid=Row ID Field;string &rgf=Row Group Field;string &rgstyle = Row Group Style;string &rgclass = Row Group Class;string &rowsel=Row Select;string &rhigh=Row Hightlight;string; &psize=Page Size;int;100 &ploc=Pager Location;list;top-right,top-left,bottom-left,bottom-right,both-right,both-left; &pclass=Pager Class;string &pstyle=Pager Style;string &head=Header Text;string &foot=Footer Text;string &tblc=Grid Class;string &tbls=Grid Style;string &itmc=Item Class;string &itms=Item Style;string &aitmc=Alt Item Class;string &aitms=Alt Item Style;string &chdrc=Column Header Class;string &chdrs=Column Header Style;string;&egmsg=Empty message;string;No records found;';
    widgetParams['richtext']    = '&w=Width;string;100% &h=Height;string;300px &edt=Editor;list;<?php echo $RTEditors; ?>';
    widgetParams['image']       = '&alttext=Alternate Text;string &hspace=H Space;int &vspace=V Space;int &borsize=Border Size;int &align=Align;list;none,baseline,top,middle,bottom,texttop,absmiddle,absbottom,left,right &name=Name;string &class=Class;string &id=ID;string &style=Style;string &attrib=Attributes;string';
    widgetParams['custom_widget']       = '&output=Output;textarea;[+value+]';

// Current Params
var currentParams = {};
var lastdf, lastmod = {};

function showParameters(ctrl) {
    var c,p,df,cp;
    var ar,desc,value,key,dt;

    currentParams = {}; // reset;

    if (ctrl) {
    	f = ctrl.form;
    } else {
        f= document.forms['mutate'];
    	if(!f) return;
    	ctrl = f.display;
    }
    cp = f.params.value.split("&"); // load current setting once

    // get display format
    df = lastdf = ctrl.options[ctrl.selectedIndex].value;

    // load last modified param values
    if (lastmod[df]) cp = lastmod[df].split("&");
    for(p = 0; p < cp.length; p++) {
        cp[p]=(cp[p]+'').replace(/^\s|\s$/,""); // trim
        ar = cp[p].split("=");
        currentParams[ar[0]]=ar[1];
    }

    // setup parameters
    tr = (document.getElementById) ? document.getElementById('displayparamrow'):document.all['displayparamrow'];
    dp = (widgetParams[df]) ? widgetParams[df].split("&"):"";
    if(!dp) tr.style.display='none';
    else {
        t='<table width="300" class="displayparams"><thead><tr><td width="50%"><?php echo $_lang['parameter']; ?></td><td width="50%"><?php echo $_lang['value']; ?></td></tr></thead>';
        for(p = 0; p < dp.length; p++) {
            dp[p]=(dp[p]+'').replace(/^\s|\s$/,""); // trim
            ar = dp[p].split("=");
            key = ar[0];     // param
            ar = (ar[1]+'').split(";");
            desc = ar[0];   // description
            dt = ar[1];     // data type
            value = decode((currentParams[key]) ? currentParams[key]:(dt=='list') ? ar[3] : (ar[2])? ar[2]:'');
            if (value!=currentParams[key]) currentParams[key] = value;
            value = (value+'').replace(/^\s|\s$/,""); // trim
	    value = value.replace(/\"/g,"&quot;"); // replace double quotes with &quot;
            if (dt) {
                switch(dt) {
                    case 'int':
                    case 'float':
                        c = '<input type="text" name="prop_'+key+'" value="'+value+'" size="30" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" />';
                        break;
                    case 'list':
                        c = '<select name="prop_'+key+'" height="1" style="width:168px" onchange="setParameter(\''+key+'\',\''+dt+'\',this)">';
                        ls = (ar[2]+'').split(",");
                        if(!currentParams[key]||currentParams[key]=='undefined') {
                            currentParams[key] = ls[0]; // use first list item as default
                        }
                        for(i=0;i<ls.length;i++){
                            c += '<option value="'+ls[i]+'"'+((ls[i]==value)? ' selected="selected"':'')+'>'+ls[i]+'</option>';
                        }
                        c += '</select>';
                        break;
                    case 'textarea':
                        c = '<textarea class="inputBox phptextarea" name="prop_'+key+'" cols="25" style="width:220px;" onchange="setParameter(\''+key+'\',\''+dt+'\',this)" >'+value+'</textarea>';
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
        case 'float':
            ctrl.value = parseFloat(ctrl.value);
            if(isNaN(ctrl.value)) ctrl.value = 0;
            v = ctrl.value;
            break;
        case 'list':
            v = ctrl.options[ctrl.selectedIndex].value;
            break;
        case 'textarea':
            v = ctrl.value+'';
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
    lastmod[lastdf]="";
    showParameters();
}
// implode parameters
function implodeParameters(){
    var v, p, s='';
    for(p in currentParams){
        v = currentParams[p];
        if(v) s += '&'+p+'='+ encode(v);
    }
    document.forms['mutate'].params.value = s;
    if (lastdf) lastmod[lastdf] = s;
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

<form name="mutate" method="post" action="index.php" enctype="multipart/form-data">
<?php
    // invoke OnTVFormPrerender event
    $evtOut = $modx->invokeEvent('OnTVFormPrerender',array('id' => $id));
    if(is_array($evtOut)) echo implode("",$evtOut);
?>
<input type="hidden" name="id" value="<?php echo $content['id'];?>">
<input type="hidden" name="a" value="302">
<input type="hidden" name="or" value="<?php echo $origin;?>">
<input type="hidden" name="oid" value="<?php echo $originId;?>">
<input type="hidden" name="mode" value="<?php echo $_GET['a'];?>">
<input type="hidden" name="params" value="<?php echo $modx->htmlspecialchars($content['display_params']);?>">

    <h1 class="pagetitle">
      <span class="pagetitle-icon">
        <i class="fa fa-list-alt"></i>
      </span>
      <span class="pagetitle-text">
        <?php echo $_lang['tmplvars_title']; ?>
      </span>
    </h1>

    <div id="actions">
          <ul class="actionButtons">
              <li id="Button1" class="transition">
                <a href="#" onclick="documentDirty=false; document.mutate.save.click();saveWait('mutate');">
                  <img src="<?php echo $_style["icons_save"]?>" /> <?php echo $_lang['save']?>
                </a>
                <span class="plus"> + </span>
                <select id="stay" name="stay">
                  <option id="stay1" value="1" <?php echo $_REQUEST['stay']=='1' ? ' selected="selected"' : ''?> ><?php echo $_lang['stay_new']?></option>
                  <option id="stay2" value="2" <?php echo $_REQUEST['stay']=='2' ? ' selected="selected"' : ''?> ><?php echo $_lang['stay']?></option>
                  <option id="stay3" value=""  <?php echo $_REQUEST['stay']=='' ? ' selected="selected"' : ''?>  ><?php echo $_lang['close']?></option>
                </select>        
              </li>
          <?php if ($_GET['a'] == '300') { ?>
              <li id="Button6" class="disabled"><a href="#" onclick="duplicaterecord();"><img src="<?php echo $_style["icons_resource_duplicate"] ?>" /> <?php echo $_lang["duplicate"]; ?></a></li>
              <li id="Button3" class="disabled"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"]?>" /> <?php echo $_lang['delete']?></a></li>
          <?php } else { ?>
              <li id="Button6"><a href="#" onclick="duplicaterecord();"><img src="<?php echo $_style["icons_resource_duplicate"] ?>" /> <?php echo $_lang["duplicate"]; ?></a></li>
              <li id="Button3"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"]?>" /> <?php echo $_lang['delete']?></a></li>
          <?php } ?>
              <li id="Button5" class="transition"><a href="#" onclick="documentDirty=false;document.location.href='index.php?a=<?php echo $origin ?><?php echo ($originId!=NULL?'&id='.$originId:''); ?>';"><img src="<?php echo $_style["icons_cancel"] ?>" /> <?php echo $_lang['cancel']?></a></li>
          </ul>
    </div>

<script type="text/javascript" src="media/script/tabpane.js"></script>
<div class="sectionBody">
<div class="tab-pane" id="tmplvarsPane">
	<script type="text/javascript">
		tpTmplvars = new WebFXTabPane( document.getElementById( "tmplvarsPane" ), false );
	</script>
	<div class="tab-page" id="tabGeneral">
	<h2 class="tab"><?php echo $_lang['settings_general'];?></h2>
	<script type="text/javascript">tpTmplvars.addTabPage( document.getElementById( "tabGeneral" ) );</script>

    <p class="element-edit-message">
      <?php echo $_lang['tmplvars_msg']; ?>
    </p>
      
<table>
  <tr>
    <th><?php echo $_lang['tmplvars_name']; ?>:</th>
    <td>[*&nbsp;<input name="name" type="text" maxlength="50" value="<?php echo $modx->htmlspecialchars($content['name']);?>" class="inputBox" style="width:250px;" onchange="documentDirty=true;">*]&nbsp; <span class="warning" id='savingMessage'>&nbsp;</span></td>
  </tr>
  <tr>
    <th><?php echo $_lang['tmplvars_caption']; ?>:</th>
    <td><input name="caption" type="text" maxlength="80" value="<?php echo $modx->htmlspecialchars($content['caption']);?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;">
    <script>document.getElementsByName("caption")[0].focus();</script></td>
  </tr>

  <tr>
    <th><?php echo $_lang['tmplvars_description']; ?>:</th>
    <td><input name="description" type="text" maxlength="255" value="<?php echo $modx->htmlspecialchars($content['description']);?>" class="inputBox" style="width:300px;" onChange="documentDirty=true;"></td>
  </tr>
  <tr>
    <th><?php echo $_lang['existing_category']; ?>:</th>
    <td><select name="categoryid" style="width:300px;" onChange="documentDirty=true;">
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
    <th><?php echo $_lang['new_category']; ?>:</th>
    <td><input name="newcategory" type="text" maxlength="45" value="" class="inputBox" style="width:300px;" onchange="documentDirty=true;"></td>
  </tr>
<?php if($modx->hasPermission('save_role')):?>
  <tr>
    <td colspan="2"><label><input name="locked" value="on" type="checkbox" <?php echo $content['locked']==1 ? "checked='checked'" : "" ;?> class="inputBox" /> <?php echo $_lang['lock_tmplvars']; ?></label> <span class="comment"><?php echo $_lang['lock_tmplvars_msg']; ?></span></td>
  </tr>
<?php endif;?>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <th><?php echo $_lang['tmplvars_type']; ?>:&nbsp;&nbsp;</th>
    <td><select name="type" size="1" class="inputBox" style="width:300px;" onchange="documentDirty=true;">
	            <optgroup label="Standard Type">
                    <option value="text" <?php      echo ($content['type']==''||$content['type']=='text')? "selected='selected'":""; ?>>Text</option>
                    <option value="rawtext" <?php       echo ($content['type']=='rawtext')? "selected='selected'":""; ?>>Raw Text (deprecated)</option>
                    <option value="textarea" <?php  echo ($content['type']=='textarea')? "selected='selected'":""; ?>>Textarea</option>
                    <option value="rawtextarea" <?php   echo ($content['type']=='rawtextarea')? "selected='selected'":""; ?>>Raw Textarea (deprecated)</option>
                    <option value="textareamini" <?php  echo ($content['type']=='textareamini')? "selected='selected'":""; ?>>Textarea (Mini)</option>
                    <option value="richtext" <?php  echo ($content['type']=='richtext'||$content['type']=='htmlarea')? "selected='selected'":""; ?>>RichText</option>
                    <option value="dropdown" <?php  echo ($content['type']=='dropdown')? "selected='selected'":""; ?>>DropDown List Menu</option>
                    <option value="listbox" <?php   echo ($content['type']=='listbox')? "selected='selected'":""; ?>>Listbox (Single-Select)</option>
                    <option value="listbox-multiple" <?php echo ($content['type']=='listbox-multiple')? "selected='selected'":""; ?>>Listbox (Multi-Select)</option>
                    <option value="option" <?php    echo ($content['type']=='option')? "selected='selected'":""; ?>>Radio Options</option>
                    <option value="checkbox" <?php  echo ($content['type']=='checkbox')? "selected='selected'":""; ?>>Check Box</option>
                    <option value="image" <?php     echo ($content['type']=='image')? "selected='selected'":""; ?>>Image</option>
                    <option value="file" <?php      echo ($content['type']=='file')? "selected='selected'":""; ?>>File</option>
                    <option value="url" <?php       echo ($content['type']=='url')? "selected='selected'":""; ?>>URL</option>
                    <option value="email" <?php     echo ($content['type']=='email')? "selected='selected'":""; ?>>Email</option>
                    <option value="number" <?php    echo ($content['type']=='number')? "selected='selected'":""; ?>>Number</option>
                    <option value="date" <?php      echo ($content['type']=='date')? "selected='selected'":""; ?>>Date</option>
                </optgroup>
                <optgroup label="Custom Type">
                    <option value="custom_tv" <?php  echo ($content['type']=='custom_tv')? "selected='selected'":""; ?>>Custom Input</option>
                    <?php 
                        $custom_tvs = scandir(MODX_BASE_PATH.'assets/tvs'); 
                        foreach($custom_tvs as $ctv) {
                            if(strpos($ctv, '.')!==0 && $ctv !='index.html'){
                                $selected = ($content['type']=='custom_tv:'.$ctv )? "selected='selected'":"";
                                echo '<option value="custom_tv:'.$ctv.'"  '.$selected.'>'.$ctv.'</option>';
                            }
                        }    
                    ?>
               </optgroup>
	        </select>
    </td>
  </tr>
  <tr>
	<th><?php echo $_lang['tmplvars_elements']; ?>:  </th>
	<td nowrap="nowrap"><textarea name="elements" maxlength="65535" class="inputBox textarea" onchange="documentDirty=true;"><?php echo $modx->htmlspecialchars($content['elements']);?></textarea><img src="<?php echo $_style["icons_tooltip_over"]?>" onmouseover="this.src='<?php echo $_style["icons_tooltip"]?>';" onmouseout="this.src='<?php echo $_style["icons_tooltip_over"]?>';" alt="<?php echo $_lang['tmplvars_binding_msg']; ?>" onclick="alert(this.alt);" style="cursor:help" /></td>
  </tr>
  <tr>
    <th><?php echo $_lang['tmplvars_default']; ?>:&nbsp;&nbsp;</th>
    <td nowrap="nowrap"><textarea name="default_text" type="text" class="inputBox" rows="5" style="width:300px;" onchange="documentDirty=true;"><?php echo $modx->htmlspecialchars($content['default_text']);?></textarea><img src="<?php echo $_style["icons_tooltip_over"]?>" onmouseover="this.src='<?php echo $_style["icons_tooltip"]?>';" onmouseout="this.src='<?php echo $_style["icons_tooltip_over"]?>';" alt="<?php echo $_lang['tmplvars_binding_msg']; ?>" onclick="alert(this.alt);" style="cursor:help" /></td>
  </tr>
  <tr>
    <th><?php echo $_lang['tmplvars_widget']; ?>:&nbsp;&nbsp;</th>
    <td>
        <select name="display" size="1" class="inputBox" style="width:300px;" onChange='documentDirty=true;showParameters(this);'>
	            <option value="" <?php echo ($content['display']=='')? "selected='selected'":""; ?>>&nbsp;</option>
			<optgroup label="Widgets">
	            <option value="datagrid" <?php echo ($content['display']=='datagrid')? "selected='selected'":""; ?>>Data Grid</option>
	            <option value="richtext" <?php echo ($content['display']=='richtext')? "selected='selected'":""; ?>>RichText</option>
	            <option value="viewport" <?php echo ($content['display']=='viewport')? "selected='selected'":""; ?>>View Port</option>
                <option value="custom_widget" <?php echo ($content['display']=='custom_widget')? "selected='selected'":""; ?>>Custom Widget</option>
			</optgroup>
			<optgroup label="Formats">
	            <option value="htmlentities" <?php echo ($content['display']=='htmlentities')? "selected='selected'":""; ?>>HTML Entities</option>
	            <option value="date" <?php echo ($content['display']=='date')? "selected='selected'":""; ?>>Date Formatter</option>
	            <option value="unixtime" <?php echo ($content['display']=='unixtime')? "selected='selected'":""; ?>>Unixtime</option>
	            <option value="delim" <?php echo ($content['display']=='delim')? "selected='selected'":""; ?>>Delimited List</option>
	            <option value="htmltag" <?php echo ($content['display']=='htmltag')? "selected='selected'":""; ?>>HTML Generic Tag</option>
	            <option value="hyperlink" <?php echo ($content['display']=='hyperlink')? "selected='selected'":""; ?>>Hyperlink</option>
	            <option value="image" <?php echo ($content['display']=='image')? "selected='selected'":""; ?>>Image</option>
	            <option value="string" <?php echo ($content['display']=='string')? "selected='selected'":""; ?>>String Formatter</option>
			</optgroup>
	        </select>
    </td>
  </tr>
  <tr id="displayparamrow">
    <th><?php echo $_lang['tmplvars_widget_prop']; ?><div style="padding-top:8px;"><a href="javascript://" onclick="resetParameters(); return false"><img src="<?php echo $_style['icons_refresh']; ?>" alt="<?php echo $_lang['tmplvars_reset_params']; ?>"></a></div></th>
    <td id="displayparams">&nbsp;</td>
  </tr>
  <tr>
    <th><?php echo $_lang['tmplvars_rank']; ?>:&nbsp;&nbsp;</th>
    <td><input name="rank" type="text" maxlength="4" value="<?php echo (isset($content['rank'])) ? $content['rank'] : 0;?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;"></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
</table>
<div class="sectionHeader"><?php echo $_lang['tmplvar_tmpl_access']; ?></div>
<div class="sectionBody">
	<p><?php echo $_lang['tmplvar_tmpl_access_msg']; ?></p>
    <ul class="actionButtons">
	    <li><a href="#" onClick="check_all();return false;"><?php echo $_lang['check_all']; ?></a></li>
	    <li><a href="#" onClick="check_none();return false;"><?php echo $_lang['check_none']; ?></a></li>
	    <li><a href="#" onClick="check_toggle(); return false;"><?php echo $_lang['check_toggle']; ?></a></li>
    </ul>
	<style type="text/css">
		label {display:block;}
	</style>
<table>
<?php
        $rs = $modx->db->select(
            sprintf("tpl.id AS id, templatename, tpl.description AS tpldescription, tpl.locked AS tpllocked, tpl.selectable AS selectable, tmplvarid, if(isnull(cat.category),'%s',cat.category) AS category, cat.id AS catid", $_lang['no_category']),
            sprintf("%s as tpl
                    LEFT JOIN %s as stt ON stt.templateid=tpl.id AND stt.tmplvarid='%s'
                    LEFT JOIN %s as cat ON tpl.category=cat.id",
                    $modx->getFullTableName('site_templates'), $modx->getFullTableName('site_tmplvar_templates'), $id, $modx->getFullTableName('categories')),
            '',
            "category, templatename"
        );
?>
  <tr>
    <td>
<?php

$tplList = '<ul>';
$preCat = '';
$insideUl = 0;
while ($row = $modx->db->getRow($rs)) {
    $row['category'] = stripslashes($row['category']); //pixelchutes
    if ($preCat !== $row['category']) {
        $tplList .= $insideUl? '</ul>': '';
        $tplList .= '<li><strong>'.$row['category']. ($row['catid']!='' ? ' <small>('.$row['catid'].')</small>' : '') .'</strong><ul>';
        $insideUl = 1;
    }

    if($_REQUEST['a']=='300' && $modx->config['default_template']==$row['id'])
    {
        $checked = true;
    }
    elseif(isset($_GET['tpl']) && $_GET['tpl'] == $row['id'])
    {
        $checked = true;
    }
    elseif($id == 0 && is_array($_POST['template']))
    {
        $checked = in_array($row['id'], $_POST['template']);
    }
    else
    {
        $checked = $row['tmplvarid'];
    }
    $selectable = !$row['selectable'] ? ' class="disabled"':'';
    $checked = $checked ? ' checked="checked"':'';
    $tplId = '&nbsp;<small>(' . $row['id'] . ')</small>';
    $desc = !empty($row['tpldescription']) ? ' - '.$row['tpldescription'] : '';

    $tplInfo = array();
    if($row['tpllocked']) $tplInfo[] = $_lang['locked'];
    if($row['id'] == $modx->config['default_template']) $tplInfo[] = $_lang['defaulttemplate_title'];
    $tplInfo = !empty($tplInfo) ? ' <em>('.join(', ', $tplInfo).')</em>' : '';

    $tplList .= sprintf('<li><label%s><input name="template[]" value="%s" type="checkbox" %s onchange="documentDirty=true;">%s%s%s%s</label></li>',
                        $selectable, $row['id'], $checked, $row['templatename'], $tplId, $desc, $tplInfo );
    $tplList .= '</li>';

    $preCat = $row['category'];
}
$tplList .= $insideUl? '</ul>': '';
$tplList .= '</ul>';
echo $tplList;

?>
    </td>
  </tr>
</table>
	</div>
<!-- Access Permissions -->
	<?php
	if($use_udperms==1) {
	    // fetch permissions for the variable
	    $rs = $modx->db->select('documentgroup', $modx->getFullTableName('site_tmplvar_access'), "tmplvarid='{$id}'");
	    $groupsarray = $modx->db->getColumn('documentgroup', $rs);

?>
<?php if($modx->hasPermission('access_permissions')) { ?>
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
<p><?php echo $_lang['tmplvar_access_msg']; ?></p>
<?php
		}
		$chk ='';
		$rs = $modx->db->select('name, id', $tbl_documentgroup_names);
		    if(empty($groupsarray) && is_array($_POST['docgroups']) && empty($_POST['id'])) {
		    	$groupsarray = $_POST['docgroups'];
		    }
		    while ($row=$modx->db->getRow($rs)) {
		        $checked = in_array($row['id'], $groupsarray);
		        if($modx->hasPermission('access_permissions')) {
		            if($checked) $notPublic = true;
		            $chks.= "<label><input type='checkbox' name='docgroups[]' value='".$row['id']."' ".($checked ? "checked='checked'" : '')." onclick=\"makePublic(false)\" />".$row['name']."</label>";
		        }
		        else {
		            if($checked) echo "<input type='hidden' name='docgroups[]'  value='".$row['id']."' />";
		        }
		    }
		    if($modx->hasPermission('access_permissions')) {
		        $chks = "<label><input type='checkbox' name='chkalldocs' ".(!$notPublic ? "checked='checked'" : '')." onclick=\"makePublic(true)\" /><span class='warning'>".$_lang['all_doc_groups']."</span></label>".$chks;
		    }
		    echo $chks;
		?>
	</div>
<?php }?>

	</div>


	<input type="submit" name="save" style="display:none">

<?php
    // invoke OnTVFormRender event
    $evtOut = $modx->invokeEvent('OnTVFormRender',array('id' => $id));
    if(is_array($evtOut)) echo implode('',$evtOut);
?>
</div>
</div>
</form>
<script type="text/javascript">setTimeout('showParameters()',10);</script>
