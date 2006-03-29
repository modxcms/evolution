<?php


/**
 * Document Manager Module - process.inc.php
 * 
 * Purpose: Contains the main form processing functions for the module
 * Author: Garry Nutting (Mark Kaplan - Menu Index functionalty, Luke Stokes - Document Permissions concept)
 * For: MODx CMS (www.modxcms.com)
 * Date:24/02/2006 Version: 1
 * 
 */

/**
 * changeOther
 * 
 * @input - whether 'tree' or 'range' has been used
 * @pids - the Document IDs for processing
 */
function changeOther($input, $pids) {
	global $modx;
	global $_lang;
	global $table;

	function parseDate($date) {
		list ($d, $m, $Y, $H, $M, $S) = sscanf($date, "%2d-%2d-%4d %2d:%2d:%2d");
		$date = strtotime("$m/$d/$Y $H:$M:$S");

		return $date;
	}

	//-- miscellaneous document settings
	if ($_POST['setoption'] == 1) {
		$fieldval = 'published';
		logDocumentChange('publish');
	}
	elseif ($_POST['setoption'] == 2) {
		$fieldval = 'hidemenu';
		logDocumentChange('hidemenu');
	}
	elseif ($_POST['setoption'] == 3) {
		$fieldval = 'searchable';
		logDocumentChange('search');
	}
	elseif ($_POST['setoption'] == 4) {
		$fieldval = 'cacheable';
		logDocumentChange('cache');
	}
	elseif ($_POST['setoption'] == 5) {
		$fieldval = 'richtext';
		logDocumentChange('richtext');
	}

	//-- document date settings
	$dateval = array ();

	if ($_POST['date_pubdate'] <> '')
		$dateval['pub_date'] = parseDate($_POST['date_pubdate']);
	if ($_POST['date_unpubdate'] <> '')
		$dateval['unpub_date'] = parseDate($_POST['date_unpubdate']);
	if ($_POST['date_createdon'] <> '')
		$dateval['createdon'] = parseDate($_POST['date_createdon']);
	if ($_POST['date_editedon'] <> '')
		$dateval['editedon'] = parseDate($_POST['date_editedon']);

	//-- document author settings
	$authorval = array ();
	if ($_POST['author_createdby'] <> 0)
		$authorval['createdby'] = intval($_POST['author_createdby']);
	if ($_POST['author_editedby'] <> 0)
		$authorval['editedby'] = intval($_POST['author_editedby']);

	$output = '';
	$new = false; // flag for if an update is made

	//-- get html header
	$output .= updateHeader();

	//-- process the ID numbers
	if ($input == 'tree') {
		$values = $pids;
		$values = rtrim($values, ',');
		if ($values<>'') $values = 'id="' . str_replace(',', '" OR id="', $values) . '"';
		else $output.= $_lang['process_novalues'];

		if ($pids <> '' && $_POST['setoption'] != 0 && $_POST['newvalue'] <> '') {
			//-- run UPDATE query for misc settings 
			$fields = array (
				$fieldval => intval($_POST['newvalue']
			));
			$modx->db->update($fields, $table, $values);
			$new = true; // update has been completed
		}

		if ($pids <> '' && count($dateval) > 0) {
			//-- run UPDATE query for document dates
			$modx->db->update($dateval, $table, $values);
			$new = true; // update has been completed
			logDocumentChange('dates');
		}

		if ($pids <> '' && count($authorval) > 0 && $values<>'') {
			//-- run UPDATE query for author settings
			$modx->db->update($authorval, $table, $values);
			$new = true; // update has been completed
			logDocumentChange('authors');
		}

		if (!$new) {
			$error .= $_lang['process_noselection'] . '<br />';
		}
	}
	elseif ($input == 'range') {
		//-- parse values
		$results = processRange($pids, 'id', 1);
		$pids = $results[0];
		$error = $results[1];
		$values = rtrim($pids, ' OR ');

		if ($pids <> '' && $_POST['newvalue'] <> '') {
			//-- run UPDATE query for misc settings 
			$fields = array (
				$fieldval => intval($_POST['newvalue']
			));
			$modx->db->update($fields, $table, $values);
			$new = true;
		}

		if ($pids <> '' && count($dateval) > 0) {
			//-- run UPDATE query for document dates
			$modx->db->update($dateval, $table, $values);
			$new = true; // update has been completed
			logDocumentChange('dates');
		}

		if ($pids <> '' && count($authorval) > 0) {
			//-- run UPDATE query for author settings
			$modx->db->update($authorval, $table, $values);
			$new = true; // update has been completed
			logDocumentChange('authors');
		}

		if (!$new) {
			$error .= '<br />' . $_lang['process_noselection'] . '<br />';
		}
	}

	if ($error == '') {
		$output .= '<p>' . $_lang['process_update_success'];
	} else {
		$output .= '<p>' . $_lang['process_update_error'] . '<br />';
		$output .= $error;
	}

	$output .= '<form name="back" method="post"><input type="submit" name="back" value="' . $_lang['process_back'] . '" /></form>';
	$output .= '</div></body></html>';

	return $output;
	break;

}

