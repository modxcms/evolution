<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

if($_REQUEST['a']!=8 && isset($_SESSION['mgrValidated'])){
    
    $homeurl = $modx->makeUrl($manager_login_startup>0 ? $manager_login_startup:$site_start);
    $logouturl = './index.php?a=8';

    $modx->setPlaceholder('modx_charset',$modx_charset);
    $modx->setPlaceholder('theme',$manager_theme);

    // support info
    $html = '';
    $pth = dirname(__FILE__);
    $file = "$pth/support.inc.php";
    $ov_file = "$pth/override.support.inc.php"; // detect override file
    if(file_exists($ov_file)) $inc = include_once($ov_file);
    else if(file_exists($file)) $inc = include_once($file);
    if($inc)  {
        ob_start();
            showSupportLink();
            $html = ob_get_contents();
        ob_end_clean();
    }
    $modx->setPlaceholder('SupportInfo',$html);

    $modx->setPlaceholder('site_name',$site_name);
    $modx->setPlaceholder('logo_slogan',$_lang["logo_slogan"]);
    $modx->setPlaceholder('manager_lockout_message',$_lang["manager_lockout_message"]);

    $modx->setPlaceholder('home',$_lang["home"]);
    $modx->setPlaceholder('homeurl',$homeurl);
    $modx->setPlaceholder('logout',$_lang["logout"]);
    $modx->setPlaceholder('logouturl',$logouturl);

    // load template file
    $tplFile = $base_path.'manager/media/style/'.$manager_theme.'/manager.lockout.html';
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