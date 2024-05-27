<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Frontend\SocialAuthController;
use App\Http\Livewire\Frontend\Pages\AccountVerify;
use App\Http\Livewire\Frontend\Pages\Address;
use App\Http\Livewire\Frontend\Pages\Cart;
use App\Http\Livewire\Frontend\Pages\ChangePassword;
use App\Http\Livewire\Frontend\Pages\Contact;
use App\Http\Livewire\Frontend\Pages\FAQ;
use App\Http\Livewire\Frontend\Pages\Favorite;
use App\Http\Livewire\Frontend\Pages\ForgotPassword;
use App\Http\Livewire\Frontend\Pages\Home;
use App\Http\Livewire\Frontend\Pages\Login;
use App\Http\Livewire\Frontend\Pages\Menu;
use App\Http\Livewire\Frontend\Pages\OrderHistory;
use App\Http\Livewire\Frontend\Pages\OrderHistoryDetails;
use App\Http\Livewire\Frontend\Pages\Page;
use App\Http\Livewire\Frontend\Pages\PaymentCallback;
use App\Http\Livewire\Frontend\Pages\PhoneVerify;
use App\Http\Livewire\Frontend\Pages\PopularMenu;
use App\Http\Livewire\Frontend\Pages\Profile;
use App\Http\Livewire\Frontend\Pages\ProfileEdit;
use App\Http\Livewire\Frontend\Pages\Register;
use App\Http\Livewire\Frontend\Pages\Reservation;
use App\Http\Livewire\Frontend\Pages\ReservationBooked;
use App\Http\Livewire\Frontend\Pages\ReservationConfirmation;
use App\Http\Livewire\Frontend\Pages\ResetPassword;
use App\Http\Livewire\Frontend\Pages\Search;
use Illuminate\Support\Facades\Route;

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

Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
    Route::get('forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('reset-password/{token}', ResetPassword::class)->name('password.reset');
});

Route::get('/', Home::class)->name('home');
Route::get('cart', Cart::class)->name('cart');
Route::get('menu', Menu::class)->name('menu');
Route::get('popular/menu', PopularMenu::class)->name('popular.menu');
Route::get('search', Search::class)->name('search.menu');

Route::get('reservation', Reservation::class)->name('reservation');
Route::get('reservation/confirmation', ReservationConfirmation::class)->name('reservation.confirmation');
Route::get('reservation/booked', ReservationBooked::class)->name('reservation.booked');

Route::get('payment/{status}/{trx_id}', PaymentCallback::class)->name('payment.callback');
Route::get('contact', Contact::class)->name('contact');
Route::get('faq', FAQ::class)->name('faq');

Route::middleware(['auth'])->group(function () {
    Route::get('account-verify', AccountVerify::class)->name('verification.notice');
    Route::get('phone-verify', PhoneVerify::class)->name('phone.verify');
    Route::get('favorite', Favorite::class)->name('favorite');
    Route::middleware(['verified'])->group(function () {
        Route::get('profile', Profile::class)->name('profile');
        Route::get('profile/edit', ProfileEdit::class)->name('profile.edit');
        Route::get('change-password', ChangePassword::class)->name('change-password');
        Route::get('address', Address::class)->name('address');
        Route::get('order-history', OrderHistory::class)->name('order');
        Route::get('order-history/{invoice}', OrderHistoryDetails::class)->name('order.details');
    });
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::get('social/{driver}/login', [SocialAuthController::class, 'index'])->name('social.login');
Route::get('social/{driver}/callback', [SocialAuthController::class, 'handleCallback'])->name('social.login.callback');

require __DIR__.'/auth.php';

Route::get('/{slug}', Page::class)->name('page.details');
