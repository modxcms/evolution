<?php
/**
 * MODx language File
 *
 * @author MEGU, yamamoto
 * @package MODx
 * @version 1.0
 * 
 * Filename:       /install/lang/japanese/japanese-utf8.inc.php
 * Language:       Japanese
 * Encoding:       utf-8
 */



$_lang['license'] = '<p class="title">MODxの著作権と使用許諾条件について</p>
	    <hr style="text-align:left;height:1px;width:90%" />
		<h4>You must agree to the License before continuing installation.</h4>
		<p>Usage of this software is subject to the GPL license. To help you understand
		what the GPL licence is and how it affects your ability to use the software, we
		have provided the following summary:</p>
		<h4>The GNU General Public License is a Free Software license.</h4>
		<p>Like any Free Software license, it grants to you the four following freedoms:</p>
		<ul>
            <li>The freedom to run the program for any purpose. </li>
            <li>The freedom to study how the program works and adapt it to your needs. </li>
            <li>The freedom to redistribute copies so you can help your neighbor. </li>
            <li>The freedom to improve the program and release your improvements to the
            public, so that the whole community benefits. </li>
		</ul>
		<p>You may exercise the freedoms specified here provided that you comply with
		the express conditions of this license. The principal conditions are:</p>
		<ul>
            <li>You must conspicuously and appropriately publish on each copy distributed an
            appropriate copyright notice and disclaimer of warranty and keep intact all the
            notices that refer to this License and to the absence of any warranty; and give
            any other recipients of the Program a copy of the GNU General Public License
            along with the Program. Any translation of the GNU General Public License must
            be accompanied by the GNU General Public License.</li>

            <li>If you modify your copy or copies of the program or any portion of it, or
            develop a program based upon it, you may distribute the resulting work provided
            you do so under the GNU General Public License. Any translation of the GNU
            General Public License must be accompanied by the GNU General Public License. </li>

            <li>If you copy or distribute the program, you must accompany it with the
            complete corresponding machine-readable source code or with a written offer,
            valid for at least three years, to furnish the complete corresponding
            machine-readable source code.</li>

            <li>Any of these conditions can be waived if you get permission from the
            copyright holder.</li>

            <li>Your fair use and other rights are in no way affected by the above.
            </li>
        </ul>
		<p>The above is a summary of the GNU General Public License. By proceeding, you
		are agreeing to the GNU General Public Licence, not the above. The above is
		simply a summary of the GNU General Public Licence, and its accuracy is not
		guaranteed. It is strongly recommended you read the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU General Public
		License</a> in full before proceeding, which can also be found in the license
		file distributed with this package.</p>';
