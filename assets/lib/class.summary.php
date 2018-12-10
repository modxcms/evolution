<?php
/**
 * summary
 * Truncates the HTML string to the specified length
 *
 * Copyright 2013 by Agel_Nash <Agel_Nash@xaker.ru>
 *
 * @category extender
 * @license GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @author Agel_Nash <Agel_Nash@xaker.ru>
 * @see http://blog.agel-nash.ru/addon/summary.html
 * @date 31.07.2013
 * @version 1.0.3
 */
include_once(MODX_BASE_PATH . 'assets/lib/APIHelpers.class.php');

/**
 * Class SummaryText
 */
class SummaryText
{
    /**
     * @var array
     */
    private $_cfg = array('content' => '', 'summary' => '', 'original' => '', 'break' => '');

    /**
     * @var bool|null
     */
    private $_useCut = null;

    /**
     * @var bool
     */
    private $_useSubstr = false;

    /**
     * @var int
     */
    private $_dotted = 0;

    /**
     * SummaryText constructor.
     * @param string $text
     * @param string $action
     * @param null|string $break
     */
    public function __construct($text, $action, $break = null)
    {
        $this->_cfg['content'] = is_scalar($text) ? $text : '';
        $this->_cfg['original'] = $this->_cfg['content'];
        $this->_cfg['summary'] = is_scalar($action) ? $action : '';
        $this->_cfg['break'] = is_scalar($break) ? $break : '. ';
    }

    /**
     * @param $cut
     * @return bool
     */
    public function setCut($cut)
    {
        if (is_scalar($cut) && $cut != '') {
            $this->_cfg['cut'] = $cut;
            $flag = true;
        } else {
            $flag = false;
        }

        return $flag;
    }

    /**
     * @return mixed
     */
    public function getCut()
    {
        return \APIHelpers::getkey($this->_cfg, 'cut', '<cut/>');
    }

    /**
     * @param int $scheme
     * @return mixed
     */
    protected function dotted($scheme = 0)
    {
        if (($scheme == 1 && ($this->_useCut === true || $this->_useSubstr)) || ($scheme == 2 && $this->_useSubstr && $this->_useCut !== true)) {
            $this->_cfg['content'] .= '&hellip;'; //...
        } else {
            if ($scheme && ($this->_useCut !== true|| $scheme != 2)) {
                $this->_cfg['content'] .= '.';
            }
        }

        return $this->_cfg['content'];
    }

    /**
     * @param int $dotted
     * @return mixed
     */
    public function run($dotted = 0)
    {
        $this->_dotted = $dotted;
        if (isset($this->_cfg['content'], $this->_cfg['summary']) && $this->_cfg['summary'] != '' && $this->_cfg['content'] != '') {
            $param = explode(",", $this->_cfg['summary']);
            $this->_cfg['content'] = $this->beforeCut($this->_cfg['content'], $this->getCut());
            foreach ($param as $doing) {
                $process = explode(":", $doing);
                switch ($process[0]) {
                    case 'notags':
                        $this->_cfg['content'] = strip_tags($this->_cfg['content']);
                        break;
                    case 'noparser':
                        $this->_cfg['content'] = APIhelpers::sanitarTag($this->_cfg['content']);
                        break;
                    case 'chars':
                        if (!(isset($process[1]) && $process[1] > 0)) {
                            $process[1] = 200;
                        }
                        $this->_cfg['content'] = APIhelpers::mb_trim_word($this->_cfg['content'], $process[1]);
                        break;
                    case 'len':
                        if (!(isset($process[1]) && $process[1] > 0)) {
                            $process[1] = 200;
                        }
                        $this->_cfg['content'] = $this->summary(
                            $this->_cfg['content'],
                            $process[1],
                            50,
                            true,
                            $this->getCut()
                        );
                        break;
                }
            }
        }

        return $this->dotted($dotted);
    }

    /**
     * @param $resource
     * @param string $splitter
     * @return array|mixed
     */
    protected function beforeCut($resource, $splitter = '')
    {
        if ($splitter !== '') {
            $summary = str_replace(
                '<p>' . $splitter . '</p>',
                $splitter,
                $resource
            ); // For TinyMCE or if it isn't wrapped inside paragraph tags
            $summary = explode($splitter, $summary, 2);
            $this->_useCut = isset($summary[1]);
            $summary = $summary['0'];
        } else {
            $summary = $resource;
        }

        return $summary;
    }

    /**
     * @param $resource
     * @param $truncLen
     * @param $truncOffset
     * @param $truncChars
     * @param string $splitter
     * @return array|mixed|string
     */
    protected function summary($resource, $truncLen, $truncOffset, $truncChars, $splitter = '')
    {
        if (isset($this->_useCut) && $splitter != '' && mb_strstr($resource, $splitter, 'UTF-8')) {
            $summary = $this->beforeCut($resource, $splitter);
        } else {
            if ($this->_useCut !== true && (mb_strlen($resource, 'UTF-8') > $truncLen)) {
                $summary = $this->html_substr($resource, $truncLen, $truncOffset, $truncChars);
                if ($resource != $summary) {
                    $this->_useSubstr = true;
                }
            } else {
                $summary = $resource;
            }
        }

        $summary = $this->closeTags($summary);
        $summary = $this->rTriming($summary);

        return $summary;
    }

