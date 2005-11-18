// ###########################################
// DropMenu                                  #
// ###########################################
// Configurable menu / navigation builder using UL tags
// Offers optional DIV wrappers for top level and nested menus (useful for hover zones)
// as well as configurable classes for the DIV, UL, and LI elements.  It even
// marks ancestors of and the current element with a hereClass (indicating you are here 
// and in this area of the site).  Also applies .last CSS class to final LI in each UL.
//
// Developed by Vertexworks.com and Opengeek.com
// Feel free to use if you keep this header and credits in place
//
// Inspired by List Site Map by Jaredc, SimpleList by Bravado, 
// and ListMenuX by OpenGeek
//
// Configuration parameters:
// 
// &menuName        - name of a placeholder for placing the output in the layout
// &topnavClass     - CSS class for styling the class assigned to the outermost UL
// 
// TO DO: configuration parameters above, more usage examples, CSS examples, output indenting

// ###########################################
// Usage Examples                            #
// ###########################################
// Creates menu with wrapping DIV with id=myMenu, starting at the site root, two levels deep,
// with descriptions next to the links, and nested UL elements with class=nestedLinks; output
// of menu can be placed in layout using placeholder named myMenu ( e.g. [ +myMenu+ ] )
// [[DropMenu? &menuName=`myMenu` &startDoc=`0` &levelLimit=`2` &topdiv=`true` &showDescription=`true` &subnavClass=`nestedLinks`]]
//
// Creates topMenu from site root, including only 1 level, with class=topMenubar applied to the top level UL
// and class=activeLink applied to current page LI
// [[DropMenu? &menuName=`topMenu` &startDoc=`0` &levelLimit=`1` &topnavClass=`topMenubar` &here=`activeLink`]]
//
// Creates dropmenu 3 levels deep, with DIV wrappers around all nested lists styled with class=hoverZone
// and currentPage LI styled with class=currentPage
// [[DropMenu? &levelLimit=3 &subdiv=true &subdivClass=hoverZone &subnavClass=menuZone &here=currentPage]]
//
// Creates dropmenu of infinite levels, ordered by menutitle in descending order
// [[DropMenu?orderBy=menutitle&orderDesc=true]]

// ###########################################
// Configuration parameters                  #
// ###########################################

// $phMode [ true | false ]
// Whether you want it to output a [+placeholder+] or simply return the output.
// Defaults to false.
$phMode = false;

// $menuName [ string ]
// Sets the name of the menu, placeholder, and top level DIV id (if topdiv 
// option is true). Set to "dropmenu" by default.
$phName = (!isset($phName)) ? 'dropmenu' : "$phName";

// $siteMapRoot [int]
// The parent ID of your root. Default 0. Can be set in 
// snippet call with startDoc (to doc id 10 for example):
// [[DropMenu?startDoc=10]]
$siteMapRoot = 0;

// $removeNewLines [ true | false ]
// If you want new lines removed from code, set to true. This is generally
// better for IE when lists are styled vertically. 
$removeNewLines = (!isset($removeNewLines)) ? false : ($removeNewLines==true);

// $maxLevels [ int ]
// Maximum number of levels to include. The default 0 will allow all
// levels. Also settable with snippet variable levelLimit:
// [[DropMenu?levelLimit=2]]
$maxLevels = 0;


// $textOfLinks [ string ]
// What database field do you want the actual link text to be?
// The default is pagetitle because it is always a valid (not empty)
// value, but if you prefer it can be any of the following:
// menutitle, id, pagetitle, description, parent, alias, longtitle, introtext
// TO DO: set text to be first non-empty of an array of options
$textOfLinks = (!isset($textOfLinks)) ? 'menutitle' : "$textOfLinks";

// $titleOfLinks [ string ]
// What database field do you want the title of your links to be?
// The default is pagetitle because it is always a valid (not empty)
// value, but if you prefer it can be any of the following:
// menutitle, id, pagetitle, description, parent, alias, longtitle, introtext
$titleOfLinks = (!isset($titleOfLinks)) ? 'description' : "$titleOfLinks";

