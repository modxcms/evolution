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

$this->set('plugins', 'anchor save autosave advlist modxlink image imagetools searchreplace print contextmenu paste fullscreen nonbreaking visualchars media youtube code', 'string');
$this->set('toolbar1', 'undo redo selectall | pastetext | search replace | nonbreaking hr charmap | image link unlink anchor media youtube | removeformat | fullscreen print code help', 'string');
$this->set('toolbar2', 'bold italic underline strikethrough subscript superscript | blockquote | bullist numlist outdent indent | alignleft aligncenter alignright alignjustify | styleselect formatselect | styleprops', 'string');