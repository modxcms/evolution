<?php namespace EvolutionCMS;

class UrlProcessor
{
    /**
     * @var Interfaces\CoreInterface
     */
    protected $core;

    public $aliasListing = [];
    public $documentListing = [];
    public $virtualDir = '';

    protected $aliases = [];
    protected $isfolder = [];

    protected $tagPattern = '!\[\~(\d+)\~\]!i';

    public function __construct(Interfaces\CoreInterface $core)
    {
        $this->core = $core;
        $this->documentListing = &$this->core->documentListing;
        $this->aliasListing = &$this->core->aliasListing;
        $this->virtualDir = &$this->core->virtualDir;

        $this->build();
    }

    protected function build() : void
    {
        $this->aliases = [];
        $this->isfolder = [];

        if (\is_array($this->documentListing)) {
            foreach ($this->documentListing as $path => $docid) { // This is big Loop on large site!
                $this->aliases[$docid] = $path;
                $this->isfolder[$docid] = !empty($this->aliasListing[$docid]['isfolder']);
            }
        }
    }

    public function getAliases()
    {
        return $this->aliases;
    }

    public function getIsFolders()
    {
        return $this->isfolder;
    }

    /**
     * @desc Create an URL.
     *
     * @param string $pre Friendly URL Prefix. @required
     * @param string $suff Friendly URL Suffix. @required
     * @param string $alias Full document path. @required
     * @param bool $isfolder Is it a folder? Default: 0.
     * @param int $id Document id. Default: 0.
     * @return string Result URL.
     */
    public function makeFriendlyURL($pre, $suff, $alias, bool $isfolder = false, int $id = 0) : string
    {
        if ($id === $this->core->getConfig('site_start') && $this->core->getConfig('seostrict')) {
            $url = $this->core->getConfig('base_url');
        } else {
            $tmp = explode('/', $alias);
            $alias = array_pop($tmp);
            $dir = implode('/', $tmp);
            unset($tmp);

            if ($this->core->getConfig('make_folders') && $isfolder) {
                $suff = '/';
            }

            $url = ($dir !== '' ? $dir . '/' : '') . $pre . $alias . $suff;
        }

        $evtOut = $this->core->invokeEvent('OnMakeDocUrl', array(
            'id'  => $id,
            'url' => $url
        ));

        if (\is_array($evtOut) && \count($evtOut) > 0) {
            $url = array_pop($evtOut);
        }

        return $url;
    }

    /**
     * Convert URL tags [~...~] to URLs
     *
     * @param string $input
     * @return string
     */
    public function rewriteUrls($input)
    {
        // rewrite the urls
        if ($this->core->getConfig('friendly_urls')) {
            $aliases = $this->getAliases();
            $isFolder = $this->getIsFolders();

            if ($this->core->getConfig('aliaslistingfolder')) {
                preg_match_all($this->tagPattern, $input, $match);
                $this->generateAliasListingFolder($match['1'], $aliases, $isFolder);
            }

            $output = $this->replaceUrl($input, $aliases, $isFolder);

        } else {
            $output = $this->rewriteToNativeUrl($input);
        }

        return $output;
    }

