<?php

include_once(MODX_BASE_PATH . 'assets/lib/APIHelpers.class.php');

/**
 * Class DLTemplate
 */
class DLTemplate
{
    /**
     * Объект DocumentParser - основной класс MODX
     * @var \DocumentParser
     * @access protected
     */
    protected $modx = null;

    /**
     * @var DLTemplate cached reference to singleton instance
     */
    protected static $instance;

    public $phx = null;

    /**
     * gets the instance via lazy initialization (created on first usage)
     *
     * @return self
     */
    public static function getInstance(DocumentParser $modx)
    {

        if (null === self::$instance) {
            self::$instance = new self($modx);
        }

        return self::$instance;
    }

    /**
     * is not allowed to call from outside: private!
     *
     */
    private function __construct(DocumentParser $modx)
    {
        $this->modx = $modx;
    }

    /**
     * prevent the instance from being cloned
     *
     * @return void
     */
    private function __clone()
    {

    }

    /**
     * prevent from being unserialized
     *
     * @return void
     */
    private function __wakeup()
    {

    }

    /**
     * Сохранение данных в массив плейсхолдеров
     *
     * @param mixed $data данные
     * @param int $set устанавливать ли глобальнй плейсхолдер MODX
     * @param string $key ключ локального плейсхолдера
     * @param string $prefix префикс для ключей массива
     * @return string
     */
    public function toPlaceholders($data, $set = 0, $key = 'contentPlaceholder', $prefix = '')
    {
        $out = '';
        if ($set != 0) {
            $this->modx->toPlaceholder($key, $data, $prefix);
        } else {
            $out = $data;
        }

        return $out;
    }

    /**
     * refactor $modx->getChunk();
     *
     * @param string $name Template: chunk name || @CODE: template || @FILE: file with template
     * @return string html template with placeholders without data
     */
    public function getChunk($name)
    {
        $tpl = '';
        if ($name != '' && !isset($this->modx->chunkCache[$name])) {
            $mode = (preg_match('/^((@[A-Z]+)[:]{0,1})(.*)/Asu', trim($name),
                    $tmp) && isset($tmp[2], $tmp[3])) ? $tmp[2] : false;
            $subTmp = (isset($tmp[3])) ? trim($tmp[3]) : null;
            switch ($mode) {
                case '@FILE':
                    if ($subTmp != '') {
                        $real = realpath(MODX_BASE_PATH . 'assets/templates');
                        $path = realpath(MODX_BASE_PATH . 'assets/templates/' . preg_replace(array(
                                '/\.*[\/|\\\]/i',
                                '/[\/|\\\]+/i'
                            ), array('/', '/'), $subTmp) . '.html');
                        $fname = explode(".", $path);
                        if ($real == substr($path, 0, strlen($real)) && end($fname) == 'html' && file_exists($path)) {
                            $tpl = file_get_contents($path);
                        }
                    }
                    break;
                case '@CHUNK':
                    if ($subTmp != '') {
                        $tpl = $this->modx->getChunk($subTmp);
                    }
                    break;
                case '@INLINE':
                case '@TPL':
                case '@CODE':
                    $tpl = $tmp[3]; //without trim
                    break;
                case '@DOCUMENT':
                case '@DOC':
                    switch (true) {
                        case ((int)$subTmp > 0):
                            $tpl = $this->modx->getPageInfo((int)$subTmp, 0, "content");
                            $tpl = isset($tpl['content']) ? $tpl['content'] : '';
                            break;
                        case ((int)$subTmp == 0):
                            $tpl = $this->modx->documentObject['content'];
                            break;
                    }
                    break;
                case '@PLH':
                case '@PLACEHOLDER':
                    if ($subTmp != '') {
                        $tpl = $this->modx->getPlaceholder($subTmp);
                    }
                    break;
                case '@CFG':
                case '@CONFIG':
                case '@OPTIONS':
                    if ($subTmp != '') {
                        $tpl = $this->modx->getConfig($subTmp);
                    }
                    break;
                case '@SNIPPET':
                    if ($subTmp != '') {
                        $tpl = $this->modx->runSnippet($subTmp, $this->modx->event->params);
                    }
                    break;
                case '@RENDERPAGE':
                    $tpl = $this->renderDoc($subTmp, false);
                    break;
                case '@LOADPAGE':
                    $tpl = $this->renderDoc($subTmp, true);
                    break;
                case '@TEMPLATE':
                    $tpl = $this->getTemplate($subTmp);
                    break;
                default:
                    $tpl = $this->modx->getChunk($name);
            }
            $this->modx->chunkCache[$name] = $tpl;
        } else {
            if ($name != '') {
                $tpl = $this->modx->getChunk($name);
            }
        }

        return $tpl;
    }

