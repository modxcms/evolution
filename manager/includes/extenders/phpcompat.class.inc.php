<?php

/**
 * @TODO file_put_contents(), strftime(), mb_*()
 */
class PHPCOMPAT
{
    /**
     * @param string $str
     * @param int $flags
     * @param string $encode
     * @return string
     */
    public function htmlspecialchars($str = '', $flags = ENT_COMPAT, $encode = '')
    {
        global $modx;

        if ($str == '') {
            return '';
        }

        if ($encode == '') {
            $encode = $modx->config['modx_charset'];
        }

        $ent_str = htmlspecialchars($str, $flags, $encode);

        if (!empty($str) && empty($ent_str)) {
            $detect_order = implode(',', mb_detect_order());
            $ent_str = mb_convert_encoding($str, $encode, $detect_order);
        }

        return $ent_str;
    }
}