/**
 * sortMenu
 * 
 * @id - Document ID of parent
 */

function sortMenu($id) {
	global $modx;
	global $theme;
	global $siteURL;
	global $basePath;
	global $actionkey;
	global $_lang;

	$basePath = $modx->config['base_path'];
	$siteURL = $modx->config['site_url'];

	$sortableLists = new SLLists($siteURL . 'manager/media/script/scriptaculous/');
	$sortableLists->addList('categories', 'categoriesListOrder');
	$sortableLists->debug = false;

	$output .= $sortableLists->printTopJS();

	$output .= '
											<link rel="stylesheet" type="text/css" href="media/style' . $theme . '/style.css" /> 
											<link rel="stylesheet" type="text/css" href="media/style' . $theme . '/coolButtons2.css" /> 
											<script type="text/javascript" src="' . $siteURL . 'assets/modules/docmanager/js/functions.js"></script>
											<script type="text/javascript" language="JavaScript" src="media/script/cb2.js"></script>';
	$output .= buttonCSS();
	$output .= '	</head><body>
											<form action="" method="post" name="resetform" style="display: none;">
											<input name="actionkey" type="hidden" value="0" />
											</form>
											';

	$header .= '<div class="subTitle" id="bttn"> 
											    <span class="right"><img src="media/style/' . $theme . '/images/_tx_.gif" width="1" height="5"><br />' . $_lang['module_title'] . '</span>
												<div class="bttnheight"><a id="Button1" onclick="save();"><img src="media/style' . $theme . '/images/icons/save.gif"> ' . $_lang['save'] . '</a></div>
												<div class="bttnheight"><a id="Button4" onclick="reset();"><img src="media/style' . $theme . '/images/icons/sort.gif"> ' . $_lang['sort_another'] . '</a></div>';

	$header .= '<div class="bttnheight"><a id="Button5" onclick="document.location.href=\'index.php?a=106\';"><img src="media/style' . $theme . '/images/icons/cancel.gif"> ' . $_lang['cancel'] . '</a></div>
												<div class="stay">  </div>
												</div>
									
									<div class="sectionHeader"><img src="media/style' . $theme . '/images/misc/dot.gif" alt="." />&nbsp;';
	$middle = '</div><div class="sectionBody">';
	$footer = ' 
										</div>
										</body>
										</html>
									';
	$output .= $header . $_lang['sort_title'] . $middle;
	
	$tblContent = $modx->getFullTableName('site_content');
	
				if (isset ($_POST['sortableListsSubmitted'])) {
			$output .= "<span class=\"warning\" id=\"updated\">" . $_lang['sort_updated'] . "<br /><br /> </span>";
			$orderArray = $sortableLists->getOrderArray($_POST['categoriesListOrder'], 'categories');
			foreach ($orderArray as $item) {
				$sql = "UPDATE $tblContent set menuindex=" . $item['order'] . " WHERE id=" . $item['element'];
				$modx->db->query($sql);
				logDocumentChange('sortmenu');
			}

		}

	if ($id <> '' && !isset ($_POST['sortableListsSubmitted'])) {
		$output .= "<span class=\"warning\" style=\"display:none;\" id=\"updating\">" . $_lang['sort_updating'] . "<br /><br /> </span>";
				$query = "SELECT id , pagetitle , parent , menuindex FROM $tblContent WHERE parent = $id ORDER BY menuindex ASC";

		if (!$rs = $modx->db->query($query)) {
			return $output;
		}
		while ($row = $modx->db->getRow($rs)) {
			$resource[] = $row;
		}

		$output .= '<ul id="categories" class="sortableList">';
	} elseif ($id=='' && !isset ($_POST['sortableListsSubmitted']) ) {
		$no_id = true;
		$output .= $_lang['sort_noid'] . $footer;
	}

	if (!$no_id && !isset ($_POST['sortableListsSubmitted'])) {
		$cnt = count($resource);
		if ($cnt < 1) {
			$output .= $_lang['sort_nochildren'] . $footer;
			return $output;
			//die;
		} else {

			foreach ($resource as $item) {
				$output .= '<li id="item_' . $item['id'] . '">' . $item['pagetitle'] . '</li>';

			}
		}
		$output .= '</ul>';

	}

	$output .= $sortableLists->printForm('', 'POST', 'Submit', 'button');

	$output .= '<br />';

	$output .= $sortableLists->printBottomJS();

	$output .= $footer;

	return $output;

}

