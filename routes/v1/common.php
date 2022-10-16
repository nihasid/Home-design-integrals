<?php

use Illuminate\Support\Facades\Route;


/*
 * define routes which can be used in both admin ans store apps
 * */

Route::get('/', 'GeneralController@welcome');
