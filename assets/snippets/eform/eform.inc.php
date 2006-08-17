<?php
#
# eForm 1.2 (Electronic Form Snippet)
# Created By: Raymond Irving 15-Dec-2004.
# -----------------------------------------------------
# Modified by Jelle Jager to work with eFormParser.inc.php
#

# See eForm.snippet for usage info

# Modified:
# Add captcha image support - thanks to Djamoer 
# Add multi checkbox, radio, select support - thanks to Djamoer 

function eForm($modx,$params) {

	global $eFormOnBeforeMailSent,$eFormOnMailSent;
	
	extract($params,EXTR_SKIP); // extract params into variables
	
	# check for valid form key
	if ($formid=="") return "Invalid Form Id number or name.";
	else $validFormId = isset($_POST['formid'])? ($formid==$_POST['formid']):false;
	
	# activate nomail if missing $to
	if (!$to) $nomail = 1;
	
	# check if postback mode
	$isPostBack	= ($validFormId && count($_POST)>0)? true:false;

	# load autotext template
	if (strlen($autotext)<50) {
		if(is_numeric($autotext)) $autotext = ($doc=$modx->getDocument($autotext)) ? $doc['content']:"Document id '$autotext' not found.";
		else if($autotext) $autotext = ($chunk=$modx->getChunk($autotext)) ? $chunk:"Chunk '$autotext' not found.";
	}

	# load report template
	$report_tpl_id = $report;
	if (strlen($report)<50) {
		if(is_numeric($report)) $report = ($doc=$modx->getDocument($report)) ? $doc['content']:"Document id '$report' not found.";
		else if($report) $report = ($chunk=$modx->getChunk($report)) ? $chunk:"Chunk '$report' not found.";
	}

	# load form template
	if (strlen($tpl)<50) {
		if(is_numeric($tpl)) $tpl = ($doc=$modx->getDocument($tpl)) ? $doc['content']:"Document id '$tpl' not found.";
		else if($tpl) $tpl = ($chunk=$modx->getChunk($tpl)) ? $chunk:"Chunk '$tpl' not found.";
	}

/* Mod by JJ - build placeholders and formats from the form template
 * &format no longer required in snippet call, nor are placeholders
 * see eFormParser.inc.php for usage details. if however the format parameter 
 * is declared in the snippet call it will be used instead. This is to keep 
 * the original default behaviour of eForm intact.
 */
 	global $formats;
	if(empty($format) ){
		include_once $snipPath."eform/eFormParser.inc.php";
		$tpl = eFormParseTemplate($tpl);
		foreach($formats as $k => $discard)	$fields[$k] = ""; // store dummy value inside $fields
	}else{
		# load format from snippet - field_name:field_description:field_datatype:field_required
		$flds = explode(",",$format);
		foreach($flds as $f) {
			$f = explode(":",$f);
			if (count($f)>0) {
				$formats[$f[0]] = $f;
				$fields[$f[0]] = ""; // store dummy value inside $fields
			}
		}
		#in case we do have eform pseudo attributes clean them
		
		 
	}
//end mod

	if ($isPostBack) {

		$vMsg = array();

//djamoer's mod to cater for arrays in $_POST
		# get user post back data
		foreach($_POST as $name => $value){
			if(is_array($value))
				$fields[$name] = $value;
			else
				$fields[$name]	= stripslashes(($allowhtml || $formats[$name][2]=='html')? $value:$modx->stripTags($value));
		}
//end mod

		# get uploaded files
		foreach($_FILES as $name => $value){
			$fields[$name] = $value;
		}
				
		# check vericode
		if($vericode) {
			//add support for captcha code - thanks to Djamoer 
			$code = $_SESSION['veriword'] ? $_SESSION['veriword'] : $_SESSION['eForm.VeriCode'];
			if($fields['vericode']!=$code) {
				$vMsg[count($vMsg)]="Invalid verification code.";
			}
		}

		# validate fields
		foreach($fields as $name => $value) {
			$fld = $formats[$name];
			if ($fld) {			
				$desc		= $fld[1];
				$datatype 	= $fld[2];
				$isRequired = $fld[3];	
				switch ($datatype)  {
					case "string": 
						if ($isRequired==1 && $value=="") $vMsg[count($vMsg)]="$desc is required";
						break;
					case "integer": 
						if ($isRequired==1 && $value=="") $vMsg[count($vMsg)]="$desc is required";
						elseif (!is_numeric($value)) $vMsg[count($vMsg)]="$desc is not a valid number";
						break;
					case "float": 
						if ($isRequired==1 && $value=="") $vMsg[count($vMsg)]="$desc is required";
						elseif (!is_numeric($value)) $vMsg[count($vMsg)]="$desc is not a valid number";
						break;
					case "date": 
						if ($isRequired==1 && $value=="") $vMsg[count($vMsg)]="$desc is required";
						elseif (strtotime($value)===-1) $vMsg[count($vMsg)]="$desc is not a valid date";
						break;
					case "email":
						if ($isRequired==1 && $value=="") $vMsg[count($vMsg)]="$desc is required";
						else if (strlen($value)>0 && !preg_match('/^[a-z0-9_.-]+@[a-z0-9.-]+\.[a-z]+$/',$value)) $vMsg[count($vMsg)]="$desc is not a valid email address";
						break;
					case "html": 
						if ($isRequired==1 && $value=="") $vMsg[count($vMsg)]="$desc is required";
						break;
					case "file": 
						if ($_FILES[$name]['error']==1 || $_FILES[$name]['error']==2) $vMsg[count($vMsg)]="$desc has exceeded maximum upload limit.";
						else if ($isRequired==1 && ($_FILES[$name] && $_FILES[$name]['type']=='')) $vMsg[count($vMsg)]="$desc is required";
						else if ($_FILES[$name]['tmp_name']) $attachments[count($attachments)] = $_FILES[$name]['tmp_name'];
						break;
					default:
						if ($isRequired==1 && $value=="") $vMsg[count($vMsg)]="$desc is required";
						break;
				}			
			}
		}

		if(count($vMsg)>0){
			# set validation error message
			$fields['validationmessage'] = '<p class="error">'.implode("<br />",$vMsg).'</p>';
		}
		else {

			# format report fields
			foreach($fields as $name => $value) {
				$fld = $formats[$name];
				if ($fld) {			
					$datatype = $fld[2];
					switch ($datatype)  {
						case "integer": 
							$value = number_format($value);
							break;
						case "float": 
							$value = number_format($value, 2, '.', ',');
							break;
						case "date": 
							$value = ($value)? strftime("%d-%b-%Y %H:%M:%S",strtotime($value)):"";
							$value=str_replace("00:00:00","",$value);// remove trailing zero time values
							break;
						case "html": 
							// convert \n to <br>
							$value = str_replace("\n","<br />",$value);
							break;
						case "file": 
							// set file name
							if($value['type']!="" && $value['type']!=""){
								$value = $value["name"];
								$patharray = explode(((strpos($value,"/")===false)? "\\":"/"), $value);
								$value = $patharray[count($patharray)-1];							
							}
							else {
								$value = "";
							}
							break;
					}
					$fields[$name] = $value;
				}
			}

			# set postdate
			$fields['postdate'] = strftime("%d-%b-%Y %H:%M:%S",time());;

/*
* Note by JJ
* Remember to treat parameter as a reference in function with OnBeforeMailSent
* eg.
* $eFormOnbeforeMailSent= 'myCoolFunctionName';
* function $eFormOnBeforeMailSent( &$fields ) {
*    //code to manipulate the variable treated as a reference goes here
* }
*/
			# invoke OnBeforeMailSent event set by another script
			if ($eFormOnBeforeMailSent) {
				if ($eFormOnBeforeMailSent($fields)===false) return;
			}

            $from = formMerge($from,$fields);
            $fromname = formMerge($fromname,$fields);
			$subject	= ($subject)? formMerge($subject,$fields):"$category";
			$keywords	= ($keywords)? formMerge($keywords,$fields):"";
			$report		=  formMerge($report,$fields);

			# check for mail selector field - select an email from to list 
			if ($mselector && $fields[$mselector]) {
				$i = (int)$fields[$mselector];
				$ar = explode(",",$to);
				if ($i>0) $i--;
				if ($ar[$i]) $to = $ar[$i];
				else $to = $ar[0];
			}


			# include PHP Mailer
			include_once "manager/includes/controls/class.phpmailer.php";

			//set reply-to address
			//$replyto snippet parameter must contain fieldname
			//$replyto = ( $fields[$replyto] && strstr($fields[$replyto],'@') )?$fields[$replyto]:$from;

			
			# send form
			if(!$noemail) {
				if($sendirect) $to = $fields['email'];
				$mail = new PHPMailer();
				$mail->IsMail();
				$mail->IsHTML(true);
				$mail->From		= $from;
				$mail->FromName	= $fromname;
				$mail->Subject	= $subject;
				$mail->Body		= $report;
				AddAddressToMailer($mail,"to",$to);
				AddAddressToMailer($mail,"cc",$cc);
				AddAddressToMailer($mail,"bcc",$bcc);
//mod by JJ - add reply-to address
				//AddAddressToMailer($mail,"replyto",$replyto);
//end mod
				AttachFilesToMailer($mail,$attachments);
				if(!$mail->send()) return $mail->ErrorInfo;
			}

			# send user a copy of the report
			if($ccsender && $fields['email']) {
				$mail = new PHPMailer();
				$mail->IsMail();
				$mail->IsHTML(true);
				$mail->From		= $from;
				$mail->FromName	= $fromname;
				$mail->Subject	= $subject;
				$mail->Body		= $report;
				AddAddressToMailer($mail,"to",$fields['email']);
				AttachFilesToMailer($mail,$attachments);
				if(!$mail->send()) return $mail->ErrorInfo;
			}

			# send auto-respond email
			if ($autotext && $fields['email']!='') {
				$autotext = formMerge($autotext,$fields);
				$mail = new PHPMailer();
				$mail->IsMail();
				$mail->IsHTML(true);
				$mail->From		= ($autosender)? $autosender:$from;
				$mail->FromName	= $fromname;
				$mail->Subject	= $subject;
				$mail->Body		= $autotext;
				AddAddressToMailer($mail,"to",$fields['email']);
				if(!$mail->send()) return $mail->ErrorInfo;
			}

			# send mobile email
			if ($mobile && $mobiletext) {
				$mobiletext = formMerge($mobiletext,$fields);
				$mail = new PHPMailer();
				$mail->IsMail();
				$mail->IsHTML(false);
				$mail->From		= $from;
				$mail->FromName	= $fromname;
				$mail->Subject	= $subject;
				$mail->Body		= $mobiletext;
				AddAddressToMailer($mail,"to",$mobile);
				$mail->send();
			}
/*
* Remember to treat parameter as a reference in function!!
* eg.
* $eFormOnMailSent= 'myCoolFunctionName';
* function $eFormOnMailSent(&$fields) {
    //code to manipulate the variable treated as a reference goes here
*}
*/
			# invoke OnMailSent event set by another script
			if ($eFormOnMailSent) {
				if ($eFormOnMailSent($fields)===false) return;
			}

			
			# show or redirect to thank you page		
			if ($gid==$modx->documentIdentifier){
				return "<h2>Thank You!</h2><div id=\"messagebox\"><p class=\"info\"> Your information was successfully submitted. <a href=\"[~[*id*]~]\">submit another form</a></p></div>";
			}
			else {
				$url = $modx->makeURL($gid);
				$modx->sendRedirect($url);
			}
			return; // stop here
		}
	}

	// set vericode
	if($vericode) {
		$_SESSION['eForm.VeriCode'] = $fields['vericode'] = substr(uniqid(''),-5);
		$fields['verimageurl'] = $modx->config['base_url'].'manager/includes/veriword.php?rand='.rand();
	}

	# build form
	return formMerge($tpl,$fields);
}

