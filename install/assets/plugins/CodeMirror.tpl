//<?php
/**
 * CodeMirror
 *
 * JavaScript library that can be used to create a relatively pleasant editor interface based on CodeMirror 5.12
 *
 * @category    plugin
 * @version     1.4
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @package     modx
 * @internal    @events OnDocFormRender,OnChunkFormRender,OnModFormRender,OnPluginFormRender,OnSnipFormRender,OnTempFormRender,OnRichTextEditorInit
 * @internal    @modx_category Manager and Admin
 * @internal    @properties &theme=Theme;list;default,ambiance,blackboard,cobalt,eclipse,elegant,erlang-dark,lesser-dark,midnight,monokai,neat,night,rubyblue,solarized,twilight,vibrant-ink,xq-dark,xq-light;default &indentUnit=Indent unit;int;4 &tabSize=The width of a tab character;int;4 &lineWrapping=lineWrapping;list;true,false;true &matchBrackets=matchBrackets;list;true,false;true &activeLine=activeLine;list;true,false;false &emmet=emmet;list;true,false;true &search=search;list;true,false;false &indentWithTabs=indentWithTabs;list;true,false;true &undoDepth=undoDepth;int;200 &historyEventDelay=historyEventDelay;int;1250
 * @internal    @installset base
 * @reportissues https://github.com/modxcms/evolution
 * @documentation Official docs https://codemirror.net/doc/manual.html
 * @author      hansek from http://www.modxcms.cz
 * @author      update Mihanik71
 * @author      update Deesen
 * @lastupdate  11/04/2016
 */

$_CM_BASE = 'assets/plugins/codemirror/';

$_CM_URL = $modx->config['site_url'] . $_CM_BASE;

require(MODX_BASE_PATH. $_CM_BASE .'codemirror.plugin.php');