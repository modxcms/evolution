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

$QE_lang['QE_lang'] = 'fi';
$QE_lang['QE_xml_lang'] = 'fi';
$QE_lang['QE_charset'] = 'iso-8859-1';
$QE_lang['QE_title'] = 'QuickEdit'; // please change only if it violates local trademarks
$QE_lang['QE_show_links'] = 'N&auml;yt&auml; linkit';
$QE_lang['QE_hide_links'] = 'Piilota linkit';
$QE_lang['QE_someone_editing'] = 'Joku muu muokkaa parhaillaan t&auml;t&auml; dokumenttia';
$QE_lang['QE_cant_find_content'] = 'Sis&auml;lt&ouml;&auml; ei l&ouml;ytynyt muokkausta varten';
$QE_lang['QE_description'] = 'Muokkaa sivuja sivuston normaalipuolelta';
$QE_lang['revert'] = 'Hylk&auml;&auml; muutokset';
$QE_lang['apply'] = 'K&auml;yt&auml;';
$QE_lang['revert_prompt'] = 'Peru KAIKKI tekem&auml;si muutokset?';
$QE_lang['QE_no_edit_rights'] = 'Ei muokkausoikeutta';
$QE_lang['ok'] = 'OK';
$QE_lang['content'] = 'Sis&auml;lt&ouml;';
$QE_lang['setting'] = 'Asetukset';
$QE_lang['go'] = 'Mene';
$QE_lang['manager'] = 'J&auml;rjestelm&auml;nhallinta';
$QE_lang['help'] = 'Apua';
$QE_lang['edit'] = 'Muokkaa';
$QE_lang['logout'] = 'Kirjaudu ulos';
$QE_lang['close'] = 'Sulje';
$QE_lang['document_title'] = 'Otsikko';
$QE_lang['document_title_help'] = 'Kirjoita dokumentin nimi/otsikko t&auml;h&auml;n. &auml;l&auml; k&auml;yt&auml; kautta-merkki&auml; nimess&auml;!';
$QE_lang['long_title'] = 'Pitk&auml; otsikko';
$QE_lang['document_long_title_help'] = 'Anna t&auml;h&auml;n dokumentin pitk&auml; otsikko. T&auml;st&auml; on hy&ouml;ty&auml; etenkin hauissa ja se voi kuvata dokumenttisi sis&auml;lt&ouml;&auml; paremmin.';
$QE_lang['document_description'] = 'Kuvaus';
$QE_lang['document_description_help'] = 'Anna t&auml;h&auml;n kuvaus dokumentista (valinnainen).';
$QE_lang['document_content'] = 'Dokumentin sis&auml;lt&ouml;';
$QE_lang['template'] = 'Sivustopohja';
$QE_lang['page_data_template_help'] = 'Valitse dokumentin k&auml;ytt&auml;m&auml; sivupohja.';
$QE_lang['document_alias'] = 'Dokumentin alias';
$QE_lang['document_alias_help'] = 'Anna t&auml;h&auml;n dokumentin alias. Mik&auml;li selke&auml;t URL-osoitteet ovat k&auml;yt&ouml;ss&auml;, p&auml;&auml;see t&auml;lle sivulle osoitteessa\n\nhttp://sunsaitti/alias\n\n';
$QE_lang['document_opt_published'] = 'Julkaistu?';
$QE_lang['document_opt_published_help'] = 'Laita t&auml;h&auml;n rasti, mik&auml;li haluat, ett&auml; dokumentti on julkaistu heti tallennuksen j&auml;lkeen.';
$QE_lang['document_summary'] = 'Yhteenveto (introtext)';
$QE_lang['document_summary_help'] = 'Anna t&auml;h&auml;n lyhyt yhteenveto dokumentista';
$QE_lang['document_opt_menu_index'] = 'Valikon j&auml;rjestysnumero (index)';
$QE_lang['document_opt_menu_index_help'] = 'Valikon j&auml;rjestysnumerolla m&auml;&auml;rit&auml;t, miss&auml; kohtaa sivu n&auml;kyy valikossasi. T&auml;t&auml; voi k&auml;ytt&auml;&auml; hyv&auml;ksi my&ouml;s muita koodinp&auml;tki&auml; k&auml;ytett&auml;ess&auml;.';
$QE_lang['document_opt_menu_title'] = 'Valikon otsikko';
$QE_lang['document_opt_menu_title_help'] = 'Anna t&auml;h&auml;n nimi, jolla haluat sivun n&auml;kyv&auml;n valikossa tai koodinp&auml;tki&auml; k&auml;ytett&auml;ess&auml;.';
$QE_lang['document_opt_show_menu'] = 'N&auml;yt&auml; valikossa';
$QE_lang['document_opt_show_menu_help'] = 'Valitse, n&auml;kyyk&ouml; t&auml;m&auml; sivu valikossa vai ei. Huomaa, ett&auml; jotkin valikkosovellukset eiv&auml;t huomioi t&auml;t&auml; asetusta.';
$QE_lang['page_data_searchable'] = 'Haettavissa';
$QE_lang['page_data_searchable_help'] = 'Valitse, otetaanko t&auml;m&auml;n sivun sis&auml;lt&ouml; mukaan sivustolla teht&auml;viin hakuihin. T&auml;t&auml; voi k&auml;ytt&auml;&auml; hyv&auml;ksi my&ouml;s muita koodinp&auml;tki&auml; k&auml;ytett&auml;ess&auml;.';
$QE_lang['page_data_cacheable'] = 'V&auml;limuistiin haettava';
$QE_lang['page_data_cacheable_help'] = 'Laita t&auml;h&auml;n rasti, mik&auml;li haluat, ett&auml; dokumentti voidaan ladata v&auml;limuistiin. Mik&auml;li sivulla on koodinp&auml;tki&auml; (snippets), j&auml;t&auml; t&auml;m&auml; kohta rastittamatta.';
?>