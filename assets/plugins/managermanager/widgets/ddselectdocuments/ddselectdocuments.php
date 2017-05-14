<?php
/**
 * mm_ddSelectDocuments
 * @version 1.3 (2016-06-06)
 * 
 * @desc A widget for ManagerManager that makes selection of documents ids easier.
 * 
 * @uses ManagerManager 0.6.
 * @uses ddTools 0.10.
 * 
 * @param $tvs {comma separated string} - TVs names that the widget is applied to. @required
 * @param $roles {comma separated string} - Roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Templates IDs for which the widget is applying (empty value means the widget is applying to all templates). Default: ''.
 * @param $parentIds {comma separated string} - Parent documents IDs. Default: '0'.
 * @param $depth {integer} - Depth of search. Default: 1.
 * @param $filter {separated string} - Filter clauses, separated by '&' between pairs and by '=' between keys and values. For example, 'template=15&published=1' means to choose the published documents with template id=15. Default: ''.
 * @param $max {integer} - The largest number of elements that can be selected by user (“0” means selection without a limit). Default: 0.
 * @param $labelMask {string} - Template to be used while rendering elements of the document selection list. It is set as a string containing placeholders for document fields and TVs. Also, there is the additional placeholder “[+title+]” that is substituted with either “menutitle” (if defined) or “pagetitle”. Default: '[+title+] ([+id+])'.
 * @param $allowDoubling {boolean} - Allows to select duplicates values. Default: false.
 * 
 * @event OnDocFormPrerender
 * @event OnDocFormRender
 * 
 * @link http://code.divandesign.biz/modx/mm_ddselectdocuments/1.3
 * 
 * @copyright 2013–2016 DivanDesign {@link http://www.DivanDesign.biz }
 */

function mm_ddSelectDocuments($tvs = '', $roles = '', $templates = '', $parentIds = '0', $depth = 1, $filter = '', $max = 0, $labelMask = '[+title+] ([+id+])', $allowDoubling = false){
	if (!useThisRule($roles, $templates)){return;}
	
	global $modx;
	$e = &$modx->Event;
	
	$output = '';
	
	if ($e->name == 'OnDocFormPrerender'){
		$pluginDir = $modx->config['site_url'].'assets/plugins/managermanager/';
		$widgetDir = $pluginDir.'widgets/ddselectdocuments/';
		
		$output .= includeJsCss($widgetDir.'ddselectdocuments.css', 'html');
		$output .= includeJsCss($pluginDir.'js/jquery-ui.min.js', 'html', 'jquery-ui', '1.12.1');
		$output .= includeJsCss($widgetDir.'jquery.ddMultipleInput-1.2.1.min.js', 'html', 'jquery.ddMultipleInput', '1.2.1');
		
		$e->output($output);
	}else if ($e->name == 'OnDocFormRender'){
		global $mm_current_page;
		
		$tvs = tplUseTvs($mm_current_page['template'], $tvs);
		if ($tvs == false){return;}
		
		$filter = ddTools::explodeAssoc($filter, '&', '=');
		
		//Необходимые поля
		preg_match_all('~\[\+([^\+\]]*?)\+\]~', $labelMask, $matchField);
		
		$fields = array_unique(array_merge(array_keys($filter), array('pagetitle', 'id'), $matchField[1]));
		
		if (($title_pos = array_search('title', $fields)) !== false){
			unset($fields[$title_pos]);
			$fields = array_unique(array_merge($fields, array('menutitle')));
		}
		
		//Рекурсивно получает все необходимые документы
		if (!function_exists('ddGetDocs')){function ddGetDocs($parentIds = array(0), $filter = array(), $depth = 1, $labelMask = '[+pagetitle+] ([+id+])', $fields = array('pagetitle', 'id')){
			//Получаем дочерние документы текущего уровня
			$docs = array();
			
			//Перебираем всех родителей
			foreach ($parentIds as $parent){
				//Получаем документы текущего родителя
				$tekDocs = ddTools::getDocumentChildrenTVarOutput($parent, $fields, false);
				
				//Если что-то получили
				if (is_array($tekDocs)){
					//Запомним
					$docs = array_merge($docs, $tekDocs);
				}
			}
			
			$result = array();
			
			//Если что-то есть
			if (count($docs) > 0){
				//Перебираем полученные документы
				foreach ($docs as $val){
					//Если фильтр пустой, либо не пустой и документ удовлетворяет всем условиям
					if (empty($filter) || count(array_intersect_assoc($filter, $val)) == count($filter)){
						$val['title'] = empty($val['menutitle']) ? $val['pagetitle'] : $val['menutitle'];
						
						//Записываем результат
						$tmp = ddTools::parseText($labelMask, $val, '[+', '+]', false);
						
						if (strlen(trim($tmp)) == 0){
							$tmp = ddTools::parseText('[+pagetitle+] ([+id+])', $val, '[+', '+]', false);
						}
						
						$result[] = array(
							'label' => $tmp,
							'value' => $val['id']
						);
					}
					
					//Если ещё надо двигаться глубже
					if ($depth > 1){
						//Сливаем результат с дочерними документами
						$result = array_merge($result, ddGetDocs(array($val['id']), $filter, $depth - 1, $labelMask, $fields));
					}
				}
			}
			
			return $result;
		}}
		
		//Получаем все дочерние документы
		$docs = ddGetDocs(explode(',', $parentIds), $filter, $depth, $labelMask, $fields);
		
		if (count($docs) == 0){return;}
		
		if (version_compare(PHP_VERSION, '5.4.0') >= 0){
			$jsonDocs = json_encode($docs, JSON_UNESCAPED_UNICODE);
		}else{
			$jsonDocs = preg_replace_callback(
				'/\\\\u([0-9a-f]{4})/i',
				create_function(
					'$matches',
					'$sym = mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UTF-16"); return $sym;'
				),
				json_encode($docs)
			);
		}
		
		$output .= '//---------- mm_ddSelectDocuments :: Begin -----'.PHP_EOL;
		
		foreach ($tvs as $tv){
			$output .=
'
$j("#tv'.$tv['id'].'").ddMultipleInput({source: '.$jsonDocs.', max: '.(int) $max.', allowDoubling: '.(int) $allowDoubling.'});
';
		}
		
		$output .= '//---------- mm_ddSelectDocuments :: End -----'.PHP_EOL;
		
		$e->output($output);
	}
}
?>