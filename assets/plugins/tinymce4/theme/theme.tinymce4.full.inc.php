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
$this->set('plugins', 'anchor autolink lists spellchecker pagebreak layer table save hr modxlink image imagetools emoticons insertdatetime preview media searchreplace print code contextmenu paste directionality fullscreen noneditable visualchars textcolor nonbreaking template youtube autosave advlist visualblocks charmap', 'string');
$this->set('toolbar1', 'save newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect', 'string');
$this->set('toolbar2', 'cut copy paste pastetext | search replace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image help code | insertdate inserttime preview | forecolor backcolor', 'string');
$this->set('toolbar3', 'table | hr removeformat visualblocks | subscript superscript | charmap emoticons youtube media hr | print | ltr rtl | fullscreen', 'string');
$this->set('toolbar4', 'insertlayer moveforward movebackward absolute | styleprops spellchecker | cite abbr acronym del ins attribs | visualchars nonbreaking template blockquote pagebreak | insertfile insertimage', 'string');