    protected function replaceUrl(string $input, array $aliases, array $isFolder) : string
    {
        $isFriendly = $this->core->getConfig('friendly_alias_urls');
        $pref = $this->core->getConfig('friendly_url_prefix');
        $suffix = $this->core->getConfig('friendly_url_suffix');
        $seoStrict = $this->core->getConfig('seostrict');

        return preg_replace_callback(
            $this->tagPattern,
            function ($match) use ($aliases, $isFolder, $isFriendly, $seoStrict, $pref, $suffix) {
                if ($isFriendly && isset($aliases[$match[1]])) {
                    $out = $this->makeFriendlyURL(
                        $pref,
                        $suffix,
                        $aliases[$match[1]],
                        $isFolder[$match[1]],
                        $match[1]
                    );

                    //found friendly url
                    if ($seoStrict) {
                        $out = $this->toAlias($out);
                    }
                } else {
                    //not found friendly url
                    $out = $this->makeFriendlyURL($pref, $suffix, $match[1]);
                }

                return $out;
            },
            $input
        );
    }
    protected function generateAliasListingFolder(array $ids, &$aliases, &$isFolder) : void
    {
        $ids = array_unique($ids);
        if (empty($ids)) {
            return;
        }

        $useAliasPath = (bool)$this->core->getConfig('use_alias_path');

        $data = Models\SiteContent::whereIn('id', $ids)
            ->where('isfolder', '=', 0)
            ->get();

        foreach ($data as $row) {
            if ($useAliasPath && $row->parent > 0) {
                $parent = $row->parent;
                $path = $aliases[$parent];

                while (isset($this->aliasListing[$parent]) && (int)$this->aliasListing[$parent]['alias_visible'] === 0) {
                    $path = $this->aliasListing[$parent]['path'];
                    $parent = $this->aliasListing[$parent]['parent'];
                }

                $aliases[$row->getKey()] = $path . '/' . $row->alias;
            } else {
                $aliases[$row->getKey()] = $row->alias;
            }
            $isFolder[$row->getKey()] = '0';
        }
    }

    public function rewriteToNativeUrl(string $content) : string
    {
        return preg_replace($this->tagPattern, 'index.php?id=\1', $content);
    }

    /**
     * @param string $text
     * @return string mixed
     */
    public function toAlias(string $text) : string
    {
        $suffix = $this->core->getConfig('friendly_url_suffix');
        return str_replace(
            [
                '.xml' . $suffix,
                '.rss' . $suffix,
                '.js' . $suffix,
                '.css' . $suffix,
                '.txt' . $suffix,
                '.json' . $suffix,
                '.pdf' . $suffix
            ],
            [
                '.xml',
                '.rss',
                '.js',
                '.css',
                '.txt',
                '.json',
                '.pdf'
            ],
            $text
        );
    }

    public function getNotFoundPageId() : int
    {
        return $this->core->getConfig(
            $this->core->getConfig('error_page') ? 'error_page' : 'site_start',
            1
        );
    }

    public function getUnAuthorizedPageId() : int
    {
        if ($this->core->getConfig('unauthorized_page')) {
            $unauthorizedPage = $this->core->getConfig('unauthorized_page');
        } elseif ($this->core->getConfig('error_page')) {
            $unauthorizedPage = $this->core->getConfig('error_page');
        } else {
            $unauthorizedPage = $this->core->getConfig('site_start');
        }

        return $unauthorizedPage;
    }

