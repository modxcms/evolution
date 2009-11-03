<?php





//---------------------------------------------------------------------------------
// mm_renameField
// Change the label for an element
//---------------------------------------------------------------------------------
function mm_renameField($field, $newlabel, $roles='', $templates='', $newhelp='') {

	global $mm_fields, $modx;
	$e = &$modx->Event;
		
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if (useThisRule($roles, $templates)) {
	
	$output = " // ----------- Rename field -------------- \n";
		
			switch ($field) {
			
				// Exceptions
				case 'keywords':
					$output .= '$j("select[name*=keywords]").siblings("span.warning").empty().prepend("'.jsSafe($newlabel).'");';
				break;
				
				case 'metatags':
					$output .= '$j("select[name*=metatags]").siblings("span.warning").empty().prepend("'.jsSafe($newlabel).'");';
				break;
						
				case 'hidemenu':
				case 'show_in_menu':
					$output .= '$j("input[name=hidemenucheck]").siblings("span.warning").empty().prepend("'.jsSafe($newlabel).'");';
				break;
				
				case 'which_editor':
					$output .= '$j("#which_editor").prev("span.warning").empty().prepend("'.jsSafe($newlabel).'");';
				break;
							
				// Ones that follow the regular pattern
				default:

					if (isset($mm_fields[$field])) {
						$fieldtype = $mm_fields[$field]['fieldtype'];
						$fieldname = $mm_fields[$field]['fieldname'];                    
						$output .= '$j("'.$fieldtype.'[name='.$fieldname.']").parents("td").prev("td").children("span.warning").empty().prepend("'.jsSafe($newlabel).'");';
					} 
				
				break;
			}	// end switch
			
			$e->output($output . "\n");
			
			// If new help has been supplied, do that too
			if ($newhelp != '') {
				mm_changeFieldHelp($field, $newhelp, $roles, $templates);
			}
			
	} // end if
} // end function






//---------------------------------------------------------------------------------
// mm_hideFields
// Hide a field
//---------------------------------------------------------------------------------
function mm_hideFields($fields, $roles='', $templates='') {

	global $mm_fields, $modx;
	$e = &$modx->Event;	
		
	// if we've been supplied with a string, convert it into an array 
	$fields = makeArray($fields);
		
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if (useThisRule($roles, $templates)) {
	
	$e->output(" // ----------- Hide fields -------------- \n");
	
		foreach ($fields as $field) {
		
			$output = '';
			
			switch ($field) {	
			
				// Exceptions
				case 'keywords':
					$output .= '$j("select[name*=keywords]").parent("td").hide();';
				break;
				
				case 'metatags':
					$output .= '$j("select[name*=metatags]").parent("td").hide()';
				break;
						
				case 'hidemenu':
				case 'show_in_menu':
					$output .= '$j("input[name=hidemenucheck]").parent("td").hide();';
				break;
						
				case 'menuindex':
					$output .= '$j("input[name=menuindex]").parents("table").parent("td").prev("td").children("span.warning").hide();' ."\n";
					$output .= '$j("input[name=menuindex]").parent("td").hide();';
				break;
				
				case 'which_editor':
					$output .= '$j("select#which_editor").prev("span.warning").hide();' . "\n";
					$output .= '$j("select#which_editor").hide();';
				break;
				
				case 'content':
					$output .= '$j("#sectionContentHeader, #sectionContentBody").hide();'; // For 1.0.0
					$output .= '$j("#ta").parent("div").parent("div").hide().prev("div").hide();'."\n"; // For 1.0.1
				break;
				
				case 'pub_date':
					$output .= '$j("input[name=pub_date]").parents("tr").next("tr").hide(); '."\n";
					$output .= '$j("input[name=pub_date]").parents("tr").hide(); ';
				break;
				
				case 'unpub_date':
					$output .= '$j("input[name=unpub_date]").parents("tr").next("tr").hide(); '."\n";
					$output .= '$j("input[name=unpub_date]").parents("tr").hide(); ';
				break;			
			
				// Ones that follow the regular pattern
				default:				
					if (isset($mm_fields[$field]))  { // Check the fields exist,  so we're not writing JS for elements that don't exist
						$output .= '$j("'.$mm_fields[$field]['fieldtype'].'[name='.$mm_fields[$field]['fieldname'].']").parents("tr").hide().next("tr").find("td[colspan=2]").parent("tr").hide(); ';
					} 				
				break;
			} // end switch
			$e->output($output . "\n");
		} // end foreach	
	} // end if
} // end function






