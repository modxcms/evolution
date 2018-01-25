<?php
/**
 * Personalize
 *
 * Checks to see if web- / mgr-users are logged in or not, to display accordingly yesChunk/noChunk
 *
 * @category    snippet
 * @version     2.2
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal    @properties
 * @internal    @modx_category Login
 * @internal    @installset base
 * @documentation [+site_url+]assets/snippets/personalize/readme.html
 * @reportissues https://github.com/modxcms/evolution
 * @author      Created By Ryan Thrash (modx@vertexworks.com)
 * @author      powered up by kudo (kudo@kudolink.com)
 * @author      powered up by yama(yamamoto@kyms.ne.jp)
 * @author      Refactored 2013 by Dmi3yy
 * @lastupdate  11/08/2013
 */

if(!defined('MODX_BASE_PATH')) die('What are you doing? Get out of here!');

# prepare params and variables

if(!isset($ph)) $ph = 'username';
if(!isset($context)) {
    if     ($this->isFrontend() && isset($_SESSION['webValidated'])) $context = 'web';
    elseif ($this->isBackend()  && isset($_SESSION['mgrValidated'])) $context = 'mgr';
    else                                                             $context = '';
}

$output = '';
switch($context) {
    case 'web':
        $short_name = $_SESSION['webShortname'];
        $full_name  = $_SESSION['webFullname'];
        $email      = $_SESSION['webEmail'];
        $last_login = $_SESSION['webLastlogin'];
        break;
    case 'mgr':
    case 'manager':
        $short_name = $_SESSION['mgrShortname'];
        $full_name  = $_SESSION['mgrFullname'];
        $email      = $_SESSION['mgrEmail'];
        $last_login = $_SESSION['mgrLastlogin'];
        break;
    default:
        $short_name = '';
}

$modx->setPlaceholder($ph,          $short_name);
$modx->setPlaceholder('short_name', $short_name);
if(isset($full_name)) $modx->setPlaceholder('full_name', $full_name);
if(isset($email))     $modx->setPlaceholder('email',     $email);

if(!empty($context)) {
    if(isset($yesTV))        $output = $modx->getField($yesTV);
    elseif(isset($yesChunk)) $output = $modx->getChunk($yesChunk);
    else                     $output = "username : {$short_name}";
} else {
    if(isset($noTV))         $output = $modx->getField($noTV);
    elseif(isset($noChunk))  $output = $modx->getChunk($noChunk);
    else                     $output = 'guest';
}

if(!empty($context)) {
    if(empty($last_login)) $modx->setPlaceholder('last_login', 'first login');
    else                   $modx->setPlaceholder('last_login', $modx->toDateFormat($last_login));
}
else                       $modx->setPlaceholder('last_login', '');

return $output;
