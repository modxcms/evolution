<?php
/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 8/14/2005
 *  Updated: 10/7/05 - Added editable description
 *  Updated: 11/17/05 - Fixed href="#" and uppercase </HEAD>
 *  Updated: 12/10/07 - changed order of TVs to be based on rank first, then by name
 *  For: MODx cms (modxcms.com)
 *  Description: Class for outputing QuickEdit links
 */

/*
                             License

QuickEdit - A MODx module which allows the editing of content via
            the frontent of the site
Copyright (C) 2005  Adam Crownoble

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

if (!isset ($QE_lang)) {
	$QE_lang = array ();
}

class Output {

	function Output() {

		global $modx;
		global $QE_lang;

		$lang = $modx->config['manager_language'];
		$qe_path = MODX_BASE_PATH . $GLOBALS['quick_edit_path'];

		$this->output = '';

		$qe_eng_path = $qe_path . '/lang/english.inc.php';
		$qe_lang_path = $qe_path . '/lang/' . $lang . '.inc.php';
		$lang_set = isset ($QE_lang);
		include_once ($qe_eng_path);
		if (file_exists($qe_lang_path)) {
			include_once ($qe_lang_path);
		}
	}

	function mergeTags() {

		/*
		 *  Replace content variables that start with a # with <quickedit> tag
		 *  We can't just replace them with the links because the links would get cached if
		 *  we did and that's not a good idea, trust me. This way only the custom tags get cached
		 *  which is harmless enough and the links get added just before the page is rendered.
		 */

		$output = & $this->output;
		$output = preg_replace('~\[\*#(.*?)\*\]~', '<quickedit:\\1 />[*\\1*]', $output);

	}

	function mergeLinks($module_id) {

		/*
		 *  If you are logged in this function will find the <quickedit:... />
		 *  tags and replace them with real edit links for frontend editing.
		 *  It also inserts the styles and javascript necessary to make the
		 *  parent hover highlighting and editor pop-up window opening possible.
		 */

		global $modx;
		global $QE_lang;
		global $show_manager_link;
		global $show_help_link;

		$show_manager_link = $GLOBALS['qe_show_manager_link'];
		$show_help_link = $GLOBALS['qe_show_help_link'];
		$editable = explode(',', $GLOBALS['qe_editable']);
		$manager_link = '';
		$help_link = '';
		$allowed = true;
		$buttons_html = '';
		$menus_html = '';
		$toolbar_cv_html = '';
		$base_path = $modx->config['base_path'];
		$qe_path = $GLOBALS['quick_edit_path']; // Path to the Quick Edit folder, set in the QuickEdit module preferences
		$output = $this->output;

		include_once ($base_path . $qe_path . '/module.class.inc.php');
		$module = new Module;
		$module->id = $module_id;

		// Set any built-in content variable to check document permissions
		include_once ($base_path . $qe_path . '/contentVariable.class.inc.php');
		$cv_obj = new ContentVariable;
		$cv_obj->set('pagetitle');

		// If we are logged in and have edit permissions...
		if (!isset ($_SESSION['mgrValidated'])) {
			$allowed = false;

		}
		elseif (!$modx->hasPermission('edit_document')) {
			$allowed = false;

		}
		elseif (!$modx->hasPermission('exec_module')) {
			$allowed = false;

		}
		elseif (!$module->checkPermissions()) {
			$allowed = false;

		} else
			if (!$cv_obj->checkPermissions()) {
				$allowed = false;
			}

		if ($allowed) {

			$cv = new ContentVariable;
			$manager_path = $modx->getManagerPath();
			$doc_id = $modx->documentIdentifier;
			$logout_url = "index.php?id={$doc_id}&amp;QuickEdit_logout=logout"; // $modx->makeURL($doc_id, '', 'QuickEdit_logout=logout'); // Would like to use makeURL but doesn't produce XHTML valid code
			$replacements = array ();
			$link = '';
			$on_click = '';
			$change_value = '';
			$menus = array (
				'content' => array (),
				'setting' => array (),
				'go' => array ()
			);

			$menus['setting'][] =<<<EOD
<a id="QE_ShowLinks" class="checkbox" href="javascript:qe.toggleLinks();">{$QE_lang['QE_show_links']}</a>
EOD;

			if ($show_manager_link) {
				$menus['go'][] =<<<EOD
<a id="QE_Manager" href="{$manager_path}">{$QE_lang['manager']}</a>
EOD;
			}

			if ($show_help_link) {
				$menus['go'][] =<<<EOD
<a id="QE_Help" href="http://www.modxcms.com/quickedit.html">{$QE_lang['help']}</a>
EOD;
			}

			$menus['go'][] =<<<EOD
<a id="QE_Logout" href="{$logout_url}">{$QE_lang['logout']}</a>
EOD;

			$cvs = $modx->getTemplateVars('*', 'id, name', '', 1, 'rank,name');

			foreach ($cvs as $content) {

				if (isset ($content['id']) || in_array($content['name'], $editable)) {

					if (isset ($content['id'])) {
						$cv_obj->set($content['id']);
					} else {
						$cv_obj->set($content['name']);
					}

					if ($cv_obj && $cv_obj->checkPermissions()) {

						// Get the menu
						$menu = $cv_obj->group;

						// Check for special CV types //

						// One checkbox and not a binding
						if ($cv_obj->type == 'checkbox' && !strpos($cv_obj->elements, '||') && substr($cv_obj->elements, 0, 1) != '@') {
							$class = 'checkbox'.($cv_obj->content ? ' checked' : '');
							$change_value = ($cv_obj->content ? '' : (strpos($cv_obj->elements, '==') ? substr(strstr($cv_obj->elements, '=='), 2) : $cv_obj->elements));
							$menus[$menu][] .=<<<EOD
<a class="{$class}" href="#" onclick="qe.ajaxSave('{$cv_obj->id}', '{$cv_obj->name}', '{$change_value}');return false;" title="{$QE_lang['edit']} {$cv_obj->description}">{$cv_obj->caption}</a>
EOD;

							// Everything else
						} else {

							$menus[$menu][] .=<<<EOD
<a href="#" onclick="qe.open('{$cv_obj->id}');return false;" title="{$QE_lang['edit']} {$cv_obj->description}">{$cv_obj->caption}</a>
EOD;

						}

					}

				}

			}
			foreach ($menus as $menu_name => $links) {

				$links_html = '';

				foreach ($links as $link) {
					$links_html .= "<li>{$link}</li>";
				}

				$menus_html .=<<<EOD
 <li>{$QE_lang[$menu_name]}
  <ul>
   {$links_html}
  </ul>
 </li>
EOD;

			}
			$site_url = MODX_BASE_URL;
			// Define the CSS and Javascript that we will add to the header of the page
			$head =<<<EOD

<!-- Start QuickEdit headers -->
<script type="text/javascript" src="{$site_url}manager/media/script/mootools/mootools.js"></script>
<script type="text/javascript" src="{$site_url}{$qe_path}/javascript/QuickEdit.js"></script>
<link type="text/css" rel="stylesheet" href="{$site_url}{$qe_path}/styles/toolbar.css" />
<script type="text/javascript">
 Window.addEvent('load',function() {
  qe = new QuickEdit({$module_id},{$doc_id},'{$manager_path}','{$qe_path}',$('QE_Toolbar'));
 });
</script>
<!-- End QuickEdit headers -->

EOD;

			$html_top =<<<EOD

<!-- Start QuickEdit toolbar -->
<div id="QE_Toolbar">
 <h1>{$QE_lang['QE_title']}</h1>
 <ul>
{$menus_html}
 </ul>
</div>
<!-- End QuickEdit toolbar -->

EOD;

			// Get an array of the content variable names
			$matches = array ();
			preg_match_all('~<quickedit:(.*?)\s*\/>~', $output, $matches);

			// Loop through every TV we found
			for ($i = 0; $i < count($matches[1]); $i++) {

				$contentVarName = $matches[1][$i];
				$cv->set($contentVarName, $doc_id);
				$link = '';

				// Check that we have permission to edit this content
				if ($cv->id && $cv->checkPermissions()) {

					// Set the HTML for the link
					$link =<<<EOD
<a href="#" onclick="javascript: qe.open('{$cv->id}'); return false;" title="Edit {$cv->description}" class="QE_Link">&laquo; {$QE_lang['edit']} {$cv->name}</a>
EOD;

				}

				$replacements[$i] = $link;

			}

			// Merge the links with the content
			$output = str_replace($matches[0], $replacements, $output);

			// If the javascript hasn't already been added to the page, do it now
			if (strpos($output, $head) === false) {
				$output = preg_replace('~(</head>)~i', $head . '\1', $output);
			}

			// If the top html hasn't already been added to the page, do it now
			if (strpos($output, $html_top) === false) {
				$output = preg_replace('~(<body[^>]*>)~i', '\1' . $html_top, $output);
			}

		}

		// Remove any leftover <quickedit> tags that we may not have had permission to
		$output = preg_replace('~<quickedit:.*?\s*\/>~', '', $output);

		$this->output = $output;

	}

}
?>
