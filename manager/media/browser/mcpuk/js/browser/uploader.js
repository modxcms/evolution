/** This file is part of KCFinder project
 *
 *      @desc FileAPI uploader
 *   @package KCFinder
 *   @version 2.54
 *    @author Pavel Tzonkov <sunhater@sunhater.com>
 * @copyright 2010-2014 KCFinder Project
 *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
 *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
 *      @link http://kcfinder.sunhater.com
 */

browser.initUploader = function() {
    var btn = $('#toolbar a[href="kcact:upload"]');
    if (!this.access.files.upload) {
        btn.css('display', 'none');
        return;
    }
    $('#toolbar').prepend('<input type="file" name="upload" style="display:none;" multiple="multiple" />');
    var upload = $('input[name="upload"]', '#toolbar');
    btn.click(function(e){
        e.preventDefault();
        browser.clearUpload();
        upload.trigger('click');
    });
    FileAPI.event.on(upload.get(0), 'change', function (evt){
        var files = FileAPI.getFiles(evt); // Retrieve file list
        browser.prepareFiles(files);
    });
    FileAPI.event.dnd($('#right').get(0), function (over){
        if (over) {
            $('#files').addClass('drag');
        } else {
            $('#files').removeClass('drag');
        }
    }, function(files){
        browser.prepareFiles(files);
    });
};
browser.clearUpload = function() {
    var upload = $('input[name="upload"]', '#toolbar');
    upload.wrap('<form>').closest('form').get(0).reset();
    upload.unwrap();
};
browser.prepareFiles = function(files) {
    FileAPI.filterFiles(
        files,
        function(file) {
            var ext = file.name.split('.').pop().toLowerCase();
            var result = browser.allowedExts.test(ext) && !browser.deniedExts.test(ext);
            if (!result) {
                file.message = browser.label("Denied file extension.");
            }
            if (result) {
                result = browser.maxFileSize > file.size;
                if (!result) {
                    file.message = browser.label("The uploaded file exceeds {size} bytes.", {size: browser.maxFileSize});
                }
            }
            return result;
        }, function (files, rejected) {
            if (rejected.length > 0) {
                var messages = [];
                $.each(rejected, function(i, file) {
                    messages.push(file.name + ': ' +file.message);
                });
                browser.alert(messages.join('<br>'), true, function(){browser.uploadFiles(files)});
            } else {
                browser.uploadFiles(files);
            }
        }
    );
};
browser.uploadFiles = function(files) {
    if (!this.dirWritable) {
        browser.alert(this.label("Cannot write to upload folder."));
        return;
    }
    var uploadInProgress = false,
        filesCount = 0,
        errors = [],
        uploaded = 0;
    if( files.length ){
        filesCount = files.length;
        var options = {
            url: browser.baseGetData('upload'),
            files: { file: files },
            data: {
                dir: browser.dir
            },
            imageAutoOrientation: false,
            prepare: function (file/**Object*/, options/**Object*/){
                if (file.type !== 'image/jpeg' && file.type !== 'image/png') {
                    options.imageTransform = false;
                }
            },
            upload: function() {
                $('#loading').html(browser.label("Uploading file {number} of {count}... {progress}", {
                    number: uploaded++,
                    count: filesCount,
                    progress: ""
                }));
                uploadInProgress = true;
                $('#loading').show();
                browser.fadeFiles();
            },
            progress: function (evt){
                var progress = Math.round((evt.loaded * 100) / evt.total) + '%';
                $('#loading').html(browser.label("Uploading file {number} of {count}... {progress}", {
                    number: uploaded,
                    count: filesCount,
                    progress: progress
                }));
            },
            filecomplete:function(err, xhr, file) {
                if(err === false) {
                    var response;
                    try {
                        response = $.parseJSON(xhr.response);
                    } catch (error) {
                        response = {
                            success:false,
                            message:browser.label("Unable to process server response")
                        }
                    }
                    if (!response.success) {
                        if (typeof response.message === 'object') {
                            response.message = response.message.join('; ');
                        };
                        errors.push(file.name + ': ' + response.message);
                    }
                } else {
                    errors.push(file.name + ': ' + browser.label("Server error") + ' ' + err);
                }
                uploaded++;
            },
            complete: function (err, xhr){
                uploadInProgress = false;
                $('#loading').hide();
                browser.refresh();
                browser.clearUpload();
                if (errors.length > 0) {
                    browser.alert(errors.join('<br>'), false);
                }
            }
        };
        if (Object.keys(browser.clientResize).length) {
            options.imageTransform = {
                maxWidth: browser.clientResize.maxWidth,
                maxHeight: browser.clientResize.maxHeight,
                quality: browser.clientResize.quality
            };
            options.imageAutoOrientation = true;
        }
        FileAPI.upload(options);
    }
};
