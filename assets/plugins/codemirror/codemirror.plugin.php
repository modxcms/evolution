<?php
/**
 * @name        CodeMirror
 * @description JavaScript library that can be used to create a relatively pleasant editor interface
 *
 * @released    Jun 5, 2013
 * @CodeMirror  1.1
 *
 * @required    MODX 0.9.6.3+
 *              CodeMirror  3.13 : pl
 *
 * @confirmed   MODX Evolution 1.0.15
 *
 * @author      Mihanik71
 *
 * @see         https://github.com/Mihanik71/CodeMirror-MODx
 */
global $content, $which_editor;
$textarea_name = 'post';
$mode = 'htmlmixed';
$lang = 'htmlmixed';
$readOnly = isset( $readOnly ) && $readOnly === true ? 'true' : 'false';
/*
 * Default Plugin configuration
 */
$theme = (isset($theme) ? $theme : 'default');
$indentUnit = (isset($indentUnit) ? $indentUnit : 4);
$tabSize = (isset($tabSize) ? $tabSize : 4);
$lineWrapping = (isset($lineWrapping) ? $lineWrapping : false);
$matchBrackets = (isset($matchBrackets) ? $matchBrackets : false);
$activeLine = (isset($activeLine) ? $activeLine : false);
$emmet = (($emmet == 'true') ? '<script src="' . $_CM_URL . 'cm/emmet-compressed.js"></script>' : "");
$search = (($search == 'true') ? '<script src="' . $_CM_URL . 'cm/search-compressed.js"></script>' : "");
$indentWithTabs = (isset($indentWithTabs) ? $indentWithTabs : false);
$undoDepth = (isset($undoDepth) ? $undoDepth : 200);
$historyEventDelay = (isset($historyEventDelay) ? $historyEventDelay : 1250);
/*
 * This plugin is only valid in "text" mode. So check for the current Editor
 */
$prte = (isset($_POST['which_editor']) ? $_POST['which_editor'] : '');
$srte = ($modx->config['use_editor'] ? $modx->config['which_editor'] : 'none');
$xrte = $content['richtext'];
$tvMode = false;
$limitedHeight = false;
/*
 * Switch event
 */
