<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

/**
 * All admin route
 */
Route::group(['middleware' => 'auth'], function () {
    Route::resource('/', 'UserController');
    Route::resource('/clients', 'ClientController');
    Route::resource('/staffs', 'StaffController');
    Route::resource('/materials', 'MaterialController');
    Route::resource('/material-list', 'MaterialListController');
    Route::resource('/services', 'ServiceController');
    Route::resource('/orders', 'OrderController');
    Route::resource('/laser', 'LaserListController');
});
