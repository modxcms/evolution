<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

switch($modx->manager->action) {
  case 16:
    if(!$modx->hasPermission('edit_template')) {
      $modx->webAlertAndQuit($_lang["error_no_privileges"]);
    }
    break;
  case 19:
    if(!$modx->hasPermission('new_template')) {
      $modx->webAlertAndQuit($_lang["error_no_privileges"]);
    }
    break;
  default:
    $modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

$tbl_site_templates = $modx->getFullTableName('site_templates');

// check to see the snippet editor isn't locked
if ($lockedEl = $modx->elementIsLocked(1, $id)) {
        $modx->webAlertAndQuit(sprintf($_lang['lock_msg'],$lockedEl['username'],$_lang['template']));
}
// end check for lock

// Lock snippet for other users to edit
$modx->lockElement(1, $id);

$content = array();
if(!empty($id)) {
    $rs = $modx->db->select('*',$tbl_site_templates,"id='{$id}'");
    $content = $modx->db->getRow($rs);
    if(!$content) {
        $modx->webAlertAndQuit("No database record has been found for this template.");
    }
    
    $_SESSION['itemname']=$content['templatename'];
    if($content['locked']==1 && $_SESSION['mgrRole']!=1) {
        $modx->webAlertAndQuit($_lang["error_no_privileges"]);
    }
} else {
    $_SESSION['itemname']=$_lang["new_template"];
}

if ($modx->manager->hasFormValues()) {
    $modx->manager->loadFormValues();
}

$content = array_merge($content, $_POST);
$selectable = $modx->manager->action == 19 ? 1 : $content['selectable'];

// Add lock-element JS-Script
$lockElementId = $id;
$lockElementType = 1;
require_once(MODX_MANAGER_PATH.'includes/active_user_locks.inc.php');
?>
<script type="text/javascript">
function duplicaterecord(){
    if(confirm("<?php echo $_lang['confirm_duplicate_record'] ?>")==true) {
        documentDirty=false;
        document.location.href="index.php?id=<?php echo $_REQUEST['id']; ?>&a=96";
    }
}

function deletedocument() {
    if(confirm("<?php echo $_lang['confirm_delete_template']; ?>")==true) {
        documentDirty=false;
        document.location.href="index.php?id=" + document.mutate.id.value + "&a=21";
    }
}

</script>

<form name="mutate" method="post" action="index.php">
<?php
    // invoke OnTempFormPrerender event
    $evtOut = $modx->invokeEvent("OnTempFormPrerender",array("id" => $id));
    if(is_array($evtOut)) echo implode("",$evtOut);
?>
<input type="hidden" name="a" value="20">
<input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>">
<input type="hidden" name="mode" value="<?php echo $modx->manager->action;?>">

    <h1 class="pagetitle">
      <span class="pagetitle-icon">
        <i class="fa fa-newspaper-o"></i>
      </span>
      <span class="pagetitle-text">
        <?php echo $_lang['template_title']; ?>
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
          <?php if ($modx->manager->action == '19') { ?>
              <li id="Button6" class="disabled"><a href="#" onclick="duplicaterecord();"><img src="<?php echo $_style["icons_resource_duplicate"] ?>" /> <?php echo $_lang["duplicate"]; ?></a></li>
              <li id="Button3" class="disabled"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"]?>" /> <?php echo $_lang['delete']?></a></li>
          <?php } else { ?>
              <li id="Button6"><a href="#" onclick="duplicaterecord();"><img src="<?php echo $_style["icons_resource_duplicate"] ?>" /> <?php echo $_lang["duplicate"]; ?></a></li>
              <li id="Button3"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"]?>" /> <?php echo $_lang['delete']?></a></li>
          <?php } ?>
              <li id="Button5" class="transition"><a href="#" onclick="documentDirty=false;document.location.href='index.php?a=76';"><img src="<?php echo $_style["icons_cancel"]?>" /> <?php echo $_lang['cancel']?></a></li>
          </ul>
    </div>

<script type="text/javascript" src="media/script/tabpane.js"></script>
<div class="sectionBody">
<div class="tab-pane" id="templatesPane">
    <script type="text/javascript">
        tp = new WebFXTabPane( document.getElementById( "templatesPane" ), <?php echo $modx->config['remember_last_tab'] == 1 ? 'true' : 'false'; ?> );
    </script>
    <div class="tab-page" id="tabTemplate">
        <h2 class="tab"><?php echo $_lang["template_edit_tab"] ?></h2>
        <script type="text/javascript">tp.addTabPage( document.getElementById( "tabTemplate" ) );</script>

        <p class="element-edit-message">
          <?php echo $_lang['template_msg']; ?>
        </p>
      
    <table>
      <tr>
        <th><?php echo $_lang['template_name']; ?></th>
        <td><input name="templatename" type="text" maxlength="100" value="<?php echo $modx->htmlspecialchars($content['templatename']);?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;"><span class="warning" id='savingMessage'></span>
            <?php if($id == $modx->config['default_template']) echo ' <b>'.$_lang['defaulttemplate_title'].'</b>'; ?>
            <script>document.getElementsByName("templatename")[0].focus();</script></td>
      </tr>
    <tr>
    <th><?php echo $_lang['template_desc']; ?></th>
    <td><input name="description" type="text" maxlength="255" value="<?php echo $modx->htmlspecialchars($content['description']);?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;"></td>
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
    <td><input name="newcategory" type="text" maxlength="45" value="<?php echo isset($content['newcategory']) ? $content['newcategory'] : '' ?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;"></td>
    </tr>
<?php if($modx->hasPermission('save_role')):?>
    <tr>
    <th colspan="2"><label style="display:block;"><input name="locked" type="checkbox" <?php echo $content['locked']==1 ? "checked='checked'" : "" ;?> class="inputBox"> <?php echo $_lang['lock_template']; ?></label> <span class="comment"><?php echo $_lang['lock_template_msg']; ?></span></th>
    </tr>
    <tr>
    <th colspan="2"><label style="display:block;"><input name="selectable" type="checkbox" <?php echo $selectable==1 ? "checked='checked'" : "" ;?> class="inputBox"> <?php echo $_lang['template_selectable']; ?></label></th>
    </tr>
<?php endif;?>
    </table>
    <!-- HTML text editor start -->
    <div class="section">
        <div class="sectionHeader">
            <?php echo $_lang['template_code']; ?>
        </div>
        <div class="sectionBody">
        <textarea dir="ltr" name="post" class="phptextarea" style="width:100%; height: 370px;" onChange="documentDirty=true;"><?php echo isset($content['post']) ? $modx->htmlspecialchars($content['post']) : $modx->htmlspecialchars($content['content']); ?></textarea>
        </div>
    </div>
    <!-- HTML text editor end -->
    <input type="submit" name="save" style="display:none">

<?php
$selectedTvs = array();
if( !isset($_POST['assignedTv']) ) {
    $rs = $modx->db->select(
        sprintf("tv.name AS tvname, tv.id AS tvid, tr.templateid AS templateid, tv.description AS tvdescription, tv.caption AS tvcaption, tv.locked AS tvlocked, if(isnull(cat.category),'%s',cat.category) AS category", $_lang['no_category']),
        sprintf("%s tv
                LEFT JOIN %s tr ON tv.id=tr.tmplvarid
                LEFT JOIN %s cat ON tv.category=cat.id",
            $modx->getFullTableName('site_tmplvars'), $modx->getFullTableName('site_tmplvar_templates'), $modx->getFullTableName('categories')),
        "templateid='{$id}'",
        "tr.rank DESC, tv.rank DESC, tvcaption DESC, tvid DESC"     // workaround for correct sort of none-existing ranks
    );
    while ($row = $modx->db->getRow($rs)) {
        $selectedTvs[$row['tvid']] = $row;
    }
    $selectedTvs = array_reverse($selectedTvs, true);       // reverse ORDERBY DESC
}

$unselectedTvs = array();
$rs = $modx->db->select(
    sprintf("tv.name AS tvname, tv.id AS tvid, tr.templateid AS templateid, tv.description AS tvdescription, tv.caption AS tvcaption, tv.locked AS tvlocked, if(isnull(cat.category),'%s',cat.category) AS category, cat.id as catid", $_lang['no_category']),
    sprintf("%s tv
	    LEFT JOIN %s tr ON tv.id=tr.tmplvarid
	    LEFT JOIN %s cat ON tv.category=cat.id",
        $modx->getFullTableName('site_tmplvars'), $modx->getFullTableName('site_tmplvar_templates'),$modx->getFullTableName('categories')),
    "",
    "category, tvcaption"
);
while($row = $modx->db->getRow($rs)) {
    $unselectedTvs[$row['tvid']] = $row;
}

// Catch checkboxes if form not validated
if( isset($_POST['assignedTv']) ) {
    $selectedTvs = array();
    foreach($_POST['assignedTv'] as $tvid) {
        if(isset($unselectedTvs[$tvid]))
            $selectedTvs[$tvid] = $unselectedTvs[$tvid];
    };
}

$total = count($selectedTvs);
?>
    </div>
    <div class="tab-page" id="tabAssignedTVs">
        <h2 class="tab"><?php echo $_lang["template_assignedtv_tab"] ?></h2>
        <script type="text/javascript">tp.addTabPage( document.getElementById( "tabAssignedTVs" ) );</script>
<?php
if ($total > 0) echo '<p>' . $_lang['template_tv_msg'] . '</p>';
if($modx->hasPermission('save_template') && $total > 1 && $id) {
    echo sprintf('<ul class="actionButtons"><li><a href="index.php?a=117&amp;id=%s">%s</a></li></ul>',$id,$_lang['template_tv_edit']);
}

// Selected TVs
$tvList = '<br/>';
if($total>0) {
    $tvList .= '<ul>';
    foreach($selectedTvs as $row) {
        $desc = !empty($row['tvdescription']) ? '&nbsp;&nbsp;<small>('.$row['tvdescription'].')</small>' : '';
        $locked = $row['tvlocked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ;
        $tvList .= sprintf('<li><label><input name="assignedTv[]" value="%s" type="checkbox" class="inputBox" checked="checked" onchange="documentDirty=true;">%s <small>(%s)</small> - %s%s</label>%s <a href="index.php?id=%s&a=301&or=%s&oid=%s">%s</a></li>',
                            $row['tvid'], $row['tvname'], $row['tvid'], $row['tvcaption'], $desc, $locked, $row['tvid'], $modx->manager->action, $id, $_lang['edit']);
    }
    $tvList .= '</ul>';

} else {
	echo $_lang['template_no_tv'];
}
echo $tvList;

// Unselected TVs
$tvList = '<br/><hr/><br/>'.$_lang['template_notassigned_tv'].'<br/><br/><ul>';
$preCat = '';
$insideUl = 0;
while ($row = array_shift($unselectedTvs)) {
    if(isset($selectedTvs[$row['tvid']])) continue; // Skip selected
    $row['category'] = stripslashes($row['category']); //pixelchutes
    if ($preCat !== $row['category']) {
        $tvList .= $insideUl? '</ul>': '';
        $tvList .= '<li><strong>'.$row['category']. ($row['catid']!='' ? ' <small>('.$row['catid'].')</small>' : '') .'</strong><ul>';
        $insideUl = 1;
    }

    $desc = !empty($row['tvdescription']) ? '&nbsp;&nbsp;<small>('.$row['tvdescription'].')</small>' : '';
    $locked = $row['tvlocked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ;
    $tvList .= sprintf('<li><label><input name="assignedTv[]" value="%s" type="checkbox" class="inputBox" onchange="documentDirty=true;">%s <small>(%s)</small> - %s%s</label>%s <a href="index.php?id=%s&a=301&or=%s">%s</a></li>',
                        $row['tvid'], $row['tvname'], $row['tvid'], $row['tvcaption'], $desc, $locked, $row['tvid'], $modx->manager->action, $_lang['edit']);
    $tvList .= '</li>';

    $preCat = $row['category'];
}
$tvList .= $insideUl? '</ul>': '';
$tvList .= '</ul>';
echo $tvList;

?></div>
<?php
// invoke OnTempFormRender event
$evtOut = $modx->invokeEvent("OnTempFormRender",array("id" => $id));
if(is_array($evtOut)) echo implode("",$evtOut);
?>
</div>
</div>
</form>
