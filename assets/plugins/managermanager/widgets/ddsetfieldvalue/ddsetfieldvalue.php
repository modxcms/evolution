<?php
/** 
 * ddSetFieldValue
 * @version 1.1 (2014-03-27)
 * 
 * Widget for ManagerManager plugin allowing ducument fields values (or TV fields values) to be strongly defined (reminds of mm_default but field value assignment is permanent).
 * 
 * @uses ManagerManager plugin 0.6.1.
 * 
 * @param $fields {comma separated string} - The name(s) of the document fields (or TVs) for which value setting is required. @required
 * @param $value {string} - Required value. Default: ''.
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Id of the templates to which this widget is applied. Default: ''.
 * 
 * @link http://code.divandesign.biz/modx/mm_ddsetfieldvalue/1.1
 * 
 * @copyright 2014, DivanDesign
 * http://www.DivanDesign.biz
 */

function mm_ddSetFieldValue($fields, $value = '', $roles = '', $templates = ''){
	global $modx;
	$e = &$modx->Event;
	
	if ($e->name == 'OnDocFormRender' && useThisRule($roles, $templates)){
		global $mm_current_page, $mm_fields;
		
		$output = "//---------- mm_ddSetFieldValue :: Begin -----\n";
		
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
		
		$fields = getTplMatchedFields($fields);
		if ($fields == false){return;}
		
		foreach ($fields as $field){
			//Результирующее значение для выставления через $.fn.val
			$setValue = $value;
			//Значение для чекбоксов
			$checkValue = (bool)$value;
			
			//Селектор для выставления через $.fn.val
			$setSelector = $mm_fields[$field]['fieldtype'].'[name=\''.$mm_fields[$field]['fieldname'].'\']';
			//Селектор для чекбоксов
			$checkSelector = false;
			
			//Некоторые поля документа требуют дополнительной обработки
			switch ($field){
				//Дата публикации
				case 'pub_date':
				//Дата отмены публикации
				case 'unpub_date':
					$setValue = ($setValue == '') ? jsSafe(date("$date_format H:i:s")) : jsSafe($setValue);
				break;
				
				//Аттрибуты ссылки
				case 'link_attributes':
					//Обработаем кавычки
					$setValue = str_replace(array("'", '"'), '\"', $setValue);
				break;
				
				//Признак папки
				case 'is_folder':
					$checkSelector = $setSelector;
					$setSelector = false;
				break;
				
				//Чекбоксы с прямой логикой
				//Признак публикации
				case 'published':
				//Признак доступности для поиска
				case 'searchable':
				//Признак кэширования
				case 'cacheable':
				//Признак очистки кэша
				case 'clear_cache':
				//Участвует в URL
				case 'alias_visible':
					//Если не 1, значит 0, другого не быть не может
					if ($setValue != '1'){
						$setValue = '0';
					}
					
					$checkSelector = $setSelector;
					
					//Не очень красиво if внутри case, ровно так же, как и 'clear_cache' == 'syncsite', что поделать
					if ($field == 'clear_cache'){
						$setSelector = 'input[name=\'syncsite\']';
					}else{
						$setSelector = 'input[name=\''.$field.'\']';
					}
				break;
				
				//Признак отображения в меню
				case 'show_in_menu':
					// Note these are reversed from what you'd think
					$setValue = ($setValue == '1') ? '0' : '1';
					
					$checkSelector = $setSelector;
					$setSelector = 'input[name=\'hidemenu\']';
				break;
				
				//Признак скрытия из меню (аналогично show_in_menu, только наоборот)
				case 'hide_menu':
					if ($setValue != '0'){
						$setValue = '1';
					}
					
					$checkValue = !$checkValue;
					
					$checkSelector = $setSelector;
					$setSelector = 'input[name=\'hidemenu\']';
				break;
				
				//Признак использованшия визуального редактора
				case 'is_richtext':
					$output .= 'var originalRichtextValue = $j("#which_editor:first").val();'."\n";
					
					if ($setValue != '1'){
						$setValue = '0';
						$output .= '
							// Make the RTE displayed match the default value that has been set here
							if (originalRichtextValue != "none"){
								$j("#which_editor").val("none");
								changeRTE();
							}
						';
						$output .= "\n";
					}
					
					$checkSelector = $setSelector;
					$setSelector = 'input[name=\'richtext\']';
				break;
				
				//Признак логирования
				case 'log':
					//Note these are reversed from what you'd think
					$setValue = ($setValue == '1') ? '0' : '1';
					$checkValue = !$checkValue;
					
					$checkSelector = $setSelector;
					$setSelector = 'input[name=\'donthit\']';
				break;
			}
			
			//Если это чекбокс
			if ($checkSelector !== false){
				if ($checkValue){
					$output .= '$j("'.$checkSelector.'").attr("checked", "checked");'."\n";
				}else{
					$output .= '$j("'.$checkSelector.'").removeAttr("checked");'."\n";
				}
			}
			
			//Если нужно задавать значение
			if ($setSelector !== false){
				$output .= '$j("'.$setSelector.'").val("'.$setValue.'");'."\n";
			}
		}
		
		$output .= "//---------- mm_ddSetFieldValue :: End -----\n";
		
		$e->output($output);
	}
}
?>