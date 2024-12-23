<?php

use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\WalletController;

Route::get('/token_error', function (Request $request) {
    return json_encode(['status' => 'Failure', 'message' => 'Please enter valid token!!']);
});
Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, "Login"]);
    Route::post('/singup', [AuthController::class, 'SingUp']);
    Route::get('/logout', [AuthController::class, "Logout"]);
    Route::get('/checkIfUserIsLoggedIn', [AuthController::class, "checkIfUserIsLoggedIn"]);

      //leads 
      Route::group(['prefix' => 'leads'], function () {
        Route::post('/create', [LeadController::class, "Create"]);
        Route::post('/showlead', [LeadController::class, "showLead"]);
        Route::post('/updatestatus/{id}', [LeadController::class, "updateStatus"]);
        Route::post('/updatefollowup/{id}', [LeadController::class, "updateFollowup"]);            
    });

    Route::group(['middleware' => 'CheckJWT'], function () {

        Route::group(['prefix' => 'wallet'], function () {
            Route::post('/add-money', [WalletController::class, 'addMoney']);
            Route::get('/transaction-history', [WalletController::class, 'showTransactionHistory']);
            Route::post('/spend-money', [WalletController::class, 'spendMoney']);
            Route::post('/spend-for-leads', [WalletController::class, 'spendForLeads']);
        });

        //User
        Route::group(['prefix' => 'user'], function () {
            Route::post('/show', [UserController::class, "Index"]);
            Route::post('/create', [UserController::class, "Create"]);
            Route::post('/update', [UserController::class, "Update"]);
        });

        //Services 
        Route::group(['prefix' => 'services'], function () {
            Route::get('/my-services', [ServiceController::class, "showServices"]);
            Route::post('/add-services', [ServiceController::class, "addServices"]);
            Route::post('/deactive-services', [ServiceController::class, "deactiveServices"]);
        });

        // Comment
        Route::get('/comments/{type}/{id}', [CommentController::class, "store"]);
        /**
         * Leads
         */
        // Route::group(['prefix' => 'leads'], function () {
//     Route::get('/data', 'LeadsController@anyData')->name('leads.data');
//     Route::patch('/updateassign/{id}', 'LeadsController@updateAssign');
//     Route::patch('/updatestatus/{id}', 'LeadsController@updateStatus');
//     Route::patch('/updatefollowup/{id}', 'LeadsController@updateFollowup')->name('leads.followup');
// });
//     Route::resource('leads', 'LeadsController');
//     Route::post('/comments/{type}/{id}', 'CommentController@store');


        /**
         * Main
         */
        // Route::get('/', 'PagesController@dashboard');
        // Route::get('dashboard', 'PagesController@dashboard')->name('dashboard');

        /**
         * Users
         */
        // Route::group(['prefix' => 'users'], function () {
//     Route::get('/data', 'UsersController@anyData')->name('users.data');
//     Route::get('/taskdata/{id}', 'UsersController@taskData')->name('users.taskdata');
//     Route::get('/leaddata/{id}', 'UsersController@leadData')->name('users.leaddata');
//     Route::get('/clientdata/{id}', 'UsersController@clientData')->name('users.clientdata');
//     Route::get('/users', 'UsersController@users')->name('users.users');
// });
//     Route::resource('users', 'UsersController');

        /**
         * Roles
         */
        // Route::resource('roles', 'RolesController');
        /**
         * Clients
         */
        // Route::group(['prefix' => 'clients'], function () {
//     Route::get('/data', 'ClientsController@anyData')->name('clients.data');
//     Route::post('/create/cvrapi', 'ClientsController@cvrapiStart');
//     Route::post('/upload/{id}', 'DocumentsController@upload');
//     Route::patch('/updateassign/{id}', 'ClientsController@updateAssign');
// });
//     Route::resource('clients', 'ClientsController');
//     Route::resource('documents', 'DocumentsController');


        /**
         * Tasks
         */
        // Route::group(['prefix' => 'tasks'], function () {
//     Route::get('/data', 'TasksController@anyData')->name('tasks.data');
//     Route::patch('/updatestatus/{id}', 'TasksController@updateStatus');
//     Route::patch('/updateassign/{id}', 'TasksController@updateAssign');
//     Route::post('/updatetime/{id}', 'TasksController@updateTime');
// });
//     Route::resource('tasks', 'TasksController');

        /**
         * Settings
         */
        // Route::group(['prefix' => 'settings'], function () {
//     Route::get('/', 'SettingsController@index')->name('settings.index');
//     Route::patch('/permissionsUpdate', 'SettingsController@permissionsUpdate');
//     Route::patch('/overall', 'SettingsController@updateOverall');
// });
//      Route::group(['prefix' => 'notifications'], function () {
//         Route::post('/markread', 'NotificationsController@markRead')->name('notification.read');
//         Route::get('/markall', 'NotificationsController@markAll');
//         Route::get('/{id}', 'NotificationsController@markRead');
//     });

        /**
         * Invoices
         */
        // Route::group(['prefix' => 'invoices'], function () {
        //     Route::post('/updatepayment/{id}', 'InvoicesController@updatePayment')->name('invoice.payment.date');
        //     Route::post('/reopenpayment/{id}', 'InvoicesController@reopenPayment')->name('invoice.payment.reopen');
        //     Route::post('/sentinvoice/{id}', 'InvoicesController@updateSentStatus')->name('invoice.sent');
        //     Route::post('/newitem/{id}', 'InvoicesController@newItem')->name('invoice.new.item');
        // });
        //     Route::resource('invoices', 'InvoicesController');

    });
});