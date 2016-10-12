<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('settings')) {
    $modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

// check to see the edit settings page isn't locked
$rs = $modx->db->select('username', $modx->getFullTableName('active_users'), "action=17 AND internalKey!='".$modx->getLoginUserID()."'");
    if ($username = $modx->db->getValue($rs)) {
            $modx->webAlertAndQuit(sprintf($_lang["lock_settings_msg"],$username));
    }
// end check for lock

// reload system settings from the database.
// this will prevent user-defined settings from being saved as system setting
$settings = array();
$rs = $modx->db->select('setting_name, setting_value', $modx->getFullTableName('system_settings'));
while ($row = $modx->db->getRow($rs)) $settings[$row['setting_name']] = $row['setting_value'];
$settings['filemanager_path'] = preg_replace('@^' . MODX_BASE_PATH . '@', '[(base_path)]', $settings['filemanager_path']);
$settings['rb_base_dir']      = preg_replace('@^' . MODX_BASE_PATH . '@', '[(base_path)]', $settings['rb_base_dir']);
extract($settings, EXTR_OVERWRITE);
if(!isset($site_name))               $site_name='My MODX Site';
if(!isset($site_start))              $site_start=1;
if(!isset($error_page))              $error_page=1;
if(!isset($unauthorized_page))       $unauthorized_page=1;
if(!isset($site_unavailable_page))   $site_unavailable_page='';
if(!isset($top_howmany))             $top_howmany=10;
if(!isset($custom_contenttype))      $custom_contenttype='text/css,text/html,text/javascript,text/plain,text/xml';
if(!isset($docid_incrmnt_method))    $docid_incrmnt_method=0;
if(!isset($valid_hostnames))         $valid_hostnames='';
if(!isset($enable_filter))           $enable_filter = 0;
if(!isset($rss_url_news))            $rss_url_news=$_lang["rss_url_news_default"];
if(!isset($rss_url_security))        $rss_url_security = $_lang["rss_url_security_default"];
if(!isset($friendly_urls))           $friendly_urls = 0;
if(!isset($friendly_url_prefix))     $friendly_url_prefix = 'p';
if(!isset($friendly_url_suffix))     $friendly_url_suffix = '.html';
if(!isset($make_folders))            $make_folders = '0';
if(!isset($seostrict))               $seostrict = '0';
if(!isset($aliaslistingfolder))      $aliaslistingfolder = '0';
if(!isset($check_files_onlogin))     $check_files_onlogin="index.php\n.htaccess\nmanager/index.php\nmanager/includes/config.inc.php";
if(!isset($use_captcha))             $use_captcha = 0;
if(!isset($pwd_hash_algo))           $pwd_hash_algo = 0;
if(!isset($rb_base_url))             $rb_base_url="{$site_url}assets/";
if(!isset($resource_tree_node_name)) $resource_tree_node_name = 'pagetitle';
if(!isset($udperms_allowroot))       $udperms_allowroot = 0;
if(!isset($failed_login_attempts))   $failed_login_attempts = 3;
if(!isset($blocked_minutes))         $blocked_minutes = 60;
if(!isset($error_reporting))         $error_reporting = '1';
if(!isset($send_errormail))          $send_errormail='0';
if(!isset($enable_bindings))         $enable_bindings=0;
if(!isset($captcha_words))           $captcha_words=$_lang["captcha_words_default"];
if(!isset($emailsender))             $emailsender='you@example.com';
if(!isset($smtp_host))               $smtp_host='smtp.example.com';
if(!isset($smtp_port))               $smtp_port=25;
if(!isset($smtp_username))           $smtp_username=$emailsender;
if(!isset($emailsubject))            $emailsubject=$_lang["emailsubject_default"];
if(!isset($signupemail_message))     $signupemail_message=$_lang["system_email_signup"];
if(!isset($websignupemail_message))  $websignupemail_message=$_lang["system_email_websignup"];
if(!isset($webpwdreminder_message))  $webpwdreminder_message=$_lang["system_email_webreminder"];
if(!isset($warning_visibility))      $warning_visibility=1;
if(!isset($tree_page_click))         $tree_page_click=3;
if(!isset($use_breadcrumbs))         $use_breadcrumbs=0;
if(!isset($remember_last_tab))       $remember_last_tab=0;
if(!isset($tree_show_protected))     $tree_show_protected=0;
if(!isset($show_meta))               $show_meta=0;
if(!isset($datepicker_offset))       $datepicker_offset=-10;
if(!isset($number_of_logs))          $number_of_logs=100;
if(!isset($mail_check_timeperiod))   $mail_check_timeperiod=60;
if(!isset($number_of_messages))      $number_of_messages=40;
if(!isset($number_of_results))       $number_of_results=30;
if(!isset($use_editor))              $use_editor=1;
if(!isset($editor_css_path))         $editor_css_path='';
if(!isset($filemanager_path))        $filemanager_path='[(base_path)]';
if(!isset($upload_files))  $upload_files='txt,php,html,htm,xml,js,css,cache,zip,gz,rar,z,tgz,tar,htaccess,pdf';
if(!isset($upload_images)) $upload_images='jpg,gif,png,ico,bmp,psd';
if(!isset($upload_media))  $upload_media='mp3,wav,au,wmv,avi,mpg,mpeg';
if(!isset($upload_flash))  $upload_flash='swf,fla';
if(!isset($upload_maxsize))          $upload_maxsize='1048576';
if(!isset($new_file_permissions))    $new_file_permissions='0644';
if(!isset($new_folder_permissions))  $new_folder_permissions='0755';
if(!isset($use_browser))             $use_browser=1;
if(!isset($which_browser))           $which_browser='mcpuk';
if(!isset($rb_webuser))              $rb_webuser=0;
if(!isset($rb_base_dir))             $rb_base_dir='[(base_path)]assets/';
if(!isset($clean_uploaded_filename)) $clean_uploaded_filename=0;
if(!isset($strip_image_paths))       $strip_image_paths=0;
if(!isset($maxImageWidth))           $maxImageWidth=1600;
if(!isset($maxImageHeight))          $maxImageHeight=1200;
if(!isset($thumbWidth))              $thumbWidth=150;
if(!isset($thumbHeight))             $thumbHeight=150;
if(!isset($thumbsDir))               $thumbsDir='.thumbs';
if(!isset($jpegQuality))             $jpegQuality=90;
if(!isset($denyZipDownload))         $denyZipDownload=1;
if(!isset($denyExtensionRename))     $denyExtensionRename=1;
if(!isset($showHiddenFiles))         $showHiddenFiles=1;

$displayStyle = ($_SESSION['browser']==='modern') ? 'table-row' : 'block' ;

// load languages and keys
$lang_keys = array();
$dir = dir("includes/lang");
while ($file = $dir->read()) {
    if(strpos($file, ".inc.php")>0) {
        $endpos = strpos ($file, ".");
        $languagename = substr($file, 0, $endpos);
        $lang_keys[$languagename] = get_lang_keys($file);
    }
}
$dir->close();

$isDefaultUnavailableMsg = $site_unavailable_message == $_lang['siteunavailable_message_default'];
$isDefaultUnavailableMsgJs = $isDefaultUnavailableMsg ? 'true' : 'false';
$site_unavailable_message_view = isset($site_unavailable_message) ? $site_unavailable_message : $_lang['siteunavailable_message_default'];

/* check the file paths */
$settings['filemanager_path'] = $filemanager_path = trim($settings['filemanager_path']) == '' ? MODX_BASE_PATH : $settings['filemanager_path'];
$settings['rb_base_dir'] = $rb_base_dir = trim($settings['rb_base_dir']) == '' ? MODX_BASE_PATH.'assets/' : $settings['rb_base_dir'];
$settings['rb_base_url'] =  $rb_base_url = trim($settings['rb_base_url']) == '' ? 'assets/' : $settings['rb_base_url'];

?>
<style type="text/css">
  table th {text-align:left; vertical-align:top;}
</style>
<script type="text/javascript">
jQuery(function(){
    jQuery('#furlRowOn').change(function()    {jQuery('.furlRow').fadeIn();});
    jQuery('#furlRowOff').change(function()   {jQuery('.furlRow').fadeOut();});
    jQuery('#udPermsOn').change(function()    {jQuery('.udPerms').slideDown();});
    jQuery('#udPermsOff').change(function()   {jQuery('.udPerms').slideUp();});
    jQuery('#editorRowOn').change(function()  {jQuery('.editorRow').slideDown();});
    jQuery('#editorRowOff').change(function() {jQuery('.editorRow').slideUp();});
    jQuery('#rbRowOn').change(function()      {jQuery('.rbRow').fadeIn();});
    jQuery('#rbRowOff').change(function()     {jQuery('.rbRow').fadeOut();});
});
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
/**
 * @param element el were language selection comes from
 * @param string lkey language key to look up
 * @param id elupd html element to update with results
 * @param string default_str default value of string for loaded manager language - allows some level of confirmation of change from default
 */
function confirmLangChange(el, lkey, elupd){
    lang_current = document.getElementById(elupd).value;
    lang_default = document.getElementById(lkey+'_hidden').value;
    changed = lang_current != lang_default;
    proceed = true;
    if(changed) {
        proceed = confirm('<?php echo $_lang['confirm_setting_language_change']; ?>');
    }
    if(proceed) {
        //document.getElementById(elupd).value = '';
        lang = el.options[el.selectedIndex].value;
        var myAjax = new Ajax('index.php?a=118', {
            method: 'post',
            data: 'action=get&lang='+lang+'&key='+lkey
        }).request();
        myAjax.addEvent('onComplete', function(resp){
            document.getElementById(elupd).value = resp;
        });
    }
}

</script>
<form name="settings" action="index.php?a=30" method="post">

    <h1 class="pagetitle">
  <span class="pagetitle-icon">
    <i class="fa fa-sliders"></i>
  </span>
  <span class="pagetitle-text">
    <?php echo $_lang['settings_title']; ?>
  </span>
    </h1>
    <div id="actions">
          <ul class="actionButtons">
              <li id="Button1" class="transition">
                <a href="#" onclick="documentDirty=false; document.settings.submit();">
                    <img src="<?php echo $_style["icons_save"]?>" /> <?php echo $_lang['save']; ?>
                </a>
              </li>
              <li id="Button5" class="transition">
                <a href="#" onclick="documentDirty=false;document.location.href='index.php?a=2';">
                    <img src="<?php echo $_style["icons_cancel"]?>" /> <?php echo $_lang['cancel']; ?>
                </a>
              </li>
        </ul>
    </div>

<div style="margin: 0 10px 0 20px">
    <input type="hidden" name="site_id" value="<?php echo $site_id; ?>" />
    <input type="hidden" name="settings_version" value="<?php echo $modx->getVersionData('version'); ?>" />
    <!-- this field is used to check site settings have been entered/ updated after install or upgrade -->
    <?php if(!isset($settings_version) || $settings_version!=$modx->getVersionData('version')) { ?>
    <div class='sectionBody'><p><?php echo $_lang['settings_after_install']; ?></p></div>
    <?php } ?>
    <script type="text/javascript" src="media/script/tabpane.js"></script>
    <div class="tab-pane" id="settingsPane">
      <script type="text/javascript">
        tpSettings = new WebFXTabPane( document.getElementById( "settingsPane" ), <?php echo $modx->config['remember_last_tab'] == 1 ? 'true' : 'false'; ?> );
    </script>

    <!-- Site Settings -->
      <div class="tab-page" id="tabPage2">
        <h2 class="tab"><?php echo $_lang["settings_site"] ?></h2>
        <script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabPage2" ) );</script>
        <table border="0" cellspacing="0" cellpadding="3">
            <tr>
              <td nowrap class="warning"><?php echo $modx->htmlspecialchars($_lang["sitename_title"]) ?></td>
              <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 200px;" name="site_name" value="<?php echo $modx->htmlspecialchars($site_name); ?>" /></td>
            </tr>
            <tr>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php echo $_lang["sitename_message"] ?></td>
            </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["language_title"]?></td>
            <td> <select name="manager_language" size="1" class="inputBox" onchange="documentDirty=true;">
<?php echo get_lang_options(null, $manager_language);?>
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
            <td nowrap class="warning"><?php echo $_lang["charset_title"]?></td>
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
            <td nowrap class="warning"><?php echo $_lang["xhtml_urls_title"] ?></td>
            <td>
                <?php echo wrap_label($_lang["yes"],form_radio('xhtml_urls', 1));?><br />
                <?php echo wrap_label($_lang["no"], form_radio('xhtml_urls', 0));?>
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["xhtml_urls_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["sitestart_title"] ?></td>
            <td><input onchange="documentDirty=true;" type="text" maxlength="10" size="5" name="site_start" value="<?php echo $site_start; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["sitestart_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["errorpage_title"] ?></td>
            <td><input onchange="documentDirty=true;" type="text" maxlength="10" size="5" name="error_page" value="<?php echo $error_page; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["errorpage_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["unauthorizedpage_title"] ?></td>
            <td><input onchange="documentDirty=true;" type="text" maxlength="10" size="5" name="unauthorized_page" value="<?php echo $unauthorized_page; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["unauthorizedpage_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["sitestatus_title"] ?></td>
            <td>
                <?php echo wrap_label($_lang["online"],  form_radio('site_status', 1));?><br />
                <?php echo wrap_label($_lang["offline"], form_radio('site_status', 0));?>
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["sitestatus_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><?php echo $_lang["siteunavailable_page_title"] ?></td>
            <td><input onchange="documentDirty=true;" name="site_unavailable_page" type="text" maxlength="10" size="5" value="<?php echo $site_unavailable_page; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["siteunavailable_page_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><?php echo $_lang["siteunavailable_title"] ?>
              <br />
              <p><?php echo $_lang["update_settings_from_language"]; ?></p>
              <select name="reload_site_unavailable" id="reload_site_unavailable_select" onchange="confirmLangChange(this, 'siteunavailable_message_default', 'site_unavailable_message_textarea');">
<?php echo get_lang_options('siteunavailable_message_default');?>
              </select>
            </td>
            <td> <textarea name="site_unavailable_message" id="site_unavailable_message_textarea" style="width:100%; height: 120px;"><?php echo $site_unavailable_message_view; ?></textarea>
                <input type="hidden" name="siteunavailable_message_default" id="siteunavailable_message_default_hidden" value="<?php echo addslashes($_lang['siteunavailable_message_default']);?>" />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang['siteunavailable_message'];?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>

          <tr>
            <td nowrap class="warning" valign="top"><?php echo $_lang["track_visitors_title"] ?></td>
            <td>
                <?php echo wrap_label($_lang["yes"],form_radio('track_visitors', 1));?><br />
                <?php echo wrap_label($_lang["no"],form_radio('track_visitors', 0));?>
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["track_visitors_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><?php echo $_lang["top_howmany_title"] ?></td>
            <td><input onchange="documentDirty=true;" type="text" maxlength="50" size="5" name="top_howmany" value="<?php echo $top_howmany; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["top_howmany_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
            <tr>
                <td nowrap class="warning" valign="top"><?php echo $_lang["defaulttemplate_logic_title"];?></td>
                <td>
                    <p><?php echo $_lang["defaulttemplate_logic_general_message"];?></p>
                    <input type="radio" name="auto_template_logic" value="system"<?php if($auto_template_logic == 'system') {echo " checked='checked'";}?>/> <?php echo $_lang["defaulttemplate_logic_system_message"]; ?><br />
                    <input type="radio" name="auto_template_logic" value="parent"<?php if($auto_template_logic == 'parent') {echo " checked='checked'";}?>/> <?php echo $_lang["defaulttemplate_logic_parent_message"]; ?><br />
                    <input type="radio" name="auto_template_logic" value="sibling"<?php if($auto_template_logic == 'sibling') {echo " checked='checked'";}?>/> <?php echo $_lang["defaulttemplate_logic_sibling_message"]; ?><br />
                </td>
            </tr>
            <tr>
                <td colspan="2"><div class='split'></div></td>
            </tr>
          <tr>
            <td nowrap class="warning" valign="top"><?php echo $_lang["defaulttemplate_title"] ?></td>
            <td>
            <?php
                $rs = $modx->db->select(
                    't.templatename, t.id, c.category',
                    $modx->getFullTableName('site_templates')." AS t
                        LEFT JOIN ".$modx->getFullTableName('categories')." AS c ON t.category = c.id",
                    "",
                    'c.category, t.templatename ASC'
                    );
            ?>
              <select name="default_template" class="inputBox" onchange="documentDirty=true;wrap=document.getElementById('template_reset_options_wrapper');if(this.options[this.selectedIndex].value != '<?php echo $default_template;?>'){wrap.style.display='block';}else{wrap.style.display='none';}" style="width:150px">
                <?php
                
                $currentCategory = '';
                                while ($row = $modx->db->getRow($rs)) {
                    $thisCategory = $row['category'];
                    if($thisCategory == null) {
                        $thisCategory = $_lang["no_category"];
                    }
                    if($thisCategory != $currentCategory) {
                        if($closeOptGroup) {
                            echo "\t\t\t\t\t</optgroup>\n";
                        }
                        echo "\t\t\t\t\t<optgroup label=\"$thisCategory\">\n";
                        $closeOptGroup = true;
                    } else {
                        $closeOptGroup = false;
                    }
                    
                    $selectedtext = $row['id'] == $default_template ? ' selected="selected"' : '';
                    if ($selectedtext) {
                        $oldTmpId = $row['id'];
                        $oldTmpName = $row['templatename'];
                    }
                    
                    echo "\t\t\t\t\t".'<option value="'.$row['id'].'"'.$selectedtext.'>'.$row['templatename']."</option>\n";
                    $currentCategory = $thisCategory;
                }
                if($thisCategory != '') {
                    echo "\t\t\t\t\t</optgroup>\n";
                }
?>
              </select>
                  <br />
                <div id="template_reset_options_wrapper" style="display:none;">
                    <input type="radio" name="reset_template" value="1" /> <?php echo $_lang["template_reset_all"]; ?><br />
                    <input type="radio" name="reset_template" value="2" /> <?php echo sprintf($_lang["template_reset_specific"],$oldTmpName); ?>
                </div>
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
            <td nowrap class="warning" valign="top"><?php echo $_lang["defaultpublish_title"] ?></td>
            <td>
                <?php echo wrap_label($_lang["yes"],form_radio('publish_default', 1));?><br />
                <?php echo wrap_label($_lang["no"],form_radio('publish_default', 0));?>
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["defaultpublish_message"] ?></td>
          </tr>

          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><?php echo $_lang["defaultcache_title"] ?></td>
            <td>
                <?php echo wrap_label($_lang["yes"],form_radio('cache_default', 1));?><br />
                <?php echo wrap_label($_lang["no"],form_radio('cache_default', 0));?>
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["defaultcache_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><?php echo $_lang["defaultsearch_title"] ?></td>
            <td>
                <?php echo wrap_label($_lang["yes"],form_radio('search_default', 1));?><br />
                <?php echo wrap_label($_lang["no"],form_radio('search_default', 0));?>
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["defaultsearch_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><?php echo $_lang["defaultmenuindex_title"] ?></td>
            <td>
                <?php echo wrap_label($_lang["yes"],form_radio('auto_menuindex', 1));?><br />
                <?php echo wrap_label($_lang["no"],form_radio('auto_menuindex', 0));?>
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["defaultmenuindex_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><?php echo $_lang["custom_contenttype_title"] ?></td>
            <td><input name="txt_custom_contenttype" type="text" maxlength="100" style="width: 200px;height:100px" value="" /> <input type="button" value="<?php echo $_lang["add"]; ?>" onclick='addContentType()' /><br />
            <table border="0" cellspacing="0" cellpadding="0"><tr><td valign="top">
            <select name="lst_custom_contenttype" style="width:200px;" size="5">
            <?php
                $ct = explode(",",$custom_contenttype);
                for($i=0;$i<count($ct);$i++) {
                    echo "<option value=\"".$ct[$i]."\">".$ct[$i]."</option>";
                }
            ?>
            </select>
            <input name="custom_contenttype" type="hidden" value="<?php echo $custom_contenttype; ?>" />
            </td><td valign="top">&nbsp;<input name="removecontenttype" type="button" value="<?php echo $_lang["remove"]; ?>" onclick='removeContentType()' /></td></tr></table>
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["custom_contenttype_message"] ?></td>
          </tr>
<tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
<tr>
    <td nowrap class="warning" valign="top"><?php echo $_lang["docid_incrmnt_method_title"] ?></td>
    <td>
    <label><input type="radio" name="docid_incrmnt_method" value="0"
            <?php echo ($docid_incrmnt_method=='0') ? 'checked="checked"' : "" ; ?> />
            <?php echo $_lang["docid_incrmnt_method_0"]?></label><br />
            
    <label><input type="radio" name="docid_incrmnt_method" value="1"
            <?php echo ($docid_incrmnt_method=='1') ? 'checked="checked"' : "" ; ?> />
            <?php echo $_lang["docid_incrmnt_method_1"]?></label><br />
    <label><input type="radio" name="docid_incrmnt_method" value="2"
            <?php echo ($docid_incrmnt_method=='2') ? 'checked="checked"' : "" ; ?> />
            <?php echo $_lang["docid_incrmnt_method_2"]?></label><br />
    </td>
</tr>
<tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>

<tr>
    <td nowrap class="warning"><?php echo $_lang["cache_type_title"] ?></td>
  <td>
    <?php echo wrap_label($_lang["cache_type_1"],form_radio('cache_type', 1));?><br />
    <?php echo wrap_label($_lang["cache_type_2"], form_radio('cache_type', 2));?>
  </td>
</tr>

          <tr>
            <td colspan="2"><div class='split'></div></td>
            </tr>
            <tr>
              <td nowrap class="warning"><?php echo $_lang["serveroffset_title"] ?></td>
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
              <td nowrap class="warning"><?php echo $_lang["server_protocol_title"] ?></td>
              <td>
                <?php echo wrap_label($_lang["server_protocol_http"],form_radio('server_protocol', 'http'));?><br />
                <?php echo wrap_label($_lang["server_protocol_https"], form_radio('server_protocol', 'https'));?>
              </td>
            </tr>
            <tr>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php echo $_lang["server_protocol_message"] ?></td>
            </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
            <tr>
              <td nowrap class="warning"><?php echo $_lang["validate_referer_title"] ?></td>
              <td>
                <?php echo wrap_label($_lang["yes"],form_radio('validate_referer', 1));?><br />
                <?php echo wrap_label($_lang["no"], form_radio('validate_referer', 0));?>
              </td>
            </tr>
            <tr>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php echo $_lang["validate_referer_message"] ?></td>
            </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
            <tr>
              <td nowrap class="warning"><?php echo $_lang["valid_hostnames_title"] ?></td>
              <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 200px;" name="valid_hostnames" value="<?php echo $modx->htmlspecialchars($valid_hostnames); ?>" /></td>
            </tr>
            <tr>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php echo $_lang["valid_hostnames_message"] ?></td>
            </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
            <tr>
              <td nowrap class="warning"><?php echo $_lang["enable_filter_title"] ?></td>
              <td >
				<?php
					// Check if PHX is enabled
					$modx->invokeEvent("OnParseDocument");
					if(class_exists('PHxParser')) {
						$disabledFilters = 1;
						echo '<b>'.$_lang["enable_filter_phx_warning"].'</b><br/>';
					}
				?>
                <?php echo wrap_label($_lang["yes"],form_radio('enable_filter', 1, '', $disabledFilters));?><br />
                <?php echo wrap_label($_lang["no"], form_radio('enable_filter', 0, '', $disabledFilters));?>
              </td>
            </tr>
            <tr>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php echo $_lang["enable_filter_message"]; ?></td>
            </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
            <tr>
              <td nowrap class="warning"><?php echo $_lang["rss_url_news_title"] ?></td>
              <td ><input onchange="documentDirty=true;" type="text" maxlength="350" style="width: 350px;" name="rss_url_news" value="<?php echo $rss_url_news; ?>" /></td>
            </tr>
            <tr>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php echo $_lang["rss_url_news_message"] ?></td>
            </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
            <tr>
              <td nowrap class="warning"><?php echo $_lang["rss_url_security_title"] ?></td>
              <td ><input onchange="documentDirty=true;" type="text" maxlength="350" style="width: 350px;" name="rss_url_security" value="<?php echo $rss_url_security; ?>" /></td>
            </tr>
            <tr>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php echo $_lang["rss_url_security_message"] ?></td>
            </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
          <tr>
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
    <td nowrap class="warning" valign="top"><?php echo $_lang["friendlyurls_title"] ?></td>
    <td>
        <?php echo wrap_label($_lang["yes"],form_radio('friendly_urls', 1, 'id="furlRowOn"'));?><br />
        <?php echo wrap_label($_lang["no"], form_radio('friendly_urls', 0, 'id="furlRowOff"'));?>
    </td>
  </tr>
  <tr>
    <td width="200">&nbsp;</td>
    <td class='comment'><?php echo $_lang["friendlyurls_message"] ?></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td colspan="2"><div class='split'></div></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td nowrap class="warning" valign="top"><?php echo $_lang["friendlyurlsprefix_title"] ?></td>
    <td><input onchange="documentDirty=true;" type="text" maxlength="50" style="width: 200px;" name="friendly_url_prefix" value="<?php echo $friendly_url_prefix; ?>" /></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td width="200">&nbsp;</td>
    <td class='comment'><?php echo $_lang["friendlyurlsprefix_message"] ?></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td colspan="2"><div class='split'></div></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td nowrap class="warning" valign="top"><?php echo $_lang["friendlyurlsuffix_title"] ?></td>
    <td><input onchange="documentDirty=true;" type="text" maxlength="50" style="width: 200px;" name="friendly_url_suffix" value="<?php echo $friendly_url_suffix; ?>" /></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td width="200">&nbsp;</td>
    <td class='comment'><?php echo $_lang["friendlyurlsuffix_message"] ?></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td colspan="2"><div class='split'></div></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <th><?php echo $_lang['make_folders_title'] ?></th>
    <td>
      <?php echo wrap_label($_lang["yes"],form_radio('make_folders','1'));?><br />
      <?php echo wrap_label($_lang["no"],form_radio('make_folders','0'));?>
</td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["make_folders_message"] ?></td>
          </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
  <td colspan="2"><div class='split'></div></td>
  </tr>
      
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <th><?php echo $_lang['seostrict_title'] ?></th>
    <td>
      <?php echo wrap_label($_lang["yes"],form_radio('seostrict','1'));?><br />
      <?php echo wrap_label($_lang["no"],form_radio('seostrict','0'));?>
     </td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["seostrict_message"] ?></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
  <td colspan="2"><div class='split'></div></td>
  </tr>

  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
      <th><?php echo $_lang['aliaslistingfolder_title'] ?></th>
      <td>
          <?php echo wrap_label($_lang["yes"],form_radio('aliaslistingfolder','1'));?><br />
          <?php echo wrap_label($_lang["no"],form_radio('aliaslistingfolder','0'));?>
      </td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
      <td width="200">&nbsp;</td>
      <td class='comment'><?php echo $_lang["aliaslistingfolder_message"] ?></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
      <td colspan="2"><div class='split'></div></td>
  </tr>

  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td nowrap class="warning" valign="top"><?php echo $_lang["friendly_alias_title"] ?></td>
    <td>
      <?php echo wrap_label($_lang["yes"],form_radio('friendly_alias_urls','1'));?><br />
      <?php echo wrap_label($_lang["no"],form_radio('friendly_alias_urls','0'));?>
    </td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td width="200">&nbsp;</td>
    <td class='comment'><?php echo $_lang["friendly_alias_message"] ?></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td colspan="2"><div class='split'></div></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td nowrap class="warning" valign="top"><?php echo $_lang["use_alias_path_title"] ?></td>
    <td>
      <?php echo wrap_label($_lang["yes"],form_radio('use_alias_path','1'));?><br />
      <?php echo wrap_label($_lang["no"],form_radio('use_alias_path','0'));?>
    </td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td width="200">&nbsp;</td>
    <td class='comment'><?php echo $_lang["use_alias_path_message"] ?></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td colspan="2"><div class='split'></div></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td nowrap class="warning" valign="top"><?php echo $_lang["duplicate_alias_title"] ?></td>
    <td>
      <?php echo wrap_label($_lang["yes"],form_radio('allow_duplicate_alias','1'));?><br />
      <?php echo wrap_label($_lang["no"],form_radio('allow_duplicate_alias','0'));?>
    </td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td width="200">&nbsp;</td>
    <td class='comment'><?php echo $_lang["duplicate_alias_message"] ?></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td colspan="2"><div class='split'></div></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td nowrap class="warning" valign="top"><?php echo $_lang["automatic_alias_title"] ?></td>
    <td>
      <?php echo wrap_label($_lang["yes"],form_radio('automatic_alias','1'));?><br />
      <?php echo wrap_label($_lang["no"],form_radio('automatic_alias','0'));?>
    </td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td width="200">&nbsp;</td>
    <td class='comment'><?php echo $_lang["automatic_alias_message"] ?></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
    <td colspan="2"><div class='split'></div></td>
  </tr>
  <tr class="furlRow" <?php echo showHide($friendly_urls==1);?>>
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
            <th><?php echo $_lang["check_files_onlogin_title"] ?></th>
            <td>
              <textarea name="check_files_onlogin"><?php echo $check_files_onlogin;?></textarea><br />
                
        </td>
        </tr>
        <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'>  <?php echo $_lang["check_files_onlogin_message"] ?></td>
      </tr>
        <tr>
          <td colspan="2"><div class='split'></div></td>
        </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["udperms_title"] ?></td>
            <td>
                <?php echo wrap_label($_lang["yes"],form_radio('use_udperms', 1, 'id="udPermsOn"'));?><br />
                <?php echo wrap_label($_lang["no"], form_radio('use_udperms', 0, 'id="udPermsOff"'));?>
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["udperms_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="udPerms" <?php echo showHide($use_udperms==1);?>>
            <td nowrap class="warning"><?php echo $_lang["udperms_allowroot_title"] ?></td>
            <td>
              <input type="radio" name="udperms_allowroot" value="1" <?php echo $udperms_allowroot=='1' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input type="radio" name="udperms_allowroot" value="0" <?php echo $udperms_allowroot=='0' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?>
            </td>
          </tr>
          <tr class="udPerms" <?php echo showHide($use_udperms==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["udperms_allowroot_message"] ?></td>
          </tr>
          <tr class="udPerms" <?php echo showHide($use_udperms==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="udPerms">
            <td nowrap class="warning"><?php echo $_lang["failed_login_title"] ?></td>
            <td><input type="text" name="failed_login_attempts" style="width:50px" value="<?php echo $failed_login_attempts; ?>" /></td>
          </tr>
          <tr class="udPerms">
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["failed_login_message"] ?></td>
          </tr>
          <tr class="udPerms">
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["blocked_minutes_title"] ?></td>
            <td><input type="text" name="blocked_minutes" style="width:100px" value="<?php echo $blocked_minutes; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["blocked_minutes_message"] ?></td>
          </tr>
           <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
    <th><?php echo $_lang['a17_error_reporting_title']; ?></th>
    <td>
      <?php echo wrap_label($_lang['a17_error_reporting_opt0'], form_radio('error_reporting','0'));?><br />
      <?php echo wrap_label($_lang['a17_error_reporting_opt1'], form_radio('error_reporting','1'));?><br />
      <?php echo wrap_label($_lang['a17_error_reporting_opt2'], form_radio('error_reporting','2'));?><br />
      <?php echo wrap_label($_lang['a17_error_reporting_opt99'],form_radio('error_reporting','99'));?>
   </td>
    </tr>
    <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'> <?php echo $_lang['a17_error_reporting_msg'];?></td>
    </tr>
<tr><td colspan="2"><div class='split'></div></td></tr>
<tr>
<th><?php echo $_lang['mutate_settings.dynamic.php6']; ?></th>
<td>
    <?php echo wrap_label($_lang['mutate_settings.dynamic.php7'],form_radio('send_errormail','0'));?><br />
    <?php echo wrap_label('error',form_radio('send_errormail','3'));?><br />
    <?php echo wrap_label('error + warning',form_radio('send_errormail','2'));?><br />
    <?php echo wrap_label('error + warning + information',form_radio('send_errormail','1'));?><br />
<?php echo parsePlaceholder($_lang['mutate_settings.dynamic.php8'],array('emailsender'=>$modx->config['emailsender']));?></td>
</tr>
    
        <tr>
          <td colspan="2"><div class='split'></div></td>
        </tr>
      <tr>
      <th><?php echo $_lang["pwd_hash_algo_title"] ?></th>
      <td>
      <?php
        if(empty($pwd_hash_algo)) $phm['sel']['UNCRYPT'] = 1;
        $phm['e']['BLOWFISH_Y'] = $modx->manager->checkHashAlgorithm('BLOWFISH_Y') ? 0:1;
        $phm['e']['BLOWFISH_A'] = $modx->manager->checkHashAlgorithm('BLOWFISH_A') ? 0:1;
        $phm['e']['SHA512']     = $modx->manager->checkHashAlgorithm('SHA512') ? 0:1;
        $phm['e']['SHA256']     = $modx->manager->checkHashAlgorithm('SHA256') ? 0:1;
        $phm['e']['MD5']        = $modx->manager->checkHashAlgorithm('MD5') ? 0:1;
        $phm['e']['UNCRYPT']    = $modx->manager->checkHashAlgorithm('UNCRYPT') ? 0:1;
      ?>
        <?php echo wrap_label('CRYPT_BLOWFISH_Y (salt &amp; stretch)',form_radio('pwd_hash_algo','BLOWFISH_Y', '', $phm['e']['BLOWFISH_Y']));?><br />
        <?php echo wrap_label('CRYPT_BLOWFISH_A (salt &amp; stretch)',form_radio('pwd_hash_algo','BLOWFISH_A', '', $phm['e']['BLOWFISH_A']));?><br />
        <?php echo wrap_label('CRYPT_SHA512 (salt &amp; stretch)'    ,form_radio('pwd_hash_algo','SHA512'    , '', $phm['e']['SHA512']));?><br />
        <?php echo wrap_label('CRYPT_SHA256 (salt &amp; stretch)'    ,form_radio('pwd_hash_algo','SHA256'    , '', $phm['e']['SHA256']));?><br />
        <?php echo wrap_label('CRYPT_MD5 (salt &amp; stretch)'       ,form_radio('pwd_hash_algo','MD5'       , '', $phm['e']['MD5']));?><br />
        <?php echo wrap_label('UNCRYPT(32 chars salt + SHA-1 hash)'  ,form_radio('pwd_hash_algo','UNCRYPT'  , '', $phm['e']['UNCRYPT']));?>
      </td>
      </tr>
      <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'>  <?php echo $_lang["pwd_hash_algo_message"]?></td>
      </tr>
           <tr>
            <td colspan="2"><div class='split'></div></td>
    </tr>
    <tr>
      <th><?php echo $_lang["enable_bindings_title"] ?></th>
      <td>
        <?php echo wrap_label($_lang["yes"],form_radio('enable_bindings','1'));?><br />
        <?php echo wrap_label($_lang["no"], form_radio('enable_bindings','0'));?>
       
    </td>
    </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'>  <?php echo $_lang["enable_bindings_message"] ?></td>
      </tr>
           <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <?php
              // Check for GD before allowing captcha to be enabled
              $gdAvailable = extension_loaded('gd');
          ?>
<?php
    $gdAvailable = extension_loaded('gd');
    if(!$gdAvailable) $use_captcha = 0;
?>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["captcha_title"] ?></td>
            <td> <input type="radio" name="use_captcha" value="1" <?php echo ($use_captcha==1) ? 'checked="checked"' : "" ; echo (!$gdAvailable)? ' readonly="readonly"' : ''; ?> />
              <?php echo $_lang["yes"]?><br />
              <input type="radio" name="use_captcha" value="0" <?php echo ($use_captcha==0) ? 'checked="checked"' : "" ; echo (!$gdAvailable)? ' readonly="readonly"' : '';?> />
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
            <td nowrap class="warning"><?php echo $_lang["captcha_words_title"] ?>
              <br />
              <p><?php echo $_lang["update_settings_from_language"]; ?></p>
              <select name="reload_captcha_words" id="reload_captcha_words_select" onchange="confirmLangChange(this, 'captcha_words_default', 'captcha_words_input');">
<?php echo get_lang_options('captcha_words_default');?>
              </select>
            </td>
            <td><input type="text" id="captcha_words_input" name="captcha_words" style="width:250px" value="<?php echo $captcha_words; ?>" />
                <input type="hidden" name="captcha_words_default" id="captcha_words_default_hidden" value="<?php echo addslashes($_lang["captcha_words_default"]);?>" />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["captcha_words_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["emailsender_title"] ?></td>
            <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="emailsender" value="<?php echo $emailsender; ?>" /></td>
          </tr>
           <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["emailsender_message"] ?></td>
          </tr>

          <!--for smtp-->

          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["email_method_title"] ?></td>
            <td>
                <?php echo wrap_label($_lang["email_method_mail"],form_radio('email_method','mail' ));?><br />
                <?php echo wrap_label($_lang["email_method_smtp"],form_radio('email_method','smtp' ));?>
            </td>
          </tr>
          <tr>
            <td colspan="2"><div class="split"></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["smtp_auth_title"] ?></td>
            <td>
                <?php echo wrap_label($_lang["yes"],form_radio('smtp_auth','1' ));?><br />
                <?php echo wrap_label($_lang["no"],form_radio('smtp_auth','0' ));?>
            </td>
          </tr>
          <tr>
            <td colspan="2"><div class="split"></div></td>
          </tr>
          
      <tr>
            <td nowrap class="warning"><?php echo $_lang["smtp_secure_title"] ?></td>
            <td >
             <select name="smtp_secure" size="1" class="inputBox">
          <option value="none" ><?php echo $_lang["no"] ?></option>
           <option value="ssl" <?php if($smtp_secure == 'ssl') echo "selected='selected'"; ?> >SSL</option>
          <option value="tls" <?php if($smtp_secure == 'tls') echo "selected='selected'"; ?> >TLS</option>
         </select>
         <br />
          </td>
          </tr>
          <tr>
            <td colspan="2"><div class="split"></div></td>
          </tr>
          
          <tr>
            <td nowrap class="warning"><?php echo $_lang["smtp_host_title"] ?></td>
            <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="smtp_host" value="<?php echo $smtp_host; ?>" /></td>
          </tr>
          <tr>
            <td colspan="2"><div class="split"></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["smtp_port_title"] ?></td>
            <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="smtp_port" value="<?php echo $smtp_port; ?>" /></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["smtp_username_title"] ?></td>
            <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="smtp_username" value="<?php echo $smtp_username; ?>" /></td>
          </tr>
           <tr>
            <td colspan="2"><div class="split"></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["smtp_password_title"] ?></td>
            <td ><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="smtppw" value="********************" autocomplete="off" /></td>
          </tr>
          <tr>
            <td colspan="2"><div class="split"></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["emailsubject_title"] ?>
              <br />
              <p><?php echo $_lang["update_settings_from_language"]; ?></p>
              <select name="reload_emailsubject" id="reload_emailsubject_select" onchange="confirmLangChange(this, 'emailsubject_default', 'emailsubject_field');">
<?php echo get_lang_options('emailsubject_default');?>
              </select>
            </td>
            <td ><input id="emailsubject_field" name="emailsubject" onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" value="<?php echo $emailsubject; ?>" />
                <input type="hidden" name="emailsubject_default" id="emailsubject_default_hidden" value="<?php echo addslashes($_lang['emailsubject_default']);?>" />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["emailsubject_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><?php echo $_lang["signupemail_title"] ?>
              <br />
              <p><?php echo $_lang["update_settings_from_language"]; ?></p>
              <select name="reload_signupemail_message" id="reload_signupemail_message_select" onchange="confirmLangChange(this, 'system_email_signup', 'signupemail_message_textarea');">
<?php echo get_lang_options('system_email_signup');?>
              </select>
            </td>
            <td> <textarea id="signupemail_message_textarea" name="signupemail_message" style="width:100%; height: 120px;"><?php echo $signupemail_message; ?></textarea>
                <input type="hidden" name="system_email_signup_default" id="system_email_signup_hidden" value="<?php echo addslashes($_lang['system_email_signup']);?>" />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["signupemail_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><?php echo $_lang["websignupemail_title"] ?>
              <br />
              <p><?php echo $_lang["update_settings_from_language"]; ?></p>
              <select name="reload_websignupemail_message" id="reload_websignupemail_message_select" onchange="confirmLangChange(this, 'system_email_websignup', 'websignupemail_message_textarea');">
<?php echo get_lang_options('system_email_websignup');?>
              </select>
            </td>
            <td> <textarea id="websignupemail_message_textarea" name="websignupemail_message" style="width:100%; height: 120px;"><?php echo $websignupemail_message; ?></textarea>
                <input type="hidden" name="system_email_websignup_default" id="system_email_websignup_hidden" value="<?php echo addslashes($_lang['system_email_websignup']);?>" />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["websignupemail_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning" valign="top"><?php echo $_lang["webpwdreminder_title"] ?>
              <br />
              <p><?php echo $_lang["update_settings_from_language"]; ?></p>
              <select name="reload_system_email_webreminder_message" id="reload_system_email_webreminder_select" onchange="confirmLangChange(this, 'system_email_webreminder', 'system_email_webreminder_textarea');">
<?php echo get_lang_options('system_email_webreminder');?>
              </select>
            </td>
            <td> <textarea id="system_email_webreminder_textarea" name="webpwdreminder_message" style="width:100%; height: 120px;"><?php echo $webpwdreminder_message; ?></textarea>
                <input type="hidden" name="system_email_webreminder_default" id="system_email_webreminder_hidden" value="<?php echo addslashes($_lang['system_email_webreminder']);?>" />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["webpwdreminder_message"] ?></td>
          </tr>
          <tr>
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
              <td nowrap class="warning"><?php echo $_lang["manager_theme"]?></td>
              <td> <select name="manager_theme" size="1" class="inputBox" onchange="documentDirty=true;document.forms['settings'].theme_refresher.value = Date.parse(new Date())">
               <?php
                  $dir = dir("media/style/");
                  while ($file = $dir->read()) {
                      if($file!="." && $file!=".." && is_dir("media/style/$file") && substr($file,0,1) != '.') {
                          if($file==='common') continue;
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
               <td nowrap class="warning"><?php echo $_lang["warning_visibility"] ?></td>
               <td> <input type="radio" name="warning_visibility" value="0" <?php echo $warning_visibility=='0' ? 'checked="checked"' : ""; ?> />
                 <?php echo $_lang["administrators"]?><br />
                 <input type="radio" name="warning_visibility" value="1" <?php echo ($warning_visibility=='1') ? 'checked="checked"' : ""; ?> />
                 <?php echo $_lang["everybody"]?></td>
             </tr>
             <tr>
               <td width="200">&nbsp;</td>
               <td class='comment'><?php echo $_lang["warning_visibility_message"]?></td>
             </tr>
            
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
             <tr>
                 <td nowrap class="warning"><?php echo $_lang["tree_page_click"] ?></td>
                 <td> <input type="radio" name="tree_page_click" value="27" <?php echo $tree_page_click=='27' ? 'checked="checked"' : ""; ?> />
                   <?php echo $_lang["edit_resource"]?><br />
                   <input type="radio" name="tree_page_click" value="3" <?php echo ($tree_page_click=='3') ? 'checked="checked"' : ""; ?> />
                   <?php echo $_lang["doc_data_title"]?></td>
               </tr>
             <tr>
               <td width="200">&nbsp;</td>
               <td class='comment'><?php echo $_lang["tree_page_click_message"]?></td>
             </tr>

            <tr>
                <td colspan="2"><div class='split'></div></td>
            </tr>
            <tr>
                <td nowrap class="warning"><?php echo $_lang["use_breadcrumbs"] ?></td>
                <td> <input type="radio" name="use_breadcrumbs" value="1" <?php echo $use_breadcrumbs=='1' ? 'checked="checked"' : ""; ?> />
                    <?php echo $_lang["yes"]?><br />
                    <input type="radio" name="use_breadcrumbs" value="0" <?php echo ($use_breadcrumbs=='0') ? 'checked="checked"' : ""; ?> />
                    <?php echo $_lang["no"]?></td>
            </tr>
            <tr>
                <td width="200">&nbsp;</td>
                <td class='comment'><?php echo $_lang["use_breadcrumbs_message"]?></td>
            </tr>

             <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
             <tr>
               <td nowrap class="warning"><?php echo $_lang["remember_last_tab"] ?></td>
               <td>
                 <input type="radio" name="remember_last_tab" value="1" <?php echo $remember_last_tab=='1' ? 'checked="checked"' : ""; ?> />
                 <?php echo $_lang["yes"]?><br />
                 <input type="radio" name="remember_last_tab" value="0" <?php echo ($remember_last_tab=='0') ? 'checked="checked"' : ""; ?> />
                 <?php echo $_lang["no"]?></td>
             </tr>
             <tr>
               <td width="200">&nbsp;</td>
               <td class='comment'><?php echo $_lang["remember_last_tab_message"]?></td>
             </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
<tr>
<th><?php echo $_lang["setting_resource_tree_node_name"] ?></th>
<td>
    <select name="resource_tree_node_name" size="1" class="inputBox">
<?php
    $tpl = '<option value="[+value+]" [+selected+]>[*[+value+]*]</option>' . "\n";
    $option = array('pagetitle','longtitle','menutitle','alias','createdon','editedon','publishedon');
    $output = array();
    foreach($option as $v)
    {
        $selected = ($v==$resource_tree_node_name) ? 'selected' : '';
        $s = array('[+value+]','[+selected+]');
        $r = array($v,$selected);
        $output[] = str_replace($s,$r,$tpl);
    }
    echo implode("\n",$output)
?>
    </select><br />
    
</td>
</tr>
        <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["setting_resource_tree_node_name_desc"]?><br/><b><?php echo $_lang["setting_resource_tree_node_name_desc_add"]?></b></td>
      </tr>
<tr>
  <td colspan="2"><div class='split'></div></td>
</tr>
             <tr>
                 <td nowrap class="warning"><?php echo $_lang["tree_show_protected"] ?></td>
                 <td> <input type="radio" name="tree_show_protected" value="1" <?php echo ($tree_show_protected=='1') ? 'checked="checked" ' : ''; ?>/>
                   <?php echo $_lang["yes"]?><br />
                   <input type="radio" name="tree_show_protected" value="0" <?php echo ($tree_show_protected=='0') ? 'checked="checked" ' : ''; ?>/>
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
                 <td nowrap class="warning"><?php echo $_lang["show_meta"] ?></td>
                 <td> <input type="radio" name="show_meta" value="1" <?php echo $show_meta=='1' ? 'checked="checked"' : ""; ?> />
                   <?php echo $_lang["yes"]?><br />
                   <input type="radio" name="show_meta" value="0" <?php echo ($show_meta=='0') ? 'checked="checked"' : ''; ?> />
                   <?php echo $_lang["no"]?></td>
               </tr>
             <tr>
               <td width="200">&nbsp;</td>
               <td class='comment'><?php echo $_lang["show_meta_message"]?></td>
             </tr>
<tr>
  <td colspan="2"><div class='split'></div></td>
</tr>
<tr>
                 <td nowrap class="warning"><?php echo $_lang["datepicker_offset"] ?></td>
                 <td><input onchange="documentDirty=true;" type="text" maxlength="50" size="5" name="datepicker_offset" value="<?php echo $datepicker_offset; ?>" /></td>
               </tr>
               <tr>
                   <td width="200">&nbsp;</td>
                   <td class='comment'><?php echo $_lang["datepicker_offset_message"]?></td>
             </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
            <tr>
              <td nowrap class="warning"><?php echo $_lang["datetime_format"]?></td>
              <td> <select name="datetime_format" size="1" class="inputBox">
              <?php
                  $datetime_format_list = array('dd-mm-YYYY', 'mm/dd/YYYY', 'YYYY/mm/dd');
                  $str = '';
                  foreach($datetime_format_list as $value)
                  {
                      $selectedtext = ($datetime_format == $value) ? ' selected' : '';
                      $str .= '<option value="' . $value . '"' . $selectedtext . '>';
                      $str .= $value . '</option>' . PHP_EOL;
                  }
                  echo $str;
              ?>
               </select></td>
            </tr>
            <tr>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php echo $_lang["datetime_format_message"]?></td>
            </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["nologentries_title"]?></td>
            <td><input onchange="documentDirty=true;" type="text" maxlength="50" size="5" name="number_of_logs" value="<?php echo $number_of_logs; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["nologentries_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["mail_check_timeperiod_title"] ?></td>
            <td><input type="text" name="mail_check_timeperiod" onchange="documentDirty=true;" size="5" value="<?php echo $mail_check_timeperiod; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["mail_check_timeperiod_message"] ?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["nomessages_title"]?></td>
            <td><input onchange="documentDirty=true;" type="text" maxlength="50" size="5" name="number_of_messages" value="<?php echo $number_of_messages; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["nomessages_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["noresults_title"]?></td>
            <td><input onchange="documentDirty=true;" type="text" maxlength="50" size="5" name="number_of_results" value="<?php echo $number_of_results; ?>" /></td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["noresults_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["use_editor_title"]?></td>
            <td>
                <?php echo wrap_label($_lang["yes"],form_radio('use_editor', 1, 'id="editorRowOn"'));?><br />
                <?php echo wrap_label($_lang["no"], form_radio('use_editor', 0, 'id="editorRowOff"'));?>
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["use_editor_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          
          
          <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
            <td nowrap class="warning"><?php echo $_lang["which_editor_title"]?></td>
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
          <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["which_editor_message"]?></td>
          </tr>
          <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
            <td nowrap class="warning"><?php echo $_lang["fe_editor_lang_title"]?></td>
            <td> <select name="fe_editor_lang" size="1" class="inputBox" onchange="documentDirty=true;">
<?php echo get_lang_options(null, $fe_editor_lang);?>
              </select> </td>
          </tr>
          <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["fe_editor_lang_message"]?></td>
          </tr>
          <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
            <td nowrap class="warning"><?php echo $_lang["editor_css_path_title"]?></td>
            <td><input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="editor_css_path" value="<?php echo $editor_css_path; ?>" />
            </td>
          </tr>
          <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["editor_css_path_message"]?></td>
          </tr>
          <tr class="editorRow" <?php echo showHide($use_editor==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
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
            <td nowrap class="warning"><?php echo $_lang["filemanager_path_title"]?></td>
            <td>
              <?php echo $_lang['default']; ?> <span id="default_filemanager_path">[(base_path)]</span><br />
              <input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="filemanager_path" id="filemanager_path" value="<?php echo $filemanager_path; ?>" /> <input type="button" onclick="reset_path('filemanager_path');" value="<?php echo $_lang["reset"]; ?>" name="reset_filemanager_path">
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["filemanager_path_message"]?></td>
          </tr>
          <tr>
          <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["uploadable_files_title"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="upload_files" value="<?php echo $upload_files; ?>" />
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
            <td nowrap class="warning"><?php echo $_lang["uploadable_images_title"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="upload_images" value="<?php echo $upload_images; ?>" />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["uploadable_images_message"]?></td>
          </tr>
          <tr>
          <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["uploadable_media_title"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="upload_media" value="<?php echo $upload_media; ?>" />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["uploadable_media_message"]?></td>
          </tr>
          <tr>
          <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["uploadable_flash_title"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="upload_flash" value="<?php echo $upload_flash; ?>" />
            </td>
          </tr>
            <tr>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php echo $_lang["uploadable_flash_message"]?></td>
            </tr>
            <tr>
              <td colspan="2"><div class='split'></div></td>
            </tr>
          <tr>
            <td nowrap class="warning"><?php echo $_lang["upload_maxsize_title"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="upload_maxsize" value="<?php echo $upload_maxsize; ?>" />
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
            <td nowrap class="warning"><?php echo $_lang["new_file_permissions_title"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="4" style="width: 50px;" name="new_file_permissions" value="<?php echo $new_file_permissions; ?>" />
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
            <td nowrap class="warning"><?php echo $_lang["new_folder_permissions_title"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="4" style="width: 50px;" name="new_folder_permissions" value="<?php echo $new_folder_permissions; ?>" />
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["new_folder_permissions_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          <tr>
            <td colspan="2">
            </td>
          </tr>
        </table>
      </div>
      
                <!-- KCFinder settings -->
      <div class="tab-page" id="tabPage8">
        <h2 class="tab"><?php echo $_lang["settings_KC"] ?></h2>
        <script type="text/javascript">tpSettings.addTabPage( document.getElementById( "tabPage8" ) );</script>
        <table border="0" cellspacing="0" cellpadding="3">
          <tr>
            <td nowrap class="warning"><?php echo $_lang["rb_title"]?></td>
            <td>
                <?php echo wrap_label($_lang["yes"],form_radio('use_browser', 1, 'id="rbRowOn"'));?><br />
                <?php echo wrap_label($_lang["no"], form_radio('use_browser', 0, 'id="rbRowOff"'));?>
            </td>
          </tr>
          <tr>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["rb_message"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td nowrap class="warning"><?php echo $_lang["which_browser_default_title"]?></td>
            <td>
				<select name="which_browser" size="1" class="inputBox" onchange="documentDirty=true;">
					<?php
					foreach (glob("media/browser/*", GLOB_ONLYDIR) as $dir) {
						$dir = str_replace('\\', '/', $dir);
						$browser_name = substr($dir, strrpos($dir, '/') + 1);
						$selected = $browser_name == $which_browser ? ' selected="selected"' : '';
						echo '<option value="' . $browser_name . '"' . $selected . '>' . "{$browser_name}</option>\n";
					}
					?>
				</select>
            </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td width="200">&nbsp;</td>
            <td class="comment"><?php echo $_lang["which_browser_default_msg"]?></td>
          </tr>
          <tr>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td nowrap class="warning"><?php echo $_lang["rb_webuser_title"]?></td>
            <td>
              <input type="radio" name="rb_webuser" value="1" <?php echo $rb_webuser=='1' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input type="radio" name="rb_webuser" value="0" <?php echo $rb_webuser=='0' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?>
            </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td width="200">&nbsp;</td>
            <td class="comment"><?php echo $_lang["rb_webuser_message"]?></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td nowrap class="warning"><?php echo $_lang["rb_base_dir_title"]?></td>
            <td>
                <?php echo $_lang['default']; ?> <span id="default_rb_base_dir">[(base_path)]assets/</span><br />
                <input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="rb_base_dir" id="rb_base_dir" value="<?php echo $rb_base_dir; ?>" /> <input type="button" onclick="reset_path('rb_base_dir');" value="<?php echo $_lang["reset"]; ?>" name="reset_rb_base_dir">
            </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["rb_base_dir_message"]?></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td nowrap class="warning"><?php echo $_lang["rb_base_url_title"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="rb_base_url" value="<?php echo $rb_base_url; ?>" />
              </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["rb_base_url_message"]?></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
              <td nowrap class="warning"><?php echo $_lang["clean_uploaded_filename"]?></td>
              <td>
                <input type="radio" name="clean_uploaded_filename" value="1" <?php echo $clean_uploaded_filename=='1' ? 'checked="checked"' : "" ; ?> />
                <?php echo $_lang["yes"]?><br />
                <input type="radio" name="clean_uploaded_filename" value="0" <?php echo $clean_uploaded_filename=='0' ? 'checked="checked"' : "" ; ?> />
                <?php echo $_lang["no"]?>
              </td>
          </tr>
            <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
              <td width="200">&nbsp;</td>
              <td class='comment'><?php echo $_lang["clean_uploaded_filename_message"];?></td>
            </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
          <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td nowrap class="warning"><?php echo $_lang["settings_strip_image_paths_title"]?></td>
            <td>
              <input type="radio" name="strip_image_paths" value="1" <?php echo $strip_image_paths=='1' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["yes"]?><br />
              <input type="radio" name="strip_image_paths" value="0" <?php echo $strip_image_paths=='0' ? 'checked="checked"' : "" ; ?> />
              <?php echo $_lang["no"]?>
            </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td width="200">&nbsp;</td>
            <td class="comment"><?php echo $_lang["settings_strip_image_paths_message"]?></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td nowrap class="warning"><?php echo $_lang["maxImageWidth"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="4" style="width: 50px;" name="maxImageWidth" value="<?php echo $maxImageWidth; ?>" />
            </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["maxImageWidth_message"]?></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td nowrap class="warning"><?php echo $_lang["maxImageHeight"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="4" style="width: 50px;" name="maxImageHeight" value="<?php echo $maxImageHeight; ?>" />
            </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["maxImageHeight_message"]?></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td nowrap class="warning"><?php echo $_lang["thumbWidth"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="4" style="width: 50px;" name="thumbWidth" value="<?php echo $thumbWidth; ?>" />
            </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["thumbWidth_message"]?></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td nowrap class="warning"><?php echo $_lang["thumbHeight"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="4" style="width: 50px;" name="thumbHeight" value="<?php echo $thumbHeight; ?>" />
            </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["thumbHeight_message"]?></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td nowrap class="warning"><?php echo $_lang["thumbsDir"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="255" style="width: 250px;" name="thumbsDir" value="<?php echo $thumbsDir; ?>" />
            </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["thumbsDir_message"]?></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td nowrap class="warning"><?php echo $_lang["jpegQuality"]?></td>
            <td>
              <input onchange="documentDirty=true;" type="text" maxlength="4" style="width: 50px;" name="jpegQuality" value="<?php echo $jpegQuality; ?>" />
            </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td width="200">&nbsp;</td>
            <td class='comment'><?php echo $_lang["jpegQuality_message"]?></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
               <td nowrap class="warning"><?php echo $_lang["denyZipDownload"] ?></td>
                <td>
                  <input type="radio" name="denyZipDownload" value="0" <?php echo $denyZipDownload=='0' ? 'checked="checked"' : ""; ?> />
                  <?php echo $_lang["no"]?><br />
                  <input type="radio" name="denyZipDownload" value="1" <?php echo $denyZipDownload=='1' ? 'checked="checked"' : ""; ?> />
                  <?php echo $_lang["yes"]?>
                </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
                <td nowrap class="warning"><?php echo $_lang["denyExtensionRename"] ?></td>
                <td>
                   <input type="radio" name="denyExtensionRename" value="0" <?php echo $denyExtensionRename=='0' ? 'checked="checked"' : ""; ?> />
                   <?php echo $_lang["no"]?><br />
                   <input type="radio" name="denyExtensionRename" value="1" <?php echo $denyExtensionRename=='1' ? 'checked="checked"' : ""; ?> />
                   <?php echo $_lang["yes"]?>
                </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
               <td nowrap class="warning"><?php echo $_lang["showHiddenFiles"] ?></td>
               <td>
                 <input type="radio" name="showHiddenFiles" value="0" <?php echo $showHiddenFiles=='0' ? 'checked="checked"' : ""; ?> />
                 <?php echo $_lang["no"]?><br />
                 <input type="radio" name="showHiddenFiles" value="1" <?php echo $showHiddenFiles=='1' ? 'checked="checked"' : ""; ?> />
                 <?php echo $_lang["yes"]?>
                </td>
          </tr>
          <tr class="rbRow" <?php echo showHide($use_browser==1);?>>
            <td colspan="2"><div class='split'></div></td>
          </tr>
          
          <tr>
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
<script>
    jQuery('input:radio').change( function() {  
        documentDirty=true;
    });
</script>
<?php
if (is_numeric($_GET['tab'])) {
    echo '<script type="text/javascript">tpSettings.setSelectedIndex( '.$_GET['tab'].' );</script>';
}

/**
 * get_lang_keys
 * 
 * @return array of keys from a language file
 */
function get_lang_keys($filename) {
    $file = MODX_MANAGER_PATH.'includes/lang' . DIRECTORY_SEPARATOR . $filename;
    if(is_file($file) && is_readable($file)) {
        include($file);
        return array_keys($_lang);
    } else {
        return array();
    }
}
/**
 * get_langs_by_key
 * 
 * @return array of languages that define the key in their file
 */
function get_langs_by_key($key) {
    global $lang_keys;
    $lang_return = array();
    foreach($lang_keys as $lang=>$keys) {
        if(in_array($key, $keys)) {
            $lang_return[] = $lang;
        }
    }
    return $lang_return;
}

/**
 * get_lang_options
 *
 * returns html option list of languages
 * 
 * @param string $key specify language key to return options of langauges that override it, default return all languages
 * @param string $selected_lang specify language to select in option list, default none
 * @return html option list
 */
function get_lang_options($key=null, $selected_lang=null) {
    global $lang_keys, $_lang;
    $lang_options = '';
    if($key) {
        $languages = get_langs_by_key($key);
        sort($languages);
        $lang_options .= '<option value="">'.$_lang['language_title'].'</option>';

        foreach($languages as $language_name) {
            $uclanguage_name = ucwords(str_replace("_", " ", $language_name));
            $lang_options .= '<option value="'.$language_name.'">'.$uclanguage_name.'</option>';
        }
        return $lang_options;
    } else {
        $languages = array_keys($lang_keys);
        sort($languages);
        foreach($languages as $language_name) {
            $uclanguage_name = ucwords(str_replace("_", " ", $language_name));
            $sel = $language_name == $selected_lang ? ' selected="selected"' : '';
            $lang_options .= '<option value="'.$language_name.'" '.$sel.'>'.$uclanguage_name.'</option>';
        }
        return $lang_options;
    }
}

function form_radio($name,$value,$add='',$disabled=false) {
    global ${$name};
    $var = ${$name};
    $checked  = ($var==$value) ? ' checked="checked"' : '';
    if($disabled) $disabled = ' disabled'; else $disabled = '';
  if($add)     $add = ' ' . $add;
  return sprintf('<input onchange="documentDirty=true;" type="radio" name="%s" value="%s" %s %s %s />', $name, $value, $checked, $disabled, $add);
}

function wrap_label($str='',$object) {
  return "<label>{$object}\n{$str}</label>";
}

function parsePlaceholder($tpl='', $ph=array())
{
    if(empty($ph) || empty($tpl)) return $tpl;
    
    foreach($ph as $k=>$v)
    {
        $k = "[+{$k}+]";
        $tpl = str_replace($k, $v, $tpl);
    }
    return $tpl;
}

function showHide($cond=true)
{
    global $displayStyle;
    $showHide = $cond ? $displayStyle : 'none';
    return sprintf('style="display:%s"', $showHide);
}