/**
 * changeDocGroups
 * 
 * @input - 'tree' or 'range' used for selection
 * @pids - the Document ID range
 * @docgroup - The Document Group ID
 * @action - 'pushDocGroup' to add doc permissions, 'pullDocGroup' to remove document permissions
 */

function changeDocGroups($input, $pids, $docgroup, $action) {
	global $theme;
	global $modx;
	global $_lang;

	$doctable = $modx->getFullTableName('document_groups');
	$output = '';

	//-- get html header
	$output .= updateHeader();
	
	$doc_id = array();
	
	//-- process the ID numbers
	if ($input == 'tree') {
		$pids = rtrim($pids, ',');
		if ($pids<>'') $doc_id = explode(',', $pids);
	}
	elseif ($input == 'range') {
		$doc_vals = processRange($pids, '', 0);
		$doc_id = $doc_vals[0];
		$error = $doc_vals[1];
	}

	switch ($action) {
		case 'pushDocGroup' :
			if (count($doc_id) > 0) {
				foreach ($doc_id as $value) {
					$docsAdded = 0;
					// first check to see if the document already belongs to this doc group: 
					$sql = "SELECT * FROM " . $modx->getFullTableName('document_groups') . " WHERE document_group = " . $docgroup . " AND document = " . $value;
					$sqlResult = $modx->db->query($sql);
					$NotAMember = ($modx->db->getRecordCount($sqlResult) == 0);
					if ($NotAMember) {
						// update the parent
						$sql = "INSERT INTO " . $modx->getFullTableName('document_groups') . " (document_group, document) VALUES (" . $docgroup . "," . $value . ")";
						$sqlResult = $modx->db->query($sql);
						$docsAdded += 1;
					} else {
						$output .= $_lang['doc_skip_message1'] . ' ' . $value . ' ' . $_lang['doc_skip_message2'] . "<br />";
					}

				}
			}

			if ($error == '') {
				$output .= '<br /><p>' . $_lang['process_update_success'];
			} else {
				$output .= '<p>' . $_lang['process_update_error'] . '<br />';
				$output .= $error;
			}

			$output .= '<br /><br /><form name="back" method="post"><input type="submit" name="back" value="' . $_lang['process_back'] . '" /></form>';
			$output .= '</div></body></html>';

			return $output;
			break;

		case 'pullDocGroup' :
			if (count($doc_id) > 0) {
				foreach ($doc_id as $value) {
					$docsRemoved = 0;
					// first check to see if the document already belongs to this doc group:  
					$sql = "SELECT * FROM " . $modx->getFullTableName('document_groups') . " WHERE document_group = " . $docgroup . " AND document = " . $value;
					$sqlResult = $modx->db->query($sql);
					$AMember = ($modx->db->getRecordCount($sqlResult) <> 0);
					if ($AMember) {
						// delete the parent 
						$sql = "DELETE FROM " . $modx->getFullTableName('document_groups') . " WHERE document_group = " . $docgroup . " AND document = " . $value;
						$sqlResult = $modx->db->query($sql);
						$docsRemoved += 1;
					} else {
						$output .= $_lang['doc_skip_message1'] . $value . $_lang['doc_skip_message2'] . "<br />";
					}
				}
			}
	}

	if ($error == '') {
		$output .= '<p>' . $_lang['process_update_success'];
	} else {
		$output .= '<p>' . $_lang['process_update_error'] . '<br />';
		$output .= $error;
	}

	$output .= '<form name="back" method="post"><input type="submit" name="back" value="' . $_lang['process_back'] . '" /></form>';
	$output .= '</div></body></html>';

	logDocumentChange('docpermissions');
	return $output;
	break;

}

