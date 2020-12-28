<!DOCTYPE html>
<html lang="{{ ManagerTheme::getLang() }}" dir="{{ ManagerTheme::getTextDir() }}">
<head>
    <title>Evolution CMS</title>
    <meta http-equiv="Content-Type" content="text/html; charset={{ ManagerTheme::getCharset() }}"/>
    <meta name="viewport" content="initial-scale=1.0,user-scalable=no,maximum-scale=1,width=device-width"/>
    <meta name="theme-color" content="#1d2023"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    @if(class_exists(Tracy\Debugger::class) && $modx->get('config')->get('tracy.active'))
        {!! Tracy\Debugger::renderLoader() !!}
    @endif
    <link rel="stylesheet" type="text/css" href="{{ ManagerTheme::css() }}"/>
    <script type="text/javascript" src="media/script/tabpane.js"></script>
    <script type="text/javascript" src="{{ $modx->getConfig('mgr_jquery_path') }}"></script>
    @if ($modx->getConfig('show_picker') === true)
        <script src="{{ ManagerTheme::getThemeUrl() }}/js/color.switcher.js" type="text/javascript"></script>
    @endif

    {!! ManagerTheme::getMainFrameHeaderHTMLBlock() !!}

    <script type="text/javascript">
        if (!evo) {
            var evo = {};
        }
        if (!evo.config) {
          evo.config = {};
        }
        var actions,
            actionStay = [],
            dontShowWorker = false,
            documentDirty = false,
            timerForUnload,
            managerPath = '';

        evo.lang = {!! json_encode(Illuminate\Support\Arr::only(
            ManagerTheme::getLexicon(),
            ['saving', 'error_internet_connection', 'warning_not_saved']
        )) !!};
        evo.style = {!! json_encode(Illuminate\Support\Arr::only(
            ManagerTheme::getStyle(),
            ['icon_file', 'icon_pencil', 'icon_reply', 'icon_plus']
        )) !!};
        evo.MODX_MANAGER_URL = '{{  MODX_MANAGER_URL }}';
        evo.config.which_browser = '{{ $modx->getConfig('which_browser') }}';
    </script>
    <script src="media/script/main.js"></script>
    @if (get_by_key($_REQUEST, 'r', '', 'is_numeric'))
        <script>doRefresh({{ $_REQUEST['r'] }});</script>
    @endif
    @stack('scripts.top')
    {!! $modx->getRegisteredClientStartupScripts() !!}
</head>

<body class="{{ ManagerTheme::getTextDir() }} {{ ManagerTheme::getThemeStyle() }}" data-evocp="color">
