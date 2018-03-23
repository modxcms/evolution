<?php namespace Helpers;

include_once(MODX_BASE_PATH . 'assets/snippets/phpthumb/phpthumb.class.php');
require_once(MODX_BASE_PATH . 'assets/lib/Helpers/FS.php');

/**
 * Class PHPThumb
 * @package Helpers
 */
class PHPThumb
{

    private $thumb = null;
    protected $fs = null;
    public $debugMessages = '';

    /**
     * PHPThumb constructor.
     */
    public function __construct()
    {
        $this->thumb = new \phpthumb();
        $this->fs = FS::getInstance();
    }

    /**
     * @param $inputFile
     * @param $outputFile
     * @param $options
     * @return bool
     */
    public function create($inputFile, $outputFile, $options)
    {
        $this->thumb->sourceFilename = $inputFile;
        $ext = explode('.', $inputFile);
        $ext = str_replace('jpeg', 'jpg', strtolower(array_pop($ext)));
        $options = 'f=' . $ext . '&' . $options;
        $this->setOptions($options);
        if ($this->thumb->GenerateThumbnail() && $this->thumb->RenderToFile($outputFile)) {
            return true;
        } else {
            $this->debugMessages = implode('<br/>', $this->thumb->debugmessages);

            return false;
        }
    }

    /**
     * @param $file
     * @param string $type
     */
    public function optimize($file, $type = 'jpg')
    {
        switch ($type) {
            case 'jpg':
                $ext = $this->fs->takeFileExt($file);
                if ($ext == 'jpeg' || $ext == 'jpg') {
                    $cmd = '/usr/bin/jpegtran -optimize -progressive -copy none -outfile ' . escapeshellarg($file . '_') . ' ' . escapeshellarg($file);
                    exec($cmd, $result, $return_var);
                    if ($this->fs->fileSize($file) > $this->fs->fileSize($file . '_')) {
                        $this->fs->moveFile($file . '_', $file);
                    } else {
                        $this->fs->unlink($file . '_');
                    }
                }
                break;
            default:
                break;
        }
    }

    /**
     * @param $options
     */
    private function setOptions($options)
    {
        $options = strtr($options, array("," => "&", "_" => "=", '{' => '[', '}' => ']'));
        parse_str($options, $params);
        if (!is_array($params)) {
            $params = array();
        }

        foreach ($params as $key => $value) {
            $this->thumb->setParameter($key, $value);
        }
    }

}
