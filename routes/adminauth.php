<?php

use App\Http\Controllers\AdminAuth\AuthenticatedSessionController;
use App\Http\Controllers\AdminAuth\ConfirmablePasswordController;
use App\Http\Controllers\AdminAuth\EmailVerificationNotificationController;
use App\Http\Controllers\AdminAuth\EmailVerificationPromptController;
use App\Http\Controllers\AdminAuth\NewPasswordController;
use App\Http\Controllers\AdminAuth\PasswordController;
use App\Http\Controllers\AdminAuth\PasswordResetLinkController;
use App\Http\Controllers\AdminAuth\VerifyEmailController;
use App\Http\Controllers\AdminAuth\AdminDashboardController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest:admin')->group(function () {

    Route::get('admin/login', [AuthenticatedSessionController::class, 'create'])
                ->name('admin.login');

    Route::post('admin/login', [AuthenticatedSessionController::class, 'store']);


    Route::get('admin/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('admin.password.request');

    Route::post('admin/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('admin.password.email');

    Route::get('admin/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('admin.password.reset');

    Route::post('admin/reset-password', [NewPasswordController::class, 'store'])
                ->name('admin.password.store');
}); 

Route::middleware('auth:admin')->group(function () {

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    
    Route::get('admin/verify-email', EmailVerificationPromptController::class)
                ->name('admin.verification.notice');

    Route::get('admin/verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('admin.verification.verify');

    Route::post('admin/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('admin.verification.send');

    Route::get('admin/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('admin.password.confirm');

    Route::post('admin/confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('admin/password', [PasswordController::class, 'update'])->name('admin.password.update');

    Route::post('admin/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('admin.logout');

    Route::get('admin/get-users', [AdminDashboardController::class, 'getUsers']);

   Route::get('/admin/users', [AdminDashboardController::class, 'showUsersWithProducts'])->name('admin.users');
   Route::delete('/admin/users/{userId}/products/{productId}', [AdminDashboardController::class, 'deleteProduct'])->name('admin.product.delete');
   Route::delete('/admin/users/{userId}', [AdminDashboardController::class, 'deleteUser'])->name('admin.user.delete');


    Route::post('admin/categories', [AdminDashboardController::class, 'createCategory'])->name('admin.categories.create');
    Route::put('admin/categories/{categoryId}', [AdminDashboardController::class, 'updateCategory'])->name('admin.categories.update');
    Route::delete('admin/categories/{categoryId}', [AdminDashboardController::class, 'deleteCategory'])->name('admin.categories.delete');
    Route::get('/admin/categories', [AdminDashboardController::class, 'categories'])->name('admin.categories');
     Route::get('/admin/categories', [AdminDashboardController::class, 'listCategories'])->name('admin.categories');

});
