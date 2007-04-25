<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('settings')) {
	$e->setError(3);
	$e->dumpError();
}

// check to see the edit settings page isn't locked
$sql = "SELECT internalKey, username FROM $dbase.`".$table_prefix."active_users` WHERE $dbase.`".$table_prefix."active_users`.action=17";
$rs = mysql_query($sql);
$limit = mysql_num_rows($rs);
if($limit>1) {
	for ($i=0;$i<$limit;$i++) {
		$lock = mysql_fetch_assoc($rs);
		if($lock['internalKey']!=$modx->getLoginUserID()) {
			$msg = sprintf($_lang["lock_settings_msg"],$lock['username']);
			$e->setError(5, $msg);
			$e->dumpError();
		}
	}
}
// end check for lock

// reload system settings from the database.
// this will prevent user-defined settings from being saved as system setting
$settings = array();
$sql = "SELECT setting_name, setting_value FROM $dbase.`".$table_prefix."system_settings`";
$rs = mysql_query($sql);
$number_of_settings = mysql_num_rows($rs);
while ($row = mysql_fetch_assoc($rs)) $settings[$row['setting_name']] = $row['setting_value'];
extract($settings, EXTR_OVERWRITE);

$displayStyle = ( ($_SESSION['browser']=='mz') || ($_SESSION['browser']=='op') ) ? "table-row" : "block" ;

?>

<script type="text/javascript">
function checkIM() {
	im_on = document.settings.im_plugin[0].checked; // check if im_plugin is on
	if(im_on==true) {
		showHide(/imRow/, 1);
	}
};

function checkCustomIcons() {
	if(document.settings.editor_toolbar.selectedIndex!=3) {
		showHide(/custom/,0);
	}
};

function showHide(what, onoff){

	var all = document.getElementsByTagName( "*" );
	var l = all.length;
	var buttonRe = what;
	var id, el, stylevar;

	if(onoff==1) {
		stylevar = "<?php echo $displayStyle; ?>";
	} else {
		stylevar = "none";
	}

	for ( var i = 0; i < l; i++ ) {
		el = all[i]
		id = el.id;
		if ( id == "" ) continue;
		if (buttonRe.test(id)) {
			el.style.display = stylevar;
		}
	}
};

function addContentType(){
	var i,o,exists=false;
	var txt = document.settings.txt_custom_contenttype;
	var lst = document.settings.lst_custom_contenttype;
	for(i=0;i<lst.options.length;i++)
	{
		if(lst.options[i].value==txt.value) {
			exists=true;
			break;
		}
	}
	if (!exists) {
		o = new Option(txt.value,txt.value);
		lst.options[lst.options.length]= o;
		updateContentType();
	}
	txt.value='';
}
function removeContentType(){
	var i;
	var lst = document.settings.lst_custom_contenttype;
	for(i=0;i<lst.options.length;i++) {
		if(lst.options[i].selected) {
			lst.remove(i);
			break;
		}
	}
	updateContentType();
}
function updateContentType(){
	var i,o,ol=[];
	var lst = document.settings.lst_custom_contenttype;
	var ct = document.settings.custom_contenttype;
	while(lst.options.length) {
		ol[ol.length] = lst.options[0].value;
		lst.options[0]= null;
	}
	if(ol.sort) ol.sort();
	ct.value = ol.join(",");
	for(i=0;i<ol.length;i++) {
		o = new Option(ol[i],ol[i]);
		lst.options[lst.options.length]= o;
	}
	documentDirty = true;
}

</script>
<div class="subTitle">
	<span class="right"><?php echo $_lang['settings_title']; ?></span>

	<table cellpadding="0" cellspacing="0" class="actionButtons">
		<tr>
			<td id="Button1"><a href="#" onclick="documentDirty=false; document.settings.submit();"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/save.gif" /> <?php echo $_lang['save']; ?></a></td>
			<td id="Button5"><a href="#" onclick="documentDirty=false;document.location.href='index.php?a=2';"><img src="media/style/<?php echo $manager_theme ? "$manager_theme/":""; ?>images/icons/cancel.gif" /> <?php echo $_lang['cancel']; ?></a></td>
		</tr>
	</table>
