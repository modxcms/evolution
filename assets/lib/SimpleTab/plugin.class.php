<?php
namespace SimpleTab;
include_once (MODX_BASE_PATH . 'assets/snippets/DocLister/lib/DLTemplate.class.php');
include_once (MODX_BASE_PATH . 'assets/lib/APIHelpers.class.php');
require_once (MODX_BASE_PATH . 'assets/lib/Helpers/FS.php');
require_once (MODX_BASE_PATH . 'assets/lib/Helpers/Assets.php');

abstract class Plugin {
	public $modx = null;
	public $pluginName = '';
	public $params = array();
    public $table = '';
	public $tpl = '';
	public $jsListDefault = '';
	public $jsListCustom = '';
	public $cssListDefault = '';
	public $cssListCustom = '';
	public $pluginEvents = array();
    public $_table = '';
	protected $fs = null;
    protected $assets = null;

	public $DLTemplate = null;
	public $lang_attribute = '';

	protected $checkTemplate = true;
	protected $renderEvent = 'OnDocFormRender';

	protected $checkId = true;

	/**
     * @param $modx
     * @param string $lang_attribute
     * @param bool $debug
     */
    public function __construct($modx, $lang_attribute = 'en', $debug = false) {
        $this->modx = $modx;
        $this->_table = $modx->getFullTableName($this->table);
        $this->lang_attribute = $lang_attribute;
        $this->params = $modx->event->params;
        if ($this->checkTemplate && !isset($this->params['template']) && $modx->event->name != 'OnEmptyTrash') {
            $this->params['template'] = array_pop($modx->getDocument($this->params['id'],'template','all','all'));
        }
        //overload plugin and class properties
        $_params = $modx->parseProperties('&template=;;'.$this->params['template'].' &id=;;'.$this->params['id'],$modx->event->activePlugin,'plugin');
        foreach ($_params as $key=>$value) 
            if (property_exists ($this, $key)) 
                $this->$key = $value;

        $this->params = array_merge($this->params,$_params);
        $modx->event->_output = "";
        $this->DLTemplate = \DLTemplate::getInstance($this->modx);
        $this->fs = \Helpers\FS::getInstance();
        $this->assets = \AssetsHelper::getInstance($modx);
    }

	public function clearFolders($ids = array(), $folder) {
        foreach ($ids as $id) $this->fs->rmDir($folder.$id.'/');
    }

    public function checkPermissions() {
        $templates = isset($this->params['templates']) ? explode(',',$this->params['templates']) : false;
        $roles = isset($this->params['roles']) ? explode(',',$this->params['roles']) : false;

        $tplFlag = ($this->checkTemplate && !$templates || ($templates && !in_array($this->params['template'],$templates)));

        $documents = isset($this->params['documents']) ? explode(',',$this->params['documents']) : false;
        $docFlag = ($this->checkId && $tplFlag) ? !($documents && in_array($this->params['id'], $documents)) : $tplFlag;

        $ignoreDocs = isset($this->params['ignoreDoc']) ? explode(',',$this->params['ignoreDoc']) : false;
        $ignoreFlag = ($this->checkId && $ignoreDocs && in_array($this->params['id'], $ignoreDocs));

        return ($docFlag || $ignoreFlag || ($roles && !in_array($_SESSION['mgrRole'],$roles)));
    }

    /**
     * @return string
     */
    public function prerender() {
        if (!$this->checkTable()) {
            $result = $this->createTable();
            if (!$result) {
                $this->modx->logEvent(0, 3, "Cannot create {$this->table} table.", $this->pluginName);
                return;
            }
			$this->registerEvents($this->pluginEvents);
        }
        $output = '';
		$plugins = $this->modx->pluginEvent;
		if(($this->renderEvent!=='OnDocFormRender' || (array_search('ManagerManager', $plugins['OnDocFormRender']) === false))) {
			$jquery = $this->assets->registerScript('jQuery',array(
                'version' => '1.9.1',
                'src'     => 'assets/js/jquery/jquery-1.9.1.min.js'
            ));
            if ($jquery) {
                $output .= $jquery;
                $output .='<script type="text/javascript">var jQuery = jQuery.noConflict(true);</script>';    
            }
		}
		$tpl = MODX_BASE_PATH.$this->tpl;
		if($this->fs->checkFile($tpl)) {
			$output .= '[+js+][+styles+]'.file_get_contents($tpl);
		} else {
			$this->modx->logEvent(0, 3, "Cannot load {$this->tpl} .", $this->pluginName);
            return false;
		}
        return $output;
    }

