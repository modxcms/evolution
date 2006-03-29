<?php
/**
 * Document Manager Module - lang.en.php
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: Garry Nutting
 * For: MODx CMS (www.modxcms.com)
 * Date:24/02/2006 Version: 1
 * 
 */
 
//-- ENGLISH LANGUAGE FILE
 
//-- titles
$_lang['module_title'] = 'Document Manager';
$_lang['action_title'] = 'Select an action';
$_lang['range_title'] = 'Specify a Range of Document IDs';
$_lang['tree_title'] = 'Select Documents from the tree';
$_lang['update_title'] = 'Update Completed';
$_lang['sort_title'] = 'Menu Index Editor';

//-- tabs
$_lang['doc_permissions'] = 'Document Permissions';
$_lang['sort_menu'] = 'Sort Menu Items';
$_lang['change_template'] = 'Change Template';
$_lang['publish'] = 'Publish/Unpublish';
$_lang['other'] = 'Other Properties';
 
//-- buttons
$_lang['cancel'] = 'Cancel';
$_lang['go'] = 'Go';
$_lang['save'] = 'Save';
$_lang['sort_another'] = 'Sort Another';

//-- templates tab
$_lang['tpl_desc'] = 'Choose the required template from the table below and then specify the Document IDs which need to be changed. Either by specifying a range of IDs or by using the Tree option below.';
$_lang['tpl_no_templates'] = 'No Templates Found';
$_lang['tpl_column_id'] = 'ID';
$_lang['tpl_column_name'] = 'Name';
$_lang['tpl_column_description'] ='Description';
$_lang['tpl_blank_template'] = 'Blank Template';

$_lang['tpl_results_message']= 'Use the Back button if you need make more changes. The Site Cache has automatically been cleared.';

//-- document permissions tab
$_lang['doc_desc'] = 'Choose the required document group from the table below and whether you wish to add or remove the group. Then specify the Document IDs which need to be changed. Either by specifying a range of IDs or by using the Tree option below.';
$_lang['doc_no_docs'] = 'No Document Groups Found';
$_lang['doc_column_id'] = 'ID';
$_lang['doc_column_name'] = 'Name';
$_lang['doc_radio_add'] = 'Add a Document Group';
$_lang['doc_radio_remove'] = 'Remove a Document Group';

$_lang['doc_skip_message1'] = 'Document with ID';
$_lang['doc_skip_message2'] = 'is already part of the selected document group (skipping)';

//-- sort menu tab
$_lang['sort_pick_item'] = 'Please click the site root or parent document from the MAIN DOCUMENT TREE that you\'d like to sort. Do not use the Document Selection options below - these will not work.'; 
$_lang['sort_updating'] = 'Updating ...';
$_lang['sort_updated'] = 'Updated';
$_lang['sort_nochildren'] = 'Parent does not have any children';
$_lang['sort_noid']='No Document has been selected. Please go back and select a document.';

//-- other tab
$_lang['other_header'] = 'Miscellaneous Document Settings';
$_lang['misc_label'] = 'Available Settings:';
$_lang['misc_desc'] = 'Please pick a setting from the dropdown menu and then the required option. Please note that only one setting can be changed at a time.';

$_lang['other_dropdown_publish'] = 'Publish/Unpublish';
$_lang['other_dropdown_show'] = 'Show/Hide in Menu';
$_lang['other_dropdown_search'] = 'Searchable/Unsearchable';
$_lang['other_dropdown_cache'] = 'Cacheable/Uncacheable';
$_lang['other_dropdown_richtext'] = 'Richtext/No Richtext Editor';

//-- radio button text
$_lang['other_publish_radio1'] = 'Publish'; 
$_lang['other_publish_radio2'] = 'Unpublish';
$_lang['other_show_radio1'] = 'Hide in Menu'; 
$_lang['other_show_radio2'] = 'Show in Menu';
$_lang['other_search_radio1'] = 'Searchable'; 
$_lang['other_search_radio2'] = 'Unsearchable';
$_lang['other_cache_radio1'] = 'Cacheable'; 
$_lang['other_cache_radio2'] = 'Uncacheable';
$_lang['other_richtext_radio1'] = 'Richtext'; 
$_lang['other_richtext_radio2'] = 'No Richtext';

//-- adjust dates 
$_lang['adjust_dates_header'] = 'Set Document Dates';
$_lang['adjust_dates_desc'] = 'Any of the following Document date settings can be changed. Use "View Calendar" option to set the dates.';
$_lang['view_calendar'] = 'View Calendar';
$_lang['clear_date'] = 'Clear Date';

//-- adjust authors
$_lang['adjust_authors_header'] = 'Set Authors';
$_lang['adjust_authors_desc'] = 'Use the dropdown lists to set new authors for the Document.';
$_lang['adjust_authors_createdby'] = 'Created By:';
$_lang['adjust_authors_editedby'] = 'Edited By:';
$_lang['adjust_authors_noselection'] = 'No change';

 //-- labels
$_lang['date_pubdate'] = 'Publish Date:';
$_lang['date_unpubdate'] = 'Unpublish Date:';
$_lang['date_createdon'] = 'Created On Date:';
$_lang['date_editedon'] = 'Edited On Date:';
//$_lang['date_deletedon'] = 'Deleted On Date';

$_lang['date_notset'] = ' (not set)';
//deprecated
$_lang['date_dateselect_label'] = 'Select a Date: ';

//-- document select section
$_lang['select_submit'] = 'Submit';
$_lang['select_range'] = 'Switch back to setting a Document ID Range';
$_lang['select_range_text'] = '<p><strong>Key (where n is a document ID	number):</strong><br /><br />
							  n* - Change setting for this document and immediate children<br /> 
							  n** - Change setting for this document and ALL children<br /> 
							  n-n2 - Change setting for this range of documents<br /> 
							  n - Change setting for a single document</p> 
							  <p>Example: 1*,4**,2-20,25 - This will change the selected setting
						      for documents 1 and its children, document 4 and all children, documents 2 
						      through 20 and document 25.</p>';
$_lang['select_tree'] ='View and select documents using the Document Tree';

//-- process tree/range messages
$_lang['process_noselection'] = 'No selection was made. ';
$_lang['process_novalues'] = 'No values have been specified.';
$_lang['process_limits_error'] = 'Upper limit less than lower limit:';
$_lang['process_invalid_error'] = 'Invalid Value:';
$_lang['process_update_success'] = 'Update was completed successfully, with no errors.';
$_lang['process_update_error'] = 'Update has completed but encountered errors:';
$_lang['process_back'] = 'Back';

//-- manager access logging
$_lang['log_template'] = 'Document Manager: Templates changed.';
$_lang['log_docpermissions'] ='Document Manager: Document Permissions changed.';
$_lang['log_sortmenu']='Document Manager: Menu Index operation completed.';
$_lang['log_publish']='Document Manager: Document Manager: Documents Published/Unpublished settings changed.';
$_lang['log_hidemenu']='Document Manager: Documents Hide/Show in Menu settings changed.';
$_lang['log_search']='Document Manager: Documents Searchable/Unsearchable settings changed.';
$_lang['log_cache']='Document Manager: Documents Cacheable/Uncacheable settings changed.';
$_lang['log_richtext']='Document Manager: Documents Use Richtext Editor settings changed.';
$_lang['log_dates']='Document Manager: Documents Date settings changed.';
$_lang['log_authors']='Document Manager: Documents Author settings changed.';

?>
