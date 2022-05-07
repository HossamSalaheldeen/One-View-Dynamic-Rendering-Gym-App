<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CoachController;
use App\Http\Controllers\Api\GymController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TrainingPackageController;
use App\Http\Controllers\Api\TrainingSessionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserTrainingSessionController;
use App\Http\Controllers\Api\YearController;
use App\Models\City;
use App\Models\Coach;
use App\Models\Gym;
use App\Models\TrainingPackage;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('register', 'register')->name('register');
    Route::post('login', 'login')->name('login');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('profile', ProfileController::class)->name('profile');
    Route::put('/training-sessions/{training_session}/attend', [TrainingSessionController::class,'attend']);
    Route::get('/training-sessions/remaining', [TrainingSessionController::class,'remaining']);
    Route::get('/training-sessions/history', [TrainingSessionController::class,'history']);
    Route::post('logout', [AuthController::class,'logout'])->name('logout');
});

Route::get(City::getTableName(), CityController::class);
Route::get(Gym::getTableName(), GymController::class);
Route::get(Coach::getTableName(), CoachController::class);
Route::get(User::getTableName(), UserController::class);

Route::get(Str::slug(TrainingSession::getTableName()), [TrainingSessionController::class,'index']);
Route::get(Str::slug(TrainingPackage::getTableName()), TrainingPackageController::class);


//Route::apiResource(User::getTableName().'.'.Str::slug(TrainingSession::getTableName()), UserTrainingSessionController::class);
//Route::put('/users/{user}/training-sessions/{training_session}/attend', [UserTrainingSessionController::class,'attend'])->name('users.training-sessions.attend');


Route::get('years', YearController::class);
