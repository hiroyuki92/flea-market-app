<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

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
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

Route::middleware('auth')->group(function () {
    Route::get('/mypage', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/sell', [ItemController::class, 'create'])->name('create');
    Route::post('/sell', [ItemController::class, 'store'])->name('item.store');
    Route::post('/item/{item_id}/toggle-like', [ItemController::class, 'toggleLike'])->name('item.toggleLike');
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comment.store');
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.index');
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'edit'])->name('address.edit');
    Route::put('/purchase/address/{item_id}', [PurchaseController::class, 'update'])->name('address.update');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
    // 購入成功時
    Route::get('/purchase/success/{item_id}', function ($item_id) {
    // 購入処理が成功した場合の処理
    return "購入が成功しました！ 商品ID: " . $item_id;
    })->name('purchase.success');

    // 購入キャンセル時
    Route::get('/purchase/cancel/{item_id}', function ($item_id) {
    // 購入がキャンセルされた場合の処理
    return "購入がキャンセルされました。 商品ID: " . $item_id;
    })->name('purchase.cancel');
});