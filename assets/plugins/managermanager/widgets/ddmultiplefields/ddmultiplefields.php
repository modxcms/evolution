<?php
/**
 * mm_ddMultipleFields
 * @version 4.6 (2014-10-24)
 * 
 * @desc Widget for plugin ManagerManager that allows you to add any number of fields values (TV) in one document (values is written as one with using separator symbols). For example: a few images.
 * 
 * @uses ManagerManager plugin 0.6.3.
 * 
 * @param $tvs {comma separated string} - Names of TV for which the widget is applying. @required
 * @param $roles {comma separated string} - The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: ''.
 * @param $templates {comma separated string} - Templates IDs for which the widget is applying (empty value means the widget is applying to all templates). Default: ''.
 * @param $columns {comma separated string} - Column types: field — field type column; text — text type column; textarea — multiple lines column; richtext — column with rich text editor; date — date column; id — hidden column containing unique id; select — list with options (parameter “columnsData”). Default: 'field'.
 * @param $columnsTitle {comma separated string} - Columns titles. Default: ''.
 * @param $colWidth {comma separated string} - Columns width (one value can be set). Default: 180;
 * @param $splY {string} - Strings separator. Default: '||'.
 * @param $splX {string} - Columns separator. Default: '::'.
 * @param $imgW {integer} - Maximum value of image preview width. Default: 300.
 * @param $imgH {integer} - Maximum value of image preview height. Default: 100.
 * @param $minRow {integer} - Minimum number of strings. Default: 0.
 * @param $maxRow {integer} - Maximum number of strings. Default: 0 (без лимита).
 * @param $columnsData {separated string} - List of valid values in json format (with “||”). Default: ''. Example: '[['','No selected'],['0','No'],['1','Yes',1]]'
 * @param $options {array or JSON} - Extend options: sortable - allow sorting (default), showIndex - display line numbers (default), btnToggleRaw - show button "Raw" (not defailt)
 * 
 * @event OnDocFormPrerender
 * @event OnDocFormRender
 * 
 * @link http://code.divandesign.biz/modx/mm_ddmultiplefields/4.5.1
 * 
 * @copyright 2014, DivanDesign
 * http://www.DivanDesign.biz
 */

function mm_ddMultipleFields($tvs = '', $roles = '', $templates = '', $columns = 'field', $columnsTitle = '', $colWidth = '180', $splY = '||', $splX = '::', $imgW = 300, $imgH = 100, $minRow = 0, $maxRow = 0, $columnsData = '',$options = array()){
	if (!useThisRule($roles, $templates)){return;}
	if (is_array($options)) {
		$options = json_encode($options);
	}
	global $modx;
	$e = &$modx->Event;
	
	$output = '';
	
	$site = $modx->config['site_url'];
	$widgetDir = $site.'assets/plugins/managermanager/widgets/ddmultiplefields/';
	
	if ($e->name == 'OnDocFormPrerender'){
		global $_lang;
		
		$output .= includeJsCss($site.'assets/plugins/managermanager/js/jquery-ui.min.js', 'html', 'jquery-ui', '1.12.1');
		$output .= includeJsCss($widgetDir.'ddmultiplefields.css', 'html');
		$output .= includeJsCss($widgetDir.'jquery.ddMM.mm_ddMultipleFields.js', 'html', 'jquery.ddMM.mm_ddMultipleFields', '1.1.1');
		
		$output .= includeJsCss('$j.ddMM.lang.edit = "'.$_lang['edit'].'";$j.ddMM.lang.confirm_delete_record = "'.$_lang["confirm_delete_record"].'";', 'html', 'mm_ddMultipleFields_plain', '1', true, 'js');

		$e->output($output);
	}else if ($e->name == 'OnDocFormRender'){
		global $mm_current_page;
		
		if ($columnsData){
			$columnsDataTemp = explode('||', $columnsData);
			$columnsData = array();
			
			foreach ($columnsDataTemp as $value){
				//Евалим знение и записываем результат или исходное значени
				try {
			    	$eval = eval($value);
			    } catch (Throwable $t) {
			    	echo $t->getMessage(), "\n";
			    } catch (Exception $e) {
			    	echo $e->getMessage(), "\n";
			    }
				$columnsData[] = $eval ? addslashes(json_encode($eval)) : addslashes($value);
			}
			//Сливаем в строку, что бы передать на клиент
			$columnsData = implode('||', $columnsData);
		}
		
		//Стиль превью изображения
		$imgW = $imgW.(is_numeric($imgW)?"px":"");
		$imgH = $imgH.(is_numeric($imgH)?"px":"");
		$stylePrewiew = "max-width:{$imgW}; max-height:{$imgH}; margin: 4px 0; cursor: pointer;";
		
		$tvsMas = tplUseTvs($mm_current_page['template'], $tvs, 'image,file,text,email,textarea', 'id,type');
		if ($tvsMas == false){return;}
		
		$output .= "//---------- mm_ddMultipleFields :: Begin -----\n";
		
		//For backward compatibility
		$columns = makeArray($columns);
		//Находим колонки, заданные как «field», теперь их нужно будет заменить на «image» и «file» соответственно
		$columns_fieldIndex = array_keys($columns, 'field');
		
		foreach ($tvsMas as $tv){
			//For backward compatibility
			if ($tv['type'] == 'image' || $tv['type'] == 'file'){
				//Проходимся по всем колонкам «field» и заменяем на соответствующий тип
				foreach($columns_fieldIndex as $val){
					$columns[$val] = $tv['type'];
				}
			}
			
			$output .=
'
$j("#tv'.$tv['id'].'").mm_ddMultipleFields({
	splY: "'.$splY.'",
	splX: "'.$splX.'",
	coloumns: "'.implode(',', $columns).'",
	coloumnsTitle: "'.$columnsTitle.'",
	coloumnsData: "'.$columnsData.'",
	colWidth: "'.$colWidth.'",
	imageStyle: "'.$stylePrewiew.'",
	minRow: "'.$minRow.'",
	maxRow: "'.$maxRow.'",
	options: '.$options.'
});
';
		}
		
		//Поругаемся
		if (!empty($columns_fieldIndex)){
			$modx->logEvent(1, 2, '<p>You are currently using the deprecated column type “field”. Please, replace it with “image” or “file” respectively.</p><p>The plugin has been called in the document with template id '.$mm_current_page['template'].'.</p>', 'ManagerManager: mm_ddMultipleFields');
		}
		
		$output .= "//---------- mm_ddMultipleFields :: End -----\n";
		
		$e->output($output);
	}
}
?>