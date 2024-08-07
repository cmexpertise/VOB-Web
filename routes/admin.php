<?php

use App\Http\Controllers\Admin\AffiliatesController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\DailyPrayerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HolyLandToursController;
use App\Http\Controllers\Admin\InspirationsController;
use App\Http\Controllers\Admin\MeditationsController;
use App\Http\Controllers\Admin\NightTimeStoriesController;
use App\Http\Controllers\Admin\PostcardController;
use App\Http\Controllers\Admin\PsalmsController;
use App\Http\Controllers\Admin\QuotesController;
use App\Http\Controllers\Admin\TravelSamaritanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['admin','auth','verified'])->group(function () {
    Route::get('/dashboard',[DashboardController::class,'index'])->name('admin.dashboard');
    Route::get('/user-analytics',[DashboardController::class,'user_analytics'])->name('user.analytics');
    Route::get('/user-analytics-datatable',[DashboardController::class,'user_analytics_datatable'])->name('user.analytics.datatable');
    Route::get('/notification',[DashboardController::class,'notification'])->name('admin.notification');
    Route::get('/smtp_setting',[DashboardController::class,'smtp_setting'])->name('admin.smtp_setting');
    Route::get('/mail_format',[DashboardController::class,'mail_format'])->name('admin.mail_format');
    Route::post('/add_mail_format',[DashboardController::class,'add_mail_format'])->name('admin.add_mail_format');
    Route::post('/add_update_smtp',[DashboardController::class,'add_update_smtp'])->name('admin.add_update_smtp');
});

Route::middleware(['admin','auth','verified'])->group(function (){
    Route::get('/books',[BookController::class,'index'])->name('books');
    Route::post('/books/add',[BookController::class,'add'])->name('books.add');
    Route::get('/books/delete',[BookController::class,'delete'])->name('books.delete');
    Route::post('/books/edit',[BookController::class,'edit'])->name('books.edit');
    Route::post('/books/update',[BookController::class,'update'])->name('books.update');
    Route::get('/books/datatable',[BookController::class,'datatable'])->name('books.datatable');
    Route::get('/books/export',[BookController::class,'export'])->name('books.export');
    Route::post('/books/exportBook',[BookController::class,'exportBooks'])->name('books.exportBook');
    Route::post('/books/importBook',[BookController::class,'importBooks'])->name('books.importBook');
});

Route::middleware(['admin','auth','verified'])->group(function (){
    Route::get('/chapters',[ChapterController::class,'index'])->name('chapters');
    Route::post('/chapters.add',[ChapterController::class,'add'])->name('chapters.add');
    Route::post('/chapters.edit',[ChapterController::class,'edit'])->name('chapters.edit');
    Route::post('/chapters.update',[ChapterController::class,'update'])->name('chapters.update');
    Route::get('/chapters/datatable',[ChapterController::class,'datatable'])->name('chapters.datatable');
    Route::get('/chapters/delete',[ChapterController::class,'delete'])->name('chapters.delete');
});

Route::middleware(['admin','auth','verified'])->group(function (){
    Route::get('/travel_samaritans',[TravelSamaritanController::class,'index'])->name('travel_samaritans');
    Route::post('/travel_samaritans/add',[TravelSamaritanController::class,'add'])->name('travel_samaritans.add');
    Route::post('/travel_samaritans/update',[TravelSamaritanController::class,'update'])->name('travel_samaritans.update');
    Route::post('/travel_samaritans/edit',[TravelSamaritanController::class,'edit'])->name('travel_samaritans.edit');
    Route::get('/travel_samaritans/delete',[TravelSamaritanController::class,'delete'])->name('travel_samaritans.delete');
    Route::get('/travel_samaritans/datatable',[TravelSamaritanController::class,'datatable'])->name('travel_samaritans.datatable');
    
});

