<?php

/** This file is part of KCFinder project
  *
  *      @desc Base JavaScript object properties
  *   @package KCFinder
  *   @version 2.51
  *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
  * @copyright 2010, 2011 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */?>

var browser = {
    opener: {},
    support: {},
    files: [],
    clipboard: [],
    labels: [],
    shows: [],
    orders: [],
    cms: ""
};
var uploader = {
    uploadQueue : [],
    uploadInProgress : false,
    already : false,
    filesCount : 0,
    errors : [],
    boundary : '------multipartdropuploadboundary' + (new Date).getTime(),
    currentFile : {},

    updateProgress : function (evt) {
        var progress = evt.lengthComputable
            ? Math.round((evt.loaded * 100) / evt.total) + '%'
            : Math.round(evt.loaded / 1024) + " KB";
        $('#loading').html(browser.label("Uploading file {number} of {count}... {progress}", {
            number: uploader.filesCount - uploader.uploadQueue.length,
            count: uploader.filesCount,
            progress: progress
        }));
    },

    processUploadQueue : function() {
        if (this.uploadInProgress)
            return false;

        if (this.uploadQueue && this.uploadQueue.length) {
            var file = this.uploadQueue.shift();
            this.currentFile = file;
            $('#loading').html(browser.label("Uploading file {number} of {count}... {progress}", {
                number: this.filesCount - this.uploadQueue.length,
                count: this.filesCount,
                progress: ""
            }));
            $('#loading').css('display', 'inline');

            var reader = new FileReader();
            reader.thisFileName = file.name;
            reader.thisFileType = file.type;
            reader.thisFileSize = file.size;
            reader.thisTargetDir = file.thisTargetDir;

            reader.onload = function(evt) {
                uploader.uploadInProgress = true;
                if (!!FileReader.prototype['readAsBinaryString']) {
                  binary = evt.target.result;
                } else { //ie10 sucks again
                  var binary = "";
                  var bytes = new Uint8Array(evt.target.result);
                  var length = bytes.byteLength;
                  for (var i = 0; i < length; i++) 
                  {
                    binary += String.fromCharCode(bytes[i]);
                  }  
                }
                var postbody = '--' + uploader.boundary + '\r\nContent-Disposition: form-data; name="upload[]"';
                if (evt.target.thisFileName)
                    postbody += '; filename="' + _.utf8encode(evt.target.thisFileName) + '"';
                postbody += '\r\n';
                if (evt.target.thisFileSize)
                    postbody += 'Content-Length: ' + evt.target.thisFileSize + '\r\n';
                postbody += 'Content-Type: ' + evt.target.thisFileType + '\r\n\r\n' + binary + '\r\n--' + uploader.boundary + '\r\nContent-Disposition: form-data; name="dir"\r\n\r\n' + _.utf8encode(evt.target.thisTargetDir) + '\r\n--' + uploader.boundary + '\r\n--' + uploader.boundary + '--\r\n';

                var xhr = new XMLHttpRequest();
                xhr.thisFileName = evt.target.thisFileName;

                if (xhr.upload) {
                    xhr.upload.thisFileName = evt.target.thisFileName;
                    xhr.upload.addEventListener("progress", uploader.updateProgress, false);
                }
                xhr.open('POST', browser.baseGetData('upload'), true);
                xhr.setRequestHeader('Content-Type', 'multipart/form-data; boundary=' + uploader.boundary);
                xhr.setRequestHeader('Content-Length', postbody.length);

                xhr.onload = function(e) {
                    $('#loading').css('display', 'none');
                    if (browser.dir == reader.thisTargetDir)
                        browser.fadeFiles();
                    uploader.uploadInProgress = false;
                    uploader.already = true;
                    uploader.processUploadQueue();
                    if (xhr.responseText.substr(0, 1) != '/')
                        uploader.errors[uploader.errors.length] = xhr.responseText;
                }

                xhr.sendAsBinary(postbody);
            };

            reader.onerror = function(evt) {
                $('#loading').css('display', 'none');
                uploader.uploadInProgress = false;
                uploader.already = true;
                uploader.processUploadQueue();
                uploader.errors[uploader.errors.length] = browser.label("Failed to upload {filename}!", {
                    filename: evt.target.thisFileName
                });
            };
            if (!!FileReader.prototype['readAsBinaryString']) {
              reader.readAsBinaryString(file);
            } else {
              reader.readAsArrayBuffer(file); //ie sucks
            }

        } else {
            var loop = setInterval(function() {
                if (uploader.uploadInProgress) return;
                clearInterval(loop);
                if (uploader.currentFile.thisTargetDir == browser.dir)
                    browser.refresh();
                uploader.boundary = '------multipartdropuploadboundary' + (new Date).getTime();

                if (uploader.errors.length) {
                    browser.alert(uploader.errors.join('\n'));
                    uploader.errors = [];
                }
            }, 333);
        }
    }
};