<?php
if (!defined('MODX_BASE_PATH')) {
    die('HACK???');
}

/**
 * Template extender for DocLister
 *
 * @category extender
 * @license GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @author Agel_Nash <Agel_Nash@xaker.ru>
 */

class template_DL_Extender extends extDocLister
{

    protected function run()
    {
        return ($this->_cfg != '') ? $this->getTemplate($this->_cfg) : '';
    }

    //@TODO load custom rulez for template. For example - load from other DB/Table OR template directory
    private function getTemplate($name)
    {
        return '';
    }
}