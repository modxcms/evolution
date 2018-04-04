<?php
require_once 'tv.filter.php';

/**
 * Filters DocLister results by value of a given MODx Template Variables (TVs) with default.
 * @author Agel_Nash <modx@agel-nash.ru>
 */
class tvd_DL_filter extends tv_DL_filter
{

    /**
     * @return string
     */
    public function get_join()
    {
        $join = parent::get_join();

        $alias = $this->DocLister->TableAlias($this->tvName, $this->extTV->tvValuesTable(), $this->getTableAlias());
        $exists = $this->DocLister->checkTableAlias($this->tvName, "site_tmplvars");
        $dPrefix = $this->DocLister->TableAlias($this->tvName, "site_tmplvars", 'd_' . $this->getTableAlias());
        $this->field = "IFNULL(`{$alias}`.`value`, `{$dPrefix}`.`default_text`)";

        if (!$exists) {
            $join .= " LEFT JOIN " . $this->DocLister->getTable(
                "site_tmplvars",
                $dPrefix
            ) . " on `" . $dPrefix . "`.`id` = " . $this->tv_id;
            $this->extTV->addTVSortWithDefault($this->tvName);
        }

        return $join;
    }
}
