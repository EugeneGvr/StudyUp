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

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    // Auth
    Route::get('login')->name('admin.login')->uses('Auth\LoginController@showLoginForm')->middleware('guest:web-admin');
    Route::post('login')->name('admin.login.attempt')->uses('Auth\LoginController@login')->middleware('guest:web-admin');
    Route::post('logout')->name('admin.logout')->uses('Auth\LoginController@logout');

    // Dashboard
    Route::get('/')->name('admin.dashboard')->uses('DashboardController')->middleware('auth:web-admin');

    // Subjects
    Route::group(['prefix' => 'subjects'], function () {
        Route::get('/')->name('admin.subjects')->uses('SubjectsController@index')->middleware('remember', 'auth:web-admin');
        Route::get('/create')->name('admin.subjects.create')->uses('SubjectsController@create')->middleware('auth:web-admin');
        Route::post('/')->name('admin.subjects.store')->uses('SubjectsController@store')->middleware('auth:web-admin');
        Route::get('/{user}/edit')->name('admin.subjects.edit')->uses('SubjectsController@edit')->middleware('auth:web-admin');
        Route::put('/{user}')->name('admin.subjects.update')->uses('SubjectsController@update')->middleware('auth:web-admin');
        Route::delete('/{user}')->name('admin.subjects.destroy')->uses('SubjectsController@destroy')->middleware('auth:web-admin');
        Route::put('/{user}/restore')->name('admin.subjects.restore')->uses('SubjectsController@restore')->middleware('auth:web-admin');
    });

    // Themes
    Route::group(['prefix' => 'themes'], function () {
        Route::get('/')->name('admin.themes')->uses('ThemesController@index')->middleware('remember', 'auth:web-admin');
        Route::get('/create')->name('admin.themes.create')->uses('ThemesController@create')->middleware('auth:web-admin');
        Route::post('/')->name('admin.themes.store')->uses('ThemesController@store')->middleware('auth:web-admin');
        Route::get('/{user}/edit')->name('admin.themes.edit')->uses('ThemesController@edit')->middleware('auth:web-admin');
        Route::put('/{user}')->name('admin.themes.update')->uses('ThemesController@update')->middleware('auth:web-admin');
        Route::delete('/{user}')->name('admin.themes.destroy')->uses('ThemesController@destroy')->middleware('auth:web-admin');
        Route::put('/{user}/restore')->name('admin.themes.restore')->uses('ThemesController@restore')->middleware('auth:web-admin');
    });

    // Subthemes
    Route::group(['prefix' => 'subthemes'], function () {
        Route::get('/')->name('admin.subthemes')->uses('SubthemesController@index')->middleware('remember', 'auth:web-admin');
        Route::get('/create')->name('admin.subthemes.create')->uses('SubthemesController@create')->middleware('auth:web-admin');
        Route::post('/')->name('admin.themes.store')->uses('SubthemesController@store')->middleware('auth:web-admin');
        Route::get('/{user}/edit')->name('admin.subthemes.edit')->uses('SubthemesController@edit')->middleware('auth:web-admin');
        Route::put('/{user}')->name('admin.subthemes.update')->uses('SubthemesController@update')->middleware('auth:web-admin');
        Route::delete('/{user}')->name('admin.subthemes.destroy')->uses('SubthemesController@destroy')->middleware('auth:web-admin');
        Route::put('/{user}/restore')->name('admin.subthemes.restore')->uses('SubthemesController@restore')->middleware('auth:web-admin');
    });

    // Questions
    Route::group(['prefix' => 'questions'], function () {
        Route::get('/')->name('admin.questions')->uses('QuestionsController@index')->middleware('remember', 'auth:web-admin');
        Route::get('/create')->name('admin.questions.create')->uses('QuestionsController@create')->middleware('auth:web-admin');
        Route::post('/')->name('admin.questions.store')->uses('QuestionsController@store')->middleware('auth:web-admin');
        Route::get('/{user}/edit')->name('admin.questions.edit')->uses('QuestionsController@edit')->middleware('auth:web-admin');
        Route::put('/{user}')->name('admin.questions.update')->uses('QuestionsController@update')->middleware('auth:web-admin');
        Route::delete('/{user}')->name('admin.questions.destroy')->uses('QuestionsController@destroy')->middleware('auth:web-admin');
        Route::put('/{user}/restore')->name('admin.questions.restore')->uses('QuestionsController@restore')->middleware('auth:web-admin');
    });

    // Users
    Route::group(['prefix' => 'users'], function () {
        Route::get('/')->name('admin.users')->uses('UsersController@index')->middleware('remember', 'auth:web-admin');
        Route::get('/create')->name('admin.users.create')->uses('UsersController@create')->middleware('auth:web-admin');
        Route::post('/')->name('admin.users.store')->uses('UsersController@store')->middleware('auth:web-admin');
        Route::get('/{user}/edit')->name('admin.users.edit')->uses('UsersController@edit')->middleware('auth:web-admin');
        Route::put('/{user}')->name('admin.users.update')->uses('UsersController@update')->middleware('auth:web-admin');
        Route::delete('/{user}')->name('admin.users.destroy')->uses('UsersController@destroy')->middleware('auth:web-admin');
        Route::put('/{user}/restore')->name('admin.users.restore')->uses('UsersController@restore')->middleware('auth:web-admin');
    });

    // Administrators
    Route::group(['prefix' => 'administrators'], function () {
        Route::get('/')->name('admin.admins')->uses('AdminsController@index')->middleware('remember', 'auth:web-admin');
        Route::get('/create')->name('admin.admins.create')->uses('AdminsController@create')->middleware('auth:web-admin');
        Route::post('/')->name('admin.admins.store')->uses('AdminsController@store')->middleware('auth:web-admin');
        Route::get('/{administrator}/show')->name('admin.admins.show')->uses('AdminsController@show')->middleware('auth:web-admin');
        Route::put('/{administrator}')->name('admin.admins.update')->uses('AdminsController@update')->middleware('auth:web-admin');
        Route::delete('/{administrator}')->name('admin.admins.destroy')->uses('AdminsController@destroy')->middleware('auth:web-admin');
        Route::put('/{administrator}/restore')->name('admin.admins.restore')->uses('AdminsController@restore')->middleware('auth:web-admin');
    });

    // Roles
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/')->name('admin.roles')->uses('RolesController@index')->middleware('remember', 'auth:web-admin');
        Route::get('/create')->name('admin.roles.create')->uses('RolesController@create')->middleware('auth:web-admin');
        Route::post('/')->name('admin.roles.store')->uses('RolesController@store')->middleware('auth:web-admin');
        Route::get('/{role}')->name('admin.roles.show')->uses('RolesController@show')->middleware('auth:web-admin');
        Route::put('/{role}')->name('admin.roles.update')->uses('RolesController@update')->middleware('auth:web-admin');
        Route::delete('/{role}')->name('admin.roles.destroy')->uses('RolesController@destroy')->middleware('auth:web-admin');
    });
