<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

$lockElementId = intval($lockElementId);

if($lockElementId > 0) {
?>
<script>
// Polyfill for Navigator.sendBeacon
if (!('sendBeacon' in navigator)) {
    (function(root) {
        'use strict';

        function sendBeacon(url, data) {
            var xhr = ('XMLHttpRequest' in window) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            xhr.open('POST', url, false);
            xhr.setRequestHeader('Accept', '*/*');
            if (typeof data === 'string') {
                xhr.setRequestHeader('Content-Type', 'text/plain;charset=UTF-8');
                xhr.responseType = 'text/plain';
            } else if (Object.prototype.toString.call(data) === '[object Blob]') {
                if (data.type) {
                    xhr.setRequestHeader('Content-Type', data.type);
                }
            }
            xhr.send(data);
            return true;
        }

        if (typeof exports !== 'undefined') {
            if (typeof module !== 'undefined' && module.exports) {
                exports = module.exports = sendBeacon;
            }
            exports.sendBeacon = sendBeacon;
        } else if (typeof define === 'function' && define.amd) {
            define([], function() {
                return sendBeacon;
            });
        } else if ('navigator' in root && !('sendBeacon' in root.navigator)) {
            root.navigator.sendBeacon = sendBeacon;
        }
    })(this);
}

    // Trigger unlock when leaving window
    var form_save = false;
    window.addEventListener('unload', unlockThisElement, false);

    function unlockThisElement() {
        var stay = jQuery('#stay').val();
        var lastClickedElement = localStorage.getItem('MODX_lastClickedElement');
        var sameElement = false;
        if(lastClickedElement != null) {
            try {
                lastClickedElement = JSON.parse( lastClickedElement );
                sameElement = lastClickedElement[0]==<?php echo $lockElementType;?> && lastClickedElement[1]==<?php echo $lockElementId;?> ? true : false;
            } catch(err) {
                console.log(err);
            }
        }

        // Trigger unlock
        // console.log('unlock triggered:', 'stay='+stay, 'form_save='+form_save, 'sameElement='+sameElement, lastClickedElement);
        if((stay != 2 || !form_save) && !sameElement) {
            navigator.sendBeacon('<?php echo MODX_MANAGER_URL; ?>index.php?a=67&type=<?php echo $lockElementType;?>&id=<?php echo $lockElementId;?>&o=' + Math.random());
            if(top.mainMenu) top.mainMenu.reloadtree();
        }
    }

</script>
<?php
}
?>