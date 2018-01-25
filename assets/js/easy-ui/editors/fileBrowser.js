(function($) {
    $.extend($.fn.datagrid.defaults.editors, {
        fileBrowser: {
            init: function (container, options) {
                var input = $('<input type="text" style="width:'+(container.width()-25)+'px;">').appendTo(container);
                var button = $('<a href="javascript:void(0)">' + (options.hasOwnProperty('icon') ? '<img style="' + options.css + '" src="'+ options.icon +'">' : '<i class="'+ options.cls +'"></i>')+'</a>').appendTo(container);
                button.click({
                    target: this,
                    field: input,
                    browserUrl: options.browserUrl,
                    opener: options.opener
                }, this.browse);
                return input;
            },
            destroy: function (target) {
                $(target).remove();
            },
            getValue: function (target) {
                return $(target).val();
            },
            setValue: function (target, value) {
                $(target).val(value);
            },
            resize: function (target, width) {
                $(target).width(width-30);
            },
            browse: function (e) {
                var width = screen.width * 0.5;
                var height = screen.height * 0.5;
                var iLeft = (screen.width - width) / 2;
                var iTop = (screen.height - height) / 2;
                var sOptions = 'toolbar=no,status=no,resizable=yes,dependent=yes';
                var url = e.data.browserUrl + '&opener=' + e.data.opener;
                sOptions += ',width=' + width;
                sOptions += ',height=' + height;
                sOptions += ',left=' + iLeft;
                sOptions += ',top=' + iTop;
                window.KCFinder = {};
                window.KCFinder = {
                    callBack: function (url) {
                        window.KCFinder = null;
                        e.data.target.setValue(e.data.field, url);
                    }
                };
                var oWindow = window.open(url, 'FileBrowser', sOptions);
            }
        }
    });
})(jQuery);
