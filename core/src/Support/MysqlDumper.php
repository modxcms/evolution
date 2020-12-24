<?php namespace EvolutionCMS\Support;

use EvolutionCMS\Interfaces\MysqlDumperInterface;

/**
 * @package  MySQLdumper
 * @version  1.0
 * @author   Dennis Mozes <opensource@mosix.nl>
 * @url        http://www.mosix.nl/mysqldumper
 * @since    PHP 4.0
 * @copyright Dennis Mozes
 * @license GNU/LGPL License: http://www.gnu.org/copyleft/lgpl.html
 *
 * Modified by Raymond for use with this module
 *
 **/
class MysqlDumper implements MysqlDumperInterface
{
    /**
     * @var array
     */
    public $_dbtables;
    /**
     * @var bool
     */
    public $_isDroptables;
    /**
     * @var string
     */
    public $dbname;
    /**
     * @var string
     */
    public $database_server;

    /**
     * Field with snapshoot filename
     * @var string
     */
    private $snapshootFile;


    /**
     * Mysqldumper constructor.
     * @param string $dbname
     */
    public function __construct($dbname)
    {
        // Don't drop tables by default.
        $this->dbname = $dbname;
        $this->setDroptables(false);
    }

    /**
     * If set to true, it will generate 'DROP TABLE IF EXISTS'-statements for each table.
     *
     * @param bool $state
     */
    public function setDroptables($state)
    {
        $this->_isDroptables = $state;
    }

    /**
     * @param array $dbtables
     */
    public function setDBtables($dbtables)
    {
        $this->_dbtables = $dbtables;
    }