// $pre [ string ]
// Text to append before links inside of LIs
$pre = (!isset($pre)) ? '' : "$pre";

// $post [ string ]
// Text to append before links inside of LIs
$post = (!isset($post)) ? '' : "$post";

// $selfAsLink [ true | false ]
// Define if the current page should be a link (true) or not (false)
$selfAsLink = (!isset($selfAsLink)) ? false : ($selfAsLink==true);

// $hereClass [ string ]
// CSS Class for LI and A when they are the currently selected page, as well
// as any ancestors of the current page (YOU ARE HERE)
$hereClass = (!isset($hereClass)) ? 'here' : $hereClass;



// $showDescription [true | false]
// Specify if you would like to include the description
// with the page title link.
$showDescription = (!isset($showDescription)) ? false : ($showDescription==true);

// $descriptionField [ string ]
// What database field do you want the description to be?
// The default is description. If you specify a field, it will attempt to use it
// first then fall back until it finds a non-empty field in description, introtext,
// then longtitle so it really tries not be empty. It can be any of the following:
// menutitle, id, pagetitle, description, parent, alias, longtitle, introtext
// TO DO: set description to the first non-empty of an array of options
$descriptionField = (!isset($descriptionField)) ? 'description' : "$descriptionField";


// $topdiv [ true | false ]
// Indicates if the top level UL is wrapped by a containing DIV block
$topdiv = (!isset($topdiv)) ? false : ($topdiv==true);

// $topdivClass [ string ]
// CSS Class for DIV wrapping top level UL
$topdivClass = (!isset($topdivClass)) ? 'topdiv' : "$topdivClass";

// $topnavClass [ string ]
// CSS Class for the top-level (root) UL
$topnavClass = (!isset($topnavClass)) ? 'topnav' : "$topnavClass";



// $useCategoryFolders [ true | false ]
// If you want folders without any content to render without a link to be used
// as "category" pages (defaults to true). In order to use Category Folders, 
// the template must be set to (blank) or it won't work properly.
$useCategoryFolders = (!isset($useCategoryFolders)) ? true : "$useCategoryFolders";

// $categoryClass [ string ]
// CSS Class for folders with no content (e.g., category folders)
$categoryClass = (!isset($categoryClass)) ? 'category' : "$categoryClass";



// $subdiv [ true | false ]
// Indicates if nested UL's should be wrapped by containing DIV blocks
// This is useful for creating "hover zones" 
// (see http://positioniseverything.net/css-dropdowns.html for a demo)
// TO CONSIDER: Setting a subdiv class at all turns on hover DIVs?
$subdiv = (!isset($subdiv)) ? false : ($subdiv==true);

// $subdivClass [ string ]
// CSS Class for DIV blocks wrapping nested UL elements
$subdivClass = (!isset($subdivClass)) ? 'subdiv' : "$subdivClass";



// $orderBy [ string ]
// Document field to sort menu by
$orderBy = (!isset($orderBy)) ? 'menuindex' : "$orderBy";

// $orderDesc [true | false]
// Order results in descending order?  default is false
$orderDesc = (!isset($orderDesc)) ? false : ($orderDesc==true);

// ###########################################
// End config, the rest takes care of itself #
// ###########################################

$debugMode = false;

// Initialize
$MakeMap = "";
$siteMapRoot = (isset($startDoc)) ? $startDoc : $siteMapRoot;
$maxLevels = (isset($levelLimit)) ? $levelLimit : $maxLevels;
$ie = ($removeNewLines) ? '' : "\n";
//Added by Remon: (undefined variables php notice)
$activeLinkIDs = array();
$subnavClass = '';

// Overcome single use limitation on functions
global $MakeMap_Defined;

