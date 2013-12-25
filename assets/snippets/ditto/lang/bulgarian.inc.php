<?php

/*
 * Title: Language File
 * Purpose:
 *  	Default Bulgarian language file for Ditto
 *
 * Note:
 * 		New language keys should added at the bottom of this page
 *
 * Author: INFORMATOR Team /www.informator.org/
*/

$_lang['language'] = "bulgarian";

$_lang['abbr_lang'] = "bg";

$_lang['file_does_not_exist'] = "�� ����������. ����, ��������� �����.";

$_lang['extender_does_not_exist'] = "���������� �� ����������. ����, ���������.";

$_lang['default_template'] = <<<TPL

    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">�� <strong>[+author+]</strong> �� [+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>

TPL;

$_lang["bad_tpl"] = "<p>&[+tpl+] ��� �� ������� ������� placeholders, ��� � ����� �� chunk-� ��� ����� � ������. ����, ���������.</p>";

$_lang['no_documents'] = '<p>�� �� �������� ���������.</p>';

$_lang['resource_array_error'] = '������ � ���������� �� ���������';

$_lang['prev'] = "&lt; ��������";

$_lang['next'] = "������� &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2006";

$_lang['invalid_class'] = "������ � Ditto �����. ����, ���������.";

$_lang['none'] = "����";

$_lang['edit'] = "�����������";

$_lang['dateFormat'] = "%d-%b-%y %H:%M";

// Debug Tab Names

$_lang['info'] = "����";

$_lang['modx'] = "MODX";

$_lang['fields'] = "������";

$_lang['templates'] = "�������";

$_lang['filters'] = "������";

$_lang['prefetch_data'] = "������������� �����";

$_lang['retrieved_data'] = "��������� �����";

// Debug Text

$_lang['placeholders'] = "Placeholders";

$_lang['params'] = "���������";

$_lang['basic_info'] = "������� ����������";

$_lang['document_info'] = "���������� �� ��������";

$_lang['debug'] = "Debug";

$_lang['version'] = "������";

$_lang['summarize'] = "����������";

$_lang['total'] = "������";

$_lang['sortBy'] = "��������� ��";

$_lang['sortDir'] = "������ �� ���������";

$_lang['start'] = "������";

$_lang['stop'] = "����";

$_lang['ditto_IDs'] = "IDs";

$_lang['ditto_IDs_selected'] = "������� IDs";

$_lang['ditto_IDs_all'] = "������ IDs";

$_lang['open_dbg_console'] = "�������� �� Debug �������";

$_lang['save_dbg_console'] = "����������� �� Debug �������";

?>