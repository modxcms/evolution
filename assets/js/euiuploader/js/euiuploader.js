!function(){var e=Handlebars.template,n=Handlebars.templates=Handlebars.templates||{};n.uploadForm=e({1:function(e,n,s,a){var t=this.lambda,l=this.escapeExpression,i=n.helperMissing;return'                <tr id="sgFilesListRow'+l(t(a&&a.index,e))+'">\n                    <td class="sgrow1">'+l(t(null!=e?e.name:e,e))+'</td>\n                    <td class="sgrow2">'+l((n.bytesToSize||e&&e.bytesToSize||i).call(e,null!=e?e.size:e,{name:"bytesToSize",hash:{},data:a}))+'</td>\n                    <td class="sgrow3 progress"></td>\n                </tr>\n'},compiler:[6,">= 2.0.0-beta.1"],main:function(e,n,s,a){var t,l=this.lambda,i=this.escapeExpression,r='<div id="euiUploadState">\n    <div id="euiUploadProgress"></div>\n    <table>\n        <thead>\n        <tr>\n            <th class="sgrow1">'+i(l(null!=(t=null!=e?e.euiuLang:e)?t.file:t,e))+'</th>\n            <th class="sgrow2">'+i(l(null!=(t=null!=e?e.euiuLang:e)?t.size:t,e))+'</th>\n            <th class="sgrow3">'+i(l(null!=(t=null!=e?e.euiuLang:e)?t.progress:t,e))+'</th>\n        </tr>\n        </thead>\n    </table>\n    <div id="euiFilesList">\n        <table>\n            <tbody>\n';return t=n.each.call(e,null!=e?e.files:e,{name:"each",hash:{},fn:this.program(1,a),inverse:this.noop,data:a}),null!=t&&(r+=t),r+"            </tbody>\n        </table>\n    </div>\n</div>\n"},useData:!0})}();
(function(window, $, api){
    var defaults = {
        workspace:'',
        dndArea:'',
        uploadBtn:'',
        filterFn:function(file,info){
            return false;
        },
        completeCallback:function(){

        }
    };
    function EUIUploader (options) {
        this._options = $.extend({},defaults,options);
        this._errorCount = 0;
        this._currentFile = 0;
        this._xhr = 0;
        return this.init();
    }
    EUIUploader.prototype = {
        init: function() {
            var workspace = $(this._options.workspace);
            var dndArea = $(this._options.dndArea, workspace);
            var self = this;
            workspace.append($('<input name="eui_files" style="display:none;" type="file" multiple />'));
            $(this._options.uploadBtn, workspace).linkbutton({
                iconCls: 'fa fa-folder-o fa-lg',
                text:_euiuLang['upload'],
                onClick: function() {
                    self.clear();
                    $('input[name="eui_files"]',workspace).trigger('click');
                }
            });
            api.event.on($('input[name="eui_files"]',workspace)[0], 'change', function (evt){
                var files = api.getFiles(evt); // Retrieve file list
                self.prepare(files);
            });
            FileAPI.event.dnd(dndArea[0], function (over){
                if (over) {
                    dndArea.addClass('dnd_hover');
                } else {
                    dndArea.removeClass('dnd_hover');
                }
            }, function(files){
                self.prepare(files);
            });
            return this;
        },
        prepare:function(files){
            var self = this;
            if (typeof self._options.filterFn === 'function') {
                api.filterFiles(files, self._options.filterFn, function (files, rejected) {
                    self.upload(files);
                });
            } else {
                self.clear();
            }
        },
        upload:function(files) {
            var self = this;
            if( files.length ){
                var options = {
                    files: { file: files },
                    beforeupload: function(xhr/**Object*/, options/**Object*/) {
                        var total = xhr.files.length;
                        var context = {
                            files: xhr.files,
                            euiuLang: _euiuLang
                        };
                        var uploadStateForm = $(Handlebars.templates.uploadForm(context));
                        uploadStateForm.dialog({
                            width:450,
                            modal:true,
                            title:_euiuLang['files_upload'],
                            doSize:true,
                            collapsible:false,
                            minimizable:false,
                            maximizable:false,
                            resizable:false,
                            buttons:[{
                                id:'euiCancelUpload',
                                iconCls:'btn-red fa fa-ban fa-lg',
                                text:_euiuLang['cancel'],
                                handler:function(){
                                    uploadStateForm.window('close',true);
                                }
                            }],
                            onOpen: function() {
                                $('body').css('overflow','hidden');
                                $('#euiUploadProgress').progressbar();
                            },
                            onClose: function() {
                                if (self._xhr) self._xhr.abort();
                                uploadStateForm.window('destroy',true);
                                $('.window-shadow,.window-mask').remove();
                                $('body').css('overflow','auto');
                            }
                        });
                    },
                    upload: function(xhr/**Object*/, options/**Object*/) {
                        //Начало загрузки
                    },
                    fileupload: function (file/**Object*/, xhr/**Object*/, options/**Object*/){
                        //Начало загрузки файла
                    },
                    fileprogress: function(evt/**Object*/, file/**Object*/, xhr/**Object*/, options/**Object*/) {
                        var fileEl = $('.progress','#sgFilesListRow'+self._currentFile);
                        var part = Math.floor(evt.loaded / evt.total * 100);
                        fileEl.text(part+'%');
                    },
                    progress: function (evt/**Object*/, file/**Object*/, xhr/**Object*/, options/**Object*/){
                        var part = Math.floor(evt.loaded / evt.total * 100);
                        $('#euiUploadProgress').progressbar('setValue',part);
                    },
                    filecomplete: function(err/**String*/, xhr/**Object*/, file/**Object/, options/**Object*/) {
                        var fileEl = $('.progress','#sgFilesListRow'+self._currentFile);
                        var error = false;
                        if(err === false) {
                            var response;
                            try {
                                response = $.parseJSON(xhr.response);
                            } catch (error) {
                                response = {
                                    success:false,
                                    message:'parse_error'
                                }
                            }
                            if (!response.success) {
                                error = _euiuLang[response.message];
                            }
                        } else {
                            error = _euiuLang['server_error'] + ' ' + err;
                        }
                        if (error !== false) {
                            fileEl.html('<span class="fa fa-warning error" title="'+error+'"></span>')
                            self._errorCount ++;
                        } else {
                            fileEl.html('<span class="fa fa-check complete"></span>')
                        }
                        self._currentFile++;
                    },
                    complete: function (err, xhr){
                        $('#euiCancelUpload').linkbutton({
                            iconCls:'btn-red fa fa-ban fa-lg',
                            text:_euiuLang['close']
                        });
                        if (!self._errorCount) $('#euiCancelUpload').click();
                        self.clear();
                        if (typeof self._options.completeCallback === 'function') {
                            self._options.completeCallback();
                        }
                    }
                };
                this._xhr = api.upload($.extend({},this._options,options));
            }
        },
        clear:function(){
            var workspace = $(this._options.workspace);
            var upload = $('input[name="eui_files"]',workspace);
            upload.wrap('<form>').closest('form').get(0).reset();
            upload.unwrap();
            this._xhr = null;
            this._currentFile = this._errorCount = 0;
        }
    };
    window.EUIUploader = EUIUploader;
})(window, jQuery, FileAPI);

