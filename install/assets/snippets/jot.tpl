//<?php
/**
 * Jot
 * 
 * User comments with moderation and email subscription
 *
 * @category 	snippet
 * @version 	1.1.4
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties
 * @internal	@modx_category Content
 * @internal    @installset base, sample
 */


/*####
#
# Author: Armand "bS" Pondman (apondman@zerobarrier.nl)
#
# Latest Version: http://modx.com/extras/package/jot
# Jot Demo Site: http://projects.zerobarrier.nl/modx/
# Documentation: http://wiki.modxcms.com/index.php/Jot (wiki)
#
####*/

$jotPath = $modx->config['base_path'] . 'assets/snippets/jot/';
include_once($jotPath.'jot.class.inc.php');

$Jot = new CJot;
$Jot->VersionCheck("1.1.4");
$Jot->Set("path",$jotPath);
$Jot->Set("action", $action);
$Jot->Set("postdelay", $postdelay);
$Jot->Set("docid", $docid);
$Jot->Set("tagid", $tagid);
$Jot->Set("subscribe", $subscribe);
$Jot->Set("moderated", $moderated);
$Jot->Set("captcha", $captcha);
$Jot->Set("badwords", $badwords);
$Jot->Set("bw", $bw);
$Jot->Set("sortby", $sortby);
$Jot->Set("numdir", $numdir);
$Jot->Set("customfields", $customfields);
$Jot->Set("guestname", $guestname);
$Jot->Set("canpost", $canpost);
$Jot->Set("canview", $canview);
$Jot->Set("canedit", $canedit);
$Jot->Set("canmoderate", $canmoderate);
$Jot->Set("trusted", $trusted);
$Jot->Set("pagination", $pagination);
$Jot->Set("placeholders", $placeholders);
$Jot->Set("subjectSubscribe", $subjectSubscribe);
$Jot->Set("subjectModerate", $subjectModerate);
$Jot->Set("subjectAuthor", $subjectAuthor);
$Jot->Set("notify", $notify);
$Jot->Set("notifyAuthor", $notifyAuthor);
$Jot->Set("validate", $validate);
$Jot->Set("title", $title);
$Jot->Set("authorid", $authorid);
$Jot->Set("css", $css);
$Jot->Set("cssFile", $cssFile);
$Jot->Set("cssRowAlt", $cssRowAlt);
$Jot->Set("cssRowMe", $cssRowMe);
$Jot->Set("cssRowAuthor", $cssRowAuthor);
$Jot->Set("tplForm", $tplForm);
$Jot->Set("tplComments", $tplComments);
$Jot->Set("tplModerate", $tplModerate);
$Jot->Set("tplNav", $tplNav);
$Jot->Set("tplNotify", $tplNotify);
$Jot->Set("tplNotifyModerator", $tplNotifyModerator);
$Jot->Set("tplNotifyAuthor", $tplNotifyAuthor);
$Jot->Set("tplSubscribe", $tplSubscribe);
$Jot->Set("debug", $debug);
$Jot->Set("output", $output);
return $Jot->Run();