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
use Illuminate\Support\Facades\Session;

Route::get('/', 'SearchController@index')->name('home');
Route::get('/resource/{id}', 'ResourceController@view');
Route::post('login_api', 'Auth\LoginController@loginApi');
Route::get('/forgotpass', 'Auth\LoginController@forgotpass');
Route::post('/doresetpass', 'Auth\LoginController@doresetpass');

Route::middleware(['auth'])->group(function () {
    Route::get('/resource_management', 'ResourceManagementController@index');
    Route::get('/resource_management/create', 'ResourceManagementController@create');
    Route::get('/resource_management/{resource}', 'ResourceManagementController@show');
    Route::get('/resource_management/{resource}/edit', 'ResourceManagementController@edit');
    Route::post('/resource_management', 'ResourceManagementController@update');
    Route::delete('/resource_management/{resource}', 'ResourceManagementController@destroy');

    Route::get('/group_management', 'GroupManagementController@index');
    Route::get('/group_management/{id}', 'GroupManagementController@view');
    Route::get('/group_management/{id}/user', 'GroupManagementController@user_search');
    Route::post('/group_management/{id}/adduser', 'GroupManagementController@add_user');
    Route::post('/group_management/{id}/edituser', 'GroupManagementController@update_user');
    Route::delete('/group_management/{id}/removeuser', 'GroupManagementController@remove_user');

    Route::get('/user_management', 'UserAdminController@index');
    Route::get('/user_management/groups/{id}', 'UserAdminController@user_groups');
    Route::post('/user_management/groups/{id}/add_group', 'UserAdminController@add_group');
    Route::delete('/user_management/removegroups', 'UserAdminController@remove_group');
    Route::post('/user_management/adduser', 'UserAdminController@add_user');
    Route::post('/user_management/updateuser', 'UserAdminController@update_user');
    Route::get('/user_management/remove/{id}', 'UserAdminController@remove_user');
    Route::get('/user_management/reset/{id}', 'UserAdminController@reset_password');
    Route::get('/user_management/fetchuser', 'UserAdminController@fetchuser');
    
    Route::get('logout_api', 'Auth\LoginController@logoutApi');
});

Route::get('reports', 'ReportsController@index');
//Route::get('test', 'TestController@test');