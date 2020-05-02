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
    # Route::apiResource('rolePermission', 'Api\Administration\RolePermissionController');
    Route::get('moduleValidator', 'Api\Administration\MenuController@modulegeneral');

    # Master routs
    Route::apiResource('mCompanies', 'Api\Master\MCompanyController');
    Route::apiResource('mBranchOffices', 'Api\Master\MBranchOfficeController');
    Route::apiResource('mUsers', 'Api\Master\MUsersController');
    Route::apiResource('mRoles', 'Api\Master\MRoleController');
    Route::get('mUserState', 'Api\Master\MOtherRoutsController@userState');
    Route::get('mCompanyState', 'Api\Master\MOtherRoutsController@companyState');
    Route::get('mCompanyType', 'Api\Master\MOtherRoutsController@companyType');
    Route::get('mBranchState', 'Api\Master\MOtherRoutsController@branchState');
    # Add move user to company
    Route::post('mMoveUsers', 'Api\Master\MUsersController@addUserToCompany');

    # Turn administration
    Route::apiResource('clientTurn', 'Api\Turns\TurnsClientController');


});


