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

Route::post('login', 'Api\Login\LoginController@login');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('users', 'Api\Administration\UserController');
    Route::apiResource('role', 'Api\Administration\RoleController');
    Route::apiResource('rolePermission', 'Api\Administration\RolePermissionController');
    Route::get('moduleValidator', 'Api\Administration\MenuController@modulegeneral');

    # Master routs
    Route::apiResource('mCompanies', 'Api\Master\MCompanyController');
    Route::apiResource('mBranchOffices', 'Api\Master\MBranchOfficeController');
    Route::apiResource('mUsers', 'Api\Master\MUsersController');
    # Add move user to company
    Route::post('mMoveUsers', 'Api\Master\MUsersController@addUserToCompany');


});


