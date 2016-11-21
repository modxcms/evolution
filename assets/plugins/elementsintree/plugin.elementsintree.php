<?php
/**
 * ElementsInTree
 *
 * Get access to all Elements and Modules inside Manager sidebar
 *
 */

if(!defined('MODX_BASE_PATH')) die('What are you doing? Get out of here!');

global $_lang;

$e = &$modx->event;

if(!isset($_SESSION['elementsInTree'])) $_SESSION['elementsInTree'] = array();

// Set reloadTree = true for this events
if( in_array($e->name, array(
        'OnTempFormSave',
        'OnTVFormSave',
        'OnChunkFormSave',
        'OnSnipFormSave',
        'OnPluginFormSave',
        'OnModFormSave',

        'OnTempFormDelete',
        'OnTVFormDelete',
        'OnChunkFormDelete',
        'OnSnipFormDelete',
        'OnPluginFormDelete',
        'OnModFormDelete',

    )) || $_GET['r'] == 2) {
    $_SESSION['elementsInTree']['reloadTree'] = true;
}

// Trigger reloading tree for relevant actions
if ( $e->name == 'OnManagerMainFrameHeaderHTMLBlock' ) {
    $triggerRequiredActions = array(19,23,300,77,101,108,106,107); // when reloadTree = true
    $alwaysRefreshActions = array(16,301,78,22,102,76); // Always reload tree
    if((in_array($_GET['a'],$triggerRequiredActions) && $_SESSION['elementsInTree']['reloadTree'] == true) 
        || in_array($_GET['a'], $alwaysRefreshActions)) 
    {
        $_SESSION['elementsInTree']['reloadTree'] = false;
        $html  = "<!-- elementsInTree Start -->\n";
        $html .= "<script>";
        $html .= "jQuery(document).ready(function() {";
        $html .= "top.tree.reloadElementsInTree();";
        $html .= "})\n";
        $html .= "</script>\n";
        $html .= "<!-- elementsInTree End -->\n";
        $e->output($html);
    };
}

