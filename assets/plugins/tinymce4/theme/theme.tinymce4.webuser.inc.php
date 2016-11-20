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

$this->set('menubar', true, 'bool' );    // https://www.tinymce.com/docs/configure/editor-appearance/#menubar
$this->set('statusbar', false, 'bool' ); // https://www.tinymce.com/docs/get-started/customize-ui/#hidingthestatusbar

// @todo: Set default plugins for webusers
// $this->set('plugins', '', 'string' );

// Overwrite default plugins if given
if( !empty( $this->pluginParams['webPlugins'])) {
    $this->set('plugins', $this->pluginParams['webPlugins'], 'string' );
};

$this->set('toolbar1', $this->pluginParams['webButtons1'], 'string', false );
$this->set('toolbar2', $this->pluginParams['webButtons2'], 'string', false );
$this->set('toolbar3', $this->pluginParams['webButtons3'], 'string', false );
$this->set('toolbar4', $this->pluginParams['webButtons4'], 'string', false );