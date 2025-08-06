<?php

use App\Livewire\Memories\Timeline;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('memories/timeline', Timeline::class)->name('memories.timeline');
});

require __DIR__ . '/auth.php';
