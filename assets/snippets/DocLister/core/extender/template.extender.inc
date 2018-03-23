<?php
/**
 * Template extender for DocLister
 *
 * @category extender
 * @license GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @author Agel_Nash <Agel_Nash@xaker.ru>
 */
class template_DL_Extender extends extDocLister
{

    /**
     * @return string
     */
    protected function run()
    {
        return ($this->_cfg != '') ? $this->getTemplate($this->_cfg) : '';
    }

    /**
     * @param $name
     * @return string
     */
    private function getTemplate($name)
    {
        return '@TODO: ' . $name;
    }

}
