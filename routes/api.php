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
Route::post('register', 'Api\Login\LoginController@registerUser');

# Forget password
Route::post('forgetPassword', 'Api\Administration\ProfileController@forgetPassword');

//Route::post('testEmail', 'Test\TestPay@testEmail');


# New password
Route::post('newPassword', 'Api\Administration\ProfileController@updateFirstPassword');

# Validate acount
Route::post('validateEmail', 'Helper\TemplateHelper@validateAcount');

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

    Route::post('logout', 'Api\Login\LoginController@logout');

    Route::apiResource('companyData', 'Api\Administration\CompanyDataController');

    # Turn administration
    Route::get('turnsList', 'Api\Administration\TurnsController@turnsList');
    # Client turn
    Route::apiResource('clientTurn', 'Api\Turns\TurnsClientController');

    # Client turn
    Route::apiResource('service', 'Api\Administration\ServiceController');
    # Companies list
    Route::get('companiesList', 'Api\Client\ClientCompanyController@companyList');
    # Branches list
    Route::get('branchesList', 'Api\Client\ClientCompanyController@branchesList');
    # Branch detail
    Route::get('branchesList/{id}', 'Api\Client\ClientCompanyController@branchDetail');
    # Services list
    Route::get('servicesList', 'Api\Client\ComplementsListController@servicesList');
    # Employees list
    Route::get('employeesList', 'Api\Client\ComplementsListController@employeesList');
    # List card config
    Route::get('listPaymentData', 'Api\Client\ComplementsListController@listPaymentData');
    # Save card config
    Route::post('paymentData', 'Api\Client\ComplementsListController@paymentData');
    # Change turn
    Route::post('changeTurn/{id}', 'Api\Administration\TurnsController@changeTurn');

    #-------- Grooming ------------
    # Administration

    # Employee type
    Route::apiResource('employeeType', 'Api\Administration\Grooming\EmployeeTypeController');

    # Service config
    Route::apiResource('serviceConfig', 'Api\Administration\Grooming\GServiceController');

    # Service administration
    Route::get('clientServiceList', 'Api\Administration\Grooming\ClientServiceController@clientServiceList');
    # Service administration detail
    Route::get('clientServiceList/{id}', 'Api\Administration\Grooming\ClientServiceController@clientServiceDetails');
    # Change state to service client
    Route::put('modifyServiceClient/{id}', 'Api\Administration\Grooming\ClientServiceController@modifyServiceClient');

    # Asign employee to service client and set state
    Route::put('assignEmployeeService/{id}', 'Api\Administration\Grooming\ClientServiceController@assignEmployee');

    # Request service
    Route::post('requestService', 'Api\Administration\Grooming\RequestServiceController@requestService');

    # Cancel service
    Route::post('cancelService/{id}', 'Api\Administration\Grooming\RequestServiceController@cancelService');

    # Client services
    Route::get('clientServices', 'Api\Client\ClientServicesListController@servicesList');

    # Client services
    Route::get('clientServices/{id}', 'Api\Client\ClientServicesListController@servicesListDetail');

    # Business hours to client service
    Route::get('businessHours', 'Api\Client\ComplementsListController@businessHours');

    # Profile
    Route::get('userProfile', 'Api\Administration\UserController@userProfile');

    # Transaction log list
    Route::get('transactionList', 'Api\Administration\ProfileController@transactionList');

    # Transaction log detail
    Route::get('transactionList/{id}', 'Api\Administration\ProfileController@transactionDetail');

    # Repayment
    Route::post('repayment/{id}', 'Api\Administration\ProfileController@repayment');

    # Change password
    Route::post('changePassword', 'Api\Administration\ProfileController@newPassword');

    # Test routs

    Route::post('testPay', 'Test\TestPay@tets');

});


