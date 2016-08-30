<?php namespace Helpers;

include_once(MODX_BASE_PATH.'assets/snippets/phpthumb/phpthumb.class.php');
require_once(MODX_BASE_PATH . 'assets/lib/Helpers/FS.php');

class PHPThumb{

    private $thumb = null;
    protected $fs = null;
	public $debugMessages = '';

    public function __construct()
    {
       	$this->thumb = new \phpthumb();
		$this->fs = FS::getInstance();
    }

    public function create($inputFile, $outputFile, $options) {
        $this->thumb->sourceFilename = $inputFile;
		$ext = explode('.',$inputFile);
        $ext = str_replace('jpeg','jpg',strtolower(array_pop($ext)));
        $options = 'f='.$ext.'&'.$options;
        $this->setOptions($options);
        if ($this->thumb->GenerateThumbnail() && $this->thumb->RenderToFile($outputFile)) {
            return true;
        } else {
            $this->debugMessages = implode('<br/>', $this->thumb->debugmessages);
            return false;
        }
    }

    public function optimize($file, $type = 'jpg') {
        switch ($type) {
            case 'jpg':
				$ext = $this->fs->takeFileExt($file);
                if ($ext == 'jpeg' || $ext == 'jpg') {
                    $cmd = '/usr/bin/jpegtran -optimize -progressive -copy none -outfile '.escapeshellarg($file.'_').' '.escapeshellarg($file);
                    exec($cmd, $result, $return_var);
                    if ($this->fs->fileSize($file) > $this->fs->fileSize($file.'_')) {
                        $this->fs->moveFile($file.'_', $file);
                    } else {
                        $this->fs->unlink($file.'_');
                    }
                }
                break;
            default:
                break;
        }
    }

    private function setOptions($options) {
        $options = strtr($options, Array("," => "&", "_" => "=", '{' => '[', '}' => ']'));
        parse_str($options, $params);
        foreach ($params as $key => $value) {
            $this->thumb->setParameter($key, $value);
        }
    }
}
