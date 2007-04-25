<?php

/*
 *  Written by: Adam Crownoble
 *  Contact: adam@obledesign.com
 *  Created: 11/18/2005
 *  For: MODx cms (modxcms.com)
 *  Description: Class for the QuickEditor
 *
 *  Modified: 11/30/2006
 *  For: MODx cms (modxcms.com) 0.9.5
 *  Encoding: Japanese UTF-8
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

$QE_lang['QE_lang'] = 'ja';
$QE_lang['QE_xml_lang'] = 'ja';
$QE_lang['QE_charset'] = 'UTF-8';
$QE_lang['QE_title'] = 'QuickEdit'; // please change only if it violates local trademarks
$QE_lang['QE_show_links'] = 'リンクを表示';
$QE_lang['QE_hide_links'] = 'リンクを隠す';
$QE_lang['QE_someone_editing'] = '他の人が編集中です';
$QE_lang['QE_cant_find_content'] = '編集する内容が見つかりません';
$QE_lang['QE_description'] = 'サイト表示中にページの編集を行えます';
$QE_lang['revert'] = '元に戻す';
$QE_lang['apply'] = '適用';
$QE_lang['revert_prompt'] = '変更を保存せずに元に戻しますがよろしいですか？';
$QE_lang['QE_no_edit_rights'] = '権限なし';
$QE_lang['ok'] = '保存終了';
$QE_lang['content'] = 'Content';
$QE_lang['setting'] = '設定';
$QE_lang['go'] = 'Go';
$QE_lang['manager'] = 'マネージャ';
$QE_lang['help'] = 'ヘルプ';
$QE_lang['edit'] = '編集';
$QE_lang['logout'] = 'ログアウト';
$QE_lang['close'] = '閉じる';
$QE_lang['document_title'] = 'タイトル';
$QE_lang['document_title_help'] = 'ドキュメントの名称/タイトルを入力してください。バックスラッシュは使用しないでください!';
$QE_lang['long_title'] = '長いタイトル';
$QE_lang['document_long_title_help'] = 'ドキュメントの長いタイトルを入力してください。これはサーチエンジンに対して効果があります。ドキュメントの詳細な情報を記述することができます。';
$QE_lang['document_description'] = '説明';
$QE_lang['document_description_help'] = 'ドキュメントに関する任意の説明をここに入力することができます。';
$QE_lang['document_content'] = '内容';
$QE_lang['template'] = 'テンプレート';
$QE_lang['page_data_template_help'] = 'ドキュメントが使用するテンプレートを選択してください。';
$QE_lang['document_alias'] = 'ドキュメントエイリアス';
$QE_lang['document_alias_help'] = 'このドキュメントのエイリアスを指定することができます。次のようにドキュメントにアクセスすることができます:\n\nhttp://yourserver/エイリアス\n\nエイリアスはフレンドリーURLを使用する場合のみ動作します。';
$QE_lang['document_opt_published'] = '公開?';
$QE_lang['document_opt_published_help'] = '保存後、すぐにドキュメントを公開する場合はチェックしてください。';
$QE_lang['document_summary'] = '要約（序説）';
$QE_lang['document_summary_help'] = 'ドキュメントの要約を簡潔に入力してください。';
$QE_lang['document_opt_menu_index'] = 'メニューインデックス';
$QE_lang['document_opt_menu_index_help'] = 'メニューインデックスは、メニュースニペット内でドキュメントを並び替えるために使用できます。また、スニペット内で他の目的に使用することもできます。';
$QE_lang['document_opt_menu_title'] = 'メニュータイトル';
$QE_lang['document_opt_menu_title_help'] = 'メニュータイトルは、メニュースニペットやモジュールで使用できるドキュメントの短いタイトルです。';
$QE_lang['document_opt_show_menu'] = 'メニューに表示';
$QE_lang['document_opt_show_menu_help'] = 'メニューにこのドキュメントを表示するにはこのオプションをチェックしてください。メニュー作成スニペットにはこのオプションを無視するものもある事に注意してください。';
$QE_lang['page_data_searchable'] = '検索対象';
$QE_lang['page_data_searchable_help'] = 'このフィールドをチェックすることにより、ドキュメントを検索対象にします。このフィールドをスニペット内で別の目的に使用することもできます。';
$QE_lang['page_data_cacheable'] = 'キャッシュ可';
$QE_lang['page_data_cacheable_help'] = 'このフィールドをチェックすることにより、ドキュメントがキャッシュに保存されることを許可します。ドキュメントにスニペットが含まれている場合は、必ずチェックを外してください。';
?>
