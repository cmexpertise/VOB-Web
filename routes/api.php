<?php
use App\Http\Controllers\Api\AuthenticationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['namespace' => 'Api', 'prefix' => 'v1',], function () {
    
    Route::post('login', [AuthenticationController::class, 'store'])->name('login');
    Route::post('signup', [UserController::class, 'signup'])->name('signup');
    Route::post('check_email', [UserController::class, 'check_email']);
});

Route::group(['namespace' => 'Api', 'prefix' => 'v1', 'middleware' => ['json.response', 'auth:api']], function () {
    Route::post('logout', [AuthenticationController::class, 'destroy']);
    Route::post('home', [HomeController::class, 'index'])->name('home');
    Route::post('last_played', [HomeController::class, 'last_played']);
    Route::post('audios', [HomeController::class, 'allAudios'])->name('audios');
    Route::post('videos', [HomeController::class, 'allVideos'])->name('videos');
    Route::post('quotes', [HomeController::class, 'allQuotes'])->name('quotes');
    Route::post('travel_samaritans', [HomeController::class, 'allTravelSamaritan']);
    Route::post('update_profile', [HomeController::class, 'updateProfile']);
    Route::post('add_postcard', [HomeController::class,'add_postcard']);
    Route::post('delete_postcard', [HomeController::class,'delete_postcard']);
    Route::post('view_postcard', [HomeController::class,'view_postcard']);
    Route::post('add_bookmark', [HomeController::class,'add_bookmark']);
    Route::post('remove_bookmark', [HomeController::class,'remove_bookmark']);
    Route::post('view_bookmark', [HomeController::class,'new_bookmark']);
    Route::post('add_subscription', [AuthenticationController::class,'add_subscription']);    
    Route::post('duration', [HomeController::class,'duration']);    
    Route::post('check_subscription', [HomeController::class,'check_subscription']);    
    Route::post('new_duration', [HomeController::class,'new_duration']);    
});