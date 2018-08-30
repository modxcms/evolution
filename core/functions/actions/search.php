<?php

if(!function_exists('highlightingCoincidence')) {
    /**
     * @param string $text
     * @param string $search
     * @return string
     */
    function highlightingCoincidence($text, $search)
    {
        $regexp = '!(' . str_replace(array(
                '(',
                ')'
            ), array(
                '\(',
                '\)'
            ), entities(trim($search), evolutionCMS()->getConfig('modx_charset'))) . ')!isu';

        return preg_replace($regexp, '<span class="text-danger">$1</span>', entities($text, evolutionCMS()->getConfig('modx_charset')));
    }
}

if(!function_exists('addClassForItemList')) {
    /**
     * @param string $locked
     * @param string $disabled
     * @param string $deleted
     * @return string
     */
    function addClassForItemList($locked = '', $disabled = '', $deleted = '')
    {
        $class = '';
        if ($locked) {
            $class .= 'locked';
        }
        if ($disabled) {
            $class .= ' disabled';
        }
        if ($deleted) {
            $class .= ' deleted';
        }
        if ($class) {
            $class = ' class="' . trim($class) . '"';
        }

        return $class;
    }
}

