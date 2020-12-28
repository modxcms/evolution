<?php
if (!defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true || !EvolutionCMS()->hasPermission('exec_module')) {
    die('<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.');
}

error_reporting(E_ALL & ~E_NOTICE);
define('MGR', MODX_BASE_PATH . MGR_DIR);
$moduleurl = 'assets/modules/store/installer/index.php';
$modulePath = __DIR__;
$self = $modulePath . '/index.php';
require_once($modulePath . "/functions.php");
$_lang = array();
$_params = array();
require_once($modulePath . "/lang/russian-UTF8.inc.php");
if (!isset($modx_branch)) $modx_branch = '';
if (!isset($modx_version)) $modx_version = '';
if (!isset($modx_release_date)) $modx_release_date = '';
if (!isset($installPath)) $installPath = '';
// start session
//session_start();
$_SESSION['test'] = 1;
install_sessionCheck();
$moduleName = "MODX";
$moduleVersion = $modx_branch . ' ' . $modx_version;
$moduleRelease = $modx_release_date;
$moduleSQLBaseFile = "setup.sql";
$moduleSQLDataFile = "setup.data.sql";

if (is_file($installPath . '/' . $moduleSQLBaseFile)) {
    $moduleSQLDataFile = $moduleSQLBaseFile;
}


$moduleChunks = array(); // chunks - array : name, description, type - 0:file or 1:content, file or content
$moduleTemplates = array(); // templates - array : name, description, type - 0:file or 1:content, file or content
$moduleSnippets = array(); // snippets - array : name, description, type - 0:file or 1:content, file or content,properties
$modulePlugins = array(); // plugins - array : name, description, type - 0:file or 1:content, file or content,properties, events,guid
$moduleModules = array(); // modules - array : name, description, type - 0:file or 1:content, file or content,properties, guid
$moduleTemplates = array(); // templates - array : name, description, type - 0:file or 1:content, file or content,properties
$moduleTVs = array(); // template variables - array : name, description, type - 0:file or 1:content, file or content,properties

$errors = 0;

// get post back status
$isPostBack = (count($_POST));

$_POST['installmode'] = 1;
//$_POST['installdata'] = 0;
$sqlParser = '';
$create = false;

// set timout limit
@ set_time_limit(120); // used @ to prevent warning when using safe mode?


$installMode = (int)$_POST['installmode'];
$installData = 1;

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


if (!function_exists('propertiesNameValue')) {
    // parses a resource property string and returns the result as an array
    // duplicate of method in documentParser class
    function propertiesNameValue($propertyString)
    {
        $parameter = array();
        if (!empty ($propertyString)) {
            $tmpParams = explode("&", $propertyString);
            for ($x = 0; $x < count($tmpParams); $x++) {
                if (strpos($tmpParams[$x], '=', 0)) {
                    $pTmp = explode("=", $tmpParams[$x]);
                    $pvTmp = explode(";", trim($pTmp[1]));
                    if ($pvTmp[1] == 'list' && $pvTmp[3] != "")
                        $parameter[trim($pTmp[0])] = $pvTmp[3]; //list default
                    else
                        if ($pvTmp[1] != 'list' && $pvTmp[2] != "")
                            $parameter[trim($pTmp[0])] = $pvTmp[2];
                }
            }
        }
        return $parameter;
    }
}

$setupPath = $modulePath;

include "{$setupPath}/setup.info.php";

include "sqlParser.class.php";

$databaseConfig = EvolutionCMS()->app['config']['database']['connections']['default'];

$sqlParser = new SqlParser('', '', '', $databaseConfig['charset'], \Lang::getLocale(), $databaseConfig['method'], 'sibling');
$sqlParser->mode = "upd";
$sqlParser->ignoreDuplicateErrors = true;

