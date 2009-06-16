<?php
/**
 * MODx language File
 *
 * @author MEGU, yamamoto.kyms
 * @package MODx
 * @version 1.0
 * 
 * Filename:       /install/lang/japanese-utf8/japanese-utf8.inc.php
 * Language:       Japanese
 * Encoding:       utf-8
 */
$_lang["license"] = '<h2>MODxの著作権と使用許諾条件について</h2>
	    <hr style="text-align:left;height:1px;width:90%" />
	    <h3>翻訳チームより</h3>
	    <p>
	    <a href="http://www.gnu.org/licenses/translations.ja.html">http://www.gnu.org/licenses/translations.ja.html</a><br />
	    ※上記ページで示される理由により、以下に示す使用許諾条件についてはあえて翻訳をしておりません。
	    </p>
		<h3>You must agree to the License before continuing installation.</h3>
		<p>Usage of this software is subject to the GPL license. To help you understand
		what the GPL licence is and how it affects your ability to use the software, we
		have provided the following summary:</p>
		<h3>The GNU General Public License is a Free Software license.</h3>
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
            <li>Your fair use and other rights are in no way affected by the above.</li>
        </ul>
		<p>The above is a summary of the GNU General Public License. By proceeding, you
		are agreeing to the GNU General Public Licence, not the above. The above is
		simply a summary of the GNU General Public Licence, and its accuracy is not
		guaranteed. It is strongly recommended you read the <a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU General Public
		License</a> in full before proceeding, which can also be found in the license
		file distributed with this package.</p>';
