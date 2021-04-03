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
Route::group(['middleware' => ['auth', 'check_role']], function () {
    Route::resource('/', 'UserController');
    Route::resource('/clients', 'ClientController');
    Route::resource('/employees', 'EmployeeController');
    Route::post('/employees/{id}/pay', 'EmployeeController@giveSalary')->name('employee_pay_salary');
    Route::post('/employees/{id}/updateGivenSalary', 'EmployeeController@updateGivenSalary')->name('employee_update_salary');
    Route::delete('/employees/{id}/deleteSalary', 'EmployeeController@deleteSalary')->name('employee_delete_salary');;
    Route::resource('/staffs', 'StaffController');
    Route::resource('/materials', 'MaterialController');
    Route::resource('/material-list', 'MaterialListController');
    Route::resource('/services', 'ServiceController');
    Route::resource('/orders', 'OrderController');
    Route::delete('/orders/destroyPayment/{id}', 'OrderController@destroyPayment')->name('orders_delete_payment');
    Route::post('/orders/{id}/pay', 'OrderController@pay')->name('orders_pay');
    Route::post('/orders/{id}/paySpending', 'OrderController@paySpending')->name('orders_pay_spending');
    Route::delete('/orders/{id}/deleteSpending', 'OrderController@deleteSpending')->name('orders_delete_spending');
    Route::post('/orders/{id}/addSpending', 'OrderController@addSpending')->name('orders_add_spending');
    Route::post('/orders/{spending_id}/editSpending', 'OrderController@editSpending')->name('orders_edit_spending');
    Route::resource('/crane-orders', 'CraneOrderController');
    Route::post('/crane-orders/{id}/pay', 'CraneOrderController@pay')->name('crane_orders_pay');
    Route::post('/crane-orders/take-from-driver/{paid_id}', 'CraneOrderController@takeFromDriver')->name('crane_orders_take_from_driver');
    Route::delete('/crane-orders/destroyPayment/{id}', 'CraneOrderController@destroyPayment')->name('crane_orders_delete_payment');
    Route::resource('/laser', 'LaserListController');
    Route::resource('/drivers', 'DriverController');
    Route::resource('/cars', 'CarController');
    Route::post('/cars/{id}/pay', 'CarController@paySalary')->name('cars.pay_salary');
    Route::post('/drivers/{id}/pay', 'DriverController@paySalary')->name('drivers.pay_salary');
    Route::post('/drivers/{id}/updateGivenSalary', 'DriverController@updateGivenSalary')->name('drivers.update_salary');
    Route::delete('/drivers/{id}/deleteSalary', 'DriverController@deleteSalary')->name('drivers.delete_salary');;
    Route::resource('/cashdesk', 'CashDeskController');
    Route::resource('/spendings', 'SpendingController');
    Route::post('/spendings/{id}/pay', 'SpendingController@paySalary')->name('spendings.pay');
});
