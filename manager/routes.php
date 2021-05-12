<?php

use Illuminate\Support\Facades\Route;

Route::match(['GET', 'POST'], '/', 'Actions@handleAction');
