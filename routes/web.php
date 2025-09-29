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
Route::post('/report', [SelectorController::class, 'report'])->name('page.report.post');
Route::get('/raw', [RawpageController::class, 'index'])->name('page.raw');
Route::get('/apis/request', [ApiController::class, 'request'])->name('page.apis.request');
Route::post('/apis/request', [ApiController::class, 'requestStore'])
    ->middleware('throttle:8,1')
    ->name('apis.request.store');
Route::get('/apis/documentation', [ApiController::class, 'documentation'])->name('page.apis.documentation');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix("dashboard")->group(function(){

        Route::get('/', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::prefix("/places")->group(function () {
            Route::get('/', [PlaceController::class, 'index'])->name('dashboard.places');
            Route::get('/create', [PlaceController::class, 'create'])->name('dashboard.places.create');
            Route::get('/edit/{id}', [PlaceController::class, 'edit'])->name('dashboard.places.edit');
            Route::post('/store', [PlaceController::class, 'store'])->name('dashboard.places.store');
            Route::patch('/update/{id}', [PlaceController::class, 'update'])->name('dashboard.places.update');
            Route::delete('/destroy/{id}', [PlaceController::class, 'destroy'])->name('dashboard.places.destroy');
            Route::get('/places-list', [PlaceController::class, 'getPlaces'])->name('api.places.getplaces');
        });

        Route::prefix("/events")->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('dashboard.events');
            Route::get('/create', [EventController::class, 'create'])->name('dashboard.events.create');
            Route::get('/edit/{id}', [EventController::class, 'edit'])->name('dashboard.events.edit');
            Route::post('/store', [EventController::class, 'store'])->name('dashboard.events.store');
            Route::patch('/update/{id}', [EventController::class, 'update'])->name('dashboard.events.update');
            Route::delete('/destroy/{id}', [EventController::class, 'destroy'])->name('dashboard.events.destroy');
            Route::get('/events-list', [EventController::class, 'getEvents'])->name('api.events.getevents');
        });

        Route::prefix("/reports")->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('dashboard.reports');
            Route::get('/create', [ReportController::class, 'create'])->name('dashboard.reports.create');
            Route::get('/edit/{id}', [ReportController::class, 'edit'])->name('dashboard.reports.edit');
            Route::post('/store', [ReportController::class, 'store'])->name('dashboard.reports.store');
            Route::patch('/update/{id}', [ReportController::class, 'update'])->name('dashboard.reports.update');
            Route::delete('/destroy/{id}', [ReportController::class, 'destroy'])->name('dashboard.reports.destroy');
            Route::get('/reports-list', [ReportController::class, 'getReports'])->name('api.reports.getreports');
        });

        Route::prefix('apis')->name('dashboard.apis.')->group(function () {
            Route::get('/', [ApiController::class, 'index'])->name('index');
            Route::post('/store', [ApiController::class, 'store'])->name('store');
            Route::delete('/{id}', [ApiController::class, 'destroy'])->name('destroy');
        });

        Route::prefix("/weather")->group(function(){
            Route::post('/get', [WeatherController::class, 'getWeather'])->name('dashboard.weather.get');
        });
    });
});