    /**
     * Рендер документа с подстановкой плейсхолдеров и выполнением сниппетов
     *
     * @param int $id ID документа
     * @param bool $events Во время рендера документа стоит ли вызывать события OnLoadWebDocument и OnLoadDocumentObject (внутри метода getDocumentObject).
     * @param mixed $tpl Шаблон с которым необходимо отрендерить документ. Возможные значения:
     *                       null - Использовать шаблон который назначен документу
     *                       int(0-n) - Получить шаблон из базы данных с указанным ID и применить его к документу
     *                       string - Применить шаблон указанный в строке к документу
     * @return string
     *
     * Событие OnLoadWebDocument дополнительно передает параметры:
     *       - с источиком от куда произошел вызов события
     *       - оригинальный экземпляр класса DocumentParser
     */
    public function renderDoc($id, $events = false, $tpl = null)
    {
        if ((int)$id <= 0) {
            return '';
        }

        $m = clone $this->modx; //Чтобы была возможность вызывать события
        $m->documentObject = $m->getDocumentObject('id', (int)$id, $events ? 'prepareResponse' : null);
        if ($m->documentObject['type'] == "reference") {
            if (is_numeric($m->documentObject['content']) && $m->documentObject['content'] > 0) {
                $m->documentObject['content'] = $this->renderDoc($m->documentObject['content'], $events);
            }
        }
        switch (true) {
            case is_integer($tpl):
                $tpl = $this->getTemplate($tpl);
                break;
            case is_string($tpl):
                break;
            case is_null($tpl):
            default:
                $tpl = $this->getTemplate($m->documentObject['template']);
        }
        $m->documentContent = $tpl;
        if ($events) {
            $m->invokeEvent("OnLoadWebDocument", array(
                'source'   => 'DLTemplate',
                'mainModx' => $this->modx,
            ));
        }

        return $this->parseDocumentSource($m->documentContent, $m);
    }

    /**
     * Получить содержимое шаблона с определенным номером
     * @param int $id Номер шаблона
     * @return string HTML код шаблона
     */
    public function getTemplate($id)
    {
        $tpl = null;
        if ($id > 0) {
            $tpl = $this->modx->db->getValue("SELECT `content` FROM {$this->modx->getFullTableName("site_templates")} WHERE `id` = '{$id}'");
        }
        if (is_null($tpl)) {
            $tpl = '[*content*]';
        }

        return $tpl;
    }

    /**
     * refactor $modx->parseChunk();
     *
     * @param string $name Template: chunk name || @CODE: template || @FILE: file with template
     * @param array $data paceholder
     * @param bool $parseDocumentSource render html template via DocumentParser::parseDocumentSource()
     * @return string html template with data without placeholders
     */
    public function parseChunk($name, $data, $parseDocumentSource = false)
    {
        $out = null;
        if (is_array($data) && ($out = $this->getChunk($name)) != '') {
            if (preg_match("/\[\+[A-Z0-9\.\_\-]+\+\]/is", $out)) {
                $item = $this->renameKeyArr($data, '[', ']', '+');
                $out = str_replace(array_keys($item), array_values($item), $out);
            }
            if (preg_match("/:([^:=]+)(?:=`(.*?)`(?=:[^:=]+|$))?/is", $out)) {
                if (is_null($this->phx) || !($this->phx instanceof DLphx)) {
                    $this->phx = $this->createPHx(0, 1000);
                }
                $this->phx->placeholders = array();
                $this->setPHxPlaceholders($data);
                $out = $this->phx->Parse($out);
                $out = $this->cleanPHx($out);
            }
        }
        if ($parseDocumentSource) {
            $out = $this->parseDocumentSource($out);
        }

        return $out;
    }

    /**
     *
     * @param string|array $value
     * @param string $key
     * @param string $path
     */
    public function setPHxPlaceholders($value = '', $key = '', $path = '')
    {
        $keypath = !empty($path) ? $path . "." . $key : $key;
        $this->phx->curPass = 0;
        if (is_array($value)) {
            foreach ($value as $subkey => $subval) {
                $this->setPHxPlaceholders($subval, $subkey, $keypath);
            }
        } else {
            $this->phx->setPHxVariable($keypath, $value);
        }
    }

    /**
     *
     * @param string $string
     * @return string
     */
    public function cleanPHx($string)
    {
        preg_match_all('~\[(\+|\*|\()([^:\+\[\]]+)([^\[\]]*?)(\1|\))\]~s', $string, $matches);
        if ($matches[0]) {
            $string = str_replace($matches[0], '', $string);
        }

        return $string;
    }

    /**
     * @param int $debug
     * @param int $maxpass
     * @return DLphx
     */
    public function createPHx($debug = 0, $maxpass = 50)
    {
        if (!class_exists('DLphx', false)) {
            include_once(dirname(__FILE__) . '/DLphx.class.php');
        }

        return new DLphx($debug, $maxpass);
    }

    /**
     * Переменовывание элементов массива
     *
     * @param array $data массив с данными
     * @param string $prefix префикс ключей
     * @param string $suffix суффикс ключей
     * @param string $sep разделитель суффиксов, префиксов и ключей массива
     * @return array массив с переименованными ключами
     */
    public function renameKeyArr($data, $prefix = '', $suffix = '', $sep = '.')
    {
        return APIhelpers::renameKeyArr($data, $prefix, $suffix, $sep);
    }

    /**
     * @param $out
     * @param DocumentParser|null $modx
     * @return mixed|string
     */
    public function parseDocumentSource($out, $modx = null)
    {
        if (!is_object($modx)) {
            $modx = $this->modx;
        }
        $minPasses = empty ($modx->minParserPasses) ? 2 : $modx->minParserPasses;
        $maxPasses = empty ($modx->maxParserPasses) ? 10 : $modx->maxParserPasses;
        $site_status = $modx->getConfig('site_status');
        $modx->config['site_status'] = 0;
        for ($i = 1; $i <= $maxPasses; $i++) {
            $html = $out;
            if (preg_match('/\[\!(.*)\!\]/us', $out)) {
                $out = str_replace(array('[!', '!]'), array('[[', ']]'), $out);
            }
            if ($i <= $minPasses || $out != $html) {
                $out = $modx->parseDocumentSource($out);
            } else {
                break;
            }
        }
        $out = $modx->rewriteUrls($out);
        $modx->config['site_status'] = $site_status;

        return $out;
    }
}
