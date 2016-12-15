<?php
require_once('MODx.php');

/**
 * Class modResource
 */
class modResource extends MODxAPI
{
    /**
     * @var string
     */
    protected $mode = 'new';
    /**
     * @var array
     */
    protected $default_field = array(
        'type'            => 'document',
        'contentType'     => 'text/html',
        'pagetitle'       => 'New document',
        'longtitle'       => '',
        'description'     => '',
        'alias'           => '',
        'link_attributes' => '',
        'published'       => 1,
        'pub_date'        => 0,
        'unpub_date'      => 0,
        'parent'          => 0,
        'isfolder'        => 0,
        'introtext'       => '',
        'content'         => '',
        'richtext'        => 1,
        'template'        => 0,
        'menuindex'       => 0,
        'searchable'      => 1,
        'cacheable'       => 1,
        'createdon'       => 0,
        'createdby'       => 0,
        'editedon'        => 0,
        'editedby'        => 0,
        'deleted'         => 0,
        'deletedon'       => 0,
        'deletedby'       => 0,
        'publishedon'     => 0,
        'publishedby'     => 0,
        'menutitle'       => '',
        'donthit'         => 0,
        'haskeywords'     => 0,
        'hasmetatags'     => 0,
        'privateweb'      => 0,
        'privatemgr'      => 0,
        'content_dispo'   => 0,
        'hidemenu'        => 0,
        'alias_visible'   => 1
    );
    /**
     * @var array
     */
    private $table = array(
        '"' => '_',
        "'" => '_',
        ' ' => '_',
        '.' => '_',
        ',' => '_',
        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'e',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'y',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'sch',
        'ь' => '',
        'ы' => 'y',
        'ъ' => '',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',
        'А' => 'A',
        'Б' => 'B',
        'В' => 'V',
        'Г' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'E',
        'Ж' => 'Zh',
        'З' => 'Z',
        'И' => 'I',
        'Й' => 'Y',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'H',
        'Ц' => 'C',
        'Ч' => 'Ch',
        'Ш' => 'Sh',
        'Щ' => 'Sch',
        'Ь' => '',
        'Ы' => 'Y',
        'Ъ' => '',
        'Э' => 'E',
        'Ю' => 'Yu',
        'Я' => 'Ya',
    );
    /**
     * @var array массив ТВшек где name это ключ массива, а ID это значение
     */
    private $tv = array();
    /**
     * @var array массив ТВшек где ID это ключ массива, а name это значение
     */
    private $tvid = array();
    /**
     * @var array значения по умолчанию для ТВ параметров
     */
    private $tvd = array();

    /** @var array связи ТВ и шаблонов */
    private $tvTpl = array();

    /**
     * Массив администраторов
     * @var DLCollection
     */
    private $managerUsers = null;

    /**
     * modResource constructor.
     * @param DocumentParser $modx
     * @param bool $debug
     */
    public function __construct($modx, $debug = false)
    {
        parent::__construct($modx, $debug);
        $this->get_TV();
        $uTable = $this->makeTable("manager_users");
        $aTable = $this->makeTable("user_attributes");
        $query = "SELECT `u`.`id`, `a`.`email`, `u`.`username`  FROM " . $aTable . " as `a` LEFT JOIN " . $uTable . " as `u` ON `u`.`id`=`a`.`internalKey`";
        $query = $this->query($query);
        $this->managerUsers = new DLCollection($modx, empty($query) ? array() : $query);
    }

    /**
     * @return array
     */
    public function toArrayMain()
    {
        $out = array_intersect_key(parent::toArray(), $this->default_field);

        return $out;
    }

    /**
     * @param bool $render
     * @return array
     */
    public function toArrayTV($render = false)
    {
        $out = array_diff_key(parent::toArray(), $this->default_field);
        $tpl = $this->get('template');
        $tvTPL = APIHelpers::getkey($this->tvTpl, $tpl, array());
        foreach ($tvTPL as $item) {
            if (isset($this->tvid[$item]) && !array_key_exists($this->tvid[$item], $out)) {
                $out[$this->tvid[$item]] = $this->get($this->tvid[$item]);
            }
        }
        if ($render) {
            foreach ($out as $key => $val) {
                $out[$key] = $this->renderTV($key);
            }
        }

        return $out;
    }

