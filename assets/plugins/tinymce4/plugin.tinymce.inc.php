<?php
/* Check plugin.tinymce.php for details */

if (!defined('MODX_BASE_PATH')) { die('What are you doing? Get out of here!'); }

// Init
if( !file_exists(MODX_BASE_PATH."assets/lib/class.modxRTEbridge.php")) { // Add Fall-Back for now
    require_once(MODX_BASE_PATH."assets/plugins/tinymce4/class.modxRTEbridge.php"); 
} else {
    require_once(MODX_BASE_PATH."assets/lib/class.modxRTEbridge.php");
}
require_once(MODX_BASE_PATH."assets/plugins/tinymce4/bridge.tinymce4.inc.php");

$rte = new tinymce4bridge($options);
$rte->setDebug(false);  // true or 'full' for Debug-Infos in HTML-comments

// Overwrite theme
// $rte->force('width',          '75%', 'string' );                               // Overwrite width parameter
// $rte->force('height',         isset($height) ? $height : '400px', 'string' );  // Get/set height from plugin-configuration
// $rte->force('height',         NULL );                                          // Removes "height" completely from editor-init


// Internal Stuff - Don´t touch!
$showSettingsInterface = true;  // Show/Hide interface in Modx- / user-configuration
$editorLabel = $rte->pluginParams['editorLabel'];
$editableClass = !empty( $rte->pluginParams['editableClass'] ) ? $rte->pluginParams['editableClass'] : 'editable';

$e = &$modx->event;
switch ($e->name) {
    // register for manager
    case "OnRichTextEditorRegister":
        $e->output($editorLabel);
        break;

    // render script for JS-initialization
    case "OnRichTextEditorInit":
        if ($editor === $editorLabel) {
            // Handle introtext-RTE
            if($introtextRte == 'enabled') {
                $rte->pluginParams['elements'][] = 'introtext';
                $rte->tvOptions['introtext']['theme'] = 'introtext';
            }
            $script = $rte->getEditorScript();
            $e->output($script);
        };
        break;

    // render script for Frontend JS-initialization (Inline-Mode)
    case "OnWebPagePrerender":
        if($inlineMode == 'enabled') {
            $rte->set('inline', true, 'bool'); // https://www.tinymce.com/docs/configure/editor-appearance/#inline
            $rte->setPluginParam('elements', $editableClass);  // Set missing plugin-parameter manually for Frontend
            $rte->addEditorScriptToBody();
        }
        break;

    // Avoid breaking content / parsing of Modx-placeholders when editing (Inline-Mode)
    case "OnLoadWebDocument":
        if($inlineMode == 'enabled') {
            $rte->protectModxPhs($editableIds);
        }
        break;

    // render Modx- / User-configuration settings-list
    case "OnInterfaceSettingsRender":
        if( $showSettingsInterface === true ) {
            $html = $rte->getModxSettings();
            $e->output($html);
        };
        break;

    default :
        return; // important! stop here!
        break;
}