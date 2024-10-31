<?php

use App\Http\Controllers\CaseController;
use App\Http\Controllers\ClientsController;
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

Route::group(['prefix' => 'clients'], function () {
    Route::get('/view', 'ClientsController@showClientsList')->name('clients.clientList');
    Route::get('/add', 'ClientsController@showAddClient')->name('clients.addClient');
    Route::post('/addClient', 'ClientsController@addClient');
    Route::get('/edit', 'ClientsController@showEditClient')->name('clients.editClient');
    Route::put('/update', 'ClientsController@updateClient');
});

Route::group(['prefix' => 'cases'], function () {
    Route::get('/view', 'CaseController@index')->name('cases.allCases');
    Route::get('/add', 'CaseController@showAddCase')->name('cases.addCase');
    Route::post('/addCase', 'CaseController@addCase');
    Route::get('/edit', 'CaseController@showEditCase')->name('cases.editcase');
    Route::put('/update', 'CaseController@updateCase');
    Route::get('/getClients', 'CaseController@getClients')->name('cases.getClients');
});
require __DIR__ . '/auth.php';