    /**
     * Create a 'clean' document identifier with path information, friendly URL suffix and prefix.
     *
     * @param string $qOrig
     * @param string $documentMethod
     * @return string
     */
    public function cleanDocumentIdentifier($qOrig, &$documentMethod) : string
    {
        if (!$qOrig) {
            $qOrig = $this->core->getConfig('site_start');
        }
        $query = $qOrig;

        $pre = $this->core->getConfig('friendly_url_prefix');
        $suf = $this->core->getConfig('friendly_url_suffix');
        $pre = preg_quote($pre, '/');
        $suf = preg_quote($suf, '/');
        if ($pre && preg_match('@^' . $pre . '(.*)$@', $query, $matches)) {
            $query = $matches[1];
        }
        if ($suf && preg_match('@(.*)' . $suf . '$@', $query, $matches)) {
            $query = $matches[1];
        }

        /* First remove any / before or after */
        $query = trim($query, '/');

        /**
         * Save path if any
         * FS#476 and FS#308: only return virtualDir if friendly paths are enabled
         */
        if ($this->core->getConfig('use_alias_path')) {
            $matches = strrpos($query, '/');
            $this->virtualDir = $matches !== false ? substr($query, 0, $matches) : '';
            if ($matches !== false) {
                $query = preg_replace('@.*/@', '', $query);
            }
        } else {
            $this->virtualDir = '';
        }

        $documentMethod = 'alias';
        if (preg_match('@^[1-9]\d*$@', $query) && !isset($this->documentListing[$query])) {
            /**
             * we got an ID returned, check to make sure it's not an alias
             * FS#476 and FS#308: check that id is valid in terms of virtualDir structure
             */
            if ($this->core->getConfig('use_alias_path')) {
                if (//(
                        (
                            $this->virtualDir !== '' &&
                            !isset($this->documentListing[$this->virtualDir . '/' . $query])
                        ) || (
                            $this->virtualDir === '' && !isset($this->documentListing[$query])
                        )
                    //)
                    && (
                        (
                            $this->virtualDir !== '' &&
                            isset($this->documentListing[$this->virtualDir]) &&
                            \in_array($query, $this->core->getChildIds($this->documentListing[$this->virtualDir], 1))
                        ) ||
                        ($this->virtualDir === '' && in_array($query, $this->core->getChildIds(0, 1)))
                    )
                ) {
                    $documentMethod = 'id';
                }
            } else {
                $documentMethod = 'id';
            }
        } else {
            /** we didn't get an ID back, so instead we assume it's an alias */
            if (! (bool)$this->core->getConfig('friendly_alias_urls')) {
                $query = $qOrig;
            }
        }

        return $query;
    }



    /**
     * Get Clean Query String
     *
     * Fixes the issue where passing an array into the q get variable causes errors
     *
     * @param string|array $query
     * @return string
     */
    public function cleanQueryString($query) : string
    {
        $out = '';

        switch (true) {
            /** If we have a string, return it */
            case \is_string($query) && !empty($query):
                $out = $query;
                break;
            /** If we have an array, return the first element */
            case (\is_array($query) && isset($query[0]) && \is_scalar($query[0])):
                $out = $query[0];
                break;
        }

        /** Return null if the query doesn't exist */
        if (empty($query)) {
            $out = '';
        }

        return $out;
    }

    /**
     * @param $id
     * @return null|array
     */
    public function getAliasListing($id) : ?array
    {
        $out = null;
        if (isset($this->aliasListing[$id])) {
            $out = $this->aliasListing[$id];
        } else {
            /** @var Models\SiteContent|null $query */
            $query = Models\SiteContent::where('id', '=', (int)$id)->first();
            if ($query !== null) {
                $this->aliasListing[$id] = array(
                    'id'       => $query->getKey(),
                    'alias'    => $query->alias === '' ? $query->getKey() : $query->alias,
                    'parent'   => $query->parent,
                    'isfolder' => $query->isfolder,
                );
                if ($query->parent > 0) {
                    if ((bool)$this->core->getConfig('use_alias_path')) {
                        $tmp = $this->getAliasListing($query->parent);
                        if ($tmp['alias_visible']) {
                            $this->aliasListing[$id]['path'] = $tmp['path'] . (($tmp['parent'] > 0 && $tmp['path'] !== '') ? '/' : '') . $tmp['alias'];
                        } else {
                            $this->aliasListing[$id]['path'] = $tmp['path'];
                        }
                    } else {
                        $this->aliasListing[$id]['path'] = '';
                    }
                }

                $out = $this->aliasListing[$id];
            }
        }

        return $out;
    }