switch ($modx->Event->name) {
    case 'OnTempFormRender'   :
        $object_name = $content['templatename'];
        $rte = ($prte ? $prte : 'none');
        break;
    case 'OnChunkFormRender'  :
        $rte = isset($which_editor) ? $which_editor : 'none';
        break;

    case 'OnRichTextEditorInit':
        if ($editor !== 'Codemirror') return;
        $textarea_name = $modx->event->params['elements'];
        $object_name = $content['pagetitle'];
        $rte = 'none';
        $tvMode = true;
        $contentType = $content['contentType'] ? $content['contentType'] : $modx->event->params['contentType'];

        /*
        * Switch contentType for doc
        */
        switch ($contentType) {
            case "text/css":
                $mode = "text/css";
                $lang = "css";
                break;
            case "text/javascript":
                $mode = "text/javascript";
                $lang = "javascript";
                break;
            case "application/json":
                $mode = "application/json";
                $lang = "javascript";
                break;
			case "application/x-httpd-php":
				$mode = "application/x-httpd-php";
				$lang = "php";
				break;
        }
        break;
    case 'OnDocFormRender'     :
        $textarea_name = 'ta';
        $object_name = $content['pagetitle'];
        $xrte = (('htmlmixed' == $mode) ? $xrte : 0);
        $rte = ($prte ? $prte : ($content['id'] ? ($xrte ? $srte : 'none') : $srte));
        $contentType = $content['contentType'];
        /*
        * Switch contentType for doc
        */
        switch ($contentType) {
            case "text/css":
                $mode = "text/css";
                $lang = "css";
                break;
            case "text/javascript":
                $mode = "text/javascript";
                $lang = "javascript";
                break;
            case "application/json":
                $mode = "application/json";
                $lang = "javascript";
                break;
        }
        break;

    case 'OnSnipFormRender'   :
    case 'OnPluginFormRender' :
    case 'OnModFormRender'    :
        $tvMode = true;
        // $limitedHeight = true; // No limited height since MODX 1.2
        $elements = array($textarea_name,'properties');
        $mode = 'application/x-httpd-php-open';
        $rte = ($prte ? $prte : 'none');
        $lang = "php";
        break;

    case 'OnManagerPageRender':
        if ((31 == $action) && (('view' == $_REQUEST['mode']) || ('edit' == $_REQUEST['mode']))) {
            $textarea_name = 'content';
            $rte = 'none';
        }
        break;

    default:
        $this->logEvent(1, 2, 'Undefined event : <b>' . $modx->Event->name . '</b> in <b>' . $this->Event->activePlugin . '</b> Plugin', 'CodeMirror Plugin : ' . $modx->Event->name);
}
$output = '';
if (('none' == $rte) && $mode && !defined('INIT_CODEMIRROR')) {
    define('INIT_CODEMIRROR', 1);
    $output = <<< HEREDOC
    <link rel="stylesheet" href="{$_CM_URL}cm/lib/codemirror.css">
    <link rel="stylesheet" href="{$_CM_URL}cm/theme/{$theme}.css">
    <script src="{$_CM_URL}cm/lib/codemirror-compressed.js"></script>
    <script src="{$_CM_URL}cm/addon-compressed.js"></script>
    <script src="{$_CM_URL}cm/mode/xml-compressed.js"></script> <!-- required by mode htmlmixed -->
    <script src="{$_CM_URL}cm/mode/javascript-compressed.js"></script> <!-- required by mode htmlmixed -->
    <script src="{$_CM_URL}cm/mode/css-compressed.js"></script>
    <script src="{$_CM_URL}cm/mode/clike-compressed.js"></script> <!-- required by mode php -->
    <script src="{$_CM_URL}cm/mode/php-compressed.js"></script>
    <script src="{$_CM_URL}cm/mode/sql-compressed.js"></script>
    <script src="{$_CM_URL}cm/mode/{$lang}-compressed.js"></script>
    {$emmet}{$search}
    
    <script type="text/javascript">
        // Add mode MODX for syntax highlighting. Dfsed on $mode
        CodeMirror.defineMode("MODx-{$mode}", function(config, parserConfig) {
            var mustacheOverlay = {
                token: function(stream, state) {
                    var ch;
                    if (stream.match("[[")) {
                        while ((ch = stream.next()) != null)
                            if (ch == "?" || (ch == "]"&& stream.next() == "]")) break;
                        return "modxSnippet";
                    }
                    if (stream.match("{{")) {
                        while ((ch = stream.next()) != null)
                            if (ch == "}" && stream.next() == "}") break;
                        stream.eat("}");
                        return "modxChunk";
                    }
                    if (stream.match("[*")) {
                        while ((ch = stream.next()) != null)
                            if (ch == "*" && stream.next() == "]") break;
                        stream.eat("]");
                        return "modxTv";
                    }
                    if (stream.match("[+")) {
                        while ((ch = stream.next()) != null)
                            if (ch == "+" && stream.next() == "]") break;
                        stream.eat("]");
                        return "modxPlaceholder";
                    }
                    if (stream.match("[!")) {
                        while ((ch = stream.next()) != null)
                            if (ch == "?" || (ch == "!"&& stream.next() == "]")) break;
                        return "modxSnippetNoCache";
                    }
                    if (stream.match("[(")) {
                        while ((ch = stream.next()) != null)
                            if (ch == ")" && stream.next() == "]") break;
                        stream.eat("]");
                        return "modxVariable";
                    }
                    if (stream.match("[~")) {
                        while ((ch = stream.next()) != null)
                            if (ch == "~" && stream.next() == "]") break;
                        stream.eat("]");
                        return "modxUrl";
                    }
                    if (stream.match("[^")) {
                        while ((ch = stream.next()) != null)
                            if (ch == "^" && stream.next() == "]") break;
                        stream.eat("]");
                        return "modxConfig";
                    }
                    if (ch = stream.match(/&([^\s=]+=+`)?/)) {
                        if(ch[1] != undefined)
                            return "modxAttribute";
                    }
                    if (stream.match("`")) {
                        return "modxAttributeValue";
                    }
                    if (stream.match("@inherit", true, true) ||
                        stream.match("@select", true, true) ||
                        stream.match("@eval", true, true) ||
                        stream.match("@directory", true, true) ||
                        stream.match("@chunk", true, true) ||
                        stream.match("@document", true, true) ||
                        stream.match("@file", true, true) ||
                        stream.match("@code", true, true)
                    ) {
                        return "modxBinding";                   
                    }
                    if (stream.match("!]")) {
                        return "modxSnippetNoCache";
                    }
                    if (stream.match("]]")) {
                        return "modxSnippet";
                    }
                    while (stream.next() != null && !stream.match("[[", false) && !stream.match("&", false) && !stream.match("{{", false) && !stream.match("[*", false) && !stream.match("[+", false) && !stream.match("[!", false) && !stream.match("[(", false) && !stream.match("[~", false) && !stream.match("[^", false) && !stream.match("`", false) && !stream.match("!]", false) && !stream.match("]]", false)) {}
                    return null;
                }
            };
            return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || "{$mode}"), mustacheOverlay);
        });
        function makeMarker(symbol) {
          var marker = document.createElement("div");
          marker.style.color = "#822";
          marker.className = "cm-marker";
          marker.innerHTML = "●";
          return marker;
        }
        //Basic settings
        var config = {
            mode: 'MODx-{$mode}',
            theme: '{$theme}',
            readOnly: {$readOnly},
            indentUnit: {$indentUnit},
            tabSize: {$tabSize},
            lineNumbers: true,
            matchBrackets: {$matchBrackets},
            lineWrapping: {$lineWrapping},
            gutters: ["CodeMirror-linenumbers", "breakpoints"],
            styleActiveLine: {$activeLine},
            indentWithTabs: {$indentWithTabs},
            undoDepth: {$undoDepth},
            historyEventDelay: {$historyEventDelay},
            extraKeys:{
                "Ctrl-Space": function(cm){
                    var n = cm.getCursor().line;
                    var info = cm.lineInfo(n);
                    foldFunc(cm, n);
                    cm.setGutterMarker(n, "breakpoints", info.gutterMarkers ? null : makeMarker("+"));
                },
                "Ctrl-S": function(cm) {
                    document.getElementById('Button1').getElementsByTagName('a')[0].onclick();
                },
                "Ctrl-E": function(cm) {
                    document.getElementById('Button1').getElementsByTagName('select')[0].options[1].selected = true;
                    document.getElementById('Button1').getElementsByTagName('a')[0].onclick();
                },
                "Ctrl-B": function(cm) {
                    document.getElementById('Button1').getElementsByTagName('select')[0].options[0].selected = true;
                    document.getElementById('Button1').getElementsByTagName('a')[0].onclick();
                },
                "Ctrl-Q": function(cm) {
                    document.getElementById('Button1').getElementsByTagName('select')[0].options[2].selected = true;
                    document.getElementById('Button1').getElementsByTagName('a')[0].onclick();
                }
            }
        };
        var myCodeMirrors = {};
    </script>
HEREDOC;
}

