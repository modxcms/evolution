<?php
/**
 * mm_ddFillMenuindex
 * @version 1.0 (2013-03-14)
 *
 * Widget sets the minimum free menuindex for a new documents. By default in MODx menuindex for a new documents equals just simply count of children, which is not always convenient.
 *
 * @uses ManagerManager plugin 0.5.
 * 
 * @param $parent {integer} - Id of parent document.
 *
 * @link http://code.divandesign.biz/modx/mm_ddfillmenuindex/1.0
 *
 * @copyright 2013, DivanDesign
 * http://www.DivanDesign.biz
 */

function mm_ddFillMenuindex($parent = ''){
	global $modx, $content;
	$e = &$modx->Event;
	
	//Если у нас правильное событие и это создание документа
	if ($e->name == 'OnDocFormPrerender' && $_REQUEST['a'] == 4){
		$pid = intval($_REQUEST['pid']);
		
		//Если задан конкретный родитель, для которого должен работать виджет и он не совпадает с тем, что сейчас
		if ($parent !== '' && $parent != $pid){
			//Давай, до свидания!
			return;
		}
		
		//Получаем наименьший свободный menuindex у документов данного родителя. Кхэм, запрос писался глубокой ночью, так что за его оптимальность отвечать сложно ;-)
		$freeMenuIndex = $modx->db->getValue("
		SELECT min(`sc`.`menuindex`)
		FROM `dd_site_content` AS `sc`
			LEFT JOIN (
				SELECT `sc1`.`menuindex`
				FROM `dd_site_content` AS `sc1`, `dd_site_content` AS `sc2`
				WHERE `sc1`.`menuindex` + 1 = `sc2`.`menuindex` AND `sc1`.`parent` = $pid AND `sc2`.`parent` = $pid
			) AS `z`
			ON `sc`.`menuindex` = `z`.`menuindex`
		WHERE `z`.`menuindex` IS NULL AND `sc`.`parent` = $pid
		");
		
		//Если такового нет (дочерних вообще нет). P.S.: Если он пуст, то переопределять нет смысла, там всё хорошо и так поставится.
		if (!is_null($freeMenuIndex)){
			//Задаём следующим
			$content['menuindex'] = $freeMenuIndex + 1;
		}
	}
}
?>