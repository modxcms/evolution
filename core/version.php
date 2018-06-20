<?php
$_ver = [
    'version' => '1.4.4', // Current version number
    'release_date' => 'Jun 08, 2018', // Date of release
    'branch' => 'Evolution', // Codebase name
];

$_ver['full_appname'] = implode(' ', [
    $_ver['branch'],
    $_ver['version'],
    $_ver['release_date']
]);

return $_ver;