</div>
<form name="settings" action="index.php?a=30" method="post" />
<div style="margin: 0 10px 0 20px">
    <input type="hidden" name="site_id" value="<?php echo $site_id; ?>" />
    <input type="hidden" name="settings_version" value="<?php echo $version; ?>" />
    <!-- this field is used to check site settings have been entered/ updated after install or upgrade -->
    <?php if(!isset($settings_version) || $settings_version!=$version) { ?>
    <div class='sectionBody'><?php echo $_lang['settings_after_install']; ?></div>
    <?php } ?>
    <script type="text/javascript" src="media/script/tabpane.js"></script>
    <div class="tab-pane" id="settingsPane">
      <script type="text/javascript">
		tpSettings = new WebFXTabPane( document.getElementById( "settingsPane" ) );
	</script>

	<!-- Site Settings -->
      <div class="tab-page" id="tabPage2">
        <h2 class="tab"><?php echo $_lang["settings_site"] ?></h2>
        <script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabPage2" ) );</script>
        <table border="0" cellspacing="0" cellpadding="3">
            <tr>
              <td nowrap class="warning"><b><?php echo $_lang["sitename_title"] ?></b></td>
              <td ><input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 200px;" name="site_name" value="<?php echo isset($site_name) ? $site_name : "My MODx Site" ; ?>" /></td>
            </tr>
            <tr>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php echo $_lang["sitename_message"] ?></td>
            </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["language_title"]?></b></td>
            <td> <select name="manager_language" size="1" class="inputBox" onchange="documentDirty=true;">
                <?php
	$dir = dir("includes/lang");

	while ($file = $dir->read()) {
		if(strpos($file, ".inc.php")>0) {
			$endpos = strpos ($file, ".");
			$languagename = substr($file, 0, $endpos);
			$selectedtext = $languagename==$manager_language ? "selected='selected'" : "" ;
?>
                <option value="<?php echo $languagename; ?>" <?php echo $selectedtext; ?>><?php echo ucwords(str_replace("_", " ", $languagename)); ?></option>
                <?php
		}
	}
	$dir->close();
