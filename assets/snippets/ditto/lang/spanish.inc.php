<?php
/**
 * Title: Language File
 * Purpose: Default Spanish language file for Ditto
 * Author: Traducido por Luciano A. Ferrer y ARES1983,
 *
 * Please commit your language changes on Transifex (https://www.transifex.com/projects/p/modx-evolution/) or on GitHub (https://github.com/modxcms/evolution).
 */
$_lang['language'] = "spanish";
$_lang['abbr_lang'] = "es";
$_lang['file_does_not_exist'] = "no existe. Compruebe el archivo.";
$_lang['extender_does_not_exist'] = "el suplemento no existe. Comprobarlo por favor";
$_lang['default_template'] = '
    <div class="ditto_item" id="ditto_item_[+id+]">
        <h3 class="ditto_pageTitle"><a href="[~[+id+]~]">[+pagetitle+]</a></h3>
        <div class="ditto_documentInfo">por <strong>[+author+]</strong> el [+date+]</div>
        <div class="ditto_introText">[+introtext+]</div>
    </div>
';
$_lang["bad_tpl"] = "<p>&[+tpl+] no contiene ninguna placeholders, no es el nombre del chunk, un bloque del código o es un nombre de fichero inválido. Comprobarlo por favor.</p>";
$_lang['missing_placeholders_tpl'] = 'Falta "placeholders" en una de las plantillas de Ditto, por favor revise la siguiente plantilla:';
$_lang['no_documents'] = '<p>No se encontró ningún documento.</p>';
$_lang['resource_array_error'] = 'Error del recurso Array';
$_lang['prev'] = "&lt; Anterior";
$_lang['next'] = "Siguiente &gt;";
$_lang['button_splitter'] = "|";
$_lang['default_copyright'] = "[(site_name)] 2007";
$_lang['invalid_class'] = "La clase(class) del DITTO es inválida. Comprobarla por favor.";
$_lang['none'] = "Ninguno";
$_lang['edit'] = "Editar";
$_lang['dateFormat'] = "%d-%b-%y %H:%M";

// Debug Tab Names
$_lang['info'] = "Info";
$_lang['modx'] = "MODX";
$_lang['fields'] = "Campos";
$_lang['templates'] = "Plantillas";
$_lang['filters'] = "Filtros";
$_lang['prefetch_data'] = "Datos de precarga";
$_lang['retrieved_data'] = "Datos de Retreived";

// Debug Text
$_lang['placeholders'] = "Placeholders";
$_lang['params'] = "Parámetros";
$_lang['basic_info'] = "Info Básica";
$_lang['document_info'] = "Info del documento";
$_lang['debug'] = "Debug";
$_lang['version'] = "Versión";
$_lang['summarize'] = "Resumen";
$_lang['total'] = "Total";
$_lang['sortBy'] = "Ordenar Por";
$_lang['sortDir'] = "Ordenar Dirección";
$_lang['start'] = "Empezar";
$_lang['stop'] = "Parar";
$_lang['ditto_IDs'] = "IDs";
$_lang['ditto_IDs_selected'] = "Seleccionar IDs";
$_lang['ditto_IDs_all'] = "Todos los IDs";
$_lang['open_dbg_console'] = "Abrir Consola de Eliminar errores(Debug)";
$_lang['save_dbg_console'] = "Guardar Consola de Eliminar errores(Debug)";
