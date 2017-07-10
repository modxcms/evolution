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