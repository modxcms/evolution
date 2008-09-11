<?php
/**
 * MODx language File
 *
 * @author davaeron
 * @package MODx
 * @version 1.0
 * 
 * Filename:       /install/lang/russian/russian.inc.php
 * Language:       Russian
 * Encoding:       UTF-8
 * Translated by:  Pertsev Dmitriy
                   Safronovich Victor
 * Date:           29 june 2008
 */

setlocale (LC_ALL, 'ru_RU');
$_lang['license'] = '<p class="title">Лицензионное соглашение MODx.</p>
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
$_lang["encoding"] = 'utf-8';
$_lang["modx_install"] = 'Установка MODx';
$_lang["loading"] = 'Загружается...';
$_lang["Begin"] = 'Начать';
$_lang["status_connecting"] = ' Соединяюсь: ';
$_lang["status_failed"] = 'ошибка!';
$_lang["status_passed"] = 'успех - база данных выбрана';
$_lang["status_passed_server"] = 'успех - сопоставление базы данных не доступно';
$_lang["status_passed_database_created"] = 'успех - база данных выбрана';
$_lang["status_checking_database"] = 'Проверка базы данных: ';
$_lang["status_failed_could_not_select_database"] = 'ошибка - нет базы данных';
$_lang["status_failed_could_not_create_database"] = 'ошибка - не удается создать базу данных';
$_lang["status_failed_table_prefix_already_in_use"] = 'ошибка - префикс таблицы уже используется!';
$_lang["welcome_message_welcome"] = 'Добро пожаловать в программу установки MODx.';
$_lang["welcome_message_text"] = 'Эта программа проведет Вас через весь процесс установки.';
$_lang["welcome_message_select_begin_button"] = 'Щелкните кнопку `Начать`:';
$_lang["installation_mode"] = 'Режим установки';
$_lang["installation_new_installation"] = 'Новая установка';
$_lang["installation_install_new_copy"] = 'Установить новую копию ';
$_lang["installation_install_new_note"] = '<br />Пожалуйста заметьте, что эта опция может перезаписать данные в Вашей базе данных.';
$_lang["installation_upgrade_existing"] = 'Обновление существующей<br />установки';
$_lang["installation_upgrade_existing_note"] = 'Обновление Ваших файлов и базы данных.';
$_lang["installation_upgrade_advanced"] = 'Расширенное обновление<br />установки<br /><small>(с настройкой параметров<br />базы данных)</small>';
$_lang["installation_upgrade_advanced_note"] = 'Для расширенного управления базой данных с различным набором символов.<br /><b>Вы должны знать полное название Вашей базы данных, имя пользователя, пароль, детали подключения, таблицу сопоставления.</b>';
$_lang["connection_screen_connection_information"] = 'Информация о подключении';
$_lang["connection_screen_server_connection_information"] = 'Параметры подключения и входа на сервер базы данных';
$_lang["connection_screen_server_connection_note"] = 'Введите данные для входа в базу данных и затем проверьте их.';
$_lang["connection_screen_server_test_connection"] = 'Нажмите здесь для проверки соединения с вашим сервером базы данных и получения сопостовления кодировки';
$_lang["connection_screen_database_connection_information"] = 'Параметры базы данных';
$_lang["connection_screen_database_connection_note"] = 'Введите имя базы данных, созданной для MODx. Если у Вас еще нет базы данных, то программа установки попытается создать базу данных для Вас. В зависимости от конфигурации MySQL или прав пользователя базы данных процесс может завершиться неудачей.';
$_lang["connection_screen_database_test_connection"] = 'Нажмите здесь для создания базы данных или для проверки что такая база существует';
$_lang["connection_screen_database_name"] = 'Имя базы данных:';
$_lang["connection_screen_table_prefix"] = 'Префикс таблиц:';
$_lang["connection_screen_collation"] = 'Сопоставление:';
$_lang["connection_screen_character_set"] = 'Набор символов подключения:';
$_lang["connection_screen_database_host"] = 'Хост базы данных:';
$_lang["connection_screen_database_login"] = 'Имя пользователя:';
$_lang["connection_screen_database_pass"] = 'Пароль:';
$_lang["connection_screen_default_admin_information"] = 'Информация об администраторе';
$_lang["connection_screen_default_admin_user"] = 'Администратор по умолчанию';
$_lang["connection_screen_default_admin_note"] = 'Теперь Вы должны ввести данные о главной записи Администратора. Вы можете ввести свое имя и пароль, который Вы вряд ли забудете. Вам понадобятся эти данные чтобы войти в Панель Управления после окончания установки.';
$_lang["connection_screen_default_admin_login"] = 'Имя администратора:';
$_lang["connection_screen_default_admin_email"] = 'E-mail администратора:';
$_lang["connection_screen_default_admin_password"] = 'Пароль администратора:';
$_lang["connection_screen_default_admin_password_confirm"] = 'Подтвердить пароль:';
$_lang["optional_items"] = 'Дополнительные элементы';
$_lang["optional_items_note"] = 'Пожалуйста выберите опции установки и щелкните `Установить`:';
$_lang["sample_web_site"] = 'Пример веб-сайта';
$_lang["install_overwrite"] = 'Установить/Переписать';
$_lang["sample_web_site_note"] = 'Осторожно! Эта опция <b style=\"color:#CC0000\">перепишет</b> существующие документы и ресурсы.';
$_lang["checkbox_select_options"] = 'Опции выбора флажков:';
$_lang["all"] = 'Все';
$_lang["none"] = 'Ниодин';
$_lang["toggle"] = 'Переключить';
$_lang["templates"] = 'Шаблоны';
$_lang["install_update"] = 'Установить/Обновить';
$_lang['chunks'] = 'Чанки';
$_lang["modules"] = 'Модули';
$_lang["plugins"] = 'Плагины';
$_lang["snippets"] = 'Cниппеты';
$_lang["preinstall_validation"] = 'Проверка перед установкой';
$_lang["summary_setup_check"] = 'Программа установки выполнит несколько тестов, чтобы удостовериться что все готово к установке.';
$_lang["checking_php_version"] = 'Проверка версии PHP: ';
$_lang["failed"] = 'Ошибка!';
$_lang["ok"] = 'OK!';
$_lang["you_running_php"] = ' - Вы используете PHP ';
$_lang["modx_requires_php"] = ', а MODx необходим PHP 4.1.0 или более поздний';
$_lang["php_security_notice"] = '<legend>Уведомление безопасности</legend><p>Несмотря на то, что MODx будет работать на Вашей версии PHP, использовать его c этой версией PHP крайне не рекомендуется.  Ваша версия PHP уязвима из-за многочисленных брешей в защите. Обновите PHP до версии 4.3.8 или более поздней для безопасности Вашего сайта.</p>';
$_lang["checking_registerglobals"] = 'Проверка php-пареметра Register_Globals: ';
$_lang["checking_registerglobals_note"] = 'Конфигурация php делает ваш сайт более восприимчевым к XSS-аттакам. Вы должны самостоятельно, или связавшись с администрацией хостинга, выключить Register_Globals. Обычно это делается одним из следующих путей: вносятся исправления в php.ini файл, добавляются правила в .htaccess файл, который находится в корне папки MODx, или добавлением своего php.ini в каждую директорию внутри папки MODx (их очень много). Вы можете продолжить установку MODx, но обдумайте это предупреждение.'; //Look at changing this to provide a solution.
$_lang["checking_sessions"] = 'Проверка настроек сессий: ';
$_lang["checking_if_cache_exist"] = 'Проверка существования папки <span class=\"mono\">assets/cache</span>: ';
$_lang["checking_if_cache_writable"] = 'Проверка возможности записи в папку <span class=\"mono\">assets/cache</span>: ';
$_lang["checking_if_cache_file_writable"] = 'Проверка возможности записи в файл <span class=\"mono\">assets/cache/siteCache.idx.php</span>: ';
$_lang["checking_if_cache_file2_writable"] = 'Проверка возможности записи в файл <span class=\"mono\">assets/cache/sitePublishing.idx.php</span>: ';
$_lang["checking_if_images_exist"] = 'Проверка существования папки <span class=\"mono\">assets/images</span>: ';
$_lang["checking_if_images_writable"] = 'Проверка возможности записи в папку <span class=\"mono\">assets/images</span>: ';
$_lang["checking_if_export_exists"] = 'Проверка существования папки <span class=\"mono\">assets/export</span>: ';
$_lang["checking_if_export_writable"] = 'Проверка возможности записи в папку <span class=\"mono\">assets/export</span>: ';
$_lang["checking_if_config_exist_and_writable"] = 'Проверка существования и возможности записи в файл <span class=\"mono\">manager/includes/config.inc.php</span>: ';
$_lang["config_permissions_note"] = 'При новой Linux/Unix установке, создайте пустой файл <span class=\"mono\">config.inc.php</span> в папке <span class=\"mono\">manager/includes/</span> с правами 0666.';
$_lang["creating_database_connection"] = 'Проверка соединения с базой данных: ';
$_lang["database_connection_failed"] = 'Ошибка соединения с базой данных!';
$_lang["database_connection_failed_note"] = 'Проверьте параметры соединения и попробуйте еще раз.';
$_lang["database_use_failed"] = 'Невозможно выбрать базу данных!';
$_lang["database_use_failed_note"] = 'Проверьте есть ли у Вас необходимые права на доступ к базе данных.';
$_lang["checking_table_prefix"] = 'Проверка префикса таблиц `';
$_lang["table_prefix_already_inuse"] = ' - Такой префикс таблиц уже используется в базе данных!';
$_lang["table_prefix_already_inuse_note"] = 'Продолжение установки невозможно. Уже существуют таблицы с указаным префиксом, измените префикс таблиц и попробуйте снова.';
$_lang["table_prefix_not_exist"] = ' - Нет такого префикса таблиц в базе данных!';
$_lang["table_prefix_not_exist_note"] = 'Продолжение установки невозможно так как нет таблиц с указаным префиксом, измените префикс таблиц и попробуйте снова.';
$_lang["setup_cannot_continue"] = 'К сожалению установка не может быть продолжена из-за ';
$_lang["error"] = 'ошибки';
$_lang["errors"] = 'ошибок'; //Plural form
$_lang["please_correct_error"] = '. Исправьте эту ошибку';
$_lang["please_correct_errors"] = '. Исправьте эти ошибки'; //Plural form
$_lang["and_try_again"] = ', и попробуйте снова. Если Вам нужна помощь по исправлению этой ошибки';
$_lang["and_try_again_plural"] = ', и попробуйте снова. Если Вам нужна помощь по исправлению этих ошибок'; //Plural form
$_lang["checking_mysql_version"] = 'Проверка версии MySQL: ';
$_lang["mysql_version_is"] = ' Ваша версия MySQL: ';
$_lang["mysql_5051_warning"] = 'Известны проблемы с MySQL 5.0.51. Настоятельно рекомендуем обновить базу данных перед продолжением установки.';
$_lang["mysql_5051"] = ' версия MySQL - 5.0.51!';
$_lang["checking_mysql_strict_mode"] = 'Проверка MySQL на строгий режим: ';
$_lang["strict_mode_error"] = 'MODx требует чтобы строгий режим был выключен. Вы можете установить режим через внесения изменений в my.cnf файл или связаться с администратором базы данных.';
$_lang["strict_mode"] = ' сервер MySQL работает в строгом режиме!';
$_lang["visit_forum"] = ', посетите форум <a href="http://www.modxcms.com/forums/" target="_blank">Operation MODx Forums</a>.';
$_lang["testing_connection"] = 'Проверка соединения...';
$_lang["btnback_value"] = 'Назад';
$_lang["btnnext_value"] = 'Далее';
$_lang["retry"] = 'Повторить';
$_lang["alert_enter_host"] = 'Введите хост базы данных!';
$_lang["alert_enter_login"] = 'Введите имя пользователя базы данных!';
$_lang["alert_server_test_connection"] = 'Проверьте соединение с сервером базы данных!';
$_lang["alert_server_test_connection_failed"] = 'Соединиться с сервером базы данных не удалось!';
$_lang["alert_enter_database_name"] = 'Введите имя базы данных!';
$_lang["alert_table_prefixes"] = 'Префикс таблиц должен начинаться с буквы!';
$_lang["alert_enter_adminlogin"] = 'Введите имя Администратора!';
$_lang["alert_enter_adminpassword"] = 'Введите пароль Администратора!';
$_lang["alert_enter_adminconfirm"] = 'Пароль Администратора и подтверждение пароля не сходятся!';
$_lang["iagree_box"] = 'Я согласен с условиями лицензии.';
$_lang["btnclose_value"] = 'Закрыть';
$_lang["running_setup_script"] = 'Запущен скрипт установки... ждите';
$_lang["modx_footer1"] = '&copy; 2005-2008 <a href="http://www.modxcms.com/" target="_blank" style="color: green; text-decoration:underline">MODx</a> Content Mangement Framework (CMF) project. Все права защищены. MODx лицензирован GNU GPL.';
$_lang["modx_footer2"] = 'MODx - свободное ПО.  Мы поощряем Вас быть творческими и использовать MODx как Вы считаете целесообразным.<br />Если Вы внесете изменения и решите распространять Ваш измененный MODx, Вы должны сохранять и распространять<br />исходный код бесплатно.';
$_lang["setup_database"] = 'Программа установки сейчас попробует установить базу данных:<br />';
$_lang["setup_database_create_connection"] = 'Создание подключения к базе данных: ';
$_lang["setup_database_create_connection_failed"] = 'Не удалось соедениться с базой данных!';
$_lang["setup_database_create_connection_failed_note"] = 'Проверьте параметры подключения и попробуйте снова.';
$_lang["setup_database_selection"] = 'Выбор базы данных `';
$_lang["setup_database_selection_failed"] = 'Ошибка выбора базы данных...';
$_lang["setup_database_selection_failed_note"] = 'База данных не существует. Программа установки попробует ее создать.';
$_lang["setup_database_creation"] = 'Создание базы данных `';
$_lang["setup_database_creation_failed"] = 'Не удалось создать базу данных!';
$_lang["setup_database_creation_failed_note"] = ' - программа установки не смогла создать базу данных!';
$_lang["setup_database_creation_failed_note2"] = 'Программа установки не смогла создать базу данных и нет базы данных с таким именем. Возможно у Вас нет прав на создание базы. Проверьте параметры базы данных и попробуйте еще раз.';
$_lang["setup_database_creating_tables"] = 'Создание таблиц базы данных: ';
$_lang["database_alerts"] = 'Внимание ошибка!';
$_lang["setup_couldnt_install"] = 'Программа установки MODx не смогла установить/изменить некоторые таблицы базы данных.';
$_lang["installation_error_occured"] = 'Следующая ошибка возникла во время установки';
$_lang["during_execution_of_sql"] = ' во время выполнения SQL запроса ';
$_lang["some_tables_not_updated"] = 'Некоторые таблицы не были обновлены. Возможно из-за предыдущих модификаций';
$_lang["installing_demo_site"] = 'Установка примера веб-сайта: ';
$_lang["writing_config_file"] = 'Запись конфигурационного файла: ';
$_lang["cant_write_config_file"] = 'Программа установки не смогла записать файл конфигурации. Скопируйте вышеперечисленное в файл ';
$_lang["cant_write_config_file_note"] = 'Как только Вы это сделаете, Вы можете войти в Панель Управления перейдя в Вашем браузере по адресу Имя_Вашего_Сайта/manager/';
$_lang["unable_install_template"] = 'Невозможно установить шаблон.  Файл';
$_lang["unable_install_chunk"] = 'Невозможно установить чанк.  Файл';
$_lang["unable_install_module"] = 'Невозможно установить модуль.  Файл';
$_lang["unable_install_plugin"] = 'Невозможно установить плагин.  Файл';
$_lang["unable_install_snippet"] = 'Невозможно установить сниппет.  Файл';
$_lang["not_found"] = 'не найден';
$_lang["upgraded"] = 'Обновлен';
$_lang["installed"] = 'Установлен';
$_lang["running_database_updates"] = 'Обновление базы данных: ';
$_lang["installation_successful"] = 'Установка успешно завершена!';
$_lang["to_log_into_content_manager"] = 'Чтобы войти в Панель Управления (manager/index.php) кликните клавишу `Закрыть`.';
$_lang["install"] = 'Установить';
$_lang["remove_install_folder_auto"] = 'Удалить папку и файлы программы установки с моего сайта <br />&nbsp;(Для выполнения этой операции необходимы права на запись в папку install).';
$_lang["remove_install_folder_manual"] = 'Пожалуйста удалите папку &quot;<b>install</b>&quot; прежде чем войти в Панель Управления.';
$_lang["install_results"] = 'Результаты установки';
$_lang["installation_note"] = '<strong>Внимание:</strong> После входа в Панель Управления Вы должны отредактировать и сохранить Системную конфигурацию MODx прежде чем смотреть сайт.<strong>Администрирование</strong> -> Системная конфигурация в Панели Управления.';
$_lang["upgrade_note"] = '<strong>Внимание:</strong> Прежде чем осмотреть Ваш сайт, Вам необходимо войти в Панель Управления, затем просмотреть и сохранить Системную конфигурацию.';
?>