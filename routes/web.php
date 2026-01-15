<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\TeamController; 
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CrmController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Auth\adminlogincontroller;

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

// Route::get('/clear-cache', function () {
//     $exitCode = Artisan::call('cache:clear');
//     // $exitCode = Artisan::call('route:clear');
//     // $exitCode = Artisan::call('config:clear');
//     // $exitCode = Artisan::call('view:clear');
//     // return what you want
// });
//=========================================== FRONTEND =====================================================

Route::group(['prefix' => '/'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('/');
});

//======================================= ADMIN ===================================================
Route::group(['prifix' => 'admin'], function () {
    Route::group(['middleware'=>'admin.guest'],function(){

        Route::get('/admin_index', [adminlogincontroller::class, 'admin_login'])->name('admin_login');
        Route::post('/login_process', [adminlogincontroller::class, 'admin_login_process'])->name('admin_login_process');

    });
Route::group(['middleware'=>'admin.auth'],function(){

 Route::get('/index', [TeamController::class, 'admin_index'])->name('admin_index');
 Route::get('/logout', [adminlogincontroller::class, 'admin_logout'])->name('admin_logout');
 Route::get('/profile', [adminlogincontroller::class, 'admin_profile'])->name('admin_profile');
 Route::get('/view_change_password', [adminlogincontroller::class, 'admin_change_pass_view'])->name('view_change_password');
 Route::post('/admin_change_password', [adminlogincontroller::class, 'admin_change_password'])->name('admin_change_password');

        // Admin Team ------------------------

Route::get('/view_team', [TeamController::class, 'view_team'])->name('view_team');
Route::get('/add_team_view', [TeamController::class, 'add_team_view'])->name('add_team_view');
Route::post('/add_team_process', [TeamController::class, 'add_team_process'])->name('add_team_process');
Route::get('/UpdateTeamStatus/{status}/{id}', [TeamController::class, 'UpdateTeamStatus'])->name('UpdateTeamStatus');
Route::get('/deleteTeam/{id}', [TeamController::class, 'deleteTeam'])->name('deleteTeam');



// Admin CRM settings ------------------------
Route::get('/add_settings', [CrmController::class, 'add_settings'])->name('add_settings');
Route::get('/view_settings', [CrmController::class, 'view_settings'])->name('view_settings');
Route::get('/update_settings/{id}', [CrmController::class, 'update_settings'])->name('update_settings');
Route::post('/add_settings_process', [CrmController::class, 'add_settings_process'])->name('add_settings_process');
Route::post('/update_settings_process/{id}', [CrmController::class, 'update_settings_process'])->name('update_settings_process');
Route::get('/deletesetting/{id}', [CrmController::class, 'deletesetting'])->name('deletesetting');

// Admin Categories ------------------------
Route::get('/view_categories', [CategoryController::class, 'view_categories'])->name('view_categories');
Route::get('/add_category_view', [CategoryController::class, 'add_category_view'])->name('add_category_view');
Route::post('/add_category_process', [CategoryController::class, 'add_category_process'])->name('add_category_process');
Route::get('/edit_category_view/{id}', [CategoryController::class, 'edit_category_view'])->name('edit_category_view');
Route::post('/update_category_process/{id}', [CategoryController::class, 'update_category_process'])->name('update_category_process');
Route::get('/update_category_status/{status}/{id}', [CategoryController::class, 'update_category_status'])->name('update_category_status');
Route::get('/delete_category/{id}', [CategoryController::class, 'delete_category'])->name('delete_category');

// Admin Subcategories ------------------------
Route::get('/view_subcategories', [CategoryController::class, 'view_subcategories'])->name('view_subcategories');
Route::get('/add_subcategory_view', [CategoryController::class, 'add_subcategory_view'])->name('add_subcategory_view');
Route::post('/add_subcategory_process', [CategoryController::class, 'add_subcategory_process'])->name('add_subcategory_process');
Route::get('/edit_subcategory_view/{id}', [CategoryController::class, 'edit_subcategory_view'])->name('edit_subcategory_view');
Route::post('/update_subcategory_process/{id}', [CategoryController::class, 'update_subcategory_process'])->name('update_subcategory_process');
Route::get('/update_subcategory_status/{status}/{id}', [CategoryController::class, 'update_subcategory_status'])->name('update_subcategory_status');
Route::get('/delete_subcategory/{id}', [CategoryController::class, 'delete_subcategory'])->name('delete_subcategory');

// Admin Products ------------------------
Route::get('/view_products', [CategoryController::class, 'view_products'])->name('view_products');
Route::get('/add_product_view', [CategoryController::class, 'add_product_view'])->name('add_product_view');
Route::post('/add_product_process', [CategoryController::class, 'add_product_process'])->name('add_product_process');
Route::get('/edit_product_view/{id}', [CategoryController::class, 'edit_product_view'])->name('edit_product_view');
Route::post('/update_product_process/{id}', [CategoryController::class, 'update_product_process'])->name('update_product_process');
Route::get('/update_product_status/{status}/{id}', [CategoryController::class, 'update_product_status'])->name('update_product_status');
Route::get('/delete_product/{id}', [CategoryController::class, 'delete_product'])->name('delete_product');
Route::get('/get_subcategories', [CategoryController::class, 'get_subcategories'])->name('get_subcategories');

    });

});



