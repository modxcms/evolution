<?php
/**
 * MemberCheck
 *
 * Show chunks based on a logged in Web User's group membership
 *
 * @category 	snippet
 * @version 	1.1
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties
 * @internal	@modx_category Login
 * @internal    @installset base
 * @documentation [+site_url+]assets/snippets/membercheck/readme.html
 * @reportissues https://github.com/modxcms/evolution
 * @author      Created By Ryan Thrash http://thrash.me
 * @author      Sanitized By Jason Coward http://opengeek.com
 * @author      Refactored 2013 by Dmi3yy
 * @author      Small fixes by Segr
 * @lastupdate  20/10/2014
 */
if(!defined('MODX_BASE_PATH')){die('What are you doing? Get out of here!');}

# debug parameter
$debug = isset ($debug) ? $debug : false;

# check if inside manager
if ($m = $modx->isBackend()) {
	return ''; # don't go any further when inside manager
}

if (!isset ($groups)) {
	return $debug ? '<p>Error: No Group Specified</p>' : '';
}

if (!isset ($chunk)) {
	return $debug ? '<p>Error: No Chunk Specified</p>' : '';
}

# turn comma-delimited list of groups into an array
$groups = array_filter(array_map('trim', explode(',', $groups)));

if (!class_exists('MemberCheck')) {
	class MemberCheck {
		var $allGroups = NULL;
		var $debug;

		function getInstance($debug) {
			static $instance;
			if (!isset ($instance)) {
				$instance = new MemberCheck($debug);
			}
			return $instance;
		}

		function __construct($debug = false) {
			global $modx;

			$this->debug = $debug;
			if ($debug) {
				$this->allGroups = array ();
				$rs = $modx->db->select('name', $modx->getFullTableName('webgroup_names'));
					$this->allGroups = $modx->db->getColumn('name', $rs);
					$this->allGroups = array_map('stripslashes', $this->allGroups);
			}
		}

		function isValidGroup($groupName) {
			$isValid = !(array_search($groupName, $this->allGroups) === false);
			return $isValid;
		}

		function getMemberChunk(& $groups, $chunk) {
			global $modx;
			$o = '';
			if (is_array($groups)) {
				if ($this->debug) {
					for ($i = 0; $i < count($groups); $i++) {
						if (!$this->isValidGroup($groups[$i])) {
							return "<p>The group <strong>" . $groups[$i] . "</strong> could not be found...</p>";
						}
					}
				}

				$check = $modx->isMemberOfWebGroup($groups);

				$chunkcheck = $modx->getChunk($chunk);

				$o .= ($check && $chunkcheck) ? $chunkcheck : '';
				if (!$chunkcheck)
					$o .= $this->debug ? "<p>The chunk <strong>$chunk</strong> not found...</p>" : '';
			} else {
				$o .= "<p>No valid group names were specified!</p>";
			}

			return $o;
		}
	}
}

$memberCheck = MemberCheck :: getInstance($debug);

if (!isset ($ph)) {
	return $memberCheck->getMemberChunk($groups, $chunk);
} else {
	$modx->setPlaceholder($ph, $memberCheck->getMemberChunk($groups, $chunk));
	return '';
}
?>