    /**
     * @param string $callBack
     * @return bool
     */
    public function createDump($callBack)
    {
        $modx = evolutionCMS();
        $createtable = array();
        $dataBaseConfig = $modx->db->getConfig();

        $databaseName = $dataBaseConfig['database'];

        $sql =  'SELECT table_name AS "table", round(((data_length + index_length) / 1024 / 1024)) "size" FROM information_schema.TABLES WHERE table_schema = "'.$databaseName.'"';
        $tableSizes = array_column($modx->db->makeArray($modx->db->query($sql)),'size','table');

        // Set line feed
        $lf = "\n";
        $tempfile_path = MODX_BASE_PATH . 'assets/backup/temp.php';

        $result = $modx->getDatabase()->query('SHOW TABLES');
        $tables = $this->result2Array(0, $result);
        foreach ($tables as $tblval) {
            $result = $modx->getDatabase()->query("SHOW CREATE TABLE `{$tblval}`");
            $createtable[$tblval] = $this->result2Array(1, $result);
        }

        $version = $modx->getVersionData();

        // Set header
        $output = "#{$lf}";
        $output .= "# " . addslashes($modx->getPhpCompat()->entities($modx->getConfig('site_name'))) . " Database Dump{$lf}";
        $output .= "# Evolution CMS Version:{$version['version']}{$lf}";
        $output .= "# {$lf}";
        $output .= "# Host: {$this->database_server}{$lf}";
        $output .= "# Generation Time: " . $modx->toDateFormat(time()) . $lf;
        $output .= "# Server version: " . $modx->getDatabase()->getVersion() . $lf;
        $output .= "# PHP Version: " . phpversion() . $lf;
        $output .= "# Database: `{$this->dbname}`{$lf}";
        $output .= "# Description: " . trim($_REQUEST['backup_title'] ?? '') . "{$lf}";
        $output .= "#";
        file_put_contents($tempfile_path, $output, FILE_APPEND | LOCK_EX);
        $output = '';

        // Generate dumptext for the tables.
        if (isset($this->_dbtables) && count($this->_dbtables)) {
            $this->_dbtables = implode(',', $this->_dbtables);
        } else {
            unset($this->_dbtables);
        }



        foreach ($tables as $tblval) {


            // check for selected table
            if (isset($this->_dbtables)) {
                if (strstr(",{$this->_dbtables},", ",{$tblval},") === false) {
                    continue;
                }
            }
            if ($callBack === 'snapshot') {
                if (!preg_match('@^' . $modx->getDatabase()->getConfig('prefix') . '@', $tblval)) {
                    continue;
                }
            }
            $output .= "{$lf}{$lf}# --------------------------------------------------------{$lf}{$lf}";
            $output .= "#{$lf}# Table structure for table `{$tblval}`{$lf}";
            $output .= "#{$lf}{$lf}";
            // Generate DROP TABLE statement when client wants it to.
            if ($this->isDroptables()) {
                $output .= "SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;{$lf}";
                $output .= "DROP TABLE IF EXISTS `{$tblval}`;{$lf}";
                $output .= "SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;{$lf}{$lf}";
            }
            $output .= "{$createtable[$tblval][0]};{$lf}";
            $output .= $lf;

            $rowCount = $modx->getDatabase()->getValue($modx->getDatabase()->select("COUNT(*)",$tblval));
            if(!empty($rowCount)){
                $output .= "#{$lf}# Dumping data for table `{$tblval}`{$lf}#{$lf}";
            }
            file_put_contents($tempfile_path, $output, FILE_APPEND | LOCK_EX);
            $output = '';

            if(empty($rowCount)){
                continue;
            }

            $tableSize = $tableSizes[$tblval];

            $parts = round($tableSize/5);
            $parts = !empty($parts)?$parts:1;
            $rowByOneQuery = round($rowCount/$parts);

            $total = intval(($rowCount - 1) / $rowByOneQuery) + 1;

            $insertQuerySize = 0;

            for ($page = 1; $page <= $total; $page++) {
                $start = $page * $rowByOneQuery - $rowByOneQuery;
                $result = $modx->getDatabase()->select('*', $tblval, '', '', "$start, $rowByOneQuery");



                while ($arr = $modx->getDatabase()->getRow($result)) {
                    //формируем блок  значений
                    $insertdump = "(";
                    if (!is_array($arr)) $arr = array();

                    foreach ($arr as $key => $value) {
                        if (is_null($value)) {
                            $value = 'NULL';
                        } else {
                            $value = addslashes($value);
                            $value = str_replace(array(
                                "\r\n",
                                "\r",
                                "\n"
                            ), '\\n', $value);
                            $value = "'{$value}'";
                        }
                        $insertdump .= $value . ',';
                    }
                    $insertdump = rtrim($insertdump, ',') . ")";

                    //если еще небыло значен
                    if($insertQuerySize === 0){
                        $output .= $lf."INSERT INTO `{$tblval}` VALUES";
                    }
                    else{
                        $output .= ",";
                    }
                    $output .= $lf."  ".$insertdump;
                    $insertQuerySize+=strlen($insertdump);

                    //если записали больше 30 строк з запрос ставим ; и сбрасивыем счетчик
                    if($insertQuerySize>47299){
                        $output .= ";".$lf;
                        $insertQuerySize = 0;
                    }
                    //если большая строрки пишем в файл чтоб не перегрузить память

                    if (5040000 < strlen($output)) {
                        file_put_contents($tempfile_path, $output, FILE_APPEND | LOCK_EX);
                        $output = '';
                    }
                }
            }
            //если данные есть, и записано больше 0 строк данных ставим ; в конце
            if(!empty($output) && $insertQuerySize >0){
                $output .= ";".$lf;
            }

            //пишем блок в файл
            file_put_contents($tempfile_path, $output, FILE_APPEND | LOCK_EX);
            $output = '';
        }

        switch ($callBack) {
            case 'dumpSql':
                dumpSql($tempfile_path);
                break;
            case 'snapshot':
                snapshot($tempfile_path,$this->snapshootFile);
                break;
        }

        return true;
    }

    /**
     * @param int $numinarray
     * @param \mysqli_result $resource
     * @return array
     */
    public function result2Array($numinarray = 0, $resource)
    {
        $modx = evolutionCMS();
        $array = array();
        while ($row = $modx->getDatabase()->getRow($resource, 'num')) {
            $array[] = $row[$numinarray];
        }

        return $array;
    }

    /**
     * @return bool
     */
    public function isDroptables()
    {
        return $this->_isDroptables;
    }

    /**
     * @param string $key
     * @param \mysqli_result $resource
     * @return array
     */
    public function loadObjectList($key = '', $resource)
    {
        $modx = evolutionCMS();
        $array = array();
        while ($row = $modx->getDatabase()->getRow($resource, 'object')) {
            if ($key) {
                $array[$row->$key] = $row;
            } else {
                $array[] = $row;
            }
        }

        return $array;
    }

    /**
     * @param \stdClass $obj
     * @return array|null
     */
    public function object2Array($obj)
    {
        $array = null;
        if (is_object($obj)) {
            $array = array();
            foreach (get_object_vars($obj) as $key => $value) {
                if (is_object($value)) {
                    $array[$key] = $this->object2Array($value);
                } else {
                    $array[$key] = $value;
                }
            }
        }

        return $array;
    }

    public function setSnapshotFile($file){
        $this->snapshootFile = $file;
    }

}
