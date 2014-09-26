<?php
/**
 * Document Manager Module
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: Luciano A. Ferrer, ARES1983
 * Language: Spanish
 * Date: 2014/02/24
 */
// titles
$_lang['DM_module_title'] = 'Administrador de documentos';
$_lang['DM_action_title'] = 'Seleccione una acción';
$_lang['DM_range_title'] = 'Especifique un rango de IDs de los documentos';
$_lang['DM_tree_title'] = 'Seleccione documentos desde el árbol';
$_lang['DM_update_title'] = 'Actualización completada';
$_lang['DM_sort_title'] = 'Editor de índice del menú';

// tabs
$_lang['DM_doc_permissions'] = 'Permisos de documento';
$_lang['DM_template_variables'] = 'Variables del template';
$_lang['DM_sort_menu'] = 'Ordenar items del menú';
$_lang['DM_change_template'] = 'Cambiar template';
$_lang['DM_publish'] = 'Publicar/Despublicar';
$_lang['DM_other'] = 'Otras propiedades';

// buttons
$_lang['DM_close'] = 'Cerrar Doc Manager';
$_lang['DM_cancel'] = 'Volver';
$_lang['DM_go'] = ' Ir';
$_lang['DM_save'] = 'Guardar';
$_lang['DM_sort_another'] = 'Ordenar otro';

// templates tab
$_lang['DM_tpl_desc'] = 'Seleccione el template requerido utilizando la tabla inferior, y especifique los IDs de documentos que necesitan ser cambiados. Especificando un rango de IDs o utilizando el árbol opcional aquí debajo.';
$_lang['DM_tpl_no_templates'] = 'No se han encontrado Templates';
$_lang['DM_tpl_column_id'] = 'ID';
$_lang['DM_tpl_column_name'] = 'Nombre';
$_lang['DM_tpl_column_description'] = 'Descripción';
$_lang['DM_tpl_blank_template'] = 'Template en blanco';
$_lang['DM_tpl_results_message'] = 'Utilice el botón volver si necesita realizar más cambios. El cache del sitio ha sido automáticamente limpiado.';

// template variables tab
$_lang['DM_tv_desc'] = 'Especifique los IDs de documento que necesitan ser cambiados, especificando un rango de IDs o utilizando el árbol opcional aquí debajo, luego seleccione el template requerido desde la tabla y las variables asociadas al mismo serán cargadas. Ingrese los valores de las Variables del Template deseados y guarde los cambios.';
$_lang['DM_tv_template_mismatch'] = 'Este documento no utiliza el template seleccionado.';
$_lang['DM_tv_doc_not_found'] = 'Este documento no fue encontrado en la base de datos.';
$_lang['DM_tv_no_tv'] = 'No se han encontrado Variables del Template en el template.';
$_lang['DM_tv_no_docs'] = 'No se han seleccionado documentos para actualizar.';
$_lang['DM_tv_no_template_selected'] = 'No se ha seleccionado template.';
$_lang['DM_tv_loading'] = 'Se están cargando las Variables del Template...';
$_lang['DM_tv_ignore_tv'] = 'Ignore estas  Variables del Template (valores separados por coma):';
$_lang['DM_tv_ajax_insertbutton'] = 'Insertar';

// document permissions tab
$_lang['DM_doc_desc'] = 'Seleccione el grupo de documento desde la tabla inferior y lo que desee agregar o quitar del mismo. Luego especifique los IDs de los documentos que necesitan ser cambiados. Especificando un rango de IDs o utilizando el árbol opcional aquí debajo.';
$_lang['DM_doc_no_docs'] = 'No se han encontrado grupos de documentos';
$_lang['DM_doc_column_id'] = 'ID';
$_lang['DM_doc_column_name'] = 'Nombre';
$_lang['DM_doc_radio_add'] = 'Agregar un grupo de documento';
$_lang['DM_doc_radio_remove'] = 'Remover un grupo de documento';

$_lang['DM_doc_skip_message1'] = 'El documento con ID';
$_lang['DM_doc_skip_message2'] = 'ya es parte del grupo de documento seleccionado (ignorando)';

// sort menu tab
$_lang['DM_sort_pick_item'] = 'Por favor presione en el raíz del sitio o el documento padre que desea ordenar, de el árbol principal de documentos.';
$_lang['DM_sort_updating'] = 'Actualizando ...';
$_lang['DM_sort_updated'] = 'Actualizado';
$_lang['DM_sort_nochildren'] = 'El padre no contiene ningún hijo';
$_lang['DM_sort_noid'] = 'No se ha seleccionado documento. Regrese y seleccionelo.';

// other tab
$_lang['DM_other_header'] = 'Configuraciones varias del documento';
$_lang['DM_misc_label'] = 'Configuraciones disponibles:';
$_lang['DM_misc_desc'] = 'Seleccione una configuración del menú desplegable y luego la opción requerida. Note que solamente una configuración puede ser cambiada a la vez.';

$_lang['DM_other_dropdown_publish'] = 'Publicar/Despublicar';
$_lang['DM_other_dropdown_show'] = 'Mostrar/Ocultar en menú';
$_lang['DM_other_dropdown_search'] = 'Buscable/No buscable';
$_lang['DM_other_dropdown_cache'] = 'Cacheable/No cacheable';
$_lang['DM_other_dropdown_richtext'] = 'Texto enriquecido/Sin editor de texto enriquecido';
$_lang['DM_other_dropdown_delete'] = 'Borrar/Recuperar';

