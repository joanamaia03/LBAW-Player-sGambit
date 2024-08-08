<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CreateAuctionController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\StaticPageController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home
Route::redirect('/', '/mainPage');


// Profile
Route::controller(UserController::class)->group(function () {
    Route::get('/users/{id}', 'show')->name('profile');
    Route::get('/users/{id}/edit', 'showEdit')->name('profileEdit');
    Route::post('/users/{id}/edit', 'edit')->name('editProfile');
    Route::get('/users/{id}/delete', 'delete')->name('deleteAccount');
});

// Main (falta te este no a7 prob)
Route::controller(MainPageController::class)->group(function () {
    Route::get('/mainPage', 'show')->name('main');
});

//Static Pages
Route::controller(StaticPageController::class)->group(function () {
    Route::get('/FAQ', 'showFAQ');
    Route::get('/aboutUs', 'showAboutUs');
    Route::get('/guide', 'showGuide');
});


// Admin
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin/deactivateUser', 'ban')->name('ban');
    Route::get('/admin/reactivateUser', 'unban')->name('unban');
    Route::get('/admin/upgrade', 'upgrade')->name('upgrade');
    Route::get('/admin/downgrade', 'downgrade')->name('downgrade');
    Route::get('/admin/users/{id}', 'showView')->name('viewProfile');
    Route::get('/admin', 'show')->name('admin');
    Route::get('/admin/users/{id}/edit', 'showAdminEdit')->name('showAdminEdit');
    Route::post('/admin/users/{id}/edit', 'adminEdit')->name('adminEdit');
});

// Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/api/auction/search', 'searchAuctions')->name('searchAuction');
    Route::get('/api/user/search', 'searchUsers')->name('searchUsers');
    Route::get('/api/auction/search/filter', 'filterData')->name('filter');
});


// Auction Creation
Route::controller(CreateAuctionController::class)->group(function () {
    Route::get('/api/auction/create', 'showCreateAuction')->name('showCreateAuction');
    Route::post('/api/auction/create', 'createAuction')->name('createdAuction');
});

// Auctions and Bids
Route::controller(AuctionController::class)->group(function () {
    Route::get('/auction/{id}', 'showAuction')->name('auction');
    Route::post('/auction/{id}/bid', 'addBid')->name('addBid');
    Route::post('/api/auction/remove', 'removeAuction');
    Route::get('/showAllAuctions/{section}', 'showAllAuctions')->name('allAuctions');
    Route::get('/auction/{id}/edit', 'showEditAuction')->name('auctionEdit');
    Route::post('/auction/{id}/edit', 'editAuction')->name('editAuction');
    Route::get('/{choice}/{user_id}', 'showHistory')->name('history');
});

// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::get('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

//File Storage
Route::post('/file/upload', [FileController::class, 'upload']);