    /**
     * @param $list
     * @param array $ph
     * @return string
     */
    public function renderJS($list,$ph = array()) {
    	$js = '';
    	$scripts = MODX_BASE_PATH.$list;
		if($this->fs->checkFile($scripts)) {
			$scripts = @file_get_contents($scripts);
			$scripts = $this->DLTemplate->parseChunk('@CODE:'.$scripts,$ph);
			$scripts = json_decode($scripts,true);
			$scripts = isset($scripts['scripts']) ? $scripts['scripts'] : $scripts['styles'];
			foreach ($scripts as $name => $params) {
				$script = $this->assets->registerScript($name,$params);
                if ($script) $js .= $script;
			}
		} else {
			if ($list == $this->jsListDefault) {
				$this->modx->logEvent(0, 3, "Cannot load {$this->jsListDefault} .", $this->pluginName);
			} elseif ($list == $this->cssListDefault) {
				$this->modx->logEvent(0, 3, "Cannot load {$this->cssListDefault} .", $this->pluginName);
			}
		}
		return $js;
    }

	/**
	 * @return array
	 */
	public function getTplPlaceholders() {
		$ph = array ();
		return $ph;
	}

    /**
     * @return string
     */
    public function render() {
		if (!$this->checkPermissions()) {
            $output = $this->prerender();
    		if ($output !== false) {
    			$ph = $this->getTplPlaceholders();
    			$ph['js'] = $this->renderJS($this->jsListDefault,$ph) . $this->renderJS($this->jsListCustom,$ph);
    			$ph['styles'] = $this->renderJS($this->cssListDefault,$ph) . $this->renderJS($this->cssListCustom,$ph);
    			$output = $this->DLTemplate->parseChunk('@CODE:'.$output,$ph);
    		}
    		return $output;
        }
    }

    /**
     * @return string
     */
    public function renderEmpty() {
        if (!$this->checkPermissions()) {
            $tpl = MODX_BASE_PATH.$this->emptyTpl;
            if($this->fs->checkFile($tpl)) {
                $output = '[+js+]'.file_get_contents($tpl);
                $ph = $this->getTplPlaceholders();
                $ph['js'] = $this->renderJS($this->jsListEmpty,$ph);
                $output = $this->DLTemplate->parseChunk('@CODE:'.$output,$ph);
                return $output;
            } else {
                $this->modx->logEvent(0, 3, "Cannot load {$this->emptyTpl} .", $this->pluginName);
            }
        }
    }

    /**
     * @return bool
     */
    public function checkTable() {
        $sql = "SHOW TABLES LIKE '{$this->_table}'";
        return $this->modx->db->getRecordCount( $this->modx->db->query($sql));
    }

    public function createTable() {
    	$sql = '';
    	return $this->modx->db->query($sql);
    }

	public function registerEvents($events = array(), $eventsType = '6') {
		$eventsTable = $this->modx->getFullTableName('system_eventnames');
		foreach ($events as $event) {
			$result = $this->modx->db->select('`id`',$eventsTable,"`name` = '{$event}'");
			if (!$this->modx->db->getRecordCount($result)) {
				$sql = "INSERT INTO {$eventsTable} VALUES (NULL, '{$event}', '{$eventsType}', '{$this->pluginName} Events')";
				if (!$this->modx->db->query($sql)) $this->modx->logEvent(0, 3, "Cannot register {$event} event.", $this->pluginName);
			}
		}
	}
}