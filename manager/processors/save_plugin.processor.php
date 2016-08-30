<?php 
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
if(!$modx->hasPermission('save_plugin')) {  
    $modx->webAlertAndQuit($_lang["error_no_privileges"]);
}

$id = intval($_POST['id']);
$name = $modx->db->escape(trim($_POST['name']));
$description = $modx->db->escape($_POST['description']);
$locked = $_POST['locked']=='on' ? '1' : '0' ;
$plugincode = $modx->db->escape($_POST['post']);
$properties = $modx->db->escape($_POST['properties']);
$disabled = $_POST['disabled']=="on" ? '1' : '0';
$moduleguid = $modx->db->escape($_POST['moduleguid']);
$sysevents = $_POST['sysevents'];
$parse_docblock = $_POST['parse_docblock']=="1" ? '1' : '0';

//Kyle Jaebker - added category support
if (empty($_POST['newcategory']) && $_POST['categoryid'] > 0) {
    $categoryid = intval($_POST['categoryid']);
} elseif (empty($_POST['newcategory']) && $_POST['categoryid'] <= 0) {
    $categoryid = 0;
} else {
    include_once(MODX_MANAGER_PATH.'includes/categories.inc.php');
    $categoryid = getCategory($_POST['newcategory']);   
}

if($name=="") $name = "Untitled plugin";

if($parse_docblock) {
    $parsed       = $modx->parseDocBlockFromString($plugincode, true);
    $name         = isset($parsed['name']) ? $parsed['name'] : $name;
    $sysevents    = isset($parsed['events']) ? explode(',', $parsed['events']) : $sysevents;
    $properties   = isset($parsed['properties']) ? $parsed['properties'] : $properties;
    $moduleguid   = isset($parsed['guid']) ? $parsed['guid'] : $moduleguid;
    
    $description  = isset($parsed['description']) ? $parsed['description'] : $description;
    $version      = isset($parsed['version']) ? '<b>'.$parsed['version'].'</b> ' : '';
    if($version) {
        $description = $version . trim(preg_replace('/(<b>.+?)+(<\/b>)/i', '', $description));
    }
    if(isset($parsed['modx_category'])) {
        include_once(MODX_MANAGER_PATH.'includes/categories.inc.php');
        $categoryid = getCategory($parsed['modx_category']);
    }
}

