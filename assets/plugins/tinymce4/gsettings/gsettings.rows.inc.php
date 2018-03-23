<?php

$params = isset($params) && !empty($params) ? $params : array('base_url'=>'', 'skinsDirectory'=>'', 'skinthemeDirectory'=>'');
// Hold general settings based on old Modx TinyMCE-Settings

// Settings interface rows configuration
$settingsRows = array(
    'theme'=>array(
        'title'=>'editor_theme_title',
        'configTpl'=>'
                    <select name="[+name+]" class="inputBox">
                        [+theme_options+]
                    </select>',
        'message'=>'editor_theme_message',
        'messageVal'=>'<b>'. $params['base_url'].'<u>theme</u></b>'
    ),
    'skin'=>array(
        'title'=>'editor_skin_title',
        'configTpl'=>'
                    <select name="[+name+]" class="inputBox">
                        [+skin_options+]
                    </select>',
        'message'=>'editor_skin_message',
        'messageVal'=>'<b>'. $params['base_url'].'<u>'.$params['skinsDirectory'] .'</u></b>'
    ),
    'skintheme'=>array(
        'title'=>'editor_skintheme_title',
        'configTpl'=>'
                    <select name="[+name+]" class="inputBox">
                        [+skintheme_options+]
                    </select>',
        'message'=>'editor_skintheme_message',
        'messageVal'=>'<b>'. $params['base_url'].'<u>'.$params['skinthemeDirectory'] .'</u></b>'
    ),
    'template'=>array(
        'title'=>'tpl_title',
        'configTpl'=>'
                    <div style="margin-bottom:8px;"><label>[+tpl_docid+] <input type="text" class="inputBox" style="width: 300px;" name="[+name+]_docs" value="[+[+name+]_docs+]" /></label></div>
                    <div><label>[+tpl_chunkname+] <input type="text" class="inputBox" style="width: 300px;" name="[+name+]_chunks" value="[+[+name+]_chunks+]" /></label></div>',
        'message'=>'tpl_msg'
    ),
    'entermode'=>array(
        'title'=>'editor_entermode_title',
        'configTpl'=>'[+entermode_options+]',
        'message'=>'editor_entermode_message'
    ),
    'element_format'=>array(
        'title'=>'element_format_title',
        'configTpl'=>'[+element_format_options+]',
        'message'=>'element_format_message'
    ),
    'schema'=>array(
        'title'=>'schema_title',
        'configTpl'=>'[+schema_options+]',
        'message'=>'schema_message'
    ),

    'custom_plugins'=>array(
        'title'=>'editor_custom_plugins_title',
        'configTpl'=>'
                  <textarea class="inputBox mce" name="[+name+]">[+[+editorKey+]_custom_plugins+]</textarea>',
        'message'=>'editor_custom_plugins_message',
        'defaultCheckbox'=>true    
    ),
    'custom_buttons'=>array(
        'title'=>'editor_custom_buttons_title',
        'configTpl'=>'
                  Row 1: <textarea class="inputBox mce" name="[+name+]1">[+[+editorKey+]_custom_buttons1+]</textarea>
                  <div>[+editor_custom_buttons1_msg+]</div>
                  Row 2: <textarea class="inputBox mce" name="[+name+]2">[+[+editorKey+]_custom_buttons2+]</textarea>
                  <div>[+editor_custom_buttons2_msg+]</div>
                  Row 3: <textarea class="inputBox mce" name="[+name+]3">[+[+editorKey+]_custom_buttons3+]</textarea>
                  Row 4: <textarea class="inputBox mce" name="[+name+]4">[+[+editorKey+]_custom_buttons4+]</textarea>',
        'message'=>'editor_custom_buttons_message',
        'defaultCheckbox'=>true
    ),
    'css_selectors'=>array(
        'title'=>'editor_css_selectors_title',
        'configTpl'=>'
                    <textarea class="inputBox mce" name="[+name+]">[+[+editorKey+]_css_selectors+]</textarea>',
        'message'=>'editor_css_selectors_message',
        'defaultCheckbox'=>true
    )
);

?>
