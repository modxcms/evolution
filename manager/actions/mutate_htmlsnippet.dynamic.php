<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

switch ((int) $_REQUEST['a']) {
    case 78:
        if (!$modx->hasPermission('edit_chunk')) {
            $modx->webAlertAndQuit($_lang["error_no_privileges"]);
        }
        break;
    case 77:
        if (!$modx->hasPermission('new_chunk')) {
            $modx->webAlertAndQuit($_lang["error_no_privileges"]);
        }
        break;
    default:
        $modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

// Get table names (alphabetical)
$tbl_active_users      = $modx->getFullTableName('active_users');
$tbl_site_htmlsnippets = $modx->getFullTableName('site_htmlsnippets');

// Check to see the snippet editor isn't locked
$rs = $modx->db->select('username', $tbl_active_users, "action=78 AND id='{$id}' AND internalKey!='".$modx->getLoginUserID()."'");
    if ($username = $modx->db->getValue($rs)) {
            $modx->webAlertAndQuit(sprintf($_lang['lock_msg'], $username, $_lang['chunk']));
    }

$content = array();
if (isset($_REQUEST['id']) && $_REQUEST['id']!='' && is_numeric($_REQUEST['id'])) {
    $rs = $modx->db->select('*', $tbl_site_htmlsnippets, "id='{$id}'");
    $content = $modx->db->getRow($rs);
    if (!$content) {
        $modx->webAlertAndQuit("Chunk not found for id '{$id}'.");
    }
    $_SESSION['itemname'] = $content['name'];
    if ($content['locked'] == 1 && $_SESSION['mgrRole'] != 1) {
        $modx->webAlertAndQuit($_lang["error_no_privileges"]);
    }
} else {
    $_SESSION['itemname'] = $_lang["new_htmlsnippet"];
}

if ($modx->manager->hasFormValues()) {
    $modx->manager->loadFormValues();
}

if (isset($_POST['which_editor'])) {
    $which_editor = $_POST['which_editor'];
} else {
    $which_editor = $content['editor_name'] != 'none' ? $content['editor_name'] : 'none';
}

$content = array_merge($content, $_POST);

// Print RTE Javascript function
?>
<script language="javascript" type="text/javascript">
// Added for RTE selection
function changeRTE(){
    var whichEditor = document.getElementById('which_editor');
    if (whichEditor) for (var i=0; i<whichEditor.length; i++){
        if (whichEditor[i].selected){
            newEditor = whichEditor[i].value;
            break;
        }
    }

    documentDirty=false;
    document.mutate.a.value = <?php echo $action?>;
    document.mutate.which_editor.value = newEditor;
    document.mutate.submit();
}

function duplicaterecord(){
    if (confirm("<?php echo $_lang['confirm_duplicate_record']?>")==true) {
        documentDirty=false;
        document.location.href="index.php?id=<?php echo $_REQUEST['id']?>&a=97";
    }
}

function deletedocument() {
    if (confirm("<?php echo $_lang['confirm_delete_htmlsnippet']?>")==true) {
        documentDirty=false;
        document.location.href="index.php?id=" + document.mutate.id.value + "&a=80";
    }
}
</script>

<form class="htmlsnippet" id="mutate" name="mutate" method="post" action="index.php">
<?php

// invoke OnChunkFormPrerender event
$evtOut = $modx->invokeEvent('OnChunkFormPrerender', array(
    'id' => $id,
));
if (is_array($evtOut))
    echo implode('', $evtOut);

?>
<input type="hidden" name="a" value="79" />
<input type="hidden" name="id" value="<?php echo $_REQUEST['id']?>" />
<input type="hidden" name="mode" value="<?php echo (int) $_REQUEST['a']?>" />

    <h1><?php echo $_lang['htmlsnippet_title']?></h1>

    <div id="actions">
          <ul class="actionButtons">
              <li id="Button1">
                <a href="#" onclick="documentDirty=false; document.mutate.save.click();">
                  <img src="<?php echo $_style["icons_save"]?>" /> <?php echo $_lang['save']?>
                </a>
                <span class="plus"> + </span>
                <select id="stay" name="stay">
          <?php if ($modx->hasPermission('new_chunk')) { ?>
                  <option id="stay1" value="1" <?php echo $_REQUEST['stay']=='1' ? ' selected="selected"' : ''?> ><?php echo $_lang['stay_new']?></option>
          <?php } ?>
                  <option id="stay2" value="2" <?php echo $_REQUEST['stay']=='2' ? ' selected="selected"' : ''?> ><?php echo $_lang['stay']?></option>
                  <option id="stay3" value=""  <?php echo $_REQUEST['stay']=='' ? ' selected="selected"' : ''?>  ><?php echo $_lang['close']?></option>
                </select>
              </li>
          <?php if ($_REQUEST['a'] == '77') { ?>
              <li id="Button6" class="disabled"><a href="#" onclick="duplicaterecord();"><img src="<?php echo $_style["icons_resource_duplicate"] ?>" /> <?php echo $_lang["duplicate"]; ?></a></li>
              <li id="Button3" class="disabled"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"]?>" /> <?php echo $_lang['delete']?></a></li>
          <?php } else { ?>
              <li id="Button6"><a href="#" onclick="duplicaterecord();"><img src="<?php echo $_style["icons_resource_duplicate"] ?>" /> <?php echo $_lang["duplicate"]; ?></a></li>
              <li id="Button3"><a href="#" onclick="deletedocument();"><img src="<?php echo $_style["icons_delete_document"]?>" /> <?php echo $_lang['delete']?></a></li>
          <?php } ?>
              <li id="Button5"><a href="#" onclick="documentDirty=false;document.location.href='index.php?a=76';"><img src="<?php echo $_style["icons_cancel"] ?>" /> <?php echo $_lang['cancel']?></a></li>
          </ul>
    </div>

<script type="text/javascript" src="media/script/tabpane.js"></script>
<div class="sectionBody">
<div class="tab-pane" id="chunkPane">
    <script type="text/javascript">
        tpChunk = new WebFXTabPane( document.getElementById( "chunkPane" ), <?php echo $modx->config['remember_last_tab'] == 1 ? 'true' : 'false'; ?> );
    </script>
    <div class="tab-page" id="tabGeneral">
        <h2 class="tab"><?php echo $_lang["settings_general"] ?></h2>
    	<script type="text/javascript">tpChunk.addTabPage( document.getElementById( "tabGeneral" ) );</script>
    <p><?php echo $_lang['htmlsnippet_msg']?></p>
    <table>
        <tr><th><?php echo $_lang['htmlsnippet_name']?></th>
            <td>{{&nbsp;<input name="name" type="text" maxlength="100" value="<?php echo $modx->htmlspecialchars($content['name'])?>" class="inputBox" style="width:250px;" onchange="documentDirty=true;">}}<span class="warning" id="savingMessage">&nbsp;</span></td></tr>
    <tr>
        <th><?php echo $_lang['htmlsnippet_desc']?></th>
        <td><input name="description" type="text" maxlength="255" value="<?php echo $modx->htmlspecialchars($content['description'])?>" class="inputBox" style="width:300px;" onchange="documentDirty=true;"></td>
    </tr>
    <tr>
        <th><?php echo $_lang['existing_category']?></th>
        <td>
        <select name="categoryid" style="width:300px;" onchange="documentDirty=true;">
            <option>&nbsp;</option>
<?php
include_once(MODX_MANAGER_PATH.'includes/categories.inc.php');
foreach (getCategories() as $n => $v) {
    echo "\t\t\t\t".'<option value="'.$v['id'].'"'.($content['category'] == $v['id'] || (empty($content['category']) && $_POST['categoryid'] == $v['id']) ? ' selected="selected"' : '').'>'.$modx->htmlspecialchars($v['category'])."</option>\n";
}
?>
        </select></td>
    </tr>
    <tr>
        <th><?php echo $_lang['new_category']?></th>
        <td><input name="newcategory" type="text" maxlength="45" value="<?php echo isset($content['newcategory']) ? $content['newcategory'] : ''?>" class="inputBox" style="width:300px;" onChange="documentDirty=true;"></td></tr>
<?php if($modx->hasPermission('save_role')):?>
    <tr><td colspan="2"><label style="display:block;"><input name="locked" type="checkbox"<?php echo $content['locked'] == 1 || $content['locked'] == 'on' ? ' checked="checked"' : ''?> class="inputBox" value="on" /> <?php echo $_lang['lock_htmlsnippet']?></label>
        <span class="comment"><?php echo $_lang['lock_htmlsnippet_msg']?></span></td>
    </tr>
<?php endif;?>
    </table>

    <div class="section">
        <div class="sectionHeader">
            <?php echo $_lang['chunk_code']?>
        </div>
        <div class="sectionBody">
        <textarea dir="ltr" class="phptextarea" name="post" style="width:100%; height:370px;" onChange="documentDirty=true;"><?php echo isset($content['post']) ? $modx->htmlspecialchars($content['post']) : $modx->htmlspecialchars($content['snippet'])?></textarea>
        </div>
    </div>

    <span class="warning"><?php echo $_lang['which_editor_title']?></span>
            <select id="which_editor" name="which_editor" onchange="changeRTE();">
                <option value="none"<?php echo $which_editor == 'none' ? ' selected="selected"' : ''?>><?php echo $_lang['none']?></option>
<?php
// invoke OnRichTextEditorRegister event
$evtOut = $modx->invokeEvent('OnRichTextEditorRegister');
if (is_array($evtOut)) {
    foreach ($evtOut as $i => $editor) {
        echo "\t".'<option value="'.$editor.'"'.($which_editor == $editor ? ' selected="selected"' : '').'>'.$editor."</option>\n";
    }
}
?>
            </select>
</div><!-- end .sectionBody -->

<?php

// invoke OnChunkFormRender event
$evtOut = $modx->invokeEvent('OnChunkFormRender', array(
    'id' => $id,
));
if (is_array($evtOut))
    echo implode('', $evtOut);
?>
</div>
</div>
<input type="submit" name="save" style="display:none;" />
</form>
<?php
// invoke OnRichTextEditorInit event
if ($use_editor == 1) {
    $evtOut = $modx->invokeEvent('OnRichTextEditorInit', array(
        'editor' => $which_editor,
        'elements' => array(
            'post',
        ),
    ));
    if (is_array($evtOut))
        echo implode('', $evtOut);
}
?>