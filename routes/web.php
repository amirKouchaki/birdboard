<?php

use App\Http\Controllers\ProjectInvitationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTasksController;
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

Route::middleware('auth')->group(function () {
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/invitations',[ProjectInvitationController::class,'store'])->name('invitation.store');
    Route::group([
        'prefix' => 'projects/{project}/tasks',
        'as' => 'tasks.'
    ], function () {
        Route::post('/', [ProjectTasksController::class, 'store'])->name('store');
        Route::patch('/{task}', [ProjectTasksController::class, 'update'])->name('update');
    });
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});
//remember the function scoped


require __DIR__ . '/auth.php';
