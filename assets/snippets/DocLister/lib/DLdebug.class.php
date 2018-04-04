<?php
include_once(MODX_BASE_PATH . 'assets/lib/Formatter/SqlFormatter.php');
include_once(MODX_BASE_PATH . 'assets/lib/Formatter/HtmlFormatter.php');

/**
 * Class DLdebug
 */
class DLdebug
{
    /**
     * @var array
     */
    private $_log = array();

    /**
     * @var array
     */
    private $_calcLog = array();

    /**
     * Объект унаследованный от абстрактоного класса DocLister
     * @var DocLister
     * @access protected
     */
    protected $DocLister = null;

    /**
     * Объект DocumentParser - основной класс MODX
     * @var DocumentParser
     * @access protected
     */
    protected $modx = null;

    /**
     * DLdebug constructor.
     * @param $DocLister
     */
    public function __construct($DocLister)
    {
        if ($DocLister instanceof DocLister) {
            $this->DocLister = $DocLister;
            $this->modx = $this->DocLister->getMODX();
        }
    }

    /**
     * @return array
     */
    public function getLog()
    {
        return $this->_log;
    }

    /**
     * @return $this
     */
    public function clearLog()
    {
        $this->_log = array();

        return $this;
    }

    /**
     * @return int
     */
    public function countLog()
    {
        return count($this->_log);
    }

    /**
     * 1 - SQL
     * 2 - Full debug
     *
     * @param $message
     * @param null $key
     * @param int $mode
     * @param bool|array|string $format
     */
    public function debug($message, $key = null, $mode = 0, $format = false)
    {
        $mode = (int)$mode;
        if ($mode > 0 && $this->DocLister->getDebug() >= $mode) {
            $data = array(
                'msg'    => $message,
                'format' => $format,
                'start'  => microtime(true) - $this->DocLister->getTimeStart()
            );
            if (is_scalar($key) && !empty($key)) {
                $data['time'] = microtime(true);
                $this->_calcLog[$key] = $data;
            } else {
                $this->_log[$this->countLog()] = $data;
            }
        }
    }

    /**
     * @param $message
     * @param $key
     * @param null $format
     */
    public function updateMessage($message, $key, $format = null)
    {
        if (is_scalar($key) && !empty($key) && isset($this->_calcLog[$key])) {
            $this->_calcLog[$key]['msg'] = $message;
            if (!is_null($format)) {
                $this->_calcLog[$key]['format'] = $format;
            }
        }
    }

    /**
     * @param $key
     * @param null $msg
     * @param null $format
     */
    public function debugEnd($key, $msg = null, $format = null)
    {
        if (is_scalar($key) && isset($this->_calcLog[$key], $this->_calcLog[$key]['time']) && $this->DocLister->getDebug() > 0) {
            $this->_log[$this->countLog()] = array(
                'msg'    => isset($msg) ? $msg : $this->_calcLog[$key]['msg'],
                'start'  => $this->_calcLog[$key]['start'],
                'time'   => microtime(true) - $this->_calcLog[$key]['time'],
                'format' => is_null($format) ? $this->_calcLog[$key]['format'] : $format
            );
            unset($this->_calcLog[$key]['time']);
        }
    }

    /**
     * @param $message
     * @param string $title
     */
    public function info($message, $title = '')
    {
        $this->_sendLogEvent(1, $message, $title);
    }

    /**
     * @param $message
     * @param string $title
     */
    public function warning($message, $title = '')
    {
        $this->_sendLogEvent(2, $message, $title);
    }

    /**
     * @param $message
     * @param string $title
     */
    public function error($message, $title = '')
    {
        $this->_sendLogEvent(3, $message, $title);
    }

    /**
     * @param $type
     * @param $message
     * @param string $title
     */
    private function _sendLogEvent($type, $message, $title = '')
    {
        $title = "DocLister" . (!empty($title) ? ' - ' . $title : '');
        $this->modx->logEvent(0, $type, $message, $title);
    }

