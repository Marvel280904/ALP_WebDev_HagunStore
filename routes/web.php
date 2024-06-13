<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DetailProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CartController;
use App\Http\AuthController;
use App\Http\Controllers\WishlistController;



Route::get('/', function () {
    return view('Home');
})->name('Home');

// Route::get('/Login', function () {
//     return view('Login');
// })->name('Login');

// Route::get('/Register', function () {
//     return view('Register');
// })->name('Register');


Route::get('/Wishlist', function () {
    return view('Wishlist');
})->name('Wishlist');

Route::get('/Admin', function () {
    return view('Admin');
})->name('Admin');

Route::get('/Register', [RegisterController::class, 'index'])->name('Register');
Route::post('/Register', [RegisterController::class, 'store']);
Route::get('/Login', [LoginController::class, 'index'])->name('Login');
Route::Post('/Login', [LoginController::class, 'store']);
Route::get('/Logout', [LoginController::class, 'logout']);
Route::post('/Logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/forgot', [LoginController::class, 'showForgot']);
Route::post('/forgot', [LoginController::class, 'forgotPassword']);
Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');
// Route::post('/Login', [LoginController::class, 'PostLogin'])->name('Login');
// Route::post('/Register', [LoginController::class, 'PostRegister']);
// Route::post('/Home', [LoginController::class, 'PostLogout']);

Route::get('/Products', [ProductController::class, 'index'])->name('Product');
Route::get('/Product/{id}', [DetailProductController::class, 'show'])->name('ProductDetail');


Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity']);
Route::post('/cart/delete-item', [CartController::class,'deleteItem'])->name('cart.delete-item');

Route::delete('/cart/{id}', 'CartController@remove')->name('cart.remove');


Route::post('/wishlist/add', [WishlistController::class, 'addToWish'])->name('wish.add');
Route::get('/wishlist', [WishlistController::class, 'showWish'])->name('wish.show');


// Route::get("/login", [AuthController::class,"ShowLogin"]);
// Route::post("/login",[AuthController::class,"PostLogin"])->name("PostLogin");
// Route::get("/logout",[AuthController::class,"PostLogout"])->name("logout");
// Route::get("/registration", [AuthController::class,"ShowCreateAcc"]);
// Route::post("/registration",[AuthController::class,"PostRegister"])->name("PostRegister");
// Route::get("/forgot-password", [AuthController::class,"ShowForgotPass"]);
// Route::post("/forgot-password-act",[AuthController::class,"PostForgotPass"])->name("forgotpasswordact");



