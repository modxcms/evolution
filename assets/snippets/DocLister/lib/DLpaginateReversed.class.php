<?php

/**
 * Class DLpaginate
 */
class DLpaginateReversed extends DLpaginate
{

    /**
     * @param $page
     * @return int|mixed
     */
    protected function getPageQuery($page)
    {
        switch ($this->mode) {
            case 'offset':
                $display = isset($this->modeConfig['display']) ? $this->modeConfig['display'] : 0;
                $out = $display * ($this->total_pages - $page);
                break;
            case 'back':
            case 'pages':
            default:
                $out = $page;
                break;
        }

        return $out;
    }

    /**
     * @param $tpl
     * @param $num
     * @return mixed
     */
    protected function renderItemTPL($tpl, $num)
    {
        $_num = $this->total_pages + 1 - $num;
        return str_replace(array('[+num+]', '[+link+]'), array($_num, $this->get_pagenum_link($_num)), $tpl);
    }

    /**
     * @param $id
     * @return mixed|string
     */
    public function get_pagenum_link($id)
    {
        $flag = (strpos($this->target, '?') === false);
        $value = $this->getPageQuery($id);
        if ($flag && !empty($this->urlF)) {
            $out = str_replace($this->urlF, $value, $this->target);
        } else {
            $out = $this->target;
            if ($id > 0 && $id < $this->total_pages) {
                $out .= ($flag ? "?" : "&") . $this->parameterName . "=" . $value;
            }
        }

        return $out;
    }
}
