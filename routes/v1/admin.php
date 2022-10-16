<?php

use Illuminate\Support\Facades\Route;


/*
 * define all admin app related routes here
 * */

Route::post('login', 'AuthController@login');
Route::post('forgot-password', 'AuthController@forgot_password');

Route::post('hash/create', 'AuthController@createHash');

Route::middleware(['apiAuth'])->group(function () {

    // routes any logged in users can access
    Route::post('shops/favourite',                  'ShopController@addTofavourite');
    Route::post('projects/favourite',               'ProjectController@addTofavourite');
    Route::post('projects/remove/user',             'ProjectController@removeUserFromProject');
    Route::post('projects/delete',                  'ProjectController@deleteProject');
    Route::post('projects/stage/{projectStage}',    'ProjectController@updateProjectStage');

    Route::get('projects/attachments/{project}',   'ProjectController@getAttachments');
    Route::post('projects/attachments/{project}',   'ProjectController@saveAttachments');
    Route::delete('projects/attachments/{rolloutAttachment}',   'ProjectController@removeRolloutAttachment');

    Route::post('projects/export',                  'ProjectController@exportInventoryUsed');

    Route::delete('projects/attachment/{projectStageAttachment}', 'ProjectController@removeAttachment');

    // ACL middleware will only allow users who have access to below routes
    Route::middleware(['ACL'])->group(function () {
        /*user module*/
        Route::get('auth/user',         'AuthController@authUser');
        Route::get('logout',            'AuthController@logout');
        /*user module*/

        Route::post('users/{user}',     'UserController@update');

        Route::post('vendors/{vendor}', 'VendorController@update');

        Route::apiResources([
            'vendors'   => 'VendorController',
            'shops'     => 'ShopController',
            'users'     => 'UserController',
            'projects'  => 'ProjectController'
        ]);
    });

});
