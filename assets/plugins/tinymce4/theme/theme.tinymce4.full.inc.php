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

// @todo: Is this list complete for a "full"-theme?
$this->set('plugins', 'anchor autolink lists spellchecker pagebreak layer table save hr modxlink image imagetools emoticons insertdatetime preview media searchreplace print code contextmenu paste directionality fullscreen noneditable visualchars textcolor nonbreaking template youtube autosave advlist visualblocks charmap wordcount codesample', 'string');
$this->set('toolbar1', 'save print newdocument | undo redo | searchreplace | cut copy paste pastetext | visualchars spellchecker | visualblocks code | preview fullscreen', 'string');
$this->set('toolbar2', 'styleselect formatselect fontselect fontsizeselect | forecolor backcolor', 'string');
$this->set('toolbar3', 'bold italic underline strikethrough subscript superscript removeformat | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent blockquote | ltr rtl', 'string');
$this->set('toolbar4', 'image youtube media | link unlink anchor | table | pagebreak hr | template codesample nonbreaking insertdatetime | charmap emoticons', 'string');