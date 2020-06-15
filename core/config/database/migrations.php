<?php
if (IN_INSTALL_MODE === false)
    return 'migrations';
else
    return 'migrations_install';

