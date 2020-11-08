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
    Route::post('/employees/{id}/updateGivenSalary', 'EmployeeController@updateGivenSalary');
    Route::delete('/employees/{id}/deleteSalary', 'EmployeeController@deleteSalary');
    Route::resource('/staffs', 'StaffController');
    Route::resource('/materials', 'MaterialController');
    Route::resource('/material-list', 'MaterialListController');
    Route::resource('/services', 'ServiceController');
    Route::resource('/orders', 'OrderController');
    Route::delete('/orders/destroyPayment/{id}', 'OrderController@destroyPayment');
    Route::post('/orders/{id}/pay', 'OrderController@pay');
    Route::post('/orders/{id}/paySpending', 'OrderController@paySpending');
    Route::delete('/orders/{id}/deleteSpending', 'OrderController@deleteSpending');
    Route::post('/orders/{id}/addSpending', 'OrderController@addSpending');
    Route::post('/orders/{spending_id}/editSpending', 'OrderController@editSpending');
    Route::resource('/crane-orders', 'CraneOrderController');
    Route::post('/crane-orders/{id}/pay', 'CraneOrderController@pay');
    Route::post('/crane-orders/take-from-driver/{paid_id}', 'CraneOrderController@takeFromDriver');
    Route::delete('/crane-orders/destroyPayment/{id}', 'CraneOrderController@destroyPayment');
    Route::resource('/laser', 'LaserListController');
    Route::resource('/drivers', 'DriverController');
    Route::resource('/cars', 'CarController');
    Route::post('/cars/{id}/pay', 'CarController@paySalary');
    Route::post('/drivers/{id}/pay', 'DriverController@paySalary');
    Route::post('/drivers/{id}/updateGivenSalary', 'DriverController@updateGivenSalary');
    Route::delete('/drivers/{id}/deleteSalary', 'DriverController@deleteSalary');
    Route::resource('/cashdesk', 'CashDeskController');
    Route::resource('/spendings', 'SpendingController');
    Route::post('/spendings/{id}/pay', 'SpendingController@paySalary');
});
