<?php
$userID = abs($modx->getLoginUserID('web'));
$modx->qs_hash = md5($modx->qs_hash."^{$userID}^");

$groupNames = ($this->strlen($opt) > 0 ) ? explode(',',$opt) : array();

// if $groupNames is not an array return false
if(!is_array($groupNames)) return 0;

// Creates an array with all webgroups the user id is in
if (isset($modx->getModifiers()->cache['mo'][$userID])) $grpNames = $modx->getModifiers()->cache['mo'][$userID];
else {

    $grpNames = \EvolutionCMS\Models\WebgroupName::query()
        ->join('web_groups', 'webgroup_names.id', '=', 'web_groups.webgroup')
        ->where('webgroup.webuser', $userID)->pluck('webgroup_names.name');
    $modx->getModifiers()->cache['mo'][$userID] = $grpNames;
}

// Check if a supplied group matches a webgroup from the array we just created
foreach($groupNames as $k=>$v) {
    if(in_array(trim($v),$grpNames)) return 1;
}

// If we get here the above logic did not find a match, so return false
return 0;
