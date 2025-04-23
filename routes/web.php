<?php

use App\Http\Controllers\InfluencerController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstragramController;
use App\Http\Controllers\InstagramGetInstagramData;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});
Route::get('instagram',[InstragramController::class,'index'])->name('instagram.index');
Route::get('influencer',[InfluencerController::class,'index'])->name('influencer.index');
Route::get('instagramGetInstagramData',InstagramGetInstagramData::class)->name('instagram.getInstagramData');
require __DIR__.'/auth.php';