# Form Merge
function formMerge($docText, $docFields) {
	global $formats;
	$lastitems;
	if(!docText) return '';
	preg_match_all('~\[\+(.*?)\+\]~', $docText, $matches);
	for($i=0;$i<count($matches[1]);$i++) {
		$name = $matches[1][$i];
//mod by TobyL - splitting name:value to get proper docFields key
		list($listName,$listValue) = explode(":",$name);
		$value = isset($docFields[$listName])? $docFields[$listName]:"";
//end mod
		// support for multi checkbox, radio and select - Djamoer 
		if(is_array($value)) $value=implode(', ', $value);
		$fld = $formats[$name]; 
		if (!isset($fld)){			
			// listbox, checkbox, radio select
			$colonPost = strpos($name, ':');
			$listName = substr($name, 0, $colonPost);
			$listValue = substr($name, $colonPost+1);
			$datatype = $formats[$listName][2];
			if(is_array($docFields[$listName])) {
				if($datatype=="listbox" && in_array($listValue, $docFields[$listName])) $docText = str_replace("[+$listName:$listValue+]","selected='selected'",$docText);
				if(($datatype=="checkbox"||$datatype=="radio") && in_array($listValue, $docFields[$listName])) $docText = str_replace("[+$listName:$listValue+]","checked='checked'",$docText);
			}
			else {
				if($datatype=="listbox" && $listValue==$docFields[$listName]) $docText = str_replace("[+$listName:$listValue+]","selected='selected'",$docText);
				if(($datatype=="checkbox"||$datatype=="radio") && $listValue==$docFields[$listName]) $docText = str_replace("[+$listName:$listValue+]","checked='checked'",$docText);
			}
/*			$datatype = $fld[2];
			if($datatype=="listbox") $docText = str_replace("[+$name:$value+]","selected='selected'",$docText);
			if($datatype=="checkbox"||$datatype=="radio") $docText = str_replace("[+$name:$value+]","checked='checked'",$docText);
*/			
		}			
		if(strpos($name,":")===false) $docText = str_replace("[+$name+]",$value,$docText);
		else {
			// this might be a listbox item. 
			// we'll remove this field later
			$lastitems[count($lastitems)]="[+$name+]";
		}
	}
	$docText = str_replace($lastitems,"",$docText);
	return $docText;
}

