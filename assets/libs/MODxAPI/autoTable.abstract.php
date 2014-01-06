<?php
require_once('MODx.php');

abstract class autoTable extends MODxAPI{
    protected $table = null;
    protected $pkName = null;

    public function __construct($modx){
        parent::__construct($modx);
        $data = $this->modx->db->getTableMetaData($this->makeTable($this->table));
        foreach($data as $item){
            if(empty($this->pkName) && $item['Key']=='PRI'){
                $this->pkName = $item['Field'];
            }
            if($this->pkName != $item['Field']){
                $this->default_field[$item['Field']] = $item['Default'];
            }
        }
    }

    public function edit($id){
        $this->newDoc = false;
        $this->id = $id;
        $this->field=array();
        $this->set=array();

        $result = $this->query("SELECT * from {$this->makeTable($this->table)} where ".$this->pkName."=".(int)$id);
        $this->fromArray($this->modx->db->getRow($result));
        unset($this->field[$this->pkName]);
        return $this;
    }

    public function save($fire_events = null,$clearCache = false){
        $fld = $this->toArray();

        foreach($this->default_field as $key=>$value){
            if ($this->newDoc && $this->get($key) == '' && $this->get($key)!==$value){
                $this->set($key,$value);
            }
            $this->Uset($key);
            unset($fld[$key]);
        }
        if (!empty($this->set)){
            if($this->newDoc){
                $SQL = "INSERT into {$this->makeTable($this->table)} SET ".implode(', ', $this->set);
            }else{
                $SQL = "UPDATE {$this->makeTable($this->table)} SET ".implode(', ', $this->set)." WHERE ".$this->pkName." = ".$this->id;
            }
            $this->query($SQL);
        }

        if($this->newDoc) $this->id = $this->modx->db->getInsertId();
        if($clearCache){
            $this->clearCache($fire_events);
        }
        return $this->id;
    }
    public function delete($ids,$fire_events = null){
        $_ids = $this->cleanIDs($ids, ',');
        try{
            if(is_array($_ids) && $_ids!=array()){
                $id = $this->sanitarIn($_ids);
                $this->query("DELETE from {$this->makeTable($this->table)} where ".$this->pkName." IN ({$id})");
                $this->clearCache($fire_events);
            } else throw new Exception('Invalid IDs list for delete: <pre>'.print_r($ids,1).'</pre>');
        }catch(Exception $e){ die($e->getMessage()); }
        return $this;
    }
}