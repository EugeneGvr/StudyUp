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
        Route::get('/{subject}/edit')->name('admin.subjects.edit')->uses('SubjectsController@edit')->middleware('auth:web-admin');
        Route::put('/{subject}')->name('admin.subjects.update')->uses('SubjectsController@update')->middleware('auth:web-admin');
        Route::delete('/{subject}')->name('admin.subjects.destroy')->uses('SubjectsController@destroy')->middleware('auth:web-admin');
        Route::put('/{subject}/restore')->name('admin.subjects.restore')->uses('SubjectsController@restore')->middleware('auth:web-admin');
    });

    // Themes
    Route::group(['prefix' => 'themes'], function () {
        Route::get('/')->name('admin.themes')->uses('ThemesController@index')->middleware('remember', 'auth:web-admin');
        Route::get('/create')->name('admin.themes.create')->uses('ThemesController@create')->middleware('auth:web-admin');
        Route::post('/')->name('admin.themes.store')->uses('ThemesController@store')->middleware('auth:web-admin');
        Route::get('/{theme}/edit')->name('admin.themes.edit')->uses('ThemesController@edit')->middleware('auth:web-admin');
        Route::put('/{theme}')->name('admin.themes.update')->uses('ThemesController@update')->middleware('auth:web-admin');
        Route::delete('/{theme}')->name('admin.themes.destroy')->uses('ThemesController@destroy')->middleware('auth:web-admin');
        Route::put('/{theme}/restore')->name('admin.themes.restore')->uses('ThemesController@restore')->middleware('auth:web-admin');
    });

    // Subthemes
    Route::group(['prefix' => 'subthemes'], function () {
        Route::get('/')->name('admin.subthemes')->uses('SubthemesController@index')->middleware('remember', 'auth:web-admin');
        Route::get('/create')->name('admin.subthemes.create')->uses('SubthemesController@create')->middleware('auth:web-admin');
        Route::post('/')->name('admin.subthemes.store')->uses('SubthemesController@store')->middleware('auth:web-admin');
        Route::get('/{subtheme}/edit')->name('admin.subthemes.edit')->uses('SubthemesController@edit')->middleware('auth:web-admin');
        Route::put('/{subtheme}')->name('admin.subthemes.update')->uses('SubthemesController@update')->middleware('auth:web-admin');
        Route::delete('/{subtheme}')->name('admin.subthemes.destroy')->uses('SubthemesController@destroy')->middleware('auth:web-admin');
        Route::put('/{subtheme}/restore')->name('admin.subthemes.restore')->uses('SubthemesController@restore')->middleware('auth:web-admin');
    });

    // Question
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
     //Localities
        Route::group(['prefix' => 'localities'], function () {
            Route::get('/')->name('admin.localities')->uses('LocalitiesController@index')->middleware('remember', 'auth:web-admin');
            Route::post('/')->name('admin.localities.store')->uses('LocalitiesController@store')->middleware('auth:web-admin');
            Route::put('/{locality}')->name('admin.localities.update')->uses('LocalitiesController@update')->middleware('auth:web-admin');
            Route::delete('/{locality}')->name('admin.localities.destroy')->uses('LocalitiesController@destroy')->middleware('auth:web-admin');
        });
});

// Auth
Route::get('login')->name('login')->uses('Auth\LoginController@showForm')->middleware('guest:web');
Route::post('login')->name('login.attempt')->uses('Auth\LoginController@login')->middleware('guest:web');
Route::post('logout')->name('logout')->uses('Auth\LoginController@logout');

Route::get('register')->name('register')->uses('Auth\RegisterController@showRegistrationForm');
Route::post('register')->name('register.attempt')->uses('Auth\RegisterController@create');

Route::get('verify/{verificationLink}')->name('verify')->uses('Auth\VerificationController@verify');

Route::get('reset')->name('reset')->uses('Auth\ResetPasswordController@showForm');
Route::post('reset')->name('reset.attempt')->uses('Auth\ResetPasswordController@reset');

// Dashboard
Route::get('/')->name('dashboard')->uses('DashboardController')->middleware('auth:web');
Route::get('/{id}')->name('test')->uses('TestController')->middleware('auth:web');

// Cabinet
Route::get('/cabinet')->name('cabinet')->uses('CabinetController@index')->middleware('auth:web');

// Subjects
//Route::group(['prefix' => 'subjects'], function () {
//    Route::get('/')->name('subjects')->uses('SubjectsController@index')->middleware('remember', 'auth:web');
//    Route::get('/{subject}')->name('subjects.show')->uses('SubjectsController@show')->middleware('auth:web');
//    Route::get('/{subject}/test')->name('subjects.test')->uses('SubjectsController@test')->middleware('auth:web');
//});

// Api routes
Route::group(['prefix' => 'api/v1', 'namespace' => 'API'], function () {
    Route::get('/localities/{parent_id}')->name('api.v1.localities')->uses('LocalitiesController@index');
    Route::get('/themes/{subject_id}')->name('api.v1.themes')->uses('ThemesController@index');
    Route::get('/subtheme/{theme_id}')->name('api.v1.subthemes')->uses('SubthemesController@index');
    Route::post('/get-question')->name('api.v1.get-question')->uses('TestController@getQuestion');
    Route::post('/answer-question')->name('api.v1.answer-question')->uses('TestController@answerQuestion');
});
