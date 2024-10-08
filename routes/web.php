<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TextAnalyzerController;

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

Route::get('/', [TextAnalyzerController::class, 'index'])->name('index');
Route::post('/analyze', [TextAnalyzerController::class, 'analyze'])->name('text.analyze');
