<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

if($_REQUEST['a']!='8' && isset($_SESSION['mgrValidated'])){
    
    $homeurl = $modx->makeUrl($manager_login_startup>0 ? $manager_login_startup:$site_start);
    $logouturl = './index.php?a=8';

    $modx->setPlaceholder('modx_charset',$modx_manager_charset);
    $modx->setPlaceholder('theme',$manager_theme);

    $modx->setPlaceholder('site_name',$site_name);
    $modx->setPlaceholder('logo_slogan',$_lang["logo_slogan"]);
    $modx->setPlaceholder('manager_lockout_message',$_lang["manager_lockout_message"]);

    $modx->setPlaceholder('home',$_lang["home"]);
    $modx->setPlaceholder('homeurl',$homeurl);
    $modx->setPlaceholder('logout',$_lang["logout"]);
    $modx->setPlaceholder('logouturl',$logouturl);

    // load template file
    $tplFile = $base_path.'assets/templates/manager/manager.lockout.html';
    $handle = fopen($tplFile, "r");
    $tpl = fread($handle, filesize($tplFile));
    fclose($handle);

    // merge placeholders
    $tpl = $modx->mergePlaceholderContent($tpl);
    $regx = strpos($tpl,'[[+')!==false ? '~\[\[\+(.*?)\]\]~' : '~\[\+(.*?)\+\]~'; // little tweak for newer parsers
    $tpl = preg_replace($regx, '', $tpl); //cleanup

    echo $tpl;    
    
    exit;
}

?>