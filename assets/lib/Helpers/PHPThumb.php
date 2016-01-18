<?php

namespace Helpers;

include_once(MODX_BASE_PATH.'assets/snippets/phpthumb/phpthumb.class.php');

class PHPThumb{

    private $thumb = null;
    public $debugMessages = '';

    public function __construct()
    {
       $this->thumb = new \phpthumb();
    }

    public function create($inputFile, $outputFile, $options) {
        $this->thumb->sourceFilename = $inputFile;
        $ext = str_replace('jpeg','jpg',strtolower(array_pop(explode('.',$inputFile))));
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
                $ext = strtolower(end(explode('.', $file)));
                if ($ext == 'jpeg' || $ext == 'jpg') {
                    $cmd = '/usr/bin/jpegtran -optimize -progressive -copy none -outfile '.escapeshellarg($file.'_').' '.escapeshellarg($file);
                    exec($cmd, $result, $return_var);
                    if (filesize($file) > filesize($file.'_')) {
                        @rename($file.'_',$file);
                    } else {
                        @unlink($file.'_');
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
