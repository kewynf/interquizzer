<?php

use App\Http\Controllers\DiscordController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ProfileController;
use App\Models\Role;
use App\Models\User;
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
    return ["message" => "Hello World"];
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/test', function () {
        return DiscordController::getGuildMembers('1047168666732605492');
    });

    Route::controller(ExamController::class)
        ->name('exam.')
        ->prefix('exam')
        ->group(function () {
            Route::get('/', 'create')->name('create');
            Route::post('/', 'generate')->name('generate');
            Route::get('/{exam}', 'renderExam')->name('render');
            Route::post('/{exam}/observer', 'addObserver')->name('addObserver');
            Route::get('/{exam}/discord/create', 'createDiscordChannel')->name('discord.create');
            Route::get('/{exam}/discord/delete', 'deleteDiscordChannel')->name('discord.delete');
            Route::get('/{exam}/start', 'start')->name('start');
            Route::get('/{exam}/end', 'end')->name('end');
            Route::get('/{exam}/during/{step}', 'during')->name('during');
            Route::get('/{exam}/during/{step}/next', 'nextStep')->name('nextStep');
            Route::get('/{exam}/during/{step}/previous', 'previousStep')->name('previousStep');
        });


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
