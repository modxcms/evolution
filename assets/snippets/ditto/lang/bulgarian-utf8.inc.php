<?php
/**
 * Title: Language File
 * Purpose: Default Bulgarian language file for Ditto
 *
 * Author: INFORMATOR Team /www.informator.org/
 *
 * Please commit your language changes on Transifex (https://www.transifex.com/projects/p/modx-evolution/) or on GitHub (https://github.com/modxcms/evolution).
 */
$_lang['language'] = "bulgarian";
$_lang['abbr_lang'] = "bg";
$_lang['file_does_not_exist'] = "не съществува. Моля, проверете файла.";
$_lang['extender_does_not_exist'] = "екстендера не съществува. Моля, проверете.";
$_lang['default_template'] = '
    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">от <strong>[+author+]</strong> на [+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>
';
$_lang["bad_tpl"] = "<p>&[+tpl+] или не съдържа никакви placeholders, или е името на chunk-а или файла е грешно. Моля, проверете.</p>";
$_lang['missing_placeholders_tpl'] = 'One of your Ditto templates are missing placeholders, please check the template below:';
$_lang['no_documents'] = '<p>Не са намерени документи.</p>';
$_lang['resource_array_error'] = 'Грешка в подредбата на ресурсите';
$_lang['prev'] = "&lt; Предишен";
$_lang['next'] = "Следващ &gt;";
$_lang['button_splitter'] = "|";
$_lang['default_copyright'] = "[(site_name)] 2006";
$_lang['invalid_class'] = "Грешка в Ditto класа. Моля, проверете.";
$_lang['none'] = "Няма";
$_lang['edit'] = "Редактиране";
$_lang['dateFormat'] = "%d-%b-%y %H:%M";

// Debug Tab Names
$_lang['info'] = "Инфо";
$_lang['modx'] = "MODX";
$_lang['fields'] = "Полета";
$_lang['templates'] = "Шаблони";
$_lang['filters'] = "Филтри";
$_lang['prefetch_data'] = "Предварителни Данни";
$_lang['retrieved_data'] = "Извлечени Данни";

// Debug Text
$_lang['placeholders'] = "Placeholders";
$_lang['params'] = "Параметри";
$_lang['basic_info'] = "Основна информация";
$_lang['document_info'] = "Информация за документ";
$_lang['debug'] = "Debug";
$_lang['version'] = "Версия";
$_lang['summarize'] = "Обобщаване";
$_lang['total'] = "Всичко";
$_lang['sortBy'] = "Сортиране по";
$_lang['sortDir'] = "Посока на сортиране";
$_lang['start'] = "Начало";
$_lang['stop'] = "Край";
$_lang['ditto_IDs'] = "IDs";
$_lang['ditto_IDs_selected'] = "Избрани IDs";
$_lang['ditto_IDs_all'] = "Всички IDs";
$_lang['open_dbg_console'] = "Отваряне на Debug конзола";
$_lang['save_dbg_console'] = "Съхраняване на Debug конзола";