Route::middleware(['admin','auth','verified'])->group(function (){
    Route::get('/daily_prayer',[DailyPrayerController::class,'index'])->name('daily_prayer');
    Route::post('/daily_prayer/add',[DailyPrayerController::class,'add'])->name('daily_prayer.add');
    Route::post('/daily_prayer/update',[DailyPrayerController::class,'update'])->name('daily_prayer.update');
    Route::post('/daily_prayer/edit',[DailyPrayerController::class,'edit'])->name('daily_prayer.edit');
    Route::get('/daily_prayer/delete',[DailyPrayerController::class,'delete'])->name('daily_prayer.delete');
    Route::get('/daily_prayer/datatable',[DailyPrayerController::class,'datatable'])->name('daily_prayer.datatable');
});

Route::middleware(['admin','auth','verified'])->group(function (){
    Route::get('/holylandtours',[HolyLandToursController::class,'index'])->name('holylandtours');
    Route::post('/holylandtours/add',[HolyLandToursController::class,'add'])->name('holylandtours.add');
    Route::post('/holylandtours/update',[HolyLandToursController::class,'update'])->name('holylandtours.update');
    Route::post('/holylandtours/edit',[HolyLandToursController::class,'edit'])->name('holylandtours.edit');
    Route::get('/holylandtours/delete',[HolyLandToursController::class,'delete'])->name('holylandtours.delete');
    Route::get('/holylandtours/datatable',[HolyLandToursController::class,'datatable'])->name('holylandtours.datatable');
});

Route::middleware(['admin','auth','verified'])->group(function (){
    Route::get('/inspirations',[InspirationsController::class,'index'])->name('inspirations');
    Route::post('/inspirations/add',[InspirationsController::class,'add'])->name('inspirations.add');
    Route::post('/inspirations/update',[InspirationsController::class,'update'])->name('inspirations.update');
    Route::post('/inspirations/update_video',[InspirationsController::class,'update_video'])->name('inspirations.update_video');
    Route::post('/inspirations/edit',[InspirationsController::class,'edit'])->name('inspirations.edit');
    Route::post('/inspirations/edit_video',[InspirationsController::class,'edit_video'])->name('inspirations.edit_video');
    Route::get('/inspirations/delete',[InspirationsController::class,'delete'])->name('inspirations.delete');
    Route::get('/inspirations/delete_video',[InspirationsController::class,'delete_video'])->name('inspirations.delete_video');
    Route::get('/inspirations/datatable',[InspirationsController::class,'datatable'])->name('inspirations.datatable');
    Route::get('/inspirations/add_video',[InspirationsController::class,'add_video'])->name('inspirations.add_video');
    Route::post('/inspirations/add_video',[InspirationsController::class,'insert_video'])->name('inspirations.add_video');
    Route::post('/inspirations/video_datatable',[InspirationsController::class,'video_datatable'])->name('inspirations.video_datatable');
});

Route::middleware(['admin','auth','verified'])->group(function (){
    Route::get('/nighttimestories',[NightTimeStoriesController::class,'index'])->name('nighttimestories');
    Route::post('/nighttimestories/add',[NightTimeStoriesController::class,'add'])->name('nighttimestories.add');
    Route::post('/nighttimestories/update',[NightTimeStoriesController::class,'update'])->name('nighttimestories.update');
    Route::post('/nighttimestories/update_video',[NightTimeStoriesController::class,'update_video'])->name('nighttimestories.update_video');
    Route::post('/nighttimestories/edit',[NightTimeStoriesController::class,'edit'])->name('nighttimestories.edit');
    Route::post('/nighttimestories/edit_video',[NightTimeStoriesController::class,'edit_video'])->name('nighttimestories.edit_video');
    Route::get('/nighttimestories/delete',[NightTimeStoriesController::class,'delete'])->name('nighttimestories.delete');
    Route::get('/nighttimestories/delete_video',[NightTimeStoriesController::class,'delete_video'])->name('nighttimestories.delete_video');
    Route::get('/nighttimestories/datatable',[NightTimeStoriesController::class,'datatable'])->name('nighttimestories.datatable');
    Route::get('/nighttimestories/add_video',[NightTimeStoriesController::class,'add_video'])->name('nighttimestories.add_video');
    Route::post('/nighttimestories/add_video',[NightTimeStoriesController::class,'insert_video'])->name('nighttimestories.add_video');
    Route::get('/nighttimestories/video_datatable',[NightTimeStoriesController::class,'video_datatable'])->name('nighttimestories.video_datatable');
});

