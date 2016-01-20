<?php namespace SimpleTab;
require_once (MODX_BASE_PATH . 'assets/lib/MODxAPI/autoTable.abstract.php');
require_once (MODX_BASE_PATH . 'assets/lib/Helpers/FS.php');
require_once (MODX_BASE_PATH . 'assets/lib/Helpers/PHPThumb.php');

class dataTable extends \autoTable {
    protected $params = array();
    protected $fs = null;

    public function __construct($modx, $debug = false) {
        parent::__construct($modx, $debug);
        $this->modx = $modx;
        $this->params = (isset($modx->event->params) && is_array($modx->event->params)) ? $modx->event->params : array();
        $this->fs = \Helpers\FS::getInstance();
    }

    protected function clearIndexes($ids, $rid) {
        $ids = $this->cleanIDs($ids, ',', array(0));
        $ids = $this->sanitarIn($ids);
        $table = $this->makeTable($this->table);
        $rows = $this->query("SELECT MIN(`{$this->indexName}`) FROM {$table} WHERE `{$this->pkName}` IN ({$ids})");
        $index = $this->modx->db->getValue($rows);
        $index = $index - 1;
        $this->query("SET @index := ".$index);
        $this->query("UPDATE {$table} SET `{$this->indexName}` = (@index := @index + 1) WHERE (`{$this->indexName}`>{$index} AND `{$this->rfName}`={$rid} AND `{$this->pkName}` NOT IN ({$ids})) ORDER BY `{$this->indexName}` ASC");
        $out = $this->modx->db->getAffectedRows();
        return $out;
    }

    public function touch($field){
        $this->set($field, date('Y-m-d H:i:s', time() + $this->modx->config['server_offset_time']));
        return $this;
    }

    /**
     * @param $ids
     * @param $dir
     * @param $rid
     */
    public function place($ids, $dir, $rid) {
        $table = $this->makeTable($this->table);
        $ids = $this->cleanIDs($ids, ',', array(0));
        if(empty($ids) || is_scalar($ids)) return false;
        $rows = $this->query("SELECT count(`{$this->pkName}`) FROM {$table} WHERE `{$this->rfName}`={$rid}");
        $index = $this->modx->db->getValue($rows);
        $cnt = count($ids);
        $ids = implode(',',$ids);
        if ($dir == 'top') {
            $this->query("SET @index := " . ($index - $cnt - 1));
            $this->query("UPDATE {$table} SET `{$this->indexName}` = (@index := @index + 1) WHERE (`{$this->pkName}` IN ({$ids})) ORDER BY `{$this->indexName}` ASC");
            $this->query("SET @index := -1");
        } else {
            $this->query("SET @index := -1");
            $this->query("UPDATE {$table} SET `{$this->indexName}` = (@index := @index + 1) WHERE (`{$this->pkName}` IN ({$ids})) ORDER BY `{$this->indexName}` ASC");
            $this->query("SET @index := " . ($cnt - 1));
        }
        $this->query("UPDATE {$table} SET `{$this->indexName}` = (@index := @index + 1) WHERE (`{$this->pkName}` NOT IN ({$ids})) AND `{$this->rfName}` = {$rid} ORDER BY `{$this->indexName}` ASC");
        $out = $this->modx->db->getAffectedRows();
        return $out;
    }

    /**
     * @param $url
     * @param bool $cache
     */
    public function deleteThumb($url, $cache = false) {
        $url = $this->fs->relativePath($url);
        if (empty($url)) return;
        if ($this->fs->checkFile($url)) unlink(MODX_BASE_PATH . $url);
        $dir = $this->fs->takeFileDir($url);
        $iterator = new \FilesystemIterator($dir);
        if (!$iterator->valid()) rmdir($dir);
        if ($cache) return;
        $thumbsCache = isset($this->params['thumbsCache']) ? $this->params['thumbsCache'] : $this->thumbsCache;
        $thumb = $thumbsCache.$url;
        if ($this->fs->checkFile($thumb)) $this->deleteThumb($thumb, true);
    }