// Install Templates
if (count($moduleTemplates) > 0) {
    echo "<h3>" . $_lang['templates'] . ":</h3> ";
    foreach ($moduleTemplates as $k => $moduleTemplate) {
        //$installSample = in_array('sample', $moduleTemplate[6]) && $installData == 1;
        //  if(in_array($k, $selTemplates) || $installSample) {
        $name = $moduleTemplate[0];
        $desc = $moduleTemplate[1];
        $category = $moduleTemplate[4];
        $locked = $moduleTemplate[5];
        $filecontent = $moduleTemplate[3];
        if (!file_exists($filecontent)) {
            echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_template'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
        } else {
            // Create the category if it does not already exist
            $category_id = getCreateDbCategory($category, $sqlParser);

            // Strip the first comment up top
            $template = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', file_get_contents($filecontent), 1);
            $template = $template;

            // See if the template already exists
            $template = \EvolutionCMS\Models\SiteTemplate::where('templatename', $name)->first();

            if ($template->count() > 0) {
                $template->content = $template;
                $template->description = $desc;
                $template->category = $category_id;
                $template->locked = $locked;
                $template->save();

                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
            } else {
                \EvolutionCMS\Models\SiteTemplate::create(['templatename' => $name, 'description' => $desc, 'content' => $template, 'category' => $category_id, 'locked' => $locked]);
                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
            }
        }
        //}
    }
}

// Install Template Variables
if (count($moduleTVs) > 0) {
    echo "<h3>" . $_lang['tvs'] . ":</h3> ";
    foreach ($moduleTVs as $k => $moduleTV) {
        //$installSample = in_array('sample', $moduleTV[12]) && $installData == 1;
        //if(in_array($k, $selTVs) || $installSample) {
        $name = $moduleTV[0];
        $caption = $moduleTV[1];
        $desc = $moduleTV[2];
        $input_type = $moduleTV[3];
        $input_options = $moduleTV[4];
        $input_default = $moduleTV[5];
        $output_widget = $moduleTV[6];
        $output_widget_params = $moduleTV[7];
        $filecontent = $moduleTV[8];
        $assignments = $moduleTV[9];
        $category = $moduleTV[10];
        $locked = $moduleTV[11];


        // Create the category if it does not already exist
        $category = getCreateDbCategory($category, $sqlParser);

        $tmplavr = \EvolutionCMS\Models\SiteTmplvar::where('name', $name);
        if ($tmplavr->count() > 0) {
            $insert = true;
            foreach ($tmplavr->get()->toArray() as $row) {
                \EvolutionCMS\Models\SiteTmplvar::query()->where('id', $row['id'])->update(['type' => $input_type, 'caption' => $caption, 'description' => $desc, 'category' => $category, 'locked' => $locked, 'elements' => $input_options, 'display' => $output_widget, 'display_params' => $output_widget_params, 'default_text' => $input_default]);
                $insert = false;
            }
            echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
        } else {
            \EvolutionCMS\Models\SiteTmplvar::create(
                ['type' => $input_type, 'name' => $name, 'caption' => $caption, 'description' => $desc, 'category' => $category, 'locked' => $locked, 'elements' => $input_options, 'display' => $output_widget, 'display_params' => $output_widget_params, 'default_text' => $input_default]
            );

            echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
        }

        // add template assignments
        if (trim($assignments) != '') {
            $assignments = explode(',', $assignments);
            if (count($assignments) > 0) {

                // remove existing tv -> template assignments
                $templateVar = \EvolutionCMS\Models\SiteTmplvar::query()->where('name', $name)->where('description', $desc)->first();
                if (!is_null($templateVar)) {
                    \EvolutionCMS\Models\SiteTmplvarTemplate::query()->where('tmplvarid', $id)->delete();

                    // add tv -> template assignments
                    foreach ($assignments as $assignment) {
                        $template = $assignment;
                        $template_name = \EvolutionCMS\Models\SiteTemplate::query();
                        if ($template != '*')
                            $template_name = $template_name->where('templatename', $template);

                        $template_name = $template_name->first();
                        if (!is_null($ts)) {
                            \EvolutionCMS\Models\SiteTmplvarTemplate::query()->create(['tmplvarid' => $templateVar->getKey(), 'templateid' => $template_name->getKey()]);
                        }
                    }
                }
            }
        }
        //}
    }
}

