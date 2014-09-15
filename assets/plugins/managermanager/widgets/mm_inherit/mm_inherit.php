<?php
/**
 * mm_inherit
 * @version 1.2 (2013-05-16)
 * 
 * Inherit values from a parent.
 * 
 * @uses ManagerManager plugin 0.4.
 * 
 * @link http://code.divandesign.biz/modx/mm_inherit/1.2
 * 
 * @copyright 2013
 */

function mm_inherit($fields, $roles = '', $templates = ''){
	global $mm_fields, $modx;
	$e = &$modx->Event;
	
	// if we've been supplied with a string, convert it into an array
	$fields = makeArray($fields);
	
	// if we aren't creating a new document or folder, we don't want to do this
	if (!($modx->manager->action == '85' || $modx->manager->action == '4')){
		return;
	}
	
	// Are we using this rule?
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		// Get the parent info
		if (isset($_REQUEST['pid'])){
			$parentID = $modx->getPageInfo($_REQUEST['pid'], 0, 'id');
			$parentID = $parentID['id'];
		}else{
			$parentID = 0;
		}
		
		$output = "//  -------------- mm_inherit (from page $parentID) :: Begin ------------- \n";
		
		foreach ($fields as $field){
			// get some info about the field we are being asked to use
			if (isset($mm_fields[$field]['dbname'])){
				$fieldtype = $mm_fields[$field]['fieldtype'];
				$fieldname = $mm_fields[$field]['fieldname'];
				
				if(!empty($mm_fields[$field]['tv'])){
					$dbname = $field;
				}else{
					$dbname = $mm_fields[$field]['dbname'];
				}
				
				// Get this field data from the parent
				if (!empty($parentID)){
					$newArray = $modx->getTemplateVarOutput($dbname, $parentID);
					$newvalue = $newArray[$dbname];
				}else{
					$newArray = false;
					$newvalue = '';
				}
			}else{
				break;	 // If it's not something stored in the database, don't get the value
			}
			
			$output .= "
			// fieldtype $fieldtype
			// fieldname $fieldname
			// dbname $dbname
			// newvalue $newvalue
			";
			
			switch ($field){
				case 'log':
				case 'hide_menu':
				case 'show_in_menu':
					$output .=  '$j("input[name='.$fieldname.']").attr("checked", "'.($newvalue?'':'checked').'"); ';
				break;
				
				case 'is_folder':
				case 'is_richtext':
				case 'searchable':
				case 'cacheable':
				case 'published':
					$output .=  '$j("input[name='.$fieldname.']").attr("checked", "'.($newvalue?'checked':'').'"); ';
				break;
				
				case 'pub_date':
				case 'unpub_date':
					$output .=  '$j("input[name='.$fieldname.']").val("'.date('d-m-Y H:i:s', $newvalue).'"); ';
				break;

				default:
					switch ($fieldtype){
						case 'textarea':
							$output .=  '$j("textarea[name='.$fieldname.']").html("'.jsSafe($newvalue).'"); ';
						break;
						
						default:
							if (!empty($newvalue)){
								$output .=  '$j("'.$fieldtype.'[name='.$fieldname.']").val("'.jsSafe($newvalue).'"); ';
							}
						break;
					}
				break;
			}
		}
		
		$output .= "//  -------------- mm_inherit (from page $parentID) :: End ------------- \n";
		
		$e->output($output . "\n");
	}
}
?>