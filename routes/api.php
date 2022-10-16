<?php

use Illuminate\Support\Facades\Route;


/*
 * define all store app related routes here
 * */

Route::get('/', 'GeneralController@welcome');
Route::get('inventoryList', 'InventroyController@index');
Route::post('projectUpdate', 'InventroyController@store');
Route::get('inventory/{project_id}', 'InventroyController@getInventoryByProjectId');

// TODO: create separate MIDDLEWARE to look for LONG-HASH
//Route::post('createHash', 'InventroyController@createHash');Route::post('getHash', 'InventroyController@getHash');
Route::post('getProject', 'InventroyController@getProjectByLongHash');
