<?php
/**
 * Mc — Manager Control Class for MODx / Qm+
 *  
 * @author      Urique Dertlian, urique@unix.am & Mikko Lammi, www.maagit.fi
 * @license     GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @version     1.1.1 updated 21/04/2009                
 */

if(!class_exists('Mcc')) {
    class Mcc {
    var $script;
    var $head;
    var $tabs;
    var $sections;
    var $fields;

        //_______________________________________________________
        function Mcc($jqpath) {
            $this->jqpath = $jqpath;
            $this->tabs = array(
            'general'       => array('index'=>1,'id'=>'tabGeneral'),
            'settings'      => array('index'=>2,'id'=>'tabSettings'),
            'meta'          => array('index'=>3,'id'=>'tabMeta'),
            'preview'       => array('index'=>4,'id'=>'tabPreview')
            );
            
            $this->sections = array(
            'docsettings'   => array('index'=>1,'name'=>'DocSettings'),
            'content'       => array('index'=>2,'name'=>'Content'),
            'tvs'           => array('index'=>3,'name'=>'TVs'),
            'access'        => array('index'=>4,'name'=>'Access')
            );
            
            $this->fields = array('content','pagetitle','longtitle','menuindex','parent','description','alias','link_attributes','introtext','template','menutitle');
            
            global $modx;	
        }
        
        //_______________________________________________________
        function addLine($line) {
            $this->script.=$line."\n";
        }
        
        //_______________________________________________________
        function Output() {
            global $modx;
            $out = $this->head;
            $this->addLine('document.body.style.display="block";');
            $this->addLine('$("#actions").hide();');
            $out.= '<script src="'.$modx->config['site_url'].$this->jqpath.'" type="text/javascript"></script>';
            $out.= '<script type="text/javascript">jQuery.noConflict(); jQuery(document).ready(function($){'.$this->script.'});</script>';
            return $out;
        }
        
        // Template
        //_______________________________________________________
        function hideTemplate($tpl) {
            $this->addLine('$("select#template option[value='.$tpl.']").remove();');
        }
        
        //_______________________________________________________
        function hideTemplates($tpls) {
            if(is_array($tpls)) {
                foreach($tpls as $tpl) {
                    $this->hideTemplate($tpl);
                }
                $this->hideTemplate(0); // remove blank
            }
        }
        
        // Section
        //_______________________________________________________
        function hideSection($section) { //DocSettings, Content, TVs, Access
            if(!isset($this->sections[$section])) return;
            $sectionName = $this->sections[$section]['name'];
            $this->addLine('$("#section'.$sectionName.'Header").hide();');
            $this->addLine('$("#section'.$sectionName.'Body").hide();');
        }
        //_______________________________________________________
        function renameSection($section, $newname) {
        $this->doSafe($newname);
            //div#documentPane h2:nth-child(1) span
            //$this->addLine('$("div#section'.$section.'Header").empty().prepend("'.$newname.'");');
            $this->addLine('$("div#section'.$section.'Header").empty().prepend("'.$newname.'");');
        }
        
        // Tab
        function hideTab($tab) {
            $tabIndex = $this->tabs[$tab]['index'];
            $tabId = $this->tabs[$tab]['id'];
            $this->addLine('$("div#documentPane h2:nth-child('.($tabIndex).')").hide();');
            $this->addLine('$("#'.$tabId.'").hide();');
        }
        //_______________________________________________________
        function renameTab($tab, $newname) {
            $this->doSafe($newname);
            $tabIndex = $this->tabs[$tab]['index'];
            $this->addLine('$("div#documentPane h2:nth-child('.$tabIndex.') span").empty().prepend("'.$newname.'");');
        }
        
        // Field
        //_______________________________________________________
        function hideField($field) {
            if(empty($field)) return;	
            if($field == 'content') return $this->hideSection($field);
            $this->addLine('$("[name='.$field.']").parents("tr").hide();');
        }
        
        //_______________________________________________________
        function showFields($fields) {
            if(!($fields = explode(',',$fields))) return;
            foreach($fields as $key=>$value) {$fields[$key] = trim($value);}
            foreach($this->fields as $field) {
                if(!in_array($field,$fields))
                $this->hideField($field);
            }
        }
        
        //_______________________________________________________
        function doSafe($string) {
            global $modx;
            $string = htmlentities($string, ENT_QUOTES, $modx->config['modx_charset']);
        }
    }
}
?>