    public function delete($ids, $fire_events = NULL) {
        $out = parent::delete($ids, $fire_events);
        $this->query("ALTER TABLE {$this->makeTable($this->table)} AUTO_INCREMENT = 1");
        return $out;
    }

    public function deleteAll ($ids, $rid, $fire_events = NULL) {
        $this->clearIndexes($ids, $rid);
        return $this->delete($ids, $fire_events);
    }

    public function fieldNames(){
        $fields = array_keys($this->getDefaultFields());
        $fields[] = $this->fieldPKName();
        return $fields;
    }

    /**
     * @param  string $name
     * @return string
     */
    public function stripName($name) {
        return $this->modx->stripAlias($name);
    }

    public function reorder($source, $target, $point, $rid, $orderDir) {
        $rid = (int)$rid;
        $point = strtolower($point);
        $orderDir = strtolower($orderDir);
        $sourceIndex = (int)$source[$this->indexName];
        $targetIndex = (int)$target[$this->indexName];
        $sourceId = (int)$source[$this->pkName];
        $table = $this->makeTable($this->table);
        $rows = 0;
        /* more refactoring  needed */
        if ($targetIndex < $sourceIndex) {
            if (($point == 'top' && $orderDir == 'asc') || ($point == 'bottom' && $orderDir == 'desc')) {
                $rows = $this->modx->db->update("`{$this->indexName}`=`{$this->indexName}`+1",$table,"`{$this->indexName}`>={$targetIndex} AND `{$this->indexName}`<{$sourceIndex} AND `{$this->rfName}`={$rid}");
                $rows = $this->modx->db->update("`{$this->indexName}`={$targetIndex}",$table,"`{$this->pkName}`={$sourceId}");             
            } elseif (($point == 'bottom' && $orderDir == 'asc') || ($point == 'top' && $orderDir == 'desc')) {
                $rows = $this->modx->db->update("`{$this->indexName}`=`{$this->indexName}`+1",$table,"`{$this->indexName}`>{$targetIndex} AND `{$this->indexName}`<{$sourceIndex} AND `{$this->rfName}`={$rid}");
                $rows = $this->modx->db->update("`{$this->indexName}`=1+{$targetIndex}",$table,"`{$this->pkName}`={$sourceId}");             
            }
        } else {
            if (($point == 'bottom' && $orderDir == 'asc') || ($point == 'top' && $orderDir == 'desc')) {
                $rows = $this->modx->db->update("`{$this->indexName}`=`{$this->indexName}`-1",$table,"`{$this->indexName}`<={$targetIndex} AND `{$this->indexName}`>={$sourceIndex} AND `{$this->rfName}`={$rid}");
                $rows = $this->modx->db->update("`{$this->indexName}`={$targetIndex}",$table,"`{$this->pkName}`={$sourceId}");             
            } elseif (($point == 'top' && $orderDir == 'asc') || ($point == 'bottom' && $orderDir == 'desc')) {
                $rows = $this->modx->db->update("`{$this->indexName}`=`{$this->indexName}`-1",$table,"`{$this->indexName}`<{$targetIndex} AND `{$this->indexName}`>={$sourceIndex} AND `{$this->rfName}`={$rid}");
                $rows = $this->modx->db->update("`{$this->indexName}`=-1+{$targetIndex}",$table,"`{$this->pkName}`={$sourceId}");                
            }
        }
        
        return $rows;
    }

    /**
     * @param $folder
     * @param $url
     * @param $options
     * @return bool
     */
    public function makeThumb($folder,$url,$options) {
        if (empty($url)) return false;
        $thumb = new \Helpers\PHPThumb();
        $inputFile = MODX_BASE_PATH . $this->fs->relativePath($url);
        $outputFile = MODX_BASE_PATH. $this->fs->relativePath($folder). '/' . $this->fs->relativePath($url);
        $dir = $this->fs->takeFileDir($outputFile);
        $this->fs->makeDir($dir, $this->modx->config['new_folder_permissions']);
        if ($thumb->create($inputFile,$outputFile,$options)) {
            return true;
        } else {
            $this->modx->logEvent(0, 3, $thumb->debugMessages,  __NAMESPACE__);
            return false;
        }
    }
}
?>