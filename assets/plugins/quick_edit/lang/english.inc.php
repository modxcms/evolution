<?php

/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 11/18/2005
 *  For: MODx cms (modxcms.com)
 *  Description: Class for the QuickEditor
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

$QE_lang['QE_lang'] = 'en';
$QE_lang['QE_xml_lang'] = 'en';
$QE_lang['QE_charset'] = 'iso-8859-1';
$QE_lang['QE_title'] = 'QuickEdit'; // please change only if it violates local trademarks
$QE_lang['QE_show_links'] = 'Show links';
$QE_lang['QE_hide_links'] = 'Hide links';
$QE_lang['QE_someone_editing'] = 'Someone else is editing this document';
$QE_lang['QE_cant_find_content'] = 'Could not find content to edit';
$QE_lang['QE_description'] = 'Edit pages from the frontend of the site';
$QE_lang['revert'] = 'Revert';
$QE_lang['apply'] = 'Apply';
$QE_lang['revert_prompt'] = 'Undo ALL of your edits?';
$QE_lang['QE_no_edit_rights'] = 'No edit rights';
$QE_lang['ok'] = 'OK';
$QE_lang['content'] = 'Content';
$QE_lang['setting'] = 'Settings';
$QE_lang['go'] = 'Go';
$QE_lang['manager'] = 'Manager';
$QE_lang['help'] = 'Help';
$QE_lang['edit'] = 'Edit';
$QE_lang['logout'] = 'Logout';
$QE_lang['close'] = 'Close';
$QE_lang['document_title'] = 'Title';
$QE_lang['document_title_help'] = 'Type the name/ title of the document here. Try to avoid using backslashes in the name!';
$QE_lang['long_title'] = 'Long title';
$QE_lang['document_long_title_help'] = 'Here you can enter a longer title for your document. This is handy for search engines, and might be more descriptive for your document.';
$QE_lang['document_description'] = 'Description';
$QE_lang['document_description_help'] = 'You can enter an optional description of the document here.';
$QE_lang['document_content'] = 'Document content';
$QE_lang['template'] = 'Template';
$QE_lang['page_data_template_help'] = 'Here you can select which template the document uses.';
$QE_lang['document_alias'] = 'Document\'s alias';
$QE_lang['document_alias_help'] = 'Here you can select an alias for this document. This will make the document accessible using:\n\nhttp://yourserver/alias\n\nThis only works if you\'re using friendly URLs.';
$QE_lang['document_opt_published'] = 'Published?';
$QE_lang['document_opt_published_help'] = 'Check this field to have the document published immediately after saving it.';
$QE_lang['document_summary'] = 'Summary (introtext)';
$QE_lang['document_summary_help'] = 'Type a brief summary of the document';
$QE_lang['document_opt_menu_index'] = 'Menu index';
$QE_lang['document_opt_menu_index_help'] = 'Menu Index is a field you can use for sorting document in your menu snippet(s). You can also use it for any other purpose in your snippets.';
$QE_lang['document_opt_menu_title'] = 'Menu title';
$QE_lang['document_opt_menu_title_help'] = 'Menu title is a field you can use to display a short title for the document inside your menu snippet(s) or modules.';
$QE_lang['document_opt_show_menu'] = 'Show in menu';
$QE_lang['document_opt_show_menu_help'] = 'Select this option to show document inside a web menu. Please note that some Menu Builders might choose to ignore this option.';
$QE_lang['page_data_searchable'] = 'Searchable';
$QE_lang['page_data_searchable_help'] = 'Checking this field will allow the document to be searched. You can also use this field for other purposes in your snippets.';
$QE_lang['page_data_cacheable'] = 'Cacheable';
$QE_lang['page_data_cacheable_help'] = 'Leaving this field checked will allow the document to be saved to cache. If your document contains snippets, make sure this field is unchecked.';
?>