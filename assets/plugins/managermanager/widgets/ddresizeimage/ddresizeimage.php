<?php
/** 
 * mm_ddResizeImage
 * @version 1.3.6 (2014-12-25)
 * 
 * @desc A widget for ManagerManager plugin that allows image size to be changed (TV) so it is possible to create a little preview (thumb).
 * 
 * @uses phpThumb lib 1.7.11-201108081537-beta (http://phpthumb.sourceforge.net/).
 * @uses сниппет ddGetMultipleField 3.0b snippet (if mm_ddMultipleFields fields unparse is required).
 * 
 * @note replaceFieldVal doesn`t work if $multipleField == 1!
 * 
 * @param $tvs {comma separated string} - The names of TVs for which the widget is applied. @required
 * @param $roles {comma separated string} -	The roles that the widget is applied to (when this parameter is empty then widget is applied to the all roles). Default: '' (to all roles).
 * @param $templates {comma separated string} - The templates for which the widget is applied (empty value means all templates). Default: '' (to all templates).
 * @param $width {integer} - Width of the image being created (in px). Empty value means width calculating automatically according to height. At least one of the two parameters must be defined. @required
 * @param $height {integer} - Height of the image being created (in px). Empty value means height calculating automatically according to width. At least one of the two parameters must be defined. @required
 * @param $cropping {string} - Cropping status. 0 — cropping is not required; 1— cropping is required (proportions won`t be saved); 'crop_resized' — the image will be resized and cropped; 'fill_sized' — the image will be resized with propotions saving, blank spaze will be filled with «background» color. Default: 'crop_resized'.
 * @param $suffix {string} - The suffix for the images being created. Its empty value makes initial images to be rewritten! Default: '_ddthumb'.
 * @param $replaceFieldVal {0; 1} - TV values rewriting status. When this parameter equals 1 then tv values are replaced by the names of the created images. It doesn`t work if multipleField = 1. Default: 0.
 * @param $background {string} - Background color. It matters if cropping equals 'fill_resized'. Default: '#ffffff'.
 * @param $multipleField {0; 1} - Multiple field status (for mm_ddMultipleFields). Default: '0';
 * @param $colNum {integer} - The number of the column in which the image is located (for mm_ddMultipleFields). Default: 0.
 * @param $splY {string} - The string delimiter (for mm_ddMultipleFields). Default: '||'.
 * @param $splX {string} - The column delimiter (for mm_ddMultipleFields). Default: '::'.
 * @param $num {integer} - The number of the string that will be processed (for mm_ddMultipleFields). Default: 'all'.
 * @param $allowEnlargement {0; 1} - Allow output enlargement. Default: 1.
 * 
 * @event OnBeforeDocFormSave.
 * 
 * @link http://code.divandesign.biz/modx/mm_ddresizeimage/1.3.6
 * 
 * @copyright 2014, DivanDesign
 * http://www.DivanDesign.biz
 */

