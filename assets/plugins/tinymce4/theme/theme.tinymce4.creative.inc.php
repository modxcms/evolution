<?php
/*
 * All available config-params of TinyMCE4
 * https://www.tinymce.com/docs/configure/
 *
 * Belows default configuration setup assures all editor-params have a fallback-value, and type per key is known
 * $this->set( $editorParam, $value, $type, $emptyAllowed=false )
 *
 * $editorParam     = param to set
 * $value           = value to set
 * $type            = string, number, bool, json (array or string)
 * $emptyAllowed    = true, false (allows param:'' instead of falling back to default)
 * If $editorParam is empty and $emptyAllowed is true, $defaultValue will be ignored
 *
 * $this->modxParams holds an array of actual Modx- / user-settings
 *
 * */

// @todo: make "styleprops"-button work with "compat3x-plugin"?
// http://archive.tinymce.com/forum/viewtopic.php?pid=115507#p115507

// @todo: layer-Plugin: Buttons broken

$this->set('plugins', 'anchor save autolink autosave advlist layer fullscreen hr paste modxlink media contextmenu table youtube image imagetools code textcolor', 'string');
$this->set('toolbar1', 'undo redo | bold forecolor backcolor strikethrough formatselect styleselect fontsizeselect code', 'string');
$this->set('toolbar2', 'image media youtube link unlink anchor | bullist numlist | blockquote outdent indent | alignleft aligncenter alignright | hr | styleprops removeformat | pastetext', 'string');
$this->set('toolbar3', 'insertlayer absolute moveforward movebackward | table | fullscreen help', 'string');