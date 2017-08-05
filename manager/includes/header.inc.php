<?php
if (IN_MANAGER_MODE != "true") {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");
}
$mxla = $modx_lang_attribute ? $modx_lang_attribute : 'en';

// invoke OnManagerRegClientStartupHTMLBlock event
$evtOut = $modx->invokeEvent('OnManagerMainFrameHeaderHTMLBlock');
$modx_textdir = isset($modx_textdir) ? $modx_textdir : null;
$onManagerMainFrameHeaderHTMLBlock = is_array($evtOut) ? implode("\n", $evtOut) : '';
$textdir = $modx_textdir === 'rtl' ? 'rtl' : 'ltr';
if (!isset($modx->config['mgr_jquery_path'])) {
    $modx->config['mgr_jquery_path'] = 'media/script/jquery/jquery.min.js';
}
if (!isset($modx->config['mgr_date_picker_path'])) {
    $modx->config['mgr_date_picker_path'] = 'media/script/air-datepicker/datepicker.inc.php';
}

if (!empty($_COOKIE['MODX_themeColor'])) {
    $body_class .= ' ' . $_COOKIE['MODX_themeColor'];
}

?>
<!DOCTYPE html>
<html lang="<?= $mxla ?>" dir="<?= $textdir ?>">
<head>
    <title>Evolution CMS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= $modx_manager_charset ?>" />
    <meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width" />
    <meta name="theme-color" content="#1d2023" />
    <link rel="stylesheet" type="text/css" href="media/style/<?= $modx->config['manager_theme'] ?>/style.css?v=<?= $modx->config['settings_version'] ?>" />
    <script type="text/javascript" src="media/script/tabpane.js"></script>
    <?= sprintf('<script src="%s" type="text/javascript"></script>' . "\n", $modx->config['mgr_jquery_path']) ?>

    <?php
    $aArr = array('2');
    if (!in_array($_REQUEST['a'], $aArr)) { ?>
        <script src="media/script/mootools/mootools.js" type="text/javascript"></script>
        <script src="media/script/mootools/moodx.js" type="text/javascript"></script>
    <?php } ?>

    <!-- OnManagerMainFrameHeaderHTMLBlock -->
    <?= $onManagerMainFrameHeaderHTMLBlock . "\n" ?>

    <script type="text/javascript">
        /* <![CDATA[ */

        if (typeof evo !== 'object') {
            evo = {}
        }

        // evoTooltips
        evo.tooltips = function (a) {
            if (!a) {
                return
            }
            a = 'string' === typeof a ? document.querySelectorAll(a) : a
            let b = document.querySelector('.evo-tooltip')
            if (!b) {
                b = document.createElement('div')
                document.body.appendChild(b)
                b.className = 'evo-tooltip'
            }
            let c = parseInt(window.getComputedStyle(b).getPropertyValue('margin-top'));
            [].slice.call(a).forEach(function (f) {
                f.addEventListener('mouseenter', function (e) {
                    b.innerHTML = (this.dataset && this.dataset.tooltip ? (this.dataset.tooltip[0] === '#' ? document.querySelector(this.dataset.tooltip).innerHTML : this.dataset.tooltip) : this.innerHTML)
                    if (e.pageX + b.offsetWidth + (c * 2) > window.innerWidth) {
                        b.style.left = Math.round(e.pageX - b.offsetWidth - (c * 2)) + 'px'
                        b.classList.add('evo-tooltip-right')
                    } else {
                        b.style.left = Math.round(e.pageX) + 'px'
                        b.classList.add('evo-tooltip-left')
                    }
                    if (e.pageY - (b.offsetHeight / 2) - c < 0) {
                        b.style.top = 0
                    } else if (e.pageY + (b.offsetHeight / 2) > window.innerHeight) {
                        b.style.top = Math.round(window.innerHeight - b.offsetHeight) - (c * 2) + 'px'
                    } else {
                        b.style.top = Math.round(e.pageY - (b.offsetHeight / 2)) - c + 'px'
                    }
                    b.classList.add('show')
                })
                f.addEventListener('mouseleave', function () {
                    b.className = 'evo-tooltip'
                })
            })
        }

        // evoSortable
        evo.sortable = function (a, b) {
            if (!a) {
                return
            }
            let h = {
                handleClass: b.handleClass || 'ghost', complete: function () {
                    if ('function' === typeof b.complete) {
                        b.complete()
                    }
                }, change: function () {
                    if ('function' === typeof b.change) {
                        b.change()
                    }
                },
            }
            a = 'string' === typeof a ? document.querySelectorAll(a) : a;
            [].slice.call(a).forEach(function (c) {
                c.onmousedown = function (e) {
                    let d = e.pageY, f, g = parseFloat(getComputedStyle(c).marginTop) + parseFloat(getComputedStyle(c).marginBottom)
                    c.classList.add(h.handleClass)
                    document.onselectstart = function (e) {
                        e.preventDefault()
                    }
                    document.onmousemove = function (e) {
                        f = (e.pageY - d)
                        if (f >= c.offsetHeight && c.nextElementSibling) {
                            d += c.offsetHeight + g
                            c.parentNode.insertBefore(c, c.nextElementSibling.nextElementSibling)
                            h.change()
                            f = 0
                        } else if (f <= -c.offsetHeight && c.previousElementSibling) {
                            d -= c.offsetHeight + g
                            c.parentNode.insertBefore(c, c.previousElementSibling)
                            h.change()
                            f = 0
                        } else if (!c.previousElementSibling && f < 0 || !c.nextElementSibling && f > 0) {
                            f = 0
                        }
                        c.style.webkitTransform = 'translateY(' + f + 'px)'
                        c.style.transform = 'translateY(' + f + 'px)'
                    }
                    document.onmouseup = function () {
                        c.style.webkitTransform = ''
                        c.style.transform = ''
                        c.classList.remove(h.handleClass)
                        document.onmousemove = null
                        document.onselectstart = null
                        h.complete()
                    }
                }
            })
        }

        // check connection to server
        evo.checkConnectionToServer = function () {
            let xhr = new ( window.ActiveXObject || XMLHttpRequest )('Microsoft.XMLHTTP')
            xhr.open('HEAD', '//' + window.location.hostname + window.location.pathname.replace('index.php', 'includes/version.inc.php') + '?time=' + new Date().getTime(), false)
            try {
                xhr.send()
                return (xhr.status >= 200 && xhr.status < 300 || xhr.status === 304)
            } catch (error) {
                return false
            }
        }

        function document_onload ()
        {
            stopWorker()

            <?php
            if (isset($_REQUEST['r']) && preg_match('@^[0-9]+$@', $_REQUEST['r'])) {
                echo 'doRefresh(' . $_REQUEST['r'] . ");\n";
            }
            ?>

            let actionButtons = document.getElementById('actions'), actionSelect = document.getElementById('stay')
            if (actionButtons !== null && actionSelect !== null) {
                let actionPlus = actionButtons.querySelector('.plus'), actionSaveButton = actionButtons.querySelector('a#Button1') || actionButtons.querySelector('#Button1 > a'), actionStay = []
                actionPlus.classList.add('dropdown-toggle')
                actionStay['stay1'] = '<i class="<?= $_style['actions_file'] ?>"></i>'
                actionStay['stay2'] = '<i class="<?= $_style['actions_pencil'] ?>"></i>'
                actionStay['stay3'] = '<i class="<?= $_style['actions_reply'] ?>"></i>'
                if (actionSelect.value) {
                    actionSaveButton.innerHTML += '<i class="<?= $_style['actions_plus'] ?>"></i><span> + </span>' + actionStay['stay' + actionSelect.value] + '<span>' + actionSelect.children['stay' + actionSelect.value].innerHTML + '</span>'
                }
                let actionSelectNewOption = null, actionSelectOptions = actionSelect.children, div = document.createElement('div')
                div.className = 'dropdown-menu'
                actionSaveButton.parentNode.classList.add('dropdown')
                for (let i = 0; i < actionSelectOptions.length; i++) {
                    if (!actionSelectOptions[i].selected) {
                        actionSelectNewOption = document.createElement('SPAN')
                        actionSelectNewOption.className = 'btn btn-block'
                        actionSelectNewOption.dataset.id = i
                        actionSelectNewOption.innerHTML = actionStay[actionSelect.children[i].id] + ' <span>' + actionSelect.children[i].innerHTML + '</span>'
                        actionSelectNewOption.onclick = function () {
                            let s = actionSelect.querySelector('option[selected=selected]')
                            if (s) {
                                s.selected = false
                            }
                            actionSelect.children[this.dataset.id].selected = true
                            actionSaveButton.click()
                        }
                        div.appendChild(actionSelectNewOption)
                    }
                }
                actionSaveButton.parentNode.appendChild(div)
                actionPlus.onclick = function () {
                    this.parentNode.classList.toggle('show')
                }
            }
            evo.tooltips(document.querySelectorAll('[data-tooltip]'))
        }

        function reset_path (elementName)
        {
            document.getElementById(elementName).value = document.getElementById('default_' + elementName).innerHTML
        }

        let dontShowWorker = false

        function document_onunload (e)
        {
            if (!dontShowWorker) {
                top.mainMenu.work()
            }
        }

        // set tree to default action.
        if (parent.tree) {
            parent.tree.ca = 'open'
        }

        // call the updateMail function, updates mail notification in top navigation
        if (top.mainMenu) {
            if (top.mainMenu.updateMail) {
                top.mainMenu.updateMail(true)
            }
        }

        function stopWorker ()
        {
            try {
                parent.mainMenu.stopWork()
            } catch (oException) {
                ww = window.setTimeout('stopWorker()', 500)
            }
        }

        function doRefresh (r)
        {
            try {
                rr = r
                top.mainMenu.startrefresh(rr)
            } catch (oException) {
                vv = window.setTimeout('doRefresh()', 1000)
            }
        }

        let documentDirty = false
        let timerForUnload

        function checkDirt (evt)
        {
            evt = evt || window.event
            if (!evo.checkConnectionToServer()) {
                let message = '<?= $_lang['error_internet_connection'] ?>'
                setTimeout(function () {
                    alert(message)
                }, 10)
                evt.returnValue = message
                timerForUnload = setTimeout('stopWorker()', 100)
                return message
            }
            if (documentDirty === true) {
                let message = '<?= addslashes($_lang['warning_not_saved']) ?>'
                evt.returnValue = message
                timerForUnload = setTimeout('stopWorker()', 100)
                return message
            }
        }

        function saveWait (fName)
        {
            document.getElementById('savingMessage').innerHTML = '<?= $_lang['saving'] ?>'
            for (let i = 0; i < document.forms[fName].elements.length; i++) {
                document.forms[fName].elements[i].disabled = 'disabled'
            }
        }

        let managerPath = ''

        function hideLoader ()
        {
            document.getElementById('preLoader').style.display = 'none'
        }

        // add the 'unsaved changes' warning event handler
        if (typeof window.addEventListener !== 'undefined') {
            window.addEventListener('beforeunload', function (e) {
                checkDirt(e)
                document_onunload()
            }, false)
        } else if (typeof window.attachEvent !== 'undefined') {
            window.attachEvent('onbeforeunload', function (e) {
                checkDirt(e)
                document_onunload()
            })
        } else {
            window.onbeforeunload = function (e) {
                checkDirt(e)
                document_onunload()
            }
        }

        if (typeof window.addEventListener !== 'undefined') {
            window.addEventListener('load', function () {
                document_onload()
            }, false)
        } else if (typeof window.attachEvent !== 'undefined') {
            window.attachEvent('onload', function () {
                document_onload()
            })
        } else {
            window.onload = function () {
                document_onload()
            }
        }

        window.addEventListener('unload', function () {
            clearTimeout(timerForUnload)
        })

        /* ]]> */
    </script>
</head>
<body <?= ($modx_textdir ? ' class="rtl"' : '') ?> class="<?= $body_class ?>">