// Images
    Route::get('/img/{path}', 'ImagesController@show')->where('path', '.*');

// Organizations
//    Route::group(['prefix' => 'organizations'], function () {
//        Route::get('/')->name('admin.organizations')->uses('OrganizationsController@index')->middleware('remember', 'auth:web-admin');
//        Route::get('/create')->name('admin.organizations.create')->uses('OrganizationsController@create')->middleware('auth:web-admin');
//        Route::post('/')->name('admin.organizations.store')->uses('OrganizationsController@store')->middleware('auth:web-admin');
//        Route::get('/{organization}/edit')->name('admin.organizations.edit')->uses('OrganizationsController@edit')->middleware('auth:web-admin');
//        Route::put('/{organization}')->name('admin.organizations.update')->uses('OrganizationsController@update')->middleware('auth:web-admin');
//        Route::delete('/{organization}')->name('admin.organizations.destroy')->uses('OrganizationsController@destroy')->middleware('auth:web-admin');
//        Route::put('/{organization}/restore')->name('admin.organizations.restore')->uses('OrganizationsController@restore')->middleware('auth:web-admin');
//    });
// Contacts
//    Route::group(['prefix' => 'contacts'], function () {
//        Route::get('/')->name('admin.contacts')->uses('ContactsController@index')->middleware('remember', 'auth:web-admin');
//        Route::get('/create')->name('admin.contacts.create')->uses('ContactsController@create')->middleware('auth:web-admin');
//        Route::post('/')->name('admin.contacts.store')->uses('ContactsController@store')->middleware('auth:web-admin');
//        Route::get('/{contact}/edit')->name('admin.contacts.edit')->uses('ContactsController@edit')->middleware('auth:web-admin');
//        Route::put('/{contact}')->name('admin.contacts.update')->uses('ContactsController@update')->middleware('auth:web-admin');
//        Route::delete('/{contact}')->name('admin.contacts.destroy')->uses('ContactsController@destroy')->middleware('auth:web-admin');
//        Route::put('/{contact}/restore')->name('admin.contacts.restore')->uses('ContactsController@restore')->middleware('auth:web-admin');
//    });
// Reports
//    Route::get('reports')->name('admin.reports')->uses('ReportsController')->middleware('auth:web-admin');

// 500 error
//    Route::get('500', function () {
//        echo $fail;
//    });
});