// Install Chunks
if (count($moduleChunks) > 0) {
    echo "<h3>" . $_lang['chunks'] . ":</h3> ";
    foreach ($moduleChunks as $k => $moduleChunk) {
        //$installSample = in_array('sample', $moduleChunk[5]) && $installData == 1;
        //if(in_array($k, $selChunks) || $installSample) {

        $name = $moduleChunk[0];
        $desc = $moduleChunk[1];
        $category = $moduleChunk[3];
        $overwrite = $moduleChunk[4];
        $filecontent = $moduleChunk[2];

        if (!file_exists($filecontent))
            echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_chunk'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
        else {

            // Create the category if it does not already exist
            $category_id = getCreateDbCategory($category, $sqlParser);

            $chunk = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', file_get_contents($filecontent), 1);
            $chunkDbRecord = \EvolutionCMS\Models\SiteHtmlsnippet::query()->where('name', $name);
            $count_original_name = $chunkDbRecord->count();
            if ($overwrite == 'false') {
                $newname = $name . '-' . str_replace('.', '_', $modx_version);
                $chunkDbRecordNew = \EvolutionCMS\Models\SiteHtmlsnippet::query()->where('name', $newname);
                $count_new_name = $chunkDbRecordNew->count();
            }
            $update = $count_original_name > 0 && $overwrite == 'true';
            if ($update) {
                \EvolutionCMS\Models\SiteHtmlsnippet::query()->where('name', $name)->update(['snippet' => $chunk, 'description' => $desc, 'category' => $category_id]);

                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
            } elseif ($count_new_name == 0) {
                if ($count_original_name > 0 && $overwrite == 'false') {
                    $name = $newname;
                }
                \EvolutionCMS\Models\SiteHtmlsnippet::query()->create(['name' => $name, 'snippet' => $chunk, 'description' => $desc, 'category' => $category_id]);
                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
            }
        }
        //}
    }
}

// Install Modules
if (count($moduleModules) > 0) {
    echo "<h3>" . $_lang['modules'] . ":</h3> ";
    foreach ($moduleModules as $k => $moduleModule) {
        //$installSample = in_array('sample', $moduleModule[7]) && $installData == 1;
        //if(in_array($k, $selModules) || $installSample) {
        $name = $moduleModule[0];
        $desc = $moduleModule[1];
        $filecontent = $moduleModule[2];
        $properties = $moduleModule[3];
        $guid = $moduleModule[4];
        $shared = $moduleModule[5];
        $category = $moduleModule[6];
        if (!file_exists($filecontent))
            echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_module'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
        else {

            // Create the category if it does not already exist
            $category = getCreateDbCategory($category, $sqlParser);

            $module = end(preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filecontent), 2));
            // remove installer docblock
            $module = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', $module, 1);
            $moduleDb = \EvolutionCMS\Models\SiteModule::query()->where('name', $name)->first();
            if (!is_null($moduleDb)) {
                $properties = propUpdate($properties, $moduleDb->properties);
                \EvolutionCMS\Models\SiteModule::query()->where('name', $name)->update(['modulecode' => $module, 'description' => $desc, 'properties' => $properties, 'enable_sharedparams' => $shared]);

                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
            } else {
                $properties = parseProperties($properties, true);
                \EvolutionCMS\Models\SiteModule::query()->create(['name' => $name, 'guid' => $guid, 'category' => $category, 'modulecode' => $module, 'description' => $desc, 'properties' => $properties, 'enable_sharedparams' => $shared]);
                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
            }
        }
        //}
    }
}

