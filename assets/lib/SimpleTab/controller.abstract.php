<?php namespace SimpleTab;

require_once (MODX_BASE_PATH . 'assets/lib/Helpers/FS.php');

abstract class AbstractController {
    public $rfName = '';
    public $rid = 0;
    public $data = null;
    public $FS = null;
    public $isExit = false;
    public $output = null;
    public $params = null;
	public $fireEvents = true;
	
    public $dlParams = array(
        "controller"    =>  "onetable",
        "table"         =>  "",
        'idField'       =>  "",
        "api"           =>  1,
        "idType"        =>  "documents",
        'ignoreEmpty'   =>  1,
        'JSONformat'    =>  "new",
        'display'       =>  10,
        'offset'        =>  0,
        'sortBy'        =>  "",
        'sortDir'       =>  "desc",


    );

    protected $modx = null;

    public function __construct(\DocumentParser $modx){
        $this->FS = \Helpers\FS::getInstance();
        $this->modx = $modx;
        $this->params = $modx->event->params;
        $this->rid = isset($_REQUEST[$this->rfName]) ? (int)$_REQUEST[$this->rfName] : 0;
    }

    public function callExit(){
        if($this->isExit){
            echo $this->output;
            exit;
        }
    }

    public function remove()
    {
        $out = array();
        $ids = isset($_REQUEST['ids']) ? (string)$_REQUEST['ids'] : '';
        $ids = isset($_REQUEST['id']) ? (string)$_REQUEST['id'] : $ids;
        $out['success'] = false;
        if (!empty($ids)) {
            if ($this->data->deleteAll($ids, $this->rid)) {
                $out['success'] = true;
            }
        }
        return $out;
    }

    public function place()
    {
        $out = array();
        $ids = isset($_REQUEST['ids']) ? (string)$_REQUEST['ids'] : '';
        $dir = isset($_REQUEST['dir']) ? $_REQUEST['dir'] : 'top';
        $out['success'] = false;
        if (!empty($ids)) {
            if ($this->data->place($ids, $dir, $this->rid)) {
                $out['success'] = true;
            }
        }
        return $out;
    }

    public function reorder() 
    {
        $out = array();
        $source = $_REQUEST['source'];
        $target = $_REQUEST['target'];
        $point = $_REQUEST['point'];
        $orderDir = $_REQUEST['orderDir'];
        $rows = $this->data->reorder($source, $target, $point, $this->rid, $orderDir);

        if ($rows) {
            $out['success'] = true;
        } else {
            $out['success'] = false;
        }
        return $out;
    }

    public function listing() {
        if (!$this->rid) {
            $this->isExit = true;
            return;
        }
        return $this->modx->runSnippet("DocLister", $this->dlParams);
    }

    public function dlInit() {
        $this->dlParams['table'] = $this->data->tableName();
        $this->dlParams['idField'] = $this->data->fieldPKName();
        $this->dlParams['addWhereList'] = "`{$this->rfName}`={$this->rid}";
        if (isset($_REQUEST['rows'])) $this->dlParams['display'] = (int)$_REQUEST['rows'];
        $offset = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
        $offset = $offset ? $offset : 1;
        $offset = $this->dlParams['display']*abs($offset-1);
        $this->dlParams['offset'] = $offset;
        if(isset($_REQUEST['sort'])){
            $this->dlParams['sortBy'] = preg_replace('/[^A-Za-z0-9_\-]/', '', $_REQUEST['sort']);
        }
        if(isset($_REQUEST['order']) && in_array(strtoupper($_REQUEST['order']), array("ASC","DESC"))){
            $param['sortDir'] = $_REQUEST['order'];
        }
        foreach ($this->dlParams as &$param) {
            if (empty($param)) unset($param);
        }
    }

    public function getLanguageCode() {
        $manager_language = $this->modx->config['manager_language'];
        if(file_exists(MODX_MANAGER_PATH."includes/lang/".$manager_language.".inc.php")) {
            include_once MODX_MANAGER_PATH."includes/lang/".$manager_language.".inc.php";
        }
        return $modx_lang_attribute;
    }
}