<?php
/**
 * EVO CMS
 * Template parser
 *
 * Date: 24.06.2017
 *
 */

Class TemplateParser {

    /**
	 * @param array $config [action, tabs, toArray]
	 * @param array $data
	 * @return string
	 */
	public function output($config = array(), $data = array()) {
        $modx = evolutionCMS();

		$output = '';
		$action = !empty($config['action']) ? $config['action'] : (!empty($_REQUEST['a']) ? $_REQUEST['a'] : '');
		$tab = isset($config['tab']) ? ' AND tab IN(' . $config['tab'] . ')' : '';

		if($action) {
			$sql = $modx->db->query('SELECT t1.*, IF(t1.alias=\'\',t1.name,t1.alias) AS alias, t2.category AS category_name
			FROM ' . $modx->getFullTableName('system_templates') . ' AS t1
			INNER JOIN ' . $modx->getFullTableName('categories') . ' AS t2 ON t2.id=t1.category
			WHERE t1.action IN(' . $action . ') ' . $tab . '
			ORDER BY t1.tab ASC, t1.rank ASC');

			if($modx->db->getRecordCount($sql)) {
				$tabs = array();
				while($row = $modx->db->getRow($sql)) {
					if(!$row['value'] && !empty($data[$row['name']])) {
						$row['value'] = $data[$row['name']];
					}
					$tabs[$row['tab']]['category_name'] = $row['category_name'];
					$tabs[$row['tab']][$row['name']] = TemplateParser::render($row);
				}

				if(!empty($config['toArray'])) {
					$output = $tabs;
				} else {
					$output .= '<div class="tab-pane" id="pane_' . $action . '">';
					$output .= '
					<script type="text/javascript">
						var pane_' . $action . ' = new WebFXTabPane(document.getElementById("pane_' . $action . '"), ' . ($modx->config['remember_last_tab'] == 1 ? 'true' : 'false') . ');
					</script>';

					foreach($tabs as $idTab => $tab) {
						$output .= '<div class="tab-page" id="tab_' . $action . '_' . $idTab . '">';
						$output .= '
						<h2 class="tab">' . (!empty($config['tabs'][$idTab]) ? $config['tabs'][$idTab] : $tab['category_name']) . '</h2>
						<script type="text/javascript">pane_' . $action . '.addTabPage(document.getElementById("tab_' . $action . '_' . $idTab . '"));</script>';
						unset($tab['category_name']);
						foreach($tab as $item) {
							$output .= $item;
						}
						$output .= '</div>';
					}
					$output .= '</div>';
				}
			}
		}

		return $output;
	}

    /**
     * @param array $data
     * @return string
     */
	private function render($data) {
		$modx = evolutionCMS(); global $_lang, $_country_lang;

		$data['lang.name'] = (isset($_lang[$data['alias']]) ? $_lang[$data['alias']] : $data['alias']);
		$data['value'] = (isset($_POST[$data['name']][$data['value']]) ? $_POST[$data['name']][$data['value']] : (isset($data['value']) ? $modx->htmlspecialchars($data['value']) : ''));
		$data['readonly'] = ($data['readonly'] ? ' readonly' : '');

		$output = '';
		$output .= '<div class="form-group row">';

		switch($data['type']) {

			case 'text':
				$output .= '<label class="col-sm-3" for="[+name+]">[+lang.name+]</label>
					<div class="col-sm-7">
					<input type="text" name="[+name+]" class="form-control" id="[+name+]" value="[+value+]" onChange="documentDirty=true;"[+readonly+] />';
				$output .= $data['content'];
				$output .= '</div>';

				break;

			case 'textarea':
				$output .= '<label class="col-sm-3" for="[+name+]">[+lang.name+]</label>
					<div class="col-sm-7">
					<textarea name="[+name+]" class="form-control" id="[+name+]" onChange="documentDirty=true;"[+readonly+]>[+value+]</textarea>';
				$output .= $data['content'];
				$output .= '</div>';

				break;

			case 'date':
				$data['value'] = (isset($_POST[$data['name']][$data['value']]) ? $modx->toDateFormat($_POST[$data['name']][$data['value']]) : (isset($data['value']) ? $modx->toDateFormat($data['value']) : ''));
				$output .= '<label class="col-sm-3" for="[+name+]">[+lang.name+]</label>
					<div class="col-sm-7">
					<input type="text" name="[+name+]" class="form-control DatePicker" id="[+name+]" value="[+value+]" onChange="documentDirty=true;"[+readonly+] />';
				$output .= $data['content'];
				$output .= '</div>';

				break;

			case 'select':
				$output .= '<label class="col-sm-3" for="[+name+]">[+lang.name+]</label>';
				$output .= '<div class="col-sm-7">';
				$output .= '<select name="[+name+]" class="form-control" id="[+name+]" onChange="documentDirty=true;">';
				if($data['name'] == 'country' && isset($_country_lang)) {
					$chosenCountry = isset($_POST['country']) ? $_POST['country'] : $data['country'];
					$output .= '<option value=""' . (!isset($chosenCountry) ? ' selected' : '') . '>&nbsp;</option>';
					foreach($_country_lang as $key => $value) {
						$output .= '<option value="' . $key . '"' . (isset($chosenCountry) && $chosenCountry == $key ? ' selected' : '') . '>' . $value . '</option>';
					}
				} else {
					if($data['elements']) {
						$elements = explode('||', $data['elements']);
						foreach($elements as $key => $value) {
							$value = explode('==', $value);
							$output .= '<option value="' . $value[1] . '">' . (isset($_lang[$value[0]]) ? $_lang[$value[0]] : $value[0]) . '</option>';
						}
					}
				}
				$output .= '</select>';
				$output .= $data['content'];
				$output .= '</div>';

				break;

			case 'checkbox':
				$output .= '<label class="col-sm-3" for="[+name+]">[+lang.name+]</label>';
				$output .= '<div class="col-sm-7">';
				$output .= '<input type="checkbox" name="[+name+]" class="form-control" id="[+name+]" value="[+value+]" onChange="documentDirty=true;"[+readonly+] />';
				if($data['elements']) {
					$elements = explode('||', $data['elements']);
					foreach($elements as $key => $value) {
						$value = explode('==', $value);
						$output .= '<br /><input type="checkbox" name="' . $value[0] . '" class="form-control" id="' . $value[0] . '" value="' . $value[1] . '" onChange="documentDirty=true;"[+readonly+] /> ' . (isset($_lang[$value[0]]) ? $_lang[$value[0]] : $value[0]);
					}
				}
				$output .= $data['content'];
				$output .= '</div>';

				break;

			case 'radio':
				$output .= '<label class="col-sm-3" for="[+name+]">[+lang.name+]</label>';
				$output .= '<div class="col-sm-7">';
				$output .= '<input type="radio" name="[+name+]" class="form-control" id="[+name+]" value="[+value+]" onChange="documentDirty=true;"[+readonly+] />';
				if($data['elements']) {
					$elements = explode('||', $data['elements']);
					foreach($elements as $key => $value) {
						$value = explode('==', $value);
						$output .= '<br /><input type="radio" name="[+name+]" class="form-control" id="[+name+]_' . $key . '" value="' . $value[1] . '" onChange="documentDirty=true;"[+readonly+] /> ' . (isset($_lang[$value[0]]) ? $_lang[$value[0]] : $value[0]);
					}
				}
				$output .= $data['content'];
				$output .= '</div>';

				break;

			case 'custom':
				$output .= '<label class="col-sm-3" for="[+name+]">[+lang.name+]</label>';
				$output .= '<div class="col-sm-7">';
				$output .= $data['content'];
				$output .= '</div>';

				break;
		}

		$output .= '</div>';

		$output = $modx->parseText($output, $data);

		return $output;
	}

}

?>

<?php
/*

include_once MODX_BASE_PATH . MGR_DIR . '/media/style/' . $modx->config['manager_theme'] . '/includes/template.parser.class.inc.php';

echo TemplateParser::output(array('action' => 88), $userdata);

*/
?>


<!--

-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.29 - MySQL Community Server (GPL)
-- Операционная система:         Win64
-- HeidiSQL Версия:              9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных modxnewhtml
CREATE DATABASE IF NOT EXISTS `modxnewhtml` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `modxnewhtml`;

-- Дамп структуры для таблица modxnewhtml.modx_system_templates
CREATE TABLE IF NOT EXISTS `modx_system_templates` (
`id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(50) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  `help` varchar(255) NOT NULL DEFAULT '',
  `value` varchar(255) NOT NULL DEFAULT '',
  `readonly` int(1) NOT NULL DEFAULT '0',
  `elements` text NOT NULL,
  `content` text NOT NULL,
  `category` int(11) NOT NULL,
  `template` int(11) NOT NULL DEFAULT '0',
  `tab` int(11) NOT NULL DEFAULT '0',
  `rank` int(11) NOT NULL DEFAULT '0',
  `action` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы modxnewhtml.modx_system_templates: ~14 rows (приблизительно)
	/*!40000 ALTER TABLE `modx_system_templates` DISABLE KEYS */;
INSERT INTO `modx_system_templates` (`id`, `name`, `alias`, `type`, `description`, `help`, `value`, `readonly`, `elements`, `content`, `category`, `template`, `tab`, `rank`, `action`) VALUES
(0, 'fullname', 'user_full_name', 'text', '', '', '', 0, '', '', 9, 0, 1, 1, 88),
	(1, 'email', 'user_email', 'text', '', '', '', 0, '', '', 9, 0, 1, 2, 88),
	(2, 'phone', 'user_phone', 'text', '', '', '', 0, '', '', 9, 0, 1, 3, 88),
	(3, 'mobilephone', 'user_mobile', 'text', '', '', '', 0, '', '', 9, 0, 1, 5, 88),
	(4, 'fax', 'user_fax', 'text', '', '', '', 0, '', '', 9, 0, 1, 6, 88),
	(5, 'street', 'user_street', 'text', '', '', '', 0, '', '', 9, 0, 1, 7, 88),
	(6, 'city', 'user_city', 'text', '', '', '', 0, '', '', 9, 0, 1, 8, 88),
	(7, 'state', 'user_state', 'text', '', '', '', 0, '', '', 9, 0, 1, 9, 88),
	(8, 'zip', 'user_zip', 'text', '', '', '', 0, '', '', 9, 0, 1, 10, 88),
	(9, 'country', 'user_country', 'select', '', '', '', 0, '', '', 9, 0, 1, 11, 88),
	(10, 'dob', 'user_dob', 'date', '', '', '', 0, '', '', 9, 0, 1, 12, 88),
	(11, 'gender', 'user_gender', 'select', '', '', '', 0, '||user_male==1||user_female==2||user_other==3', '', 9, 0, 1, 13, 88),
	(12, 'comment', '', 'textarea', '', '', '', 0, '', '', 9, 0, 1, 14, 88),
	(13, 'logincount', 'user_logincount', 'custom', '', '', '', 0, '', '[+value+]', 9, 0, 1, 15, 88);
/*!40000 ALTER TABLE `modx_system_templates` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

-->
