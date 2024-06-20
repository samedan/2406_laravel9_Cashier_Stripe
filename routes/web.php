<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Single Charge
Route::post('single-charge', [HomeController::class, 'singleCharge'])->name('single.charge');


Route::get('plans/create', [SubscriptionController::class, 'showPlanForm'])->name('plans.create');
// Create Post Subscription
Route::post('plans/store', [SubscriptionController::class, 'savePlan'])->name('plans.store');

Route::get('plans', function() {
    return view('stripe.plans');
});