function mm_ddResizeImage($tvs = '', $roles = '', $templates = '', $width = '', $height = '', $cropping = 'crop_resized', $suffix = '_ddthumb', $replaceFieldVal = 0, $background = '#FFFFFF', $multipleField = 0, $colNum = 0, $splY = '||', $splX = '::', $num = 'all', $allowEnlargement = 1){
	global $modx;
	$e = &$modx->Event;
	
	if(!function_exists('ddCreateThumb')){
		/**
		 * Делает превьюшку
		 * 
		 * @param $thumbData {array}
		 */
		function ddCreateThumb($thumbData){
			//Вычислим размеры оригинаольного изображения
			$originalImg = array();
			list($originalImg['width'], $originalImg['height']) = getimagesize($thumbData['originalImage']);
			
			//Если хотя бы один из размеров оригинала оказался нулевым (например, это не изображение) — на(\s?)бок
			if ($originalImg['width'] == 0 || $originalImg['height'] == 0){return;}
			
			//Пропрорции реального изображения
			$originalImg['ratio'] = $originalImg['width'] / $originalImg['height'];
			
			//Если по каким-то причинам высота не задана
			if ($thumbData['height'] == '' || $thumbData['height'] == 0){
				//Вычислим соответственно пропорциям
				$thumbData['height'] = $thumbData['width'] / $originalImg['ratio'];
			}
			//Если по каким-то причинам ширина не задана
			if ($thumbData['width'] == '' || $thumbData['width'] == 0){
				//Вычислим соответственно пропорциям
				$thumbData['width'] = $thumbData['height'] * $originalImg['ratio'];
			}
			
			//Если превьюшка уже есть и имеет нужный размер, ничего делать не нужно
			if ($originalImg['width'] == $thumbData['width'] &&
				$originalImg['height'] == $thumbData['height'] &&
				file_exists($thumbData['thumbName'])
			){
				return;
			}
			
			$thumb = new phpThumb();
			//зачистка формата файла на выходе
			$thumb->setParameter('config_output_format', null);
			//Путь к оригиналу
			$thumb->setSourceFilename($thumbData['originalImage']);
			//Качество (для JPEG) = 100
			$thumb->setParameter('q', '100');
			//Разрешить ли увеличивать изображение
			$thumb->setParameter('aoe', $thumbData['allowEnlargement']);
			
			//Если нужно просто обрезать
			if($thumbData['cropping'] == '1'){
				//Ширина превьюшки
				$thumb->setParameter('sw', $thumbData['width']);
				//Высота превьюшки
				$thumb->setParameter('sh', $thumbData['height']);
				
				//Если ширина оригинального изображения больше
				if ($originalImg['width'] > $thumbData['width']){
					//Позиция по оси x оригинального изображения (чтобы было по центру)
					$thumb->setParameter('sx', ($originalImg['width'] - $thumbData['width']) / 2);
				}
				
				//Если высота оригинального изображения больше
				if ($originalImg['height'] > $thumbData['height']){
					//Позиция по оси y оригинального изображения (чтобы было по центру)
					$thumb->setParameter('sy', ($originalImg['height'] - $thumbData['height']) / 2);
				}
			}else{
				//Ширина превьюшки
				$thumb->setParameter('w', $thumbData['width']);
				//Высота превьюшки
				$thumb->setParameter('h', $thumbData['height']);
				
				//Если нужно уменьшить + отрезать
				if($thumbData['cropping'] == 'crop_resized'){
					$thumb->setParameter('zc', '1');
				//Если нужно пропорционально уменьшить, заполнив поля цветом
				}else if($thumbData['cropping'] == 'fill_resized'){
					//Устанавливаем фон (без решётки)
					$thumb->setParameter('bg', str_replace('#', '', $thumbData['backgroundColor']));
					//Превьюшка должна точно соответствовать размеру и находиться по центру (недостающие области зальются цветом)
					$thumb->setParameter('far', 'c');
				}
			}
			
			//Создаём превьюшку
			$thumb->GenerateThumbnail();
			//Сохраняем в файл
			$thumb->RenderToFile($thumbData['thumbName']);
		}
	}
	
	//Проверим, чтобы было нужное событие, чтобы были заполнены обязательные параметры и что правило подходит под роль
	if ($e->name == 'OnBeforeDocFormSave' &&
		$tvs != '' && ($width != '' || $height != '') &&
		useThisRule($roles, $templates)
	){
		global $mm_current_page, $tmplvars;
		
		//Получаем необходимые tv для данного шаблона (т.к. в mm_ddMultipleFields тип может быть любой, получаем все, а не только изображения)
		$tvs = tplUseTvs($mm_current_page['template'], $tvs, '', 'id,name');
		
		//Если что-то есть
		if (is_array($tvs) && count($tvs) > 0){
			//Обработка параметров
			$replaceFieldVal = ($replaceFieldVal == '1') ? true : false;
			$multipleField = ($multipleField == '1') ? true : false;
			
			//Подключаем phpThumb
			require_once $modx->config['base_path'].'assets/plugins/managermanager/widgets/ddresizeimage/phpthumb.class.php';
			
			//Перебираем их
			foreach ($tvs as $tv){
				//Если в значении tv что-то есть
				if (isset($tmplvars[$tv['id']]) && trim($tmplvars[$tv['id']][1]) != ''){
					$image = trim($tmplvars[$tv['id']][1]);
					
					//Если это множественное поле
					if ($multipleField){
						//Получим массив изображений
						$images = $modx->runSnippet('ddGetMultipleField', array(
							'string' => $image,
							'rowDelimiter' => $splY,
							'colDelimiter' => $splX,
							'startRow' => ($num == 'all' ? 0 : $num),
							'totalRows' => ($num == 'all' ? 'all' : 1),
							'outputFormat' => 'JSON',
							'columns' => $colNum,
							//For backward compatibility with < 3.0b
							'field' => $image,
							'splY' => $splY,
							'splX' => $splX,
							'num' => ($num == 'all' ? 0 : $num),
							'count' => ($num == 'all' ? 'all' : 1),
							'format' => 'JSON',
							'colNum' => $colNum
						));
						
						//Если пришла пустота (ни одного изображения заполнено не было)
						if (trim($images) == ''){
							$images = array();
						}else if ($num == 'all'){
							$images = json_decode($images, true);
						}else{
							$images = array(trim(stripcslashes($images), '\'\"'));
						}
					}else{
						//Запишем в массив одно изображение
						$images = array($image);
					}
					
					foreach ($images as $image){
						//Если есть лишний слэш в начале, убьём его
						if (strpos($image, '/') === 0) $image = substr($image, 1);
						
						//На всякий случай проверим, что файл существует
						if (file_exists($modx->config['base_path'].$image)){
							//Полный путь изображения
							$imageFullPath = pathinfo($modx->config['base_path'].$image);
							
							//Если имя файла уже заканчивается на суффикс (необходимо при $replaceFieldVal == 1), не будем его добавлять
							if (substr($imageFullPath['filename'], strlen($suffix) * -1) == $suffix){
								$suffix = '';
							}
							
							//Имя нового изображения
							$newImageName = $imageFullPath['filename'].$suffix.'.'.$imageFullPath['extension'];
						
							//Делаем превьюшку
							ddCreateThumb(array(
								//Ширина превьюшки
								'width' => $width,
								//Высота превьюшки
								'height' => $height,
								//Фон превьюшки (может понадобиться для заливки пустых мест)
								'backgroundColor' => $background,
								//Режим обрезания
								'cropping' => $cropping,
								//Формируем новое имя изображения (полный путь)
								'thumbName' => $imageFullPath['dirname'].'/'.$newImageName,
								//Ссылка на оригинальное изображение
								'originalImage' => $modx->config['base_path'].$image,
								//Разрешить ли увеличение изображения
								'allowEnlargement' => $allowEnlargement
							));
							
							//Если нужно заменить оригинальное значение TV на вновь созданное и это не $multipleField
							if ($replaceFieldVal && !$multipleField){
								$tmplvars[$tv['id']][1] = dirname($tmplvars[$tv['id']][1]).'/'.$newImageName;
							}
						}
					}
				}
			}
		}
	}
}
?>