    /**
     * @param $alias
     * @return null|int
     */
    public function getIdFromAlias($alias)
    {
        if (isset($this->documentListing[$alias])) {
            return $this->documentListing[$alias];
        }

        if ($this->core->getConfig('use_alias_path')) {
            if ($alias === '.') {
                return 0;
            }

            if (strpos($alias, '/') !== false) {
                $aliases = explode('/', $alias);
            } else {
                $aliases = [$alias];
            }
            $id = 0;

            foreach ($aliases as $tmp) {
                if ($id === null) {
                    break;
                }
                /** @var Models\SiteContent $query */
                $query = Models\SiteContent::where('deleted', '=', 0)
                    ->where('parent', '=', $id)
                    ->where('alias', '=', $tmp)
                    ->first();

                if ($query === null) {
                    /** @var Models\SiteContent $query */
                    $query = Models\SiteContent::where('deleted', '=', 0)
                        ->where('parent', '=', $id)
                        ->where('id', '=', $tmp)
                        ->first();
                }

                $id = ($query === null) ? $this->getHiddenIdFromAlias($id, $tmp) : $query->getKey();
            }
        } else {
            /** @var Models\SiteContent $query */
            $query = Models\SiteContent::where('deleted', '=', 0)
                ->where('alias', '=', $alias)
                ->first();

            $id = ($query !== null) ? $query->getKey() : null;
        }
        return $id;
    }

