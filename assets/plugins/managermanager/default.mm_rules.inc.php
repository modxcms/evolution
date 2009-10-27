<?php

// MM rules can go in here, instead of a chunk
// If you copy them to a chunk, don't include the opening and closing PHP tags

// ------------------------ INSERT YOUR RULES HERE --------------------
// These are example rules -- replace them with your own
// PHP *is* allowed

// News editors role -- creating a variable makes it easier to manage if this changes in the future
$news_role = '3';

mm_hideFields('pagetitle,menutitle,link_attributes,template,menuindex,description,show_in_menu,which_editor,is_folder,is_richtext,log,searchable,cacheable,clear_cache', $news_role);
mm_renameTab('settings', 'Publication settings', $news_role);	
mm_synch_fields('pagetitle,menutitle,longtitle', $news_role);
mm_renameField('longtitle','Headline', $news_role, '', 'This will be displayed at the top of each page');

// News story template
$news_tpl = '10';
mm_createTab('Categories','HrCats', '', $news_tpl, '', '600');
mm_moveFieldsToTab('tvnewsbigimage', 'general', '', $news_tpl);
mm_changeFieldHelp('longtitle', 'The story\'s headline', '', $news_tpl);
mm_changeFieldHelp('introtext', 'A short summary of the story', '', $news_tpl);
mm_hideFields('menuindex,show_in_menu', '', $news_tpl);
mm_changeFieldHelp('parent', 'To move this story to a different folder: Click this icon to activate, then choose a new folder in the tree on the left.', '', $news_tpl);


// For everyone
mm_default('pub_date');
mm_renameField('introtext','Summary');
mm_changeFieldHelp('alias', 'The URL that will be used to reach this story. Only numbers, letters and hyphens can be used');
mm_widget_tags('blogTags'); // Give blog tag editing capabilities to the 'blogTags' TV
mm_widget_colors('colour', '#666666'); // make a color selector widget for the 'colour' TV
mm_widget_showimagetvs(); // Always give a preview of Image TVs

// For everyone except administrators
mm_hideFields('link_attributes', '!1');
//mm_moveFieldsToTab('tvcolour', 'general');


// ------------------------ END OF RULES --------------------


?>