$tblSitePlugins = $modx->getFullTableName('site_plugins');
$eventIds = array();
switch ($_POST['mode']) {
    case '101':
       
        // invoke OnBeforePluginFormSave event
        $modx->invokeEvent("OnBeforePluginFormSave",
                                array(
                                    "mode"  => "new",
                                    "id"    => $id
                                ));
    
		// disallow duplicate names for active plugins
		if ($disabled == '0') {
			$rs = $modx->db->select('COUNT(id)', $modx->getFullTableName('site_plugins'), "name='{$name}' AND disabled='0'");
			$count = $modx->db->getValue($rs);
			if($count > 0) {
				$modx->manager->saveFormValues(101);
				$modx->webAlertAndQuit(sprintf($_lang['duplicate_name_found_general'], $_lang['plugin'], $name), "index.php?a=101");
			}
		}

		//do stuff to save the new plugin
		$newid = $modx->db->insert(
			array(
				'name'       => $name,
				'description' => $description,
				'plugincode'  => $plugincode,
				'disabled'    => $disabled,
				'moduleguid'  => $moduleguid,
				'locked'      => $locked,
				'properties'  => $properties,
				'category'    => $categoryid,
			), $tblSitePlugins);
            
            // save event listeners
            saveEventListeners($newid,$sysevents,$_POST['mode']);
            
            // invoke OnPluginFormSave event
            $modx->invokeEvent("OnPluginFormSave",
                                    array(
                                        "mode"  => "new",
                                        "id"    => $newid
                                    ));
            
		// Set the item name for logger
		$_SESSION['itemname'] = $name;

            // empty cache
            $modx->clearCache('full');

            // finished emptying cache - redirect
            if($_POST['stay']!='') {
                $a = ($_POST['stay']=='2') ? "102&id=$newid":"101";
                $header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
                header($header);
            } else {
                $header="Location: index.php?a=76&r=2";
                header($header);
            }
        break;
    case '102':

        // invoke OnBeforePluginFormSave event
        $modx->invokeEvent("OnBeforePluginFormSave",
                                array(
                                    "mode"  => "upd",
                                    "id"    => $id
                                ));
     
		// disallow duplicate names for active plugins
	    if ($disabled == '0') {
			$rs = $modx->db->select('COUNT(*)', $modx->getFullTableName('site_plugins'), "name='{$name}' AND id!='{$id}' AND disabled='0'");
			if ($modx->db->getValue($rs) > 0) {
				$modx->manager->saveFormValues(102);
				$modx->webAlertAndQuit(sprintf($_lang['duplicate_name_found_general'], $_lang['plugin'], $name), "index.php?a=102&id={$id}");
			}
	    }

        //do stuff to save the edited plugin    
        $modx->db->update(
            array(
                'name'         => $name,
                'description'  => $description,
                'plugincode'   => $plugincode,
                'disabled'     => $disabled,
                'moduleguid'   => $moduleguid,
                'locked'       => $locked,
                'properties'   => $properties,
                'category'     => $categoryid,
            ), $tblSitePlugins, "id='{$id}'");

            // save event listeners
            saveEventListeners($id,$sysevents,$_POST['mode']);

            // invoke OnPluginFormSave event
            $modx->invokeEvent("OnPluginFormSave",
                                    array(
                                        "mode"  => "upd",
                                        "id"    => $id
                                    ));
            
		// Set the item name for logger
		$_SESSION['itemname'] = $name;

            // empty cache
            $modx->clearCache('full');

            // finished emptying cache - redirect   
            if($_POST['stay']!='') {
                $a = ($_POST['stay']=='2') ? "102&id=$id":"101";
                $header="Location: index.php?a=".$a."&r=2&stay=".$_POST['stay'];
                header($header);
            } else {
                $header="Location: index.php?a=76&r=2";
                header($header);
            }
        break;
    default:
		$modx->webAlertAndQuit("No operation set in request.");
}


# Save Plugin Event Listeners
function saveEventListeners($id,$sysevents,$mode) {
    global $modx;
    // save selected system events
    $tblSitePluginEvents = $modx->getFullTableName('site_plugin_events');
    $insert_sysevents = array();
    for($i=0;$i<count($sysevents);$i++){
        $evtId = $sysevents[$i];
        $evtId = !is_numeric($evtId) ? getEventIdByName($evtId) : $evtId;
        if ($mode == '101') {
            $rs = $modx->db->select('max(priority) as priority', $tblSitePluginEvents, "evtid='{$evtId}'");
        } else {
            $rs = $modx->db->select('priority', $tblSitePluginEvents, "evtid='{$evtId}' and pluginid='{$id}'");
        }
        $prevPriority = $modx->db->getValue($rs);
        if ($mode == '101') {
            $priority = isset($prevPriority) ? $prevPriority + 1 : 1;
        } else {
            $priority = isset($prevPriority) ? $prevPriority : 1;
        }
        $insert_sysevents[] = array('pluginid'=>$id,'evtid'=>$evtId,'priority'=>$priority);
    }
    $modx->db->delete($tblSitePluginEvents, "pluginid='{$id}'");
    foreach ($insert_sysevents as $insert_sysevent) {
        $modx->db->insert($insert_sysevent, $tblSitePluginEvents);
    }
}

function getEventIdByName($name) {
    global $modx, $eventIds;
    if(empty($eventIds)) {
        $rs = $modx->db->select('id, name', $modx->getFullTableName('system_eventnames'));
        while ($row = $modx->db->getRow($rs)) 
            $eventIds[$row['name']] = $row['id']; 
    }
    return $eventIds[$name];
}
?>