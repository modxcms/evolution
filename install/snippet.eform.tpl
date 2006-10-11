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
$snipPath = $modx-config[base_path].assetssnippets;

# check if inside manager
if ($modx-isBackend()) {
	return ''; # don't go any further when inside manager
}

# Snippet customize settings
$params = array (
	 Snippet Path
	snipPath	= $snipPath,
	
	 eForm Params
	vericode	= isset($vericode) $vericode,
	formid 		= isset($formid) $formid,
	from 		= isset($from) $from$modx-config['emailsender'],
	fromname	= isset($fromname) $fromname$modx-config['site_name'],
	to		= isset($to) $to$modx-config['emailsender'],
	cc		= isset($cc) $cc,
	bcc		= isset($bcc) $bcc,
	subject		= isset($subject) $subject,
	ccsender	= isset($ccsender) 10,
	sendirect	= isset($sendirect) 10,
	mselector	= isset($mailselector) $mailselector0,
	mobile		= isset($mobile) $mobile'',
	mobiletext	= isset($mobiletext) $mobiletext'',
	autosender	= isset($autosender) $autosender$from,
	autotext	= isset($automessage) $automessage,
	category	= isset($category) $category,
	keywords	= isset($keywords) $keywords,
	gid 		= isset($gotoid) $gotoid$modx-documentIdentifier,
	noemail		= isset($noemail) truefalse,
	saveform	= isset($saveform) ($saveform truefalse)true,
	tpl		= isset($tpl) $tpl,
	report		= isset($report) $report,
	allowhtml	= isset($allowhtml) 10,
Added by JJC
	replyto		= isset($replyto) $replyto,
	language	= isset($language) $language$modx-config['manager_language'],
	thankyou	= isset($thankyou) $thankyou,
	isDebug 	= isset($debug) $debug0,
	reportAbuse	= isset($reportAbuse)$reportAbuse0,
	eFormOnBeforeMailSent = isset($eFormOnBeforeMailSent)$eFormOnBeforeMailSent'',
	eFormOnMailSent = isset($eFormOnMailSent)$eFormOnMailSent'',
	disclaimer	= isset($disclaimer)$disclaimer''

);


# Start processing
unset($formats,$fields);
include_once ($snipPath.eformeform.inc.php);

$output = eForm($modx,$params);

# Return
return $output;
