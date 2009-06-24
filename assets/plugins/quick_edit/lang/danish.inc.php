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

$QE_lang['QE_lang'] = 'da';
$QE_lang['QE_xml_lang'] = 'da';
$QE_lang['QE_charset'] = 'iso-8859-1';
$QE_lang['QE_title'] = 'QuickEdit'; // please change only if it violates local trademarks
$QE_lang['QE_show_links'] = 'Vis links';
$QE_lang['QE_hide_links'] = 'Skjul links';
$QE_lang['QE_someone_editing'] = 'En anden redigerer dette dokument';
$QE_lang['QE_cant_find_content'] = 'Kunne ikke finde noget indhold at redigere';
$QE_lang['QE_description'] = 'Redigerer siderne fra frontend af dette website';
$QE_lang['revert'] = 'Gendan';
$QE_lang['apply'] = 'Tilf&oslash;j';
$QE_lang['revert_prompt'] = 'Fortryd alle dine &aelig;ndringer?';
$QE_lang['QE_no_edit_rights'] = 'Ingen redigeringsrettigheder';
$QE_lang['ok'] = 'OK';
$QE_lang['content'] = 'Indhold';
$QE_lang['setting'] = 'Indstillinger';
$QE_lang['go'] = 'Forts&aelig;t';
$QE_lang['manager'] = 'Administration';
$QE_lang['help'] = 'Hj&aelig;lp';
$QE_lang['edit'] = 'Redig&eacute;r';
$QE_lang['logout'] = 'Log ud';
$QE_lang['close'] = 'Luk';
$QE_lang['document_title'] = 'Titel';
$QE_lang['document_title_help'] = 'Indtast navnet/ titlen p&aring; dette dokument her. Fors&oslash;g at undg&aring; backslashes i navnet!';
$QE_lang['long_title'] = 'Lang titel';
$QE_lang['document_long_title_help'] = 'Her kan du indtaste en l&aelig;ngere titel for dit dokument. Dette er smart for s&oslash;ge maskiner, og vil m&aring;ske v&aelig;re mere beskrivende for dine dokumenter.';
$QE_lang['document_description'] = 'Beskrivelse';
$QE_lang['document_description_help'] = 'Du kan indtaste en ekstra beskrivelse af dokument her.';
$QE_lang['document_content'] = 'Dokument indhold';
$QE_lang['template'] = 'Skabelon';
$QE_lang['page_data_template_help'] = 'Her kan du v&aelig;lge hvilken skabelon dokumentet skal bruge.';
$QE_lang['document_alias'] = 'Documentet\'s alias';
$QE_lang['document_alias_help'] = 'Her kan du v&aelig;lge et alias for dette dokument. Dette vil vise dokumentet p&aring; f&oslash;lgende m&aring;de i din browser:\n\nhttp://yourserver/alias\n\nDette virker kun hvis du har aktiveret s&oslash;gevenlige urler.';
$QE_lang['document_opt_published'] = 'Publiseret?';
$QE_lang['document_opt_published_help'] = 'Afkryds dette felt for at f&aring; dokumenterne til at blive publiseret med det samme efter du har gemt dem.';
$QE_lang['document_summary'] = 'Resum&eacute; (introtekst)';
$QE_lang['document_summary_help'] = 'Skriv et kort resum&eacute; for dokumentet';
$QE_lang['document_opt_menu_index'] = 'Menu indeks';
$QE_lang['document_opt_menu_index_help'] = 'Menu Indeks er et felt du kan bruge til at sortere dokumenter i dine menu snippet(s). Du kan ogs&aring; bruge det til andre form&aring;l i dine snippets.';
$QE_lang['document_opt_menu_title'] = 'Menu titel';
$QE_lang['document_opt_menu_title_help'] = 'Menu titel er et felt som du kan bruge til at vise en kort titel for dette dokument inde i dine menu snippet(s) eller moduler.';
$QE_lang['document_opt_show_menu'] = 'Vis i menu';
$QE_lang['document_opt_show_menu_help'] = 'V&aelig;lg denne mulighed for at vise dokumentet inde i en web menu. V&aelig;r opm&aelig;rksom p&aring; at nogle Menu programmer m&aring;ske vil ignorerer den valgmulighed.';
$QE_lang['page_data_searchable'] = 'S&oslash;gbar';
$QE_lang['page_data_searchable_help'] = 'Afkrydsningen af dette felt vil tillade at dokumentet kan blive indekseret af s&oslash;gninger. Du kan ogs&aring; bruge dette felt til andre form&aring;l i dine snippets.';
$QE_lang['page_data_cacheable'] = 'Cacherbar';
$QE_lang['page_data_cacheable_help'] = 'Afkrydsningen af dette felt vil tillade at dokumentet kan blive gemt i cachen. Hvis dit dokument indeholder snippets, v&aelig;r sikker p&aring; at feltet ikke er afkrydset.';
?>