<?php

use App\Enums\RoleEnum;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CityManagerController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\GymManagerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\TrainingPackageController;
use App\Http\Controllers\TrainingSessionController;
use App\Http\Controllers\UserController;
use App\Models\Attendance;
use App\Models\City;
use App\Models\Coach;
use App\Models\Gym;
use App\Models\Revenue;
use App\Models\TrainingPackage;
use App\Models\TrainingSession;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

Route::redirect('/', 'login');

Route::get('/ban',function (){
   return view('ban');
});

Route::group(['middleware' => ['auth','logs-out-banned-user','forbid-banned-user']], function () {
    Route::resource('dashboard',DashboardController::class);
    Route::resource(Str::plural(RoleEnum::City_MANAGER), CityManagerController::class);
    Route::put('gym-managers/{gym_manager}/toggle-ban',[GymManagerController::class,'toggleBan'])->name('users.toggle-ban');
    Route::resource(Str::plural(RoleEnum::GYM_MANAGER), GymManagerController::class);
    Route::resource(Gym::getTableName(), GymController::class);
    Route::resource(City::getTableName(), CityController::class);
    Route::resource(User::getTableName(), UserController::class);
    Route::resource(Coach::getTableName(), CoachController::class);
    Route::put('/training-sessions/{training_session}/attend', [TrainingSessionController::class,'attend'])->name('training-sessions.attend');
    Route::resource(Str::slug(TrainingSession::getTableName()), TrainingSessionController::class);
    Route::resource(Str::slug(TrainingPackage::getTableName()), TrainingPackageController::class);
    Route::resource(Attendance::getTableName(), AttendanceController::class);
    Route::resource(Revenue::getTableName(), RevenueController::class);


    Route::resource(Str::slug(TrainingSession::getTableName()), TrainingSessionController::class);
    Route::resource(Str::slug(TrainingPackage::getTableName()), TrainingPackageController::class);
    Route::resource(Attendance::getTableName(), AttendanceController::class);
    Route::resource('payments',PaymentController::class)->only('index','store');
    Route::resource('home', HomeController::class);
});


Auth::routes();


