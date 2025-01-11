<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BlogTagController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\UploadTestController;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::prefix('blog')->group(function (){
        Route::get('tags',[BlogTagController::class,'index'])->name('blog-tags.index')->middleware('permission:blog-tags.view');
        Route::get('tags/create',[BlogTagController::class,'create'])->name('blog-tags.create')->middleware('permission:blog-tags.create');
        Route::post('tags',[BlogTagController::class,'store'])->name('blog-tags.store')->middleware('permission:blog-tags.create');
        Route::get('tags/{tag}/edit',[BlogTagController::class,'edit'])->name('blog-tags.edit')->middleware('permission:blog-tags.edit');
        Route::patch('tags/{tag}/update',[BlogTagController::class,'update'])->name('blog-tags.update')->middleware('permission:blog-tags.edit');
        Route::delete('tags/{tag}',[BlogTagController::class,'destroy'])->name('blog-tags.delete')->middleware('permission:blog-tags.delete');

        Route::get('categories',[BlogCategoryController::class,'index'])->name('blog-categories.index')->middleware('permission:blog-categories.view');
        Route::get('categories/create',[BlogCategoryController::class,'create'])->name('blog-categories.create')->middleware('permission:blog-categories.create');
        Route::post('categories',[BlogCategoryController::class,'store'])->name('blog-categories.store')->middleware('permission:blog-categories.create');
        Route::get('categories/{blogCategory}/edit',[BlogCategoryController::class,'edit'])->name('blog-categories.edit')->middleware('permission:blog-categories.edit');
        Route::patch('categories/{blogCategory}/update',[BlogCategoryController::class,'update'])->name('blog-categories.update')->middleware('permission:blog-categories.edit');
        Route::delete('categories/{blogCategory}',[BlogCategoryController::class,'destroy'])->name('blog-categories.delete')->middleware('permission:blog-categories.delete');
    });

    Route::get('test-upload',[UploadTestController::class,'index'])->name('upload-test.index');
    Route::post('test-upload',[UploadTestController::class,'store'])->name('upload-test.store');


    Route::get('roles',[RoleController::class,'index'])->name('roles.index')->middleware('permission:roles.view');
    Route::get('roles/create',[RoleController::class,'create'])->name('roles.create')->middleware('permission:roles.create');
    Route::post('roles',[RoleController::class,'store'])->name('roles.store')->middleware('permission:roles.create');
    Route::get('roles/{role}/edit',[RoleController::class,'edit'])->name('roles.edit')->middleware('permission:roles.edit');
    Route::patch('roles/{role}/update',[RoleController::class,'update'])->name('roles.update')->middleware('permission:roles.edit');
    Route::delete('roles/{role}',[RoleController::class,'destroy'])->name('roles.delete')->middleware('permission:roles.delete');


    Route::get('users',[UserController::class,'index'])->name('users.index')->middleware('permission:users.view');
    Route::delete('users/bulk-delete',[UserController::class,'bulkDestroy'])->name('users.bulk-delete');

    Route::delete('users/{user}',[UserController::class,'destroy'])->name('users.delete');
    Route::get('users/create',[UserController::class,'create'])->name('users.create');
    Route::get('users/{user}/edit',[UserController::class,'edit'])->name('users.edit');
    Route::patch('users/{user}/update',[UserController::class,'update'])->name('users.update');
    Route::post('users',[UserController::class,'store'])->name('users.store');

    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});