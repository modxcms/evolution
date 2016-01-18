<?php namespace Module;

include_once(dirname(dirname(__FILE__)).'/APIHelpers.class.php');
class Helper extends \APIhelpers{
    protected static $modx = null;
	protected static $mode = 'list';

    public static function init(\DocumentParser $modx, $mode = 'list'){
        self::$modx = $modx;
		self::setMode($mode);
    }

	public static function getMode()
	{
		return self::$mode;
	}

	public static function setMode($text)
	{
		self::$mode = $text;
	}

    protected static function _counter($from, $where = ''){
        $q = self::$modx->db->select('count(id)', self::$modx->getFullTableName($from), $where);
        return self::$modx->db->getValue($q);
    }

    public static function jeditable($key = 'id', $post = true){
       $data = array();
       $request = $post ? $_POST : $_GET;
       $match = (isset($request[$key]) && is_scalar($request[$key]) && preg_match("/^(.*)_(\d+)$/i", $request[$key], $match)) ? $match : array();
       if(!empty($match)){
           $data = array(
               'key' => $match[1],
               'id' => $match[2]
           );
       }
       return $data;
    }

	public static function curl($url, $data = '', $post = false, array $header = array())
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$post = (bool)$post;
		curl_setopt($ch, CURLOPT_POST, $post);
		if ($post) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		if (!empty($header)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_URL, $url);
		return curl_exec($ch);
	}

	/**
	 * Были ли ошибки во время работы с JSON
	 *
	 * @param $json string строка с JSON для записи в лог при отладке
	 * @return bool|string
	 */
	public function isErrorJSON($json)
	{
		require_once(MODX_BASE_PATH . "assets/snippets/DocLister/lib/jsonHelper.class.php");
		$error = false;
		$error = \jsonHelper::json_last_error_msg();
		if (!in_array($error, array('error_none', 'other'))) {
			$error = true;
		}
		return $error;
	}

	public static function readFileLine($path, $callback, array $callbackParams = array(), $lines = 0, $size = 4096)
	{
		$handle = fopen($path, "r");
		$i = $total = 0;
		while (!feof($handle)) {
			$i++;
			$buffer = fgets($handle, $size);
			if (is_callable($callback)) {
				$callbackParams['line'] = $buffer;
				$callbackParams['numLine'] = $i;
				if (call_user_func($callback, $callbackParams)) {
					$total++;
				}
			}
			if ($lines > 0 && $i >= $lines) {
				break;
			}
		}
		fclose($handle);
		return array('line' => $i, 'add' => $total);
	}
}