?>
              </select> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["language_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["charset_title"]?></b></td>
            <td> <select name="modx_charset" size="1" class="inputBox" style="width:250px;" onchange="documentDirty=true;">
                <?php include "charsets.php"; ?>
              </select> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["charset_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["manager_direction_title"] ?></b></td>
            <td><select name="manager_direction" size="1" class="inputBox" style="width:100px;" onchange="documentDirty=true;">
                <option value="ltr" <?php echo (isset($manager_direction) && $manager_direction=='ltr') ? 'selected' : ''?>>ltr</option>
                <option value="rtl" <?php echo (isset($manager_direction) && $manager_direction=='rtl') ? 'selected' : ''?>>rtl</option>
              </select></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["manager_direction_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["manager_lang_attribute_title"] ?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='20' size='10' name="manager_lang_attribute" value="<?php echo isset($manager_lang_attribute) ? $manager_lang_attribute : 'en' ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["manager_lang_attribute_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["xhtml_urls_title"] ?></b></td>
            <td><input onchange="documentDirty=true;" type="radio" name="xhtml_urls" value="1" <?php echo $xhtml_urls=='1' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="xhtml_urls" value="0" <?php echo ($xhtml_urls=='0' || !isset($xhtml_urls)) ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["xhtml_urls_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["sitestart_title"] ?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='10' size='5' name="site_start" value="<?php echo isset($site_start) ? $site_start : 1 ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["sitestart_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["errorpage_title"] ?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='10' size='5' name="error_page" value="<?php echo isset($error_page) ? $error_page : 1 ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["errorpage_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["unauthorizedpage_title"] ?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='10' size='5' name="unauthorized_page" value="<?php echo isset($unauthorized_page) ? $unauthorized_page : 1 ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["unauthorizedpage_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["sitestatus_title"] ?></b></td>
            <td><input onchange="documentDirty=true;" type="radio" name="site_status" value="1" <?php echo ($site_status=='1' || !isset($site_status)) ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["online"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="site_status" value="0" <?php echo $site_status=='0' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["offline"]?> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["sitestatus_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["siteunavailable_page_title"] ?></b></td>
            <td><input onchange="documentDirty=true;" name="site_unavailable_page" type="text" maxlength="10" size="5" value="<?php echo isset($site_unavailable_page) ? $site_unavailable_page : "" ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["siteunavailable_page_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["siteunavailable_title"] ?></b></td>
            <td> <textarea name="site_unavailable_message" style="width:100%; height: 120px;"><?php echo isset($site_unavailable_message) ? $site_unavailable_message : "The site is currently unavailable" ; ?></textarea> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["siteunavailable_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>

          <tr>
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["track_visitors_title"] ?></b></td>
            <td><input onchange="documentDirty=true;" type="radio" name="track_visitors" value="1" <?php echo ($track_visitors=='1' || !isset($track_visitors)) ? 'checked="checked"' : "" ; ?> onclick='showHide(/logRow/, 1);' />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="track_visitors" value="0" <?php echo $track_visitors=='0' ? 'checked="checked"' : "" ; ?> onclick='showHide(/logRow/, 0);' />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["track_visitors_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='logRow1' class='row1' style="display: <?php echo $track_visitors==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["resolve_hostnames_title"] ?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="resolve_hostnames" value="1" <?php echo ($resolve_hostnames=='1' || !isset($resolve_hostnames)) ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="resolve_hostnames" value="0" <?php echo $resolve_hostnames=='0' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr id='logRow2' class='row1' style="display: <?php echo $track_visitors==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["resolve_hostnames_message"] ?></td>
          </tr>
          <tr id='logRow3' style="display: <?php echo $track_visitors==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["top_howmany_title"] ?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='50' size="5" name="top_howmany" value="<?php echo isset($top_howmany) ? $top_howmany : 10 ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["top_howmany_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["defaulttemplate_title"] ?></b></td>
            <td>
			<?php
				$sql = "select templatename, id from $dbase.`".$table_prefix."site_templates`";
				$rs = mysql_query($sql);
			?>
			  <select name="default_template" class="inputBox" onchange='documentDirty=true;' style="width:150px">
				<?php
				while ($row = mysql_fetch_assoc($rs)) {
					$selectedtext = $row['id']==$default_template ? "selected='selected'" : "" ;
					if ($selectedtext) {
						$oldTmpId = $row['id'];
						$oldTmpName = $row['templatename'];
					}
				?>
					<option value="<?php echo $row['id']; ?>" <?php echo $selectedtext; ?>><?php echo $row['templatename']; ?></option>
				<?php
				}
				?>
 			 </select>
 			 	<br />
 			 	<br />
				<input onchange="documentDirty=true;" type="radio" name="reset_template" value="1" /> <?php echo $_lang["template_reset_all"]; ?><br />
				<input onchange="documentDirty=true;" type="radio" name="reset_template" value="2" /> <?php echo sprintf($_lang["template_reset_specific"],$oldTmpName); ?>
				<input type="hidden" name="old_template" value="<?php echo $oldTmpId; ?>" />
			</td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["defaulttemplate_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["defaultpublish_title"] ?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="publish_default" value="1" <?php echo $publish_default=='1' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="publish_default" value="0" <?php echo ($publish_default=='0' || !isset($publish_default)) ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["defaultpublish_message"] ?></td>
          </tr>

          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["defaultcache_title"] ?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="cache_default" value="1" <?php echo $cache_default=='1' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="cache_default" value="0" <?php echo ($cache_default=='0' || !isset($cache_default)) ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["defaultcache_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["defaultsearch_title"] ?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="search_default" value="1" <?php echo $search_default=='1' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="search_default" value="0" <?php echo ($search_default=='0' || !isset($search_default)) ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["defaultsearch_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr> 
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["defaultmenuindex_title"] ?></b></td> 
            <td> <input onchange="documentDirty=true;" type="radio" name="auto_menuindex" value="1" <?php echo ($auto_menuindex=='1' || !isset($auto_menuindex)) ? 'checked="checked"' : "" ; ?> /> 
            <?php echo $_lang["yes"]?><br /> 
            <input onchange="documentDirty=true;" type="radio" name="auto_menuindex" value="0" <?php echo ($auto_menuindex=='0') ? 'checked="checked"' : "" ; ?> /> 
            <?php echo $_lang["no"]?> </td> 
          </tr>
          <tr> 
            <td width="200">&nbsp;</td> 
            <td class='comment'><?php echo $_lang["defaultmenuindex_message"] ?></td> 
          </tr>
          <tr> 
            <td colspan="2"><div class='split'></div></td> 
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["custom_contenttype_title"] ?></b></td>
            <td><input name="txt_custom_contenttype" type="text" maxlength="100" style="width: 200px;" value="" /> <input type="button" value="<?php echo $_lang["add"]; ?>" style="width:60px" onclick='addContentType()' /><br />
            <table border="0" cellspacing="0" cellpadding="0"><tr><td valign="top">
            <select name="lst_custom_contenttype" style="width:200px;" size="5">
            <?php
	            $custom_contenttype = (isset($custom_contenttype) ? $custom_contenttype : "text/css,text/html,text/javascript,text/plain,text/xml");
            	$ct = explode(",",$custom_contenttype);
            	for($i=0;$i<count($ct);$i++) {
            		echo "<option value=\"".$ct[$i]."\">".$ct[$i]."</option>";
            	}
            ?>
            </select>
            <input name="custom_contenttype" type="hidden" value="<?php echo $custom_contenttype; ?>" />
            </td><td valign="top">&nbsp;<input name="removecontenttype" type="button" value="<?php echo $_lang["remove"]; ?>" style="width:60px" onclick='removeContentType()' /></td></tr></table>
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["custom_contenttype_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
            <tr>
              <td nowrap class="warning"><b><?php echo $_lang["serveroffset_title"] ?></b></td>
              <td> <select name="server_offset_time" size="1" class="inputBox">
                  <?php
  			for($i=-24; $i<25; $i++) {
  				$seconds = $i*60*60;
  				$selectedtext = $seconds==$server_offset_time ? "selected='selected'" : "" ;
  			?>
                  <option value="<?php echo $seconds; ?>" <?php echo $selectedtext; ?>><?php echo $i; ?></option>
                  <?php
  			}
  			?>
                </select> </td>
            </tr>
            <tr>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php printf($_lang["serveroffset_message"], strftime('%H:%M:%S', time()), strftime('%H:%M:%S', time()+$server_offset_time)); ?></td>
            </tr>
            <tr>
              <td colspan="2"><div class='split'>&nbsp;</div></td>
            </tr>
            <tr>
              <td nowrap class="warning"><b><?php echo $_lang["server_protocol_title"] ?></b></td>
              <td> <input onchange="documentDirty=true;" type="radio" name="server_protocol" value="http" <?php echo ($server_protocol=='http' || !isset($server_protocol))? 'checked="checked"' : "" ; ?> />
                <?php echo $_lang["server_protocol_http"]?><br />
                <input onchange="documentDirty=true;" type="radio" name="server_protocol" value="https" <?php echo $server_protocol=='https' ? 'checked="checked"' : "" ; ?> />
                <?php echo $_lang["server_protocol_https"]?> </td>
            </tr>
            <tr>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php echo $_lang["server_protocol_message"] ?></td>
            </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
		  <tr class='row1'>
            <td colspan="2">
		        <?php
					// invoke OnSiteSettingsRender event
					$evtOut = $modx->invokeEvent("OnSiteSettingsRender");
					if(is_array($evtOut)) echo implode("",$evtOut);
		        ?>
            </td>
          </tr>
        </table>
      </div>

      <!-- Friendly URL settings  -->
      <div class="tab-page" id="tabPage3">
        <h2 class="tab"><?php echo $_lang["settings_furls"] ?></h2>
        <script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabPage3" ) );</script>
        <table border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["friendlyurls_title"] ?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="friendly_urls" value="1" <?php echo $friendly_urls=='1' ? 'checked="checked"' : "" ; ?> onclick='showHide(/furlRow/, 1);' />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="friendly_urls" value="0" <?php echo ($friendly_urls=='0' || !isset($friendly_urls)) ? 'checked="checked"' : "" ; ?> onclick='showHide(/furlRow/, 0);' />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment' width="300"><?php echo $_lang["friendlyurls_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='furlRow1' class='row1' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["friendlyurlsprefix_title"] ?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='50' style="width: 200px;" name="friendly_url_prefix" value="<?php echo isset($friendly_url_prefix) ? $friendly_url_prefix : "p" ; ?>" /></td>
          </tr>
          <tr id='furlRow2' class='row1' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["friendlyurlsprefix_message"] ?></td>
          </tr>
          <tr id='furlRow3' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='furlRow4' class='row1' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["friendlyurlsuffix_title"] ?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='50' style="width: 200px;" name="friendly_url_suffix" value="<?php echo isset($friendly_url_suffix) ? $friendly_url_suffix : ".html" ; ?>" /></td>
          </tr>
          <tr id='furlRow5' class='row1' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["friendlyurlsuffix_message"] ?></td>
          </tr>
          <tr id='furlRow6' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='furlRow7' class='row1' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["friendly_alias_title"] ?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="friendly_alias_urls" value="1" <?php echo $friendly_alias_urls=='1' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="friendly_alias_urls" value="0" <?php echo ($friendly_alias_urls=='0' || !isset($friendly_alias_urls)) ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr id='furlRow8' class='row1' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["friendly_alias_message"] ?></td>
          </tr>
          <tr id='furlRow9' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='furlRow10' class='row1' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["use_alias_path_title"] ?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="use_alias_path" value="1" <?php echo $use_alias_path=='1' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="use_alias_path" value="0" <?php echo ($use_alias_path=='0' || !isset($use_alias_path)) ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr id='furlRow11' class='row1' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["use_alias_path_message"] ?></td>
          </tr>
          <tr id='furlRow12' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='furlRow16' class='row2' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["duplicate_alias_title"] ?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="allow_duplicate_alias" value="1" <?php echo $allow_duplicate_alias=='1' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="allow_duplicate_alias" value="0" <?php echo ($allow_duplicate_alias=='0' || !isset($allow_duplicate_alias)) ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr id='furlRow17' class='row2' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["duplicate_alias_message"] ?></td>
          </tr>
          <tr id='furlRow18' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='furlRow13' class='row1' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["automatic_alias_title"] ?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="automatic_alias" value="1" <?php echo $automatic_alias=='1' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="automatic_alias" value="0" <?php echo ($automatic_alias=='0' || !isset($automatic_alias)) ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr id='furlRow14' class='row1' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["automatic_alias_message"] ?></td>
          </tr>
          <tr id='furlRow15' style="display: <?php echo $friendly_urls==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
		  <tr class='row1'>
            <td colspan="2">
		        <?php
					// invoke OnFriendlyURLSettingsRender event
					$evtOut = $modx->invokeEvent("OnFriendlyURLSettingsRender");
					if(is_array($evtOut)) echo implode("",$evtOut);
		        ?>
            </td>
          </tr>
        </table>
      </div>

      <!-- User settings -->
      <div class="tab-page" id="tabPage4">
        <h2 class="tab"><?php echo $_lang["settings_users"] ?></h2>
        <script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabPage4" ) );</script>
        <table border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["udperms_title"] ?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="use_udperms" value="1" <?php echo $use_udperms=='1' ? 'checked="checked"' : "" ; ?> onclick='showHide(/udPerms/, 1);' />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="use_udperms" value="0" <?php echo ($use_udperms=='0' || !isset($use_udperms)) ? 'checked="checked"' : "" ; ?> onclick='showHide(/udPerms/, 0);' />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["udperms_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='udPermsRow1' class='row1' style="display: <?php echo $use_udperms==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning"><b><?php echo $_lang["udperms_allowroot_title"] ?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="udperms_allowroot" value="1" <?php echo $udperms_allowroot=='1' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="udperms_allowroot" value="0" <?php echo ($udperms_allowroot=='0' || !isset($udperms_allowroot)) ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr id='udPermsRow2' class='row1' style="display: <?php echo $use_udperms==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["udperms_allowroot_message"] ?></td>
          </tr>
          <tr id='udPermsRow3' style="display: <?php echo $use_udperms==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["failed_login_title"] ?></b></td>
            <td><input name="failed_login_attempts" style="width:50px" value="<?php echo isset($failed_login_attempts) ? $failed_login_attempts : "3" ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["failed_login_message"] ?></td>
          </tr>
           <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["blocked_minutes_title"] ?></b></td>
            <td><input name="blocked_minutes" style="width:100px" value="<?php echo isset($blocked_minutes) ? $blocked_minutes : "60" ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["blocked_minutes_message"] ?></td>
          </tr>
           <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <?php
              // Check for GD before allowing captcha to be enabled
              $gdAvailable = extension_loaded('gd');
          ?>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["captcha_title"] ?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="use_captcha" value="1" <?php echo ($use_captcha=='1' && $gdAvailable) ? 'checked="checked"' : "" ; echo (!$gdAvailable)? ' readonly="readonly"' : ''; ?> />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="use_captcha" value="0" <?php echo ($use_captcha=='0' || !isset($use_captcha) || !$gdAvailable) ? 'checked="checked"' : "" ; echo (!$gdAvailable)? ' readonly="readonly"' : '';?> />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["captcha_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["captcha_words_title"] ?></b></td>
            <td><input name="captcha_words" style="width:250px" value="<?php echo isset($captcha_words) ? $captcha_words : "MODx,Access,Better,BitCode,Chunk,Cache,Desc,Design,Excell,Enjoy,URLs,TechView,Gerald,Griff,Humphrey,Holiday,Intel,Integration,Joystick,Join(),Oscope,Genetic,Light,Likeness,Marit,Maaike,Niche,Netherlands,Ordinance,Oscillo,Parser,Phusion,Query,Question,Regalia,Righteous,Snippet,Sentinel,Template,Thespian,Unity,Enterprise,Verily,Tattoo,Veri,Website,WideWeb,Yap,Yellow,Zebra,Zygote" ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["captcha_words_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["emailsender_title"] ?></b></td>
            <td ><input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="emailsender" value="<?php echo isset($emailsender) ? $emailsender : "you@yourdomain.com" ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["emailsender_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["emailsubject_title"] ?></b></td>
            <td ><input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="emailsubject" value="<?php echo isset($emailsubject) ? $emailsubject : "Your login details" ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["emailsubject_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["signupemail_title"] ?></b></td>
            <td> <textarea name="signupemail_message" style="width:100%; height: 120px;"><?php echo isset($signupemail_message) ? $signupemail_message : $_lang["system_email_signup"] ?></textarea> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["signupemail_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["websignupemail_title"] ?></b></td>
            <td> <textarea name="websignupemail_message" style="width:100%; height: 120px;"><?php echo isset($websignupemail_message) ? $websignupemail_message : $_lang["system_email_websignup"] ?></textarea> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["websignupemail_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><b><?php echo $_lang["webpwdreminder_title"] ?></b></td>
            <td> <textarea name="webpwdreminder_message" style="width:100%; height: 120px;"><?php echo isset($webpwdreminder_message) ? $webpwdreminder_message : $_lang["system_email_webreminder"] ?></textarea> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["webpwdreminder_message"] ?></td>
          </tr>
		  <tr class='row1'>
            <td colspan="2">
		        <?php
					// invoke OnUserSettingsRender event
					$evtOut = $modx->invokeEvent("OnUserSettingsRender");
					if(is_array($evtOut)) echo implode("",$evtOut);
		        ?>
            </td>
          </tr>
        </table>
      </div>

      <!-- Interface & editor settings -->
      <div class="tab-page" id="tabPage5">
        <h2 class="tab"><?php echo $_lang["settings_ui"] ?></h2>
        <script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabPage5" ) );</script>
        <table border="0" cellspacing="0" cellpadding="3">
            <tr>
              <td nowrap class="warning"><b><?php echo $_lang["manager_theme"]?></b></td>
              <td> <select name="manager_theme" size="1" class="inputBox" onchange="documentDirty=true;document.forms['settings'].theme_refresher.value = Date.parse(new Date())">
               <?php
      			$dir = dir("media/style/");
      			while ($file = $dir->read()) {
      				if($file!="." && $file!=".." && is_dir("media/style/$file") && substr($file,0,1) != '.') {
      					$themename = $file;
      					$selectedtext = $themename==$manager_theme ? "selected='selected'" : "" ;
      	            	echo "<option value='$themename' $selectedtext>".ucwords(str_replace("_", " ", $themename))."</option>";
      				}
      			}
      			$dir->close();
      		 ?>
               </select><input type="hidden" name="theme_refresher" value="" /></td>
            </tr>
            <tr>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php echo $_lang["manager_theme_message"]?></td>
            </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
             <tr>
      		   <td nowrap class="warning"><b><?php echo $_lang["tree_show_protected"] ?></b></td>
      		   <td> <input onchange="documentDirty=true;" type="radio" name="tree_show_protected" value="1" <?php echo (!isset($tree_show_protected) || $tree_show_protected=='0') ? '' : 'checked="checked" '; ?>/>
      			 <?php echo $_lang["yes"]?><br />
      			 <input onchange="documentDirty=true;" type="radio" name="tree_show_protected" value="0" <?php echo (!isset($tree_show_protected) || $tree_show_protected=='0') ? 'checked="checked" ' : ''; ?>/>
      			 <?php echo $_lang["no"]?></td>
      		 </tr>
                 <tr>
                   <td width="200">&nbsp;</td>
                   <td class='comment'><?php echo $_lang["tree_show_protected_message"]?></td>
                 </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
             <tr>
      		   <td nowrap class="warning"><b><?php echo $_lang["show_preview"] ?></b></td>
      		   <td> <input onchange="documentDirty=true;" type="radio" name="show_preview" value="1" <?php echo ($show_preview=='1' || !isset($show_preview)) ? 'checked="checked"' : ""; ?> />
      			 <?php echo $_lang["yes"]?><br />
      			 <input onchange="documentDirty=true;" type="radio" name="show_preview" value="0" <?php echo $show_preview=='0' ? 'checked="checked"' : ""; ?> />
      			 <?php echo $_lang["no"]?></td>
      		 </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["nologentries_title"]?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='50' size="5" name="number_of_logs" value="<?php echo isset($number_of_logs) ? $number_of_logs : 100 ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["nologentries_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["mail_check_timeperiod_title"] ?></b></td>
            <td><input name="mail_check_timeperiod" onchange="documentDirty=true;" size="5" value="<?php echo isset($mail_check_timeperiod) ? $mail_check_timeperiod : "60" ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["mail_check_timeperiod_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["nomessages_title"]?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='50' size="5" name="number_of_messages" value="<?php echo isset($number_of_messages) ? $number_of_messages : 30 ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["nomessages_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["noresults_title"]?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='50' size="5" name="number_of_results" value="<?php echo isset($number_of_results) ? $number_of_results : 30 ; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["noresults_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>

          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["rb_title"]?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="use_browser" value="1" <?php echo ($use_browser=='1' || !isset($use_browser)) ? 'checked="checked"' : "" ; ?> onclick="showHide(/rbRow/, 1);" />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="use_browser" value="0" <?php echo $use_browser=='0' ? 'checked="checked"' : "" ; ?> onclick="showHide(/rbRow/, 0);" />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["rb_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          
          <?php if(!isset($use_browser)) $use_browser=1; ?>
          
          <tr id='rbRow1' class='row3' style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning"><b><?php echo $_lang["settings_strip_image_paths_title"]?></b></td>
            <td><input onchange="documentDirty=true;" type="radio" name="strip_image_paths" value="1" <?php echo $strip_image_paths=='1' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="strip_image_paths" value="0" <?php echo ($strip_image_paths=='0' || !isset($strip_image_paths)) ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr id='rbRow2' class='row3' style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["settings_strip_image_paths_message"]?></td>
          </tr>
          <tr id='rbRow3' class='row3' style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='rbRow4' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning"><b><?php echo $_lang["rb_base_dir_title"]?></b></td>
            <td> <?php
				function getResourceBaseDir() {
					global $base_path;
					return $base_path."assets/";
				}
				?>
              <input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="rb_base_dir" value="<?php echo isset($rb_base_dir) ? $rb_base_dir : getResourceBaseDir() ; ?>" />
              </td>
          </tr>
          <tr id='rbRow5' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["rb_base_dir_message"]?></td>
          </tr>
          <tr id='rbRow6' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='rbRow7' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning"><b><?php echo $_lang["rb_base_url_title"]?></b></td>
            <td> <?php
				function getResourceBaseUrl() {
					global $site_url;
					return $site_url . "assets/";
				}
				?>
              <input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="rb_base_url" value="<?php echo isset($rb_base_url) ? $rb_base_url : getResourceBaseUrl() ; ?>" />
              </td>
          </tr>
          <tr id='rbRow8' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["rb_base_url_message"]?></td>
          </tr>
          <tr id='rbRow9' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='rbRow10' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning"><b><?php echo $_lang["uploadable_images_title"]?></b></td>
            <td>
              <input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="upload_images" value="<?php echo isset($upload_images) ? $upload_images : "jpg,gif,png,ico,bmp,psd" ; ?>" />
            </td>
          </tr>
          <tr id='rbRow11' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["uploadable_images_message"]?></td>
          </tr>
          <tr id='rbRow12' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
          <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='rbRow13' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning"><b><?php echo $_lang["uploadable_media_title"]?></b></td>
            <td>
              <input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="upload_media" value="<?php echo isset($upload_media) ? $upload_media : "mp3,wav,au,wmv,avi,mpg,mpeg" ; ?>" />
            </td>
          </tr>
          <tr id='rbRow14' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["uploadable_media_message"]?></td>
          </tr>
          <tr id='rbRow15' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
          <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='rbRow16' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning"><b><?php echo $_lang["uploadable_flash_title"]?></b></td>
            <td>
              <input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="upload_flash" value="<?php echo isset($upload_flash) ? $upload_flash : "swf,fla" ; ?>" />
            </td>
          </tr>
          <tr id='rbRow17' class='row3' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["uploadable_flash_message"]?></td>
          </tr>
          <tr id='rbRow18' style="display: <?php echo $use_browser==1 ? $displayStyle : 'none' ; ?>">
          <td colspan="2"><div class='split'></div></td>
          </tr>

          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["use_editor_title"]?></b></td>
            <td> <input onchange="documentDirty=true;" type="radio" name="use_editor" value="1" <?php echo ($use_editor=='1' || !isset($use_editor)) ? 'checked="checked"' : "" ; ?> onclick="showHide(/editorRow/, 1); checkCustomIcons();" />
              <?php echo $_lang["yes"]?><br />
              <input onchange="documentDirty=true;" type="radio" name="use_editor" value="0" <?php echo $use_editor=='0' ? 'checked="checked"' : "" ; ?> onclick="showHide(/editorRow/, 0);" />
              <?php echo $_lang["no"]?> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["use_editor_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          
          <?php if(!isset($use_editor)) $use_editor=1; ?>
          
          <tr id='editorRow0' class="row3" style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning"><b><?php echo $_lang["which_editor_title"]?></b></td>
            <td>
				<select name="which_editor" onchange="documentDirty=true;">
					<?php
						// invoke OnRichTextEditorRegister event
						$evtOut = $modx->invokeEvent("OnRichTextEditorRegister");
						echo "<option value='none'".($which_editor=='none' ? " selected='selected'" : "").">".$_lang["none"]."</option>\n";
						if(is_array($evtOut)) for($i=0;$i<count($evtOut);$i++) {
							$editor = $evtOut[$i];
							echo "<option value='$editor'".($which_editor==$editor ? " selected='selected'" : "").">$editor</option>\n";
						}
					?>
				</select>
			</td>
          </tr>
          <tr id='editorRow1' class="row3" style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["which_editor_message"]?></td>
          </tr>
          <tr id='editorRow3' class="row3" style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='editorRow4' class="row3" style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning"><b><?php echo $_lang["fe_editor_lang_title"]?></b></td>
            <td> <select name="fe_editor_lang" size="1" class="inputBox" onchange="documentDirty=true;">
                <?php
	$dir = dir("includes/lang");

	while ($file = $dir->read()) {
		if(strpos($file, ".inc.php")>0) {
			$endpos = strpos ($file, ".");
			$languagename = substr($file, 0, $endpos);
			$selectedtext = $languagename==$fe_editor_lang ? "selected='selected'" : "" ;
?>
                <option value="<?php echo $languagename; ?>" <?php echo $selectedtext; ?>><?php echo ucwords(str_replace("_", " ", $languagename)); ?></option>
                <?php
		}
	}
	$dir->close();
?>
              </select> </td>
          </tr>
          <tr id='editorRow5' class="row3" style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["fe_editor_lang_message"]?></td>
          </tr>
          <tr id='editorRow2' class="row3" style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr id='editorRow14' class="row3" style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td nowrap class="warning"><b><?php echo $_lang["editor_css_path_title"]?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="editor_css_path" value="<?php echo isset($editor_css_path) ? $editor_css_path : "" ; ?>" />
			</td>
          </tr>
          <tr id='editorRow15' class='row3' style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["editor_css_path_message"]?></td>
          </tr>
		  <tr id='editorRow16' class="row3" style="display: <?php echo $use_editor==1 ? $displayStyle : 'none' ; ?>">
            <td colspan="2"><div class='split'></div></td>
          </tr>
		  <tr class='row1'>
            <td colspan="2">
		        <?php
					// invoke OnInterfaceSettingsRender event
					$evtOut = $modx->invokeEvent("OnInterfaceSettingsRender");
					if(is_array($evtOut)) echo implode("",$evtOut);
		        ?>
            </td>
          </tr>
        </table>
      </div>

      <!-- Miscellaneous settings -->
      <div class="tab-page" id="tabPage7">
        <h2 class="tab"><?php echo $_lang["settings_misc"] ?></h2>
        <script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabPage7" ) );</script>
        <table border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["filemanager_path_title"]?></b></td>
            <td><input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="filemanager_path" value="<?php echo isset($filemanager_path) ? $filemanager_path : $base_path; ?>" />
              <br /> </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["filemanager_path_message"]?></td>
          </tr>
          <tr>
          <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["uploadable_files_title"]?></b></td>
            <td>
              <input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="upload_files" value="<?php echo isset($upload_files) ? $upload_files : "txt,php,html,htm,xml,js,css,cache,zip,gz,rar,z,tgz,tar,htaccess,pdf" ; ?>" />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["uploadable_files_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["upload_maxsize_title"]?></b></td>
            <td>
              <input onchange="documentDirty=true;" type='text' maxlength='255' style="width: 250px;" name="upload_maxsize" value="<?php echo isset($upload_maxsize) ? $upload_maxsize : "1048576" ; ?>" />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["upload_maxsize_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["new_file_permissions_title"]?></b></td>
            <td>
              <input onchange="documentDirty=true;" type='text' maxlength='4' style="width: 50px;" name="new_file_permissions" value="<?php echo isset($new_file_permissions) ? $new_file_permissions : "0644" ; ?>" />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["new_file_permissions_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><b><?php echo $_lang["new_folder_permissions_title"]?></b></td>
            <td>
              <input onchange="documentDirty=true;" type='text' maxlength='4' style="width: 50px;" name="new_folder_permissions" value="<?php echo isset($new_folder_permissions) ? $new_folder_permissions : "0755" ; ?>" />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["new_folder_permissions_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
		  <tr class='row1'>
            <td colspan="2">
		        <?php
					// invoke OnMiscSettingsRender event
					$evtOut = $modx->invokeEvent("OnMiscSettingsRender");
					if(is_array($evtOut)) echo implode("",$evtOut);
		        ?>
            </td>
          </tr>
        </table>
      </div>
    </div>
</div>
</form>
