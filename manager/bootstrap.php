<?php

global $site_sessionname;

include_once 'functions/preload.php';

$site_sessionname = genEvoSessionName(); // For legacy extras not using startCMSSession

include_once 'src/Interfaces/CoreInterface.php';
include_once 'src/Interfaces/DatabaseInterface.php';
include_once 'src/Interfaces/EventInterface.php';
