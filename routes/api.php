<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST,PATCH, PUT, DELETE, OPTIONS');
//header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-xsrf-token, Authorization');
// auth
Route::get('storage/{fileType}/{id}/{ext}/{thumbnail?}', 'StorageController@getFile'); // no auth check as some photos must be available public. TODO add auth check in controller
Route::delete('storage/{fileType}/{id}', 'StorageController@deleteFile')->middleware('auth:api');

Route::post('auth/login', 'LoginController@login');
Route::post('auth/refresh', 'LoginController@refreshToken');
Route::post('auth/logout', 'LoginController@logout');

Route::post('users', 'UserController@store');
Route::get('users', 'UserController@index')->middleware('auth:api', 'role:owner|admin');         
Route::get('users/{id}', 'UserController@show');
Route::put('users/{id}', 'UserController@update')->middleware('auth:api');
Route::delete('users/{id}', 'UserController@delete')->middleware('auth:api', 'role:owner|admin');
Route::get('users/{id}/forms', 'FormController@getAvailableFormsbyUser');


Route::post('users/{userId}/photo', 'ProfilePhotoController@upload')->middleware('auth:api');
Route::get('users/{userId}/photo', 'ProfilePhotoController@show')->middleware('auth:api');
Route::put('users/{userId}/photo/rotate/{degree}', 'ProfilePhotoController@rotate')->middleware('auth:api');
Route::delete('users/{userId}/photo', 'ProfilePhotoController@destroy')->middleware('auth:api');

Route::get('settings/{userId}/sign', 'SignController@show')->middleware('auth:api');
Route::post('settings/{userId}/sign', 'SignController@set')->middleware('auth:api');
Route::put('settings/{userId}/sign', 'SignController@set')->middleware('auth:api');
Route::get('settings/{userId}/sign/image', 'SignController@getImage')->middleware('auth:api');

Route::get('settings/{userId}/initial', 'InitialController@show')->middleware('auth:api');
Route::post('settings/{userId}/initial', 'InitialController@set')->middleware('auth:api');
Route::put('settings/{userId}/initial', 'InitialController@set')->middleware('auth:api');
Route::get('settings/{userId}/initial/image', 'InitialController@getImage')->middleware('auth:api');

Route::post('fields', 'FieldController@store')->middleware('auth:api');
Route::get('fields/{id}', 'FieldController@show');
Route::put('fields/{id}', 'FieldController@update')->middleware('auth:api');
Route::delete('fields/{id}', 'FieldController@destroy')->middleware('auth:api');
Route::post('fields/{id}/selects', 'FieldController@addSelectOptions')->middleware('auth:api');

Route::delete('files/{id}/fields', 'FileController@destroyFields')->middleware('auth:api');
Route::resource('/files', 'FileController')->middleware('auth:api');
Route::get('/files/{id}/stream', 'FileController@getFile');

Route::get('forms/{id}/fields', 'FormController@getFields')->middleware('auth:api');
Route::get('forms/{id}/files', 'FormController@getFiles')->middleware('auth:api');
Route::get('forms/{id}', 'FormController@show');
Route::get('forms', 'FormController@index')->middleware('auth:api');

Route::post('forms', 'FormController@store')->middleware('auth:api');
Route::put('forms/{id}', 'FormController@update')->middleware('auth:api');
Route::delete('forms/{id}', 'FormController@destroy')->middleware('auth:api');


Route::get('users/{userId}/all_send_emails', 'SendEmailController@index')->middleware('auth:api');
Route::get('users/{userId}/send_emails', 'SendEmailController@indexWithParams')->middleware('auth:api');
Route::post('send_emails', 'SendEmailController@store')->middleware('auth:api');
Route::put('send_emails/{id}', 'SendEmailController@update')->middleware('auth:api');
Route::delete('send_emails/{id}', 'SendEmailController@destroy')->middleware('auth:api');
Route::get('emails', 'UserController@getAllEmails');
Route::post('emails/send', 'SendFormController@send');
/* TODO: ADD api to api authentification */
Route::get('forms/company/{company}', 'FormController@getAvailableFormsByCompany');
	//->middleware('client');


Route::get('shared/forms', 'FormController@getSharedForms');
Route::get('shared/forms/{id}', 'FormController@getSharedForm');


Route::get('generate-pdf', 'pdfController@generatePDF')->name('generate-pdf');

Route::post('files/{fileId}/store', 'FormEntriesController@store');

