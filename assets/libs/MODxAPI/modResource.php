<?php
require_once('MODx.php'); 

class modResource extends MODxAPI{
	protected $default_field = array(
			'type'=>'document',
			'contentType'=>'text/html',
			'pagetitle'=>'New document',
			'longtitle'=>'',
			'description'=>'',
			'alias'=>'',
			'link_attributes'=>'',
			'published'=>'1',
			'pub_date'=>'0',
			'unpub_date'=>'0',
			'parent'=>'0',
			'isfolder'=>'0',
			'introtext'=>'',
			'content'=>'',
			'richtext'=>'1',
			'template'=>'0',
			'menuindex'=>'0',
			'searchable'=>'1',
			'cacheable'=>'1',
			'createdon'=>'0',
			'createdby'=>'0',
			'editedon'=>'0',
			'editedby'=>'0',
			'deleted'=>'0',
			'deletedon'=>'0',
			'deletedby'=>'0',
			'publishedon'=>'0',
			'publishedby'=>'0',
			'menutitle'=>'',
			'donthit'=>'0',
			'haskeywords'=>'0',
			'hasmetatags'=>'0',
			'privateweb'=>'0',
			'privatemgr'=>'0',
			'content_dispo'=>'0',
			'hidemenu'=>'0',
			'alias_visible'=>'1'
		);
	private $table=array('"'=>'_',"'"=>'_',' '=>'_','.'=>'_',','=>'_','а'=>'a','б'=>'b','в'=>'v',
		'г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'zh','з'=>'z','и'=>'i','й'=>'y','к'=>'k',
		'л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u',
		'ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'sch','ь'=>'','ы'=>'y','ъ'=>'',
		'э'=>'e','ю'=>'yu','я'=>'ya','А'=>'A','Б'=>'B','В'=>'V','Г'=>'G','Д'=>'D','Е'=>'E',
		'Ё'=>'E','Ж'=>'Zh','З'=>'Z','И'=>'I','Й'=>'Y','К'=>'K','Л'=>'L','М'=>'M','Н'=>'N',
		'О'=>'O','П'=>'P','Р'=>'R','С'=>'S','Т'=>'T','У'=>'U','Ф'=>'F','Х'=>'H','Ц'=>'C',
		'Ч'=>'Ch','Ш'=>'Sh','Щ'=>'Sch','Ь'=>'','Ы'=>'Y','Ъ'=>'','Э'=>'E','Ю'=>'Yu','Я'=>'Ya',
	);
	private $tv = array();
	private $tvid = array();

	public function __construct($modx){
		parent::__construct($modx);
		$this->get_TV();
	}
	public function set($key,$value){
		if(is_scalar($value) && is_scalar($key) && !empty($key)){
			switch($key){
				case 'template': {
					$value = trim($value);
					$value = $this->setTemplate($value);
					break;
				}
			}
			$this->field[$key] = $value;
		}
		return $this;
	}
	
	public function create($data=array()){
		parent::create($data);
		if($this->newDoc){
			$this->set('createdon',time());
		}
		return $this;
	}
	public function edit($id){
		$this->close();
        $this->newDoc = false;
		
		$result = $this->query("SELECT * from {$this->makeTable('site_content')} where id=".(int)$id);
		$this->fromArray($this->modx->db->getRow($result));
		$result = $this->query("SELECT * from {$this->makeTable('site_tmplvar_contentvalues')} where contentid=".(int)$id);
		while ($row = $this->modx->db->getRow($result)){
			$this->field[$this->tvid[$row['tmplvarid']]]=$row['value'];
		}
		if(empty($this->field['id'])){
			$this->id = null;
		}
		unset($this->field['id']);
		return $this;
	}
	public function save($fire_events = null,$clearCache = false){
		if ($this->field['pagetitle'] == '') {
			$this->log['emptyPagetitle'] =  'Pagetitle is empty in <pre>'.print_r($this->field,true).'</pre>';
			return false;
		}

		$this->set('alias',$this->getAlias());

		$this->invokeEvent('OnBeforeDocFormSave',array (
			"mode" => $this->newDoc ? "new" : "upd",
			"id" => $this->id ? $this->id : ''
		),$fire_events);
		
		$fld = $this->toArray();
		
		foreach($this->default_field as $key=>$value){
			if ($this->newDoc && $this->get($key) == '' && $this->get($key)!==$value){
				switch($key){
					case 'cacheable':{
						$value = $this->modxConfig('cache_default');
						break;
					}
					case 'template':{
						$value = $value = $this->modxConfig('default_template');
						break;
					}
					case 'published':{
						$value = $this->modxConfig('publish_default');
						break;
					}
					case 'searchable':{
						$value = $this->modxConfig('search_default');
						break;
					}
					case 'donthit':{
						$value = $this->modxConfig('track_visitors');
						break;
					}
				}
				$this->set($key,$value);
			}
            if($key == 'alias_visible' && !$this->checkVersion('1.0.10',true)){
                $this->eraseField('alias_visible');
            }else{
			    $this->Uset($key);
            }
			unset($fld[$key]);
		}
		
		if (!empty($this->set)){
			if($this->newDoc){
				$SQL = "INSERT into {$this->makeTable('site_content')} SET ".implode(', ', $this->set);
			}else{
				$SQL = "UPDATE {$this->makeTable('site_content')} SET ".implode(', ', $this->set)." WHERE id = ".$this->id;
			}
			$this->query($SQL);
		}
		
		if($this->newDoc) {
			$this->id = $this->modx->db->getInsertId();
		}
		
		foreach($fld as $key=>$value){
			if(empty($this->tv[$key])) continue;
            if($value === ''){
                $result = $this->query("DELETE FROM {$this->makeTable('site_tmplvar_contentvalues')} WHERE `contentid` = '{$this->id}' AND `tmplvarid` = '{$this->tv[$key]}'");
            }else{
                $result = $this->query("SELECT `value` FROM {$this->makeTable('site_tmplvar_contentvalues')} WHERE `contentid` = '{$this->id}' AND `tmplvarid` = '{$this->tv[$key]}'");
                if($this->modx->db->getRecordCount($result)>0){
                    $result = $this->query("UPDATE {$this->makeTable('site_tmplvar_contentvalues')} SET `value` = '{$value}' WHERE `contentid` = '{$this->id}' AND `tmplvarid` = '{$this->tv[$key]}';");
                }else{
                    $result = $this->query("INSERT into {$this->makeTable('site_tmplvar_contentvalues')} SET `contentid` = {$this->id},`tmplvarid` = {$this->tv[$key]},`value` = '{$value}';");
                }
            }
		}
		
		$this->invokeEvent('OnDocFormSave',array (
			"mode" => $this->newDoc ? "new" : "upd",
			"id" => $this->id
		),$fire_events);
		
		if($clearCache){ 
			$this->clearCache($fire_events); 
		}
		return $this->id;
	}
	
