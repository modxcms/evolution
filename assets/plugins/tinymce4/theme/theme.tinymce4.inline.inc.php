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

$this->set('plugins', 'autolink save emoticons modxlink paste image imagetools contextmenu template', 'string');

$this->set('toolbar1', 'undo redo | formatselect | bold strikethrough | alignleft aligncenter alignright | link unlink image emoticons template | help', 'string');
$this->set('toolbar2', NULL, 'string');

// Hide bars
$this->set('menubar',               false,                           'bool' );       // https://www.tinymce.com/docs/configure/editor-appearance/#menubar
$this->set('statusbar',             false,                           'bool' );       // https://www.tinymce.com/docs/get-started/customize-ui/#hidingthestatusbar

// When using template-plugin/button, you can mark elements as noneditable via <div class="myclass mceNonEditable">Contents</div>
// https://www.tinymce.com/docs/plugins/noneditable/
$this->appendSet('plugins', 'noneditable', ' ');