<?php

class filter {
	var $array_key, $filtertype, $filterValue, $filterArgs;

// ---------------------------------------------------
// Filter documents via either a custom filter or basic filter
// ---------------------------------------------------
	function execute($resource, $filter) {
		foreach ($filter["basic"] AS $currentFilter) {
			if (is_array($currentFilter) && count($currentFilter) > 0) {
				$this->array_key = $currentFilter["source"];
				$this->filterValue = $currentFilter["value"];
				$this->filtertype = (isset ($currentFilter["mode"])) ? $currentFilter["mode"] : 1;
				$resource = array_filter($resource, array($this, "basicFilter"));
			}
		}
		foreach ($filter["custom"] AS $currentFilter) {
			if (is_array($currentFilter)  && count($currentFilter) > 0) {
				$resource = array_filter($resource, $currentFilter);
			}
		}
		return $resource;
	}
	
	// ---------------------------------------------------
	// Filter supporting code
	// ---------------------------------------------------
	
	function basicFilter ($value) {
			$unset = 1;
			switch ($this->filtertype) {
				case "!=" :
				case 1 :
					if (!isset ($value[$this->array_key]) || $value[$this->array_key] != $this->filterValue)
						$unset = 0;
					break;
				case "==" :
				case 2 :
					if ($value[$this->array_key] == $this->filterValue)
						$unset = 0;
					break;
				case "<" :
				case 3 :
					if ($value[$this->array_key] < $this->filterValue)
						$unset = 0;
					break;
				case ">" :
				case 4 :
					if ($value[$this->array_key] > $this->filterValue)
						$unset = 0;
					break;
				case "<=" :
				case 5 :
					if (!($value[$this->array_key] < $this->filterValue))
						$unset = 0;
					break;
				case ">=" :
				case 6 :
					if (!($value[$this->array_key] > $this->filterValue))
						$unset = 0;
					break;
					
				// Cases 7 & 8 created by MODx Testing Team Member ZAP
				case 7 :
					if (strpos($value[$this->array_key], $this->filterValue)===FALSE)
						$unset = 0;
					break;
				case 8 :
					if (strpos($value[$this->array_key], $this->filterValue)!==FALSE)
						$unset = 0;
					break;					

				// Date Filtering
				case "date" :
					$start = (strlen($this->filterValue) <= 4) ? mktime(0,0,0,1,1,$this->filterValue) : strtotime($this->filterValue);
						$date = getdate($start);
					$min = (strlen($this->filterValue) <= 4) ? $start : mktime(0,0,0,$date['mon'],1,$date['year']);
					$max = (strlen($this->filterValue) <= 4) ? mktime(0,0,0,1,1,($this->filterValue)+1): mktime(0,0,0,($date['mon']+1),0,$date['year']);
					if ($value[$this->array_key] <= $min || $value[$this->array_key] >= $max)
						$unset = 0;
					break;
			}
			return $unset;
	}
	
}
?>