// radio button text
$_lang['DM_other_publish_radio1'] = 'Publicar';
$_lang['DM_other_publish_radio2'] = 'Despublicar';
$_lang['DM_other_show_radio1'] = 'Ocultar del menú';
$_lang['DM_other_show_radio2'] = 'Mostrar en menú';
$_lang['DM_other_search_radio1'] = 'Buscable';
$_lang['DM_other_search_radio2'] = 'No buscable';
$_lang['DM_other_cache_radio1'] = 'Cacheable';
$_lang['DM_other_cache_radio2'] = 'No cacheable';
$_lang['DM_other_richtext_radio1'] = 'Texto enriquecido';
$_lang['DM_other_richtext_radio2'] = 'Sin texto enriquecido';
$_lang['DM_other_delete_radio1'] = 'Borrar';
$_lang['DM_other_delete_radio2'] = 'Recuperar';

// adjust dates
$_lang['DM_adjust_dates_header'] = 'Ajuste las fechas del documento';
$_lang['DM_adjust_dates_desc'] = 'Cualquiera de los siguientes ajustes de fecha del documento pueden ser cambiados. Utilice la opción "Ver calendario" para cambiar las fechas.';
$_lang['DM_view_calendar'] = 'Ver calendario';
$_lang['DM_clear_date'] = 'Limpiar fecha';

// adjust authors
$_lang['DM_adjust_authors_header'] = 'Elegir autores';
$_lang['DM_adjust_authors_desc'] = 'Utilice las listas desplegables para elegir nuevos autores para el documento.';
$_lang['DM_adjust_authors_createdby'] = 'Creado por:';
$_lang['DM_adjust_authors_editedby'] = 'Editado por:';
$_lang['DM_adjust_authors_noselection'] = 'Sin cambio';

// labels
$_lang['DM_date_pubdate'] = 'Fecha de publicación:';
$_lang['DM_date_unpubdate'] = 'Fecha de despublicación:';
$_lang['DM_date_createdon'] = 'Creado el:';
$_lang['DM_date_editedon'] = 'Editado el:';
$_lang['DM_date_notset'] = ' (no ajustado)';
$_lang['DM_date_dateselect_label'] = 'Seleccione una fecha: ';

// document select section
$_lang['DM_select_submit'] = 'Enviar';
$_lang['DM_select_range'] = 'Cambiar de nuevo los ajustes de rango ID del documento';
$_lang['DM_select_range_text'] = '<p><strong>Llave (donde n es el numero ID del documento):</strong><br /><br />
							  n* - Cambiar el ajuste para este documento y su hijo inmediato<br />
							  n** - Cambiar el ajuste para este documento y TODOS sus hijos<br />
							  n-n2 - Cambiar el ajuste para este rango de documentos<br />
							  n - Cambiar el ajuste para un sólo documento</p>
							  <p>Ejemplo: 1*,4**,2-20,25 - Esto cambiará el ajuste seleccionado para
						      documentos 1 y sus hijos, documento 4 y todos sus hijos, documentos 2
						      a 20 y documento 25.</p>';
$_lang['DM_select_tree'] = 'Vea y seleccione documentos utilizando el árbol de documentos';

// process tree/range messages
$_lang['DM_process_noselection'] = 'No se ha realizado una selección. ';
$_lang['DM_process_novalues'] = 'No se han especificado valores.';
$_lang['DM_process_limits_error'] = 'Límite superior menor que límite inferior:';
$_lang['DM_process_invalid_error'] = 'Valor no válido:';
$_lang['DM_process_update_success'] = 'La actualización se ha completado correctamente, sin errores.';
$_lang['DM_process_update_error'] = 'La actualización se ha completado, pero se han encontrado errores:';
$_lang['DM_process_back'] = 'Volver';

// manager access logging
$_lang['DM_log_template'] = 'Administrador de documentos: Templates cambiadas.';
$_lang['DM_log_templatevariables'] = 'Administrador de documentos: Variables del Template cambiadas.';
$_lang['DM_log_docpermissions'] = 'Administrador de documentos: Permisos de documento cambiados.';
$_lang['DM_log_sortmenu'] = 'Administrador de documentos: Operación de menú índice completada.';
$_lang['DM_log_publish'] = 'Administrador de documentos: Ajustes de Publicación/Despublicación de documentos cambiados.';
$_lang['DM_log_hidemenu'] = 'Administrador de documentos: Ajustes de Ocultar/Mostrar documentos en menú cambiados.';
$_lang['DM_log_search'] = 'Administrador de documentos: Ajustes de Buscable/No buscable de documentos cambiados.';
$_lang['DM_log_cache'] = 'Administrador de documentos: Ajustes de Cacheable/No cacheable de documentos cambiados.';
$_lang['DM_log_richtext'] = 'Administrador de documentos: Ajustes de uso del Editor de Texto Enriquecido de documentos cambiados';
$_lang['DM_log_delete'] = 'Administrador de documentos: Ajustes de Borrar/Recuperar de documentos cambiados.';
$_lang['DM_log_dates'] = 'Administrador de documentos: Ajustes de Fecha de documentos cambiados.';
$_lang['DM_log_authors'] = 'Administrador de documentos: Ajustes de Autor de documentos cambiados.';
?>
