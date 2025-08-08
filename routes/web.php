<?php

use App\Livewire\Memories\Timeline;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('erinnerungen', Timeline\Index::class)->name('timelines.index');
    Route::get('erinnerungen/{timeline}', Timeline\Show::class)->name('timelines.show');
});

require __DIR__ . '/auth.php';
