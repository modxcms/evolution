<?php
/**
 * Jot extender for DocLister
 *
 * @category extender
 * @license GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @author Pathologic <maxx@np.by>
 */
class jotcount_DL_Extender extends extDocLister
{

    /**
     * @return bool
     */
    protected function run()
    {
        $join = "LEFT JOIN (SELECT `uparent`, COUNT(`id`) AS `jotcount` FROM {$this->DocLister->getTable('jot_content')} WHERE `published`=1 AND `deleted`=0 GROUP BY `uparent`) `jc` ON `jc`.`uparent` = {$this->DocLister->getPK()}";
        $this->DocLister->setFiltersJoin($join);
        $fields = $this->DocLister->getCFGDef('selectFields', 'c.*');
        $this->DocLister->config->setConfig(array(
            "selectFields" => $fields . ",IFNULL(`jc`.`jotcount`,0) AS `jotcount`"
        ));

        return true;
    }
}