# Adds Addresses to Mailer
function AddAddressToMailer(&$mail,$type,$addr){
	$a = explode(",",$addr);
	for($i=0;$i<count($a);$i++){
		if(!empty($a[$i])) {
			if ($type=="to") $mail->AddAddress($a[$i]);
			elseif ($type=="cc") $mail->AddCC($a[$i]);
			elseif ($type=="bcc") $mail->AddBCC($a[$i]);
//mod by JJ - add replyto field
			//elseif ($type=="replyto") $mail->AddReplyTo($a[$i]);
//end mod
		}
	}

}

# Attach Files to Mailer
function AttachFilesToMailer(&$mail,&$attachFiles) {
	if(count($attachFiles)>0){
		foreach($attachFiles as $attachFile){
			if(!file_exists($attachFile)) continue;
			$FileName = $attachFile;
			$contentType = "application/octetstream";
			if (is_uploaded_file($attachFile)){
				foreach($_FILES as $n => $v){
					if($_FILES[$n]['tmp_name']==$attachFile) {
						$FileName = $_FILES[$n]['name'];
						$contentType = $_FILES[$n]['type'];
					}
				}
			}
			$patharray = explode(((strpos($FileName,"/")===false)? "\\":"/"), $FileName);
			$FileName = $patharray[count($patharray)-1];
			$mail->AddAttachment($attachFile,$FileName,"base64",$contentType); 
		}
	}
}
?>
