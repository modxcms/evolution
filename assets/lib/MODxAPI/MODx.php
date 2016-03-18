<?php
include_once(MODX_BASE_PATH.'assets/lib/APIHelpers.class.php');
include_once(MODX_BASE_PATH.'assets/snippets/DocLister/lib/jsonHelper.class.php');
include_once(MODX_BASE_PATH.'assets/snippets/DocLister/lib/DLCollection.class.php');

abstract class MODxAPI extends MODxAPIhelpers
{
    protected $modx = null;
    protected $log = array();
    protected $field = array();
    protected $default_field = array();
    protected $id = null;
    protected $set = array();
    protected $newDoc = false;
    protected $pkName = 'id';
	protected $ignoreError = '';
    protected $_debug = false;
    protected $_query = array();
    protected $jsonFields = array();
	/**
	 * @var DLCollection
	 */
    private $_decodedFields;
	private $_table = array();

    public function __construct(DocumentParser $modx, $debug = false)
    {	
    	$this->modx = $modx;
        if(function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()){
        	throw new Exception('Magic Quotes is a deprecated and mostly useless setting that should be disabled. Please ask your server administrator to disable it in php.ini or in your webserver config.');
		}

		$this->setDebug($debug);
        $this->_decodedFields = new DLCollection($this->modx);
    }

    public function setDebug($flag){
        $this->_debug = (bool)$flag;
        return $this;
    }
    public function getDebug(){
        return $this->_debug;
    }
    public function getDefaultFields(){
        return $this->default_field;
    }
    final public function modxConfig($name, $default = null)
    {
        return APIHelpers::getkey($this->modx->config, $name, $default);
    }
    public function addQuery($q){
        if(is_scalar($q) && !empty($q)){
            $this->_query[] = $q;
        }
        return $this;
    }
    public function getQueryList(){
        return $this->_query;
    }
    final public function query($SQL)
    {
        if($this->getDebug()){
            $this->addQuery($SQL);
        }
        return empty($SQL) ? null : $this->modx->db->query($SQL);
    }
    final public function escape($value){
        if(!is_scalar($value)){
            $value = '';
        }else{
            $value = $this->modx->db->escape($value);
        }
        return $value;
     }
    final public function invokeEvent($name, $data = array(), $flag = false)
    {
        $flag = (isset($flag) && $flag != '') ? (bool)$flag : false;
        if ($flag) {
            $this->modx->invokeEvent($name, $data);
        }
        return $this;
    }

    final public function clearLog()
    {
        $this->log = array();
        return $this;
    }

    final public function getLog()
    {
        return $this->log;
    }

    final public function list_log($flush = false)
    {
        echo '<pre>' . print_r(APIHelpers::sanitarTag($this->log), true) . '</pre>';
        if ($flush) $this->clearLog();
        return $this;
    }

    final public function getCachePath($full = true)
    {
        $path = $this->modx->getCachePath();
        if ($full) {
            $path = MODX_BASE_PATH . substr($path, strlen(MODX_BASE_URL));
        }
        return $path;
    }

    final public function clearCache($fire_events = null, $custom = false)
    {
		$IDs = array();
		if($custom === false) {
			$this->modx->clearCache();
			include_once(MODX_MANAGER_PATH . 'processors/cache_sync.class.processor.php');
			$sync = new synccache();
			$path = $this->getCachePath(true);
			$sync->setCachepath($path);
			$sync->setReport(false);
			$sync->emptyCache();
		}else {
			if(is_scalar($custom)){
				$custom = array($custom);
			}
			switch ($this->modx->config['cache_type']) {
				case 2:
					$cacheFile = "_*.pageCache.php";
					break;
				default:
					$cacheFile = ".pageCache.php";
			}
			if(is_array($custom)) {
				foreach($custom as $id) {
					$tmp = glob(MODX_BASE_PATH."assets/cache/docid_" . $id . $cacheFile);
					foreach($tmp as $file){
						if(is_readable($file)){
							unlink($file);
						}
						$IDs[] = $id;
					}
				}
			}
			clearstatcache();
		}
        $this->invokeEvent('OnSiteRefresh', array('IDs' => $IDs), $fire_events);
    }
	public function switchObject($id){
        switch(true){
            //Если загружен другой объект - не тот, с которым мы хотим временно поработать
            case ($this->getID() != $id && $id):
                $obj = clone $this;
                $obj->edit($id);
                break;
            //Если уже загружен объект, с которым мы хотим временно поработать
            case ($this->getID() == $id && $id):
            //Если $id не указан, но уже загружен какой-то объект
            case (!$id && $this->getID()):
            default:
                $obj = $this;
                break;
        }
        return $obj;
    }
	public function useIgnore($flag = true){
		$this->ignoreError = $flag ? 'IGNORE' : '';
		return $this;
	}
	public function hasIgnore(){
		return (bool)$this->ignoreError;
	}