/**
 * changeDocGroups
 * 
 * @input - 'tree' or 'range' used for selection
 * @pids - the Document ID range
 * @template - the Template ID to be used
 */

function changeTemplate($input, $pids, $template) {
	global $theme;
	global $modx;
	global $_lang;

	$table = $modx->getFullTableName('site_content');

	// opcode is tree, run the tree data handler 
	if ($input == 'tree') {
		$values = $pids;
		if ($pids <> '' && $template <> '') {
			//-- run UPDATE query 
			$values = rtrim($values, ',');
			$values = 'id="' . str_replace(',', '" OR id="', $values) . '"';
			$fields = array (
				'template' => intval($template
			));
			$modx->db->update($fields, $table, $values);
		} else {
			$error .= '<br />' . $_lang['process_noselection'] . '<br />';
		}

		$output .= updateHeader();

		if ($error == '') {
			$output .= '<p>' . $_lang['process_update_success'];
		} else {
			$output .= '<p>' . $_lang['process_update_error'] . '<br />';
			$output .= $error;
		}

		$output .= '</p> 
																					<p>' . $_lang['tpl_results_message'] . '</p> 
																					<form name="back" method="post"><input type="submit" name="back" value="' . $_lang['process_back'] . '" />
																					</form>
																					<input type="submit" name="refresh" onclick="document.location.href=\'index.php?a=26\';" value="' . $_lang['tpl_refresh_site'] . '" /> 
																					</div> 
																					</body></html>';
	}

	//-- values have been passed via the range text field 
	elseif ($input == 'range') {
		//-- parse values
		$results = processRange($pids, 'id', 1);
		$pids = $results[0];
		$error = $results[1];

		if ($pids <> '' && $template <> '') {
			//-- run UPDATE query 

			$values = rtrim($pids, ' OR ');
			$fields = array (
				'template' => intval($template
			));
			$modx->db->update($fields, $table, $values);
		} else {
			$error .= '<br />' . $_lang['process_noselection'] . '<br />';
		}

		$output .= updateHeader();

		if ($error == '') {
			$output .= '<p>' . $_lang['process_update_success'];
		} else {
			$output .= '<p>' . $_lang['process_update_error'] . '<br />';
			$output .= $error;
		}

		$output .= '</p> 
																					<p>' . $_lang['tpl_results_message'] . '</p><br />
																					<form name="back" method="post"><input type="submit" name="back" value="' . $_lang['process_back'] . '" />
																					</form>
																					</div> 
																					</body></html>';
	}
	//-- clear the cache
	$modx->clearCache();

	//-- log the event
	logDocumentChange('template');
	return $output;
}

/**
 * Process Range
 * 
 * @pids - The document ID range
 * @column - The column to return (eg. id,document etc.) if returnval is 1.
 * @returnval - Return values as array(0) or SQL WHERE string (1)
 */
