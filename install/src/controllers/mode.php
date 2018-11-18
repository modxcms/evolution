<?php
// Determine upgradeability
$upgradeable = 0;
if (is_file(EVO_CORE_PATH . 'config/database/connections/default.php')) { // Include the file so we can test its validity
    $db_config = include_once EVO_CORE_PATH . 'config/database/connections/default.php';
    // We need to have all connection settings - tho prefix may be empty so we have to ignore it
    if (isset($db_config['database'])) {
        if (!$conn = @mysqli_connect($db_config['host'], $db_config['username'], $db_config['password'], '', isset
        ($db_config['port']) ? $db_config['port'] : null)) {
            $upgradeable = isset($_POST['installmode']) && $_POST['installmode'] == 'new' ? 0 : 2;
        } elseif (!@mysqli_select_db($conn, $db_config['database'])) {
            $upgradeable = isset($_POST['installmode']) && $_POST['installmode'] == 'new' ? 0 : 2;
        } else {
            $upgradeable = 1;
        }
    } else {
        $upgradeable = 2;
    }
}

$ph['moduleName'] = $moduleName;
$ph['displayNew'] = ($upgradeable != 0) ? 'display:none;' : '';
$ph['displayUpg'] = ($upgradeable == 0) ? 'display:none;' : '';
$ph['displayAdvUpg'] = $ph['displayUpg'];
$ph['checkedNew'] = !$upgradeable ? 'checked' : '';
$ph['checkedUpg'] = ($_POST['installmode'] == 1 || $upgradeable == 1) ? 'checked' : '';
$ph['checkedAdvUpg'] = ($_POST['installmode'] == 2 || $upgradeable == 2) ? 'checked' : '';
$ph['install_language'] = $install_language;
$ph['disabledUpg'] = ($upgradeable != 1) ? 'disabled' : '';
$ph['disabledAdvUpg'] = ($upgradeable == 0) ? 'disabled' : '';

$tpl = file_get_contents(dirname(__DIR__) . '/template/actions/mode.tpl');
$content = parse($tpl, $ph);
echo parse($content, $_lang, '[%', '%]');
