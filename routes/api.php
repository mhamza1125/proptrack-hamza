<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\ListingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth — 5 req/min per IP
Route::prefix('auth')->middleware('throttle:api.auth')->group(function (): void {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum');
});

// Public listings — 60 req/min per IP
Route::prefix('listings')->middleware('throttle:api.listings')->group(function (): void {
    Route::get('/', [ListingController::class, 'index']);
    Route::get('{id}', [ListingController::class, 'show'])->whereNumber('id');
});

// Authenticated inquiry submission — 10 req/min per user/IP
Route::prefix('inquiries')
    ->middleware(['auth:sanctum', 'throttle:api.inquiries'])
    ->group(function (): void {
        Route::post('/', [InquiryController::class, 'store']);
    });
