<?php namespace EvolutionCMS\Support\Formatter;

class CSSMinify
{
    private $cssPath = array();

    public function __construct($cssFilesPath = array())
    {
        if (is_array($cssFilesPath) && !empty($cssFilesPath)) {
            $this->cssPath = $cssFilesPath;
        }
    }

    public function addFile($cssFilePath)
    {
        $this->cssPath[] = $cssFilePath;
    }

    public function minify()
    {
        $allCss = array();
        if (empty($this->cssPath)) {
            echo "No CSS was added";
            exit;
        }

        foreach ($this->cssPath as $css) {
            $bits = explode(".", $css);
            $ext = $bits[count($bits) - 1];
            if ($ext !== "css") {
                echo "Only CSS allowed";
                exit;
            }
            $filename = basename($css);
            $css = file_get_contents($css);
            $css = preg_replace("/\s{2,}/", "", $css);
            $css = str_replace(array("\n", ', ', ': ', '; ', ' > ', ' }', '} ', ';}', '{ ', ' {'), array('', ',', ':', ';', '>', '}', '}', '}', '{', '{'), $css);
            $css = preg_replace('/\/\*.*?\*\//s', '', $css);

            $allCss[] = '/* ' . $filename . ' */' . "\n" . $css;
        }

        return implode("\n", $allCss);
    }
}