    /**
     * @param int $parentid
     * @param string $alias
     * @return null|int
     */
    public function getHiddenIdFromAlias(int $parentid, string $alias) : ?int
    {
        $out = false;
        if ($alias !== '') {
            $table = $this->getFullTableName('site_content');
            $query = $this->db->query("SELECT 
                `sc`.`id` AS `hidden_id`,
                `children`.`id` AS `child_id`,
                children.alias AS `child_alias`,
                COUNT(`grandsons`.`id`) AS `grandsons_count`
              FROM " . $table ." AS `sc`
              JOIN " . $table . " AS `children` ON `children`.`parent` = `sc`.`id`
              LEFT JOIN " . $table . " AS `grandsons` ON `grandsons`.`parent` = `children`.`id`
              WHERE `sc`.`parent` = '" . (int)$parentid . "' AND `sc`.`alias_visible` = '0'
              GROUP BY `children`.`id`");
            while ($child = $this->db->getRow($query)) {
                if ($child['child_alias'] == $alias || $child['child_id'] == $alias) {
                    $out = $child['child_id'];
                    break;
                } else if ($child['grandsons_count'] > 0 && ($id = $this->getHiddenIdFromAlias($child['child_id'], $alias))) {
                    $out = $id;
                    break;
                }
            }
        }
        return $out;
    }

    /**
     * Create an URL for the given document identifier. The url prefix and postfix are used, when “friendly_url” is active.
     *
     * @param int $id The document identifier. @required
     * @param string $alias The alias name for the document. Default: ''.
     * @param string $args The paramaters to add to the URL. Default: ''.
     * @param string $scheme With full as valus, the site url configuration is used. Default: ''.
     * @return string Result URL.
     */
    public function makeUrl(int $id, string $alias = '', string $args = '', string $scheme = '') : string
    {
        $url = '';
        $virtualDir = $this->core->getConfig('virtual_dir', '');
        $f_url_prefix = $this->core->getConfig('friendly_url_prefix');
        $f_url_suffix = $this->core->getConfig('friendly_url_suffix');

        if ($args !== '') {
            // add ? or & to $args if missing
            $args = ltrim($args, '?&');
            $_ = strpos($f_url_prefix, '?');

            if ($_ === false && $this->core->getConfig('friendly_urls')) {
                $args = "?{$args}";
            } else {
                $args = "&{$args}";
            }
        }

        if ($id !== $this->core->getConfig('site_start')) {
            if ($this->core->getConfig('friendly_urls') && $alias == '') {
                $alias = (string)$id;
                $alPath = '';

                if ($this->core->getConfig('friendly_alias_urls')) {

                    if ($this->core->getConfig('aliaslistingfolder')) {
                        $al = $this->getAliasListing($id);
                    } else {
                        $al = $this->aliasListing[$id] ?? null;
                    }

                    if(\is_array($al)) {
                        if ($al['isfolder'] === 1 && $this->core->getConfig('make_folders')) {
                            $f_url_suffix = '/';
                        }
                        $alPath = !empty($al['path']) ? $al['path'] . '/' : '';

                        if (isset($al['alias'])) {
                            $alias = $al['alias'];
                        }
                    }
                }

                $alias = $alPath . $f_url_prefix . $alias . $f_url_suffix;
                $url = "{$alias}{$args}";
            } else {
                $url = "index.php?id={$id}{$args}";
            }
        } else {
            $url = $args;
        }

        $host = $this->core->getConfig('base_url');

        // check if scheme argument has been set
        if ($scheme != '') {
            // for backward compatibility - check if the desired scheme is different than the current scheme
            if (is_numeric($scheme) && $scheme != $_SERVER['HTTPS']) {
                $scheme = ($_SERVER['HTTPS'] ? 'http' : 'https');
            }

            //TODO: check to make sure that $site_url incudes the url :port (e.g. :8080)
            $host = $scheme == 'full' ? $this->core->getConfig('site_url') : $scheme . '://' . $_SERVER['HTTP_HOST'] . $host;
        }

        //fix strictUrl by Bumkaka
        if ($this->core->getConfig('seostrict')) {
            $url = $this->toAlias($url);
        }

        if ($this->core->getConfig('xhtml_urls')) {
            $url = preg_replace("/&(?!amp;)/", "&amp;", $host . $virtualDir . $url);
        } else {
            $url = $host . $virtualDir . $url;
        }

        $evtOut = $this->core->invokeEvent('OnMakeDocUrl', array(
            'id'  => $id,
            'url' => $url
        ));

        if (is_array($evtOut) && count($evtOut) > 0) {
            $url = array_pop($evtOut);
        }

        return $url;
    }

    public function strictURI(string $query, int $id) :? string
    {
        $out = null;
        // FIX URLs
        if (empty($id) ||
            $this->core->getConfig('seostrict') === false ||
            $this->core->getConfig('friendly_urls') === false ||
            $this->core->getConfig('site_status') === false
        ) {
            return $out;
        }

        $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
        $len_base_url = strlen($this->core->getConfig('base_url'));

        $url_path = $query;//LANG

        if (substr($url_path, 0, $len_base_url) === $this->core->getConfig('base_url')) {
            $url_path = substr($url_path, $len_base_url);
        }

        $strictURL = $this->makeUrl($id);
        $strictURL = $this->toAlias($strictURL);

        if (substr($strictURL, 0, $len_base_url) === $this->core->getConfig('base_url')) {
            $strictURL = substr($strictURL, $len_base_url);
        }
        $http_host = $_SERVER['HTTP_HOST'];
        $requestedURL = "{$scheme}://{$http_host}" . '/' . $query; //LANG

        $url_query_string = explode('?', $_SERVER['REQUEST_URI']);
        // Strip conflicting id/q from query string
        $qstring = !empty($url_query_string[1]) ? preg_replace("#(^|&)(q|id)=[^&]+#", '', $url_query_string[1]) : '';

        if ($id === (int)$this->core->getConfig('site_start')) {
            if ($requestedURL !== $this->core->getConfig('site_url')) {
                $url = $this->core->getConfig('site_url');
                if ($qstring) {
                     $url .= '?' . $qstring;
                }

                if ($this->core->getConfig('base_url') != $_SERVER['REQUEST_URI']) {
                    if (empty($_POST)) {
                        if (($this->core->getConfig('base_url') . '?' . $qstring) != $_SERVER['REQUEST_URI']) {
                            $out = $url;
                        }
                    }
                }
            }
        } elseif ($url_path !== $strictURL && $id !== (int)$this->core->getConfig('error_page')) {
            if (!empty($qstring)) {
                $url = $this->core->getConfig('site_url') . $strictURL . '?' . $qstring;
            } else {
                $url = $this->core->getConfig('site_url') . $strictURL;
            }
            $out = $url;
        }

        return $out;
    }
}
