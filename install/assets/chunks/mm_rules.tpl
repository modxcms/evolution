/**
 * mm_demo_rules
 * 
 * ManagerManager rules for the demo content. Should be modified for your own sites.
 * 
 * @category	chunk
 * @version 	1.0.1
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal 	@modx_category Demo Content
 */

// PHP *is* allowed
// $news_role and $news_tpl will not apply to demo content but are left as a demonstration of what can be done

// For everyone
mm_default('pub_date');
mm_renameField('introtext','Summary');
mm_changeFieldHelp('alias', 'The URL that will be used to reach this resource. Only numbers, letters and hyphens can be used');
mm_widget_tags('documentTags',' '); // Give blog tag editing capabilities to the 'documentTags (3)' TV
mm_widget_showimagetvs(); // Always give a preview of Image TVs
// mm_widget_colors('color', '#666666'); // make a color selector widget for the 'colour' TV

// For everyone except administrators
mm_hideFields('link_attributes', '!1');
mm_hideFields('loginName ', '!1');
// mm_renameField('alias','URL alias','!1');

// News editors role -- creating a variable makes it easier to manage if this changes in the future
$news_role = '3';
mm_hideFields('pagetitle,menutitle,link_attributes,template,menuindex,description,show_in_menu,which_editor,is_folder,is_richtext,log,searchable,cacheable,clear_cache', $news_role);
mm_renameTab('settings', 'Publication settings', $news_role);	
mm_synch_fields('pagetitle,menutitle,longtitle', $news_role);
mm_renameField('longtitle','Headline', $news_role, '', 'This will be displayed at the top of each page');

// News story template
$news_tpl = '8';
// mm_createTab('Categories','HrCats', '', $news_tpl, '', '600');
// mm_moveFieldsToTab('updateImage1', 'general', '', $news_tpl);
// mm_hideFields('menuindex,show_in_menu', '', $news_tpl);
mm_changeFieldHelp('longtitle', 'The story\'s headline', '', $news_tpl);
mm_changeFieldHelp('introtext', 'A short summary of the story', '', $news_tpl);
mm_changeFieldHelp('parent', 'To move this story to a different folder: Click this icon to activate, then choose a new folder in the tree on the left.', '', $news_tpl);

