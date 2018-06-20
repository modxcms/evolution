<?php
require_once __DIR__ . '/vendor/autoload.php';

global $site_sessionname;

require_once __DIR__ . '/legacy.php';

$site_sessionname = genEvoSessionName(); // For legacy extras not using startCMSSession