if (!isset ($MakeMap_Defined)) {
	function filterHidden($var) {
		return (!$var['hidemenu']==1);
	}
	function filterEmpty($var) {
	    return (!empty($var));
	}
	function MakeMap($modx, $listParent, $listLevel, $description, $titleOfLinks, $maxLevels, $inside, $pre, $post, $selfAsLink, $ie, $activeLinkIDs, $topdiv, $topdivClass, $topnavClass, $subdiv, $subdivClass, $subnavClass, $hereClass, $useCategoryFolders, $categoryClass, $showDescription, $descriptionField, $textOfLinks, $orderBy, $orderDesc, $debugMode) {
		// Added by Remon. Define this variable _here_ ;-)
		$output = '';

		$children = $modx->getActiveChildren($listParent, $orderBy, (!$orderDesc) ? 'ASC' : 'DESC', 'id, pagetitle, description, isfolder, parent, alias, longtitle, menutitle, hidemenu, introtext, content_dispo, contentType, type, template');
		// filter out the content that is set to be hidden from menu snippets
		$children = array_filter($children, "filterHidden");
		$numChildren = count($children);

		if (is_array($children) && !empty($children)) {

			// determine if it's a top category or not
			$toplevel = !$inside;

			// build the output
			$topdivcls = (!empty($topdivClass)) ? ' class="'.$topdivClass.'"' : '';
			$topdivblk = ($topdiv) ? "<div$topdivcls>" : '';
			$topnavcls = (!empty($topnavClass)) ? ' class="'.$topnavClass.'"' : '';
			$subdivcls = (!empty($subdivClass)) ? ' class="'.$subdivClass.'"' : '';
			$subdivblk = ($subdiv) ? "<div$subdivcls>$ie" : '';
			$subnavcls = (!empty($subnavClass)) ? ' class="'.$subnavClass.'"' : '';
			$output = ($toplevel) ? "$topdivblk<ul$topnavcls>$ie" : "$ie$subdivblk<ul$subnavcls>$ie";

			//loop through and process subchildren
			foreach ($children as $child) {
				// figure out if it's a containing category folder or not 
				$numChildren --;
				$isFolder = $child['isfolder'];
			    $itsEmpty = ($isFolder && ($child['template'] == '0'));
				$itm = "";

                // if menutitle is blank fall back to pagetitle for menu link
                $textOfLinks = (empty($child['menutitle'])) ? 'pagetitle' : "$textOfLinks"; 

			    // If at the top level
				if (!$inside) 
				{
					$itm .= ((!$selfAsLink && ($child['id'] == $modx->documentIdentifier)) || ($itsEmpty && $useCategoryFolders)) ? 
					        $pre.$child[$textOfLinks].$post . (($debugMode) ? ' self|cat' : '') : 
					        '<a href="[~'.$child['id'].'~]" title="'.$child[$titleOfLinks].'">'.$pre.$child[$textOfLinks].$post.'</a>';
					$itm .= ($debugMode) ? ' top' : '';
				}
				
				// it's a folder and it's below the top level
				elseif ($isFolder && $inside) 
				{
				    
					$itm .= ($itsEmpty && $useCategoryFolders) ?
					        $pre.$child[$textOfLinks].$post . (($debugMode) ? 'subfolder T': '') :
					        '<a href="[~'.$child['id'].'~]" title="'.$child[$titleOfLinks].'">'.$pre.$child[$textOfLinks].$post.'</a>'. (($debugMode) ? ' subfolder F' :'');        					
				}
				
				// it's a document inside a folder
				else 
				{
					$itm .= ($child['alias'] > '0' && !$selfAsLink && ($child['id'] == $modx->documentIdentifier)) ? $child[$textOfLinks] : '<a href="[~'.$child['id'].'~]" title="'.$child[$titleOfLinks].'">'.$child[$textOfLinks].'</a>';
					$itm .= ($debugMode) ? ' doc' : '';
				}
				$itm .= ($debugMode)? "$useCategoryFolders $isFolder $itsEmpty" : '';
   					
				// loop back through if the doc is a folder and has not reached the max levels
				if ($isFolder && (($maxLevels == 0) || ($maxLevels > $listLevel +1))) {
					$itm .= MakeMap($modx, $child['id'], $listLevel +1, $description, $titleOfLinks, $maxLevels, true, $pre, $post, $selfAsLink, $ie, $activeLinkIDs, $topdiv, $topdivClass, $topnavClass, $subdiv, $subdivClass, $subnavClass, $hereClass, $useCategoryFolders, $categoryClass, $showDescription, $descriptionField, $textOfLinks, $orderBy, $orderDesc, $debugMode);
				}

				if ($itm && !$selfAsLink && ($child['id'] == $modx->documentIdentifier)) {
					$output .= "    <li class=\"$hereClass". ($numChildren == 0 ? ' last' : '')."\">$itm</li>$ie";
				}
				elseif ($itm) {
					// Added by Remon
					// define it here:
					$class = '';
					if ($numChildren == 0) {
						$class = 'last';
					}
					if (is_array($activeLinkIDs)) {
						if (in_array($child['id'], $activeLinkIDs)) {
							$class .= ($class ? ' ' : '').$hereClass;
						}
					}
					// it's an empty folder and using Category Folders
					if ($useCategoryFolders && $itsEmpty) {
						$class .= ($class ? ' ' : '').$categoryClass;
					}
					if ($class) {
						$class = ' class="'.$class.'"';
					}
					
					// TO DO: set description to the first non-empty of an array of options
					if ($showDescription && (!empty($child['$descriptionField']))) {
					    $desc = " &ndash; ".$child['$descriptionField'];
					} elseif ($showDescription && (!empty($child['description']))) {
					    $desc = ' &ndash; ' . $child['description'];
					} elseif ($showDescription && (!empty($child['introtext']))) {
					    $desc = ' &ndash; ' . $child['introtext'];
					} elseif ($showDescription && (!empty($child['longtitle']))) {
					    $desc = ' &ndash; ' . $child['longtitle'];
					} else {
					    $desc = '';
					}
					
					$output .= "<li$class>$itm$desc</li>$ie";
					$class = '';
				}
			}
			$output .= "</ul>$ie";
			$output .= ($toplevel) ? (($topdiv) ? "</div>$ie" : "") : (($subdiv) ? "</div>$ie" : "");
		}
		return $output;
	}
	$MakeMap_Defined = true;
}

