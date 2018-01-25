<?php
/**
 * Title: Language File
 * Purpose: Default Portuguese language file for Ditto
 *
 * Please commit your language changes on Transifex (https://www.transifex.com/projects/p/modx-evolution/) or on GitHub (https://github.com/modxcms/evolution).
 */
$_lang['language'] = "portuguese";
$_lang['abbr_lang'] = "pt";
$_lang['file_does_not_exist'] = "não existe. Verifique o nome do ficheiro.";
$_lang['extender_does_not_exist'] = "extensão não existe. Verifique o nome da extensão.";
$_lang['default_template'] = '
    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">by <strong>[+author+]</strong> on [+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>
';
$_lang["bad_tpl"] = "<p>&[+tpl+] não contém nenhum placeholder ou é um nome de chunk, bloco de código ou ficheiro inválido. Verifique o nome.</p>";
$_lang['missing_placeholders_tpl'] = 'One of your Ditto templates are missing placeholders, please check the template below:';
$_lang['no_documents'] = '<p>Nenhum documento encontrado.</p>';
$_lang['resource_array_error'] = 'Erro de array de Recursos.';
$_lang['prev'] = "&lt; Anterior";
$_lang['next'] = "Próximo &gt;";
$_lang['button_splitter'] = "|";
$_lang['default_copyright'] = "[(site_name)] 2007";
$_lang['invalid_class'] = "A class do Ditto é inválida. Verifique-a.";
$_lang['none'] = "Nenhum";
$_lang['edit'] = "Editar";
$_lang['dateFormat'] = "%d-%b-%y %H:%M";

// Debug Tab Names
$_lang['info'] = "Informações";
$_lang['modx'] = "MODX";
$_lang['fields'] = "Campos";
$_lang['templates'] = "Modelos (Templates)";
$_lang['filters'] = "Filtros";
$_lang['prefetch_data'] = "Antecipar (Prefetch) Dados";
$_lang['retrieved_data'] = "Dados obtidos";

// Debug Text
$_lang['placeholders'] = "Placeholders";
$_lang['params'] = "Parâmetros";
$_lang['basic_info'] = "Informações básicas";
$_lang['document_info'] = "Informações do Documento";
$_lang['debug'] = "Correcção de erros (Debug)";
$_lang['version'] = "Versão";
$_lang['summarize'] = "Sumarizar";
$_lang['total'] = "Total";
$_lang['sortBy'] = "Ordenar por";
$_lang['sortDir'] = "Direcção ordenação";
$_lang['start'] = "Início";
$_lang['stop'] = "Parar";
$_lang['ditto_IDs'] = "IDs";
$_lang['ditto_IDs_selected'] = "IDs Seleccionados";
$_lang['ditto_IDs_all'] = "Todos os IDs";
$_lang['open_dbg_console'] = "Abrir a consola de correcção de rros (Debug)";
$_lang['save_dbg_console'] = "Salvar a consola de correcção de erros (Debug)";
