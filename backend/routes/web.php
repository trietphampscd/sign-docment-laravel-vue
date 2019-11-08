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

Route::get('/', 'SpaController@index')->where('any', '.*');

Route::get('callback-onedrive', 'SpaController@callbackOnedrive');
//====admin=====
Route::prefix('admin')->group(function () {
    Route::get('403', 'WelcomeController@page403');

    //login
    Route::get('login', function () {
        if (Auth::viaRemember() || Auth::check()) {
            return redirect()->intended('/admin/dashboard');
        }
        return view('admin.auth.login', ['msg' => null]);
    });
    Route::post('login', 'Auth\LoginController@login');
    //logout
    Route::get('logout', 'Auth\LoginController@logout');
    
    Route::group(['prefix' => '',  'middleware' => 'checkAuth'], function(){
        Route::get('', function () {
            return redirect('admin/dashboard');
        });

        Route::prefix('dashboard')->group(function(){
            Route::get('', 'DashboardController@showData');
        });

        Route::prefix('settings')->group(function(){
            Route::get('notifications', function(){
                return view('admin.setting.notification', ['status'=>null, 'msg'=>null]);
            });
        });

        Route::prefix('profile')->group(function(){
            Route::get('/view', 'UsersController@profile');
            Route::get('/edit', 'UsersController@profileViewEdit');
            Route::post('/edit', 'UsersController@profileEdit');
            Route::get('change-password', function(){
                return view('admin.profile.change-password', ['status'=>null, 'msg'=>null]);
            });
            Route::post('change-password', 'UsersController@changePassword');
        });

        Route::prefix('users')->group(function(){
            Route::get('/all', 'UsersController@index');
            Route::delete('/delete/{id}', 'UsersController@destroy');
            Route::get('/new', function(){
                return view('admin.user.new', ['status'=>null, 'msg'=>null]);
            });
            Route::post('/new', 'UsersController@newUser');
            Route::get('/{id}/update', 'UsersController@getUserEdit');
            Route::put('/{id}/update', 'UsersController@updateUser');
        });

        Route::group(['prefix' => 'admin-users',  'middleware' => 'checkMasAdmin'],function(){
            Route::get('/all', 'UsersController@indexAdminUser');
            Route::delete('/delete/{id}', 'UsersController@destroy');
            Route::get('/new', function(){
                return view('admin.admin_users.new', ['status'=>null, 'msg'=>null]);
            });
            Route::post('/new', 'UsersController@newUserAdmin');
            Route::get('/{id}/update', 'UsersController@getUserAdminEdit');
            Route::put('/{id}/update', 'UsersController@updateUserAdmin');
        });

        Route::prefix('contracts')->group(function(){
            Route::get('/all', 'ContractsController@index');
            Route::delete('/delete/{id}', 'ContractsController@destroy');
        });

    });
});

