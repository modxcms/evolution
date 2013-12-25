<?php
/**
 * Ditto Snippet - language strings for use in the snippet
 * Filename:       assets/snippets/ditto/lang/russian.inc.php
 * Language:       Russian
 * Encoding:       Windows-1251
 * Translated by:  Russian MODX community, Jaroslav Sidorkin, based on translation by modx.ru
 * Date:           9 May 2010
 * Version:        2.1.0
*/
setlocale (LC_ALL, 'ru_RU.CP1251');
$_lang['language'] = "russian";

$_lang['abbr_lang'] = "ru";

$_lang['file_does_not_exist'] = "�� ����������. ����������, ��������� ����.";

$_lang['extender_does_not_exist'] = "- ������ ���������� �����������. ����������, ��������� ���.";

$_lang['default_template'] = <<<TPL

    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">�����: <strong>[+author+]</strong> �� [+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>

TPL;
$_lang['missing_placeholders_tpl'] = '� ����� �� �������� Ditto (������) ��������� �����, ��������� ��������� ������: <br /><br /><hr /><br /><br />';

$_lang["bad_tpl"] = "<p>&[+tpl+] ��� �� �������� �����-���� �������������, ��� �������� �������� ��������� �����, ������ ���� ��� ������ �����. ����������, ��������� ���.</p>";

$_lang['no_documents'] = '<p>������� �� �������.</p>';

$_lang['resource_array_error'] = '������ ������� ��������';
 
$_lang['prev'] = "&lt; �����";

$_lang['next'] = "����� &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2009";

$_lang['invalid_class'] = "�������� ����� Ditto. ����������, ��������� ���.";

$_lang['none'] = "���";

$_lang['edit'] = "�������������";

$_lang['dateFormat'] = "%d.%b.%y %H:%M";

// Debug Tab Names

$_lang['info'] = "����������";

$_lang['modx'] = "MODX";

$_lang['fields'] = "����";

$_lang['templates'] = "�������";

$_lang['filters'] = "�������";

$_lang['prefetch_data'] = "��������������� ������";

$_lang['retrieved_data'] = "���������� ������";

// Debug Text

$_lang['placeholders'] = "������������";

$_lang['params'] = "���������";

$_lang['basic_info'] = "�������� ����������";

$_lang['document_info'] = "���������� � �������";

$_lang['debug'] = "�������";

$_lang['version'] = "������";

$_lang['summarize'] = "����� ��������� ������� (summarize):";

$_lang['total'] = "����� � ���� ������:";

$_lang['sortBy'] = "����������� �� (sortBy):";

$_lang['sortDir'] = "������� ���������� (sortDir):";

$_lang['start'] = "������ �";
	 
$_lang['stop'] = "��������� ��";

$_lang['ditto_IDs'] = "ID";

$_lang['ditto_IDs_selected'] = "��������� ID";

$_lang['ditto_IDs_all'] = "��� ID";

$_lang['open_dbg_console'] = "������� ������� �������";

$_lang['save_dbg_console'] = "������� ����� �������";

?>