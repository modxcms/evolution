<?php
/**
 * Prepare extender for DocLister
 *
 * @category extender
 * @license GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @author Agel_Nash <Agel_Nash@xaker.ru>
 */
class prepare_DL_Extender extends extDocLister
{
    /**
     * @var array
     */
    private $_store = array();

    /**
     * @param $name
     * @param $val
     */
    public function setStore($name, $val)
    {
        $this->_store[$name] = $val;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getStore($name)
    {
        return APIHelpers::getkey($this->_store, $name);
    }

    /**
     * @return array|bool|mixed|null|string|xNop
     */
    protected function run()
    {
        $out = $this->getCFGDef('data', array());
        $nameParam = $this->getCFGDef('nameParam', 'prepare');
        $ret = $this->getCFGDef('return', null);
        if (($prepare = $this->DocLister->getCFGDef($nameParam, '')) != '') {
            $params = $this->getParams($out);
            if (is_scalar($prepare)) {
                $prepare = array_map('trim', explode(',', $prepare));
            } else if (!is_array($prepare) || is_callable($prepare)) {
                $prepare = [$prepare];
            }

            if (!empty($prepare)) {
                foreach ($prepare as $item) {
                    $params['data'] = $this->callPrepare($item, $params);
                    if ($params['data'] === false) {
                        break;
                    }
                }
            }
            $out = $params['data'];
        }

        return (!empty($ret) && is_scalar($ret) && isset($out[$ret])) ? $out[$ret] : $out;
    }

    /**
     * @param array $data
     * @return array
     */
    public function getParams(array $data = array())
    {
        return array(
            '_extDocLister' => $this,
            '_DocLister'    => $this->DocLister,
            'data'          => $data,
            'modx'          => $this->modx
        );
    }

    /**
     * @param $name
     * @param array $params
     * @return array|bool|mixed|null|string|xNop
     */
    public function callPrepare($name, array $params)
    {
        $out = null;
        if (empty($name)) {
            return $params['data'];
        }
        if ((is_object($name) && ($name instanceof Closure)) || is_callable($name)) {
            $data = call_user_func(
                $name,
                $params['data'],
                $params['modx'],
                $params['_DocLister'],
                $params['_extDocLister']
            );
        } else {
            $data = $this->modx->runSnippet($name, $params);
        }
        switch (true) {
            case is_array($data):
                $out = $data;
                break;
            case ($data === '' || $data === false):
                $out = false;
                break;
            case is_string($data):
                if ($data[0] == '[' || $data[0] == '{') {
                    $out = $this->DocLister->jsonDecode($data, array('assoc' => true), true);
                } else {
                    $out = unserialize($data);
                }
        }

        return is_null($out) ? $params['data'] : $out;
    }

}
