<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true || ! $modx->hasPermission('exec_module')) {
    die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.');
}

$_POST['installmode'] = 1;
//$_POST['installdata'] = 0;
$sqlParser = '';

$create = false;

// set timout limit
@ set_time_limit(120); // used @ to prevent warning when using safe mode?


$installMode= (int)$_POST['installmode'];
$installData = $_POST['installdata'] == "1" ? 1 : 0;

// set session name variable
if (!isset ($site_sessionname)) {
    $site_sessionname = 'SN' . uniqid('');
}

// get base path and url
$a = explode("install", str_replace("\\", "/", dirname($_SERVER["PHP_SELF"])));
if (count($a) > 1)
    array_pop($a);
$url = implode("install", $a);
reset($a);
$a = explode("install", str_replace("\\", "/", realpath(__DIR__)));
if (count($a) > 1)
    array_pop($a);
$pth = implode("install", $a);
unset ($a);
$base_url = $url . (substr($url, -1) != "/" ? "/" : "");
$base_path = $pth . (substr($pth, -1) != "/" ? "/" : "");


if(!function_exists('propertiesNameValue')) {
    // parses a resource property string and returns the result as an array
    // duplicate of method in documentParser class
    function propertiesNameValue($propertyString) {
        $parameter= array ();
        if (!empty ($propertyString)) {
            $tmpParams= explode("&", $propertyString);
            for ($x= 0; $x < count($tmpParams); $x++) {
                if (strpos($tmpParams[$x], '=', 0)) {
                    $pTmp= explode("=", $tmpParams[$x]);
                    $pvTmp= explode(";", trim($pTmp[1]));
                    if ($pvTmp[1] == 'list' && $pvTmp[3] != "")
                        $parameter[trim($pTmp[0])]= $pvTmp[3]; //list default
                    else
                        if ($pvTmp[1] != 'list' && $pvTmp[2] != "")
                            $parameter[trim($pTmp[0])]= $pvTmp[2];
                }
            }
        }
        return $parameter;
    }
}
$table_prefix = EvolutionCms()->getDatabase()->getConfig('prefix');
$setupPath = $modulePath;
include "{$setupPath}/setup.info.php";
include "{$setupPath}/sqlParser.class.php";
$sqlParser = new SqlParser();

