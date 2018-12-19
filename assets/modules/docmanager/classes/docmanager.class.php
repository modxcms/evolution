<?php

class DocManager {
	var $modx = null;
	var $lang = array();
	var $ph = array();
	var $theme = '';
	var $fileRegister = array();
	
    function __construct(&$modx) {
    	$this->modx = $modx;
    }
    
    function getLang() {
    	$_lang = array();
    	$ph = array();
		$managerLanguage = $this->modx->config['manager_language'];

		$userId = $this->modx->getLoginUserID();
		if (!empty($userId)) {
			$lang = $this->modx->db->select('setting_value', $this->modx->getFullTableName('user_settings'), "setting_name='manager_language' AND user='{$userId}'");
			if ($lang = $this->modx->db->getValue($lang)) {
	   	 		$managerLanguage = $lang;
			}
		}
		
		include MODX_MANAGER_PATH.'includes/lang/english.inc.php';
		if($managerLanguage != 'english') {
			if (file_exists(MODX_MANAGER_PATH.'includes/lang/'.$managerLanguage.'.inc.php')) {
     			include MODX_MANAGER_PATH.'includes/lang/'.$managerLanguage.'.inc.php';
			}
		}
		
		include MODX_BASE_PATH.'assets/modules/docmanager/lang/english.inc.php';
		if($managerLanguage != 'english') {
			if (file_exists(MODX_BASE_PATH.'assets/modules/docmanager/lang/'.$managerLanguage.'.inc.php')) {
     			include MODX_BASE_PATH.'assets/modules/docmanager/lang/'.$managerLanguage.'.inc.php';
			}
		}
		$this->lang = $_lang;
		foreach ($_lang as $key => $value) {
			$ph['lang.'.$key] = $value;
		}
		return $ph;
    }
    
    function getTheme() {
    	$theme = $this->modx->db->select('setting_value', $this->modx->getFullTableName('system_settings'), "setting_name='manager_theme'");
		if ($theme = $this->modx->db->getValue($theme)) {
			$this->theme = ($theme <> '') ? '/' . $theme : '';
			return $this->theme;
		} else {
			return '';
		}
    }
    
    function getFileContents($file) {
    	if (empty($file)) {
    		return false;
    	} else {
	    	$file = MODX_BASE_PATH.'assets/modules/docmanager/templates/'.$file;
	    	if(array_key_exists($file, $this->fileRegister)) {
	    		return $this->fileRegister[$file];
	    	} else {
	    		$contents = file_get_contents($file);
	    		$this->fileRegister[$file] = $contents;
	    		return $contents;
	    	}
	    }
    }
    
    function loadTemplates() {
    	$this->fileGetContents('main.tpl');
    }

    function parseTemplate($tpl, $values = array()) {
    	$tpl = array_key_exists($tpl, $this->fileRegister) ? $this->fileRegister[$tpl] : $this->getFileContents($tpl);
    	if($tpl) {
    		if(strpos($tpl,'</body>')!==false) {
    			if(!isset($this->modx->config['mgr_date_picker_path']))   $this->modx->config['mgr_date_picker_path']   = 'media/script/air-datepicker/datepicker.inc.php';
    			$dp = $this->modx->manager->loadDatePicker($this->modx->config['mgr_date_picker_path']);
    			$tpl = str_replace('</body>',$dp.'</body>',$tpl);
                global $modx;
                $evtOut = $modx->invokeEvent('OnManagerMainFrameHeaderHTMLBlock');
                $onManagerMainFrameHeaderHTMLBlock = is_array($evtOut) ? implode("\n", $evtOut) : '';
                $tpl = str_replace('[+onManagerMainFrameHeaderHTMLBlock+]',$onManagerMainFrameHeaderHTMLBlock,$tpl);
      		}
    		if(!isset($this->modx->config['mgr_jquery_path']))  $this->modx->config['mgr_jquery_path'] = 'media/script/jquery/jquery.min.js';
    		$tpl = $this->modx->mergeSettingsContent($tpl);
    		foreach ($values as $key => $value) {
    			$tpl = str_replace('[+'.$key.'+]', $value, $tpl); 
    		}
    		$tpl = preg_replace('/(\[\+.*?\+\])/' ,'', $tpl);
    		return $tpl;
    	} else {
    		return '';
    	}
    }
}
?>