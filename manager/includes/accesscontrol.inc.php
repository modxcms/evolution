<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODx Content Manager instead of accessing this file directly.");

// start session
startCMSSession();
if (isset($_SESSION['mgrValidated']) && $_SESSION['usertype']!='manager'){
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', 0);
    }
    session_destroy();
    // start session
//    startCMSSession();
}
if(!isset($_SESSION['mgrValidated'])){
    include_once("browsercheck.inc.php");

    if(isset($manager_language)) {
        include_once "lang/".$manager_language.".inc.php";
    }
    else {
        include_once "lang/english.inc.php";
    }

//    $cookieKey = substr(md5($site_id."Admin-User"),0,15);
//
//    include_once ("crypt.class.inc.php");
//    if(isset($_COOKIE[$cookieKey])) {
//        $cookieSet = 1;
//        $username = $_COOKIE[$cookieKey];
//    }
//    $thepasswd = substr($site_id,-5)."crypto"; // create a password based on site id
//    $rc4 = new rc4crypt;
//    $thestring = $rc4->endecrypt($thepasswd,$username,'de');
//    $uid = $thestring;

    $modx->setPlaceholder('modx_charset',$modx_charset);
    $modx->setPlaceholder('theme',$manager_theme);

    // invoke OnManagerLoginFormPrerender event
    $evtOut = $modx->invokeEvent('OnManagerLoginFormPrerender');
    $html = is_array($evtOut) ? implode('',$evtOut) : ''; 
    $modx->setPlaceholder('OnManagerLoginFormPrerender',$html);

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
    $modx->setPlaceholder('login_message',$_lang["login_message"]);
    if($use_captcha==1)  {
        $modx->setPlaceholder('login_captcha_message',$_lang["login_captcha_message"]);
        $modx->setPlaceholder('captcha_image','<a href="'.$_SERVER['PHP_SELF'].'" class="loginCaptcha"><img src="'.$modx->getManagerPath().'includes/veriword.php?rand='.rand().'" alt="'.$_lang["login_captcha_message"].'" /></a>');
        $modx->setPlaceholder('captcha_input','<label>'.$_lang["captcha_code"].'</label> <input type="text" name="captcha_code" tabindex="3" value="" />');
    }
    
    // login info
    $modx->setPlaceholder('uid',$uid); 
    $modx->setPlaceholder('username',$_lang["username"]); 
    $modx->setPlaceholder('password',$_lang["password"]); 

    // remember me
    $html =  isset($cookieSet) ? 'checked="checked"' :''; 
    $modx->setPlaceholder('remember_me',$html); 
    $modx->setPlaceholder('remember_username',$_lang["remember_username"]);
    $modx->setPlaceholder('login_button',$_lang["login_button"]);

    // invoke OnManagerLoginFormRender event
    $evtOut = $modx->invokeEvent('OnManagerLoginFormRender');
    $html = is_array($evtOut) ? '<div id="onManagerLoginFormRender">'.implode('',$evtOut).'</div>' : '';
    $modx->setPlaceholder('OnManagerLoginFormRender',$html);

    // load template file
    $tplFile = $base_path.'manager/media/style/'.$manager_theme.'/login.html';
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
else {
    // log user action
    if (getenv("HTTP_CLIENT_IP")) $ip = getenv("HTTP_CLIENT_IP");else if(getenv("HTTP_X_FORWARDED_FOR")) $ip = getenv("HTTP_X_FORWARDED_FOR");else if(getenv("REMOTE_ADDR")) $ip = getenv("REMOTE_ADDR");else $ip = "UNKNOWN";$_SESSION['ip'] = $ip;
    $itemid = isset($_REQUEST['id']) ? $_REQUEST['id'] : '' ;$lasthittime = time();$a = isset($_REQUEST['a']) ? $_REQUEST['a'] : "" ;
    if($a!=1) {
        if (!intval($itemid)) $itemid= 'NULL';
        $sql = "REPLACE INTO $dbase.`".$table_prefix."active_users` (internalKey, username, lasthit, action, id, ip) values(".$modx->getLoginUserID().", '{$_SESSION['mgrShortname']}', '{$lasthittime}', '{$a}', {$itemid}, '{$ip}')";
        if(!$rs = mysql_query($sql)) {
            echo "error replacing into active users! SQL: ".$sql."\n".mysql_error();
            exit;
        }
    }
}

?>
