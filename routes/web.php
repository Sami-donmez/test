<?php

use Illuminate\Support\Facades\Auth;
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


Route::group(['middleware'=>'auth'],function (){
    Route::get('/users', 'UserController@index')->name('users');
    Route::get('/users/create', 'UserController@create')->name('users.create');
    Route::post('/users/store', 'UserController@store')->name('users.store');
    Route::get('/users/update/{id}', 'UserController@index')->name('users.update');
    Route::post('/users/update/{id}', 'UserController@index')->name('users.edit');


    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
    Route::get('/logout', 'HomeController@logout')->name('logout.get');
    Route::get('/salesflow', 'FlowController@salesflow')->name('salesflow');


    Route::get('/workflow', 'TaskController@flow')->name('workflow');
    Route::get('/tasks', 'TaskController@index')->name('tasks');
    Route::get('/tasks/kanban', 'TaskController@get_kanban_data')->name('tasks.data.kanban');


    Route::get('/contacts', 'ContactController@index')->name('contacts');
    Route::get('/contacts/data', 'ContactController@get_table_data')->name('contacts.data');
    Route::get('/contacts/create', 'ContactController@create')->name('contacts.create');
    Route::post('/contacts/create', 'ContactController@store')->name('contacts.store');

    Route::get('/customers', 'CustomerController@index')->name('customers');
    Route::get('/customers/data', 'CustomerController@get_table_data')->name('customers.data');
    Route::get('/customers/create', 'CustomerController@create')->name('customers.create');
    Route::post('/customers/create', 'CustomerController@store')->name('customers.store');

    Route::get('/leads', 'LeadController@index')->name('leads');
    Route::get('/leads/data', 'LeadController@get_table_data')->name('leads.data');
    Route::get('/leads/data/kanban', 'LeadController@get_kanban_data')->name('leads.data.kanban');
    Route::get('/leads/create', 'LeadController@create')->name('leads.create');
    Route::post('/leads/create', 'LeadController@store')->name('leads.store');


    Route::get('/products', 'ProductController@index')->name('products');
    Route::get('/products/create', 'ProductController@create')->name('products.create');
    Route::post('/products/create', 'ProductController@store')->name('products.store');


    Route::get('/suppliers', 'SupplierController@index')->name('suppliers');
    Route::get('/suppliers/create', 'SupplierController@create')->name('suppliers.create');
    Route::post('/suppliers/create', 'SupplierController@store')->name('suppliers.store');
    Route::get('/suppliers/update/{id}', 'SupplierController@update')->name('suppliers.update');
    Route::post('/suppliers/update/{id}', 'SupplierController@edit')->name('suppliers.edit');


    Route::get('/calendar', 'CalendarController@index')->name('calendar');
    Route::post('/calendar/create', 'CalendarController@store')->name('calendar.store');
    Route::get('/calendar/update/{id}', 'CalendarController@update')->name('calendar.update');
    Route::get('/calendar/{id}', 'CalendarController@show')->name('calendar.show');
    Route::get('/calendar/delete/{id}', 'CalendarController@update')->name('calendar.delete');
    Route::get('/calendar/data', 'CalendarController@update')->name('calendar.data');



});
Route::get('/', 'HomeController@index')->name('home.index');
Route::get('/login/{company}', 'HomeController@login')->name('home.login');
Auth::routes(['register'=>false]);
