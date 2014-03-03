<?php
/** 
 * ddSetFieldValue
 * @version 1.0.5 (2013-10-16)
 * 
 * Widget for ManagerManager plugin allowing ducument fields values (or TV fields values) to be strongly defined (reminds of mm_default but field value assignment is permanent).
 * 
 * @uses ManagerManager plugin 0.4.
 * 
 * @param field {string} - Field or TV name for which value setting is required. @required
 * @param value {string} - Required value. Default: ''.
 * @param roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param templates {comma separated string} - Id of the templates to which this widget is applied. Default: ''.
 * 
 * @link http://code.divandesign.biz/modx/mm_ddsetfieldvalue/1.0.5
 * 
 * @copyright 2013, DivanDesign
 * http://www.DivanDesign.biz
 */

function mm_ddSetFieldValue($field, $value = '', $roles = '', $templates = ''){
	global $modx, $mm_current_page;
	$e = &$modx->Event;
	
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		
		$output = " // ----------- mm_ddSetFieldValue :: Begin -------------- \n";
		
		//Подбираем правильный формат даты в соответствии с конфигурацией
		switch($modx->config['datetime_format']){
			case 'dd-mm-YYYY':
				$date_format = 'd-m-Y';
			break;
			case 'mm/dd/YYYY':
				$date_format = 'm/d/Y';
			break;
			case 'YYYY/mm/dd':
				$date_format = 'Y/m/d';
			break;
		}
		
		//Смотрим, что за поле надо изменить
		switch ($field){
			//Дата публикации
			case 'pub_date':
				$value = ($value == '') ? date("$date_format H:i:s") : $value;
				$output .= '$j("input[name=pub_date]").val("'.jsSafe($value).'"); '."\n";
			break;
			
			//Дата отмены публикации
			case 'unpub_date':
				$value = ($value=='') ? date("$date_format H:i:s") : $value;
				$output .= '$j("input[name=unpub_date]").val("'.jsSafe($value).'"); '."\n";
			break;
			
			//Признак публикации
			case 'published':
				if ($value == '1'){
					$output .= '$j("input[name=publishedcheck]").attr("checked", "checked"); '."\n";
				}else{
					$value = '0';
					$output .= '$j("input[name=publishedcheck]").removeAttr("checked"); '."\n";
				}
				
				$output .= '$j("input[name=published]").val("'.$value.'"); '."\n";
			break;
			
			//Признак отображения в меню
			case 'show_in_menu':
				if ($value == '1'){
					$output .= '$j("input[name=hidemenucheck]").attr("checked", "checked"); '."\n";
				}else{
					$value = '0';
					$output .= '$j("input[name=hidemenucheck]").removeAttr("checked"); '."\n";
				}
								
				$output .= '$j("input[name=hidemenu]").val("'.(($value == '1') ? '0' : '1').'"); '."\n"; // Note these are reversed from what you'd think
			break;
			
			//Признак скрытия из меню (аналогично show_in_menu, только наоборот)
			case 'hide_menu':
				if ($value == '0'){
					$output .= '$j("input[name=hidemenucheck]").attr("checked", "checked"); '."\n";
				}else{
					$value = '1';
					$output .= '$j("input[name=hidemenucheck]").removeAttr("checked"); '."\n";
				}
				
				$output .= '$j("input[name=hidemenu]").val("'.$value.'"); '."\n";
			break;
					
			//Признак доступности для поиска
			case 'searchable':
				if ($value == '1'){
					$output .= '$j("input[name=searchablecheck]").attr("checked", "checked"); '."\n";
				}else{
					$value = '0';
					$output .= '$j("input[name=searchablecheck]").removeAttr("checked"); '."\n";
				}
				
				$output .= '$j("input[name=searchable]").val("'.$value.'"); '."\n";
			break;
			
			//Признак кэширования
			case 'cacheable':
				if ($value == '1'){
					$output .= '$j("input[name=cacheablecheck]").attr("checked", "checked"); '."\n";
				}else{
					$value = '0';
					$output .= '$j("input[name=cacheablecheck]").removeAttr("checked"); '."\n";
				}
				
				$output .= '$j("input[name=cacheable]").val("'.$value.'"); '."\n";
			break;
			
			//Признак очистки кэша
			case 'clear_cache':
				if ($value == '1'){
					$output .= '$j("input[name=syncsitecheck]").attr("checked", "checked"); '."\n";
				}else{
					$value = '0';
					$output .= '$j("input[name=syncsitecheck]").removeAttr("checked"); '."\n";
				}

				$output .= '$j("input[name=syncsite]").val("'.$value.'"); '."\n";
			break;
			
			//Признак папки
			case 'is_folder':
				if ($value == '1'){
					$output .= '$j("input[name=isfoldercheck]").attr("checked", "checked"); '."\n";
				}else{
					$value = '0';
					$output .= '$j("input[name=isfoldercheck]").removeAttr("checked"); '."\n";
				}
			break;
			
			//Участвует в URL
			case 'alias_visible':
				if ($value == '1'){
					$output .= '$j("input[name=alias_visible_check]").attr("checked", "checked"); '."\n";
				}else{
					$value = '0';
					$output .= '$j("input[name=alias_visible_check]").removeAttr("checked"); '."\n";
				}
				
				$output .= '$j("input[name=alias_visible]").val("'.$value.'"); '."\n";
			break;
			
			//Признак использованшия визуального редактора
			case 'is_richtext':
				$output .= 'var originalRichtextValue = $j("#which_editor:first").val(); '."\n";
				
				if ($value == '1'){
					$output .= '$j("input[name=richtextcheck]").attr("checked", "checked"); '."\n";
				}else{
					$value = '0';
					$output .= '
								$j("input[name=richtextcheck]").removeAttr("checked");
								// Make the RTE displayed match the default value that has been set here
								if (originalRichtextValue != "none"){
									$j("#which_editor").val("none");
									changeRTE();
								}
										
								';
					$output .= ''."\n";
				}

				$output .= '$j("input[name=richtext]").val("'.$value.'"); '."\n";
			break;
			
			//Признак логирования
			case 'log':
				//Note these are reversed from what you'd think
				$value = ($value) ? '0' : '1';
				
				if ($value == '1'){
					$output .= '$j("input[name=donthitcheck]").attr("checked", "checked"); '."\n";
				}else{
					$output .= '$j("input[name=donthitcheck]").removeAttr("checked"); '."\n";
				}
				
				$output .= '$j("input[name=donthit]").val("'.$value.'"); '."\n";
			break;
			
			//Тип содержимого
			case 'content_type':
				$output .= '$j("select[name=contentType]").val("'.$value.'");' . "\n";
			break;
			
			//Аттрибуты ссылки
			case 'link_attributes':
				//Обработаем кавычки
				$value = str_replace(array("'", '"'), '\"', $value);
				$output .= '$j("input[name=link_attributes]").val("'.$value.'"); '."\n";
			break;
			
			//TV
			default:
				$tvsMas = tplUseTvs($mm_current_page['template'], $field);
				
				if ($tvsMas){
					$output .= '$j("#tv'.$tvsMas[0]['id'].'").val("'.$value.'");' . "\n";
				}
			break;
		}
		
		$output .= "\n// ---------------- mm_ddSetFieldValue :: End -------------";
		
		$e->output($output . "\n");
	}
}
?>