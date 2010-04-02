<?php
$installMode = intval($_POST['installmode']);
if ($installMode == 0 || $installMode == 2) {
    $database_collation = isset($_POST['database_collation']) ? $_POST['database_collation'] : 'utf8_general_ci';
    $database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
    $_POST['database_connection_charset'] = $database_charset;
}
elseif ($installMode == 1) {
    include "../manager/includes/config.inc.php";

    if (@ $conn = mysql_connect($database_server, $database_user, $database_password)) {
        if (@ mysql_query("USE {$dbase}")) {
            if (!$rs = @ mysql_query("show session variables like 'collation_database'")) {
                $rs = @ mysql_query("show session variables like 'collation_server'");
            }
            if ($rs && $collation = mysql_fetch_row($rs)) {
                $database_collation = trim($collation[1]);
            }
        }
    }
    if (empty ($database_collation)) {
        $database_collation = 'utf8_unicode_ci';
    }
    $database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
    if (!isset ($database_connection_charset) || empty ($database_connection_charset)) {
        $database_connection_charset = $database_charset;
    }

    if (!isset ($database_connection_method) || empty ($database_connection_method)) {
        $database_connection_method = 'SET CHARACTER SET';
    }
    if ($database_connection_method != 'SET NAMES' && $database_connection_charset != $database_charset) {
        $database_connection_method = 'SET NAMES';
    }

    $_POST['database_name'] = $dbase;
    $_POST['tableprefix'] = $table_prefix;
    $_POST['database_connection_charset'] = $database_connection_charset;
    $_POST['database_connection_method'] = $database_connection_method;
    $_POST['databasehost'] = $database_server;
    $_POST['databaseloginname'] = $database_user;
    $_POST['databaseloginpassword'] = $database_password;
}
?>

<form name="install" id="install_form" action="index.php?action=summary" method="post">
  <div>
    <input type="hidden" value="<?php echo $install_language?>" name="language" />
	<input type="hidden" value="<?php echo $manager_language?>" name="managerlanguage" />
    <input type="hidden" value="<?php echo $installMode ?>" name="installmode" />
    <input type="hidden" value="<?php echo trim($_POST['database_name'], '`') ?>" name="database_name" />
    <input type="hidden" value="<?php echo $_POST['tableprefix'] ?>" name="tableprefix" />
    <input type="hidden" value="<?php echo $_POST['database_collation'] ?>" name="database_collation" />
    <input type="hidden" value="<?php echo $_POST['database_connection_charset'] ?>" name="database_connection_charset" />
    <input type="hidden" value="<?php echo $_POST['database_connection_method'] ?>" name="database_connection_method" />
    <input type="hidden" value="<?php echo $_POST['databasehost'] ?>" name="databasehost" />
    <input type="hidden" value="<?php echo $_POST['databaseloginname'] ?>" name="databaseloginname" />
    <input type="hidden" value="<?php echo $_POST['databaseloginpassword'] ?>" name="databaseloginpassword" />
    <input type="hidden" value="<?php echo $_POST['cmsadmin'] ?>" name="cmsadmin" />
    <input type="hidden" value="<?php echo $_POST['cmsadminemail'] ?>" name="cmsadminemail" />
    <input type="hidden" value="<?php echo $_POST['cmspassword'] ?>" name="cmspassword" />
    <input type="hidden" value="1" name="options_selected" />
  </div>

<?php


# load setup information file
$setupPath = realpath(dirname(__FILE__));
include "{$setupPath}/setup.info.php";

echo "<h2>" . $_lang['optional_items'] . "</h2><p>" . $_lang['optional_items_note'] . "</p>";

$chk = isset ($_POST['installdata']) ? 'checked="checked"' : "";
echo '<img src="img/sample_site.png" class="options" alt="Sample Data" />';
echo "<h3>" . $_lang['sample_web_site'] . "</h3>";
echo "<p><input type=\"checkbox\" name=\"installdata\" id=\"installdata_field\" value=\"1\" $chk />&nbsp;<label for=\"installdata_field\">" . $_lang['install_overwrite'] . " <span class=\"comname\">" . $_lang['sample_web_site'] . "</span></label></p><p><em>&nbsp;" . $_lang['sample_web_site_note'] . "</em></p><hr />";

// toggle options
echo "<h4>" . $_lang['checkbox_select_options'] . "</h4>
	<p class=\"actions\"><a href=\"javascript:Checkboxes.checkAll('toggle');\">" . $_lang['all'] . "</a> <a href=\"javascript:Checkboxes.uncheckAll('toggle');\">" . $_lang['none'] . "</a> <a href=\"javascript:Checkboxes.toggle('toggle');\">" . $_lang['toggle'] . "</a></p>
	<br class=\"clear\" />
	<div id=\"installChoices\">";

$options_selected = isset ($_POST['options_selected']);

// display templates
$templates = isset ($_POST['template']) ? $_POST['template'] : array ();
$limit = count($moduleTemplates);
if ($limit > 0) {
    echo "<h3>" . $_lang['templates'] . "</h3><br />";
    for ($i = 0; $i < $limit; $i++) {
        $chk = in_array($i, $templates) || (!$options_selected) ? 'checked="checked"' : "";
        echo "<input type=\"checkbox\" name=\"template[]\" value=\"$i\" class=\"toggle\" $chk />" . $_lang['install_update'] . " <span class=\"comname\">" . $moduleTemplates[$i][0] . "</span> - " . $moduleTemplates[$i][1] . "<hr />";
    }
}

