<?php
if( ! function_exists('getLangOptions')) {
    /**
     * @param string $install_language
     * @return string
     */
    function getLangOptions($install_language = 'english')
    {
        $langs = array();
        if ($handle = opendir('lang/')) {
            while (false !== ($file = readdir($handle))) {
                if (strpos($file, '.')) {
                    $langs[] = str_replace('.inc.php', '', $file);
                }
            }
            closedir($handle);
        }
        sort($langs);
        $_ = array();
        foreach ($langs as $language) {
            $abrv_language = explode('-', $language);
            $selected = ($language === $install_language) ? 'selected' : '';
            $_[] = sprintf('<option value="%s" %s>%s</option>', $language, $selected,
                    ucwords($abrv_language[0])) . "\n";
        }

        return implode("\n", $_);
    }
}

$content = file_get_contents('actions/tpl_language.html');
$content = parse($content, array(
    'langOptions' => getLangOptions($install_language))
);
$content = parse($content, $_lang,'[%','%]');

echo $content;
