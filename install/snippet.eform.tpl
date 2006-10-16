# eForm 1.4 - Electronic Form Snippet 
# based on eForm snippet
# Original created by Raymond Irving 15-Dec-2004.
# Extended by Jelle Jager (TobyL) September 2006
# -----------------------------------------------------
#
# Captcha image support - thanks to Djamoer
# Multi checkbox, radio, select support - thanks to Djamoer
# Form Parser and extened validation - by Jelle Jager
# 
# 
# see eform.inc.php for more details
#

# Set Snippet Paths 
$snipPath = $modx->config["base_path"]."assets/snippets/";

# check if inside manager
if ($modx->isBackend()) {
	return ''; # don't go any further when inside manager
}

# Snippet customize settings
$params = array (
	// Snippet Path
	snipPath	=> $snipPath,
	
	// eForm Params
	vericode	=> isset($vericode)? $vericode:"",
	formid 		=> isset($formid)? $formid:"",
	from 		=> isset($from)? $from:$modx->config['emailsender'],
	fromname	=> isset($fromname)? $fromname:$modx->config['site_name'],
	to			=> isset($to)? $to:$modx->config['emailsender'],
	cc			=> isset($cc)? $cc:"",
	bcc			=> isset($bcc)? $bcc:"",
	subject		=> isset($subject)? $subject:"",
	ccsender	=> isset($ccsender)? 1:0,
	sendirect	=> isset($sendirect)? 1:0,
	mselector	=> isset($mailselector)? $mailselector:0,
	mobile		=> isset($mobile)? $mobile:'',
	mobiletext	=> isset($mobiletext)? $mobiletext:'',
	autosender	=> isset($autosender)? $autosender:$from,
	autotext	=> isset($automessage)? $automessage:"",
	category	=> isset($category)? $category:0,
	keywords	=> isset($keywords)? $keywords:"",
	gid 		=> isset($gotoid)? $gotoid:$modx->documentIdentifier,
	noemail		=> isset($noemail)? true:false,
	saveform	=> isset($saveform)? ($saveform? true:false):true,
	tpl		=> isset($tpl)? $tpl:"",
	report		=> isset($report)? $report:"",
	allowhtml	=> isset($allowhtml)? 1:0,
//Added by JJ
	replyto		=> isset($replyto)? $replyto:"",
	language	=> isset($language)? $language:$modx->config['manager_language'],
	thankyou	=> isset($thankyou)? $thankyou:"",
	isDebug 	=> isset($debug)? $debug:0,
	reportAbuse	=> isset($reportAbuse)? $reportAbuse:false,
	eFormOnBeforeMailSent => isset($eFormOnBeforeMailSent)?$eFormOnBeforeMailSent:'',
	eFormOnMailSent => isset($eFormOnMailSent)?$eFormOnMailSent:'',
	disclaimer	=> isset($disclaimer)?$disclaimer:'',
	sendAsHtml => isset($sendAsHtml)?$sendAsHtml:false,
	sendAsText => isset($sendAsText)?$sendAsText:false
);


# Start processing

include_once ($snipPath."eform/eform.inc.php");

$output = eForm($modx,$params);

# Return
return $output;

