<?php
require_once('MODx.php');

abstract class autoTable extends MODxAPI
{
    protected $table = null;
    protected $generateField = false;

    public function tableName(){
        return $this->table;
    }
    public function __construct($modx, $debug = false)
    {
        parent::__construct($modx, $debug);
        if(empty($this->default_field)){
            $data = $this->modx->db->getTableMetaData($this->makeTable($this->table));
            foreach ($data as $item) {
                if (empty($this->pkName) && $item['Key'] == 'PRI') {
                    $this->pkName = $item['Field'];
                }
                if ($this->pkName != $item['Field']) {
                    $this->default_field[$item['Field']] = $item['Default'];
                }
            }
            $this->generateField = true;
        }
    }

    public function edit($id)
    {
        $id = is_scalar($id) ? trim($id) : '';
        if ($this->getID() != $id) {
            $this->newDoc = false;
            $this->id = null;
            $this->markAllEncode();
            $this->field = array();
            $this->set = array();
            $result = $this->query("SELECT * from {$this->makeTable($this->table)} where `" . $this->pkName . "`='" . $this->escape($id)."'");
            $this->fromArray($this->modx->db->getRow($result));
            $this->id = $this->eraseField($this->pkName);
            if(is_bool($this->id) && $this->id === false){
                $this->id = null;
            }else{
                $this->decodeFields();
            }
        }
        return $this;
    }

    public function save($fire_events = null, $clearCache = false)
    {
        $result = false;
        $fld = $this->encodeFields()->toArray();
        foreach ($this->default_field as $key => $value) {
            if ($this->newDoc && $this->get($key) === null && $this->get($key) !== $value) {
                $this->set($key, $value);
            }
            if((!$this->generateField || isset($fld[$key])) && $this->get($key) !== null){
                $this->Uset($key);
            }
            unset($fld[$key]);
        }
        if (!empty($this->set)) {
            if ($this->newDoc) {
                $SQL = "INSERT {$this->ignoreError} INTO {$this->makeTable($this->table)} SET " . implode(', ', $this->set);
            } else {
                $SQL = ($this->getID() === null) ? null : "UPDATE {$this->ignoreError} {$this->makeTable($this->table)} SET " . implode(', ', $this->set) . " WHERE `" . $this->pkName . "` = " . $this->getID();
            }
            $result = $this->query($SQL);
        }
			if($result && $this->modx->db->getAffectedRows() >= 0 ){
				if ($this->newDoc && !empty($SQL)) $this->id = $this->modx->db->getInsertId();
				if ($clearCache) {
					$this->clearCache($fire_events);
				}
				$result = $this->id;
			}else{
				$this->log['SqlError'] = $SQL;
				$result = false;
			}
        return $result;
    }

    public function delete($ids, $fire_events = null)
    {
        $_ids = $this->cleanIDs($ids, ',');
        try {
            if (is_array($_ids) && $_ids != array()) {
                $id = $this->sanitarIn($_ids);
                if(!empty($id)){
                    $this->query("DELETE from {$this->makeTable($this->table)} where `" . $this->pkName . "` IN ({$id})");
                }
                $this->clearCache($fire_events);
            } else throw new Exception('Invalid IDs list for delete: <pre>' . print_r($ids, 1) . '</pre>');
        } catch (Exception $e) {
            die($e->getMessage());
        }
        return $this;
    }
}