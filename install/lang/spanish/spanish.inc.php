<?php
/**
 * MODx language File
 *
 * @author davaeron
 * @package MODx
 * @version 1.0
 *
 * Filename:       /install/lang/spanish/spanish.inc.php
 * Language:       Spanish
 * Encoding:       iso-8859-1
 */




$_lang['license'] = '<p class="title">Acuerdo de Licencia de MODx.</p>
      <hr style="text-align:left;height:1px;width:90%" />
      <p><em>Aviso: Puedes encontrar una traducción de este acuerdo
      <a href="http://creativecommons.org/licenses/GPL/2.0/deed.es_CL" target=_blank>aquí</a>.
      Fíjate que esto es solamente un resumen del acuerdo completo, cuyo enlace está en el último párafo abajo.
      Al seguir con la instalación estás señalando tu conformidad con el acuerdo completo (el GNU General Public License).</em></p>
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
		guaranteed. It is strongly recommended you read the <a href="http://www.gnu.org/copyleft/gpl.html" target=_blank>GNU General Public
		License</a> in full before proceeding, which can also be found in the license
		file distributed with this package.</p>';
$_lang["encoding"] = 'iso-8859-1';	//charset encoding for html header
$_lang["modx_install"] = 'MODx &raquo; Instalar';
$_lang["loading"] = 'Cargando...';
$_lang["Begin"] = 'Iniciar';
$_lang["status_connecting"] = ' Conexión al servidor: ';
$_lang["status_failed"] = '¡Falló!';
$_lang["status_passed"] = 'OK';
$_lang["status_checking_database"] = '...    Probando la base de datos: ';
$_lang["status_failed_could_not_select_database"] = '¡Falló! - Error al seleccionar la base de datos';
$_lang["status_failed_table_prefix_already_in_use"] = 'falló - El prefijo de tabla ya existe';
$_lang["welcome_message_welcome"] = 'Bienvenid@ al programa de instalación de MODx.';
$_lang["welcome_message_text"] = 'Este asistente te guiará en todo el proceso de la instalación.';
$_lang["welcome_message_select_begin_button"] = 'Para empezar, favor de dar clic en `Iniciar`:';
$_lang["installation_mode"] = 'Modo de Instalación';
$_lang["installation_new_installation"] = 'Instalación Nueva';
$_lang["installation_install_new_copy"] = 'Instalar una copia nueva de ';
$_lang["installation_install_new_note"] = 'Ten en cuenta que esta opción puede sobreescribir los datos existentes en tu base de datos.';
$_lang["installation_upgrade_existing"] = 'Actualizar un Instalación Existente';
$_lang["installation_upgrade_existing_note"] = 'Actualizar los archivos y la base de datos de una instalación ya existente.';
$_lang["installation_upgrade_advanced"] = 'Actualización Avanzada<br /><small>(editar configuración de la base de datos)</small>';
$_lang["installation_upgrade_advanced_note"] = 'Para cambiar la confuguración de la base de datos, por ejemplo cuando se está migrando de un servidor a otro. <b>Necesitarás el nombre completo de tu base de datos, su usuario, contraseña, y detalles de su conexión y colación.</b>';
$_lang["connection_screen_connection_information"] = 'Datos de Conexión';
$_lang["connection_screen_connection_and_login_information"] = 'Datos de la conexión y usario/contraseña';
$_lang["connection_screen_connection_note"] = 'Escribe aquí el nombre de la base de datos que se creó para MODx. Si aún no existe una base de datos, el instalador intentará crear una. Puede que no funcione si las confuguraciones y permisos de su servidor/dominio no lo permita (y entonces tendrás que crear la base de datos manualmente antes de seguir).';
$_lang["connection_screen_database_name"] = 'Nombre de la base de datos:';
$_lang["connection_screen_table_prefix"] = 'Prefijo de las tablas:';
$_lang["connection_screen_collation"] = 'Colación:';
$_lang["connection_screen_character_set"] = 'Conjunto de carácteres:';
$_lang["connection_screen_database_info"] = 'Ahora favor de escribir los parámetros de conexión a tu base de datos.';
$_lang["connection_screen_database_host"] = 'Servidor (host):';
$_lang["connection_screen_database_login"] = 'Nombre de usuario:';
$_lang["connection_screen_database_pass"] = 'Contraseña:';
$_lang["connection_screen_test_connection"] = 'Probar la conexión';
$_lang["connection_screen_default_admin_user"] = 'Usuario de Administrador Predeterminado';
$_lang["connection_screen_default_admin_note"] = 'Escribe aquí los datos que se usarán para la cuenta predeterminada del administrador de MODx. Puedes escribir tu propio nombre y una contraseña que no se te va a olvidar. Una vez que se termina la instalación, necesitarás estos datos para administrar el sistema.';
$_lang["connection_screen_default_admin_login"] = 'Nombre de usuario:';
$_lang["connection_screen_default_admin_email"] = 'Correo electrónico:';
$_lang["connection_screen_default_admin_password"] = 'Contraseña:';
$_lang["connection_screen_default_admin_password_confirm"] = 'Confirmar contraseña:';
$_lang["optional_items"] = 'Opciones adicionales';
$_lang["optional_items_note"] = 'Escoge las opciones adicionales que quieres y da clic en Instalar abajo:';
$_lang["sample_web_site"] = 'Páginas de Muestra';
$_lang["install_overwrite"] = 'Instalar/Sobreescribir las';
$_lang["sample_web_site_note"] = 'Aviso: Al escoger esta opción se va a <b style=\"color:#CC0000\">sobreescribir</b> los documentos y recursos existentes.';
$_lang["checkbox_select_options"] = 'Opciones para seleccionar:';
$_lang["all"] = 'Todos';
$_lang["none"] = 'Ninguno';
$_lang["toggle"] = 'Invertir';
$_lang["templates"] = 'Plantillas';
$_lang["install_update"] = 'Instalar/Actualizar';
$_lang["chunks"] = 'Chunks';
$_lang["modules"] = 'Módulos';
$_lang["plugins"] = 'Plugins';
$_lang["snippets"] = 'Snippets';
$_lang["preinstall_validation"] = 'Revisión previa a la instalación';
$_lang["summary_setup_check"] = 'El asistente ha revisado varios elementos para averiguar que todo está en orden para seguir.';
$_lang["checking_php_version"] = "Revisando versión de PHP: ";
$_lang["failed"] = '¡Falló!';
$_lang["ok"] = 'OK';
$_lang["you_running_php"] = ' - Tu serivdor tiene versión ';
$_lang["modx_requires_php"] = ' de PHP, mientras MODx requiere por lo menos la versión 4.1.0';
$_lang["php_security_notice"] = '<legend>Aviso de Seguridad</legend><p>Aunque MODx puede funcionar con esta versión de PHP, no lo recomendamos. Esta versión de PHP tiene varias vulnerabilidades y problemas de seguridad. Debes de actualizar PHP a la versión 4.3.8 o más nueva para tu seguridad. Esto se debe de hacer aunque no se instala MODx porque implica una falta de seguridad para todo el servidor.</p>';
$_lang["checking_sessions"] = 'Revisando la configuración de las sesiones: ';
$_lang["checking_if_cache_exist"] = 'Comprobando que existe el directorio <span class=\"mono\">assets/cache</span>: ';
$_lang["checking_if_cache_writable"] = 'Comprobando que el directorio <span class=\"mono\">assets/cache</span> tiene permisos de escritura: ';
$_lang["checking_if_cache_file_writable"] = 'Comprobando que <span class=\"mono\">assets/cache/siteCache.idx.php</span> tiene permisos de escritura: ';
$_lang["checking_if_cache_file2_writable"] = 'Comprobando que <span class=\"mono\">assets/cache/sitePublishing.idx.php</span> tiene permisos de escritura: ';
$_lang["checking_if_images_exist"] = 'Comprobando que existe el directorio <span class=\"mono\">assets/images</span>: ';
$_lang["checking_if_images_writable"] = 'Comprobando que <span class=\"mono\">assets/images</span> tiene permisos de escritura: ';
$_lang["checking_if_export_exists"] = 'Comprobando que existe el directorio <span class=\"mono\">assets/export</span>: ';
$_lang["checking_if_export_writable"] = 'Comprobando que <span class=\"mono\">assets/export</span> tiene permisos de escritura: ';
$_lang["checking_if_config_exist_and_writable"] = 'Comprobando que existe el archivo <span class=\"mono\">manager/includes/config.inc.php</span> y que tiene permisos de escritura: ';
$_lang["config_permissions_note"] = 'Para instalaciones nuevas en servidores Linux/Unix, hay que crear un archivo nuevo en blanco llamado <span class=\"mono\">config.inc.php</span> en el directorio <span class=\"mono\">manager/includes/</span> con los permisos 0666.';
$_lang["creating_database_connection"] = 'Conectándose a la base de datos: ';
$_lang["database_connection_failed"] = '¡Falló la conexión a la base de datos!';
$_lang["database_connection_failed_note"] = 'Favor de revisar los parámetros de conexión a tu base de datos y volver a intentar.';
$_lang["database_use_failed"] = '¡No se pudo seleccionar la base de datos!';
$_lang["database_use_failed_note"] = 'Favor de revisar los permisos que tiene el usuario que proporcionaste  y volver a intentar.';
$_lang["checking_table_prefix"] = 'Comprobando el prefijo de las tablas `';
$_lang["table_prefix_already_inuse"] = ' - ¡Este prefijo ya se está usando en esta base de datos!';
$_lang["table_prefix_already_inuse_note"] = 'El asistente no puede seguir porque ya existen tablas con el mismo prefijo en esta base de datos. Favor de escoger otro prefijo y volver a intentar.';
$_lang["table_prefix_not_exist"] = ' - ¡Este prefijo no existe en esta base de datos!';
$_lang["table_prefix_not_exist_note"] = 'El asistente no puede seguir porque no existen las tablas con el prefijo que especificaste en esta base de datos. Favor de proporcionar el prefijo correcto de las tablas que quieres actualizar y volver a intentar.';
$_lang["setup_cannot_continue"] = 'Lo sentimos. El asistente no puede seguir debido ';
$_lang["error"] = 'al error indicado.';
$_lang["errors"] = 'a los errores indicados.'; //Plural form
$_lang["please_correct_error"] = ' Favor de corregirlo ';
$_lang["please_correct_errors"] = ' Favor de corregirlos '; //Plural form
$_lang["and_try_again"] = 'y volver a intentar. Si tienes dudas o necesitas ayuda';
$_lang["and_try_again_plural"] = 'y volver a intentar. Si tienes dudas o necesitas ayuda'; //Plural form
$_lang["visit_forum"] = 'visita <a href="http://modxcms.com/forums/index.php/board,121.0.html" target="_blank">los Foros de MODx</a>.';
$_lang["testing_connection"] = 'Probando la conexión...';
$_lang["btnback_value"] = 'Anterior';
$_lang["btnnext_value"] = 'Siguiente';
$_lang["retry"] = 'Volver a Intentar';
$_lang["alert_enter_database_name"] = '¡Hay que proporcionar el nombre de la base de datos!';
$_lang["alert_table_prefixes"] = '¡El prefijo tiene que empezar con una letra!';
$_lang["alert_enter_host"] = '¡Hay que proporcionar el servidor (host) de la base de datos!';
$_lang["alert_enter_login"] = '¡Hay que proporcionar el nombre de usuario (login name) de la base de datos!';
$_lang["alert_enter_adminlogin"] = '¡Hay que proporcionar el nombre de usuario para el administrador predeterminado!';
$_lang["alert_enter_adminpassword"] = '¡Hay que proporcionar una contraseña para el administrador predeterminado!';
$_lang["alert_enter_adminconfirm"] = '¡Las dos contraseñas para el administrador predeterminado no coinciden!';
$_lang["iagree_box"] = 'Estoy de acuerdo con los términos de la licencia.';
$_lang["btnclose_value"] = 'Cerrar';
$_lang["running_setup_script"] = 'Instalación en curso... Favor de esperar.';
$_lang["modx_footer1"] = '&copy; 2005-2007 del proyecto de <a href="http://www.modxcms.com/" target="_blank" style="color: green; text-decoration:underline">MODx</a>. Todos los derechos reservados. MODx tiene licencia bajo el GPL de GNU.';
$_lang["modx_footer2"] = 'MODx es software gratis.  Te invitamos a ser ceativo y usarlo de cualquier manera que se te antoje. Sólo asegura que si haces cambios y decides distribuir una versión modificada de MODx que el código fuente siga siendo gratis.';
$_lang["setup_database"] = 'El asistente intentará confugurar la base de datos:<br />';
$_lang["setup_database_create_connection"] = 'Conectándose a la base de datos: ';
$_lang["setup_database_create_connection_failed"] = '¡No se pudo conectar!';
$_lang["setup_database_create_connection_failed_note"] = 'Favor de revisar los parámetros de conexión a la base de datos y volver a intentar.';
$_lang["setup_database_selection"] = 'Seleccionando la base de datos: `';
$_lang["setup_database_selection_failed"] = '¡No se pudo seleccionar la base de datos!';
$_lang["setup_database_selection_failed_note"] = 'No existe la base de datos. El asistente intentará crearla.';
$_lang["setup_database_creation"] = 'Creando la base de datos: `';
$_lang["setup_database_creation_failed"] = '¡No se pudo crear la base de datos!';
$_lang["setup_database_creation_failed_note"] = ' - ¡El asistente no pudo crear la base de datos!';
$_lang["setup_database_creation_failed_note2"] = 'El asistente no pudo crear la base de datos, y no se encontró una base de datos existentes con el nombre proporcionado. Es probable que la seguridad de su servidor no permite que programas externas crean a bases de datos. Hay que crear la base de datos según el procedimiento correcto para tu servidor y volver a intentar.';
$_lang["setup_database_creating_tables"] = 'Creando tablas de la base de datos: ';
$_lang["database_alerts"] = '¡Avisos de la base de datos!';
$_lang["setup_couldnt_install"] = 'El asistente no pudo instalar/modificar algunas tablas de la base de datos seleccionada.';
$_lang["installation_error_occured"] = 'Se registraron los siguientes errores';
$_lang["during_execution_of_sql"] = ' durante la ejecución del comando SQL ';
$_lang["some_tables_not_updated"] = 'Algunas tablas no se actualizaron (quizás debido a modificaciones previas).';
$_lang["installing_demo_site"] = 'Instalando las páginas de muestra: ';
$_lang["writing_config_file"] = 'Escribiendo el archivo de configuración: ';
$_lang["cant_write_config_file"] = 'MODx no pudo escribir al archivo de configuración. Será necesario copiar lo siguiente y insertarlo en el archivo manualmente. ';
$_lang["cant_write_config_file_note"] = 'Una vez que se ha hecho esto, puedes entrar al sistema administrativo de MODx en YourSiteName.com/manager/.';
$_lang["unable_install_template"] = 'No se pudo instalar la plantilla. Archivo ';
$_lang["unable_install_chunk"] = 'No se pudo instalar el chunk. Archivo ';
$_lang["unable_install_module"] = 'No se pudo instalar el módulo. Archivo ';
$_lang["unable_install_plugin"] = 'No se pudo instalar el plugin. Archivo ';
$_lang["unable_install_snippet"] = 'No se pudo instalar el snippet. Archivo ';
$_lang["not_found"] = 'no encontrado';
$_lang["upgraded"] = 'Actualizado';
$_lang["installed"] = 'Instalado';
$_lang["running_database_updates"] = 'Actualizando la base de datos: ';
$_lang["installation_successful"] = '¡La instalación se terminó éxitosamente!';
$_lang["to_log_into_content_manager"] = 'Puedes entrar al sistema de administración de MODx (manager/index.php) dando clic en `Cerrar`.';
$_lang["install"] = 'Instalar';
$_lang["remove_install_folder_auto"] = 'Borrar la carpeta &quot;<b>install</b>&quot; y sus archivos del servidor <br />&nbsp;(requiere los permisos correctos para borrar la carpeta).';
$_lang["remove_install_folder_manual"] = 'No olvides de borrar la carpeta  &quot;<b>install</b>&quot; antes de entrar al sistema de administración.';
$_lang["install_results"] = 'Resultados de la instalación';
$_lang["installation_note"] = '<strong>Aviso:</strong> Después de entrar al sistema de administración de MODx, debes de editar y guardar las configuraciones del sistema que se encuentran en &quot;<strong>Administration</strong> -> System Configuration&quot;.';
$_lang["upgrade_note"] = '<strong>Aviso:</strong> Ahora debes de entrar al sistema de administración de MODx y volver a guardar las configuraciones del sistema que se encuentran en &quot;<strong>Administration</strong> -> System Configuration&quot;.';
?>
