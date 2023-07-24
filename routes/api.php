<?php

use App\Http\Controllers\ComponentController;
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
use App\Http\Controllers\QueryController;
use App\Http\Controllers\Reporte\ParadasController;
use App\Http\Controllers\Reporte\RptMaxhinerieController;
use App\Http\Controllers\Setting\PermisoController;
use App\Http\Controllers\Setting\RolController;
use App\Http\Controllers\Setting\SyncController;
use App\Http\Controllers\Setting\UserController;
use App\Http\Controllers\Supervisor\FiscalizadorController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('guest')->group( function() {
    // Users
    Route::get('/user-list', [UserController::class, 'list']);
    Route::get('/user-create', [UserController::class, 'create']);
    Route::post('/user-save', [UserController::class, 'save']);
    Route::post('/user-edit', [UserController::class, 'edit']);
    Route::post('/user-update', [UserController::class, 'update']);
    Route::post('/user-update-pass', [UserController::class, 'update_pass']);
    Route::get('/user-list-permission', [PermisoController::class, 'list_permission']);
    Route::post('/permisos-save', [PermisoController::class, 'save']);

    // Components
    Route::post('/component-card-menu', [ComponentController::class, 'card_menu']);
    Route::post('/component-card-submenu', [ComponentController::class, 'card_submenu']);

    // Roles
    Route::get('/rol-list', [RolController::class, 'list']);
    Route::post('/rol-edit', [RolController::class, 'edit']);
    Route::post('/rol-edit-save', [RolController::class, 'update']);

    // Area
    Route::get('/area-list', [AreaController::class, 'list']);

    // Implemento
    Route::get('/implemento-list', [ImplementController::class, 'list']);
    Route::post('/implemento-state', [ImplementController::class, 'update_state']);
    Route::post('/implemento-get', [ImplementController::class, 'get']);
    Route::post('/implemento-update', [ImplementController::class, 'update']);

    // Ubicacion
    Route::get('/location-list', [LocationController::class, 'list']);
    Route::post('/location-get', [LocationController::class, 'edit']);
    Route::post('/location-update', [LocationController::class, 'update']);

    // Maquinaria
    Route::get('/maquinaria-list', [MachinerieController::class, 'list']);
    Route::post('/machinerie-state', [MachinerieController::class, 'update_state']);
    Route::post('/machinerie-get', [MachinerieController::class, 'edit']);
    Route::post('/machinerie-update', [MachinerieController::class, 'update']);

    // Parking
    Route::get('/parking-list', [ParkingController::class, 'list']);
    Route::get('/parking-new', [ParkingController::class, 'create']);
    Route::post('/parking-save', [ParkingController::class, 'save']);
    Route::post('/parking-get', [ParkingController::class, 'edit']);
    Route::post('/parking-update', [ParkingController::class, 'update']);

    // Stop's
    Route::get('/stop-list', [StopController::class, 'list']);
    Route::get('/stop-new', [StopController::class, 'create']);
    Route::post('/stop-save', [StopController::class, 'save']);
    Route::post('/stop-get', [StopController::class, 'edit']);
    Route::post('/stop-update', [StopController::class, 'update']);

    // Tipo Trabajador
    Route::get('/ttrabajador-list', [PersonTypeController::class, 'list']);

    // Trabajador
    Route::get('/trabajador-list', [PersonController::class, 'list']);
    Route::get('/trabajador-create', [PersonController::class, 'create']);
    Route::post('/trabajador-usave', [PersonController::class, 'save_user']);
    Route::post('/trabajador-save', [PersonController::class, 'save']);
    Route::post('/trabajador-edit', [PersonController::class, 'edit']);
    Route::post('/trabajador-update', [PersonController::class, 'update']);

    // Unidad Medida
    Route::get('/unitm-list', [UnitMeasureController::class, 'list']);
    Route::get('/unitm-new', [UnitMeasureController::class, 'create']);
    Route::post('/unitm-save', [UnitMeasureController::class, 'save']);
    Route::post('/unitm-get', [UnitMeasureController::class, 'edit']);
    Route::post('/unitm-update', [UnitMeasureController::class, 'update']);

    // Labores
    Route::get('/labores-list', [TaskController::class, 'list']);
    Route::post('/tasks-get', [TaskController::class, 'edit']);
    Route::post('/tasks-update', [TaskController::class, 'update']);

    // Planificacion
    Route::get('/operario-list', [AbilityController::class, 'list']);
    Route::get('/operario-create', [AbilityController::class, 'create']);
    Route::post('/operario-select', [AbilityController::class, 'get_person']);
    Route::post('/operario-save', [AbilityController::class, 'save']);
    Route::post('/operator-state', [AbilityController::class, 'update']);

    Route::post('/requerimiento-list', [RequestController::class, 'list']);
    Route::post('/requerimiento-list-table', [RequestController::class, 'listSltPerson']);          //==> Tabla selecte buscar requerimiento
    Route::post('/requerimiento-create', [RequestController::class, 'create']);
    Route::post('/requerimiento-save', [RequestController::class, 'save']);
    Route::post('/requerimiento-plan', [RequestController::class, 'create_request']); // lista requerimientos y agregar labor al requerimiento
    Route::post('/requerimiento-list-labor', [RequestController::class, 'list_request']);
    // Route::post('/requerimiento-list-labor', [RequestController::class, 'list_requerimiento']); // v2.0
    Route::post('/requerimiento-save-labor', [RequestController::class, 'save_task']);
    Route::post('/request-save-dtll', [RequestController::class, 'save_task_detail']);
    Route::post('/request-update-dtll', [RequestController::class, 'update_task_detail']);

    Route::post('/request-machinery-list', [PlanningController::class, 'list']);
    Route::get('/request-machinery-search', [PlanningController::class, 'search_widget']);
    Route::post('/request-machinery-save', [PlanningController::class, 'save']);
    Route::post('/request-search-operator', [PlanningController::class, 'search_operator']);
    Route::post('/request-save-operator', [PlanningController::class, 'save_operator']);
    Route::post('/request-cero-operator', [PlanningController::class, 'save_operator_cero']);

    Route::post('/request-manage-list', [PlanningController::class, 'list_manage']);
    Route::post('/request-manage-newlist', [PlanningController::class, 'newlist_manage']);

    Route::post('/change-list', [ChangeController::class, 'list']); // lista requerimientos de la semana6
    Route::post('/change-list-table', [ChangeController::class, 'listSltPerson']);
    Route::post('/change-plan', [ChangeController::class, 'create_request']); // lista requerimientos y agregar labor al requerimiento
    Route::post('/change-list-labor', [ChangeController::class, 'list_request']);
    Route::post('/change-save-dtll', [ChangeController::class, 'save_task_detail']);
    Route::post('/change-cancel-dtll', [ChangeController::class, 'cancel_task_detail']);
    Route::post('/change-update-dtll', [ChangeController::class, 'update_task_detail']);
    Route::get('/change-approved-search', [ChangeController::class, 'search_widget']);
    Route::post('/change-approved-list', [ChangeController::class, 'list_approved']);
    Route::post('/change-approved-list-js', [ChangeController::class, 'list_approved_js']);
    Route::post('/change-request-save', [ChangeController::class, 'save']);
    Route::post('/change-modal-body', [ChangeController::class, 'modalBody']);

    Route::get('/planificacion-list/{nro_semana}/{anio}', [PlanningController::class, 'plan_list']);
    Route::post('/planificacion-save-row', [PlanningController::class, 'save_row']);
    Route::post('/planificacion-list-tareos', [FiscalizadorController::class, 'list_tareos']);

    Route::get('/list-supervisores', [PlanningController::class, 'list_sup']);
    Route::get('/select-supervisor', [PlanningController::class, 'select_sup']);

    Route::get('/list-fecha-sync', [SyncController::class, 'list_date']);
    Route::post('/load-list-sync', [SyncController::class, 'list_sync']);
    Route::post('/load-list-sync-semana', [SyncController::class, 'list_semana']);
    Route::post('/migrate-nisira', [SyncController::class, 'migrate']);

    Route::get('/paradas-list', [ParadasController::class, 'list']);
    Route::post('/paradas-list-search', [ParadasController::class, 'list_search']);

    Route::get('/rptmaquinaria-list', [RptMaxhinerieController::class, 'list_maquinaria']);
    Route::post('/rptmaquinaria-list-search', [RptMaxhinerieController::class, 'list_search']);

    /// Mr. Enrique
    Route::get('/yo-enrique/{sem}', [QueryController::class, 'index']);

    /// Nuevos
    Route::post('/component-select-option', [ComponentController::class, 'select_option']);
    Route::post('/component-select-supervisor', [ComponentController::class, 'select_supervisor']);
    //Route::post('/planificacion-list-supervisor', [PlanningController::class, 'list_supervisor']);
    Route::post('/component-list-subarea', [ComponentController::class, 'table_subarea']);
    Route::post('/component-new-subarea', [ComponentController::class, 'new_subarea']);
    Route::post('/component-edit-subarea', [ComponentController::class, 'edit_subarea']);
    Route::post('/component-trabajador-subarea', [ComponentController::class, 'subarea_trabajador']);

    Route::post('/subarea-save', [SubAreaController::class, 'save']);
    Route::post('/subarea-update', [SubAreaController::class, 'update']);
    Route::post('/subarea-delete', [SubAreaController::class, 'delete']);
    Route::get('/planificacion-list-supervisor/{nro_semana}/{anio}/{person_id}', [PlanningController::class, 'list_supervisor']);
    Route::get('/planificacion-list-area/{nro_semana}/{anio}/{area_id}', [PlanningController::class, 'list_area']);
    Route::get('/planificacion-list-subarea/{nro_semana}/{anio}/{subarea_id}', [PlanningController::class, 'list_subarea']);

    Route::post('/request-delete', [RequestController::class, 'delete']);
    Route::post('/request-cancel-dtll', [RequestController::class, 'cancel_task_detail']);
});