// Main elementsInTree-part
if ($e->name == 'OnManagerTreePrerender') {
    
    // use icons
    if ($useIcons == 'yes') {
        $tabPadding = '10px';
    }
    else {
        $tabPadding = '9px';
    }
    
    // unify frames
    if ($unifyFrames == 'yes') {
        $unifyFrames_css = '
            /* Unify Frames */
            body,
            div.treeframebody {
                background-color: #f2f2f2 !important;
            }

            div.treeframebody {
                background-color: transparent !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
            }

            #treeMenu {
                background-color: transparent !important;
                border-bottom-color: transparent !important;
            }
      ';
    }
    
    // tree buttons in tab
    if ($treeButtonsInTab == 'yes') {
       
        $treeButtonsInTab_js  = '
          jQuery("#treeMenu").detach().prependTo("#tabDoc");
          jQuery("#treeMenu").addClass("is-intab");
          parent.tree.resizeTree();
        ';
        
        $treeButtonsInTab_css = '
      /* Tree Buttons in Tab */
      #treeHolder {
        padding-top: 10px;
        padding-left: 10px;
      }
      
      #treeMenu {
        display: none;
        margin-left: 0;
        margin-bottom: 6px;
        background-color: transparent !important;
        border-bottom-width: 0;
      }

      #treeMenu.is-intab {
        display: table;
      }

      .treeButton,
      .treeButtonDisabled {
        padding: 2px 3px;
      }

      #tabDoc {
        padding-top: 11px !important;
        padding-left: 13px !important;
        padding-right: 13px !important;
      }
      
      #floater {
        width: 99%;
        top: 94px;
      }
      ';
    }
    
    // Prepare lang-strings
    $unlockTranslations = array('msg'=>$_lang["unlock_element_id_warning"],
                                'type1'=>$_lang["lock_element_type_1"], 'type2'=>$_lang["lock_element_type_2"], 'type3'=>$_lang["lock_element_type_3"], 'type4'=>$_lang["lock_element_type_4"],
                                'type5'=>$_lang["lock_element_type_5"], 'type6'=>$_lang["lock_element_type_6"], 'type7'=>$_lang["lock_element_type_7"], 'type8'=>$_lang["lock_element_type_8"]);

    // start main output
    $output = '
        <script>
            /**
             * nuContextMenu - jQuery Plugin
             * https://github.com/avxto/nuContextMenu
             */
            (function($, window, document, undefined) {
              
              "use strict";
              
              var plugin = "nuContextMenu";
              
              var defaults = {
                hideAfterClick: false,
                contextMenuClass: "nu-context-menu",
                activeClass: "active"
              };
              
              var nuContextMenu = function(container, options) {
                this.container = $(container);
                this.options = $.extend({}, defaults, options);
                this._defaults = defaults;
                this._name = plugin;
                this.init();
              };
              
              $.extend(nuContextMenu.prototype, {
                init: function() {
                  
                  if (this.options.items) {
                    this.items = $(this.options.items);
                  }
                  
                  if (this._buildContextMenu()) {
                    this._bindEvents();
                    this._menuVisible = this._menu.hasClass(this.options.activeClass);
                  }
                },
                
                _getCallback: function() {
                  return ((this.options.callback && typeof this.options.callback ===
                  "function") ? this.options.callback : function() {});
                },
                
                _buildContextMenu: function() {
                  
                  // Create context menu
                  this._menu = $("<div>")
                  .addClass(this.options.contextMenuClass)
                  .append("<ul>");
                  
                  var menuArray = this.options.menu,
                  menuList = this._menu.children("ul");
                  
                  // Create menu items
                  $.each(menuArray, function(index, element) {
                    
                    var item;
                    
                    if (element !== null && typeof element !==
                      "object") {
                      return;
                      }
                      
                      if (element.name === "void") {
                        item = $("<hr>");
                        menuList.append(item);
                        return;
                      }
                      
                      item = $("<li>")
                      .attr("data-key", element.name)
                      .text(" " + element.title);
                      
                      if (element.icon) {
                        var icon = $("<i>")
                        .addClass("fa fa-" + element.icon.toString());
                        item.prepend(icon);
                      }
                      
                      menuList.append(item);
                      
                  });
                  
                  $("body")
                  .append(this._menu);
                  
                  return true;
                  
                },
                
                _pDefault: function(event) {
                  event.preventDefault();
                  event.stopPropagation();
                  return false;
                },
                
                _contextMenu: function(event) {
                  
                  event.preventDefault();
                  
                  // Store the value of this
                  // So it can be used in the listItem click event
                  var _this = this;
                  var element = event.target;
                  
                  if (this._menuVisible || this.options.disable) {
                    return false;
                  }
                  
                  var callback = this._getCallback();
                  var listItems = this._menu.children("ul")
                  .children("li");
                  
                  listItems.off()
                  .on("click", function() {
                    
                    var key = $(this)
                    .attr("data-key");
                    callback(key, element);
                    if (_this.options.hideAfterClick) {
                      _this.closeMenu();
                    }
                  });
                  
                  this.openMenu();
                  
                  // Custom fix: Assure menu is displayed within viewport
                  var winHeight = jQuery(window).height();
                  var winWidth = jQuery(window).width();
                  var menuHeight = this._menu.height();
                  var menuWidth = this._menu.width();
                  
                  var posX = event.pageX;
                  var posY = event.pageY;
                  if(event.pageX+menuWidth > winWidth) posX = event.pageX-menuWidth;
                  if(event.pageY+menuHeight > winHeight) posY = event.pageY-menuHeight;
                  
                  this._menu.css({
                    "top": posY + "px",
                    "left": posX + "px"
                  });
                  
                  return true;
                },
                
                _onMouseDown: function(event) {
                  // Remove menu if clicked outside
                  if (!$(event.target)
                    .parents("." + this.options.contextMenuClass)
                    .length) {
                    this.closeMenu();
                    }
                },
                
                _bindEvents: function() {
                  
                  if (this.items) {
                    // Make it possible to bind to dynamically created items
                    this.container.on("contextmenu", this.options.items,
                                      $.proxy(this._contextMenu,
                                              this));
                  } else {
                    this.container.on("contextmenu", $.proxy(this._contextMenu,
                                                             this));
                  }
                  
                  // Remove menu on click
                  $(document)
                  .on("mousedown", $.proxy(this._onMouseDown, this));
                  
                },
                
                disable: function() {
                  this.options.disable = true;
                  return true;
                },
                
                destroy: function() {
                  if (this.items) {
                    this.container.off("contextmenu", this.options.items);
                  } else {
                    this.container.off("contextmenu");
                  }
                  this._menu.remove();
                  return true;
                },
                
                openMenu: function() {
                  this._menu.addClass(this.options.activeClass);
                  this._menuVisible = true;
                  return true;
                },
                
                closeMenu: function() {
                  this._menu.removeClass(this.options.activeClass);
                  this._menuVisible = false;
                  return true;
                }
                
              });
              
              $.fn[plugin] = function(options) {
                var args = Array.prototype.slice.call(arguments, 1);
                
                return this.each(function() {
                  var item = $(this),
                                 instance = item.data(plugin);
                                 if (!instance) {
                                   item.data(plugin, new nuContextMenu(this, options));
                                 } else {
                                   if (typeof options === "string" && options[0] !== "_" &&
                                     options !== "init") {
                                     instance[options].apply(instance, args);
                                     }
                                 }
                });
              };
              
            })(jQuery, window, document);
        </script>
        
        <style>
        #tabDoc {
            overflow: hidden;
        }
        
        #tabDoc::before {
            position: absolute;
            content: "";
            right: 0;
            top: 0;
            bottom: 0;
            width: 30px;
            background: -moz-linear-gradient(left, rgba(255,255,255,0) 0%, rgba(255,255,255,1) 90%, rgba(255,255,255,1) 100%);
            background: -webkit-linear-gradient(left, rgba(255,255,255,0) 0%,rgba(255,255,255,1) 90%,rgba(255,255,255,1) 100%);
            background: linear-gradient(to right, rgba(255,255,255,0) 0%,rgba(255,255,255,1) 90%,rgba(255,255,255,1) 100%);
        }

        #treePane .tab-page ul {
            margin: 0;
            margin-bottom: 5px;
            padding: 0;
        }

        #treePane .tab-page ul li {
            list-style: none;
            padding-left: 8px;
        }

        #treePane .tab-page ul li li {
            list-style: none;
            padding-left: 5px;
            line-height: 1.6;
        }

        #treePane .tab-page ul li a {
            text-decoration: none;
        }

        #treePane .tab-page ul li a:hover {
            text-decoration: underline;
        }

        #treePane .tab {
            padding-left: 7px;
            padding-right: 7px;
        }

        #treePane .tab > span > .fa {
            margin-right: 2px;
            margin-left: 2px;
        }

        #treePane .tab.selected {
            padding-bottom: 6px;
        }

        #treePane .tab-row .tab span {
            font-size: 14px;
        }

        /* Clearfix to avoid .tab-row height() = 0 */
        #treePane .tab-row:after {
            content: ".";
            clear: both;
            display: block;
            visibility: hidden;
            height: 0px;
        }

        #treePane .ext-ico {
            text-decoration:none!important;
            color:#97D19C!important;
        }

        #treePane ul > li > strong > a.catname
        {
            color: #444;
        }

        #treePane .fade {
            opacity: 0;
            -webkit-transition: opacity .15s linear;
            -o-transition: opacity .15s linear;
            transition: opacity .15s linear;
        }

        #treePane .fade.in {
            opacity: 1;
        }

        #treePane .collapse {
            display: none;
        }

        #treePane .collapse.in {
            display: block;
        }

        #treePane tr.collapse.in {
            display: table-row;
        }

        #treePane tbody.collapse.in {
            display: table-row-group;
        }

        #treePane .collapsing {
            position: relative;
            height: 0;
            overflow: hidden;
            -webkit-transition-timing-function: ease;
                     -o-transition-timing-function: ease;
                            transition-timing-function: ease;
            -webkit-transition-duration: .35s;
                     -o-transition-duration: .35s;
                            transition-duration: .35s;
            -webkit-transition-property: height;
            -o-transition-property: height;
            transition-property: height;
        }

        #treePane.no-transition .collapsing {
            -webkit-transition: none;
            -o-transition: none;
            transition: none;
        }

        #treePane .panel-title a{
            display: block;
            padding: 4px 0 4px 17px;
            color: #657587;
            font-weight: bold;
        }
        #treePane .panel-title a:hover {
            text-decoration: none;
            color:#3697CD;
        }

        #treePane .panel-title > a::before {
            content: "\f107"; /* fa-angle-down */
            font-family: "FontAwesome";
            margin-left:-17px;
        }
        #treePane .panel-title > a.collapsed::before {
            content: "\f105"; /* fa-angle-right */
            padding:0 2px;
        }
        #treePane .panel-title > a[aria-expanded="true"] {
            color: #657587;
        }

        #treePane li.eltree {
            margin-left: 5px;
            line-height: 1.4em;
        }

        #treePane li.eltree:before {
            font-family: FontAwesome;
            padding:0 5px 0 0;
            margin-right:2px;
            color: #657587;
        }

        .filterElements-form--eit {
            width: 200px;
            width: calc(100% - 70px);
        }
        
        .actionButtons--eit {
            position: absolute;
            top: 25px;
            right: 10px;
        }
        
        .actionButtons--eit li {
            margin-right: 5px;
            padding-left: 0 !important;
        }
        
        .actionButtons--eit a {
            padding: 5px 8px;
            font-size: 14px;
        }

        #tabTemp li.eltree:before {content: "\f1ea";}
        #tabCH   li.eltree:before {content: "\f009";}
        #tabSN   li.eltree:before {content: "\f121";}
        #tabTV   li.eltree:before {content: "\f022";}
        #tabPL   li.eltree:before {content: "\f1e6";}
        #tabMD   li.eltree:before {content: "\f085";}
        
        .no-events { pointer-events: none; }
        
        /* Context Menu */
        .nu-context-menu {
            background-clip: padding-box;
            background-color: #fff;
            border: 1px solid rgba(0,0,0,0.10);
            border-top:3px solid #3697CD;
            border-radius: 2px;
            box-shadow: 0 2px 2px rgba(0,0,0,0.15);
            box-sizing: border-box;
            display: block;
            height: 0;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            width: 0;
            z-index: 9999;
        }
        
        .nu-context-menu.active {
            opacity: 1;
            height: auto;
            width: auto;
        }
        
        .nu-context-menu ul {
            font-size: 14px;
            list-style: none;
            margin: 2px 0 0;
            padding: 4px 0;
            text-align: left;
        }
        
        .nu-context-menu ul li {
            clear: both;
            color: #777;
            cursor: pointer;
            font-weight: 400;
            line-height: 1.42857;
            padding: 2px 30px 2px 5px;
            margin: 0 4px;
            white-space: nowrap;
            border: 1px solid transparent;
        }
        
        .nu-context-menu ul li:hover {
            background: #E6F2FF;
            border-color: #BACCDB;
            color: #333;
        }
        
        .nu-context-menu ul li .fa {
            margin-right: 5px;
        }
        
        .nu-context-menu ul hr {
            background: #e8e8e8;
            border: 0;
            color: #e8e8e8;
            height: 1px;
            margin: 4px 0;
        }
         
        .nu-context-menu-title {
            color:#3697CD;
            font-weight:bold;
        }
        
        '.$unifyFrames_css.'
        '.$treeButtonsInTab_css.'
        
        </style>

        <div class="tab-pane no-transition" id="treePane" style="border:0;">
        <script type="text/javascript" src="media/script/tabpane.js"></script>
        <script src="media/script/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="media/script/jquery.quicksearch.js"></script>
        <script>
            function initQuicksearch(inputId, listId) {
                jQuery("#"+inputId).quicksearch("#"+listId+" ul li", {
                    selector: ".elementname",
                    "show": function () { jQuery(this).removeClass("hide"); },
                    "hide": function () { jQuery(this).addClass("hide"); },
                    "bind":"keyup",
                    "onAfter": function() {
                        jQuery("#"+listId).find(".panel-collapse").each( function() {
                            var parentLI = jQuery(this);
                            var totalLI  = jQuery(this).find("li").length;
                            var hiddenLI = jQuery(this).find("li.hide").length;
                            if (hiddenLI == totalLI) { parentLI.prev(".panel-heading").addClass("hide"); }
                            else { parentLI.prev(".panel-heading").removeClass("hide"); }
                        });
                    }
                });
                jQuery(".filterElements-form").keydown(function (e) {
                    if (e.keyCode == 13) {
                        e.preventDefault();
                    }
                });
            }
            
            var storageKey = "MODX_elementsInTreeParams";
            
            // localStorage reset :
            // localStorage.removeItem(storageKey);
            
            // Prepare remember collapsed categories function
            var storage = localStorage.getItem(storageKey);
            var elementsInTreeParams = {};
            var searchFieldCache = {};

            try {
                if(storage != null) {
                    try {
                        elementsInTreeParams = JSON.parse( storage );
                    } catch(err) {
                        console.log(err);
                        elementsInTreeParams = { "cat_collapsed": {}, "scroll_pos": {} };
                    }
                } else {
                    elementsInTreeParams = { "cat_collapsed": {}, "scroll_pos": {} };
                }
                
                // Remember collapsed categories functions
                function setRememberCollapsedCategories(obj=null) {
                    obj = obj == null ? elementsInTreeParams.cat_collapsed : obj;
                    for (var type in obj) {
                        if (!elementsInTreeParams.cat_collapsed.hasOwnProperty(type)) continue;
                        for (var category in elementsInTreeParams.cat_collapsed[type]) {
                            if (!elementsInTreeParams.cat_collapsed[type].hasOwnProperty(category)) continue;
                            state = elementsInTreeParams.cat_collapsed[type][category];
                            if(state == null) continue;
                            var collapseItem = jQuery("#collapse" + type + category);
                            var toggleItem = jQuery("#toggle" + type + category);
                            if(state == 0) {
                                // Collapsed
                                collapseItem.collapse("hide");
                                toggleItem.addClass("collapsed");
                            } else {
                                // Open
                                collapseItem.collapse("show");
                                toggleItem.removeClass("collapsed");
                            } 
                        }
                    }
                    // Avoid first category collapse-flicker on reload
                    setTimeout(function() {
                       jQuery("#treePane").removeClass("no-transition");
                    }, 50);
                }

                function setLastCollapsedCategory(type, id, state) {
                      state = state != 1 ? 1 : 0;
                      if(typeof elementsInTreeParams.cat_collapsed[type] == "undefined") elementsInTreeParams.cat_collapsed[type] = {};
                      elementsInTreeParams.cat_collapsed[type][id] = state;
                }
                function writeElementsInTreeParamsToStorage() {
                    var jsonString = JSON.stringify(elementsInTreeParams);
                    localStorage.setItem(storageKey, jsonString );
                }
                
                // Issue #20 - Keep HTTP_REFERER
                function reloadElementsInTree() {
                    // http://stackoverflow.com/a/7917528/2354531 
                    var url = "index.php?a=1&f=tree";
                    var a = document.createElement("a");
                    if (a.click)
                    {
                        // HTML5 browsers and IE support click() on <a>, early FF does not.
                        a.setAttribute("href", url);
                        a.style.display = "none";
                        document.body.appendChild(a);
                        a.click();
                    } else {
                        // Early FF can, however, use this usual method where IE cannot with secure links.
                        window.location = url;
                    }
                }
                
                /////////////////////////////////////////////////////////////
                // Prepare "remember scroll-position" functions
                var tabIds = ["tree_site_templates","tree_site_tmplvars","tree_site_htmlsnippets","tree_site_snippets","tree_site_plugins","tree_site_modules"];
                
                function getScrollXY(tab) {
                    var t = document.getElementById(tab);
                    return [t.scrollLeft, t.scrollTop];
                }
    
                function setScrollXY(tab, pos) {
                    document.getElementById(tab).scrollLeft = pos[0];
                    document.getElementById(tab).scrollTop = pos[1];
                }
                
                // Window load
                function loadPositions() {
                    for (var i = 0; i < tabIds.length; i++) {
                        var tabId = tabIds[i];
                        var tabEl = jQuery("#"+tabId);
                        tabEl.css("box-sizing","content-box").css("overflow","auto");
                        if(typeof elementsInTreeParams.scroll_pos[tabId] == "undefined") continue;
                        var tabPage = tabEl.closest(".tab-page");
                        if(tabPage.is(":visible")) {
                            setScrollXY(tabId, elementsInTreeParams.scroll_pos[tabId]);
                        } else {
                            tabPage.show();
                            setScrollXY(tabId, elementsInTreeParams.scroll_pos[tabId]);
                            tabPage.hide(); 
                        }
                    }
                }
                
                // Window unload
                function savePositions() {
                    if(typeof elementsInTreeParams.scroll_pos == "undefined") { elementsInTreeParams.scroll_pos = {}; }
                    for (var i = 0; i < tabIds.length; i++) {
                        var tabId = tabIds[i];
                        var tabEl = jQuery("#"+tabId);
                        var tabPage = tabEl.closest(".tab-page");
                        if(tabPage.is(":visible")) {
                            elementsInTreeParams.scroll_pos[tabId] = getScrollXY(tabId);
                        } else {
                            tabPage.show(); 
                            elementsInTreeParams.scroll_pos[tabId] = getScrollXY(tabId);
                            tabPage.hide(); 
                        }
                        
                    }
                    writeElementsInTreeParamsToStorage();
                }
                
                // Window load & resize
                var winHeight, tabsHeight, buttonsSize, themeMargins, tabHeight;
                
                function determineHeightValues() {
                    winHeight = jQuery(window).height();
                    tabsHeight = jQuery(".tab-row:first").height();
                    buttonsSize = jQuery(".filterElements-form:first").getSize();
                    themeMargins = 70; // All MODxRE2 top/bottom margins
                    tabHeight = winHeight - tabsHeight - buttonsSize.height - themeMargins;
                }
                
                function setTabsHeight() {
                    for (var i = 0; i < tabIds.length; i++) {
                        var tabId = tabIds[i];
                        var tabEl = jQuery("#"+tabId);
                        tabEl.css("max-height",tabHeight+"px");
                    }
                }
                
                jQuery(window).on("load", function() {
                    determineHeightValues();
                    setTabsHeight();
                    // Workaround for Firefox, which sometimes does not set scrollTop, 1ms is hopefully enough 
                    var initDelay = setTimeout(function(){
                        loadPositions();
                    }, 1);
                });
                jQuery(window).on("unload", function() {
                    savePositions();
                });
                jQuery(window).on("resize", function() {
                    determineHeightValues();
                    setTabsHeight();
                });
                
                // Get size of invisible elements - http://stackoverflow.com/a/8839261/2354531
                jQuery.fn.getSize = function() {    
                    var $wrap = jQuery("<div />").appendTo(jQuery("body"));
                    $wrap.css({
                        "position":   "absolute !important",
                        "visibility": "hidden !important",
                        "display":    "block !important"
                    });
                
                    $clone = jQuery(this).clone().appendTo($wrap);
                
                    sizes = {
                        "width": $clone.width(),
                        "height": $clone.height()
                    };
                
                    $wrap.remove();
                
                    return sizes;
                };
                /////////////////////////////////////////////////////////////
            
                jQuery(document).ready(function() {

                jQuery(".filterElements-form").keydown(function (e) {
                    if(e.keyCode == 13) e.preventDefault();
                });
              
                '.$treeButtonsInTab_js.'
                
                // Shift-Mouseclick opens/collapsed all categories
                jQuery(".accordion-toggle").click(function(e) {
                          e.preventDefault();
                          var thisItemCollapsed = jQuery(this).hasClass("collapsed");
                          if (e.shiftKey) {
                              // Shift-key pressed
                              var toggleItems = jQuery(this).closest(".panel-group").find("> .panel .accordion-toggle");
                              var collapseItems = jQuery(this).closest(".panel-group").find("> .panel > .panel-collapse");
                              if(thisItemCollapsed) {
                                toggleItems.removeClass("collapsed");
                                collapseItems.collapse("show");
                              } else {
                                toggleItems.addClass("collapsed");
                                collapseItems.collapse("hide");
                              }
                              // Save states to localStorage
                              toggleItems.each(function() {
                                state = jQuery(this).hasClass("collapsed") ? 1 : 0;
                                setLastCollapsedCategory(jQuery(this).data("cattype"), jQuery(this).data("catid"), state);
                              });
                              writeElementsInTreeParamsToStorage();
                          } else {
                            jQuery(this).toggleClass("collapsed");
                            jQuery(jQuery(this).attr("href")).collapse("toggle");
                            // Save state to localStorage
                            state = thisItemCollapsed ? 0 : 1;
                            setLastCollapsedCategory(jQuery(this).data("cattype"), jQuery(this).data("catid"), state);
                            writeElementsInTreeParamsToStorage();
                          }
                    });
                      
                    setRememberCollapsedCategories();

                });
            } catch(err) {
                alert("document.ready error: " + err);
            }
        </script>
        <script type="text/javascript">
        treePane = new WebFXTabPane(document.getElementById( "treePane" ),true);
        </script>
        <div class="tab-page" id="tabDoc" style="padding-left:0; padding-right:0;">
        <h2 class="tab">'.$tabTreeTitle.'</h2>
        <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabDoc" ) );</script>
    ';
    $e->output($output);
}