// Install Plugins
if (count($modulePlugins) > 0) {
    echo "<h3>" . $_lang['plugins'] . ":</h3> ";

    foreach ($modulePlugins as $k => $modulePlugin) {
        //$installSample = in_array('sample', $modulePlugin[8]) && $installData == 1;
        // if(in_array($k, $selPlugs) || $installSample) {
        $name = $modulePlugin[0];
        $desc = $modulePlugin[1];
        $filecontent = $modulePlugin[2];
        $properties = $modulePlugin[3];
        $events = explode(",", $modulePlugin[4]);
        $guid = $modulePlugin[5];
        $category = $modulePlugin[6];
        $leg_names = [];
        $disabled = $modulePlugin[9];
        if (array_key_exists(7, $modulePlugin)) {
            // parse comma-separated legacy names and prepare them for sql IN clause
            $leg_names = preg_split('/\s*,\s*/', $modulePlugin[7]);
        }
        if (!file_exists($filecontent))
            echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_plugin'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
        else {

            // disable legacy versions based on legacy_names provided
            if (count($leg_names)) {
                \EvolutionCMS\Models\SitePlugin::query()->whereIn('name', $leg_names)->update(['disabled' => 1]);

            }

            // Create the category if it does not already exist
            $category = getCreateDbCategory($category, $sqlParser);

            $plugin = end(preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filecontent), 2));
            // remove installer docblock
            $plugin = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', $plugin, 1);
            $pluginDbRecord = \EvolutionCMS\Models\SitePlugin::where('name', $name)->orderBy('id');
            $prev_id = null;

            if ($pluginDbRecord->count() > 0) {
                $insert = true;
                foreach ($pluginDbRecord->get()->toArray() as $row) {
                    $properties = propUpdate($properties, $row['properties']);
                    if ($row['description'] == $desc) {
                        \EvolutionCMS\Models\SitePlugin::query()->where('id', $row['id'])->update(['plugincode' => $plugin, 'description' => $desc, 'properties' => $properties]);

                        $insert = false;
                    } else {
                        \EvolutionCMS\Models\SitePlugin::query()->where('id', $row['id'])->update(['disabled' => 1]);
                    }
                    $prev_id = $row['id'];
                }
                if ($insert === true) {
                    $properties = propUpdate($properties, $row['properties']);

                    \EvolutionCMS\Models\SitePlugin::query()->create(['name' => $name, 'plugincode' => $plugin, 'description' => $desc, 'properties' => $properties, 'moduleguid' => $guid, 'disabled' => 0, 'category' => $category]);
                }
                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
            } else {
                $properties = parseProperties($properties, true);
                \EvolutionCMS\Models\SitePlugin::query()->create(['name' => $name, 'plugincode' => $plugin, 'description' => $desc, 'properties' => $properties, 'moduleguid' => $guid, 'disabled' => $disabled, 'category' => $category]);
                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
            }
            // add system events
            if (count($events) > 0) {
                $sitePlugin = \EvolutionCMS\Models\SitePlugin::where('name', $name)->where('description', $desc)->first();
                if (!is_null($sitePlugin)) {
                    $id = $sitePlugin->id;

                    // add new events
                    foreach ($events as $event) {
                        $eventName = \EvolutionCMS\Models\SystemEventname::where('name', $event)->first();
                        if (!is_null($eventName)) {
                            $prev_priority = null;
                            if ($prev_id) {
                                $pluginEvent = \EvolutionCMS\Models\SitePluginEvent::query()
                                    ->where('pluginid', $prev_id)
                                    ->where('evtid', $eventName->getKey())->first();
                                if (!is_null($pluginEvent)) {
                                    $prev_priority = $pluginEvent->priority;
                                }
                            }
                            if (is_null($prev_priority)) {
                                $pluginEvent = \EvolutionCMS\Models\SitePluginEvent::query()
                                    ->where('evtid', $eventName->getKey())
                                    ->orderBy('priority', 'DESC')->first();
                                if (!is_null($pluginEvent)) {
                                    $prev_priority = $pluginEvent->priority;
                                    $prev_priority++;
                                }
                            }
                            if (is_null($prev_priority)) {
                                $prev_priority = 0;
                            }
                            $arrInsert = ['pluginid' => $id, 'evtid' => $eventName->getKey(), 'priority' => $prev_priority];
                            \EvolutionCMS\Models\SitePluginEvent::query()
                                ->firstOrCreate($arrInsert);
                        }
                    }

                    // remove absent events
                    \EvolutionCMS\Models\SitePluginEvent::query()->join('system_eventnames', function ($join) use ($events) {
                        $join->on('site_plugin_events.evtid', '=', 'system_eventnames.id')
                            ->whereIn('name', $events);
                    })
                        ->whereNull('name')
                        ->where('pluginid', $id)->delete();

                }
            }


        }
        //}
    }
}

