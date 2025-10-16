<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Redirect;

// ✅ Rota "home" necessária para evitar erro de rota indefinida
Route::get('/home', function () {
    return redirect()->route('dashboard');
})->name('home');

// ✅ Ao acessar a raiz do site, o utilizador será redirecionado ao login
Route::get('/', function () {
    return Redirect::route('login');
});

// ✅ Dashboard (mantido como está)
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

    // ✅ Rota para listar os utilizadores (somente autenticados)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});

require __DIR__.'/auth.php';
