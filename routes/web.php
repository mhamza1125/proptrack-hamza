<?php

declare(strict_types=1);

use App\Http\Controllers\InquiryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

// ── Public Routes ─────────────────────────────────────────────────────────────
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/listings/{id}', [PublicController::class, 'show'])->name('listings.show');

// Public inquiry submission (guests allowed)
Route::post('/inquiries', [InquiryController::class, 'store'])->name('inquiries.store');

// ── Authenticated Routes ───────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard — redirect to property management
    Route::get('/dashboard', function () {
        return redirect()->route('properties.index');
    })->name('dashboard');

    // Property management (admin sees all; agent sees own — scoped in service)
    Route::resource('properties', PropertyController::class);

    // Inquiry management
    Route::get('/inquiries', [InquiryController::class, 'index'])->name('inquiries.index');
    Route::get('/inquiries/{id}', [InquiryController::class, 'show'])->name('inquiries.show');

    // Status update — admin only (enforced in FormRequest + middleware)
    Route::patch('/inquiries/{inquiry}/status', [InquiryController::class, 'updateStatus'])
        ->middleware('role:admin')
        ->name('inquiries.update-status');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
