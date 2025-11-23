<?php

use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\SelectorController;
use App\Http\Controllers\WelcomepageController;
use App\Http\Controllers\JoinpageController;
use App\Http\Controllers\RawpageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [WelcomepageController::class, 'index'])->name('page.welcome');
Route::get('/join', [JoinpageController::class, 'index'])->name('page.join');

Route::get('/report', [SelectorController::class, 'index'])->name('page.report.get');
Route::get('/report-result', [SelectorController::class, 'result'])->name('page.report.result');

Route::get('/raw', [RawpageController::class, 'index'])->name('page.raw');
Route::get('/apis/request', [ApiController::class, 'request'])->name('page.apis.request');
Route::post('/apis/request', [ApiController::class, 'requestStore'])
    ->middleware('throttle:8,1')
    ->name('apis.request.store');
Route::get('/apis/documentation', [ApiController::class, 'documentation'])->name('page.apis.documentation');
Route::get('/events/{event}/image', [EventController::class, 'image'])->name('events.image');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix("dashboard")->name('dashboard.')->group(function(){

        Route::get('/', [WelcomepageController::class, 'dashboard'])->name('index');

        Route::prefix('places')->name('places.')->group(function () {
            Route::get('/', [PlaceController::class,'index'])->name('index');
            Route::get('/create', [PlaceController::class,'create'])->name('create');
            Route::post('/store', [PlaceController::class,'store'])->name('store');
            Route::get('/edit/{id}', [PlaceController::class,'edit'])->name('edit');
            Route::patch('/update/{id}', [PlaceController::class,'update'])->name('update');
            Route::delete('/destroy/{id}', [PlaceController::class,'destroy'])->name('destroy');
        });

        Route::prefix("/events")->name('events.')->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('index');
            Route::get('/create', [EventController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [EventController::class, 'edit'])->name('edit');
            Route::post('/store', [EventController::class, 'store'])->name('store');
            Route::patch('/update/{id}', [EventController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [EventController::class, 'destroy'])->name('destroy');
            Route::get('/{event}/generate', [EventController::class, 'generate'])->name('generate');
            // 👉 NEW ENDPOINT: Generate + Post to X.com
            Route::post('/{event}/share-post', [EventController::class, 'shareAndPost'])->name('share-post');
        });

        Route::prefix("/reports")->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('dashboard.reports');
            Route::get('/list/{eventId}', [ReportController::class, 'listByEvent'])
                ->whereNumber('eventId')
                ->name('list');
            Route::get('/create', [ReportController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [ReportController::class, 'edit'])->name('edit');
            Route::post('/store', [ReportController::class, 'store'])->name('store');
            Route::patch('/update/{id}', [ReportController::class, 'update'])->name('update');
            Route::delete('/destroy/{id}', [ReportController::class, 'destroy'])->name('destroy');
            Route::get('/reports-list', [ReportController::class, 'getReports'])->name('api.reports.getreports');
        });

        Route::prefix('apis')->name('apis')->group(function () {
            Route::get('/', [ApiController::class, 'index'])->name('index');
            Route::post('/store', [ApiController::class, 'store'])->name('store');
            Route::delete('/{id}', [ApiController::class, 'destroy'])->name('destroy');
        });

        Route::prefix("/weather")->name('weather.')->group(function(){
            Route::post('/get', [WeatherController::class, 'getWeather'])->name('get');
        });
    });
});
