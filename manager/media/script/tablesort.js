/*
        TableSort revisited v0.1 by frequency-decoder.com

        Released under a creative commons Attribution-ShareAlike 2.5 license (http://creativecommons.org/licenses/by-sa/2.5/)

        You are free:

        * to copy, distribute, display, and perform the work
        * to make derivative works
        * to make commercial use of the work

        Under the following conditions:

                by Attribution.
                --------------
                You must attribute the work in the manner specified by the author or licensor.

                sa
                --
                Share Alike. If you alter, transform, or build upon this work, you may distribute the resulting work only under a license identical to this one.

        * For any reuse or distribution, you must make clear to others the license terms of this work.
        * Any of these conditions can be waived if you get permission from the copyright holder.
*/

var fdTableSort = {

        regExp_Currency:        /^[ﾣ$ﾀﾥﾤ]/,
        regExp_Number:          /^(\-)?[0-9]+(\.[0-9]*)?$/,
        pos:                    -1,
        uniqueHash:             1,
        thNode:                 null,
        tableCache:             {},
        tableId:                null,

        addEvent: function(obj, type, fn) {
                if( obj.attachEvent ) {
                        obj["e"+type+fn] = fn;
                        obj[type+fn] = function(){obj["e"+type+fn]( window.event );}
                        obj.attachEvent( "on"+type, obj[type+fn] );
                } else
                        obj.addEventListener( type, fn, false );
        },

        stopEvent: function(e) {
                e = e || window.event;

                if(e.stopPropagation) {
                        e.stopPropagation();
                        e.preventDefault();
                }
                /*@cc_on@*/
                /*@if(@_win32)
                e.cancelBubble = true;
                e.returnValue = false;
                /*@end@*/
                return false;
        },

        init: function() {
                if (!document.getElementsByTagName) return;

                var tables = document.getElementsByTagName('table');
                var sortable, headers, thtext, aclone, a, span, columnNum, noArrow;

                a               = document.createElement("a");
                a.href          = "#";
                a.onkeypress    = fdTableSort.keyWrapper;

                span            = document.createElement("span");

                for(var t = 0, tbl; tbl = tables[t]; t++) {

                        headers = fdTableSort.getTableHeaders(tbl);
                        
                        sortable  = false;
                        columnNum = tbl.className.search(/sortable-onload-([0-9]+)/) != -1 ? parseInt(tbl.className.match(/sortable-onload-([0-9]+)/)[1]) - 1 : -1;
                        showArrow = tbl.className.search(/no-arrow/) == -1;

                        // Remove any old dataObj for this table (tables created from an ajax callback require this)
                        if(tbl.id && tbl.id in fdTableSort.tableCache) delete fdTableSort.tableCache[tbl.id];

                        for (var z=0, th; th = headers[z]; z++) {
                                if(th.className && th.className.match('sortable')) {
                                
                                        // Remove previously applied classes for the ajaxers also
                                        th.className = th.className.replace(/forwardSort|reverseSort/, "");

                                        if(z == columnNum) sortable = th;
                                        thtext = fdTableSort.getInnerText(th);

                                        while(th.firstChild) th.removeChild(th.firstChild);

                                        // Create the link
                                        aclone = a.cloneNode(true);
                                        aclone.appendChild(document.createTextNode(thtext));
                                        aclone.title = "Sort on " + thtext;
                                        a.onclick = th.onclick = fdTableSort.clickWrapper;
                                        th.appendChild(aclone);

                                        // Add the span if needs be
                                        if(showArrow) th.appendChild(span.cloneNode(false));

                                        var cn = "fd-column-" + z;
                                        th.className = th.className.replace(/fd-identical|fd-not-identical/, "").replace(cn, "") + " " + cn;
                                };
                        };

                        if(sortable) {
                                fdTableSort.thNode = sortable;
                                fdTableSort.initSort();
                        };
                };
        },

        getTableHeaders: function(tbl) {
                var headers;
                var thead = tbl.getElementsByTagName('thead');

                if(thead && thead.length) {
                        thead = thead[0];
                        headers = thead.getElementsByTagName('tr');
                        headers = headers[headers.length - 1].getElementsByTagName('th');
                } else {
                        headers = tbl.getElementsByTagName('th');
                }
                return headers;
        },
        
        clickWrapper: function(e) {
                e = e || window.event;
                if(fdTableSort.thNode == null) {
                        fdTableSort.thNode = this;
                        fdTableSort.addSortActiveClass();
                        setTimeout("fdTableSort.initSort()",5);

                };
                return fdTableSort.stopEvent(e);
        },

        keyWrapper: function(e) {
                e = e || window.event;
                var kc = e.keyCode != null ? e.keyCode : e.charCode;
                if(kc == 13) {
                        var targ = this;
                        while(targ.tagName.toLowerCase() != "th") targ = targ.parentNode;

                        fdTableSort.thNode = targ;
                        fdTableSort.addSortActiveClass();
                        setTimeout("fdTableSort.initSort()",5);

                        return fdTableSort.stopEvent(e);
                };
                return true;
        },

        jsWrapper: function(tableid, colNum) {
                var table = document.getElementById(tableid);
                fdTableSort.thNode = table.getElementsByTagName('th')[colNum];
                if(!fdTableSort.thNode || fdTableSort.thNode.className.search(/fd-column/) == -1) return false;
                fdTableSort.addSortActiveClass();
                // setTimeout("fdTableSort.initSort()",5);
                fdTableSort.initSort();
        },

        addSortActiveClass: function() {
                if(fdTableSort.thNode == null) return;
                fdTableSort.addClass(fdTableSort.thNode, "sort-active");
                fdTableSort.addClass(document.getElementsByTagName('body')[0], "sort-active");
                if("sortInitiatedCallback" in window) sortInitiatedCallback();
        },

        removeSortActiveClass: function() {
                fdTableSort.removeClass(fdTableSort.thNode, "sort-active");
                fdTableSort.removeClass(document.getElementsByTagName('body')[0], "sort-active");
                if("sortCompleteCallback" in window) sortCompleteCallback();
        },

        addClass: function(e,c) {
                if(new RegExp("(^|\\s)" + c + "(\\s|$)").test(e.className)) return;
                e.className += ( e.className ? " " : "" ) + c;
        },

        removeClass: function(e,c) {
                e.className = !c ? "" : e.className.replace(new RegExp("(^|\\s*\\b[^-])"+c+"($|\\b(?=[^-]))", "g"), "");
        },

        prepareTableData: function(table) {
                // Create a table id if needs be
                if(!table.id) table.id = "fd-table-" + fdTableSort.uniqueHash++;

                var data = [];

                var start = table.getElementsByTagName('tbody');
                start = start.length ? start[0] : table;

                var trs = start.getElementsByTagName('tr');
                var ths = fdTableSort.getTableHeaders(table);

                var numberOfRows = trs.length;
                var numberOfCols = ths.length;

                var data = [];
                var identical = new Array(numberOfCols);
                var identVal  = new Array(numberOfCols);

                var tr, td, th, txt, tds, col, row;

                var rowCnt = 0;

                // Start to create the 2D matrix of data
                for(row = 0; row < numberOfRows; row++) {

                        tr              = trs[row];

                        // Have we any th tags or are we in a tfoot ?
                        if(tr.getElementsByTagName('th').length > 0 || (tr.parentNode && tr.parentNode.tagName.toLowerCase() == "tfoot")) continue;

                        data[rowCnt]    = [];
                        tds             = tr.getElementsByTagName('td');

                        for(col = 0; col < numberOfCols; col++) {
                                th = ths[col];

                                if(th.className.search(/sortable/) == -1) continue;

                                td  = tds[col];
                                txt = fdTableSort.getInnerText(td) + " ";
                                txt = txt.replace(/^\s+/,'').replace(/\s+$/,'');

                                if(th.className.search(/sortable-date/) != -1) {
                                        txt = fdTableSort.dateFormat(txt);
                                } else if(th.className.search(/sortable-numeric|sortable-currency/) != -1) {
                                        txt = parseFloat(txt.replace(/[^0-9\.\-]/g,''));
                                        if(isNaN(txt)) txt = "";
                                } else if(th.className.search(/sortable-text/) != -1) {
                                        txt = txt.toLowerCase();
                                } else if(th.className.search(/sortable-([a-zA-Z\_]+)/) != -1) {
                                        if((th.className.match(/sortable-([a-zA-Z\_]+)/)[1] + "PrepareData") in window) {
                                                txt = window[th.className.match(/sortable-([a-zA-Z\_]+)/)[1] + "PrepareData"](td, txt);
                                        };
                                } else {
                                        if(txt != "") {
                                                fdTableSort.removeClass(th, "sortable");
                                                if(fdTableSort.dateFormat(txt) != 0) {
                                                        fdTableSort.addClass(th, "sortable-date");
                                                        txt = fdTableSort.dateFormat(txt);
                                                } else if(txt.search(fdTableSort.regExp_Number) != -1 || txt.search(fdTableSort.regExp_Currency) != -1) {
                                                        fdTableSort.addClass(th, "sortable-numeric");
                                                        txt = parseFloat(txt.replace(/[^0-9\.\-]/g,''));
                                                        if(isNaN(txt)) txt = "";
                                                } else {
                                                        fdTableSort.addClass(th, "sortable-text");
                                                        txt = txt.toLowerCase();
                                                };
                                        };
                                };

                                if(rowCnt > 0 && identVal[col] != txt) {
                                        identical[col] = false;
                                };

                                identVal[col]     = txt;
                                data[rowCnt][col] = txt;
                        };

                        // Add the tr for this row
                        data[rowCnt][numberOfCols] = tr;

                        // Increment the row count
                        rowCnt++;
                }

                // Get the row and column styles
                var colStyle = table.className.search(/colstyle-([\S]+)/) != -1 ? table.className.match(/colstyle-([\S]+)/)[1] : false;
                var rowStyle = table.className.search(/rowstyle-([\S]+)/) != -1 ? table.className.match(/rowstyle-([\S]+)/)[1] : false;

                // Cache the data object for this table
                fdTableSort.tableCache[table.id] = { data:data, pos:-1, identical:identical, colStyle:colStyle, rowStyle:rowStyle, noArrow:table.className.search(/no-arrow/) != -1 };
        },

        initSort: function() {
                var span;
                var thNode      = fdTableSort.thNode;

                // Get the table
                var tableElem   = fdTableSort.thNode;
                while(tableElem.tagName.toLowerCase() != 'table' && tableElem.parentNode) {
                        tableElem = tableElem.parentNode;
                };

                // If this is the first time that this table has been sorted, create the data object
                if(!tableElem.id || !(tableElem.id in fdTableSort.tableCache)) {
                        fdTableSort.prepareTableData(tableElem);
                };

                // Cache the table id
                fdTableSort.tableId = tableElem.id;

                // Get the column position using the className added earlier
                fdTableSort.pos = thNode.className.match(/fd-column-([0-9]+)/)[1];

                // Grab the data object for this table
                var dataObj     = fdTableSort.tableCache[tableElem.id];

                // Get the position of the last column that was sorted
                var lastPos     = dataObj.pos;

                // Get the stored data object for this table
                var data        = dataObj.data;
                var colStyle    = dataObj.colStyle;
                var rowStyle    = dataObj.rowStyle;
                var len1        = data.length;
                var len2        = data[0].length - 1;
                var identical   = dataObj.identical[fdTableSort.pos] == false ? false : true;
                var noArrow     = dataObj.noArrow;

                if(lastPos != fdTableSort.pos && lastPos != -1) {
                        var th = thNode.parentNode.getElementsByTagName('th')[lastPos];

                        fdTableSort.removeClass(th, "forwardSort");
                        fdTableSort.removeClass(th, "reverseSort");
                        if(!noArrow) {
                                // Remove arrow
                                span = th.getElementsByTagName('span')[0];
                                while(span.firstChild) span.removeChild(span.firstChild);
                        };
                };

                // If the same column is being sorted then just reverse the data object contents.
                var classToAdd = "forwardSort";

                if(lastPos == fdTableSort.pos && !identical) {
                        data.reverse();
                        classToAdd = thNode.className.search(/reverseSort/) != -1 ? "forwardSort" : "reverseSort";
                } else {
                        fdTableSort.tableCache[tableElem.id].pos = fdTableSort.pos;
                        if(!identical) {
                                if(thNode.className.match(/sortable-numeric|sortable-currency|sortable-date/)) {
                                        data.sort(fdTableSort.sortNumeric);
                                } else if(thNode.className.match('sortable-text')) {
                                        data.sort(fdTableSort.sortText);
                                } else if(thNode.className.search(/sortable-([a-zA-Z\_]+)/) != -1 && thNode.className.match(/sortable-([a-zA-Z\_]+)/)[1] in window) {
                                        data.sort(window[thNode.className.match(/sortable-([a-zA-Z\_]+)/)[1]]);
                                };
                        };
                };

                fdTableSort.removeClass(thNode, "forwardSort");
                fdTableSort.removeClass(thNode, "reverseSort");
                fdTableSort.addClass(thNode, classToAdd);

                if(!noArrow) {
                        var arrow = thNode.className.search(/forwardSort/) != -1 ? " \u2193" : " \u2191";
                        span = thNode.getElementsByTagName('span')[0];
                        while(span.firstChild) span.removeChild(span.firstChild);
                        span.appendChild(document.createTextNode(arrow));
                };

                if(!rowStyle && !colStyle && identical) {
                        fdTableSort.removeSortActiveClass();
                        fdTableSort.thNode = null;
                        return;
                }

                var hook = tableElem.getElementsByTagName('tbody');
                hook = hook.length ? hook[0] : tableElem;

                var td, tr;

                for(var i = 0; i < len1; i++) {
                        tr = data[i][len2];
                        if(colStyle) {
                                if(lastPos != -1) {
                                        fdTableSort.removeClass(tr.getElementsByTagName('td')[lastPos], colStyle);
                                }
                                fdTableSort.addClass(tr.getElementsByTagName('td')[fdTableSort.pos], colStyle);
                        };
                        if(!identical) {
                                if(rowStyle) {
                                        if(i % 2) fdTableSort.addClass(tr, rowStyle);
                                        else fdTableSort.removeClass(tr, rowStyle);
                                };
                                hook.appendChild(tr);
                        };
                };
                fdTableSort.removeSortActiveClass();
                fdTableSort.thNode = null;
        },

        getInnerText: function(el) {
                if (typeof el == "string" || typeof el == "undefined") return el;
                if(el.innerText) return el.innerText;

                var txt = '', i;
                for (i = el.firstChild; i; i = i.nextSibling) {
                        if (i.nodeType == 3)            txt += i.nodeValue;
                        else if (i.nodeType == 1)       txt += fdTableSort.getInnerText(i);
                };

                return txt;
        },

        dateFormat: function(dateIn) {
                var y, m, d, res;

                // mm-dd-yyyy
                if(dateIn.match(/^(0[1-9]|1[012])([- \/.])(0[1-9]|[12][0-9]|3[01])([- \/.])(\d\d?\d\d)$/)) {
                        res = dateIn.match(/^(0[1-9]|1[012])([- \/.])(0[1-9]|[12][0-9]|3[01])([- \/.])(\d\d?\d\d)$/);
                        y = res[5];
                        m = res[1];
                        d = res[3];
                // dd-mm-yyyy
                } else if(dateIn.match(/^(0[1-9]|[12][0-9]|3[01])([- \/.])(0[1-9]|1[012])([- \/.])(\d\d?\d\d)$/)) {
                        res = dateIn.match(/^(0[1-9]|[12][0-9]|3[01])([- \/.])(0[1-9]|1[012])([- \/.])(\d\d?\d\d)$/);
                        y = res[5];
                        m = res[3];
                        d = res[1];
                // yyyy-mm-dd
                } else if(dateIn.match(/^(\d\d?\d\d)([- \/.])(0[1-9]|1[012])([- \/.])(0[1-9]|[12][0-9]|3[01])$/)) {
                        res = dateIn.match(/^(\d\d?\d\d)([- \/.])(0[1-9]|1[012])([- \/.])(0[1-9]|[12][0-9]|3[01])$/);
                        y = res[1];
                        m = res[3];
                        d = res[5];
                } else return 0;

                if(m.length == 1) m = "0" + m;
                if(d.length == 1) d = "0" + d;
                if(y.length == 1) y = '0' + y;
                if(y.length != 4) y = (parseInt(y) < 50) ? '20' + y : '19' + y;

                return y+m+d;
        },

        sortDate: function(a,b) {
                var aa = a[fdTableSort.pos];
                var bb = b[fdTableSort.pos];

                return aa - bb;
        },

        sortNumeric:function (a,b) {
                var aa = a[fdTableSort.pos];
                var bb = b[fdTableSort.pos];

                if(aa === "" && !isNaN(bb)) return -1;
                else if(bb === "" && !isNaN(aa)) return 1;
                else if(aa == bb) return 0;

                return aa - bb;
        },

        sortText:function (a,b) {
                var aa = a[fdTableSort.pos];
                var bb = b[fdTableSort.pos];

                if(aa == bb) return 0;
                if(aa < bb)  return -1;

                return 1;
        }
};

fdTableSort.addEvent(window, "load", fdTableSort.init);

