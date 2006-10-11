<?php
/*####
#
#	Name: Jot
#	Version: 1.0 BETA 8
#	Author: Armand "bS" Pondman (apondman@zerobarrier.nl)
#	Date: Oct 8, 2006 0:00 CET
#
####*/

include_once $modx->config['base_path'] . "assets/snippets/jot/jot.inc.php";

$Jot = new CJot;
$Jot->VersionCheck("1.0 Beta 8");
$Jot->Set("path",$modx->config['base_path'] . "assets/snippets/jot/");
$Jot->Set("action", $action);
$Jot->Set("postdelay", $postdelay);
$Jot->Set("docid", $docid);
$Jot->Set("tagid", $tagid);
$Jot->Set("subscribe", $subscribe);
$Jot->Set("moderated", $moderated);
$Jot->Set("captcha", $captcha);
$Jot->Set("badwords", $badwords);
$Jot->Set("bw", $bw);
$Jot->Set("customfields", $customfields);
$Jot->Set("guestname", $guestname);
$Jot->Set("canpost", $canpost);
$Jot->Set("canview", $canview);
$Jot->Set("canmoderate", $canmoderate);
$Jot->Set("trusted", $trusted);
$Jot->Set("pagination", $pagination);
$Jot->Set("placeholders", $placeholders);
$Jot->Set("subjectSubscribe", $subjectSubscribe);
$Jot->Set("subjectModerate", $subjectModerate);
$Jot->Set("notify", $notify);
$Jot->Set("authorid", $authorid);
$Jot->Set("cssRowAlt", $cssRowAlt);
$Jot->Set("cssRowMe", $cssRowMe);
$Jot->Set("cssRowAuthor", $cssRowAuthor);
$Jot->Set("tplForm", $tplForm);
$Jot->Set("tplComments", $tplComments);
$Jot->Set("tplModerate", $tplModerate);
$Jot->Set("tplNav", $tplNav);
$Jot->Set("tplNotify", $tplNotify);
$Jot->Set("tplNotifyModerator", $tplNotifyModerator);
$Jot->Set("tplSubscribe", $tplSubscribe);
$Jot->Set("debug", $debug);
$Jot->Set("output", $output);
return $Jot->Run();
?>