<?php
if (!defined('MODX_BASE_PATH')) {
    die('HACK???');
}

require_once 'content.filter.php';

class private_DL_filter extends content_DL_filter {
    const TableAlias = 'dg';
    public function __construct() {
        $this->setTableAlias(self::TableAlias);
    }
    public function parseFilter($filter) {
        return true;
    }
    public function get_where() {
        if($docgrp = $this->modx->getUserDocGroups()) $docgrp = implode(",",$docgrp);
        $alias = parent::TableAlias;
        $where = ($this->modx->isFrontend() ? "`{$alias}`.`privateweb`=0" : "1='{$_SESSION['mgrRole']}' OR {$alias}.`privatemgr`=0").(!$docgrp ? "" : " OR `{$this->tableAlias}`.`document_group` IN ({$docgrp})");
        return "($where)";
    }

    public function get_join()
    {
        $join = 'LEFT JOIN ' . $this->DocLister->getTable('document_groups', $this->tableAlias) . ' ON `' . $this->tableAlias . '`.`document`=`' . parent::TableAlias . '`.`id`';
        return $join;
    }
}