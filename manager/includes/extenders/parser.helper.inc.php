<?php

	/**
	* Repeat While Loop
	* Used to repeat $tpl for every item found in $src
	* 
	* @param string $src Source string
	* @param string $step Loop step
	* @param string $tpl First template
	* @param string $altTpl Alternate Template
	*/
	function parser_RepeatWhile($src,$offset,$step,$pop,$tpl,$altTpl) {
		global $modx;
		if ($src=='') return '';
		$range = array();
		$format = '';
		
		// convert source to array
		if(is_string($src)){
			// check for numeric range format
			if($ok = is_numeric($src)) $range = range(1,$src); 
			elseif (strpos($src,'-')!==false) { // this could be a numeric range, digits <= 12
				$range = explode('-',$src,2);
				$ok = is_numeric($range[0]) && is_numeric($range[1]);
				if($ok) $range = range($range[0],$range[1]);
				else $range = array();
			}	  
			if($ok)	$format = 'range';

			// check for html table format
			if (strpos($src,'</tr>')!==false) { 
				// convert html table to array
				$format = 'table';
				$range = parser_Table2Array($src);
			}
			// check for key/value format
			elseif (strpos($src,'||')!==false) {
				// check for key/value format == ||
				if(strpos($src,'==')!==false) { 
					$format = 'array';
					$rows = explode('||',$src);
					foreach($rows as $row) {
						$kv = explode('==',$row,2);
						$range[$kv[0]] = isset($kv[1]) ? $kv[1] : '';
					}
				}		
				// check for row/column format || \n	
				else {   
					$format = 'row';
					$rows = explode("\n",$src);
					foreach($rows as $row) $range[] = explode('||',$row);
				}
			}
			// check for comma delimitted format
			elseif (strpos($src,',')!==false) {
				$format = 'array';	
				$range = explode(',',$src);
			}
			// check for \n delimitted format
			elseif (strpos($src,"\n")!==false) {
				$format = 'array';	
				$range = explode("\n",$src);		
			}
		}
		else {
			// check if src is an array
			if(is_array($src)) {
				$range = $src;			 
				if(isset($src[0]) && is_array($src[0])) $format = 'row'; // it's a 2D array
				else $format = 'array';  
			}
			elseif(is_resource($src)) {   // resource (assume MySQL for now)
				while($row = @$modx->db->getRow($src)) $range[] = $row; 
				$format = 'row';
			}
		}
		
		// render values
		$_pre = '{#:'; $_suf = '#}';
		$_KEY = $_pre.'KEY'.$_suf;
		$_VALUE = $_pre.'VALUE'.$_suf;

		$i = 1; $alt = 1;
		$rows = array(); 
		$length = count($range);
		$step = isset($step) ? $step : 1;
		$pop = isset($pop) ? (int)$pop : -1;
		if($step=='random') { srand(); shuffle($range); $step = 1; } // random step - to be revised
		elseif($step<0) { $range = array_reverse($range); $step = abs($step); } // reverse step
		elseif($step!=1) $step = (int)$step;

		if($format == 'row'||$format == 'table') {
			// setup keys for row/col format
			if ($format == 'row') $keys = array_keys($range[0]);
			else {
				$keys = $range[0];
				$range[0] = null;					
			}
			$length = count($keys);
			for ($k=0; $k<$length; $k++) $keys[$k] = $_pre.$keys[$k].$_suf;
		}	

		foreach($range as $key=>$value) {			 
			if($key==null && $format == 'table') continue; // skip first row
			if($i%$step==0 && $i>$offset) { // find modulus of $i using $step
				$row = ($altTpl!='' && ($alt*=-1)==1) ? $altTpl : $tpl; // select alternate template
				if($format == 'range') $row = str_replace($_KEY,$value,$row);
				elseif($format == 'array') {
					$row = str_replace($_KEY,$key,$row);
					$row = str_replace($_VALUE,$value,$row);
				}
				elseif($format == 'row') {
					$row = str_replace($keys,array_values($value),$row);					
				}
				elseif($format == 'table') {
					$row = str_replace($keys,$value,$row);	
					// support for nested fields
					if(strpos($row,'{#:')!==false) $row = str_replace($keys,$value,$row);	
				}
				$rows[] = $row;
				if ((--$pop)==0) break;  // stop if we have rendered enough
			}
			$i++;				
		}
		return implode('',$rows);
	}

	/**
	 * Converts an HTML Table to a two dimentional Array
	 *
	 * @param: string $html HTML table code to be converted
	 * @return: array 
	 */
	function parser_Table2Array($html) {
		$match = $rows = array();
		$ok = preg_match('|<table[^>]*>(.*?)</table>|is',$html,$match);				
		if($ok) {
			// parse table content
			$table = $match[1];	
			$table = preg_replace('|</td>[^>]*<td[^>]*>|is','|',$table);
			preg_match_all('|<td[^>]*>(.*?)</td>|is',$table,$match);
			$rows = $match[1];
			// format rows and column name
			$rows[0] = str_replace(array(' ',"\t","\r","\n"),'',$rows[0]);  // clean names
			$rows[0] = strip_tags($rows[0]); // first row stores column names
			foreach($rows as $i => $value) {
				$rows[$i] = explode('|',$rows[$i]);
			}
		}		
		return $rows;
	}
?>