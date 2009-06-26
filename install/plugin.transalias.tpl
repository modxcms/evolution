/**
 * @name TransAlias
 * @desc Handle the task of loading transliteration tables and applying them
 *      to a string for the purpose of creating a friendly URL alias.
 * @package modx
 * @subpackage modx.plugins.transalias
 * @author Olivier B. Deland, TV override by Mike Schell
 * @license GNU General Public License
 */

/*
 * Initialize parameters
 */
if (!isset ($alias)) { return ; }
if (!isset ($plugin_dir) ) { $plugin_dir = 'transalias'; }
if (!isset ($plugin_path) ) { $plugin_path = $modx->config['base_path'].'assets/plugins/'.$plugin_dir; }
if (!isset ($table_name)) { $table_name = 'common'; }
if (!isset ($override_tv)) { $override_tv = ''; }

if (!class_exists('TransAlias')) {
    require_once $plugin_path.'/transalias.class.php';
    $trans = new TransAlias($modx);
}

/*
 * see if TV overrides the table name
 */
if(!empty($override_tv)) {
    $tvval = $trans->getTVValue($override_tv);
    if(!empty($tvval)) {
        $table_name = $tvval;
    }
}

/*
 * Handle events
 */
$e =& $modx->event;
switch ($e->name ) {
    case 'OnStripAlias':
        if ($trans->loadTable($table_name)) {
            $output = $trans->stripAlias($alias);
            $e->output($output);
            $e->stopPropagation();
        }
        break ;
    default:
        return ;
}