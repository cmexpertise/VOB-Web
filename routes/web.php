<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CronController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\WebController;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $user = Auth::user();
    if(isset($user) && !empty($user)){
        return redirect()->intended(RouteServiceProvider::HOME);
    }else{
        return redirect()->route('home');
        // return redirect()->route('login');
        // return view('welcome');
    }
});

Route::get('/privacy', function () {
    return view('privacy');
});
Route::get('/terms', function () {
    return view('terms');
});

Route::get('/about-us', function () {
    return view('about-us');
});
Route::get('/contact-us', function () {
    return view('contactus');
})->name('contact-us');

Route::get('/home', [WebController::class, 'index'])->name('home');
Route::get('/book_of_the_bible', [WebController::class, 'book_of_the_bible'])->name('book_of_the_bible');
Route::get('/audio_books', [WebController::class, 'audio_books'])->name('audio_books');
Route::get('/travelSamaritan', [WebController::class, 'travelSamaritan'])->name('travelSamaritan');
Route::get('/subscription_plan', [WebController::class, 'subscription_plan'])->name('subscription_plan');
Route::get('/signin', [WebController::class, 'signin'])->name('signin');
Route::get('/allChapters', [WebController::class, 'allChapters'])->name('allChapters');
Route::get('/allAudios', [WebController::class, 'allAudios'])->name('allAudios');
Route::get('/travelList', [WebController::class, 'travelList'])->name('travelList');
Route::get('/nighttimestoryList', [WebController::class, 'nighttimestoryList'])->name('nighttimestoryList');
Route::get('/broadcastList', [WebController::class, 'broadcastList'])->name('broadcastList');
Route::get('/meditationList', [WebController::class, 'meditationList'])->name('meditationList');
Route::get('/download_app', [WebController::class, 'download_app'])->name('download_app');
Route::post('/payfees', [WebController::class, 'payfees'])->name('payfees');
Route::post('/charge', [WebController::class, 'charge'])->name('charge');
Route::post('/change_language', [WebController::class, 'change_language'])->name('change_language');
Route::post('/add_contact_us', [WebController::class, 'add_contact_us'])->name('add_contact_us');

Route::get('/continue_watching', [CronController::class, 'getContinueWatching']);
Route::get('/change_extensions', [CronController::class, 'change_extensions']);
Route::post('/send_notification', [CronController::class, 'send_notification'])->name('cron.send_notification');
Route::get('/send_mail', [CronController::class, 'send_mail'])->name('cron.send_mail');
Route::get('/check', [CronController::class, 'check'])->name('cron.check');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth','admin','verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';