<?php

class debug{
	function debug() {
		global $modx,$ditto_lang;
		$modx->regClientCSS($ditto_lang["debug_styles"]);
	}
	
	function modxPrep($value) {
		$value = (strpos($value,"<") !== FALSE) ? htmlentities($value) : $value;
		$value = str_replace("[","&#091;",$value);
		$value = str_replace("]","&#093;",$value);
		$value = str_replace("{","&#123;",$value);
		$value = str_replace("}","&#125;",$value);
		return $value;
	}
	
	function header($ditto,$ditto_version, $IDs, $fields, $summarize, $recordCount, $sortBy, $sortDir, $start, $stop, $total,$filter) {
		global $ditto_lang,$modx;

		sort($IDs);
		$fields = array("retrieved"=>$fields);
		$items['[+call+]'] =  $this->parameters2table($modx->event->params,$ditto_lang["params"]);
		$items['[+version+]'] = $ditto_version;
		$items['[+summarize+]'] = $summarize;
		$items['[+recordCount+]'] = $recordCount;	 
		$items['[+sortBy+]'] = ($ditto->advSort !== false) ? $ditto->advSort : $sortBy;	 
		$items['[+sortDir+]'] = $sortDir;	 
		$items['[+start+]'] = $start;	 
		$items['[+stop+]'] = $stop;	 
		$items['[+total+]'] = $total;	 
		$items['[+prefetch+]'] = ($ditto->prefetch == true) ? $ditto_lang["yes"] : $ditto_lang["no"];	 
		$items['[+ids+]'] = (count($IDs) > 0) ? wordwrap(implode(", ",$IDs),75, "<br />") : $ditto_lang['none'];	 
		$items['[+filter+]'] = ($filter !== false) ? $this->array2table($this->cleanArray($filter), true, true) : $ditto_lang['none'];
		$items['[+fields+]'] = $this->array2table($this->cleanArray(array_merge_recursive($ditto->fields,$fields)), true, true);

		return str_replace(array_keys($items), array_values($items), $ditto_lang['debug_head']);
	}
	
	function content($resource,$placeholders,$currentTPLname, $currentTPL) {
		global $ditto_lang;
		switch ($currentTPLname) {
			case "base":
				$displayName = "tpl";
			break;
			
			case "default":
				$displayName = "tpl";
			break;
			
			default:
				$displayName = "tpl".$tplName;
			break;
		}
		$placeholders["$displayName"] = $currentTPL;
		$header = str_replace(array('[+pagetitle+]','[+id+]'),array($resource['pagetitle'],$resource['id']),$ditto_lang['debug_item']);
		$output = $this->parameters2table($placeholders,$header)."<br />";

		return $output;
	}
	
	//---Helper Functions------------------------------------------------ //

	function cleanArray($array) {
	   foreach ($array as $index => $value) {
	       if(is_array($array[$index])) $array[$index] = $this->cleanArray($array[$index]);
	       if (empty($value)) unset($array[$index]);
	   }
	   return $array;
	}
	
	function parameters2table($parameters,$header,$sort=true) {
		if ($sort === true) {
					ksort($parameters);
		}
				$output = '<table>
				  <tbody>
				    <tr>
				      <th>'.$header.'</th>
				    </tr>
				    <tr>
				      <td>
				      <table>
				        <tbody>
		';
		foreach ($parameters as $key=>$value) {
			$output .= '
					    <tr>
					      <th>'.$key.'</th>
					      <td>'.$this->modxPrep($value).'</td>
					    </tr>
			';
		}
		$output .=
		'
				        </tbody>
				      </table>
				      </td>
				    </tr>
				  </tbody>
				</table>
				';

	return $output;
	}
	
	/**
	 * Translate a result array into a HTML table
	 *
	 * @author      Aidan Lister <aidan@php.net>
	 * @version     1.3.1
	 * @link        http://aidanlister.com/repos/v/function.array2table.php
	 * @param       array  $array      The result (numericaly keyed, associative inner) array.
	 * @param       bool   $recursive  Recursively generate tables for multi-dimensional arrays
	 * @param       bool   $return     return or echo the data
	 * @param       string $null       String to output for blank cells
	 */
	function array2table($array, $recursive = false, $return = false, $null = '&nbsp;')
	{
	    // Sanity check
	    if (empty($array) || !is_array($array)) {
	        return false;
	    }

	    if (!isset($array[0]) || !is_array($array[0])) {
	        $array = array($array);
	    }

	    // Start the table
	    $table = "<table>\n";
		$head = array_keys($array[0]);
	if (!is_numeric($head[0])) {
	    // The header
	    $table .= "\t<tr>";
	    // Take the keys from the first row as the headings
	    foreach (array_keys($array[0]) as $heading) {
	        $table .= '<th>' . $heading . '</th>';
	    }
	    $table .= "</tr>\n";
	}
	    // The body
	    foreach ($array as $row) {
	        $table .= "\t<tr>" ;
	        foreach ($row as $cell) {
	            $table .= '<td>';

	            // Cast objects
	            if (is_object($cell)) { $cell = (array) $cell; }

	            if ($recursive === true && is_array($cell) && !empty($cell)) {
	                // Recursive mode
	                $table .= "\n" . $this->array2table($cell, true, true) . "\n";
	            } else {
	                $table .= (strlen($cell) > 0) ?
	                    htmlspecialchars((string) $cell) :
	                    $null;
	            }

	            $table .= '</td>';
	        }

	        $table .= "</tr>\n";
	    }

	    // End the table
	    $table .= '</table>';

	    // Method of output
	    if ($return === false) {
	        echo $table;
	    } else {
	        return $table;
	    }
	}
}

?>