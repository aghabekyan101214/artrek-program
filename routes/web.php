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
]);

/**
 * All admin route
 */
Route::group(['middleware' => 'auth'], function () {
    Route::resource('/', 'UserController');
    Route::resource('/clients', 'ClientController');
    Route::resource('/employees', 'EmployeeController');
    Route::post('/employees/{id}/pay', 'EmployeeController@giveSalary');
    Route::delete('/employees/{id}/deleteSalary', 'EmployeeController@deleteSalary');
    Route::resource('/staffs', 'StaffController');
    Route::resource('/materials', 'MaterialController');
    Route::resource('/material-list', 'MaterialListController');
    Route::resource('/services', 'ServiceController');
    Route::resource('/orders', 'OrderController');
    Route::delete('/orders/destroyPayment/{id}', 'OrderController@destroyPayment');
    Route::post('/orders/{id}/pay', 'OrderController@pay');
    Route::resource('/crane-orders', 'CraneOrderController');
    Route::post('/crane-orders/{id}/pay', 'CraneOrderController@pay');
    Route::post('/crane-orders/take-from-driver/{paid_id}', 'CraneOrderController@takeFromDriver');
    Route::resource('/laser', 'LaserListController');
    Route::resource('/drivers', 'DriverController');
    Route::resource('/cars', 'CarController');
    Route::post('/cars/{id}/pay', 'CarController@paySalary');
    Route::post('/drivers/{id}/pay', 'DriverController@paySalary');
    Route::resource('/cashdesk', 'CashDeskController');
    Route::resource('/spendings', 'SpendingController');
    Route::post('/spendings/{id}/pay', 'SpendingController@paySalary');
});
