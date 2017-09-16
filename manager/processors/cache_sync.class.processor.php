<?php
// cache & synchronise class
if(!class_exists('synccache')) {
	class synccache
	{
		var $cachePath;
		var $showReport;
		var $deletedfiles = array();
		var $aliases = array();
		var $parents = array();
		var $aliasVisible = array();
		var $request_time;


		function __construct() {
			global $modx;
			
			$this->request_time = $_SERVER['REQUEST_TIME']+$modx->config['server_offset_time'];
		}
		
		function setCachepath($path)
		{
			$this->cachePath = $path;
		}

		function setReport($bool)
		{
			$this->showReport = $bool;
		}

		function escapeDoubleQuotes($s)
		{
			$q1 = array("\\", '"', "\r", "\n", "\$");
			$q2 = array("\\\\", '\\"', "\\r", "\\n", "\\$");
			return str_replace($q1, $q2, $s);
		}

		function escapeSingleQuotes($s)
		{
			if($s=='') return $s;
			$q1 = array("\\", "'");
			$q2 = array("\\\\", "\\'");
			return str_replace($q1, $q2, $s);
		}

		function getParents($id, $path = '')
		{ // modx:returns child's parent
			global $modx;
			if (empty($this->aliases)) {
				$qh = $modx->db->select('id, IF(alias=\'\', id, alias) AS alias, parent, alias_visible', '[+prefix+]site_content');
				while ($row = $modx->db->getRow($qh)) {
					$this->aliases[$row['id']] = $row['alias'];
					$this->parents[$row['id']] = $row['parent'];
					$this->aliasVisible[$row['id']] = $row['alias_visible'];
				}
			}
			if (isset($this->aliases[$id])) {
				$path = ($this->aliasVisible[$id] == 1 ? $this->aliases[$id] . ($path != '' ? '/' : '') . $path : $path);
				return $this->getParents($this->parents[$id], $path);
			}
			return $path;
		}

		function emptyCache($modx = null)
		{
			if (is_a($modx, 'DocumentParser') === false || get_class($modx) !== 'DocumentParser') {
				$modx = $GLOBALS['modx'];
			}
			if (!isset($this->cachePath)) {
				$modx->messageQuit("Cache path not set.");
			}
			$filesincache = 0;
			$deletedfilesincache = 0;

			// New and improved!
			$files = glob(realpath($this->cachePath) . '/*');
			$filesincache = count($files);
			$deletedfiles = array();
			while ($file = array_shift($files)) {
				$name = basename($file);
				if (preg_match('/\.pageCache/', $name) && !in_array($name, $deletedfiles)) {
					$deletedfilesincache++;
					$deletedfiles[] = $name;
					@unlink($file);
					clearstatcache();
                }
            }

        $this->buildCache($modx);

		$this->publishTimeConfig();

        // finished cache stuff.
        if($this->showReport==true) {
        global $_lang;
            printf($_lang['refresh_cache'], $filesincache, $deletedfilesincache);
            $limit = count($deletedfiles);
            if($limit > 0) {
                echo '<p>'.$_lang['cache_files_deleted'].'</p><ul>';
                for($i=0;$i<$limit; $i++) {
                    echo '<li>',$deletedfiles[$i],'</li>';
                }
                echo '</ul>';
            }
        }
    }

	public function publishTimeConfig($cacheRefreshTime='')
	{
		$cacheRefreshTimeFromDB = $this->getCacheRefreshTime();
		if(!preg_match('@^[0-9]+$]@',$cacheRefreshTime) || $cacheRefreshTimeFromDB < $cacheRefreshTime)
			$cacheRefreshTime = $cacheRefreshTimeFromDB;


		// write the file
		$content = array();
		$content[] = '<?php';
		$content[] = sprintf('$recent_update = %s;'   , $this->request_time);
		$content[] = sprintf('$cacheRefreshTime = %s;', $cacheRefreshTime);

		$filename = $this->cachePath.'/sitePublishing.idx.php';
		if (!$handle = fopen($filename, 'w')) {
			exit("Cannot open file ({$filename}");
		}

		// Write $somecontent to our opened file.
		if (fwrite($handle, implode("\n",$content)) === FALSE) {
			exit("Cannot write publishing info file! Make sure the assets/cache directory is writable!");
		}
	}

	public function getCacheRefreshTime()
	{
		global $modx;

		// update publish time file
		$timesArr = array();

		$result = $modx->db->select('MIN(pub_date) AS minpub', '[+prefix+]site_content', 'pub_date>'.$this->request_time);
		if(!$result) echo "Couldn't determine next publish event!";

		$minpub = $modx->db->getValue($result);
		if($minpub!=NULL)
			$timesArr[] = $minpub;

		$result = $modx->db->select('MIN(unpub_date) AS minunpub', '[+prefix+]site_content', 'unpub_date>'.$this->request_time);
		if(!$result) echo "Couldn't determine next unpublish event!";

		$minunpub = $modx->db->getValue($result);
		if($minunpub!=NULL)
			$timesArr[] = $minunpub;

		if(isset($this->cacheRefreshTime) && !empty($this->cacheRefreshTime))
			$timesArr[] = $this->cacheRefreshTime;

		if(count($timesArr)>0) $cacheRefreshTime = min($timesArr);
		else                   $cacheRefreshTime = 0;
		return $cacheRefreshTime;
	}

    /**
     * build siteCache file
     * @param  DocumentParser $modx
     * @return boolean success
     */
    public function buildCache($modx) {
        $tmpPHP = "<?php\n";

        // SETTINGS & DOCUMENT LISTINGS CACHE

        // get settings
        $rs = $modx->db->select('*', '[+prefix+]system_settings');
        $config = array();
		$tmpPHP .= '$c=&$this->config;';
        while(list($key,$value) = $modx->db->getRow($rs,'num')) {
            $config[$key] = $value;
		}
		if ($config['enable_filter']) {
			$where = "plugincode LIKE '%phx.parser.class.inc.php%OnParseDocument();%' AND disabled != 1";
			$count = $modx->db->getRecordCount($modx->db->select('id', '[+prefix+]site_plugins', $where));
			if ($count) $config['enable_filter'] = '0';
		}
		foreach($config as $key=>$value) {
			$tmpPHP .= sprintf('$c["%s"]="%s";', $this->escapeDoubleQuotes($key), $this->escapeDoubleQuotes($value));
		}

        // get aliases modx: support for alias path
        $tmpPath = '';
        $tmpPHP .= '$this->aliasListing=array();';
        $tmpPHP .= '$a=&$this->aliasListing;';
        $tmpPHP .= '$d=&$this->documentListing;';
        $tmpPHP .= '$m=&$this->documentMap;';

        if ($config['aliaslistingfolder'] == 1) {
            $f['alias']         = "IF( c.alias='', c.id, c.alias)";
            $f['id']            = 'c.id';
            $f['parent']        = 'c.parent';
            $f['isfolder']      = 'c.isfolder';
            $f['alias_visible'] = 'c.alias_visible'; 
            $from = array();
            $from[] = '[+prefix+]site_content';
            $from[] = 'LEFT JOIN [+prefix+]site_content p ON p.id=c.parent';
            $where = 'c.deleted=0 AND (c.isfolder=1 OR p.alias_visible=0)';
            $rs = $modx->db->select( $f, $from, $where, 'c.parent, c.menuindex');
        }else{
            $f = "IF(alias='', id, alias) AS alias, id, parent, isfolder, alias_visible";
            $rs = $modx->db->select($f, '[+prefix+]site_content', 'deleted=0', 'parent, menuindex');
        }
        while ($doc = $modx->db->getRow($rs)) {
            if ($config['friendly_urls'] == 1 && $config['use_alias_path'] == 1) {
                $tmpPath = $this->getParents($doc['parent']);
                $alias= (strlen($tmpPath) > 0 ? "$tmpPath/" : '').$doc['alias'];
                $tmpPHP .= sprintf('$d["%s"]=%s;', $this->escapeDoubleQuotes($alias), $doc['id'] );
            } else {
                $tmpPHP .= sprintf('$d["%s"]=%s;', $this->escapeDoubleQuotes($doc['alias']), $doc['id'] );
            }
            $doc['alias'] = $this->escapeDoubleQuotes($doc['alias']);
            if($tmpPath) $tmpPath = $this->escapeDoubleQuotes($tmpPath);
            $param = array($doc['id'],$doc['alias'],$tmpPath,$doc['parent'],$doc['alias_visible'],$doc['isfolder']);
            $tpl = '$a[%1$s]=array("id"=>%1$s,"alias"=>"%2$s","path"=>"%3$s","parent"=>%4$s,"alias_visible"=>%5$s,"isfolder"=>%6$s);';
            $tmpPHP .= vsprintf($tpl, $param);
            $tmpPHP .= sprintf('$m[]=array("%s"=>"%s");', $doc['parent'], $doc['id']);
        }

        // get content types
        $rs = $modx->db->select('id, contentType', '[+prefix+]site_content', "contentType != 'text/html'");
        $tmpPHP .= '$c = &$this->contentTypes;';
        while ($doc = $modx->db->getRow($rs)) {
            $tmpPHP .= sprintf('$c[%s]="%s";', $doc['id'], $this->escapeDoubleQuotes($doc['contentType']));
        }

        // WRITE Chunks to cache file
        $rs = $modx->db->select('*', '[+prefix+]site_htmlsnippets');
        $tmpPHP .= '$c = &$this->chunkCache;';
        while ($doc = $modx->db->getRow($rs)) {
            /** without trim */
            $code = (!$doc['disabled']) ? $this->escapeDoubleQuotes($doc['snippet']) : '';
            $tmpPHP .= sprintf('$c["%s"]="%s";', $this->escapeDoubleQuotes($doc['name']), $code);
        }

        // WRITE snippets to cache file
        $rs = $modx->db->select(
			'ss.*, sm.properties as sharedproperties',
			'[+prefix+]site_snippets ss LEFT JOIN [+prefix+]site_modules sm on sm.guid=ss.moduleguid'
			);
			$tmpPHP .= '$s=&$this->snippetCache;';
			while ($row = $modx->db->getRow($rs)) {
                $name = $this->escapeSingleQuotes($row['name']);
			    if($row['disabled']) {
                    $tmpPHP .= sprintf("\$s['%s']='%s';", $name, "return false;");
                    $tmpPHP .= sprintf("\$s['%sProps']='%s';", $name, '');
                } else {
                    $code = trim($row['snippet']);
                    if($modx->config['minifyphp_incache'])
                        $code = $this->php_strip_whitespace($code);
                    $code = $this->escapeSingleQuotes($code);
                    $properties       = $modx->parseProperties($row['properties']);
                    $sharedproperties = $modx->parseProperties($row['sharedproperties']);
                    $properties = array_merge($sharedproperties,$properties);
                    $tmpPHP .= sprintf("\$s['%s']='%s';", $name, $code);
                    if (0<count($properties)) {
                        $properties = json_encode($properties);
                        $properties = $this->escapeSingleQuotes($properties);
                        $tmpPHP .= sprintf("\$s['%sProps']='%s';", $name, $properties);
                    }
                }
			}

			// WRITE plugins to cache file
			$rs = $modx->db->select(
				'sp.*, sm.properties as sharedproperties',
				'[+prefix+]site_plugins sp LEFT JOIN [+prefix+]site_modules sm on sm.guid=sp.moduleguid',
				'sp.disabled=0');
			$tmpPHP .= '$p=&$this->pluginCache;';
			while ($row = $modx->db->getRow($rs)) {
				$name = $this->escapeSingleQuotes($row['name']);
				$code = trim($row['plugincode']);
				if($modx->config['minifyphp_incache'])
					$code = $this->php_strip_whitespace($code);
				$tmpPHP .= sprintf("\$p['%s']='%s';", $name, $this->escapeSingleQuotes($code));
				if ($row['properties'] != '' || $row['sharedproperties'] != '') {
					$k = $name;
					$v = $this->escapeSingleQuotes($row['properties'] . ' ' . $row['sharedproperties']);
					$tmpPHP .= sprintf("\$p['%sProps']='%s';", $k, $v);
				}
			}

			// WRITE system event triggers
            $events = array();
            $f = 'sysevt.name as evtname, event.pluginid, plugin.name';
            $from = array();
            $from[] = '[+prefix+]system_eventnames sysevt';
            $from[] = 'INNER JOIN [+prefix+]site_plugin_events event ON event.evtid=sysevt.id';
            $from[] = 'INNER JOIN [+prefix+]site_plugins plugin ON plugin.id=event.pluginid';
			$rs = $modx->db->select($f,$from, 'plugin.disabled=0', 'sysevt.name, event.priority');
			$tmpPHP .= '$e = &$this->pluginEvent;';
			while ($evt = $modx->db->getRow($rs)) {
				if (!isset($events[$evt['evtname']])) {
					$events[$evt['evtname']] = array();
				}
				$events[$evt['evtname']][] = $evt['name'];
			}
			foreach ($events as $evtname => $pluginnames) {
                $param = array($this->escapeDoubleQuotes($evtname), join('","', $this->escapeDoubleQuotes($pluginnames)));
				$tmpPHP .= vsprintf('$e["%s"]=array("%s");', $param);
			}

			// close and write the file
			$filename = $this->cachePath . 'siteCache.idx.php';
			$somecontent = $tmpPHP;

			// invoke OnBeforeCacheUpdate event
			if ($modx) $modx->invokeEvent('OnBeforeCacheUpdate');

			if (!$handle = fopen($filename, 'w')) {
				exit("Cannot open file ({$filename}");
			}

			if (!is_file($this->cachePath . '/.htaccess')) {
				file_put_contents($this->cachePath . '/.htaccess', "order deny,allow\ndeny from all\n");
			}

			// Write $somecontent to our opened file.
			if (fwrite($handle, $somecontent) === FALSE) {
				exit("Cannot write main MODX cache file! Make sure the assets/cache directory is writable!");
			}
			fclose($handle);

			// invoke OnCacheUpdate event
			if ($modx) $modx->invokeEvent('OnCacheUpdate');

			return true;
		}
		
        // ref : http://php.net/manual/en/tokenizer.examples.php
        function php_strip_whitespace($source) {
            
            $source = trim($source);
            if(substr($source,0,5)!=='<?php') $source = '<?php ' . $source;
            
            $tokens = token_get_all($source);
            $_ = '';
            $prev_token = 0;
            $chars = explode(' ', '( ) ; , = { } ? :');
            foreach ($tokens as $i=>$token) {
                if (is_string($token)) {
                    if(in_array($token,array('=',':')))
                        $_ = trim($_);
                    elseif(in_array($token,array('(','{')) && in_array($prev_token,array(T_IF,T_ELSE,T_ELSEIF)))
                        $_ = trim($_);
                    $_ .= $token;
                    continue;
                }
                
                list($type, $text) = $token;
                
                switch ($type) {
                    case T_COMMENT    :
                    case T_DOC_COMMENT:
                        break;
                    case T_WHITESPACE :
                        $_ = trim($_);
                        $lastChar = substr($_,-1);
                        if( !in_array($lastChar,$chars ) ) {// ,320,327,288,284,289
                            if(!in_array($prev_token,array(T_FOREACH,T_WHILE,T_FOR,T_BOOLEAN_AND,T_BOOLEAN_OR,T_DOUBLE_ARROW)))
                                $_ .= ' ';
                        }
                        break;
                    case T_IS_EQUAL :
                    case T_IS_IDENTICAL :
                    case T_IS_NOT_EQUAL :
                    case T_DOUBLE_ARROW :
                    case T_BOOLEAN_AND :
                    case T_BOOLEAN_OR :
                        $prev_token=$type;
                        $_ = trim($_);
                        $_ .= $text;
                        break;
                    default:
                        $prev_token=$type;
                        $_ .= $text;
                }
            }
            $source = preg_replace('@^<\?php@', '', $_);
            $source = trim($source);
            return $source;
        }
	}
}