// display template variables
$tvs = isset ($_POST['tv']) ? $_POST['tv'] : array ();
$limit = count($moduleTVs);
if ($limit > 0) {
    echo "<h3>" . $_lang['tvs'] . "</h3><br />";
    for ($i = 0; $i < $limit; $i++) {
        $chk = in_array($i, $tvs) || (!$options_selected) ? 'checked="checked"' : "";
        echo "<input type=\"checkbox\" name=\"tv[]\" value=\"$i\" class=\"toggle\" $chk />" . $_lang['install_update'] . " <span class=\"comname\">" . $moduleTVs[$i][0] . "</span> - " . $moduleTVs[$i][2] . "<hr />";
    }
}

// display chunks
$chunks = isset ($_POST['chunk']) ? $_POST['chunk'] : array ();
$limit = count($moduleChunks);
if ($limit > 0) {
    echo "<h3>" . $_lang['chunks'] . "</h3>";
    for ($i = 0; $i < $limit; $i++) {
        $chk = in_array($i, $chunks) || (!$options_selected) ? 'checked="checked"' : "";
        echo "<input type=\"checkbox\" name=\"chunk[]\" value=\"$i\" class=\"toggle\" $chk />" . $_lang['install_update'] . " <span class=\"comname\">" . $moduleChunks[$i][0] . "</span> - " . $moduleChunks[$i][1] . "<hr />";
    }
}

// display modules
$modules = isset ($_POST['module']) ? $_POST['module'] : array ();
$limit = count($moduleModules);
if ($limit > 0) {
    echo "<h3>" . $_lang['modules'] . "</h3>";
    for ($i = 0; $i < $limit; $i++) {
        $chk = in_array($i, $modules) || (!$options_selected) ? 'checked="checked"' : "";
        echo "<input type=\"checkbox\" name=\"module[]\" value=\"$i\" class=\"toggle\" $chk />" . $_lang['install_update'] . " <span class=\"comname\">" . $moduleModules[$i][0] . "</span> - " . $moduleModules[$i][1] . "<hr />";
    }
}

// display plugins
$plugins = isset ($_POST['plugin']) ? $_POST['plugin'] : array ();
$limit = count($modulePlugins);
if ($limit > 0) {
    echo "<h3>" . $_lang['plugins'] . "</h3>";
    for ($i = 0; $i < $limit; $i++) {
        $chk = in_array($i, $plugins) || (!$options_selected) ? 'checked="checked"' : "";
        echo "<input type=\"checkbox\" name=\"plugin[]\" value=\"$i\" class=\"toggle\" $chk />" . $_lang['install_update'] . " <span class=\"comname\">" . $modulePlugins[$i][0] . "</span> - " . $modulePlugins[$i][1] . "<hr />";
    }
}

// display snippets
$snippets = isset ($_POST['snippet']) ? $_POST['snippet'] : array ();
$limit = count($moduleSnippets);
if ($limit > 0) {
    echo "<h3>" . $_lang['snippets'] . "</h3>";
    for ($i = 0; $i < $limit; $i++) {
        $chk = in_array($i, $snippets) || (!$options_selected) ? 'checked="checked"' : "";
        echo "<input type=\"checkbox\" name=\"snippet[]\" value=\"$i\" class=\"toggle\" $chk />" . $_lang['install_update'] . " <span class=\"comname\">" . $moduleSnippets[$i][0] . "</span> - " . $moduleSnippets[$i][1] . "<hr />";
    }
}
?>
	</div>
    <p class="buttonlinks">
        <a href="javascript:document.getElementById('install_form').action='index.php?action=<?php echo (($installMode == 1) ? 'mode' : 'connection'); ?>';document.getElementById('install_form').submit();" class="prev" title="<?php echo $_lang['btnback_value']?>"><span><?php echo $_lang['btnback_value']?></span></a>
        <a href="javascript:document.getElementById('install_form').submit();" title="<?php echo $_lang['install']?>"><span><?php echo $_lang['install']?></span></a>
    </p>

</form>
<script type="text/javascript">
/* <![CDATA[ */
var Checkboxes = {
  // checks all the checkboxes of a given class name
  checkAll: function(className) {
    Checkboxes.setChecking(className, true);
  },

  // unchecks all the checkboxes of a given class name
  uncheckAll: function(className) {
    Checkboxes.setChecking(className, false);
  },

  // toggles the value of the checkboxes of a given class name
  toggle: function(className) {
    Checkboxes.setChecking(className, 'toggle');
  },

  // sets the checked value of elements of a given class name
  setChecking: function(className, value) {
    var boxes = getElementsByClassName(document, "*", className);
    var cur_value = false;
    for (var i=0, boxes_len=boxes.length; i<boxes_len; i++) {
      if (value == 'toggle') {
        cur_value = boxes[i].checked;
        if (cur_value == true) {
          boxes[i].checked = '';
        } else {
          boxes[i].checked = 'checked';
        }
      } else {
        boxes[i].checked = value;
      }
    }
  }
}

/*
Written by Jonathan Snook, http://www.snook.ca/jonathan
Add-ons by Robert Nyman, http://www.robertnyman.com
*/
function getElementsByClassName(oElm, strTagName, strClassName){
  var arrElements = (strTagName == "*" && oElm.all)? oElm.all : oElm.getElementsByTagName(strTagName);
  var arrReturnElements = new Array();
  strClassName = strClassName.replace(/-/g, "\-");
  var oRegExp = new RegExp("(^|\s)" + strClassName + "(\s|$)");
  var oElement;
  for(var i=0; i<arrElements.length; i++){
    oElement = arrElements[i];
    if(oRegExp.test(oElement.className)){
      arrReturnElements.push(oElement);
    }
  }
  return (arrReturnElements)
}
/* ]]> */
</script>