if (!$tvMode) {
    $elements = array($textarea_name);
}

if (('none' == $rte) && $mode && $elements !== NULL) {
    foreach ($elements as $el) {

        if($el != $textarea_name && $limitedHeight) {
            $setHeight = "myCodeMirrors['{$el}'].setSize('98%', 260);";
        } else {
            $setHeight = '';
        };
        
        $object_id = md5($evt->name . '-' . $content['id'] . '-' . $el);
        
        $output .= "
    <script>
        var readOnly = {$readOnly};
        var foldFunc = CodeMirror.newFoldFunction(CodeMirror.tagRangeFinder);
        var myTextArea = document.getElementsByName('{$el}')[0];
        config['extraKeys']['F11'] = function(cm) {
            setFullScreen(cm, !isFullScreen(cm));
            localStorage['cm_fullScreen_{$object_id}'] = isFullScreen(cm);
        };
        config['extraKeys']['Esc'] = function(cm) {
            if (isFullScreen(cm)){
                setFullScreen(cm, false);
                localStorage['cm_fullScreen_{$object_id}'] = 'false';
            }
        };
        myCodeMirrors['{$el}'] = CodeMirror.fromTextArea(myTextArea, config);
        {$setHeight}
        // reset onchange tab
        $$('.tab-row .tab').addEvents({
                click: function() {
                    myCodeMirrors['{$el}'].refresh();
                }
        });
        // get data in localStorage
        if ('true' == localStorage['cm_fullScreen_{$object_id}'] && !readOnly){
            setFullScreen(myCodeMirrors['{$el}'], !isFullScreen(myCodeMirrors['{$el}']));
            myCodeMirrors['{$el}'].hasFocus();
        }
        if (localStorage['history_{$object_id}'] !== undefined && !readOnly){
            var cmHistory = JSON.parse(localStorage['history_{$object_id}']);
            myCodeMirrors['{$el}'].doc.setHistory(cmHistory);
        }
        // add event
        myCodeMirrors['{$el}'].on('gutterClick', function(cm, n) {
            var info = cm.lineInfo(n);
            foldFunc(cm, n);
            cm.setGutterMarker(n, 'breakpoints', info.gutterMarkers ? null : makeMarker('+'));
        });
        myCodeMirrors['{$el}'].on('change', function(cm, n) {
            try {
                var cmHistory = myCodeMirrors['{$el}'].doc.getHistory();
                localStorage['history_{$object_id}'] = JSON.stringify(cmHistory);
                documentDirty=true;
            } catch(e) {
                alert('History could not be written. Error: '+e);
            }
        });

        (function() {
            var tId = setInterval(function() {
                if (document.readyState == 'complete')
                    onComplete();
            }, 11);
            function onComplete() {
                clearInterval(tId);
                myCodeMirrors['{$el}'].refresh();
            };
        })();
    </script>\n";
    };
};

$modx->Event->output($output);