    /**
     * @return string
     */
    public function showLog()
    {
        $out = "";
        if ($this->DocLister->getDebug() > 0 && is_array($this->_log)) {
            foreach ($this->_log as $item) {
                $item['time'] = isset($item['time']) ? round(floatval($item['time']), 5) : 0;
                $item['start'] = isset($item['start']) ? round(floatval($item['start']), 5) : 0;

                if (isset($item['msg'])) {
                    if (is_scalar($item['msg'])) {
                        $item['msg'] = array($item['msg']);
                    }
                    if (is_scalar($item['format'])) {
                        $item['format'] = array($item['format']);
                    }
                    $message = '';
                    $i = 0;
                    foreach ($item['msg'] as $title => $msg) {
                        $format = isset($item['format'][$i]) ? $item['format'][$i] : null;
                        switch ($format) {
                            case 'sql':
                                $msg = $this->dumpData(Formatter\SqlFormatter::format($msg), '', null);
                                break;
                            case 'html':
                                $msg = is_numeric($msg) ? $msg : $this->dumpData(
                                    Formatter\HtmlFormatter::format(!is_scalar($msg) ? print_r($msg, true) : $msg),
                                    '',
                                    null
                                );
                                break;
                            default:
                                $msg = $this->dumpData($msg);
                                break;
                        }
                        if (!empty($title) && !is_numeric($title)) {
                            $message .= $this->DocLister->parseChunk(
                                '@CODE:<strong>[+title+]</strong>: [+msg+]<br />',
                                compact('msg', 'title')
                            );
                        } else {
                            $message .= $msg;
                        }
                        $i++;
                    }
                    $item['msg'] = $message;
                } else {
                    $item['msg'] = '';
                }

                $tpl = '<li>
                            <strong>action time</strong>: <em>[+time+]</em> &middot; <strong>total time</strong>: <em>[+start+]</em><br />
                            <blockquote>[+msg+]</blockquote>
                    </li>';
                $out .= $this->DocLister->parseChunk("@CODE: " . $tpl, $item);
            }
            if (!empty($out)) {
                $out = $this->DocLister->parseChunk("@CODE:
                <style>.dlDebug{
                    background: #eee !important;
                    padding:0 !important;
                    margin: 0 !important;
                    text-align:left;
                    font-size:14px !important;
                    width:100%;
                    z-index:999;
                }
                .dlDebug > ul{
                    list-style:none !important;
                    padding: 3px !important;
                    margin: 0 !important;
                    border: 2px solid #000;
                }
                .dlDebug > ul > li{
                    border-top: 1px solid #000 !important;
                    background: none;
                    margin: 0;
                    padding: 0;
                    width:100%;
                }
                .dlDebug > ul > li:first-child {
                    border-top: 0 !important;
                }
                .dlDebug > ul > li > blockquote{
                    border-left: 4px solid #aaa !important;
                    font-family: monospace !important;
                    margin: 5px 0 !important;
                    padding:5px !important;

                    word-wrap: break-word !important;
                    white-space: pre-wrap !important;
                    white-space: -moz-pre-wrap !important;
                    white-space: -pre-wrap !important;
                    white-space: -o-pre-wrap !important;
                    word-break: break-all !important;
                }
                </style>
                <div class=\"dlDebug\"><ul>[+wrap+]</ul></div>", array('wrap' => $out));
            }
        }

        return $out;
    }

    /**
     * @param $data
     * @param string $wrap
     * @param string $charset
     * @return string
     */
    public function dumpData($data, $wrap = '', $charset = 'UTF-8')
    {
        $out = $this->DocLister->sanitarData(print_r($data, 1), $charset);
        if (!empty($wrap) && is_string($wrap)) {
            $out = "<{$wrap}>{$out}</{$wrap}>";
        }

        return $out;
    }
}
