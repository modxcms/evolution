<?php
/**
 * Request extender for DocLister
 *
 * @category extender
 * @license GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @author Agel_Nash <Agel_Nash@xaker.ru>
 */
//&requestActive=`order:g:(ASC,DESC)||display:g:(*):int`
class request_DL_Extender extends extDocLister
{
    /**
     * @var array
     */
    private $param = array();

    /**
     * @return array|int
     */
    protected function run()
    {
        $count = 0;
        if ($this->_cfg != '') {
            $tmp = explode("||", $this->_cfg);
            foreach ($tmp as $item) {
                $count = $this->parseParam($item);
            }
        }

        return $count;
    }

    /**
     * @param $data
     * @return array
     */
    private function parseParam($data)
    {
        $GLOBArr = array();
        $name = '';

        $tmp = explode(":", $data);

        $tmp[1] = isset($tmp[1]) ? strtolower($tmp[1]) : 'get';

        switch ($tmp[1]) {
            case 'r':
            case 'request':
                //$_REQUEST;
                $GLOBArr = $_REQUEST;
                $name = "request";
                break;
            case 'c':
            case 'cookie':
                //$_COOKIE;
                $GLOBArr = $_COOKIE;
                $name = "cookie";
                break;
            case 'p':
            case 'post':
                //$_POST;
                $GLOBArr = $_POST;
                $name = "post";
                break;
            case 'session':
                //$_SESSION;
                $GLOBArr = $_SESSION;
                $name = "session";
                break;
            case 'g':
            case 'get':
                //$_GET;
                $GLOBArr = $_GET;
                $name = "get";
        }

        if (isset($GLOBArr[$tmp[0]])) {
            /*
             array(
                'g'=>array(
                    'order' => 'ASC'
                    'id'    => '2',
                    'limit' => '10',
                )
             )
            */
            if (isset($tmp[2])) {
                if (($val = $this->parseValidate($tmp[2], $GLOBArr[$tmp[0]])) !== false) {
                    $this->param[$name][$tmp[0]] = $val;
                }
            } else {
                $this->param[$name][$tmp[0]] = $GLOBArr[$tmp[0]];
            }
        }

        $this->setCFG();

        return $this->param;
    }

    /**
     * @param $data
     * @param $val
     * @return bool
     */
    private function parseValidate($data, $val)
    {
        $out = false;
        if (preg_match("/^\((.*)\)$/iu", $data, $data) && isset($data[1]) && $data[1] != '') {
            if ($data[1] == "*") { //Юзать если нужны массивы в суппер-глабальных массивах
                /**
                 * Но в таком случае в $this->param попадут все!!! элементы массива, которые были переданы
                 *
                 * Для примера order:g:*
                 * http://example.com?order[a]=a&order[b]=b&order[c]=c
                 * в $tihs->param получим целый массив ('a'=>'a',b'=>'b','c'=>'c')
                 *
                 * Поэтмоу если дополнительно не обработать этот массив, в своем контроллере, то можно получить
                 * в пагинаторе ссылки вида http://example.com?order[]=a&order[]=b&order[]=c
                 *
                 * Учитыая специфику MODX млэгл полвесьи не только XSS атаку, но и выполнить любой сниппет движка
                 *
                 * Все понятно?
                 */
                $out = $val;
            } else {
                $tmp = explode(",", $data[1]);
                if (in_array($val, $tmp, true)) {
                    $out = $val;
                }
            }
        }

        return $out;
    }

    /**
     *
     */
    private function setCFG()
    {
        foreach ($this->param as $name => $item) {
            $this->DocLister->config->setConfig($item);
        }
    }

    /**
     * @return string
     */
    public function getLink()
    {
        $link = array();
        if (isset($this->param['get']) && is_array($this->param['get'])) {
            foreach ($this->param['get'] as $name => $item) {
                $link[] = $this->_getLink($item, $name);
            }
        }

        return (count($link) > 0) ? implode("&", $link) : "";
    }

    /**
     * @param $item
     * @param string $name
     * @return array|string
     */
    private function _getLink($item, $name = '')
    {
        $link = '';
        if (is_array($item) && count($item) > 0) {
            foreach ($item as $nameItem => $valItem) {
                $link[] = $name . "[" . (intval($nameItem) ? "" : $nameItem) . "]=" . urlencode($valItem);
            }
            $link = implode("&", $link);
        } else {
            if ($name == '') {
                $link = urlencode($item);
            } else {
                $link = $name . "=" . urlencode($item);
            }
        }

        return $link;
    }

}
