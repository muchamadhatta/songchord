<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\SongVersionController;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes (hanya bisa diakses guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
});

// Logout hanya untuk user yang sudah login
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Protected routes with role middleware
Route::middleware(['auth'])->group(function () {
    // Routes accessible by all authenticated users
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Admin only routes
    Route::middleware(['role:ADMIN'])->group(function () {
        //
    });

    // Contributor routes
    Route::middleware(['role:ADMIN,CONTRIBUTOR'])->group(function () {
        Route::resource('songs', SongController::class);
        
        // Song Versions routes
        Route::get('songs/{song}/versions', [SongVersionController::class, 'index'])->name('songs.versions.index');
        Route::get('songs/{song}/versions/create', [SongVersionController::class, 'create'])->name('songs.versions.create');
        Route::post('songs/{song}/versions', [SongVersionController::class, 'store'])->name('songs.versions.store');
        Route::get('songs/{song}/versions/{version}', [SongVersionController::class, 'show'])->name('songs.versions.show');
        Route::get('songs/{song}/versions/{version}/edit', [SongVersionController::class, 'edit'])->name('songs.versions.edit');
        Route::put('songs/{song}/versions/{version}', [SongVersionController::class, 'update'])->name('songs.versions.update');
        Route::delete('songs/{song}/versions/{version}', [SongVersionController::class, 'destroy'])->name('songs.versions.destroy');
        
        // Additional version actions
        Route::patch('songs/{song}/versions/{version}/set-default', [SongVersionController::class, 'setDefault'])->name('songs.versions.set-default');
        Route::post('songs/{song}/versions/{version}/duplicate', [SongVersionController::class, 'duplicate'])->name('songs.versions.duplicate');
        
        // AJAX API Routes for Structure Editing
        Route::post('songs/{song}/versions/{version}/sections', [SongVersionController::class, 'storeSection']);
        Route::patch('sections/{section}', [SongVersionController::class, 'updateSection']);
        Route::patch('sections/{section}/move', [SongVersionController::class, 'moveSection']);
        Route::post('sections/{section}/duplicate', [SongVersionController::class, 'duplicateSection']);
        Route::delete('sections/{section}', [SongVersionController::class, 'deleteSection']);
        
        Route::post('sections/{section}/lines', [SongVersionController::class, 'storeLine']);
        Route::patch('lines/{line}', [SongVersionController::class, 'updateLine']);
        Route::delete('lines/{line}', [SongVersionController::class, 'deleteLine']);
        
        Route::patch('lines/{line}/chords', [SongVersionController::class, 'updateChords']);
        Route::post('lines/{line}/chords', [SongVersionController::class, 'addChordPosition']);
        Route::patch('chord-positions/{chordPosition}', [SongVersionController::class, 'updateChordPosition']);
        Route::delete('chord-positions/{chordPosition}', [SongVersionController::class, 'deleteChordPosition']);
        
        // AJAX API routes for structure editing
        Route::post('songs/{song}/versions/{version}/sections', [SongVersionController::class, 'storeSection'])->name('songs.versions.sections.store');
        Route::patch('sections/{section}', [SongVersionController::class, 'updateSection'])->name('sections.update');
        Route::delete('sections/{section}', [SongVersionController::class, 'deleteSection'])->name('sections.destroy');
        
        Route::post('sections/{section}/lines', [SongVersionController::class, 'storeLine'])->name('sections.lines.store');
        Route::patch('lines/{line}', [SongVersionController::class, 'updateLine'])->name('lines.update');
        Route::delete('lines/{line}', [SongVersionController::class, 'deleteLine'])->name('lines.destroy');
        
        Route::patch('lines/{line}/chords', [SongVersionController::class, 'updateChords'])->name('lines.chords.update');
    });
});
