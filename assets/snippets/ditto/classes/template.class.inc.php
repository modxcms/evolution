<?php

/*
 * Title: Template Class
 * Purpose:
 *      The Template class contains all functions relating to Ditto's
 *      handling of templates and any supporting functions they need
*/

class template{
    var $language,$fields,$current;

    // ---------------------------------------------------
    // Function: template
    // Set the class language and fields variables
    // ---------------------------------------------------
    function __construct() {
        $this->language = $GLOBALS['ditto_lang'];
        $this->fields = array (
            'db' => array (),
            'tv' => array (),
            'custom' => array (),
            'item' => array (),
            'phx' => array (),
            'rss' => array (),
            'json' => array (),
            'xml' => array (),
            'unknown' => array()
        );
    }

    // ---------------------------------------------------
    // Function: process
    // Take the templates and parse them for tempalte variables,
    // Check to make sure they have fields, and sort the fields
    // ---------------------------------------------------
    function process($template) {
        if (!isset($template['base'])) $template['base'] = $template['default'];
        else                           unset($template['default']);
        
        foreach ($template as $name=>$tpl) {
            if(!empty($tpl) && $tpl != '') $templates[$name] = $this->fetch($tpl);
        }
        $fieldList = array();
        foreach ($templates as $tplName=>$tpl) {
            $check = $this->findTemplateVars($tpl);
            if (is_array($check)) $fieldList = array_merge($check, $fieldList);
            else {
                switch ($tplName) {
                    case 'base'   :
                    case 'default': $displayName = 'tpl';break;
                    default       : $displayName = 'tpl'.$tplName;
                }
                $templates[$tplName] = str_replace('[+tpl+]',$displayName,$this->language['bad_tpl']);
            }
        }

        $fieldList = array_unique($fieldList);
        $fields = $this->sortFields($fieldList);
        $checkAgain = array ('json', 'xml');
        foreach ($checkAgain as $type) {
            $fields = array_merge_recursive($fields, $this->sortFields($fields[$type]));
        }
        $this->fields = $fields;
        return $templates;
    }

    // ---------------------------------------------------
    // Function: findTemplateVars
    // Find al the template variables in the template
    // ---------------------------------------------------
    function findTemplateVars($tpl) {
        preg_match_all('~\[\+(.*?)\+\]~', $tpl, $matches);
        $TVs = array();
        foreach($matches[1] as $tv) {
            $match = explode(':', $tv);
            $TVs[strtolower($match[0])] = $match[0];
        }
        if (count($TVs) >= 1) {
            return array_values($TVs);
        } else {
            return false;
        }
    }

    // ---------------------------------------------------
    // Function: sortFields
    // Sort the array of fields provided by type
    // ---------------------------------------------------
    function sortFields ($fieldList) {
        global $ditto_constantFields;
        $dbFields = $ditto_constantFields['db'];
        $tvFields = $ditto_constantFields['tv'];
        $fields = array (
            'db' => array (),
            'tv' => array (),
            'custom' => array (),
            'item' => array (),
            'phx' => array (),
            'rss' => array (),
            'json' => array (),
            'xml' => array (),
            'unknown' => array()
        );
        
        $custom = array('author','date','url','title','ditto_iteration');

        foreach ($fieldList as $field) {
            if (substr($field, 0, 4)    == 'rss_')  $fields['rss'][]  = substr($field,4);
            elseif(substr($field, 0, 4) == 'xml_')  $fields['xml'][]  = substr($field,4);
            elseif(substr($field, 0, 5) == 'json_') $fields['json'][] = substr($field,5);
            elseif(substr($field, 0, 5) == 'item[') $fields['item'][] = substr($field,4);
            elseif(substr($field, 0, 4) == 'phx:')  $fields['phx'][]  = $field;
            elseif(in_array($field, $dbFields))     $fields['db'][]   = $field;
            elseif(in_array($field, $tvFields))     $fields['tv'][]   = $field;
            elseif(substr($field, 0, 2) == 'tv' && in_array(substr($field,2), $tvFields))
                                                    $fields['tv'][]   = substr($field,2);
                    // TODO: Remove TV Prefix support in Ditto
            elseif(in_array($field, $custom))       $fields['custom'][]  = $field;
            else                                    $fields['unknown'][] = $field; 
        }
        return $fields;
    }

    // ---------------------------------------------------
    // Function: replace
    // Replcae placeholders with their values
    // ---------------------------------------------------
    public static function replace( $placeholders, $tpl ) {
        $keys = array();
        $values = array();
        foreach ($placeholders as $key=>$value) {
            $keys[] = '[+'.$key.'+]';
            $values[] = $value;
        }
        return str_replace($keys,$values,$tpl);
    }

    // ---------------------------------------------------
    // Function: determine
    // Determine the correct template to apply
    // ---------------------------------------------------        
    function determine($templates,$x,$start,$stop,$id) {
        global $modx;

        // determine current template
        if ($x == ($stop -1) && !empty($templates['last'])) $currentTPL = 'last';
        elseif ($x == 0 && !empty($templates['first']))     $currentTPL = 'first';
        elseif ($id == $modx->documentIdentifier && !empty($templates['current']))
                                                            $currentTPL = 'current';
        elseif ($x % 2 && !empty($templates['alt']))        $currentTPL = 'alt';
        else                                                $currentTPL = 'base';
        
        $this->current = $currentTPL;
        return $templates[$currentTPL];
    }

    // ---------------------------------------------------
    // Function: fetch
    // Get a template, based on version by Doze
    // 
    // http://forums.modx.com/thread/41066/support-comments-for-ditto?page=2#dis-post-237942
    // ---------------------------------------------------
    function fetch($tpl){
        global $modx;
        $template = '';
        if(substr($tpl, 0, 6) == '@CHUNK') {
            $template = $modx->getChunk(substr($tpl, 7));
        } elseif(substr($tpl, 0, 5) == '@FILE') {
            $path = trim(substr($tpl, 6));
            if(strpos($path, 'manager/includes/config.inc.php')===false)
                $template = file_get_contents($path);
        } elseif(substr($tpl, 0, 5) == '@CODE') {
            $template = substr($tpl, 6);
        } elseif(strpos($tpl, '[+') !==false) {
            $template = $tpl;
        } elseif(substr($tpl, 0, 9) == '@DOCUMENT') {
            $docid = trim(substr($tpl, 10));
            if(preg_match('@^[1-9][0-9]*$@',$docid))
                $template = $modx->getField('content',$docid);
        } else {
            $template = $modx->getChunk($tpl);
        }
        
        if(strpos($template,'[!')!==false)
            $template = str_replace(array('[!','!]'),array('[[',']]'),$template);
        elseif($template===''||$template===false)
            $template = $this->language['missing_placeholders_tpl'];
        return $template;
    }

    // ---------------------------------------------------
    // Function: get_file_contents
    // Returns the contents of file name passed
    // 
    // From http://www.nutt.net/2006/07/08/file_get_contents-function-for-php-4/#more-210
    // ---------------------------------------------------
    function get_file_contents($filename) {
        if (!function_exists('file_get_contents')) {
            $fhandle = fopen($filename, 'r');
            $fcontents = fread($fhandle, filesize($filename));
            fclose($fhandle);
        } else    {
            $fcontents = file_get_contents($filename);
        }
        return $fcontents;
    }
}