    /**
     * @see summary extender for Ditto (truncate::html_substr)
     * @link https://github.com/modxcms/evolution/blob/develop/assets/snippets/ditto/extenders/summary.extender.inc.php#L142
     *
     * @param $posttext
     * @param int $minimum_length
     * @param int $length_offset
     * @param bool $truncChars
     * @return string
     */
    protected function html_substr($posttext, $minimum_length = 200, $length_offset = 100, $truncChars = false)
    {
        $tag_counter = 0;
        $quotes_on = false;
        if (mb_strlen($posttext) > $minimum_length && $truncChars !== true) {
            $c = 0;
            $len = mb_strlen($posttext, 'UTF-8');
            for ($i = 0; $i < $len; $i++) {
                $current_char = mb_substr($posttext, $i, 1, 'UTF-8');
                if ($i < mb_strlen($posttext, 'UTF-8') - 1) {
                    $next_char = mb_substr($posttext, $i + 1, 1, 'UTF-8');
                } else {
                    $next_char = "";
                }
                if (! $quotes_on) {
                    // Check if it's a tag
                    // On a "<" add 3 if it's an opening tag (like <a href...)
                    // or add only 1 if it's an ending tag (like </a>)
                    if ($current_char == '<') {
                        if ($next_char == '/') {
                            $tag_counter += 1;
                        } else {
                            $tag_counter += 3;
                        }
                    }
                    // Slash signifies an ending (like </a> or ... />)
                    // substract 2
                    if ($current_char == '/' && $tag_counter <> 0) {
                        $tag_counter -= 2;
                    }
                    // On a ">" substract 1
                    if ($current_char == '>') {
                        $tag_counter -= 1;
                    }
                    // If quotes are encountered, start ignoring the tags
                    // (for directory slashes)
                    if ($current_char == '"') {
                        $quotes_on = true;
                    }
                } else {
                    // IF quotes are encountered again, turn it back off
                    if ($current_char == '"') {
                        $quotes_on = false;
                    }
                }

                // Count only the chars outside html tags
                if ($tag_counter == 2 || $tag_counter == 0) {
                    $c++;
                }

                // Check if the counter has reached the minimum length yet,
                // then wait for the tag_counter to become 0, and chop the string there
                if ($c > $minimum_length - $length_offset && $tag_counter == 0) {
                    $posttext = mb_substr($posttext, 0, $i + 1, 'UTF-8');

                    return $posttext;
                }
            }
        }

        return $this->textTrunc($posttext, $minimum_length + $length_offset, $this->_cfg['break']);
    }

    /**
     * @see summary extender for Ditto (truncate::textTrunc)
     * @link https://github.com/modxcms/evolution/blob/develop/assets/snippets/ditto/extenders/summary.extender.inc.php#L213
     *
     * @param $string
     * @param $limit
     * @param string $break
     * @return string
     */
    protected function textTrunc($string, $limit, $break = ". ")
    {
        // Original PHP code from The Art of Web: www.the-art-of-web.com

        // return with no change if string is shorter than $limit
        if (mb_strlen($string, 'UTF-8') < $limit) {
            return $string;
        }

        $string = mb_substr($string, 0, $limit, 'UTF-8');
        if (false !== ($breakpoint = mb_strrpos($string, $break, 'UTF-8'))) {
            $string = mb_substr($string, 0, $breakpoint + 1, 'UTF-8');
        } else {
            if ($break != ' ') {
                $string = $this->textTrunc($string, $limit, " ");
            }
        }

        return $string;
    }

    /**
     * @param $str
     * @return mixed
     */
    protected function rTriming($str)
    {
        $str = preg_replace('/[\r\n]++/', ' ', $str);
        if ($this->_useCut !== true || $this->_dotted != 2) {
            $str = preg_replace("/(([\.,\-:!?;\s])|(&\w+;))+$/ui", "", $str);
        }

        return $str;
    }

    /**
     * @see summary extender for Ditto (truncate::closeTags)
     * @link https://github.com/modxcms/evolution/blob/develop/assets/snippets/ditto/extenders/summary.extender.inc.php#L227
     * @param $text
     * @return string
     */
    private function closeTags($text)
    {
        $openPattern = "/<([^\/].*?)>/";
        $closePattern = "/<\/(.*?)>/";
        $endTags = '';

        preg_match_all($openPattern, $text, $openTags);
        preg_match_all($closePattern, $text, $closeTags);

        $c = 0;
        $loopCounter = count($closeTags[1]); //used to prevent an infinite loop if the html is malformed
        while ($c < count($closeTags[1]) && $loopCounter) {
            $i = 0;
            while ($i < count($openTags[1])) {
                $tag = trim($openTags[1][$i]);

                if (mb_strstr($tag, ' ', 'UTF-8')) {
                    $tag = mb_substr($tag, 0, strpos($tag, ' '), 'UTF-8');
                }
                if ($tag == $closeTags[1][$c]) {
                    $openTags[1][$i] = '';
                    $c++;
                    break;
                }
                $i++;
            }
            $loopCounter--;
        }

        $results = $openTags[1];

        if (is_array($results)) {
            $results = array_reverse($results);

            foreach ($results as $tag) {
                $tag = trim($tag);

                if (mb_strstr($tag, ' ', 'UTF-8')) {
                    $tag = mb_substr($tag, 0, strpos($tag, ' '), 'UTF-8');
                }
                if (! mb_stristr($tag, 'br', 'UTF-8') && ! mb_stristr($tag, 'img', 'UTF-8') && ! empty($tag)) {
                    $endTags .= '</' . $tag . '>';
                }
            }
        }

        return $text . $endTags;
    }
}
