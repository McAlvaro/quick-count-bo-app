<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    if (auth()->check() && auth()->user()->hasRole('super_admin')) {
        return redirect()->intended('/admin');
    }

    return redirect()->intended('/votos');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

use App\Livewire\ControlPanel;

Route::get('/control-panel', ControlPanel::class)->name('control-panel');

require __DIR__.'/auth.php';