    /**
     * @param string $prefix
     * @param string $suffix
     * @param string $sep
     * @param bool $render
     * @return array
     */
    public function toArray($prefix = '', $suffix = '', $sep = '_', $render = true)
    {
        $out = array_merge(
            $this->toArrayMain(),
            $this->toArrayTV($render),
            array($this->fieldPKName() => $this->getID())
        );

        return \APIhelpers::renameKeyArr($out, $prefix, $suffix, $sep);
    }

    /**
     * @return null|string
     */
    public function getUrl()
    {
        $out = null;
        $id = (int)$this->getID();
        if (!empty($id)) {
            $out = $this->modx->makeUrl($id);
        }

        return $out;
    }

    /**
     * @param string $main
     * @param string $second
     * @return mixed
     */
    public function getTitle($main = 'menutitle', $second = 'pagetitle')
    {
        $title = $this->get($main);
        if (empty($title) && $title !== '0') {
            $title = $this->get($second);
        }

        return $title;
    }

    /**
     * @return bool
     */
    public function isWebShow()
    {
        $pub = ($this->get('publishedon') < time() && $this->get('published'));
        $unpub = ($this->get('unpub_date') == 0 || $this->get('unpub_date') > time());
        $del = ($this->get('deleted') == 0 && ($this->get('deletedon') == 0 || $this->get('deletedon') > time()));

        return ($pub && $unpub && $del);
    }

    /**
     * @return $this
     */
    public function touch()
    {
        $this->set('editedon', time());

        return $this;
    }

