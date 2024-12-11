<?php

use App\Http\Controllers\SpeechController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;

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

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return view('dashboard', compact('user'));
    })->name('dashboard');
});

Route::group(['prefix' => 'speech', 'middleware' => 'auth'], function () {
    Route::post('/transcribe', [SpeechController::class, 'transcribe'])->name('speech.transcribe');
    Route::get('/showAdd', [speechController::class, 'index']);
});

Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
    Route::post('/addClient', 'ClientsController@addClient');
    Route::get('/edit/{id}', 'ClientsController@showEditClient')->name('clients.editClient');
    Route::put('/update/{id}', 'ClientsController@updateClient');
});

// Route::get('/', function () {
//     return view('login');
// });

// clients routes
Route::group(['prefix' => 'clients', 'middleware' => 'auth'], function () {
    Route::get('/view', 'ClientsController@showClientsList')->name('clients.clientList');
    Route::get('/add', 'ClientsController@showAddClient')->name('clients.addClient');
    Route::post('/addClient', 'ClientsController@addClient');
    Route::get('/edit/{id}', 'ClientsController@showEditClient')->name('clients.editClient');
    Route::put('/update/{id}', 'ClientsController@updateClient');
});

// cases and proceedings routes
Route::group(['prefix' => 'cases', 'middleware' => 'auth'], function () {
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
Route::group(['prefix' => 'properties', 'middleware' => 'auth'], function () {
    Route::get('/view', 'PropertiesController@index')->name('properties.view');
    Route::get('/add', 'PropertiesController@showAddProperty')->name('properties.add');
    Route::post('/addProp', 'PropertiesController@addProp');
    Route::get('/edit/{id}', 'PropertiesController@showEditProperty')->name('properties.edit');
    Route::put('/update/{id}', 'PropertiesController@updateProp');
});

// tenants routes
Route::group(['prefix' => 'tenants', 'middleware' => 'auth'], function () {
    Route::get('/view', 'TenantsController@index')->name('tenants.view');
    Route::get('/add', 'TenantsController@showAdd')->name('tenants.add');
    Route::post('/addTenant', 'TenantsController@addTenant');
    Route::get('/edit/{id}', 'TenantsController@showEdit')->name('tenants.edit');
    Route::put('/update/{id}', 'TenantsController@update');
});

// transactions route
Route::group(['prefix' => 'transactions', 'middleware' => 'auth'], function () {
    Route::get('/view', 'Trans@index')->name('transactions.view');
    Route::get('/add', 'Trans@showAdd')->name('transactions.add');
    Route::post('/create', 'Trans@addTrans');
    Route::get('/edit/{id}', 'Trans@showEdit')->name('transactions.edit');
    Route::put('/update/{id}', 'Trans@updateTrans');
});

// Appointments
Route::group(['prefix' => 'appointments', 'middleware' => 'auth'], function () {
    Route::get('/view', 'AppointmentController@index')->name('appointments.view');
    Route::post('/store', 'AppointmentController@store')->name('appointments.store');
    Route::get('/create', 'AppointmentController@create')->name('appointments.create');
    Route::get('/{appointment}', 'AppointmentController@show')->name('appointments.show');
    Route::get('/{appointment}/edit', 'AppointmentController@edit')->name('appointments.edit');
    Route::put('/{appointment}', 'AppointmentController@update')->name('appointments.update');
    Route::delete('/{appointment}', 'AppointmentController@destroy')->name('appointments.destroy');
    Route::get('/available-slots', 'AppointmentController@availableSlots')->name('appointments.available-slots');
});

require __DIR__ . '/auth.php';
