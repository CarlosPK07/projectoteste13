<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Redirect;

// ✅ Página inicial redireciona para o login
Route::get('/', function () {
    return Redirect::route('login');
});

// ✅ Rota "home" (usada por Jetstream / Fortify)
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

// ✅ Dashboard (mantido)
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ✅ Grupo de rotas que requerem autenticação
Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');

    // ✅ Rota para listar utilizadores (somente autenticados)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});

require __DIR__.'/auth.php';