// Install Snippets
if (count($moduleSnippets) > 0) {
    echo "<h3>" . $_lang['snippets'] . ":</h3> ";

    foreach ($moduleSnippets as $k => $moduleSnippet) {

        //$installSample = in_array('sample', $moduleSnippet[5]) && $installData == 1;
        //if(in_array($k, $selSnips) || $installSample) {
        $name = $moduleSnippet[0];
        $desc = $moduleSnippet[1];
        $filecontent = $moduleSnippet[2];
        $properties = $moduleSnippet[3];
        $category = $moduleSnippet[4];
        if (!file_exists($filecontent))
            echo "<p>&nbsp;&nbsp;$name: <span class=\"notok\">" . $_lang['unable_install_snippet'] . " '$filecontent' " . $_lang['not_found'] . ".</span></p>";
        else {

            // Create the category if it does not already exist
            $category = getCreateDbCategory($category, $sqlParser);

            $snippet = end(preg_split("/(\/\/)?\s*\<\?php/", file_get_contents($filecontent)));
            // remove installer docblock
            $snippet = preg_replace("/^.*?\/\*\*.*?\*\/\s+/s", '', $snippet, 1);
            $snippetDbRecord = \EvolutionCMS\Models\SiteSnippet::query()->where('name', $name)->first();

            if (!is_null($snippetDbRecord)) {

                $properties = propUpdate($properties, $snippetDbRecord->properties);
                \EvolutionCMS\Models\SiteSnippet::query()->where('name', $name)->update(['snippet' => $snippet, 'description' => $desc, 'properties' => $properties]);

                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['upgraded'] . "</span></p>";
            } else {
                $properties = parseProperties($properties, true);
                \EvolutionCMS\Models\SiteSnippet::query()->insert(['name' => $name, 'snippet' => $snippet, 'description' => $desc, 'properties' => $properties, 'category' => $category]);
                echo "<p>&nbsp;&nbsp;$name: <span class=\"ok\">" . $_lang['installed'] . "</span></p>";
            }
        }
        //}
    }
}

// install data

if (is_file($installPath . '/' . $moduleSQLDataFile)) {
    echo "<p>" . $_lang['installing_demo_site'];
    $sqlParser->process($installPath . '/' . $moduleSQLDataFile);
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
        echo "<span class=\"ok\">" . $_lang['ok'] . "</span></p>";
    }
}

// always empty cache after install
$modx->clearCache('full');


// setup completed!
echo "<p><b>" . $_lang['installation_successful'] . "</b></p>";


// Property Update function
function propUpdate($new, $old)
{
    $newArr = parseProperties($new);
    $oldArr = parseProperties($old);
    foreach ($oldArr as $k => $v) {
        if (isset($v['0']['options'])) {
            $oldArr[$k]['0']['options'] = $newArr[$k]['0']['options'];
        }
    }
    $return = $oldArr + $newArr;
    $return = json_encode($return, JSON_UNESCAPED_UNICODE);
    $return = ($return != '[]') ? $return : '';
    return $return;
}

function parseProperties($propertyString, $json = false)
{
    $propertyString = str_replace('{}', '', $propertyString);
    $propertyString = str_replace('} {', ',', $propertyString);

    if (empty($propertyString) || $propertyString == '{}' || $propertyString == '[]') {
        $propertyString = '';
    }

    $jsonFormat = isJson($propertyString, true);
    $property = array();
    // old format
    if ($jsonFormat === false) {
        $props = explode('&', $propertyString);
        $arr = array();
        $key = array();
        foreach ($props as $prop) {
            if ($prop != '') {
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
    } else if (!empty($jsonFormat)) {
        $property = $jsonFormat;
    }
    if ($json) {
        $property = json_encode($property, JSON_UNESCAPED_UNICODE);
    }
    $property = ($property != '[]') ? $property : '';
    return $property;
}

function isJson($string, $returnData = false)
{
    $data = json_decode($string, true);
    return (json_last_error() == JSON_ERROR_NONE) ? ($returnData ? $data : true) : false;
}

function getCreateDbCategory($category)
{
    $category_id = 0;
    if (!empty($category)) {
        $categoryDbRecord = \EvolutionCMS\Models\Category::query()->where('category', $category)->first();
        if (!is_null($categoryDbRecord)) {
            $category_id = $categoryDbRecord->getKey();
        } else {
            $category_id = \EvolutionCMS\Models\Category::query()->insertGetId(['category' => $category]);
        }
    }
    return $category_id;
}
