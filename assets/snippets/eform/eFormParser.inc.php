<?php
/*
* eFormParser 1.0 - an extension to eForm 1.2 by Raymond Irving
* Parses an eForm form template and adds placeholders for form fields
* It also sets the $formats array from a pseudo attribute in the form fields
* Created by Jelle Jager, May 2006
* ----------------------------------
* 
* Note. in order to use this file with eForm you'll have to hack eForm.inc.php
* (that is if you received this file without the accompanying hacked eForm version)
* This should probably be merged with eForm into a new snippet but while things are still
* cooking I've kept it separate.
* 
* 
* usage:
* Besides the [+vericode+],[+verimageurl+] and [+validationmessage+] placeholders
* you don't need to add placeholders to the form fields nor do you need to add the 
* &format parameter to the snippet call. You can set format parametes from within
* the form fields themselves.
* 
* To set server-side validation options for a field add the modx-options (pseudo)
* attribute to the form field. Format has (almost) remained the same except that 
* you skip the field-name and the field_datatype is optional. See snippet for 
* original options.
* 
* format: field_description:field_datatype:field_required
* You only need to set the following datatypes. Others will be set by default 
* (radio & checkbox as they are, string for textbox and listbox for select)
*	- field_datatype:
*			date, 
*			integer, 
*			float, 
*			email, 
*			html 		- will converts \n to <br />
* 
* examples:
* 1. Selectbox - set as required field
* <select name="mySelect" eform_options="Select Country::1" /> (datatype left blank)
* 	<option value="en-au">Australia</option>
* 	<option value="en-us">USA</option>
* </select>
* 
* 2. Textbox - required and format set to date
* <input type="text" name="theDate" eform_options="Date of Birth:date:1" />
* 
* 3. Multiple checkbox - required, eform_options only set once.
* <input type="checkbox" name="myColors[]" value="Red" eform_options="Colors::1" /> (datatype left blank)
* <input type="checkbox" name="myColors[]" value="Green" /> (datatype left blank)
* 
* Enjoy... 
*/

//should really be hard coded I guess
$GLOBALS['optionsName'] = "eform"; //name of pseudo attribute used for format settings	

function  eFormParseTemplate( $tpl ){
	global $formats,$optionsName;
	
	//retrieve all the form fields
	$regExpr = "#(<(input|select|textarea)[^>]*?>)#si";
	preg_match_all($regExpr,$tpl,$matches);
	$fieldTypes = $matches[2];
	$fieldTags = $matches[1];
	
	for($i=0;$i<count($fieldTypes);$i++){
		$type = $fieldTypes[$i];
	
		//get array of html attributes
		$tagAttributes = attr2array($fieldTags[$i]);
		//attribute values are stored including quotes
		//this avoids problems with embedded quotes 
		//strip quotes as well as any brackets to get the raw name
		$name = str_replace(array("'",'"','[',']'),'',$tagAttributes['name']);
		//exception for vericode field
        if($name=="vericode") continue;
        
		
		//store the field options 
		if (isset($tagAttributes[$optionsName])){ 
			$formats[$name] = explode(":",stripTagQuotes($tagAttributes[$optionsName])) ;
			array_unshift($formats[$name],$name);
		}
		unset($tagAttributes[$optionsName]);

		switch($type){
			case "select":
				
				//replace with 'cleaned' tag and added placeholder
				$newTag = buildTagPlaceholder('select',$tagAttributes,$name);
				$tpl = str_replace($fieldTags[$i],$newTag,$tpl);
				if($formats[$name]) $formats[$name][2]='listbox';
				
				//Get the whole select block with option tags
				$regExp = "#<select .*?name=".$tagAttributes['name']."[^>]*?".">(.*?)</select>#si";
				preg_match($regExp,$tpl,$matches);
				$optionTags = $matches[1];
				
				$select = $newSelect = $matches[0];
				//get separate option tags and split them up
				preg_match_all("#(<option [^>]*?>)#si",$optionTags,$matches);
				$validValues = array();
				foreach($matches[1] as $option){
					$attr = attr2array($option);
					$validValues[] = $attr['value'];
					$newTag = buildTagPlaceholder('option',$attr,$name);
					$newSelect = str_replace($option,$newTag,$newSelect);
				}
				//replace complete select block
				$tpl = str_replace($select,$newSelect,$tpl);
				//add valid values to formats... (extension to $formats)
				if($formats[$name] && !$formats[$name]['validate']) 
					$formats[$name]['validate']= "@LIST " . implode(",",$validValues);
				break;
				
			case "textarea":
				$newTag = buildTagPlaceholder($type,$tagAttributes,$name);
				$regExp = "#<textarea .*?name=" . $tagAttributes["name"] . "[^>]*?" . ">.*?</textarea>#si";
				preg_match($regExp,$tpl,$matches);
				$tpl = str_replace($matches[0],$newTag."[+$name+]</textarea>",$tpl);
				break;
			default: //all the rest, ie. "input"
				$newTag = buildTagPlaceholder($type,$tagAttributes,$name);
				  $fieldType = stripTagQuotes($tagAttributes['type']);
					if($formats[$name] && !$formats[$name][2]) $formats[$name][2]=($fieldType=='text')?"string":$fieldType;
				$tpl = str_replace($fieldTags[$i],$newTag,$tpl); 
				break;
		}
	}
	return $tpl;
}

function stripTagQuotes($value){
	$srch = array( "'", '"' );
	return str_replace($srch,'',$value);
}

function buildTagPlaceholder($tag,$attributes,$name){
	$type = stripTagQuotes($attributes["type"]); 
	$quotedValue = $attributes['value'];
	$val = stripTagQuotes($quotedValue);
	
	foreach ($attributes as $k => $v)
			$t .= ($k!='value' && $k!='checked' && $k!='selected')?" $k=$v":"";
	switch($tag){
		case "select":
			return "<$tag$t>"; //only the start tag mind you
			break;
		case "option": 
			return "<$tag$t value=".$quotedValue."[+$name:$val+]/>";
			break;
		case "input":
			switch($type){
				case 'radio':
				case 'checkbox':
					return "<input$t value=".$quotedValue."[+$name:$val+]/>";
        			break;
				case 'text':
				case 'password':
					return "<input$t value=\"[+$name+]\"/>";
        			break;
				default: //leave as is - no placeholder
					return "<input$t value=".$quotedValue."/>";
        			break;
			}
		case "textarea": //placeholder needs to be added in calling code
			return "<$tag$t>";
			break;
 		default:
			return "<input$t value=\"[+$name+]\"/>";
			break;
	} // switch
	return ""; //if we've arrived here we're in trouble
}

function attr2array($tag){
	$expr = "#([a-z0-9_]*?)=(([\"'])[^\\3]*?\\3)#si";
	preg_match_all($expr,$tag,$matches);
	foreach($matches[1] as $i => $key)
		$rt[$key]= $matches[2][$i];
	return $rt;
}
?>