if ( $modx->hasPermission('edit_template') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('edit_chunk') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('exec_module') ) {
    if($e->name == 'OnManagerTreeRender'){
        
        if ($useIcons=='yes') {
            $tabLabel_template  = '<i class="fa fa-newspaper-o"></i>';
            $tabLabel_tv        = '<i class="fa fa-list-alt"></i>';
            $tabLabel_chunk     = '<i class="fa fa-th-large"></i>';
            $tabLabel_snippet   = '<i class="fa fa-code"></i>';
            $tabLabel_plugin    = '<i class="fa fa-plug"></i>';
            $tabLabel_module    = '<i class="fa fa-cogs"></i>';
            $tabLabel_create    = '<i class="fa fa-plus"></i>';
            $tabLabel_refresh   = '<i class="fa fa-refresh"></i>';
        }
        else {
            $tabLabel_template  = 'TPL';
            $tabLabel_tv        = 'TV';
            $tabLabel_chunk     = 'CH';
            $tabLabel_snippet   = 'SN';
            $tabLabel_plugin    = 'PL';
            $tabLabel_module    = 'MD';
            $tabLabel_create    = 'Create';
            $tabLabel_refresh   = 'Refresh';
        }
        
        $text_reload_title = 'Click here to reload elements list.';
        
        function renderLockIcon($resourceTable, $id)
        {
            global $modx, $_lang, $_style;
            
            switch($resourceTable) {
                case 'site_templates': $lockType = 1; break;
                case 'site_tmplvars': $lockType = 2; break;
                case 'site_htmlsnippets': $lockType = 3; break;
                case 'site_snippets': $lockType = 4; break;
                case 'site_plugins': $lockType = 5; break;
                case 'site_modules': $lockType = 6; break;
            }
            
            if(!isset($lockType)) return '';
            
            $lockedByUser = '';
            $rowLock = $modx->elementIsLocked($lockType, $id, true);
            if($rowLock && $modx->hasPermission('display_locks')) {
                if($rowLock['internalKey'] == $modx->getLoginUserID()) {
                    $title = $modx->parseText($_lang['lock_element_editing'], array('element_type'=>$_lang['lock_element_type_'.$lockType],'firsthit_df'=>$rowLock['firsthit_df']));
                    $lockedByUser = '<span title="'.$title.'" class="editResource" style="cursor:context-menu;"><img src="'.$_style['icons_preview_resource'].'" /></span>&nbsp;';
                } else {
                    $title = $modx->parseText($_lang['lock_element_locked_by'], array('element_type'=>$_lang['lock_element_type_'.$lockType], 'username'=>$rowLock['username'], 'firsthit_df'=>$rowLock['firsthit_df']));
                    if($modx->hasPermission('remove_locks')) {
                        $lockedByUser = '<a href="#" onclick="unlockElement('.$lockType.', '.$id.', this);return false;" title="'.$title.'" class="lockedResource"><img src="'.$_style['icons_secured'].'" /></a>';
                    } else {
                        $lockedByUser = '<span title="'.$title.'" class="lockedResource" style="cursor:context-menu;"><img src="'.$_style['icons_secured'].'" /></span>';
                    }
                }
            }
            return '<span id="lock'.$lockType.'_'.$id.'">'.$lockedByUser.'</span>';
        }
        
        $tablePre = $modx->db->config['dbase'] . '.`' . $modx->db->config['table_prefix'];
        
        // create elements list function
        function createResourceList($resourceTable,$action,$tablePre,$nameField = 'name') {
            global $modx, $_lang;
            
            $output  = '
                <form class="filterElements-form filterElements-form--eit" style="margin-top: 0;">
                  <input class="form-control" type="text" placeholder="Type here to filter list" id="tree_'.$resourceTable.'_search">
                </form>';
                
            $output .= '<div class="panel-group"><div class="panel panel-default" id="tree_'.$resourceTable.'">';
            $pluginsql = $resourceTable == 'site_plugins' ? $tablePre.$resourceTable.'`.disabled, ' : '';
            $tvsql = $resourceTable == 'site_tmplvars' ? $tablePre.$resourceTable.'`.caption, ' : '';
            //$orderby = $resourceTable == 'site_plugins' ? '6,2' : '5,1';

            if ($resourceTable == 'site_plugins' || $resourceTable == 'site_tmplvars') {
                $orderby= '6,2';
            }
            
            else{
                $orderby= '5,1';
            }

            $sql = 'SELECT '.$pluginsql.$tvsql.$tablePre.$resourceTable.'`.'.$nameField.' as name, '.$tablePre.$resourceTable.'`.id, '.$tablePre.$resourceTable.'`.description, '.$tablePre.$resourceTable.'`.locked, if(isnull('.$tablePre.'categories`.category),\''.$_lang['no_category'].'\','.$tablePre.'categories`.category) as category, '.$tablePre.'categories`.id  as catid FROM '.$tablePre.$resourceTable.'` left join '.$tablePre.'categories` on '.$tablePre.$resourceTable.'`.category = '.$tablePre.'categories`.id ORDER BY '.$orderby;
            
            $rs = $modx->db->query($sql);
            $limit = $modx->db->getRecordCount($rs);
            
            if($limit<1){
                return '';
            }
            
            $preCat = '';
            $insideUl = 0;
            
            for($i=0; $i<$limit; $i++) {
                $row = $modx->db->getRow($rs);
                $row['category'] = stripslashes($row['category']);
                if ($preCat !== $row['category']) {
                    $output .= $insideUl? '</div>': '';
                    $row['catid'] = intval($row['catid']);
                    $output .= '<div class="panel-heading"><span class="panel-title"><a class="accordion-toggle" id="toggle'.$resourceTable.$row['catid'].'" href="#collapse'.$resourceTable.$row['catid'].'" data-cattype="'.$resourceTable.'" data-catid="'.$row['catid'].'" title="Click to toggle collapse. Shift+Click to toggle all."> '.$row['category'].'</a></span></div><div class="panel-collapse in '.$resourceTable.'"  id="collapse'.$resourceTable.$row['catid'].'"><ul>';
                    $insideUl = 1;
                }
                if ($resourceTable == 'site_plugins') $class = $row['disabled'] ? ' class="disabledPlugin"' : '';
                $lockIcon = renderLockIcon($resourceTable, $row['id']);
                $output .= '<li class="eltree">'.$lockIcon.'<span'.$class.'><a href="index.php?id='.$row['id'].'&amp;a='.$action.'" title="'.strip_tags($row['description']).'" target="main" class="context-menu" data-type="'.$resourceTable.'" data-id="'.$row['id'].'"><span class="elementname">'.$row['name'].'</span><small> (' . $row['id'] . ')</small></a>
                  <a class="ext-ico" href="#" title="Open in new window" onclick="window.open(\'index.php?id='.$row['id'].'&a='.$action.'\',\'gener\',\'width=800,height=600,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')"> <small><i class="fa fa-external-link" aria-hidden="true"></i></small></a>'.($modx_textdir ? '&rlm;' : '').'</span>';
                
                $output .= $row['locked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ;
                $output .= '</li>';
                $preCat = $row['category'];
            }
            $output .= $insideUl? '</ul></div></div>': '';
            $output .= '</div>';
            $output .= '
    
        <script>
          initQuicksearch(\'tree_'.$resourceTable.'_search\', \'tree_'.$resourceTable.'\');
          jQuery(\'#tree_'.$resourceTable.'_search\').on(\'focus\', function () {
            searchFieldCache = elementsInTreeParams.cat_collapsed;
            jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').removeClass("collapsed");
            jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').addClass("no-events");
            jQuery(\'.'.$resourceTable.'\').collapse(\'show\');
          }).on(\'blur\', function () {
            setRememberCollapsedCategories(searchFieldCache);
            jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').removeClass("no-events");
          });
        </script>';
            return $output;
        }
        
        // end createResourceList function
        
        // createModulesList function
        
        function createModulesList($resourceTable,$action,$tablePre,$nameField = 'name') {
        
            global $modx, $_lang;
            
            $output  = '
                <form class="filterElements-form filterElements-form--eit" style="margin-top: 0;">
                  <input class="form-control" type="text" placeholder="Type here to filter list" id="tree_'.$resourceTable.'_search">
                </form>';
                
            $output .= '<div class="panel-group"><div class="panel panel-default" id="tree_'.$resourceTable.'">';

            if ($_SESSION['mgrRole'] != 1) {
                $rs = $modx->db->query('SELECT sm.id, sm.name, sm.description, sm.category, sm.disabled, cats.category AS catname, cats.id AS catid, mg.member
                FROM ' . $modx->getFullTableName('site_modules') . ' AS sm
                LEFT JOIN ' . $modx->getFullTableName('site_module_access') . ' AS sma ON sma.module = sm.id
                LEFT JOIN ' . $modx->getFullTableName('member_groups') . ' AS mg ON sma.usergroup = mg.user_group
                LEFT JOIN ' . $modx->getFullTableName('categories') . ' AS cats ON sm.category = cats.id
                WHERE (mg.member IS NULL OR mg.member = ' . $modx->getLoginUserID() . ') AND sm.disabled != 1 AND sm.locked != 1
                ORDER BY 5,1');
            } 
            
            else {
                $rs = $modx->db->query('SELECT sm.id, sm.name, sm.description, sm.category, sm.disabled, cats.category AS catname, cats.id AS catid
                FROM ' . $modx->getFullTableName('site_modules') . ' AS sm
                LEFT JOIN ' . $modx->getFullTableName('categories') . ' AS cats ON sm.category = cats.id
                WHERE sm.disabled != 1
                ORDER BY 5,1');
            }
            
            $limit = $modx->db->getRecordCount($rs);
            
            if($limit<1){
                return '';
            }
            
            $preCat   = '';
            $insideUl = 0;
            
            for($i=0; $i<$limit; $i++) {
                $row = $modx->db->getRow($rs);
                if($row['catid'] > 0) {
                    $row['catid'] = stripslashes($row['catid']);
                } else {
                    $row['catname'] = $_lang["no_category"];
                }
                if ($preCat !== $row['category']) {
                    $output .= $insideUl? '</div>': '';
                    $row['catid'] = intval($row['catid']);
                    $output .= '<div class="panel-heading"><span class="panel-title"><a class="accordion-toggle" id="toggle'.$resourceTable.$row['catid'].'" href="#collapse'.$resourceTable.$row['catid'].'" data-cattype="'.$resourceTable.'" data-catid="'.$row['catid'].'" title="Click to toggle collapse. Shift+Click to toggle all."> '.$row['catname'].'</a></span></div><div class="panel-collapse in '.$resourceTable.'"  id="collapse'.$resourceTable.$row['category'].'"><ul>';
                    $insideUl = 1;
                }
                $output .= '<li class="eltree"><span><a href="index.php?id='.$row['id'].'&amp;a='.$action.'" title="'.strip_tags($row['description']).'" target="main" class="context-menu" data-type="'.$resourceTable.'" data-id="'.$row['id'].'"><span class="elementname">'.$row['name'].'</span><small> (' . $row['id'] . ')</small></a>
                  <a class="ext-ico" href="#" title="Open in new window" onclick="window.open(\'index.php?id='.$row['id'].'&a='.$action.'\',\'gener\',\'width=800,height=600,top=\'+((screen.height-600)/2)+\',left=\'+((screen.width-800)/2)+\',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no\')"> <small><i class="fa fa-external-link" aria-hidden="true"></i></small></a>'.($modx_textdir ? '&rlm;' : '').'</span>';
                $output .= $row['locked'] ? ' <em>('.$_lang['locked'].')</em>' : "" ;
                $output .= '</li>';
                $preCat  = $row['category'];
            }
            $output .= $insideUl? '</ul></div></div>': '';
            $output .= '</div>';
            $output .= '
    
        <script>
          initQuicksearch(\'tree_'.$resourceTable.'_search\', \'tree_'.$resourceTable.'\');
          jQuery(\'#tree_'.$resourceTable.'_search\').on(\'focus\', function () {
            searchFieldCache = elementsInTreeParams.cat_collapsed;
            jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').addClass("no-events");
            jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').removeClass("collapsed");
            jQuery(\'.'.$resourceTable.'\').collapse(\'show\');
          }).on(\'blur\', function () {
            jQuery(\'#tree_'.$resourceTable.' .accordion-toggle\').removeClass("no-events");
            setRememberCollapsedCategories(searchFieldCache);
          });
        </script>';
            return $output;
        }
        
        // end createModulesList function
        
        $temp    = createResourceList('site_templates',16,$tablePre,'templatename');
        $tv      = createResourceList('site_tmplvars',301,$tablePre);
        $chunk   = createResourceList('site_htmlsnippets',78,$tablePre);
        $snippet = createResourceList('site_snippets',22,$tablePre);
        $plugin  = createResourceList('site_plugins',102,$tablePre);
        $module  = createModulesList('site_modules',112,$tablePre);

        if ( $modx->hasPermission('edit_template') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('edit_chunk') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('exec_module') ) {
            $output = '</div>';
        }

        if ($modx->hasPermission('edit_template')) {
            $output .= '
              <div class="tab-page" id="tabTemp" style="padding-left:0; padding-right:0;">
              <h2 class="tab" title="Templates">'.$tabLabel_template.'</h2>
              <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabTemp" ) );</script>
              '.$temp.'
              <ul class="actionButtons actionButtons--eit">
              <li><a href="index.php?a=19" target="main" title="'.$_lang['new_template'].'">'.$tabLabel_create.'</a></li>
              <li><a href="javascript:reloadElementsInTree();" title="'.$text_reload_title.'">'.$tabLabel_refresh.'</a></li>
              </ul>
              </div>
              <div class="tab-page" id="tabTV" style="padding-left:0; padding-right:0;">
              <h2 class="tab" title="Template Variables">'.$tabLabel_tv.'</h2>
              <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabTV" ) );</script>
              '.$tv.'
              <ul class="actionButtons actionButtons--eit">
              <li><a href="index.php?a=300" target="main" title="'.$_lang['new_tmplvars'].'">'.$tabLabel_create.'</a></li>
              <li><a href="javascript:reloadElementsInTree();" title="'.$text_reload_title.'">'.$tabLabel_refresh.'</a></li>
              </ul>
              </div>
            ';
        }

        if ($modx->hasPermission('edit_chunk')) {
            $output .= '
              <div class="tab-page" id="tabCH" style="padding-left:0; padding-right:0;">
              <h2 class="tab" title="Chunks">'.$tabLabel_chunk.'</h2>
              <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabCH" ) );</script>
              '.$chunk.'
              <ul class="actionButtons actionButtons--eit">
              <li><a href="index.php?a=77" target="main" title="'.$_lang['new_htmlsnippet'].'">'.$tabLabel_create.'</a></li>
              <li><a href="javascript:reloadElementsInTree();" title="'.$text_reload_title.'">'.$tabLabel_refresh.'</a></li>
              </ul>
              </div>
            ';
        }

        if ($modx->hasPermission('edit_snippet')) {
            $output .= '
              <div class="tab-page" id="tabSN" style="padding-left:0; padding-right:0;">
              <h2 class="tab" title="Snippets">'.$tabLabel_snippet.'</h2>
              <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabSN" ) );</script>
              '.$snippet.'
              <ul class="actionButtons actionButtons--eit">
              <li><a href="index.php?a=23" target="main" title="'.$_lang['new_snippet'].'">'.$tabLabel_create.'</a></li>
              <li><a href="javascript:reloadElementsInTree();" title="'.$text_reload_title.'">'.$tabLabel_refresh.'</a></li>
              </ul>
              </div>
            ';
        }

        if ($modx->hasPermission('edit_plugin')) {
            $output .= '
              <div class="tab-page" id="tabPL" style="padding-left:0; padding-right:0;">
              <h2 class="tab" title="Plugins">'.$tabLabel_plugin.'</h2>
              <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabPL" ) );</script>
              '.$plugin.'
              <ul class="actionButtons actionButtons--eit">
              <li><a href="index.php?a=101" target="main" title="'.$_lang['new_plugin'].'">'.$tabLabel_create.'</a></li>
              <li><a href="javascript:reloadElementsInTree();" title="'.$text_reload_title.'">'.$tabLabel_refresh.'</a></li>
              </ul>
              </div>
            ';
        }
        
        if ($modx->hasPermission('exec_module')) {
            
            $new_module_button = '';
      
            if ($modx->hasPermission('new_module')) {
                $new_module_button = '<li><a href="index.php?a=107" target="main" title="'.$_lang['new_module'].'">'.$tabLabel_create.'</a></li>';
            }
            
            $output .= '
              <div class="tab-page" id="tabMD" style="padding-left:0; padding-right:0;">
              <h2 class="tab" title="Modules">'.$tabLabel_module.'</h2>
              <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabMD" ) );</script>
              '.$module.'
              <ul class="actionButtons actionButtons--eit">
              '.$new_module_button.'
              <li><a href="javascript:reloadElementsInTree();" title="'.$text_reload_title.'">'.$tabLabel_refresh.'</a></li>
              </ul>
              </div>
          ';
        }

        if ($modx->hasPermission('edit_template') || $modx->hasPermission('edit_snippet') || $modx->hasPermission('edit_chunk') || $modx->hasPermission('edit_plugin') || $modx->hasPermission('exec_module') ) {
            
            $output .= '
            <script>
                jQuery(function() {
                    var context = jQuery("#treePane").nuContextMenu({
                      hideAfterClick: true,
                      items: ".context-menu",
                      callback: function(action, element) {
                        var el = jQuery(element);
                        var name = el.html();
                        var cm = el.closest(".context-menu");
                        eitAction(name, action, cm.data("type"), cm.data("id"));
                      },
                      menu: [
                        { name: "create",    title: "'.addslashes($_lang["create_new"]).'", icon: "plus", },
                        { name: "edit",      title: "'.addslashes($_lang["edit"]).'",       icon: "edit", },
                        { name: "duplicate", title: "'.addslashes($_lang["duplicate"]).'",  icon: "clone", },
                        { name: "void" },
                        { name: "delete",    title: "'.addslashes($_lang["delete"]).'",     icon: "trash", },
                      ]
                    });
                });
                
                function eitAction(name, action, type, id) {
                    var actionIds, deleteMsg;
                    
                    switch(type) {
                        case "site_templates" :
                            actionsIds = { "create":19, "edit":16, "duplicate":96, "delete":21 }; 
                            deleteMsg = "'.addslashes($_lang["confirm_delete_template"]).'";
                            break;
                        case "site_tmplvars" :
                            actionsIds = { "create":300, "edit":301, "duplicate":304, "delete":303 };
                            deleteMsg = "'.addslashes($_lang["confirm_delete_tmplvars"]).'";
                            break;
                        case "site_htmlsnippets" :
                            actionsIds = { "create":77, "edit":78, "duplicate":97, "delete":80 };
                            deleteMsg = "'.addslashes($_lang["confirm_delete_htmlsnippet"]).'";
                            break;
                        case "site_snippets" :
                            actionsIds = { "create":23, "edit":22, "duplicate":98, "delete":25 };
                            deleteMsg = "'.addslashes($_lang["confirm_delete_snippet"]).'";
                            break;
                        case "site_plugins" :
                            actionsIds = { "create":101, "edit":102, "duplicate":105, "delete":104 };
                            deleteMsg = "'.addslashes($_lang["confirm_delete_plugin"]).'";
                            break;
                        case "site_modules" :
                            actionsIds = { "create":107, "edit":108, "duplicate":111, "delete":110 };
                            deleteMsg = "'.addslashes($_lang["confirm_delete_module"]).'";
                            break;
                        default :
                            alert("Unknown type");
                            return;
                    }
                    
                    // Actions that need confirmation
                    var confirmMsg = false;
                    switch(action) {
                        case "create" : id = false; break;
                        case "edit" : break;
                        case "duplicate" : confirmMsg = "'.addslashes($_lang["confirm_duplicate_record"]).'"; break;
                        case "delete" : confirmMsg = deleteMsg; break;
                    }
                    
                    if(confirmMsg) {
                        confirmMsg += " \n \n " + name + " ("+id+")";
                        var r = confirm(confirmMsg);
                        if (r != true) return;
                    }
                    
                    top.main.document.location.href="index.php?a="+actionsIds[action]+ (id ? "&id="+id : "");
                }
              </script>
            ';
            
            $output .= '</div>';
            $e->output($output);
        }

    }
}