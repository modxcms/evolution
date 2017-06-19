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

.tab-page { margin-bottom:0; }

[+unifyFrames_css+]
[+treeButtonsInTab_css+]

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
            themeMargins = 60; // All MODxRE2 top/bottom margins
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
      
        [+treeButtonsInTab_js+]
        
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
<h2 class="tab">[+tabTreeTitle+]</h2>
<script type="text/javascript">treePane.addTabPage( document.getElementById( "tabDoc" ) );</script>
