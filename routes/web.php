<?php

use App\Http\Controllers\InfluencerController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstagramGetInstagramData;
use App\Http\Controllers\InstagramMetricsController;
use App\Http\Controllers\SocialProfileController;

/*Route::get('/', function () {
    return view('welcome');
})->name('home');
*/
Route::get('/', [InfluencerController::class, 'index'])->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::get('instagram', [InstagramMetricsController::class, 'index'])->name('instagram.index');
Route::get('instagram/{id}', [InstagramMetricsController::class, 'show'])->name('instagram.show');
Route::get('influencer', [InfluencerController::class, 'index'])->name('influencer.index');
Route::get('influencer/create', [InfluencerController::class, 'create'])->name('influencer.create');
Route::post('influencer', [InfluencerController::class, 'store'])->name('influencer.store');
Route::get('socialprofile/{socialProfile}', [SocialProfileController::class, 'show'])->name('socialprofile.show');
Route::get('instagramGetInstagramData', InstagramGetInstagramData::class)->name('instagram.getInstagramData');

// Rutas para Twitch
Route::get('twitch', [App\Http\Controllers\TwitchController::class, 'index'])->name('twitch.index');
Route::get('twitch/{username}', [App\Http\Controllers\TwitchController::class, 'show'])->name('twitch.show');
Route::get('twitch/{username}/fetch', [App\Http\Controllers\TwitchController::class, 'fetch'])->name('twitch.fetch');

require __DIR__ . '/auth.php';
