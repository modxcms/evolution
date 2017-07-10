<?php

/**
 * Class sms
 */
class SmsModel extends \autoTable
{
    protected $table = 'smscaptcha';
    protected $default_field = array(
        'formid'  => '',
        'phone'   => '',
        'code'    => '',
        'active'  => 0,
        'expires' => 0,
        'ip'      => ''
    );

    public function getData($phone,$formid) {

        $this->close();
        $this->markAllEncode();
        $this->newDoc = false;
        $result = $this->query("SELECT * from {$this->makeTable($this->table)} where `phone`='{$this->escape($phone)}' AND `formid`='{$this->escape($formid)}'");
        $this->fromArray($this->modx->db->getRow($result));
        $this->store($this->toArray());
        $this->id = $this->eraseField($this->pkName);
        if (is_bool($this->id) && $this->id === false) {
            $this->id = null;
        } else {
            $this->decodeFields();
        }


        return $this;
    }

    public function createTable()
    {
        $table = $this->modx->getFullTableName($this->table);
        $q = "CREATE TABLE IF NOT EXISTS {$table} (
            `id` INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `formid` VARCHAR(255) NOT NULL DEFAULT '',
            `phone` VARCHAR(20) NOT NULL DEFAULT '',
            `code` VARCHAR (10) NOT NULL DEFAULT '',
            `active` INT(1) NOT NULL DEFAULT 0,
            `expires` INT(10) NOT NULL DEFAULT 0,
            `ip` VARCHAR (16) NOT NULL DEFAULT '',
            KEY `formid` (`formid`),
            KEY `phone` (`phone`),
            KEY `code` (`code`),
            KEY `active` (`active`),
            KEY `expires` (`expires`)
            ) Engine=MyISAM
            ";
        $this->modx->db->query($q);
    }
}
