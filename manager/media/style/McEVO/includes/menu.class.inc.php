<?php
/*
menu->Build('id','parent','name','link','alt','onclick','permission','target','divider 1/0','menuindex')
*/
class EVOmenu{
	var $defaults = array();
	var $menu;
	var $output;
	function Build($menu,$setting=array()){
		$this->defaults['outerClass']      = 'nav';
		$this->defaults['parentClass']     = 'dropdown';
		$this->defaults['parentLinkClass'] = 'dropdown-toggle';
		$this->defaults['parentLinkAttr']  = 'data-toggle="dropdown"';
		$this->defaults['parentLinkIn']    = '<b class="caret"></b>';
		$this->defaults['innerClass']      = 'subnav';
		
		$this->defaults = $this->defaults + $setting; 
		$this->Structurise($menu);
		$this->output = $this->DrawSub('main',0);
		echo $this->output;
	}
	
	function Structurise($menu){
		foreach ($menu as $key => $row) {
			$data[$key] = $row[9];
		}

		array_multisort($data,SORT_ASC, $menu);

		foreach($menu as $key=>$value){
			$new[$value[1]][] = $value;
		}
		
		$this->menu = $new;
	}
	

	function DrawSub($parentid,$level){
		global $modx;
		if (isset($this->menu[$parentid])){
			
			$countChild = 0;
			$itemTpl  = '<li id="[+id+]" class="[+li_class+]"><a href="[+href+]" alt="[+alt+]" target="[+target+]"
				          onclick="[+onclick+]" [+a_class+] [+LinkAttr+]>[+itemName+]</a>[+DrawSub+]</li>';
			$outerTpl = '<ul  id="[+id+]" class="[+class+]">[+output+]</ul>';
			foreach($this->menu[$parentid] as $key=>$value){
				if($value[6]!=='') {
					$permissions = explode(',',$value[6]);
					foreach($permissions as $val) {
						if(!$modx->hasPermission($val)) continue;
					}
				}
				
				$countChild++;
				$id = $value[0];
				$ph['id']       = $id;
				$ph['li_class'] = $this->get_li_class($id) . $value[10];
				$ph['href']     = $value[3];
				$ph['alt']      = $value[4];
				$ph['target']   = $value[7];
				$ph['onclick']  = $value[5];
				$ph['a_class']  = $this->get_a_class($id);
				$ph['LinkAttr'] = $this->getLinkAttr($id);
				$ph['itemName'] = $value[2] . $this->getItemName($id);
				
				if (isset($this->menu[$id])){
					$level++;
					$ph['DrawSub'] = $this->DrawSub($id , $level);
					$level--;
				}
				else $ph['DrawSub'] = '';
				
				$output .= $modx->parseText($itemTpl,$ph);
				if ($value[8]==1) $output .= '<li class="divider"></li>';
			}

			$ph = array();
			if ($countChild>0) {
				$ph['id']     = $level==0 ? $this->defaults['outerClass'] : '';
				$ph['class']  = $level==0 ? $this->defaults['outerClass'] : $this->defaults['innerClass'];
				$ph['output'] = $output;
				$output = $modx->parseText($outerTpl,$ph);
			}
		}
		return $output;
	}
	
	function get_li_class($id) {
		if(isset($this->menu[$id]))
			return $this->defaults['parentClass'] . ' ';
	}
	
	function get_a_class($id) {
		if(isset($this->menu[$id]))
			return 'class="' . $this->defaults['parentLinkClass'] . '"';
	}
	
	function getLinkAttr($id) {
		if(isset($this->menu[$id]))
			return $this->defaults['parentLinkAttr'];
	}
	
	function getItemName($id) {
		if(isset($this->menu[$id]))
			return $this->defaults['parentLinkIn'];
	}
}
