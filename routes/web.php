<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Login\LoginContoller;
use App\Http\Controllers\Master\AreaController;
use App\Http\Controllers\Master\ImplementController;
use App\Http\Controllers\Master\LocationController;
use App\Http\Controllers\Master\MachinerieController;
use App\Http\Controllers\Master\ParkingController;
use App\Http\Controllers\Master\PersonController;
use App\Http\Controllers\Master\PersonTypeController;
use App\Http\Controllers\Master\StopController;
use App\Http\Controllers\Master\SubAreaController;
use App\Http\Controllers\Master\TaskController;
use App\Http\Controllers\Master\UnitMeasureController;
use App\Http\Controllers\Planning\AbilityController;
use App\Http\Controllers\Planning\ChangeController;
use App\Http\Controllers\Planning\PlanningController;
use App\Http\Controllers\Planning\RequestController;
use App\Http\Controllers\Reporte\ParadasController;
use App\Http\Controllers\Reporte\RptMaxhinerieController;
use App\Http\Controllers\Setting\PermisoController;
use App\Http\Controllers\Setting\RolController;
use App\Http\Controllers\Setting\SyncController;
use App\Http\Controllers\Setting\UserController;
use App\Http\Controllers\Supervisor\FiscalizadorController;
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

// Login
Route::middleware('guest')->group(function () {
    Route::get('/', [LoginContoller::class, 'index'])->name('login');
    Route::post('/', [LoginContoller::class, 'verify']);
});

Route::post('/logout', [LoginContoller::class, 'logout'])->name('logout');

Route::middleware('auth')->group( function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');

    // Menu::Usuarios
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/rols', [RolController::class, 'index']);
    Route::get('/users-permission', [PermisoController::class, 'index']);

    // Menu::Maestros
    Route::get('/master/areas', [AreaController::class, 'index']);
    Route::get('/master/implements', [ImplementController::class, 'index']);

    Route::get('/master/location', [LocationController::class, 'index']);
    Route::get('/master/machinerie', [MachinerieController::class, 'index']);
    Route::get('/master/parking', [ParkingController::class, 'index']);
    Route::get('/master/stops', [StopController::class, 'index']);
    Route::get('/master/person', [PersonController::class, 'index']);
    Route::get('/master/person-types', [PersonTypeController::class, 'index']);
    Route::get('/master/unit-measure', [UnitMeasureController::class, 'index']);
    Route::get('/master/tasks', [TaskController::class, 'index']);
    Route::get('/master/subarea', [SubAreaController::class, 'index']);

    // Menu::Planificación
    Route::get('/planning/ability', [AbilityController::class, 'index']);
    Route::get('/planning/request', [RequestController::class, 'index']);
    Route::get('/planning/machinerie', [PlanningController::class, 'index']);
    Route::get('/planning/changes', [ChangeController::class, 'index']);
    Route::get('/planning/approved', [ChangeController::class, 'index_approved']);
    Route::get('/planning/manage-online', [PlanningController::class, 'index_manage']);

    // Menu::Reportes
    Route::get('/reporte-paradas', [ParadasController::class, 'index']);
    Route::get('/reporte-maquinaria', [RptMaxhinerieController::class, 'index']);

    Route::get('/sync', [SyncController::class, 'index']);

    Route::get('/supervisor-fiscalizador', [FiscalizadorController::class, 'index']);
});