// Install Templates
if (isset ($_POST['template']) || $installData) {
    echo "<h3>" . $_lang['templates'] . ":</h3> ";
    $selTemplates = $_POST['template'];
    foreach ($moduleTemplates as $k=>$moduleTemplate) {
        $installSample = in_array('sample', $moduleTemplate[6]) && $installData == 1;
        if($installSample || in_array($k, $selTemplates)) {
            $name = EvolutionCms()->getDatabase()->escape($moduleTemplate[0]);
            $desc = EvolutionCms()->getDatabase()->escape($moduleTemplate[1]);
            $category = EvolutionCms()->getDatabase()->escape($moduleTemplate[4]);
            $locked = EvolutionCms()->getDatabase()->escape($moduleTemplate[5]);
            $filecontent = $moduleTemplate[3];
            if (!file_exists($filecontent)) {
                echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_template'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
            } else {
                // Create the category if it does not already exist
                $category_id = getCreateDbCategory($category, $sqlParser);

                // Strip the first comment up top
                $template = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', file_get_contents($filecontent), 1);
                $template = EvolutionCms()->getDatabase()->escape($template);

                // See if the template already exists
                $rs = EvolutionCms()->getDatabase()->query("SELECT * FROM `" . $table_prefix . "site_templates` WHERE templatename='$name'");

                if (EvolutionCms()->getDatabase()->getRecordCount($rs)) {
                    if (!@ EvolutionCms()->getDatabase()->query("UPDATE `" . $table_prefix . "site_templates` SET content='$template', description='$desc', category='$category_id', locked='$locked'  WHERE templatename='$name';")) {
                        $errors += 1;
                        echo "<p>" . mysql_error() . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
                } else {
                    if (!@ EvolutionCms()->getDatabase()->query("INSERT INTO `" . $table_prefix . "site_templates` (templatename,description,content,category,locked) VALUES('$name','$desc','$template','$category_id','$locked');")) {
                        $errors += 1;
                        echo "<p>" . mysql_error() . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
                }
            }
        }
    }
}

// Install Template Variables
if (isset ($_POST['tv']) || $installData) {
    echo "<h3>" . $_lang['tvs'] . ":</h3> ";
    $selTVs = $_POST['tv'];
    foreach ($moduleTVs as $k=>$moduleTV) {
        $installSample = in_array('sample', $moduleTV[12]) && $installData == 1;
        if($installSample || in_array($k, $selTVs)) {
            $name = EvolutionCms()->getDatabase()->escape($moduleTV[0]);
            $caption = EvolutionCms()->getDatabase()->escape($moduleTV[1]);
            $desc = EvolutionCms()->getDatabase()->escape($moduleTV[2]);
            $input_type = EvolutionCms()->getDatabase()->escape($moduleTV[3]);
            $input_options = EvolutionCms()->getDatabase()->escape($moduleTV[4]);
            $input_default = EvolutionCms()->getDatabase()->escape($moduleTV[5]);
            $output_widget = EvolutionCms()->getDatabase()->escape($moduleTV[6]);
            $output_widget_params = EvolutionCms()->getDatabase()->escape($moduleTV[7]);
            $filecontent = $moduleTV[8];
            $assignments = $moduleTV[9];
            $category = EvolutionCms()->getDatabase()->escape($moduleTV[10]);
            $locked = EvolutionCms()->getDatabase()->escape($moduleTV[11]);


            // Create the category if it does not already exist
            $category = getCreateDbCategory($category, $sqlParser);

            $rs = EvolutionCms()->getDatabase()->query("SELECT * FROM `" . $table_prefix . "site_tmplvars` WHERE name='$name'");
            if (EvolutionCms()->getDatabase()->getRecordCount($rs)) {
                $insert = true;
                while($row = EvolutionCms()->getDatabase()->getRow($rs,'assoc')) {
                    if (!@ EvolutionCms()->getDatabase()->query("UPDATE `" . $table_prefix . "site_tmplvars` SET type='$input_type', caption='$caption', description='$desc', category='$category', locked='$locked', elements='$input_options', display='$output_widget', display_params='$output_widget_params', default_text='$input_default' WHERE id='{$row['id']}';")) {
                        echo "<p>" . mysql_error() . "</p>";
                        return;
                    }
                    $insert = false;
                }
                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
            } else {
                //$q = "INSERT INTO `" . $table_prefix . "site_tmplvars` (type,name,caption,description,category,locked,elements,display,display_params,default_text) VALUES('$input_type','$name','$caption','$desc',(SELECT (CASE COUNT(*) WHEN 0 THEN 0 ELSE `id` END) `id` FROM `" . $table_prefix . "categories` WHERE `category` = '$category'),$locked,'$input_options','$output_widget','$output_widget_params','$input_default');";
                $q = "INSERT INTO `" . $table_prefix . "site_tmplvars` (type,name,caption,description,category,locked,elements,display,display_params,default_text) VALUES('$input_type','$name','$caption','$desc','$category','$locked','$input_options','$output_widget','$output_widget_params','$input_default');";
                if (!@ EvolutionCms()->getDatabase()->query($q)) {
                    echo "<p>" . mysql_error() . "</p>";
                    return;
                }
                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
            }

            // add template assignments
            if (trim($assignments) != ''){
                $assignments = explode(',', $assignments);

                if (count($assignments) > 0) {

                    // remove existing tv -> template assignments
                    $ds=EvolutionCms()->getDatabase()->query("SELECT id FROM `".$table_prefix."site_tmplvars` WHERE name='$name' AND description='$desc';" );
                    $row = EvolutionCms()->getDatabase()->getRow($ds,'assoc');
                    $id = $row["id"];
                    EvolutionCms()->getDatabase()->query("DELETE FROM $dbase.`" . $table_prefix . "site_tmplvar_templates` WHERE tmplvarid = '$id';");

                    // add tv -> template assignments
                    foreach ($assignments as $assignment) {
                        $template = EvolutionCms()->getDatabase()->escape($assignment);
                        $where = "WHERE templatename='$template'";
                        if ($template=='*') $where ='';
                        $ts = EvolutionCms()->getDatabase()->query("SELECT id FROM `".$table_prefix."site_templates` $where;" );
                        if ($ds && $ts) {
                            $tRow = EvolutionCms()->getDatabase()->getRow($ts,'assoc');
                            $templateId = $tRow['id'];
                            EvolutionCms()->getDatabase()->query("INSERT INTO `" . $table_prefix . "site_tmplvar_templates` (tmplvarid, templateid) VALUES('$id', '$templateId');");
                       }
                    }
                }
            }
        }
    }
}

// Install Chunks
if (isset ($_POST['chunk']) || $installData) {
    echo "<h3>" . $_lang['chunks'] . ":</h3> ";
    $selChunks = $_POST['chunk'];
    foreach ($moduleChunks as $k=>$moduleChunk) {
        $installSample = in_array('sample', $moduleChunk[5]) && $installData == 1;
        if($installSample || in_array($k, $selChunks)) {

            $name = EvolutionCms()->getDatabase()->escape($moduleChunk[0]);
            $desc = EvolutionCms()->getDatabase()->escape($moduleChunk[1]);
            $category = EvolutionCms()->getDatabase()->escape($moduleChunk[3]);
            $overwrite = EvolutionCms()->getDatabase()->escape($moduleChunk[4]);
            $filecontent = $moduleChunk[2];

            if (!file_exists($filecontent))
                echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_chunk'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
            else {

                // Create the category if it does not already exist
                $category_id = getCreateDbCategory($category, $sqlParser);

                $chunk = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', file_get_contents($filecontent), 1);
                $chunk = EvolutionCms()->getDatabase()->escape($chunk);
                $rs = EvolutionCms()->getDatabase()->query("SELECT * FROM `" . $table_prefix . "site_htmlsnippets` WHERE name='$name'");
                $count_original_name = EvolutionCms()->getDatabase()->getRecordCount($rs);
                if($overwrite == 'false') {
                    $newname = $name . '-' . str_replace('.', '_', $modx_version);
                    $rs = EvolutionCms()->getDatabase()->query("SELECT * FROM `" . $table_prefix . "site_htmlsnippets` WHERE name='$newname'");
                    $count_new_name = EvolutionCms()->getDatabase()->getRecordCount($rs);
                }
                $update = $count_original_name > 0 && $overwrite == 'true';
                if ($update) {
                    if (!@ EvolutionCms()->getDatabase()->query("UPDATE `" . $table_prefix . "site_htmlsnippets` SET snippet='$chunk', description='$desc', category='$category_id' WHERE name='$name';")) {
                        $errors += 1;
                        echo "<p>" . mysql_error() . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
                } elseif($count_new_name == 0) {
                    if($count_original_name > 0 && $overwrite == 'false') {
                        $name = $newname;
                    }
                    if (!@ EvolutionCms()->getDatabase()->query("INSERT INTO `" . $table_prefix . "site_htmlsnippets` (name,description,snippet,category) VALUES('$name','$desc','$chunk','$category_id');")) {
                        $errors += 1;
                        echo "<p>" . mysql_error() . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
                }
            }
        }
    }
}

// Install Modules
if (isset ($_POST['module']) || $installData) {
    echo "<h3>" . $_lang['modules'] . ":</h3> ";
    $selModules = $_POST['module'];
    foreach ($moduleModules as $k=>$moduleModule) {
        $installSample = in_array('sample', $moduleModule[7]) && $installData == 1;
        if($installSample || in_array($k, $selModules)) {
            $name = EvolutionCms()->getDatabase()->escape($moduleModule[0]);
            $desc = EvolutionCms()->getDatabase()->escape($moduleModule[1]);
            $filecontent = $moduleModule[2];
            $properties = $moduleModule[3];
            $guid = EvolutionCms()->getDatabase()->escape($moduleModule[4]);
            $shared = EvolutionCms()->getDatabase()->escape($moduleModule[5]);
            $category = EvolutionCms()->getDatabase()->escape($moduleModule[6]);
            if (!file_exists($filecontent))
                echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_module'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
            else {

                // Create the category if it does not already exist
                $category = getCreateDbCategory($category, $sqlParser);

                $module = end(preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filecontent), 2));
                // remove installer docblock
                $module = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', $module, 1);
                $module = EvolutionCms()->getDatabase()->escape($module);
                $rs = EvolutionCms()->getDatabase()->query("SELECT * FROM `" . $table_prefix . "site_modules` WHERE name='$name'");
                if (EvolutionCms()->getDatabase()->getRecordCount($rs)) {
                    $row = EvolutionCms()->getDatabase()->getRow($rs,'assoc');
                    $props = EvolutionCms()->getDatabase()->escape(propUpdate($properties,$row['properties']));
                    if (!@ EvolutionCms()->getDatabase()->query("UPDATE `" . $table_prefix . "site_modules` SET modulecode='$module', description='$desc', properties='$props', enable_sharedparams='$shared' WHERE name='$name';")) {
                        echo "<p>" . mysql_error() . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
                } else {
                    $properties = EvolutionCms()->getDatabase()->escape(parseProperties($properties, true));
                    if (!@ EvolutionCms()->getDatabase()->query("INSERT INTO `" . $table_prefix . "site_modules` (name,description,modulecode,properties,guid,enable_sharedparams,category) VALUES('$name','$desc','$module','$properties','$guid','$shared', '$category');")) {
                        echo "<p>" . mysql_error() . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
                }
            }
        }
    }
}

// Install Plugins
if (isset ($_POST['plugin']) || $installData) {
    echo "<h3>" . $_lang['plugins'] . ":</h3> ";
    $selPlugs = $_POST['plugin'];
    foreach ($modulePlugins as $k=>$modulePlugin) {
        $installSample = in_array('sample', $modulePlugin[8]) && $installData == 1;
        if($installSample || in_array($k, $selPlugs)) {
            $name = EvolutionCms()->getDatabase()->escape($modulePlugin[0]);
            $desc = EvolutionCms()->getDatabase()->escape($modulePlugin[1]);
            $filecontent = $modulePlugin[2];
            $properties = $modulePlugin[3];
            $events = explode(",", $modulePlugin[4]);
            $guid = EvolutionCms()->getDatabase()->escape($modulePlugin[5]);
            $category = EvolutionCms()->getDatabase()->escape($modulePlugin[6]);
            $leg_names = '';
            $disabled = $modulePlugin[9];
            if(array_key_exists(7, $modulePlugin)) {
                // parse comma-separated legacy names and prepare them for sql IN clause
                $leg_names = "'" . implode("','", preg_split('/\s*,\s*/', EvolutionCms()->getDatabase()->escape($modulePlugin[7]))) . "'";
            }
            if (!file_exists($filecontent))
                echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_plugin'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
            else {

                // disable legacy versions based on legacy_names provided
                if(!empty($leg_names)) {
                    $update_query = "UPDATE `" . $table_prefix . "site_plugins` SET disabled='1' WHERE name IN ($leg_names);";
                    $rs = EvolutionCms()->getDatabase()->query($update_query);
                }

                // Create the category if it does not already exist
                $category = getCreateDbCategory($category, $sqlParser);

                $plugin = end(preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filecontent), 2));
                // remove installer docblock
                $plugin = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', $plugin, 1);
                $plugin = EvolutionCms()->getDatabase()->escape($plugin);
                $rs = EvolutionCms()->getDatabase()->query("SELECT * FROM `" . $table_prefix . "site_plugins` WHERE name='$name' ORDER BY id");
                $prev_id = null;
                if (EvolutionCms()->getDatabase()->getRecordCount($rs)) {
                    $insert = true;
                    while($row = EvolutionCms()->getDatabase()->getRow($rs,'assoc')) {
                        $props = EvolutionCms()->getDatabase()->escape(propUpdate($properties,$row['properties']));
                        if($row['description'] == $desc){
                            if (!@ EvolutionCms()->getDatabase()->query("UPDATE `" . $table_prefix . "site_plugins` SET plugincode='$plugin', description='$desc', properties='$props' WHERE id='{$row['id']}';")) {
                                echo "<p>" . mysql_error() . "</p>";
                                return;
                            }
                            $insert = false;
                        } else {
                            if (!@ EvolutionCms()->getDatabase()->query("UPDATE `" . $table_prefix . "site_plugins` SET disabled='1' WHERE id='{$row['id']}';")) {
                                echo "<p>".mysql_error()."</p>";
                                return;
                            }
                        }
                        $prev_id = $row['id'];
                    }
                    if($insert === true) {
                        if(!@EvolutionCms()->getDatabase()->query("INSERT INTO `".$table_prefix."site_plugins` (name,description,plugincode,properties,moduleguid,disabled,category) VALUES('$name','$desc','$plugin','$props','$guid','0','$category');" )) {
                            echo "<p>".mysql_error()."</p>";
                            return;
                        }
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
                } else {
                    $properties = EvolutionCms()->getDatabase()->escape(parseProperties($properties, true));
                    if (!@ EvolutionCms()->getDatabase()->query("INSERT INTO `" . $table_prefix . "site_plugins` (name,description,plugincode,properties,moduleguid,disabled,category) VALUES('$name','$desc','$plugin','$properties','$guid','$disabled','$category');")) {
                        echo "<p>" . mysql_error() . "</p>";
                        return;
                    }

                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
                }
                // add system events
                if (count($events) > 0) {
                    $ds=EvolutionCms()->getDatabase()->query("SELECT id FROM `".$table_prefix."site_plugins` WHERE name='$name' AND description='$desc' ORDER BY id DESC LIMIT 1;" );
                    if ($ds) {
                        $row = EvolutionCms()->getDatabase()->getRow($ds,'assoc');
                        $id = $row["id"];
                        $_events = implode("','", $events);

                        // add new events
                        if ($prev_id) {
                            $prev_id = EvolutionCms()->getDatabase()->escape($prev_id);

                            EvolutionCms()->getDatabase()->query("INSERT IGNORE INTO `{$table_prefix}site_plugin_events` (`pluginid`, `evtid`, `priority`)
                                SELECT {$id} as 'pluginid', `se`.`id` AS `evtid`, COALESCE(`spe`.`priority`, MAX(`spe2`.`priority`) + 1, 0) AS `priority`
                                FROM `{$table_prefix}system_eventnames` `se`
                                LEFT JOIN `{$table_prefix}site_plugin_events` `spe` ON `spe`.`evtid` = `se`.`id` AND `spe`.`pluginid` = {$prev_id}
                                LEFT JOIN `{$table_prefix}site_plugin_events` `spe2` ON `spe2`.`evtid` = `se`.`id`
                                WHERE name IN ('{$_events}')
                                GROUP BY `se`.`id`
                            ");
                        } else {
                            EvolutionCms()->getDatabase()->query("INSERT IGNORE INTO `{$table_prefix}site_plugin_events` (`pluginid`, `evtid`, `priority`) 
                                SELECT {$id} as `pluginid`, `se`.`id` as `evtid`, COALESCE(MAX(`spe`.`priority`) + 1, 0) as `priority` 
                                FROM `{$table_prefix}system_eventnames` `se` 
                                LEFT JOIN `{$table_prefix}site_plugin_events` `spe` ON `spe`.`evtid` = `se`.`id` 
                                WHERE `name` IN ('{$_events}') GROUP BY `se`.`id`
                            ");
                        }

                        // remove existing events
                        EvolutionCms()->getDatabase()->query("DELETE `pe` FROM `{$table_prefix}site_plugin_events` `pe` LEFT JOIN `{$table_prefix}system_eventnames` `se` ON `pe`.`evtid`=`se`.`id` AND `name` IN ('{$_events}') WHERE ISNULL(`name`) AND `pluginid` = {$id}");
                    }
                }
            }
        }
    }
}

// Install Snippets
if (isset ($_POST['snippet']) || $installData) {
    echo "<h3>" . $_lang['snippets'] . ":</h3> ";
    $selSnips = $_POST['snippet'];
    foreach ($moduleSnippets as $k=>$moduleSnippet) {
        if (!is_array($moduleSnippet) || !isset($moduleSnippet[5]) || !is_array($moduleSnippet[5])) continue;
        $installSample = in_array('sample', $moduleSnippet[5]) && $installData == 1;
        if($installSample || in_array($k, $selSnips)) {
            $name = EvolutionCms()->getDatabase()->escape($moduleSnippet[0]);
            $desc = EvolutionCms()->getDatabase()->escape($moduleSnippet[1]);
            $filecontent = $moduleSnippet[2];
            $properties = $moduleSnippet[3];
            $category = EvolutionCms()->getDatabase()->escape($moduleSnippet[4]);
            if (!file_exists($filecontent))
                echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_snippet'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
            else {

                // Create the category if it does not already exist
                $category = getCreateDbCategory($category, $sqlParser);
                $tmp = preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filecontent));
                $snippet = end($tmp);
                // remove installer docblock
                $snippet = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', $snippet, 1);
                $snippet = EvolutionCms()->getDatabase()->escape($snippet);
                $rs = EvolutionCms()->getDatabase()->query("SELECT * FROM `" . $table_prefix . "site_snippets` WHERE name='$name'");

                if (EvolutionCms()->getDatabase()->getRecordCount($rs)) {

                    $row = EvolutionCms()->getDatabase()->getRow($rs,'assoc');
                    $props = EvolutionCms()->getDatabase()->escape(propUpdate($properties,$row['properties']));
                    if (!EvolutionCms()->getDatabase()->query("UPDATE `" . $table_prefix . "site_snippets` SET snippet='$snippet', description='$desc', properties='$props' WHERE name='$name';")) {
                        echo "<p>" . mysql_error() . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
                } else {
                    $properties = EvolutionCms()->getDatabase()->escape(parseProperties($properties, true));
                    if (!EvolutionCms()->getDatabase()->query("INSERT INTO `" . $table_prefix . "site_snippets` (name,description,snippet,properties,category) VALUES('$name','$desc','$snippet','$properties','$category');")) {
                        echo "<p>" . mysql_error() . "</p>";
                        return;
                    }
                    echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
                }
            }
        }
    }
}


// install data
if ($installData && $moduleSQLDataFile) {
    echo "<p>" . $_lang['installing_demo_site'];
    $sqlParser->process($installPath.'/'.$moduleSQLDataFile);
    // display database results
    if ($sqlParser->installFailed == true) {
        $errors += 1;
        echo "<span class=\"notok\"><b>" . $_lang['database_alerts'] . "</span></p>";
        echo "<p>" . $_lang['setup_couldnt_install'] . "</p>";
        echo "<p>" . $_lang['installation_error_occured'] . "<br /><br />";
        for ($i = 0; $i < count($sqlParser->mysqlErrors); $i++) {
            echo "<em>" . $sqlParser->mysqlErrors[$i]["error"] . "</em>" . $_lang['during_execution_of_sql'] . "<span class='mono'>" . strip_tags($sqlParser->mysqlErrors[$i]["sql"]) . "</span>.<hr />";
        }
        echo "</p>";
        echo "<p>" . $_lang['some_tables_not_updated'] . "</p>";
        return;
    } else {
        echo "<span class=\"ok\">".$_lang['ok']."</span></p>";
    }
}


// always empty cache after install
$modx->clearCache('full');



// setup completed!
echo "<p><b>" . $_lang['installation_successful'] . "</b></p>";



// Property Update function
function propUpdate($new,$old){
    $newArr = parseProperties($new);
    $oldArr = parseProperties($old);
    foreach ($oldArr as $k => $v){
        if (isset($v['0']['options'])){
            $oldArr[$k]['0']['options'] = $newArr[$k]['0']['options'];
        }
    }
    $return = $oldArr + $newArr;
    $return = json_encode($return, JSON_UNESCAPED_UNICODE);
    $return = ($return != '[]') ? $return : '';
    return $return;
}

function parseProperties($propertyString, $json=false) {
    $propertyString = str_replace('{}', '', $propertyString );
    $propertyString = str_replace('} {', ',', $propertyString );

    if (empty($propertyString) || $propertyString == '{}' || $propertyString == '[]') {
        $propertyString = '';
    }

    $jsonFormat = isJson($propertyString, true);
    $property = array();
    // old format
    if ( $jsonFormat === false) {
        $props= explode('&', $propertyString);
        $arr = array();
        $key = array();
        foreach ($props as $prop) {
            if ($prop != ''){
                $arr = explode(';', $prop);
                $key = explode('=', $arr['0']);
                $property[$key['0']]['0']['label'] = trim($key['1']);
                $property[$key['0']]['0']['type'] = trim($arr['1']);
                switch ($arr['1']) {
                    case 'list':
                    case 'list-multi':
                    case 'checkbox':
                    case 'radio':
                    case 'menu':
                        $property[$key['0']]['0']['value'] = trim($arr['3']);
                        $property[$key['0']]['0']['options'] = trim($arr['2']);
                        $property[$key['0']]['0']['default'] = trim($arr['3']);
                        break;
                    default:
                        $property[$key['0']]['0']['value'] = trim($arr['2']);
                        $property[$key['0']]['0']['default'] = trim($arr['2']);
                }
                $property[$key['0']]['0']['desc'] = '';
            }

        }
    // new json-format
    } else if(!empty($jsonFormat)){
        $property = $jsonFormat;
    }
    if ($json) {
        $property = json_encode($property, JSON_UNESCAPED_UNICODE);
    }
    $property = ($property != '[]') ? $property : '';
    return $property;
}

function isJson($string, $returnData=false) {
    $data = json_decode($string, true);
    return (json_last_error() == JSON_ERROR_NONE) ? ($returnData ? $data : true) : false;
}

function getCreateDbCategory($category, $sqlParser) {

    $table_prefix = EvolutionCms()->getDatabase()->getConfig('prefix');
    $category_id = 0;
    if(!empty($category)) {
        $category = EvolutionCms()->getDatabase()->escape($category);
        $rs = EvolutionCms()->getDatabase()->query("SELECT id FROM `".$table_prefix."categories` WHERE category = '".$category."'");
        if(EvolutionCms()->getDatabase()->getRecordCount($rs) && ($row = EvolutionCms()->getDatabase()->getRow($rs,'assoc'))) {
            $category_id = $row['id'];
        } else {
            $q = "INSERT INTO `".$table_prefix."categories` (`category`) VALUES ('{$category}');";
            $rs = EvolutionCms()->getDatabase()->query($q);
            if($rs) {
                $category_id = EvolutionCms()->getDatabase()->getInsertId($sqlParser->conn);
            }
        }
    }
    return $category_id;
}
