<?php
if( ! function_exists('install_sessionCheck')) {
    function install_sessionCheck()
    {
        global $_lang;

        // session loop-back tester
        if (!isset($_GET['action']) || $_GET['action'] !== 'mode') {
            if (!isset($_SESSION['test']) || $_SESSION['test'] != 1) {
                echo '
<html>
<head>
	<title>Install Problem</title>
	<style type="text/css">
		*{margin:0;padding:0}
		body{margin:150px;background:#eee;}
		.install{padding:10px;border:3px solid #ffc565;background:#ffddb4;margin:0 auto;text-align:center;}
		p{ margin:20px 0; }
		a{margin-top:30px;padding:5px;}
	</style>
</head>
<body>
	<div class="install">
		<p>' . $_lang["session_problem"] . '</p>
		<p><a href="./">' . $_lang["session_problem_try_again"] . '</a></p>
	</div>
</body>
</html>';
                exit;
            }
        }
    }
}

if( ! function_exists('parse')) {
    /**
     * @param string $src
     * @param array $ph
     * @param string $left
     * @param string $right
     * @return string
     */
    function parse($src, $ph, $left = '[+', $right = '+]')
    {
        foreach ($ph as $k => $v) {
            $k = $left . $k . $right;
            $src = str_replace($k, $v, $src);
        }

        return $src;
    }
}

if( ! function_exists('ph')) {
    /**
     * @return array
     */
    function ph()
    {
        global $_lang, $moduleName, $moduleVersion, $modx_textdir, $modx_release_date;
        $ph = array();

        if (isset($_SESSION['installmode'])) {
            $installmode = $_SESSION['installmode'];
        } else {
            $installmode = get_installmode();
        }

        $ph['pagetitle'] = $_lang['modx_install'];
        $ph['textdir'] = $modx_textdir ? ' id="rtl"' : '';
        $ph['help_link'] = $installmode == 0 ? $_lang['help_link_new'] : $_lang['help_link_upd'];
        $ph['version'] = $moduleVersion;
        $ph['release_date'] = ($modx_textdir ? '&rlm;' : '') . $modx_release_date;
        $ph['footer1'] = $_lang['modx_footer1'];
        $ph['footer2'] = $_lang['modx_footer2'];
        $ph['current_year'] = date('Y');

        return $ph;
    }
}

if( ! function_exists('get_installmode')) {
    /**
     * @return int
     */
    function get_installmode()
    {
        global $base_path, $database_server, $database_user, $database_password, $dbase, $table_prefix;

        $conf_path = "{$base_path}manager/includes/config.inc.php";
        if (!is_file($conf_path)) {
            $installmode = 0;
        } elseif (isset($_POST['installmode'])) {
            $installmode = $_POST['installmode'];
        } else {
            include_once("{$base_path}manager/includes/config.inc.php");

            if (!isset($dbase) || empty($dbase)) {
                $installmode = 0;
            } else {
                $host = explode(':', $database_server, 2);
                $conn = mysqli_connect($host[0], $database_user, $database_password,'', isset($host[1]) ? $host[1] : null);
                if ($conn) {
                    $_SESSION['database_server'] = $database_server;
                    $_SESSION['database_user'] = $database_user;
                    $_SESSION['database_password'] = $database_password;

                    $dbase = trim($dbase, '`');
                    $rs = mysqli_select_db($conn, $dbase);
                } else {
                    $rs = false;
                }

                if ($rs) {
                    $_SESSION['dbase'] = $dbase;
                    $_SESSION['table_prefix'] = $table_prefix;
                    $_SESSION['database_collation'] = 'utf8_general_ci';
                    $_SESSION['database_connection_method'] = 'SET CHARACTER SET';

                    $tbl_system_settings = "`{$dbase}`.`{$table_prefix}system_settings`";
                    $rs = mysqli_query($conn,
                        "SELECT setting_value FROM {$tbl_system_settings} WHERE setting_name='settings_version'");
                    if ($rs) {
                        $row = mysqli_fetch_assoc($rs);
                        $settings_version = $row['setting_value'];
                    } else {
                        $settings_version = '';
                    }

                    if (empty($settings_version)) {
                        $installmode = 0;
                    } else {
                        $installmode = 1;
                    }
                } else {
                    $installmode = 1;
                }
            }
        }

        return $installmode;
    }
}

if( ! function_exists('getLangs')) {
    /**
     * @param string $install_language
     * @return string
     */
    function getLangs($install_language)
    {
        if ($install_language !== "english" && is_file(sprintf("../%s/includes/lang/%s.inc.php", MGR_DIR, $install_language))) {
            $manager_language = $install_language;
        } else {
            $manager_language = "english";
        }

        $langs = array();
        if ($handle = opendir("../" . MGR_DIR . "/includes/lang")) {
            while (false !== ($file = readdir($handle))) {
                if (strpos($file, '.inc.') !== false) {
                    $langs[] = $file;
                }
            }
            closedir($handle);
        }
        sort($langs);

        $_ = array();
        foreach ($langs as $language) {
            $abrv_language = explode('.', $language);
            $selected = (strtolower($abrv_language[0]) == strtolower($manager_language)) ? ' selected' : '';
            $_[] = sprintf('<option value="%s" %s>%s</option>', $abrv_language[0], $selected,
                ucwords($abrv_language[0]));
        }

        return implode("\n", $_);
    }
}
