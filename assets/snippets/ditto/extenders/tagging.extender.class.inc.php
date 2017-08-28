<?php

class tagging {
    var $delimiter,$source,$landing,$mode,$format,$givenTags,$caseSensitive, $displayDelimiter, $sort, $displayMode, $tpl, $callback;

    function __construct($delimiter,$source,$mode,$landing,$givenTags,$format,$caseSensitive, $displayDelimiter, $callback, $sort, $displayMode, $tpl) {
        $this->delimiter = $delimiter;
        $this->source = $this->parseTagData($source);
        $this->mode = $mode;
        $this->landing = $landing;
        $this->format = $format;
        $this->givenTags = $this->prepGivenTags($givenTags);
        $this->caseSensitive = $caseSensitive;
        $this->displayDelimiter = $displayDelimiter;
        $this->sort = $sort;
        $this->displayMode = $displayMode;
        $this->tpl = $tpl;
        $this->callback = $callback;
    }

    function prepGivenTags ($givenTags) {
        global $modx,$_GET,$dittoID;

        $getTags = !empty($_GET[$dittoID.'tags']) ? $modx->stripTags(trim($_GET[$dittoID.'tags'])) : false;
        // Get tags from the $_GET array

        $tags1 = array();
        $tags2 = array();
    
        if ($getTags !== false)   $tags1 = explode($this->delimiter,$getTags);
    
        if ($givenTags !== false) $tags2 = explode($this->delimiter,$givenTags);
    
        $kTags = array();
        $tags = array_merge($tags1,$tags2);
        foreach ($tags as $tag) {
            if (empty($tag)) continue;
            
            if (!$this->caseSensitive) $tag = strtolower($tag);
            $tag = trim($tag);
            $kTags[strtolower($tag)] = $tag;
        }
        return $kTags;
    }

    function tagFilter ($value) {
        if ($this->caseSensitive == false) {
            $documentTags = array_values(array_flip($this->givenTags));
            $filterTags = array_values(array_flip($this->combineTags($this->source, $value,true)));
        } else {
            $documentTags = $this->givenTags;
            $filterTags =$this->combineTags($this->source, $value,true);
        }
        $compare = array_intersect($filterTags, $documentTags);
        $commonTags = count($compare);
        $totalTags = count($filterTags);
        $docTags = count($documentTags);
        
        $unset = 1;
        switch ($this->mode) {
            case 'onlyAllTags' :
                if ($commonTags != $docTags) $unset = 0;
                break;
            case 'removeAllTags' :
                if ($commonTags == $docTags) $unset = 0;
                break;
            case 'onlyTags' :
                if ($commonTags > $totalTags || $commonTags == 0) $unset = 0;
                break;
            case 'removeTags' :
                if ($commonTags <= $totalTags && $commonTags != 0) $unset = 0;
                break;
        }
        return $unset;
    }

    function makeLinks($resource) {
        return $this->tagLinks($this->combineTags($this->source,$resource,true), $this->delimiter, $this->landing, $this->format);
    }

    function parseTagData($tagData,$names=array()) {
        return explode(',',$tagData);
    }

    function combineTags($tagData, $resource, $array=false) {
        if ($this->callback !== false) {
            return call_user_func_array($this->callback,array('tagData'=>$tagData,'resource'=>$resource,'array'=>$array));
        }
        $tags = array();
        foreach ($tagData as $source) {
            if(!empty($resource[$source])) {
                $tags[] = $resource[$source];
            }
        }
        $kTags = array();
        $tags = explode($this->delimiter,implode($this->delimiter,$tags));
        foreach ($tags as $tag) {
            if (!empty($tag)) {
                if ($this->caseSensitive) {
                    $kTags[trim($tag)] = trim($tag);
                } else {
                    $kTags[strtolower(trim($tag))] = trim($tag);
                }
            }
        }
        return ($array == true) ? $kTags : implode($this->delimiter,$kTags);
    }

    function tagLinks($tags, $tagDelimiter, $tagID=false, $format='html') {
        global $ditto_lang,$modx;
        if(count($tags) == 0 && $format=='html') {
            return $ditto_lang['none'];
        } else if (count($tags) == 0 && ($format=='rss' || $format=='xml' || $format == 'xml'))
        {
            return '<category>'.$ditto_lang['none'].'</category>';
        }

        $output = '';
        if ($this->sort) {
            ksort($tags);
        }
        
        // set templates array
        $tplRss = "\r\n".'                <category>[+tag+]</category>';
        $tpl = ($this->tpl == false) ? '<a href="[+url+]" class="ditto_tag" rel="tag">[+tag+]</a>' : $this->tpl;
        
        $tpl = (in_array($format, array('rss','xml','atom')) && $templates['user'] == false) ? $tplRss : $tpl;
        
        if ($this->displayMode == 1) {
            foreach ($tags as $tag) {
                $tagDocID = (!$tagID) ? $modx->documentObject['id'] : $tagID;
                $url = ditto::buildURL("tags={$tag}&start=0",$tagDocID);
                $output .= template::replace(array('url'=>$url,'tag'=>$tag),$tpl);
                $output .= ($format != 'rss' && $format != 'xml' && $format != 'atom') ? $this->displayDelimiter : '';
            }
        } else if (!in_array($format, array('rss','xml','atom')) && $this->displayMode == 2) {
            $tagList = array();
            foreach ($tags as $tag) {
                $tagDocID = (!$tagID) ? $modx->documentObject['id'] : $tagID;
                $url = ditto::buildURL("tags={$tag}&start=0",$tagDocID);
                $tagList[] = template::replace(array('url'=>$url,'tag'=>$tag),$tpl);
            }
            $output = $this->makeList($tagList, 'ditto_tag_list', 'ditto_tag_');
        }
        
        return (!in_array($format, array('rss','xml','atom'))) ? substr($output,0,-1*strlen($this->displayDelimiter)) : $output;
    }
    
    function makeList($array, $ulroot= 'root', $ulprefix= 'sub_', $type= '', $ordered= false, $tablevel= 0) {
        // first find out whether the value passed is an array
        if (!is_array($array)) {
            return '<ul><li>Bad list</li></ul>';
        }
        if (!empty ($type)) {
            $typestr= " style='list-style-type: {$type}'";
        } else {
            $typestr= '';
        }
        $tabs= '';
        for ($i= 0; $i < $tablevel; $i++) {
            $tabs .= "\t";
        }
        $listhtml= $ordered == true ? $tabs . "<ol class='$ulroot'$typestr>\n" : $tabs . "<ul class='$ulroot'$typestr>\n";
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $listhtml .= $tabs . "\t<li>" . $key . "\n" . $this->makeList($value, $ulprefix . $ulroot, $ulprefix, $type, $ordered, $tablevel +2) . $tabs . "\t</li>\n";
            } else {
                $listhtml .= $tabs . "\t<li>" . $value . "</li>\n";
            }
        }
        $listhtml .= $ordered == true ? $tabs . "</ol>\n" : $tabs . "</ul>\n";
        return $listhtml;
    }
}
