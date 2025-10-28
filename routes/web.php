<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\AttendanceController;

// Public route - Login page
Route::get('login', function () {
    return view('login');
})->name('login'); // Ensure named 'login' for Laravel auth redirects

// Redirect root '/' to attendance page if authenticated
Route::get('/', function () {
    return redirect()->route('attendance.index');
})->middleware('auth');

// Protected routes - only accessible if logged in
Route::middleware(['auth', 'verified'])->group(function () {

    // User profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Places CRUD routes
    Route::get('/places', [PlaceController::class, 'index'])->name('places.index');          // Show all places
    Route::get('/places/create', [PlaceController::class, 'create'])->name('places.create'); // Show create form
    Route::post('/places', [PlaceController::class, 'store'])->name('places.store');         // Handle form submission
    Route::get('/places/{place}/edit', [PlaceController::class, 'edit'])->name('places.edit');// Show edit form
    Route::put('/places/{place}', [PlaceController::class, 'update'])->name('places.update');// Handle update
    Route::delete('/places/{place}', [PlaceController::class, 'destroy'])->name('places.destroy'); // Delete

    // Attendance CRUD routes
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');

    // Attendance check-in/check-out routes
    Route::post('/attendance/checkin', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
    Route::post('/attendance/checkout/{attendance}', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');

    // Get user places
    Route::get('/attendance/user-places/{user}', [AttendanceController::class, 'getUserPlaces']);
});

// Auth routes from Breeze (login, register, etc.)
require __DIR__.'/auth.php';