Route::middleware(['admin','auth','verified'])->group(function (){
    Route::get('/psalms',[PsalmsController::class,'index'])->name('psalms');
    Route::post('/psalms/add',[PsalmsController::class,'add'])->name('psalms.add');
    Route::post('/psalms/update',[PsalmsController::class,'update'])->name('psalms.update');
    Route::post('/psalms/edit',[PsalmsController::class,'edit'])->name('psalms.edit');
    Route::get('/psalms/delete',[PsalmsController::class,'delete'])->name('psalms.delete');
    Route::get('/psalms/datatable',[PsalmsController::class,'datatable'])->name('psalms.datatable');
});

Route::middleware(['admin','auth','verified'])->group(function (){
    Route::get('/affiliates',[AffiliatesController::class,'index'])->name('affiliates');
    Route::post('/affiliates/add',[AffiliatesController::class,'add'])->name('affiliates.add');
    Route::post('/affiliates/update',[AffiliatesController::class,'update'])->name('affiliates.update');
    Route::post('/affiliates/edit',[AffiliatesController::class,'edit'])->name('affiliates.edit');
    Route::get('/affiliates/delete',[AffiliatesController::class,'delete'])->name('affiliates.delete');
    Route::get('/affiliates/datatable',[AffiliatesController::class,'datatable'])->name('affiliates.datatable');
});

Route::middleware(['admin','auth','verified'])->group(function (){
    Route::get('/quotes',[QuotesController::class,'index'])->name('quotes');
    Route::post('/quotes/add',[QuotesController::class,'add'])->name('quotes.add');
    Route::post('/quotes/update',[QuotesController::class,'update'])->name('quotes.update');
    Route::post('/quotes/edit',[QuotesController::class,'edit'])->name('quotes.edit');
    Route::get('/quotes/delete',[QuotesController::class,'delete'])->name('quotes.delete');
    Route::get('/quotes/add_quote',[QuotesController::class,'add_quote'])->name('quotes.add_quote');
    Route::post('/quotes/add_quotes',[QuotesController::class,'add_quotes'])->name('quotes.add_quotes');
    Route::get('/quotes/datatable',[QuotesController::class,'datatable'])->name('quotes.datatable');
    Route::get('/quotes/getquotes',[QuotesController::class,'getquotes'])->name('quotes.getquotes');
    Route::get('/quotes/quotes_delete',[QuotesController::class,'quotes_delete'])->name('quotes.quotes_delete');
    
});

Route::middleware(['admin','auth','verified'])->group(function (){
    Route::get('/postcards',[PostcardController::class,'index'])->name('postcards');
    Route::post('/postcards/add',[PostcardController::class,'add'])->name('postcards.add');
    Route::get('/postcards/delete',[PostcardController::class,'delete'])->name('postcards.delete');
    Route::get('/postcards/datatable',[PostcardController::class,'datatable'])->name('postcards.datatable');
    Route::get('/postcards/users',[PostcardController::class,'users'])->name('postcards.users');
    Route::get('/postcards/userdatatable',[PostcardController::class,'userdatatable'])->name('postcards.userdatatable');
    Route::post('/postcards/edit',[PostcardController::class,'edit'])->name('postcards.edit');
    Route::post('/postcards/update',[PostcardController::class,'update'])->name('postcards.update');
});

Route::middleware(['admin','auth','verified'])->group(function (){
    Route::get('/meditations',[MeditationsController::class,'index'])->name('meditations');
    Route::post('/meditations/add',[MeditationsController::class,'add'])->name('meditations.add');
    Route::post('/meditations/update',[MeditationsController::class,'update'])->name('meditations.update');
    Route::post('/meditations/edit',[MeditationsController::class,'edit'])->name('meditations.edit');
    Route::get('/meditations/delete',[MeditationsController::class,'delete'])->name('meditations.delete');
    Route::get('/meditations/datatable',[MeditationsController::class,'datatable'])->name('meditations.datatable');
});
