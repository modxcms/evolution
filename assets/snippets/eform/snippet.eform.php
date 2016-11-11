<?php
/**
 * eForm
 *
 * Robust form parser/processor with validation, multiple sending options, chunk/page support for forms and reports, and file uploads
 *
 * @category   snippet
 * @version    1.4.8
 * @license    http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal   @properties
 * @internal   @modx_category Forms
 * @internal   @installset base, sample
 * @documentation MODX Docs https://rtfm.modx.com/extras/evo/eform
 * @documentation History, usage and examples [+site_url+]assets/snippets/eform/docs/eform.htm
 * @reportissues https://github.com/modxcms/evolution
 * @author      Original created by Raymond Irving 15-Dec-2004.
 * @author      v1.3+ extended by Jelle Jager (TobyL) September 2006
 * @author      Captcha image support - thanks to Djamoer
 * @author      Multi checkbox, radio, select support - thanks to Djamoer
 * @author      Form Parser and extended validation - by Jelle Jager
 * @author      and many others
 * @lastupdate  11/04/2016
 */
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

# Set Snippet Paths
$snipFolder = isset($snipFolder)?$snipFolder:'eform';
$snipPath = $modx->config["base_path"].'assets/snippets/'.$snipFolder.'/';


# check if inside manager
if ($modx->isBackend()) {
return ''; # don't go any further when inside manager
}

//tidying up some casing errors in parameters
if(isset($eformOnValidate))        $eFormOnValidate        = $eformOnValidate;
if(isset($eformOnBeforeMailSent))  $eFormOnBeforeMailSent  = $eformOnBeforeMailSent;
if(isset($eformOnMailSent))        $eFormOnMailSent        = $eformOnMailSent;
if(isset($eformOnBeforeFormMerge)) $eFormOnBeforeFormMerge = $eformOnBeforeFormMerge;
if(isset($eformOnBeforeFormParse)) $eFormOnBeforeFormParse = $eformOnBeforeFormParse;
//for sottwell :)
if(isset($eFormCSS)) $cssStyle = $eFormCSS;

# Snippet customize settings
$_params = array (
   // Snippet Path
   'snipPath' => $snipPath, //includes $snipFolder
   'snipFolder' => $snipFolder,

// eForm Params
   'vericode' => isset($vericode)? $vericode:"",
   'formid' => isset($formid)? $formid:"",
   'from' => isset($from)? $from:$modx->config['emailsender'],
   'fromname' => isset($fromname)? $fromname:$modx->config['site_name'],
   'to' => isset($to)? $to:$modx->config['emailsender'],
   'cc' => isset($cc)? $cc:"",
   'bcc' => isset($bcc)? $bcc:"",
   'subject' => isset($subject)? $subject:"",
   'ccsender' => isset($ccsender) ? $ccsender:0,
   'sendirect' => isset($sendirect)? $sendirect:0,
   'mselector' => isset($mailselector)? $mailselector:0,
   'mobile' => isset($mobile)? $mobile:'',
   'mobiletext' => isset($mobiletext)? $mobiletext:'',
   'autosender' => isset($autosender)? $autosender:$from,
   'autotext' => isset($automessage)? $automessage:"",
   'category' => isset($category)? $category:0,
   'keywords' => isset($keywords)? $keywords:"",
   'gid' => isset($gotoid)? $gotoid:$modx->documentIdentifier,
   'noemail' => isset($noemail)? ($noemail):false,
   'saveform' => isset($saveform)? ($saveform? true:false):true,
   'tpl' => isset($tpl)? $tpl:"",
   'report' => isset($report)? $report:"",
   'allowhtml' => isset($allowhtml)? $allowhtml:0,
   //Added by JJ
   'replyto' => isset($replyto)? $replyto:"",
   'language' => isset($language)? $language:$modx->config['manager_language'],
   'thankyou' => isset($thankyou)? $thankyou:"",
   'isDebug' => isset($debug)? $debug:0,
   'reportAbuse' => isset($reportAbuse)? $reportAbuse:false,
   'disclaimer' => isset($disclaimer)?$disclaimer:'',
   'sendAsHtml' => isset($sendAsHtml)?$sendAsHtml:false,
   'sendAsText' => isset($sendAsText)?$sendAsText:false,
   'sessionVars' => isset($sessionVars)?$sessionVars:false,
   'postOverides' => isset($postOverides)?$postOverides:0,
   'eFormOnBeforeMailSent' => isset($eFormOnBeforeMailSent)?$eFormOnBeforeMailSent:'',
   'eFormOnMailSent' => isset($eFormOnMailSent)?$eFormOnMailSent:'',
   'eFormOnValidate' => isset($eFormOnValidate)?$eFormOnValidate:'',
   'eFormOnBeforeFormMerge' => isset($eFormOnBeforeFormMerge)?$eFormOnBeforeFormMerge:'',
   'eFormOnBeforeFormParse' => isset($eFormOnBeforeFormParse)?$eFormOnBeforeFormParse:'',
   'cssStyle' => isset($cssStyle)?$cssStyle:'',
   'jScript' => isset($jScript)?$jScript:'',
   'submitLimit' => (isset($submitLimit) &&  is_numeric($submitLimit))?$submitLimit*60:0,
   'protectSubmit' => isset($protectSubmit)?$protectSubmit:1,
   'requiredClass' => isset($requiredClass)?$requiredClass:"required",
   'invalidClass' => isset($invalidClass)?$invalidClass:"invalid",
   'runSnippet' => ( isset($runSnippet) && !is_numeric($runSnippet) )?$runSnippet:'',
   'autoSenderName' => isset($autoSenderName)?$autoSenderName:'',
   'attachmentField' => isset($attachmentField)?$attachmentField:'',
   'attachmentPath' => isset($attachmentPath)?$attachmentPath:'',
   'errorTpl' => isset($errorTpl)?$errorTpl:'<div class="errors"><strong>[+ef_message_text+]</strong><br />[+ef_wrapper+]</div>',
   'errorRequiredTpl' => isset($errorRequiredTpl)?$errorRequiredTpl:'<span class="requiredlist"><span>[+ef_required_list+]</span>.</span>',
   'errorRequiredSeparator' => isset($errorRequiredSeparator)?$errorRequiredSeparator:'</span>, <span>',
   'version' => '1.4.8'
);

// pixelchutes PHx workaround
foreach( $_params as $key=>$val ) $params[ $key ] = str_replace( array('((','))'), array('[+','+]'), $val );

# Start processing

include_once ($snipPath."eform.inc.php");

$output = eForm($modx,array_merge($_params,$params));

# Return
return $output;
?>
