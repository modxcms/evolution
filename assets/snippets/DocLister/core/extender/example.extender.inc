<?php
/**
 * Example extender for DocLister
 *
 * @category extender
 * @license GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @author Agel_Nash <Agel_Nash@xaker.ru>
 */
class example_DL_Extender extends extDocLister
{

    /**
     *
     */
    protected function run()
    {
        print_r($this->_cfg);
    }

}