    public function set($key, $value)
    {
        if ((is_scalar($value) || $this->isJsonField($key)) && is_scalar($key) && !empty($key)) {
            $this->field[$key] = $value;
        }
        return $this;
    }

    final public function getID()
    {
        return $this->id;
    }

    public function get($key)
    {
        return APIHelpers::getkey($this->field, $key, null);
    }

    public function fromArray($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->set($key, $value);
            }
        }
        return $this;
    }

    final protected function Uset($key, $id = '')
    {
        if (!isset($this->field[$key])) {
            $tmp = "`{$key}`=''";
            $this->log[] = "{$key} is empty";
        } else {
            if ($this->issetField($key) && is_scalar($this->field[$key])) {
            	$tmp = "`{$key}`='{$this->escape($this->field[$key])}'";
			} else throw new Exception("{$key} is invalid <pre>" . print_r($this->field[$key], true) . "</pre>");
        }
        if (!empty($tmp)) {
            if ($id == '') {
                $this->set[] = $tmp;
            } else {
                $this->set[$id][] = $tmp;
            }
        }
        return $this;
    }


    final public function cleanIDs($IDs, $sep = ',', $ignore = array())
    {
        $out = array();
        if (!is_array($IDs)) {
            if (is_scalar($IDs)) {
            	$IDs = explode($sep, $IDs);
			} else {
            	$IDs = array();
                throw new Exception('Invalid IDs list <pre>' . print_r($IDs, 1) . '</pre>');
			}
        }
        foreach ($IDs as $item) {
            $item = trim($item);
            if (is_scalar($item) && (int)$item >= 0) { //Fix 0xfffffffff
                if (!empty($ignore) && in_array((int)$item, $ignore, true)) {
                    $this->log[] = 'Ignore id ' . (int)$item;
                } else {
                    $out[] = (int)$item;
                }
            }
        }
        $out = array_unique($out);
        return $out;
    }

    final public function fromJson($data, $callback = null)
    {
        if (is_scalar($data) && !empty($data)) {
        	$json = json_decode($data);
		} else throw new Exception("json is not string with json data");

		if ($this->jsonError($json)) {
        	if (isset($callback) && is_callable($callback)) {
            	call_user_func_array($callback, array($json));
			} else {
            	if (isset($callback)) throw new Exception("Can't call callback JSON unpack <pre>" . print_r($callback, 1) . "</pre>");
                foreach ($json as $key => $val) {
                	$this->set($key, $val);
				}
			}
		} else throw new Exception('Error from JSON decode: <pre>' . print_r($data, 1) . '</pre>');

		return $this;
    }

    final public function toJson($callback = null)
    {
        $data = $this->toArray();
        if (isset($callback) && is_callable($callback)) {
        	$data = call_user_func_array($callback, array($data));
		} else {
        	if (isset($callback)) throw new Exception("Can't call callback JSON pre pack <pre>" . print_r($callback, 1) . "</pre>");
		}
        $json = json_encode($data);

		if ($this->jsonError($data)) {
        	throw new Exception('Error from JSON decode: <pre>' . print_r($data, 1) . '</pre>');
		}

		return $json;
    }

    final protected function jsonError($data)
    {
        $flag = false;
        if (json_last_error() === JSON_ERROR_NONE && is_object($data) && $data instanceof stdClass) {
            $flag = true;
        }
        return $flag;
    }

    public function toArray($prefix = '', $suffix = '', $sep = '_')
    {
        $tpl = '';
        $plh = '[+key+]';
        if ($prefix !== '') {
            $tpl = $prefix . $sep;
        }
        $tpl .= $plh;
        if ($suffix !== '') {
            $tpl .= $sep . $suffix;
        }
        $out = array();
        $fields = $this->field;
        $fields[$this->fieldPKName()] = $this->getID();
        if ($tpl != $plh) {
            foreach ($fields as $key => $value) {
                $out[str_replace($plh, $key, $tpl)] = $value;
            }
        } else {
            $out = $fields;
        }
        return $out;
    }
    final public function fieldPKName(){
        return $this->pkName;
    }
    final public function makeTable($table)
    {
        //Без использования APIHelpers::getkey(). Иначе getFullTableName будет всегда выполняться
        return (isset($this->_table[$table])) ? $this->_table[$table] : $this->modx->getFullTableName($table);
    }

    final public function sanitarIn($data, $sep = ',')
    {
        if (!is_array($data)) {
            $data = explode($sep, $data);
        }
        $out = array();
        foreach ($data as $item) {
            if($item !== ''){
                $out[] = $this->escape($item);
            }
        }
        $out = empty($out) ? '' : "'" . implode("','", $out) . "'";
        return $out;
    }

    public function checkUnique($table, $field, $PK = 'id')
    {
        if (is_array($field)) {
            $where = array();
            foreach ($field as $_field) {
                $val = $this->get($_field);
                if ($val != '')
                    $where[] = "`".$this->escape($_field)."` = '".$this->escape($val)."'";
            }
            $where = implode(' AND ',$where);
        } else {
            $where = '';
            $val = $this->get($field);
            if ($val != '')
                $where = "`".$this->escape($field)."` = '".$this->escape($val)."'";
        }
        
        if ($where != '') {
            $sql = $this->query("SELECT `" . $this->escape($PK) . "` FROM " . $this->makeTable($table) . " WHERE ".$where);
            $id = $this->modx->db->getValue($sql);
            if (is_null($id) || (!$this->newDoc && $id == $this->getID())) {
                $flag = true;
            } else {
                $flag = false;
            }
        } else {
            $flag = false;
        }
        return $flag;
    }

    public function create($data = array())
    {
        $this->close();
        $this->fromArray($data);
        return $this;
    }

    public function copy($id)
    {
        $this->edit($id)->id = 0;
        $this->newDoc = true;
        return $this;
    }

    public function close()
    {
        $this->newDoc = true;
        $this->id = null;
        $this->field = array();
        $this->set = array();
        $this->markAllDecode();
    }

    public function issetField($key)
    {
        return (is_scalar($key) && array_key_exists($key, $this->default_field));
    }

    abstract public function edit($id);

    abstract public function save($fire_events = null, $clearCache = false);

    abstract public function delete($ids, $fire_events = null);

    final public function sanitarTag($data)
    {
        return parent::sanitarTag($this->modx->stripTags($data));
    }

    final protected function checkVersion($version, $dmi3yy = true)
    {
        $flag = false;
        $currentVer = $this->modx->getVersionData('version');
        if (is_array($currentVer)) {
            $currentVer = APIHelpers::getkey($currentVer, 'version', '');
        }
        $tmp = substr($currentVer, 0, strlen($version));
        if (version_compare($tmp, $version, '>=')) {
            $flag = true;
            if ($dmi3yy) {
                $flag = (boolean)preg_match('/^' . $tmp . '(.*)\-d/', $currentVer);
            }
        }
        return $flag;
    }

    protected function eraseField($name)
    {
        $flag = false;
        if (array_key_exists($name, $this->field)) {
            $flag = $this->field[$name];
            unset($this->field[$name]);
        }
        return $flag;
    }

    /**
     * Может ли содержать данное поле json массив
     * @param  string $field имя поля
     * @return boolean
     */
    public function isJsonField($field){
        return (is_scalar($field) && in_array($field, $this->jsonFields));
    }

    /**
     * Пометить поле как распакованное
     * @param  string $field имя поля
     * @return $this
     */
    public function markAsDecode($field){
        if(is_scalar($field)){
            $this->_decodedFields->set($field, false);
        }
        return $this;
    }

    /**
     * Пометить поле как запакованное
     * @param  string $field имя поля
     * @return $this
     */
    public function markAsEncode($field){
        if(is_scalar($field)){
            $this->_decodedFields->set($field, true);
        }
        return $this;
    }

    /**
     * Пометить все поля как запакованные
     * @return $this
     */
    public function markAllEncode(){
        $this->_decodedFields->clear();
        foreach($this->jsonFields as $field){
            $this->markAsEncode($field);
        }
        return $this;
    }

    /**
     * Пометить все поля как распакованные
     * @return $this
     */
    public function markAllDecode(){
        $this->_decodedFields->clear();
        foreach($this->jsonFields as $field){
            $this->markAsDecode($field);
        }
        return $this;
    }

    /**
     * Получить список не запакованных полей
     * @return array
     */
    public function getNoEncodeFields(){
        return $this->_decodedFields->filter(function($value){
            return ($value === false);
        });
    }

    /**
     * Получить список не распакованных полей
     * @return array
     */
    public function getNoDecodeFields(){
       return $this->_decodedFields->filter(function($value){
            return ($value === true);
        });
    }

    /**
     * Можно ли данное декодировать с помощью json_decode
     * @param  string $field имя поля
     * @return boolean
     */
    public function isDecodableField($field){
        $data = $this->get($field);
        /**
         * Если поле скалярного типа и оно не распаковывалось раньше
         */
        return (is_scalar($data) && is_scalar($field) && $this->_decodedFields->get($field)===true);
    }

    /**
     * Можно ли закодировать данные с помощью json_encode
     * @param  string  $field имя поля
     * @return boolean
     */
    public function isEncodableField($field){
        /**
         * Если поле было распаковано ранее и еще не упаковано
         */
        return (is_scalar($field) && $this->_decodedFields->get($field)===false);
    }

    /**
     * Декодирует конкретное поле
     * @param  string $field Имя поля
     * @param  bool $store обновить распакованное поле
     * @return array ассоциативный массив с данными из json строки
     */
    public function decodeField($field, $store = false){
        $out = array();
        if($this->isDecodableField($field)){
            $data = $this->get($field);
            $out = jsonHelper::jsonDecode($data, array('assoc' => true), true);
        }
        if($store){
            $this->field[$field] = $out;
            $this->markAsDecode($field);
        }
        return $out;
    }

    /**
     * Декодирование всех json полей
     * @return $this
     */
    protected function decodeFields(){
        foreach($this->getNoDecodeFields() as $field => $flag){
            $this->decodeField($field, true);
        }
        return $this;
    }

    /**
     * Запаковывает конкретное поле в JSON
     * @param  string $field Имя поля
     * @param  bool $store обновить запакованное поле
     * @return array json строка
     */
    public function encodeField($field, $store = false){
        $out = null;
        if($this->isEncodableField($field)){
            $data = $this->get($field);
            $out = json_encode($data);
        }
        if($store){
            $this->field[$field] = $out;
            $this->markAsEncode($field);
        }
        return $out;
    }

    /**
     * Запаковка всех json полей
     * @return $this
     */
    protected function encodeFields(){
        foreach($this->getNoEncodeFields() as $field => $flag){
            $this->encodeField($field, true);
        }
        return $this;
    }
}

class MODxAPIhelpers
{
    public function emailValidate($email, $dns = true)
    {
        return \APIhelpers::emailValidate($email, $dns);
    }
    public function genPass($len, $data = '')
    {
        return \APIhelpers::genPass($len, $data);
    }
    public function getUserIP($out = '127.0.0.1')
    {
        return \APIhelpers::getUserIP($out);
    }

    public function sanitarTag($data)
    {
        return \APIhelpers::sanitarTag($data);
    }

    public function checkString($value, $minLen = 1, $alph = array(), $mixArray = array())
    {
        return \APIhelpers::checkString($value, $minLen, $alph, $mixArray);
    }
}