$_lang["alert_database_test_connection"] = 'You need to create your database or test the selection of your database!';
$_lang["alert_database_test_connection_failed"] = 'The test of your database selection has failed!';
$_lang["alert_enter_adminconfirm"] = 'The administrator password and the confirmation don\'t match!';
$_lang["alert_enter_adminlogin"] = 'You need to enter a username for the system admin account!';
$_lang["alert_enter_adminpassword"] = 'You need to enter a password for the system admin account!';
$_lang["alert_enter_database_name"] = 'You need to enter a value for database name!';
$_lang["alert_enter_host"] = 'DBのホスト名を入力してください';
$_lang["alert_enter_login"] = 'DBのログイン名を入力してください';
$_lang["alert_server_test_connection"] = 'You need to test your server connection!';
$_lang["alert_server_test_connection_failed"] = 'The test of your server connection has failed!';
$_lang["alert_table_prefixes"] = 'Table prefixes must start with a letter!';
$_lang["all"] = '全て選択';
$_lang["and_try_again"] = 'これらのエラーを修正し、右下の「再チェック」ボタンをクリックしてください。';
$_lang["and_try_again_plural"] = 'これらのエラーを修正し、右下の「再チェック」ボタンをクリックしてください。'; //Plural form
$_lang["begin"] = '開始';
$_lang["btnback_value"] = '戻る';
$_lang["btnclose_value"] = 'インストール終了';
$_lang["btnnext_value"] = '進む';
$_lang["cant_write_config_file"] = 'MODx couldn\'t write the config file. Please copy the following into the file ';
$_lang["cant_write_config_file_note"] = 'Once that\'s been done, you can log into MODx Admin by pointing your browser at YourSiteName.com/manager/.';
$_lang["checkbox_select_options"] = '拡張機能の選択:';
$_lang["checking_if_cache_exist"] = '<span class=\"mono\">assets/cache</span>ディレクトリの存在チェック(なければ転送に失敗しています): ';
$_lang["checking_if_cache_file2_writable"] = 'ファイル<span class=\"mono\">assets/cache/sitePublishing.idx.php</span>の書き込み属性(606などに設定): ';
$_lang["checking_if_cache_file_writable"] = 'ファイル<span class=\"mono\">assets/cache/siteCache.idx.php</span>の書き込み属性(606などに設定): ';
$_lang["checking_if_cache_writable"] = '<span class=\"mono\">assets/cache</span>ディレクトリの書き込み属性(707などに設定): ';
$_lang["checking_if_config_exist_and_writable"] = 'ファイル<span class=\"mono\">manager/includes/config.inc.php</span>の存在と書き込み属性: ';
$_lang["checking_if_export_exists"] = '<span class=\"mono\">assets/export</span>ディレクトリの存在(なければ転送に失敗しています): ';
$_lang["checking_if_export_writable"] = '<span class=\"mono\">assets/export</span>ディレクトリの書き込み属性(707などに設定): ';
$_lang["checking_if_images_exist"] = '<span class=\"mono\">assets/images</span>ディレクトリの存在(なければ転送に失敗しています): ';
$_lang["checking_if_images_writable"] = '<span class=\"mono\">assets/images</span>ディレクトリの書き込み属性(707などに設定): ';
$_lang["checking_mysql_strict_mode"] = 'Checking MySQL for strict mode: ';
$_lang["checking_mysql_version"] = 'MySQLのバージョン: ';
$_lang["checking_php_version"] = 'PHPのバージョンチェック: ';
$_lang["checking_registerglobals"] = 'Register_Globalsの設定: ';
$_lang["checking_registerglobals_note"] = 'Register_Globalsがオンになっていると、サイトはXSS攻撃の対象としてさらされます。非常に危険ですので、特に必要がなければオフにしてください。.htaccessに「php_flag register_globals off」と記述を加えることでオフに設定できます。'; //Look at changing this to provide a solution.
$_lang["checking_sessions"] = 'セッション情報が正常に構成されるかどうか: ';
$_lang["checking_table_prefix"] = 'Tableプリフィクスの設定 `';
$_lang["chunks"] = 'チャンク';
$_lang["config_permissions_note"] = '<span class=\"mono\">config.inc.php</span>という名前の空ファイルを作り<span class=\"mono\">manager/includes/</span>ディレクトリに転送し、パーミッションを606などに設定してください。';
$_lang["connection_screen_character_set"] = '文字セット(character set):';
$_lang["connection_screen_collation"] = '照合順序(文字セット指定含む):';
$_lang["connection_screen_connection_information"] = 'データベース設定';
$_lang["connection_screen_connection_method"] = '接続時の文字セットの扱い:';
$_lang["connection_screen_database_connection_information"] = 'データベース設定';
$_lang["connection_screen_database_connection_note"] = 'データベース名を入力してください。データベース作成権限がある場合は、指定に従ってデータベースが作成されます。<br />文字セットの扱いは「SET CHARACTER SET」、接続照合順序は「utf8_general_ci」をおすすめします。<br />※なおMySQL4.1未満ではこれらのエンコード設定を無視して日本語を扱います。';
$_lang["connection_screen_database_host"] = 'データベースホスト名:';
$_lang["connection_screen_database_login"] = 'データベース接続ログイン名:';
$_lang["connection_screen_database_name"] = 'データベース名:';
$_lang["connection_screen_database_pass"] = 'データベース接続パスワード:';
$_lang["connection_screen_database_test_connection"] = 'ここをクリックして指定条件による既存データベースとのマッチングを確認できます。権限がある場合は、ここで条件を指定しデータベースを新規に作成できます';
$_lang["connection_screen_default_admin_email"] = 'email:';
$_lang["connection_screen_default_admin_information"] = 'Administrator information';
$_lang["connection_screen_default_admin_login"] = 'ログイン名(半角英数字):';
$_lang["connection_screen_default_admin_note"] = 'デフォルトの管理アカウントを作成します。メールアドレスはパスワード再発行の際に必要となるので、タイプミスがないよう気をつけてください。';
$_lang["connection_screen_default_admin_password"] = 'パスワード:';
$_lang["connection_screen_default_admin_password_confirm"] = 'パスワード(確認入力):';
$_lang["connection_screen_default_admin_user"] = 'デフォルトの管理アカウント作成';
$_lang["connection_screen_server_connection_information"] = 'データベースホストへの接続';
$_lang["connection_screen_server_connection_note"] = 'データベースサーバのホスト名・ログイン名・パスワードを入力し、「ここをクリック」をクリックし接続テストをしてください。<br />※MODx本体はMySQL4.0.2以上をサポートしますが、MySQL4.1未満ではAjaxSearchなど一部のアドオンが使えません。ご注意ください。';
$_lang["connection_screen_server_test_connection"] = 'ここをクリックすると正常に接続できるかどうかを確認できます';
$_lang["connection_screen_table_prefix"] = 'Tableプリフィクス:';
$_lang["creating_database_connection"] = 'データベース接続: ';
$_lang["database_alerts"] = 'データベースの警告';
$_lang["database_connection_failed"] = 'データベース接続に異常があります';
$_lang["database_connection_failed_note"] = 'データベースのログイン設定を確認し、再びチェックを試してください。';
$_lang["database_use_failed"] = 'データベースを選択できません';
$_lang["database_use_failed_note"] = 'Please check the database permissions for the specified user and try again.';
$_lang["during_execution_of_sql"] = ' during the execution of SQL statement ';
$_lang["encoding"] = 'utf-8';	//charset encoding for html header
$_lang["error"] = 'つのエラー';
$_lang["errors"] = 'つのエラー'; //Plural form
$_lang["failed"] = '確認してください';
$_lang["iagree_box"] = '<strong style="color:#8b0000;">このライセンスで規定される諸条件に同意します。</strong>';
$_lang["install"] = 'プリチェック開始';
$_lang["install_overwrite"] = 'インストール - ';
$_lang["install_results"] = 'インストールを完了しました。おつかれさまでした！';
$_lang["install_update"] = '';
$_lang["installation_error_occured"] = 'The following errors had occurred during installation';
$_lang["installation_install_new_copy"] = '';
$_lang["installation_install_new_note"] = '</strong>を新規インストールします。すでにMODxをインストールしている場合はデータを上書きします。<br />※Tableプリフィクスが異なる場合を除く<strong>';
$_lang["installation_mode"] = 'インストールの選択';
$_lang["installation_new_installation"] = '新規インストール';
$_lang["installation_note"] = '<strong>はじめに:</strong>管理画面に無事にログインできたら、ドキュメント及び各種設定を日本語を含めて編集・保存し、文字化けが起きないかどうかを必ず確認してください。';
$_lang["installation_successful"] = 'インストールは無事に成功しました。';
$_lang["installation_upgrade_advanced"] = 'カスタムアップデート<br /><small>(データベース設定を変更できます)</small>';
$_lang["installation_upgrade_advanced_note"] = 'データベース設定の変更を伴うアップデートが必要な場合はこちらを選んでください。<br />';
$_lang["installation_upgrade_existing"] = '通常アップデート';
$_lang["installation_upgrade_existing_note"] = 'コアファイル・リソースファイルの両方とデータベースをアップデートします。';
$_lang["installed"] = 'インストールしました';
$_lang["installing_demo_site"] = 'サンプルサイトのインストール: ';
$_lang["language_code"] = 'ja';	// for html element e.g. <html xml:lang="ja" lang="ja">
$_lang["loading"] = '処理中...';
$_lang["modules"] = 'モジュール';
$_lang["modx_footer1"] = '&copy; 2005-2009 the <a href="http://www.modxcms.com/" target="_blank" style="color: green; text-decoration:underline">MODx</a> Content Mangement Framework (CMF) project. All rights reserved. MODx is licensed under the GNU GPL.';
$_lang["modx_footer2"] = 'MODx is free software.  We encourage you to be creative and make use of MODx in any way you see fit. Just make sure that if you do make changes and decide to redistribute your modified MODx, that you keep the source code free!';
$_lang["modx_install"] = 'MODx &raquo; インストール';
$_lang["modx_requires_php"] = ', and MODx requires PHP 4.2.0 or later';
$_lang["mysql_5051"] = ' MySQL server version is 5.0.51!';
$_lang["mysql_5051_warning"] = 'MySQL 5.0.51では問題が確認されています。アップデートをおすすめします。';
$_lang["mysql_version_is"] = ' Version ';
$_lang["none"] = '全ての選択を解除';
$_lang["not_found"] = 'not found';
$_lang["ok"] = '問題なし';
$_lang["optional_items"] = 'インストールオプションの選択';
$_lang["optional_items_note"] = 'オプションを選択してください:<br /><br />初めてMODxを試す人は、全てチェックを入れましょう。ある程度MODxを理解している場合は、これらサンプルコンテンツがインストールされていると逆に扱いづらいこともあります。必要に応じて選択してください。';
$_lang["php_security_notice"] = '<legend>セキュリティ警告</legend><p>このサーバ上で稼働しているPHPには重大な問題があります。MODxの稼働自体には問題はありませんが、このバージョンのPHPには報告されている脆弱性がいくつか存在し、MODxに限らず様々なPHPアプリを通じて多数の攻撃にさらされてきました。バージョン4.3.8より古いPHPは深刻な脆弱性を抱えています。この機会にPHPのアップデートをおすすめします。</p>';
$_lang["please_correct_error"] = 'があります。';
$_lang["please_correct_errors"] = 'があります。'; //Plural form
$_lang["plugins"] = 'プラグイン';
$_lang["preinstall_validation"] = 'インストール前の状態確認';
$_lang["remove_install_folder_auto"] = 'インストールディレクトリを自動的に削除する<br />&nbsp;(この操作はサーバ設定によっては実行されないことがあります。削除できなかった場合は、管理画面ログイン時に太文字で警告が表示されますので、手作業で削除してください).';
$_lang["remove_install_folder_manual"] = 'Please remember to remove the &quot;<b>install</b>&quot; folder before you log into the Content Manager.';
$_lang["retry"] = '再チェック';
$_lang["running_database_updates"] = '実行中のデータベースのアップデート: ';
$_lang["running_setup_script"] = 'セットアップを実行中... しばらくお待ちください';
$_lang["sample_web_site"] = 'サンプルサイト';
$_lang["sample_web_site_note"] = '<span style="font-style:normal;">新規インストールの場合は関係ありませんが、すでにMODxでサイトを構成している場合は<strong style="color:#CC0000;">上書き</strong>されます。ご注意ください。</span>';
$_lang["setup_cannot_continue"] = '';
$_lang["setup_couldnt_install"] = 'MODx setup couldn\'t install/alter some tables inside the selected database.';
$_lang["setup_database"] = 'セットアップ結果<br />';
$_lang["setup_database_create_connection"] = 'データベース接続: ';
$_lang["setup_database_create_connection_failed"] = 'Database connection failed!';
$_lang["setup_database_create_connection_failed_note"] = 'Please check the database login details and try again.';
$_lang["setup_database_creating_tables"] = '必要なテーブルの作成: ';
$_lang["setup_database_creation"] = 'Creating database `';
$_lang["setup_database_creation_failed"] = 'Database creation failed!';
$_lang["setup_database_creation_failed_note"] = ' - Setup could not create the database!';
$_lang["setup_database_creation_failed_note2"] = 'Setup could not create the database, and no existing database with the same name was found. It is likely that your hosting provider\'s security does not allow external scripts to create a database. Please create a database according to your hosting provider\'s procedure, and run Setup again.';
$_lang["setup_database_selection"] = 'データベース選択 `';
$_lang["setup_database_selection_failed"] = 'Database selection failed...';
$_lang["setup_database_selection_failed_note"] = 'The database does not exist. Setup will attempt to create it.';
$_lang["snippets"] = 'スニペット';
$_lang["some_tables_not_updated"] = 'Some tables were not updated. This might be due to previous modifications.';
$_lang["status_checking_database"] = '...    データベースとのマッチング: ';
$_lang["status_connecting"] = ' DBホストとの接続テストの結果: ';
$_lang["status_failed"] = '接続できません';
$_lang["status_failed_could_not_create_database"] = 'データベースを作成できません';
$_lang["status_failed_could_not_select_database"] = '接続できません - データベース名を確認 - ';
$_lang["status_failed_database_collation_does_not_match"] = 'failed - データベース側の照合順序のデフォルト値が「%s」になっています。データベース側の照合順序の設定を「utf8_general_ci」などutf8系の値に変更してからインストールを再試行してください。phpMyAdminが利用できる場合は、該当データベースの「操作」タブで照合順序のデフォルト値を変更できます。';
$_lang["status_failed_table_prefix_already_in_use"] = '接続できません - このTableプリフィクスはすでに使われています。異なるTableプリフィクスを指定するか、phpMyAdminなどを利用し関連Tableを削除してください。';
$_lang["status_passed"] = '問題ありません';
$_lang["status_passed_database_created"] = 'データベースを作成できます';
$_lang["status_passed_server"] = '接続できます';
$_lang["strict_mode"] = ' MySQL server is in strict mode!';
$_lang["strict_mode_error"] = 'MODx requires that strict mode be disabled. You can set the MySQL mode by editing the my.cnf file or contact your server administrator.';
$_lang["summary_setup_check"] = '<strong>インストール実行前の最終チェックです。</strong>';
$_lang["table_prefix_already_inuse"] = ' - このTableプリフィクスはすでに使われています。';
$_lang["table_prefix_already_inuse_note"] = '異なるTableプリフィクスを指定するか、phpMyAdminなどを利用し関連Tableを削除し、再びインストールを試してみてください。';
$_lang["table_prefix_not_exist"] = ' - Table prefix does not exist in this database!';
$_lang["table_prefix_not_exist_note"] = 'Setup couldn\'t install into the selected database, as it does not contain existing tables with the prefix you specified to be upgraded. Please choose an existing table prefix, and run Setup again.';
$_lang["templates"] = 'テンプレート';
$_lang["testing_connection"] = '接続テスト中...';
$_lang["to_log_into_content_manager"] = 'おつかれさまでした。「インストール終了」ボタンをクリックすると、<a href="../manager/">管理画面のログインページ</a>(manager/index.php)にアクセスします。';
$_lang["toggle"] = '選択状態を反転';
$_lang["unable_install_chunk"] = 'Unable to install chunk.  File';
$_lang["unable_install_module"] = 'Unable to install module.  File';
$_lang["unable_install_plugin"] = 'Unable to install plugin.  File';
$_lang["unable_install_snippet"] = 'Unable to install snippet.  File';
$_lang["unable_install_template"] = 'Unable to install template.  File';
$_lang["upgrade_note"] = '<strong>Note:</strong> Before browsing your site you should log into the manager with an administrative account, then review and save your System Configuration settings.';
$_lang["upgraded"] = 'アップデートしました';
$_lang["visit_forum"] = '';
$_lang["warning"] = '警告 ';
$_lang["welcome_message_select_begin_button"] = 'まずは使用許諾を確認しましょう。「開始」ボタンをクリックしてください。';
$_lang["welcome_message_text"] = '心踊る、未知の領域へようこそ。ガイドに従ってインストールを進めましょう。特殊な条件のサーバでない限り、マニュアルに頼る必要はありません。分かりやすいインストーラとあなたの好奇心が、このプロセスを先に進めます。<br /><br />このインストーラの手順に従うことにより、他のCMSとのデータベースの共有設定(Tableプリフィクス)や、サンプルコンテンツ及び、推奨される拡張機能のインストールを個別に選択できます。何も選択せずシンプルにコアのみをインストールすることもできます。また、すでに運用中のMODxをアップデートしたり、データベースの設定を変更することもできます。';
$_lang["welcome_message_welcome"] = 'MODxのインストールを開始します。';
$_lang["writing_config_file"] = 'config.inc.phpへの書き込み(設定情報): ';
$_lang["you_running_php"] = ' - You are running on PHP ';
?>