<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\welcomeController;
use App\Http\Controllers\DashboardController;

Route::get('/', [welcomeController::class, 'index'])->name('welcome');




Route::middleware(['auth', 'verified'])->group(function () {
    // Routes khusus member
    Route::middleware(['role:member'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('member.dashboard');
            Route::view('/loans', 'member.books')
            ->name('member.books');

            Route::view('/books', 'member.book-management')
            ->name('member.book-management');
            Route::view('/profile', 'profile')
            ->name('profile.edit');
            Route::get('/payment/{id}', [App\Http\Controllers\PaymentController::class, 'show'])->name('payment.show');
    });

    // Routes khusus admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::view('/admin/users', 'admin.users')
            ->name('admin.users');

        Route::view('/admin/books', 'admin.admin-book-management')->name('admin.admin-book-management');
        Route::view('/admin/loans', 'admin.admin-book-loan')->name('admin.admin-book-loan');
        Route::view('/admin/categories', 'admin.admin-categories')->name('admin.admin-categories');
    });
    // Rout dan fungsi untuk logout

Route::post('/logout', function () {
    Auth::guard('web')->logout();
    Session::invalidate();
    Session::regenerateToken();

    return redirect('/')->with('status', 'You have been logged out.');
})->name('logout');

});
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

require __DIR__.'/auth.php';