$_lang["encoding"] = 'utf-8';	//charset encoding for html header
$_lang["modx_install"] = 'MODx &raquo; インストール';
$_lang["loading"] = '処理中...';
$_lang["Begin"] = '開始';
$_lang["status_connecting"] = ' DBホストとの接続テストの結果: ';
$_lang["status_failed"] = '接続できません';
$_lang["status_passed"] = '接続できます';
$_lang["status_checking_database"] = '...    データベースとの接続テストの結果: ';
$_lang["status_failed_could_not_select_database"] = '接続できません - DB名を確認 - ';
$_lang["status_failed_table_prefix_already_in_use"] = '接続できません - このTableプリフィクスはすでに使われています。異なるTableプリフィクスを指定するか、phpMyAdminなどを利用し関連Tableを削除してください。';
$_lang["welcome_message_welcome"] = 'MODxのインストールを開始します。';
$_lang["welcome_message_text"] = '心踊る、未知の領域へようこそ。ガイドに従ってインストールを進めましょう。特殊な条件のサーバでない限り、マニュアルに頼る必要はありません。分かりやすいインストーラとあなたの好奇心が、このプロセスを先に進めます。<br /><br />このインストーラの手順に従うことにより、他のCMSとのデータベースの共有設定や、サンプルコンテンツ及び、推奨される拡張機能のインストールを個別に選択できます。何も選択せずシンプルにコアのみをインストールすることもできます。また、すでにインストール済みのMODxをアップデートしたり、データベースの設定を変更することもできます。';
$_lang["welcome_message_select_begin_button"] = 'まずは使用許諾を確認しましょう。「開始」ボタンをクリックしてください。';
$_lang["installation_mode"] = 'インストールの選択';
$_lang["installation_new_installation"] = '新規インストール';
$_lang["installation_install_new_copy"] = '';
$_lang["installation_install_new_note"] = '</strong>を新規インストールします。すでにMODxをインストールしている場合はデータを上書きします。<br />※Tableプリフィクスが異なる場合を除く<strong>';
$_lang["installation_upgrade_existing"] = '通常アップデート';
$_lang["installation_upgrade_existing_note"] = 'コアファイル・リソースファイルの両方とデータベースをアップデートします。';
$_lang["installation_upgrade_advanced"] = 'カスタムアップデート<br /><small>(データベース設定を変更できます)</small>';
$_lang["installation_upgrade_advanced_note"] = 'データベース設定の変更を伴うアップデートが必要な場合はこちらを選んでください。<br />';
$_lang["connection_screen_connection_information"] = 'データベース設定';
$_lang["connection_screen_connection_and_login_information"] = '<strong>データベース接続の設定</strong>';
$_lang["connection_screen_connection_note"] = 'データベース名・データベースが稼働しているホストサーバの名前・ユーザ名・パスワード等を入力してください。データベースが作られていない場合、ここでの指定のとおりにデータベース新規作成を試みます。<br />※ただし多くのレンタルサーバでは権限が制限されているためデータベースの新規作成ができないケースがほとんどです。あらかじめ用意されているデータベースをご利用ください。<br />※「Tableプリフィクス」と「MySQLの接続照合順序」は、通常は下記の初期値のままでかまいません。特に「Tableプリフィクス」は他CMSとの共存に関わる設定なので、よく分からない場合はこの値のままにしておきましょう。複数のMODxをインストールしたい場合などにこの値を操作します。';
$_lang["connection_screen_database_name"] = 'データベース名:';
$_lang["connection_screen_table_prefix"] = 'Tableプリフィクス:';
$_lang["connection_screen_collation"] = 'MySQLの接続照合順序:';
$_lang["connection_screen_character_set"] = '文字セット(character set):';
$_lang["connection_screen_database_info"] = '<strong>データベースに接続するための情報を入力してください</strong>';
$_lang["connection_screen_database_host"] = 'データベースホスト名:';
$_lang["connection_screen_database_login"] = 'データベース接続ログイン名:';
$_lang["connection_screen_database_pass"] = 'データベース接続パスワード:';
$_lang["connection_screen_test_connection"] = '接続テスト';
$_lang["connection_screen_default_admin_user"] = 'デフォルトの管理アカウント作成';
$_lang["connection_screen_default_admin_note"] = '特別な管理アカウントを、この時点で作っておきましょう。このインストールが終わったら、MODx管理画面にアクセスするためにさっそく必要になります。なお、ここでのアカウント設定は管理画面で手軽に変更できます。';
$_lang["connection_screen_default_admin_login"] = 'ログイン名(半角英数字):';
$_lang["connection_screen_default_admin_email"] = 'email:';
$_lang["connection_screen_default_admin_password"] = 'パスワード:';
$_lang["connection_screen_default_admin_password_confirm"] = 'パスワード(確認入力):';
$_lang["optional_items"] = 'インストールオプションの選択';
$_lang["optional_items_note"] = 'オプションを選択してください:<br /><br />初めてMODxを試す人は、全てチェックを入れましょう。ある程度MODxを理解している場合は、これらサンプルコンテンツがインストールされていると扱いづらいこともあります。必要に応じて選択してください。';
$_lang["sample_web_site"] = 'サンプルサイト';
$_lang["install_overwrite"] = 'インストール - ';
$_lang["sample_web_site_note"] = '<span style="font-style:normal;">新規インストールの場合は関係ありませんが、すでにMODxでサイトを構成している場合は<strong style="color:#CC0000;">上書き</strong>されます。ご注意ください。</span>';
$_lang["checkbox_select_options"] = '拡張機能の選択:';
$_lang["all"] = '全て選択';
$_lang["none"] = '全ての選択を解除';
$_lang["toggle"] = '選択状態を反転';
$_lang["templates"] = 'テンプレート';
$_lang["install_update"] = '';
$_lang["chunks"] = 'チャンク';
$_lang["modules"] = 'モジュール';
$_lang["plugins"] = 'プラグイン';
$_lang["snippets"] = 'スニペット';
$_lang["preinstall_validation"] = 'インストール前の状態確認';
$_lang["summary_setup_check"] = '<strong>インストール実行前の最終チェックです。</strong>';
$_lang["checking_php_version"] = "PHPのバージョンチェック: ";
$_lang["failed"] = '確認してください';
$_lang["ok"] = '問題なし';
$_lang["you_running_php"] = ' - You are running on PHP ';
$_lang["modx_requires_php"] = ', and MODx requires PHP 4.1.0 or later';
$_lang["php_security_notice"] = '<legend>セキュリティ警告</legend><p>このサーバ上で稼働しているPHPには重大な問題があります。MODxの稼働自体には問題はありませんが、このバージョンのPHPには報告されている脆弱性がいくつか存在し、MODxに限らず様々なPHPアプリを通じて多数の攻撃にさらされてきました。脆弱性からサイトを守る上で最低限必要とされるPHPのバージョンは4.3.8以上とされており、この機会にPHPのアップデートをおすすめします。</p>';
$_lang["checking_sessions"] = 'セッション情報が正常に構成されるかどうか: ';
$_lang["checking_if_cache_exist"] = '<span class=\"mono\">assets/cache</span>ディレクトリの存在チェック(なければ転送に失敗しています): ';
$_lang["checking_if_cache_writable"] = '<span class=\"mono\">assets/cache</span>ディレクトリの書き込み属性(707などに設定): ';
$_lang["checking_if_cache_file_writable"] = 'ファイル<span class=\"mono\">assets/cache/siteCache.idx.php</span>の書き込み属性(606などに設定): ';
$_lang["checking_if_cache_file2_writable"] = 'ファイル<span class=\"mono\">assets/cache/sitePublishing.idx.php</span>の書き込み属性(606などに設定): ';
$_lang["checking_if_images_exist"] = '<span class=\"mono\">assets/images</span>ディレクトリの存在(なければ転送に失敗しています): ';
$_lang["checking_if_images_writable"] = '<span class=\"mono\">assets/images</span>ディレクトリの書き込み属性(707などに設定): ';
$_lang["checking_if_export_exists"] = '<span class=\"mono\">assets/export</span>ディレクトリの存在(なければ転送に失敗しています): ';
$_lang["checking_if_export_writable"] = '<span class=\"mono\">assets/export</span>ディレクトリの書き込み属性(707などに設定): ';
$_lang["checking_if_config_exist_and_writable"] = 'ファイル<span class=\"mono\">manager/includes/config.inc.php</span>の存在と書き込み属性: ';
$_lang["config_permissions_note"] = '<span class=\"mono\">config.inc.php</span>という名前の空ファイルを作り<span class=\"mono\">manager/includes/</span>ディレクトリに転送し、パーミッションを606などに設定してください。';
$_lang["creating_database_connection"] = 'データベース接続: ';
$_lang["database_connection_failed"] = 'データベース接続に異常があります';
$_lang["database_connection_failed_note"] = 'データベースのログイン設定を確認し、再びチェックを試してください。';
$_lang["database_use_failed"] = 'データベースを選択できません';
$_lang["database_use_failed_note"] = 'Please check the database permissions for the specified user and try again.';
$_lang["checking_table_prefix"] = 'Tableプリフィクスの設定 `';
$_lang["table_prefix_already_inuse"] = ' - このTableプリフィクスはすでに使われています。';
$_lang["table_prefix_already_inuse_note"] = '異なるTableプリフィクスを指定するか、phpMyAdminなどを利用し関連Tableを削除し、再びインストールを試してみてください。';
$_lang["table_prefix_not_exist"] = ' - Table prefix does not exist in this database!';
$_lang["table_prefix_not_exist_note"] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table prefix, and run Setup again.';
$_lang["setup_cannot_continue"] = 'Unfortunately, Setup cannot continue at the moment, due to the above ';
$_lang["error"] = 'エラー';
$_lang["errors"] = 'errors'; //Plural form
$_lang["please_correct_error"] = '. Please correct the error';
$_lang["please_correct_errors"] = '. Please correct the errors'; //Plural form
$_lang["and_try_again"] = ', and try again. If you need help figuring out how to fix the problem';
$_lang["and_try_again_plural"] = ', and try again. If you need help figuring out how to fix the problems'; //Plural form
$_lang["visit_forum"] = ', visit the <a href="http://www.modxcms.com/forums/" target="_blank">Operation MODx Forums</a>.';
$_lang["testing_connection"] = '接続テスト中...';
$_lang["btnback_value"] = '戻る';
$_lang["btnnext_value"] = '進む';
$_lang["retry"] = '再チェック';
$_lang["alert_enter_database_name"] = 'You need to enter a value for database name!';
$_lang["alert_table_prefixes"] = 'Table prefixes must start with a letter!';
$_lang["alert_enter_host"] = 'You need to enter a value for database host!';
$_lang["alert_enter_login"] = 'You need to enter your database login name!';
$_lang["alert_enter_adminlogin"] = 'You need to enter a username for the system admin account!';
$_lang["alert_enter_adminpassword"] = 'You need to a password for the system admin account!';
$_lang["alert_enter_adminconfirm"] = 'The administrator password and the confirmation don\\\'t match!';
$_lang["iagree_box"] = '<strong style="color:#8b0000;">このライセンスで規定される諸条件に同意します。</strong>';
$_lang["btnclose_value"] = '閉じる';
$_lang["running_setup_script"] = 'セットアップを実行中... しばらくお待ちください';
$_lang["modx_footer1"] = '&copy; 2005-2008 the <a href="http://www.modxcms.com/" target="_blank" style="color: green; text-decoration:underline">MODx</a> Content Mangement Framework (CMF) project. All rights reserved. MODx is licensed under the GNU GPL.';
$_lang["modx_footer2"] = 'MODx is free software.  We encourage you to be creative and make use of MODx in any way you see fit. Just make sure that if you do make changes and decide to redistribute your modified MODx, that you keep the source code free!';
$_lang["setup_database"] = 'Setup will now attempt to setup the database:<br />';
$_lang["setup_database_create_connection"] = 'データベース接続: ';
$_lang["setup_database_create_connection_failed"] = 'Database connection failed!';
$_lang["setup_database_create_connection_failed_note"] = 'Please check the database login details and try again.';
$_lang["setup_database_selection"] = 'データベース選択 `';
$_lang["setup_database_selection_failed"] = 'Database selection failed...';
$_lang["setup_database_selection_failed_note"] = 'The database does not exist. Setup will attempt to create it.';
$_lang["setup_database_creation"] = 'Creating database `';
$_lang["setup_database_creation_failed"] = 'Database creation failed!';
$_lang["setup_database_creation_failed_note"] = ' - Setup could not create the database!';
$_lang["setup_database_creation_failed_note2"] = 'Setup could not create the database, and no existing database with the same name was found. It is likely that your hosting provider\'s security does not allow external scripts to create a database. Please create a database according to your hosting provider\'s procedure, and run Setup again.';
$_lang["setup_database_creating_tables"] = '必要なTableの生成: ';
$_lang["database_alerts"] = 'データベースの警告';
$_lang["setup_couldnt_install"] = 'MODx setup couldn\'t install/alter some tables inside the selected database.';
$_lang["installation_error_occured"] = 'The following errors had occurred during installation';
$_lang["during_execution_of_sql"] = ' during the execution of SQL statement ';
$_lang["some_tables_not_updated"] = 'Some tables were not updated. This might be due to previous modifications.';
$_lang["installing_demo_site"] = 'サンプルサイトのインストール: ';
$_lang["writing_config_file"] = 'config.inc.phpへの書き込み(設定情報): ';
$_lang["cant_write_config_file"] = 'MODx couldn\'t write the config file. Please copy the following into the file ';
$_lang["cant_write_config_file_note"] = 'Once that\'s been done, you can log into MODx Admin by pointing your browser at YourSiteName.com/manager/.';
$_lang["unable_install_template"] = 'Unable to install template.  File';
$_lang["unable_install_chunk"] = 'Unable to install chunk.  File';
$_lang["unable_install_module"] = 'Unable to install module.  File';
$_lang["unable_install_plugin"] = 'Unable to install plugin.  File';
$_lang["unable_install_snippet"] = 'Unable to install snippet.  File';
$_lang["not_found"] = 'not found';
$_lang["upgraded"] = 'アップデートしました';
$_lang["installed"] = 'インストールしました';
$_lang["running_database_updates"] = '実行中のデータベースのアップデート: ';
$_lang["installation_successful"] = 'インストールは無事に成功しました。';
$_lang["to_log_into_content_manager"] = 'お待たせしました。「閉じる」ボタンをクリックすると、<a href="../manager/">管理画面のログインページ</a>(manager/index.php)にアクセスします。';
$_lang["install"] = 'インストール実行';
$_lang["remove_install_folder_auto"] = 'インストールディレクトリを自動的に削除する<br />&nbsp;(この操作はサーバ設定によっては実行されないことがあります。削除できなかった場合は、管理画面ログイン時に太文字で警告が表示されますので、手作業で削除してください).';
$_lang["remove_install_folder_manual"] = 'Please remember to remove the &quot;<b>install</b>&quot; folder before you log into the Content Manager.';
$_lang["install_results"] = 'インストールを完了しました。おつかれさまでした！';
$_lang["installation_note"] = '<strong>はじめに:</strong>管理画面に無事にログインできたら、「Tools」タブの「Configuration」をクリックし、MODx設定画面を開いてください。ここで「Language」を「Japanese-utf8」に変更すると、管理画面が日本語表記になります。次に、適当なページを編集・保存し、文字化けが起きないかどうかを確認してください。もし文字化けが発生し困った場合は<a href="http://modxcms.com/forums/index.php#10">フォーラム</a>にご相談ください。ボランティアユーザが解決のお手伝いをします。';
$_lang["upgrade_note"] = '<strong>Note:</strong> Before browsing your site you should log into the manager with an administrative account, then review and save your System Configuration settings.';
?>