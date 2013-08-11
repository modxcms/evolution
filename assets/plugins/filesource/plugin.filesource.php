<?php
/**
 * @name FileSource
 * @version 0.1
 * 
 * @description Позволяет хранить сниппеты в виде файлов
 * 
 * @author Maxim Mukharev
 * @install
 * Привязываем к следующим событиям:
 *      - OnSnipFormRender
 *  - OnBeforeSnipFormSave
 *  - OnSnipFormPrerender
 */

global $_lang;

$e = $modx->Event;

$output = '';

/**
 * Подготовка информации перед рендером формы редактирования сниппета
 */
$name=$e->name;
$dir='snippets';
$vals='snippet';
$include='return require';
$count=47;
  if ($name=='OnPluginFormPrerender' ||$name=='OnPluginFormRender' ||$name=='OnBeforePluginFormSave') {$dir='plugins';$vals='plugincode';$count=39;$include='require';}



switch ($e->name) {
          case 'OnPluginFormPrerender':
        case 'OnSnipFormPrerender':

                global $content;  
                if(substr(trim($content[$vals]),0,$count) == $include.' MODX_BASE_PATH.\'assets/'.$dir.'/'){
                        $content['file_binding'] = str_replace(array(';','\''),'',trim(substr(trim($content[$vals]),$count,250)));
                        $snippetPath = MODX_BASE_PATH . 'assets/'.$dir.'/' . $content['file_binding'];
                        $content[$vals] = file_get_contents($snippetPath);
                  if ( $vals=='snippet'){
                        if ( strncmp($content[$vals], "<?", 2) == 0 ) { // strip out PHP tags (from save_snippet.processor.php)
                                $content[$vals] = substr($content[$vals], 2);
                                if ( strncmp( $content[$vals], "php", 3 ) == 0 ) $content[$vals] = substr($content[$vals], 3);
                                if ( substr($content[$vals], -2, 2) == '?>' ) $content[$vals] = substr($content[$vals], 0, -2);
                         
                } else {
                        $content['file_binding'] = '';
                }
                }
                
                if ( $vals=='plugincode'){
                        if ( strncmp($content[$vals], "<?", 2) == 0 ) { // strip out PHP tags (from save_snippet.processor.php)
                                $content[$vals] = substr($content[$vals], 2);
                                if ( strncmp( $content[$vals], "php", 3 ) == 0 ) $content[$vals] = substr($content[$vals], 3);
                                if ( substr($content[$vals], -2, 2) == '?>' ) $content[$vals] = substr($content[$vals], 0, -2);
                         
                } 
}
                        $_SESSION['itemname']=$content['name'];
                } elseif (substr(trim($content[$vals]),0,7) == '//@FILE'){ // Added by Carw
                        $content['file_binding'] = str_replace(';','',trim(substr(trim($content[$vals]),7,250)));
                        $snippetPath = MODX_BASE_PATH . 'assets/'.$dir.'/' . $content['file_binding'];
                        $content[$vals] = file_get_contents($snippetPath);
                        if ( strncmp($content[$vals], "<?", 2) == 0 ) { // strip out PHP tags (from save_snippet.processor.php)
                                $content[$vals] = substr($content[$vals], 2);
                                if ( strncmp( $content[$vals], "php", 3 ) == 0 ) $content[$vals] = substr($content[$vals], 3);
                                if ( substr($content[$vals], -2, 2) == '?>' ) $content[$vals] = substr($content[$vals], 0, -2);
                } else {
                        $content['file_binding'] = '';
                }
                $_SESSION['itemname']=$content['name'];
                } else {
                        $_SESSION['itemname']="New snippet";
                }
                break;
        case 'OnSnipFormRender':
            case 'OnPluginFormRender':
                global $content;
  
                $output = '
                        <script type="text/javascript">
                                mE1 = new Element("tr");
                                mE11 = new Element("td",{"align":"left","styles":{"padding-top":"14px"}});
                                mE12 = new Element("td",{"align":"left","styles":{"padding-top":"14px"}});
                                mE122 = new Element("input",{"name":"filebinding","type":"text","maxlength":"45","value":"'.$content['file_binding'].'","class":"inputBox","styles":{"width":"300px","margin-left":"14px"},"events":{"change":function(){documentDirty=true;}}});
                                
                                mE11.appendText("Привязанный файл:");
                                mE11.inject(mE1);
                                mE122.inject(mE12);
                                mE12.inject(mE1);
                                
                                setPlace = $("displayparamrow");
                                
                                mE1.inject(setPlace,"after");
                                
                        </script>
                ';
                break;
        case 'OnBeforeSnipFormSave':
                            if(!empty($_POST['filebinding'])) {
                        global $snippet;
                        $pathsnippet = trim($modx->db->escape($_POST['filebinding']));
                        $fullpathsnippet = MODX_BASE_PATH . 'assets/'.$dir.'/' . $pathsnippet;
                        
                        if($fl = @fopen($fullpathsnippet,'w')) {
                                fwrite($fl, $_POST['post']);
                                fclose($fl);
                                $snippet = $modx->db->escape($include.' MODX_BASE_PATH.\'assets/'.$dir.'/' . $pathsnippet . '\';');
                        }
                }
                break;
            case 'OnBeforePluginFormSave':
 
              
                if(!empty($_POST['filebinding'])) {
                        global $plugincode;
                        $pathsnippet = trim($modx->db->escape($_POST['filebinding']));
                        $fullpathsnippet = MODX_BASE_PATH . 'assets/'.$dir.'/' . $pathsnippet;
                  
                  
                        $code='<?php '.$_POST['post'].'?>';

                        if($fl = @fopen($fullpathsnippet,'w')) {
                          fwrite($fl, $code);
                                fclose($fl);
                                $plugincode = $modx->db->escape($include.' MODX_BASE_PATH.\'assets/'.$dir.'/' . $pathsnippet . '\';');
                        }
                  
                }
                break;
}

if($output != '') {
        $e->output($output);
}
?>