function processRange($pids, $column, $returnval = 1) {
	global $table;
	global $modx;
	global $_lang;

	//-- set initial vars 
	$values = array ();
	$error = '';

	//-- check for empty field 
	if (trim($pids) <> '') {
		$values = explode(',', $pids);
	} else {
		$error .= $_lang['process_novalues'];
	}

	$pids = '';

	//-- parse values, and check for invalid entries 
	foreach ($values as $key => $value) {
		//-- value is a range 
		if (preg_match('/^[\d]+\-[\d]+$/', trim($value))) {
			//-- explode the lower and upper limits 
			$match = explode('-', $value);
			//-- Upper limit lower than lower limit 
			if (($match[1] - $match[0]) < 0) {
				$error = $_lang['process_limits_error'] . $value . '<br />';
			}
			//-- loop through values and parse WHERE SQL statement 
			$loop = $match[1] - $match[0];
			for ($i = 0; $i <= $loop; $i++) {
				if (returnval == 0) {
					$idarray[] = ($i + $match[0]);
				} else {
					$pids .= '' . $column . '=\'' . ($i + $match[0]) . '\' OR ';
				}
			}
		}

		//-- value is a group for immediate children 
		elseif (preg_match('/^[\d]+\*$/', trim($value), $match)) {
			//-- get ID number of folder 
			$match = rtrim($match[0], '*');
			//-- get ALL children 
			$group = $modx->db->select('id', $table, 'parent=' . $match, '', '');
			//-- parse WHERE SQL statement 
			if ($returnval == 0) {
				$idarray[] = $match;
			} else {
				$pids .= '' . $column . '=\'' . $match . '\' OR ';
			}
			while ($row = $modx->db->getRow($group)) {
				if (returnval == 0) {
					$idarray[] = ($row['id']);
				} else {
					$pids .= '' . $column . '=\'' . $row['id'] . '\' OR ';
				}
			}
		}
		//-- value is a group for ALL children 
		elseif (preg_match('/^[\d]+\*\*$/', trim($value), $match)) {
			//-- get ID number of folder 
			$match = rtrim($match[0], '**');
			$idarray[] = $match;
			//-- recurse and get ALL children 
			for ($i = 0; $i < count($idarray); $i++) {
				$where = 'parent=' . $idarray[$i];
				$rs = $modx->db->select("id", $table, $where);
				if ($modx->db->getRecordCount($rs) > 0) {
					while ($row = $modx->db->getRow($rs)) {
						$idarray[] = $row['id'];
					}
				}
			}

			//-- parse array into string 
			for ($i = 0; $i < count($idarray); $i++) {
				$pids .= '' . $column . '=\'' . $idarray[$i] . '\' OR ';
			}
		}
		//-- value is a single document 
		elseif (preg_match('/^[\d]+$/', trim($value))) {
			//-- parse WHERE SQL statement 
			if ($returnval == 0) {
				$idarray[] = ($i + $match[0]);
			} else {
				$pids .= '' . $column . '=\'' . trim($value) . '\' OR ';
			}
			//-- value is invalid 
		} else {
			$error .= $_lang['process_invalid_error'] . $value . '<br />';
		}
	} //foreach end 
	if ($returnval == 0) {
		$results[] = $idarray;
		$results[] = $error;
	} else {
		$results[] = $pids;
		$results[] = $error;
	}
	return $results;
}

function logDocumentChange($action) {
	global $_lang;
	global $basePath;

	include_once 'log.class.inc.php'; // include_once the class
	$log = new logHandler; // create the object

	switch ($action) {

		case 'template' :
			$log->initAndWriteLog($_lang['log_template']);
			break;

		case 'docpermissions' :
			$log->initAndWriteLog($_lang['log_docpermissions']);
			break;

		case 'sortmenu' :
			$log->initAndWriteLog($_lang['log_sortmenu']);
			break;

		case 'publish' :
			$log->initAndWriteLog($_lang['log_publish']);
			break;

		case 'hidemenu' :
			$log->initAndWriteLog($_lang['log_hidemenu']);
			break;

		case 'search' :
			$log->initAndWriteLog($_lang['log_search']);
			break;

		case 'cache' :
			$log->initAndWriteLog($_lang['log_cache']);
			break;

		case 'richtext' :
			$log->initAndWriteLog($_lang['log_richtext']);
			break;

		case 'dates' :
			$log->initAndWriteLog($_lang['log_richtext']);
			break;

		case 'authors' :
			$log->initAndWriteLog($_lang['log_authors']);
			break;

	}

}
?>
