<?php

use App\Livewire;
use App\Livewire\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', Livewire\HomePage::class)->name('home');
Route::get('/categories', Livewire\CategoriesPage::class);

Route::get('/products', Livewire\ProductsPage::class);
Route::get('/products/{slug}', Livewire\ProductDetailPage::class);
Route::get('/cart', Livewire\CartPage::class);

Route::middleware('guest')->group(function () {
    Route::get('/login', Auth\LoginPage::class)->name('login');
    Route::get('/forgot', Auth\ForgotPasswordPage::class);
    Route::get('/register', Auth\RegisterPage::class);
    Route::get('/reset-password', Auth\ResetPasswordPage::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/checkout', Livewire\CheckoutPage::class);
    Route::get('/my-orders', Livewire\MyOrdersPage::class);
    Route::get('/my-orders/{order}', Livewire\MyOrderDetailPage::class);

    Route::get('/success', Livewire\SuccessPage::class);
    Route::get('/cancel', Livewire\CancelPage::class);

    Route::get('/logout', function () {
        auth()->logout();
        return redirect('/');
    });
});
