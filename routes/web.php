<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('login');
});

// clients routes
Route::group(['prefix' => 'clients'], function () {
    Route::get('/view', 'ClientsController@showClientsList')->name('clients.clientList');
    Route::get('/add', 'ClientsController@showAddClient')->name('clients.addClient');
    Route::post('/addClient', 'ClientsController@addClient');
    Route::get('/edit/{id}', 'ClientsController@showEditClient')->name('clients.editClient');
    Route::put('/update/{id}', 'ClientsController@updateClient');
});

// cases and proceedings routes
Route::group(['prefix' => 'cases'], function () {
    Route::get('/view', 'CaseController@index')->name('cases.allCases');
    Route::get('/add', 'CaseController@showAddCase')->name('cases.addCase');
    Route::post('/addCase', 'CaseController@addCase');
    Route::get('/edit/{id}', 'CaseController@showEditCase')->name('cases.edit');
    Route::put('/update/{id}', 'CaseController@updateCase');

    // Record of Proceedings
    Route::get('/addRecord', 'ProceedingsController@showAdd')->name('cases.addRecord');
    Route::get('/editRecord/{id}', 'ProceedingsController@showEdit')->name('cases.editRecord');
    Route::post('/saveRecord', 'ProceedingsController@addPro');
    Route::put('/updateRecord/{id}', 'ProceedingsController@updatePro');
});

// properties routes
Route::group(['prefix' => 'properties'], function () {
    Route::get('/view', 'PropertiesController@index')->name('properties.view');
    Route::get('/add', 'PropertiesController@showAddProperty')->name('properties.add');
    Route::post('/addProp', 'PropertiesController@addProp');
    Route::get('/edit/{id}', 'PropertiesController@showEditProperty')->name('properties.edit');
    Route::put('/update/{id}', 'PropertiesController@updateProp');
});

// tenants routes
Route::group(['prefix' => 'tenants'], function () {
    Route::get('/view', 'TenantsController@index')->name('tenants.view');
    Route::get('/add', 'TenantsController@showAdd')->name('tenants.add');
    Route::post('/addTenant', 'TenantsController@addTenant');
    Route::get('/edit/{id}', 'TenantsController@showEdit')->name('tenants.edit');
    Route::put('/update/{id}', 'TenantsController@update');
});

// appointments route
Route::group(['prefix' => 'appointments'], function () {
    Route::get('/view', 'Appointments@index')->name('appointments.view');
    Route::get('/add', 'Appointments@showAdd')->name('appointments.add');
    Route::post('/addAppt', 'Appointments@addAppt');
    Route::get('/edit/{id}', 'Appointments@showEdit')->name('appointments.edit');
    Route::put('/update/{id}', 'Appointments@updateAppt');
});

// transactions route
Route::group(['prefix' => 'transactions'], function () {
    Route::get('/view', 'Trans@index')->name('transactions.view');
    Route::get('/add', 'Trans@showAdd')->name('transactions.add');
    Route::post('/create', 'Trans@addTrans');
    Route::get('/edit/{id}', 'Trans@showEdit')->name('transactions.edit');
    Route::put('/update/{id}', 'Trans@updateTrans');
});

require __DIR__ . '/auth.php';