$currentID = $modx->documentIdentifier;
$parentID = $currentID;

// find the parent docs of the current "you-are-here" doc
// used in the logic to mark parents as such also
while ($parentID != $siteMapRoot && $parentID != 0) {
	$parent = $modx->getParent($parentID, 0);
	if ($parent) {
		$parentID = $parent['id'];
		$activeLinkIDs[] = $parentID;
	} else {
		$parentID = 0;
	}
}

if ($phMode) {
    // output to a [+placeholder+]
    $modx->setPlaceholder($phName, MakeMap($modx, $siteMapRoot, 0, $showDescription, $titleOfLinks, $maxLevels, false, $pre, $post, $selfAsLink, $ie, $activeLinkIDs, $topdiv, $topdivClass, $topnavClass, $subdiv, $subdivClass, $subnavClass, $hereClass, $useCategoryFolders, $categoryClass, $showDescription, $descriptionField, $textOfLinks, $orderBy, $orderDesc, $debugMode));

} else {
    // return the output a "usual"
    return MakeMap($modx, $siteMapRoot, 0, $showDescription, $titleOfLinks, $maxLevels, false, $pre, $post, $selfAsLink, $ie, $activeLinkIDs, $topdiv, $topdivClass, $topnavClass, $subdiv, $subdivClass, $subnavClass, $hereClass, $useCategoryFolders, $categoryClass, $showDescription, $descriptionField, $textOfLinks, $orderBy, $orderDesc, $debugMode);

}
