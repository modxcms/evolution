<?php
/*********************************
RandomLib Version 1.0
Programmed by : Chao Xu(Mgccl)
E-mail        : mgcclx@gmail.com
Website       : http://www.webdevlogs.com
Info          : Please email me if there is any feature you want
or there is any bugs. I will fix them as soon as possible.
*********************************/
	
class random{
var $data = array();
function add($string,$weight=1){
	$this->data[] = array('s' => $string, 'w' => $weight);
}
function optimize(){
	foreach($this->data as $var){
		if($new[$var['s']]){
			$new[$var['s']] += $var['w'];
		}else{
			$new[$var['s']] = $var['w'];
		}
	}
	unset($this->data);
	foreach($new as $key=>$var){
		$this->data[] = array('s' => $key, 'w' => $var);
	}
}

function select($amount=1){
	if($amount == 1){
		$rand = array_rand($this->data);
		$result = $this->data[$rand]['s'];
	}else{
		$i = 0;
		while($i<$amount){
			$result[] = $this->data[array_rand($this->data)]['s'];
			++$i;
		}
	}
	return $result;
}

function select_unique($amount=1){
	if($amount == 1){
		$rand = array_rand($this->data);
		$result = $this->data[$rand]['s'];
	}else{
		$rand = array_rand($this->data, $amount);
		foreach($rand as $var){
			$result[] = $this->data[$var]['s'];
		}
	}
	return $result;
}

function select_weighted($amount=1){
	$count = count($this->data);
	$i = 0;
	$max = -1;
	while($i < $count){
		$max += $this->data[$i]['w'];
		++$i;
	}
	if(1 == $amount){
		$rand = mt_rand(0, $max);
		$w = 0; $n = 0;
		while($w <= $rand){
			$w += $this->data[$n]['w'];
			++$n;
		}
		$key = $this->data[$n-1]['s'];
	}else{
		$i = 0;
		while($i<$amount){
			$random[] = mt_rand(0, $max);
			++$i;
		}
		sort($random);
		$i = 0;
		$n = 0;
		$w = 0;
		while($i<$amount){
			while($w<=$random[$i]){
				$w += $this->data[$n]['w'];
				++$n;
			}
			$key[] = $this->data[$n-1]['s'];
			++$i;
		}
	}
	return $key;
}

function select_weighted_unique($amount=1){
	$count = count($this->data);
	$i = 0;
	if($amount >= $count){
		while($i < $count){
			$return[] = $this->data[$i]['s'];
			++$i;
		}
		return $return;
	}else{
		$max = -1;
		while($i < $count){
			$max += $this->data[$i]['w'];
			++$i;
		}
		
		$i = 0;
		while($i < $amount){
			$max -= $sub;
			$w = 0;
			$n = 0;
			$num = mt_rand(0,$max);
			while($w <= $num){
				$w += $this->data[$n]['w'];
				++$n;
			}
			$sub = $this->data[$n-1]['w'];
			$key[] = $this->data[$n-1]['s'];
			
			unset($this->data[$n-1]);
			$this->data = array_merge($this->data);
			++$i;
		}
		return $key;
	}
}
}
?>