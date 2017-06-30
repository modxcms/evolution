<?php
/**
 * EVO CMS
 * Template parser
 *
 * Date: 24.06.2017
 */

Class TemplateParser {

	function __construct() {
	}

	/**
	 * @param array $config [action, tabs, toArray]
	 * @param array $data
	 * @return string
	 */
	public function output($config = array(), $data = array()) {
		global $modx;

		$output = '';
		$action = !empty($config['action']) ? $config['action'] : (!empty($_REQUEST['a']) ? $_REQUEST['a'] : '');
		$tab = isset($config['tab']) ? ' AND tab IN(' . $config['tab'] . ')' : '';

		if($action) {
			$sql = $modx->db->query('SELECT * 
			FROM ' . $modx->getFullTableName('system_templates') . '
			WHERE action IN(' . $action . ') ' . $tab . '
			ORDER BY tab ASC, rank ASC');

			if($modx->db->getRecordCount($sql)) {
				$tabs = array();
				while($row = $modx->db->getRow($sql)) {
					if(!$row['value'] && !empty($data[$row['name']])) {
						$row['value'] = $data[$row['name']];
					}
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
						$tabName = !empty($config['tabs'][$idTab]) ? $config['tabs'][$idTab] : $idTab;
						$output .= '<div class="tab-page" id="tab_' . $idTab . '">';
						$output .= '
						<h2 class="tab">' . $tabName . '</h2>
						<script type="text/javascript">pane_' . $action . '.addTabPage(document.getElementById("tab_' . $idTab . '"));</script>';
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

	private function render($data) {
		global $modx, $_lang, $_country_lang;

		$output = '';
		$output .= '<div class="form-group row">';

		switch($data['type']) {

			case 'text':
				$output .= '<label class="col-sm-3" for="' . $data['name'] . '">' . $_lang['user_' . $data['name']] . '</label>';
				$output .= '<div class="col-sm-7">';
				$output .= '<input type="text" name="' . $data['name'] . '" class="form-control" id="' . $data['name'] . '" value="' . ($_POST[$data['value']] ? $_POST[$data['value']] : $modx->htmlspecialchars($data['value'])) . '" onChange="documentDirty=true;"' . ($data['readonly'] ? ' readonly' : '') . ' />';
				$output .= $data['content'] ? $data['content'] : '';
				$output .= '</div>';

				break;

			case 'date':
				$output .= '<label class="col-sm-3" for="' . $data['name'] . '">' . $_lang['user_' . $data['name']] . '</label>';
				$output .= '<div class="col-sm-7">';
				$output .= '<input type="text" name="' . $data['name'] . '" class="form-control DatePicker" id="' . $data['name'] . '" value="' . ($_POST[$data['value']] ? $modx->toDateFormat($_POST[$data['value']]) : $modx->toDateFormat($data['value'])) . '" onChange="documentDirty=true;"' . ($data['readonly'] ? ' readonly' : '') . ' />';
				$output .= $data['content'] ? $data['content'] : '';
				$output .= '</div>';

				break;

			case 'select':
				$output .= '<label class="col-sm-3" for="' . $data['name'] . '">' . $_lang['user_' . $data['name']] . '</label>';
				$output .= '<div class="col-sm-7">';
				$output .= '<select name="' . $data['name'] . '" class="form-control" id="' . $data['name'] . '" onChange="documentDirty=true;">';
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
							$output .= '<option value="' . $value[1] . '">' . $_lang[$value[0]] . '</option>';
						}
					}
				}
				$output .= '</select>';
				$output .= $data['content'] ? $data['content'] : '';
				$output .= '</div>';

				break;

			case 'custom':
				$output .= '<label class="col-sm-3" for="' . $data['name'] . '">' . $_lang['user_' . $data['name']] . '</label>';
				$output .= '<div class="col-sm-7">';
				$output .= $data['content'];
				$output .= '</div>';

				break;
		}

		$output .= '</div>';
		return $output;
	}

}
