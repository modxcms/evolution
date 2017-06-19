<?php
if (!defined('MODX_BASE_PATH')) {
    die('HACK???');
}

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
        return true;
    }

    /**
     * @param $docs
     * @return array
     */
    public function countComments($docs)
    {
        $comments = array();
        if (count($docs)) {
            $from = $this->DocLister->getTable('jot_content');
            $rs = $this->DocLister->dbQuery("SELECT uparent, COUNT(*) as total FROM {$from} WHERE uparent IN (" . implode(',',
                    $docs) . ") AND published=1 AND deleted=0 GROUP BY uparent");
            $counts = $this->modx->db->makeArray($rs);
            foreach ($counts as $v) {
                $comments[$v['uparent']] = $v['total'];
            }
        }

        return $comments;
    }
}