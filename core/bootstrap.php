<?php
require_once 'vendor/autoload.php';

global $site_sessionname;

if (! defined('MAGPIE_CACHE_DIR')) {
    define('MAGPIE_CACHE_DIR', MODX_BASE_PATH . 'assets/cache/rss');
}

require_once 'legacy.php';

require_once 'functions/actions/bkmanager.php';
require_once 'functions/actions/files.php';
require_once 'functions/actions/help.php';
require_once 'functions/actions/import.php';
require_once 'functions/actions/logging.php';
require_once 'functions/actions/mutate_content.php';
require_once 'functions/actions/mutate_plugin.php';
require_once 'functions/actions/mutate_role.php';
require_once 'functions/actions/search.php';
require_once 'functions/actions/settings.php';

require_once 'functions/helper.php';
require_once 'functions/preload.php';
require_once 'functions/tv.php';
require_once 'functions/nodes.php';
require_once 'functions/processors.php';

$site_sessionname = genEvoSessionName(); // For legacy extras not using startCMSSession
