<?php
$installMode = intval($_POST['installmode']);

if ($installMode == 1) {
    include "../manager/includes/config.inc.php";
    
    if (!isset ($database_connection_charset) || empty($database_connection_charset)) {
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
	    $database_connection_charset = $database_charset;
    }
    
    $_POST['database_name']= $dbase;
    $_POST['tableprefix']= $table_prefix;
    $_POST['database_connection_charset']= $database_connection_charset;
    $_POST['databasehost']= $database_server;
    $_POST['databaseloginname']= $database_user;
    $_POST['databaseloginpassword']= $database_password;
}
?>

<form name="install" action="index.php?action=summary" method="post">
	<div>
		<input type="hidden" value="1" name="chkagree" <?php echo isset($_POST['chkagree']) ? 'checked="checked" ':""; ?>/>
		<input type="hidden" value="<?php echo $installMode ?>" name="installmode" />
		<input type="hidden" value="<?php echo trim($_POST['database_name'], '`') ?>" name="database_name" />
		<input type="hidden" value="<?php echo $_POST['tableprefix'] ?>" name="tableprefix" />
		<input type="hidden" value="<?php echo $_POST['database_collation'] ?>" name="database_collation" />
		<input type="hidden" value="<?php echo $_POST['database_connection_charset'] ?>" name="database_connection_charset" />
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

echo "<p class=\"title\">Optional Items</p><p>Please choose your installation options and click Install:</p>";

$chk = isset ($_POST['installdata']) ? 'checked="checked"' : "";
echo '<img src="im_sample.gif" align="left" width="45" height="48" hspace="5" hspace="10" alt="Sample Data" />';
echo "<h1>&nbsp;Sample Web Site</h1>";
echo "&nbsp;<input type=\"checkbox\" name=\"installdata\" value=\"1\" $chk />Install/Overwrite <span class=\"comname\">Sample Web Site</span> <br /><span><i>&nbsp;Please note that this will <b style=\"color:#CC0000\">overwrite</b> existing documents and resources.</i></span><hr size=\"1\" style=\"border:1px dotted silver;\" /><br />";


// toggle options
echo "<h4>Checkbox select options:</h4><p class=\"actions\"><a href=\"javascript:Checkboxes.checkAll('toggle');\">All</a> <a href=\"javascript:Checkboxes.uncheckAll('toggle');\">None</a> <a href=\"javascript:Checkboxes.toggle('toggle');\">Toggle</a></p><br class=\"clear\" />";

$options_selected= isset ($_POST['options_selected']);

// display templates
$templates = isset ($_POST['template']) ? $_POST['template'] : array ();
$limit = count($moduleTemplates);
if ($limit > 0) {
    echo '<br/><img src="im_resources.gif" align="left" width="15" height="15" hspace="5" alt="Templates" />';
    echo "<h1>Templates</h1><br />";
    for ($i = 0; $i < $limit; $i++) {
        $chk = in_array($i, $templates) || (!$options_selected) ? 'checked="checked"' : "";
        echo "&nbsp;<input type=\"checkbox\" name=\"template[]\" value=\"$i\" class=\"toggle\" $chk />Install/Update <span class=\"comname\">" . $moduleTemplates[$i][0] . "</span> - " . $moduleTemplates[$i][1] . "<hr size=\"1\" style=\"border:1px dotted silver;\" />";
    }
}

// display chunks
$chunks = isset ($_POST['chunk']) ? $_POST['chunk'] : array ();
$limit = count($moduleChunks);
if ($limit > 0) {
    echo '<br/><img src="im_resources.gif" align="left" width="15" height="15" hspace="5" alt="Chunks" />';
    echo "<h1>Chunks</h1>";
    for ($i = 0; $i < $limit; $i++) {
        $chk = in_array($i, $chunks) || (!$options_selected) ? 'checked="checked"' : "";
        echo "&nbsp;<input type=\"checkbox\" name=\"chunk[]\" value=\"$i\" class=\"toggle\" $chk />Install/Update <span class=\"comname\">" . $moduleChunks[$i][0] . "</span> - " . $moduleChunks[$i][1] . "<hr size=\"1\" style=\"border:1px dotted silver;\" />";
    }
}

// display modules
$modules = isset ($_POST['module']) ? $_POST['module'] : array ();
$limit = count($moduleModules);
if ($limit > 0) {
    echo '<br/><img src="im_resources.gif" align="left" width="15" height="15" hspace="5" alt="Modules" />';
    echo "<h1>Modules</h1>";
    for ($i = 0; $i < $limit; $i++) {
        $chk = in_array($i, $modules) || (!$options_selected) ? 'checked="checked"' : "";
        echo "&nbsp;<input type=\"checkbox\" name=\"module[]\" value=\"$i\" class=\"toggle\" $chk />Install/Update <span class=\"comname\">" . $moduleModules[$i][0] . "</span> - " . $moduleModules[$i][1] . "<hr size=\"1\" style=\"border:1px dotted silver;\" />";
    }
}

// display plugins
$plugins = isset ($_POST['plugin']) ? $_POST['plugin'] : array ();
$limit = count($modulePlugins);
if ($limit > 0) {
    echo '<br/><img src="im_resources.gif" align="left" width="15" height="15" hspace="5" alt="Plugins" />';
    echo "<h1>Plugins</h1>";
    for ($i = 0; $i < $limit; $i++) {
        $chk = in_array($i, $plugins) || (!$options_selected) ? 'checked="checked"' : "";
        echo "&nbsp;<input type=\"checkbox\" name=\"plugin[]\" value=\"$i\" class=\"toggle\" $chk />Install/Update <span class=\"comname\">" . $modulePlugins[$i][0] . "</span> - " . $modulePlugins[$i][1] . "<hr size=\"1\" style=\"border:1px dotted silver;\" />";
    }
}

// display snippets
$snippets = isset ($_POST['snippet']) ? $_POST['snippet'] : array ();
$limit = count($moduleSnippets);
if ($limit > 0) {
    echo '<br/><img src="im_resources.gif" align="left" width="15" height="15" hspace="5" alt="Snippets" />';
    echo "<h1>Snippets</h1>";
    for ($i = 0; $i < $limit; $i++) {
        $chk = in_array($i, $snippets) || (!$options_selected) ? 'checked="checked"' : "";
        echo "&nbsp;<input type=\"checkbox\" name=\"snippet[]\" value=\"$i\" class=\"toggle\" $chk />Install/Update <span class=\"comname\">" . $moduleSnippets[$i][0] . "</span> - " . $moduleSnippets[$i][1] . "<hr size=\"1\" style=\"border:1px dotted silver;\" />";
    }
}
?>

	<div id="navbar">
		<input type="submit" value="Next" name="cmdnext" style="float:right;width:100px;" />
		<span style="float:right">&nbsp;</span>
		<input type="submit" value="Back" name="cmdback" style="float:right;width:100px;" onclick="this.form.action='index.php?action=connection';this.form.submit();return false;" />
	</div>
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