	public function delete($ids,$fire_events = null){
		//@TODO: delete with SET deleted=1
		$ignore = $this->systemID();
		$_ids = $this->cleanIDs($ids, ',', $ignore);
		try{
			if(is_array($_ids) && $_ids!=array()){
				$this->invokeEvent('OnBeforeEmptyTrash',array(
					"ids"=>$_ids
				),$fire_events);
		
				$id = $this->sanitarIn($_ids);
				$this->query("DELETE from {$this->makeTable('site_content')} where id IN ({$id})");
				$this->query("DELETE from {$this->makeTable('site_tmplvar_contentvalues')} where contentid IN ({$id})");
				
				$this->invokeEvent('OnEmptyTrash',array(
					"ids"=>$_ids
				),$fire_events);
			} else throw new Exception('Invalid IDs list for delete: <pre>'.print_r($ids,1).'</pre> please, check ignore list: <pre>'.print_r($ignore,1).'</pre>');
		}catch(Exception $e){ die($e->getMessage()); }
		
		return $this;
	}
	
	private function systemID(){
		$ignore = array(
			0, //empty document
			(int)$this->modx->config['site_start'],
			(int)$this->modx->config['error_page'],
			(int)$this->modx->config['unauthorized_page'],
			(int)$this->modx->config['site_unavailable_page']
		);
		$data = $this->query("SELECT DISTINCT setting_value FROM {$this->makeTable('web_user_settings')} WHERE setting_name='login_home' AND setting_value!=''");
		$data = $this->modx->db->makeArray($data);
		foreach($data as $item){
			$ignore[]=(int)$item['setting_value'];
		}
		return array_unique($ignore);
		
	}
	private function checkAlias($alias){
		$alias = strtolower($alias);
		if($this->modxConfig('friendly_urls')){
			$flag = false;
			$_alias = $this->modx->db->escape($alias);
			if((!$this->modxConfig('allow_duplicate_alias') && !$this->modxConfig('use_alias_path')) || ($this->modxConfig('allow_duplicate_alias') && $this->modxConfig('use_alias_path'))){
				$flag = $this->modx->db->getValue($this->query("SELECT id FROM {$this->makeTable('site_content')} WHERE alias='{$_alias}' AND parent={$this->get('parent')} LIMIT 1"));
			} else {
				$flag = $this->modx->db->getValue($this->query("SELECT id FROM {$this->makeTable('site_content')} WHERE alias='{$_alias}' LIMIT 1"));
			}
			if(($flag && $this->newDoc) || (!$this->newDoc && $flag && $this->id != $flag)){
				$suffix = substr($alias, -2);
				if(preg_match('/-(\d+)/',$suffix,$tmp) && isset($tmp[1]) && (int)$tmp[1]>1){
					$suffix = (int)$tmp[1] + 1;
					$alias = substr($alias, 0, -2) . '-'. $suffix;
				}else{
					$alias .= '-2';
				}
				$alias = $this->checkAlias($alias);
			}
		}
		return $alias;
	}
	public function issetField($key){
        return (isset($this->default_field[$key]) || isset($this->tv[$key]));
    }
	public function get_TV(){
		$result = $this->query('SELECT id,name FROM '.$this->makeTable('site_tmplvars'));
		while($row = $this->modx->db->GetRow($result)) {
			$this->tv[$row['name']] = $row['id'];
			$this->tvid[$row['id']] = $row['name'];
		}
	}

	private function setTemplate($tpl) {
		if(!is_numeric($tpl) || $tpl != (int) $tpl) {
			try{
				if(is_scalar($tpl)){
					$sql = "SELECT id FROM {$this->makeTable('site_templates')} WHERE templatename = '{$tpl}'";
					$rs = $this->query($sql);
					if(!$rs || $this->modx->db->getRecordCount($rs) <= 0) throw new Exception("Template {$tpl} is not exists");
					$tpl = $this->modx->db->getValue($rs);
				} else throw new Exception("Invalid template name: ".print_r($tpl,1));
			}catch(Exception $e){
				$tpl = 0;
				die($e->getMessage()); 
			}
		}
		return (int)$tpl;
	}
	
	private function getAlias(){
		if ($this->modx->config['friendly_urls'] && $this->modx->config['automatic_alias'] && $this->get('alias') == ''){
			$alias = strtr($this->get('pagetitle'), $this->table);
		}else{
			if($this->get('alias')!=''){
				$alias = $this->get('alias');
			}else{
				$alias = '';
			}
		}
		$alias = $this->modx->stripAlias($alias);
		return $this->checkAlias($alias);
	}
}