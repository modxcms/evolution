/*
 * jQuery Taconite plugin - A port of the Taconite framework by Ryan Asleson and
 *     Nathaniel T. Schutta: http://taconite.sourceforge.net/
 *
 * Examples and documentation at: http://malsup.com/jquery/taconite/
 * Copyright (c) 2007 M. Alsup
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * Thanks to Kenton Simpson for contributing some good ideas!
 *
 * $Id$
 * @version: 2.1.4
 * @requires jQuery v1.0.4 or later
 */

(function($) {

$.expr[':'].taconiteTag = 'a.taconiteTag';

// add 'replace' and 'replaceContent' plugins (conditionally)
if (typeof $.fn.replace == 'undefined')
    $.fn.replace = function(a) { return this.after(a).remove(); };
if (typeof $.fn.replaceContent == 'undefined')
    $.fn.replaceContent = function(a) { return this.empty().append(a); };

/**
 *  Processes a Taconite XML command document.
 *  @name taconite
 *  @param Document|String command document
 */
$.taconite = $.xmlExec = function(xml) { 
    var status = true, ex;
    try {
        $.event.trigger('taconite.begin.notify', [xml])
        status = $.taconite.impl.process(xml); 
    } catch(e) {
        status = ex = e;
    }
    $.event.trigger('taconite.complete.notify', [xml, !!status, status === true ? null : status]);
    if (ex) throw ex;
};

$.taconite.version = [2,1,3]; // major,minor,point revision nums
$.taconite.debug = 0;    // set to true to enable debug logging to Firebug
$.taconite.lastTime = 0; // processing time for most recent document
$.taconite._httpData = $.httpData; // original jQuery httpData function

// auto-detection method (replaces jQuery's httpData method when auto-detection is enabled)
$.httpData = $.taconite.detect = function(xhr, type) {
    var ct = xhr.getResponseHeader('content-type');
    if ($.taconite.debug) {
        $.taconite.log('[AJAX response] content-type: ', ct, ';  status: ', xhr.status, ' ', xhr.statusText, ';  has responseXML: ', xhr.responseXML != null);
        $.taconite.log('type: ' + type);
        $.taconite.log('responseXML: ' + xhr.responseXML);
    }
    var data = $.taconite._httpData(xhr, type); // call original method
    if (data && data.documentElement) {
        var root = data.documentElement.tagName;
        $.taconite.log('XML document root: ', root);
        if (root == 'taconite') {
            $.taconite.log('taconite command document detected');
            $.taconite(data);
        }
    }
    else { 
        $.taconite.log('jQuery core httpData returned: ' + data);
        $.taconite.log('httpData: response is not XML (or not "valid" XML)');
    }
    return data;
};

// allow auto-detection to be enabled/disabled on-demand
$.taconite.enableAutoDetection = function(b) {
    // reset jQuery's httpData method
    $.httpData = b ? $.taconite.detect : $.taconite._httpData;
};

$.taconite.log = function() {
    if (!$.taconite.debug || !window.console || !window.console.log) return;
    if (!$.taconite.log.count++)
        $.taconite.log('Plugin Version: ' + $.taconite.version.join('.'));
    window.console.log('[taconite] ' + [].join.call(arguments,''));
};
$.taconite.log.count = 0;

$.taconite.impl = {
    trimHash: {wrap:1},
    // convert string to xml document
    convert: function(s) {
        var doc;
        $.taconite.log('attempting string to document conversion');
        try {
            if (window.ActiveXObject) {
                doc = new ActiveXObject('Microsoft.XMLDOM');
                doc.async = 'false';
                doc.loadXML(s);
            }
            else {
                var parser = new DOMParser();
                doc = parser.parseFromString(s, 'text/xml');
            }
        }
        catch(e) {
            if (window.console && window.console.error)
                window.console.error('[taconite] ERROR parsing XML string for conversion: ' + e);
            throw e;
        }
        var ok = doc && doc.documentElement && doc.documentElement.tagName != 'parsererror';
        $.taconite.log('conversion ', ok ? 'successful!' : 'FAILED');
        return doc;
    },
    process: function(xml) {
        if (typeof xml == 'string')
            xml = this.convert(xml);
        if (!xml || !xml.documentElement) {
            $.taconite.log('$.taconite invoked without valid document; nothing to process');
            return false;
        }
        try {
            var t = new Date().getTime();
            // process the document
            $.taconite.impl.process1(xml.documentElement.childNodes);
            $.taconite.lastTime = (new Date().getTime()) - t;
            $.taconite.log('time to process response: ' + $.taconite.lastTime + 'ms');
        } catch(e) {
            if (window.console && window.console.error)
                window.console.error('[taconite] ERROR processing document: ' + e);
            throw e;
        }
        return true;
    },
    process1: function(commands) {
        var doPostProcess = 0;
        for(var i=0; i < commands.length; i++) {
            if (commands[i].nodeType != 1)
                continue; // commands are elements
            var cmdNode = commands[i], cmd = cmdNode.tagName;
            if (cmd == 'eval') {
                var js = (cmdNode.firstChild ? cmdNode.firstChild.nodeValue : null);
                $.taconite.log('invoking "eval" command: ', js);
                if (js) $.globalEval(js);
                continue;
            }
            var q = cmdNode.getAttribute('select');
            var jq = $(q);
            if (!jq[0]) {
                $.taconite.log('No matching targets for selector: ', q);
                continue;
            }

            var a = [];
            if (cmdNode.childNodes.length > 0) {
                doPostProcess = 1;
                for (var j=0,els=[]; j < cmdNode.childNodes.length; j++)
                    els[j] = this.createNode(cmdNode.childNodes[j]);
                a.push(this.trimHash[cmd] ? this.cleanse(els) : els);
            }
            else {
                // remain backward compat with pre 2.0.9 versions
                var n = cmdNode.getAttribute('name');
                var v = cmdNode.getAttribute('value');
                if (n !== null) a.push(n);
                if (v !== null) a.push(v);

                // @since: 2.0.9: support arg1, arg2, arg3...
                for (var j=1; true; j++) {
                    v = cmdNode.getAttribute('arg'+j);
                    if (v === null)
                        break;
                    a.push(v);
                }
            }

            if ($.taconite.debug) {
                var arg = els ? '...' : a.join(',');
                $.taconite.log("invoking command: $('", q, "').", cmd, '('+ arg +')');
            }
            jq[cmd].apply(jq,a);
        }
        // apply dynamic fixes
        if (doPostProcess) this.postProcess();
    },
    postProcess: function() {
        if (!$.browser.opera && !$.browser.msie) return; 
        // post processing fixes go here; currently there is only one:
        // fix1: opera and IE6 don't maintain selected options in all cases (thanks to Karel FuÄÃ­k for this!)
        $('select:taconiteTag').each(function() {
            $('option:taconiteTag', this).each(function() {
                this.setAttribute('selected','selected');
                delete this.taconiteTag;
            });
            delete this.taconiteTag;
        });
    },
    cleanse: function(els) {
        for (var i=0, a=[]; i < els.length; i++)
            if (els[i].nodeType == 1) a.push(els[i]);
        return a;
    },
    createNode: function (node) {
        var type = node.nodeType;
        if (type == 1) return this.createElement(node);
        if (type == 3) return this.fixTextNode(node.nodeValue);
        if (type == 4) return document.createTextNode(node.nodeValue);
        return null;
    },
    fixTextNode: function(s) {
        if ($.browser.msie) s = s.replace(/\n/g, '\r');
        return document.createTextNode(s);
    },
    createElement: function (node) {
        var e, tag = node.tagName.toLowerCase();
        if ($.browser.msie && (tag == 'input' || tag == 'button')) {
            var type = node.getAttribute('type');
            if (type == 'radio' || type == 'checkbox')
                return document.createElement('<input ' + this.copyAttrs(null, node, true) + '>');
            else if (tag == 'button')
                e = document.createElement('<button ' + this.copyAttrs(null, node, true) + '>');
        }
        if (!e) {
            e = document.createElement(tag);
            this.copyAttrs(e, node);
        }

        // IE fix; script tag not allowed to have children
        if($.browser.msie && !e.canHaveChildren) {
            if(node.childNodes.length > 0)
                e.text = node.text;
        }
        else {
            for(var i=0, max=node.childNodes.length; i < max; i++) {
                var child = this.createNode (node.childNodes[i]);
                if(child) e.appendChild(child);
            }
        }
        if ($.browser.msie || $.browser.opera) {
            if (tag == 'select' || (tag == 'option' && node.getAttribute('selected')))
                e.taconiteTag = 1;
        }
        return e;
    },
    copyAttrs: function (dest, src, inline) {
        for (var i=0, attr=''; i < src.attributes.length; i++) {
            var a = src.attributes[i], n = $.trim(a.name), v = $.trim(a.value);
            if (inline) attr += (n + '="' + v + '" ');
            else if (n == 'style') { // IE workaround
                dest.style.cssText = v;
                dest.setAttribute(n, v);
            }
            else $.attr(dest, n, v);
        }
        return attr;
    }
};

})(jQuery);