//---------------------------------------------------------------------------------
// mm_changeFieldHelp
// Change the help text of a field
//---------------------------------------------------------------------------------
function mm_changeFieldHelp($field, $helptext='', $roles='', $templates='') {

	global $mm_fields, $modx;
	$e = &$modx->Event;	
		
	if ($helptext=='') {
		return;
	}
	
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if (useThisRule($roles, $templates)) {
	
	$output = " // ----------- Change field help -------------- \n";
	
			switch ($field) {
			
	
				
				// Ones that follow the regular pattern
				default:
					// What type is this field?
					if (isset($mm_fields[$field])) {
						$fieldtype = $mm_fields[$field]['fieldtype'];
						$fieldname = $mm_fields[$field]['fieldname'];
						
						// Give the help button an ID, and modify the alt/title text
						$output .= '$j("'.$fieldtype.'[name='.$fieldname.']").siblings("img[style:contains(\'cursor:help\')]").attr("id", "'.$fieldname.'-help").attr("alt", "'.jsSafe($helptext).'").attr("title", "'.jsSafe($helptext).'"); ';									
					} else {
						break;
					}
				
				
				
				break;
			} // end switch
						
			$e->output($output . "\n");
	} // end if
} // end function










//---------------------------------------------------------------------------------
// mm_moveFieldsToTab
// Move a field to a different tab
//--------------------------------------------------------------------------------- 
function mm_moveFieldsToTab($fields, $newtab, $roles='', $templates='') {

	global $modx, $mm_fields;
	$e = &$modx->Event;
	
	// if we've been supplied with a string, convert it into an array 
	$fields = makeArray($fields);
			
	// if the current page is being edited by someone in the list of roles, and uses a template in the list of templates
	if (useThisRule($roles, $templates)) {
	
	$output = " // ----------- Move field to tab -------------- \n";
	
		// If it's one of the default tabs, we need to get the capitalisation right
		switch ($newtab) {
			case 'general':
			case 'settings':
			case 'access':
			case 'meta': // version 1.0.0 only, removed in 1.0.1
				$newtab = ucfirst($newtab);
			break;
		}
		
		// Make sure the new tab exists in the DOM
		$output .= "if ( \$j('#tab".$newtab."').length > 0) { \n";
		$output .= 'var ruleHtml = \'<tr style="height: 10px"><td colspan="2"><div class="split"></div></td></tr>\'; ';
			
		// Go through each field that has been supplied
		foreach ($fields as $field) {

			switch ($field) {
			
				// We can't move these fields because they belong in a particular place
				case 'keywords':
				case 'metatags':
				case 'which_editor':
				case 'content':
				case 'hidemenu':
				case 'show_in_menu':
				case 'menuindex':
					// Do nothing
				break;
				
				case 'pub_date':
					$output .= 'var helpline = $j("input[name=pub_date]").parents("tr").next("tr").appendTo("#tab'.$newtab.'>table:first"); ' . "\n";
					$output .= '$j(helpline).before($j("input[name=pub_date]").parents("tr")); ' . "\n";
					$output .= 'helpline.after(ruleHtml); '. "\n";
				break;

				case 'unpub_date':
					$output .= 'var helpline = $j("input[name=unpub_date]").parents("tr").next("tr").appendTo("#tab'.$newtab.'>table:first"); ' . "\n";
					$output .= '$j(helpline).before($j("input[name=unpub_date]").parents("tr")); ' . "\n";
					$output .= 'helpline.after(ruleHtml); '. "\n";
				break;
			
				
				default:
				
					// What type is this field?
					if (isset($mm_fields[$field])) {
						$fieldtype = $mm_fields[$field]['fieldtype'];
						$fieldname = $mm_fields[$field]['fieldname'];
						$output .= '
						var toMove = $j("'.$fieldtype.'[name='.$fieldname.']").parents("tr"); // Identify the table row to move
						toMove.next("tr").find("td[colspan=2]").parents("tr").remove(); // Get rid of line after, if there is one
						var movedTV = toMove.appendTo("#tab'.$newtab.'>table:first"); // Move the table row
						movedTV.after(ruleHtml); // Insert a rule after 
						movedTV.find("td[width]").attr("width","");  // Remove widths from label column
						$j("[name^='.$fieldname.']:first").parents("td").removeAttr( "style" );  // This prevents an IE6/7 bug where the moved field would not be visible until you switched tabs
						';
					}	
								
						
				break;
			
			} // end switch	
		} // end foreach
		
		$output .= "}";
		$e->output($output . "\n");
		
	}	// end if
} // end function









?>
