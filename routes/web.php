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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/{any?}', function (){
    return view('welcome');
})->where('any', '^(?!api\/)[\/\w\.-]*');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/companies', function (){
    return view('admin.companies.index');
});
Route::resource('api/companies', 'CompaniesController', ['except' => ['create', 'edit']]);
Route::get('/main', function () {
    return view('layouts.coreuiadmin2');
});

Route::get('/currentuser', function() {
    return Auth::user();
});