    /**
     * @param $tvname
     * @return null|string
     */
    public function renderTV($tvname)
    {
        $out = null;
        if ($this->getID() > 0) {
            include_once MODX_MANAGER_PATH . "includes/tmplvars.format.inc.php";
            include_once MODX_MANAGER_PATH . "includes/tmplvars.commands.inc.php";
            $tvval = $this->get($tvname);
            $param = APIHelpers::getkey($this->tvd, $tvname, array());
            $display = APIHelpers::getkey($param, 'display', '');
            $display_params = APIHelpers::getkey($param, 'display_params', '');
            $type = APIHelpers::getkey($param, 'type', '');
            $out = getTVDisplayFormat($tvname, $tvval, $display, $display_params, $type, $this->getID(), '');
        }

        return $out;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        $out = parent::get($key);
        if (isset($this->tv[$key])) {
            $tpl = $this->get('template');
            $tvTPL = APIHelpers::getkey($this->tvTpl, $tpl, array());
            $tvID = APIHelpers::getkey($this->tv, $key, 0);
            if (in_array($tvID, $tvTPL) && is_null($out)) {
                $out = APIHelpers::getkey($this->tvd[$key], 'value', null);
            }
        }

        return $out;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($key, $value)
    {
        if (is_scalar($value) && is_scalar($key) && !empty($key)) {
            switch ($key) {
                case 'parent':
                    $value = (int)$value;
                    break;
                case 'template':
                    $value = trim($value);
                    $value = $this->setTemplate($value);
                    break;
                case 'published':
                    $value = (int)((bool)$value);
                    if ($value) {
                        $this->field['publishedon'] = time() + $this->modxConfig('server_offset_time');
                    }
                    break;
                case 'pub_date':
                    $value = $this->getTime($value);
                    if ($value > 0 && time() + $this->modxConfig('server_offset_time') > $value) {
                        $this->field['published'] = 1;
                        $this->field['publishedon'] = $value;
                    }
                    break;
                case 'unpub_date':
                    $value = $this->getTime($value);
                    if ($value > 0 && time() + $this->modxConfig('server_offset_time') > $value) {
                        $this->field['published'] = 0;
                        $this->field['publishedon'] = 0;
                    }
                    break;
                case 'deleted':
                    $value = (int)((bool)$value);
                    if ($value) {
                        $this->field['deletedon'] = time() + $this->modxConfig('server_offset_time');
                    } else {
                        $this->field['deletedon'] = 0;
                    }
                    break;
                case 'deletedon':
                    $value = $this->getTime($value);
                    if ($value > 0 && time() + $this->modxConfig('server_offset_time') < $value) {
                        $value = 0;
                    }
                    if ($value) {
                        $this->field['deleted'] = 1;
                    }
                    break;
                case 'editedon':
                case 'createdon':
                case 'publishedon':
                    $value = $this->getTime($value);
                    break;
                case 'publishedby':
                case 'editedby':
                case 'createdby':
                case 'deletedby':
                    $value = $this->getUser($value, $this->default_field[$key]);
                    break;
            }
            $this->field[$key] = $value;
        }

        return $this;
    }

    /**
     * @param $value
     * @param int $default
     * @return int|mixed
     */
    protected function getUser($value, $default = 0)
    {
        $currentAdmin = APIHelpers::getkey($_SESSION, 'mgrInternalKey', 0);
        $value = (int)$value;
        if (!empty($value)) {
            $by = $this->findUserBy($value);
            $exists = $this->managerUsers->exists(function ($key, Helpers\Collection $val) use ($by, $value) {
                return ($val->containsKey($by) && $val->get($by) === (string)$value);
            });
            if (!$exists) {
                $value = 0;
            }
        }
        if (empty($value)) {
            $value = empty($currentAdmin) ? $default : $currentAdmin;
        }

        return $value;
    }

    /**
     * @param $data
     * @return bool|string
     */
    protected function findUserBy($data)
    {
        switch (true) {
            case (is_int($data) || ((int)$data > 0 && (string)intval($data) === $data)):
                $find = 'id';
                break;
            case filter_var($data, FILTER_VALIDATE_EMAIL):
                $find = 'email';
                break;
            case is_scalar($data):
                $find = 'username';
                break;
            default:
                $find = false;
        }

        return $find;
    }

    /**
     * @param $value
     * @return int|mixed|string
     */
    protected function getTime($value)
    {
        $value = trim($value);
        if (!empty($value)) {
            if (!is_numeric($value)) {
                $value = (int)strtotime($value);
            }
            if (!empty($value)) {
                $value += $this->modxConfig('server_offset_time');
            }
        }

        return $value;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function create($data = array())
    {
        parent::create($data);
        $this->set('createdby', null)
            ->set('editedby', null)
            ->set('createdon', time())
            ->touch();

        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function edit($id)
    {
        $id = is_scalar($id) ? trim($id) : '';
        if ($this->getID() != $id) {
            $this->close();
            $this->newDoc = false;

            $result = $this->query("SELECT * from {$this->makeTable('site_content')} where `id`=" . (int)$id);
            $this->fromArray($this->modx->db->getRow($result));
            $result = $this->query("SELECT * from {$this->makeTable('site_tmplvar_contentvalues')} where `contentid`=" . (int)$id);
            while ($row = $this->modx->db->getRow($result)) {
                $this->field[$this->tvid[$row['tmplvarid']]] = $row['value'];
            }
            if (empty($this->field['id'])) {
                $this->id = null;
            } else {
                $this->id = $this->field['id'];
                $this->set('editedby', null)->touch();
            }
            $this->store($this->toArray(null, null, null, false));
            unset($this->field['id']);
        }

        return $this;
    }

    /**
     * @param bool $fire_events
     * @param bool $clearCache
     * @return bool|null|void
     */
    public function save($fire_events = false, $clearCache = false)
    {
        $parent = null;
        if ($this->field['pagetitle'] == '') {
            $this->log['emptyPagetitle'] = 'Pagetitle is empty in <pre>' . print_r($this->field, true) . '</pre>';

            return false;
        }

        $uid = $this->modx->getLoginUserID('mgr');

        if (
            $this->field['parent'] == 0 &&
            !$this->modxConfig('udperms_allowroot') &&
            !($uid && isset($_SESSION['mgrRole']) && $_SESSION['mgrRole'] == 1)
        ) {
            $this->log['rootForbidden'] = 'Only Administrators can create documents in the root folder because udperms_allowroot setting is off';

            return false;
        }

        $this->set('alias', $this->getAlias());

        $this->invokeEvent('OnBeforeDocFormSave', array(
            'mode'   => $this->newDoc ? "new" : "upd",
            'id'     => $this->id ? $this->id : '',
            'doc'    => $this->toArray(),
            'docObj' => $this
        ), $fire_events);

        $fld = $this->toArray(null, null, null, false);
        foreach ($this->default_field as $key => $value) {
            $tmp = $this->get($key);
            if ($this->newDoc && (!is_int($tmp) && $tmp == '')) {
                if ($tmp == $value) {
                    switch ($key) {
                        case 'cacheable':
                            $value = $this->modxConfig('cache_default');
                            break;
                        case 'template':
                            $value = $value = $this->modxConfig('default_template');
                            break;
                        case 'published':
                            $value = $this->modxConfig('publish_default');
                            break;
                        case 'searchable':
                            $value = $this->modxConfig('search_default');
                            break;
                        case 'donthit':
                            $value = $this->modxConfig('track_visitors');
                            break;
                    }
                }
                $this->field[$key] = $value;
            }
            switch (true) {
                case $key == 'parent':
                    $parent = (int)$this->get($key);
                    $q = $this->query("SELECT count(`id`) FROM {$this->makeTable('site_content')} WHERE `id`='{$parent}'");
                    if ($this->modx->db->getValue($q) != 1) {
                        $parent = 0;
                    }
                    $this->field[$key] = $parent;
                    $this->Uset($key);
                    break;
                case ($key == 'alias_visible' && !$this->checkVersion('1.0.10', true)):
                    $this->eraseField('alias_visible');
                    break;
                default:
                    $this->Uset($key);
            }
            unset($fld[$key]);
        }

        if (!empty($this->set)) {
            if ($this->newDoc) {
                $SQL = "INSERT into {$this->makeTable('site_content')} SET " . implode(', ', $this->set);
            } else {
                $SQL = "UPDATE {$this->makeTable('site_content')} SET " . implode(', ',
                        $this->set) . " WHERE `id` = " . $this->id;
            }
            $this->query($SQL);

            if ($this->newDoc) {
                $this->id = $this->modx->db->getInsertId();
            }

            if ($parent > 0) {
                $this->query("UPDATE {$this->makeTable('site_content')} SET `isfolder`='1' WHERE `id`='{$parent}'");
            }
        }

        $_deleteTVs = $_updateTVs = $_insertTVs = array();
        foreach ($fld as $key => $value) {
            if (empty($this->tv[$key]) || !$this->isChanged($key)) {
                continue;
            } elseif ($value === '') {
                $_deleteTVs[] = $this->tv[$key];
            } else {
                $_insertTVs[$this->tv[$key]] = $this->escape($value);
            }
        }

        if (!$this->newDoc && !empty($_insertTVs)) {
            $ids = implode(',',array_keys($_insertTVs));
            $result = $this->query("SELECT `tmplvarid` FROM {$this->makeTable('site_tmplvar_contentvalues')} WHERE `contentid`={$this->id} AND `tmplvarid` IN ({$ids})");
            $existedTVs = $this->modx->db->getColumn('tmplvarid',$result);
            foreach ($existedTVs as $id) {
                $_updateTVs[$id] = $_insertTVs[$id];
                unset($_insertTVs[$id]);
            }
        }

        if (!empty($_updateTVs)) {
            foreach($_updateTVs as $id => $value) {
                $this->query("UPDATE {$this->makeTable('site_tmplvar_contentvalues')} SET `value` = '{$value}' WHERE `contentid` = {$this->id} AND `tmplvarid` = {$id}");
            }
        }

        if (!empty($_insertTVs)) {
            $values = array();
            foreach ($_insertTVs as $id => $value) {
                $values[] = "({$this->id}, {$id}, '{$value}')";
            }
            $values = implode(',',$values);
            $this->query("INSERT into {$this->makeTable('site_tmplvar_contentvalues')} (`contentid`,`tmplvarid`,`value`) VALUES {$values}");
        }

        if (!empty($_deleteTVs)) {
            $ids = implode(',',$_deleteTVs);
            $this->query("DELETE FROM {$this->makeTable('site_tmplvar_contentvalues')} WHERE `contentid` = '{$this->id}' AND `tmplvarid` IN ({$ids})");
        }

        if (!isset($this->mode)) {
            $this->mode = $this->newDoc ? "new" : "upd";
            $this->newDoc = false;
        }
        $this->invokeEvent('OnDocFormSave', array(
            'mode'   => $this->mode,
            'id'     => $this->id,
            'doc'    => $this->toArray(),
            'docObj' => $this
        ), $fire_events);

        if ($clearCache) {
            $this->clearCache($fire_events);
        }

        return $this->id;
    }

    /**
     * @param $ids
     * @return $this
     * @throws Exception
     */
    public function toTrash($ids)
    {
        $ignore = $this->systemID();
        $_ids = $this->cleanIDs($ids, ',', $ignore);
        if (is_array($_ids) && $_ids != array()) {
            $id = $this->sanitarIn($_ids);
            $uid = (int)$this->modx->getLoginUserId();
            $deletedon = time() + $this->modxConfig('server_offset_time');
            $this->query("UPDATE {$this->makeTable('site_content')} SET `deleted`=1, `deletedby`={$uid}, `deletedon`={$deletedon} WHERE `id` IN ({$id})");
        } else {
            throw new Exception('Invalid IDs list for mark trash: <pre>' . print_r($ids,
                    1) . '</pre> please, check ignore list: <pre>' . print_r($ignore, 1) . '</pre>');
        }

        return $this;
    }

    /**
     * @param bool $fire_events
     * @return $this
     */
    public function clearTrash($fire_events = false)
    {
        $q = $this->query("SELECT `id` FROM {$this->makeTable('site_content')} WHERE `deleted`='1'");
        $_ids = $this->modx->db->getColumn('id', $q);
        if (is_array($_ids) && $_ids != array()) {
            $this->invokeEvent('OnBeforeEmptyTrash', array(
                "ids" => $_ids
            ), $fire_events);

            $id = $this->sanitarIn($_ids);
            $this->query("DELETE from {$this->makeTable('site_content')} where `id` IN ({$id})");
            $this->query("DELETE from {$this->makeTable('site_tmplvar_contentvalues')} where `contentid` IN ({$id})");

            $this->invokeEvent('OnEmptyTrash', array(
                "ids" => $_ids
            ), $fire_events);
        }

        return $this;
    }

    /**
     * @param $ids
     * @param int|bool $depth
     * @return array
     */
    public function children($ids, $depth)
    {
        $_ids = $this->cleanIDs($ids, ',');
        if (is_array($_ids) && $_ids != array()) {
            $id = $this->sanitarIn($_ids);
            if (!empty($id)) {
                $q = $this->query("SELECT `id` FROM {$this->makeTable('site_content')} where `parent` IN ({$id})");
                $id = $this->modx->db->getColumn('id', $q);
                if ($depth > 0 || $depth === true) {
                    $id = $this->children($id, is_bool($depth) ? $depth : ($depth - 1));
                }
                $_ids = array_merge($_ids, $id);
            }
        }

        return $_ids;
    }

    /**
     * @param string|array $ids
     * @param bool $fire_events
     * @return $this
     * @throws Exception
     */
    public function delete($ids, $fire_events = false)
    {
        $ids = $this->children($ids, true);
        $_ids = $this->cleanIDs($ids, ',', $this->systemID());
        $this->invokeEvent('OnBeforeDocFormDelete', array(
            'ids' => $_ids
        ), $fire_events);
        $this->toTrash($_ids);
        $this->invokeEvent('OnDocFormDelete', array(
            'ids' => $_ids
        ), $fire_events);

        return $this;
    }

    /**
     * @return array
     */
    private function systemID()
    {
        $ignore = array(
            0, //empty document
            (int)$this->modxConfig('site_start'),
            (int)$this->modxConfig('error_page'),
            (int)$this->modxConfig('unauthorized_page'),
            (int)$this->modxConfig('site_unavailable_page')
        );
        $data = $this->query("SELECT DISTINCT setting_value FROM {$this->makeTable('web_user_settings')} WHERE `setting_name`='login_home' AND `setting_value`!=''");
        $data = $this->modx->db->makeArray($data);
        foreach ($data as $item) {
            $ignore[] = (int)$item['setting_value'];
        }

        return array_unique($ignore);

    }

    /**
     * @param $alias
     * @return string
     */
    private function checkAlias($alias)
    {
        $alias = strtolower($alias);
        if ($this->modxConfig('friendly_urls')) {
            $_alias = $this->escape($alias);
            if ((!$this->modxConfig('allow_duplicate_alias') && !$this->modxConfig('use_alias_path')) || ($this->modxConfig('allow_duplicate_alias') && $this->modxConfig('use_alias_path'))) {
                $flag = $this->modx->db->getValue($this->query("SELECT `id` FROM {$this->makeTable('site_content')} WHERE `alias`='{$_alias}' AND `parent`={$this->get('parent')} LIMIT 1"));
            } else {
                $flag = $this->modx->db->getValue($this->query("SELECT `id` FROM {$this->makeTable('site_content')} WHERE `alias`='{$_alias}' LIMIT 1"));
            }
            if (($flag && $this->newDoc) || (!$this->newDoc && $flag && $this->id != $flag)) {
                $suffix = substr($alias, -2);
                if (preg_match('/-(\d+)/', $suffix, $tmp) && isset($tmp[1]) && (int)$tmp[1] > 1) {
                    $suffix = (int)$tmp[1] + 1;
                    $alias = substr($alias, 0, -2) . '-' . $suffix;
                } else {
                    $alias .= '-2';
                }
                $alias = $this->checkAlias($alias);
            }
        }

        return $alias;
    }

    /**
     * @param $key
     * @return bool
     */
    public function issetField($key)
    {
        return (array_key_exists($key, $this->default_field) || array_key_exists($key, $this->tv));
    }

    /**
     * @param bool $reload
     * @return $this
     */
    protected function get_TV($reload = false)
    {
        if (empty($this->modx->_TVnames) || $reload) {
            $result = $this->query('SELECT `id`,`name` FROM ' . $this->makeTable('site_tmplvars'));
            while ($row = $this->modx->db->GetRow($result)) {
                $this->modx->_TVnames[$row['name']] = $row['id'];
            }
        }
        foreach ($this->modx->_TVnames as $name => $id) {
            $this->tvid[$id] = $name;
            $this->tv[$name] = $id;
        }
        $this->loadTVTemplate()->loadTVDefault(array_values($this->tv));

        return $this;
    }

    /**
     * @return $this
     */
    protected function loadTVTemplate()
    {
        $q = $this->query("SELECT `tmplvarid`, `templateid` FROM " . $this->makeTable('site_tmplvar_templates'));
        $q = $this->modx->db->makeArray($q);
        $this->tvTpl = array();
        foreach ($q as $item) {
            $this->tvTpl[$item['templateid']][] = $item['tmplvarid'];
        }

        return $this;
    }

    /**
     * @param array $tvId
     * @return $this
     */
    protected function loadTVDefault(array $tvId = array())
    {
        if (is_array($tvId) && !empty($tvId)) {
            $tbl_site_tmplvars = $this->makeTable('site_tmplvars');
            $fields = 'id,name,default_text as value,display,display_params,type';
            $implodeTvId = implode(',', $tvId);
            $rs = $this->query("SELECT {$fields} FROM {$tbl_site_tmplvars} WHERE id IN({$implodeTvId})");
            $rows = $this->modx->db->makeArray($rs);
            $this->tvd = array();
            foreach ($rows as $item) {
                $this->tvd[$item['name']] = $item;
            }
        }

        return $this;
    }

    /**
     * @param $tpl
     * @return int
     * @throws Exception
     */
    public function setTemplate($tpl)
    {
        if (!is_numeric($tpl) || $tpl != (int)$tpl) {
            if (is_scalar($tpl)) {
                $sql = "SELECT `id` FROM {$this->makeTable('site_templates')} WHERE `templatename` = '" . $this->escape($tpl) . "'";
                $rs = $this->query($sql);
                if (!$rs || $this->modx->db->getRecordCount($rs) <= 0) {
                    throw new Exception("Template {$tpl} is not exists");
                }
                $tpl = $this->modx->db->getValue($rs);
            } else {
                throw new Exception("Invalid template name: " . print_r($tpl, 1));
            }
        }

        return (int)$tpl;
    }

    /**
     * @return string
     */
    private function getAlias()
    {
        if ($this->modxConfig('friendly_urls') && $this->modxConfig('automatic_alias') && $this->get('alias') == '') {
            $alias = strtr($this->get('pagetitle'), $this->table);
        } else {
            if ($this->get('alias') != '') {
                $alias = $this->get('alias');
            } else {
                $alias = '';
            }
        }
        $alias = $this->modx->stripAlias($alias);

        return $this->checkAlias($alias);
    }

    /**
     * @param int $parent
     * @param string $criteria
     * @param string $dir
     * @return $this
     *
     * Пересчет menuindex по полю таблицы site_content
     */
    public function updateMenuindex($parent, $criteria = 'id', $dir = 'asc')
    {
        $dir = strtolower($dir) == 'desc' ? 'desc' : 'asc';
        if (is_integer($parent) && $criteria !== '') {
            $this->query("SET @index := 0");
            $this->query("UPDATE {$this->makeTable('site_content')} SET `menuindex` = (@index := @index + 1) WHERE `parent`={$parent} ORDER BY {$criteria} {$dir}");
        }

        return $this;
    }

    /**
     * Устанавливает значение шаблона согласно системной настройке
     *
     * @return $this
     */
    public function setDefaultTemplate()
    {
        $parent = $this->get('parent');
        $template = $this->modxConfig('default_template');
        switch ($this->modxConfig('auto_template_logic')) {
            case 'sibling':
                if (!$parent) {
                    $site_start = $this->modxConfig('site_start');
                    $where = "sc.isfolder=0 AND sc.id!={$site_start}";
                    $sibl = $this->modx->getDocumentChildren($parent, 1, 0, 'template', $where, 'menuindex', 'ASC', 1);
                    if (isset($sibl[0]['template']) && $sibl[0]['template'] !== '') {
                        $template = $sibl[0]['template'];
                    }
                } else {
                    $sibl = $this->modx->getDocumentChildren($parent, 1, 0, 'template', 'isfolder=0', 'menuindex',
                        'ASC', 1);
                    if (isset($sibl[0]['template']) && $sibl[0]['template'] !== '') {
                        $template = $sibl[0]['template'];
                    } else {
                        $sibl = $this->modx->getDocumentChildren($parent, 0, 0, 'template', 'isfolder=0', 'menuindex',
                            'ASC', 1);
                        if (isset($sibl[0]['template']) && $sibl[0]['template'] !== '') {
                            $template = $sibl[0]['template'];
                        }
                    }
                }
                break;
            case 'parent':
                if ($parent) {
                    $_parent = $this->modx->getPageInfo($parent, 0, 'template');
                    if (isset($_parent['template'])) {
                        $template = $_parent['template'];
                    }
                }
                break;
        }
        $this->set('template', $template);

        return $this;
    }
}
