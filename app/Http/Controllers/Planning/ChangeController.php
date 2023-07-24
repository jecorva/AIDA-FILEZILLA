<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use App\Models\CategoriaImplemento;
use App\Models\CategoriaMaquinaria;
use App\Models\Implement;
use App\Models\Location;
use App\Models\Machinerie;
use App\Models\MenuItem;
use App\Models\Person;
use App\Models\Request as ModelsRequest;
use App\Models\RequestDetail;
use App\Models\RequestDetailTask;
use App\Models\Task;
use App\Models\TaskImplement;
use App\Models\TaskLocation;
use App\Models\TaskMachinery;
use App\Models\TaskSupervisor;
use App\Models\Turno;
use App\Models\UnitMeasure;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ChangeController extends Controller
{
    //
    public function index()
    {
        $id = Crypt::encryptString(Auth::user()->id);
        // item_id = 14 => ID Item Cambios
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 14)
            ->get();

        return view('planificacion.change.index')
                ->with('menu', $menu)
                ->with('id_user', $id);
    }

    public function list(Request $request)
    {
        $id_user = Crypt::decryptString($request->key);
        try{
            $persons = Person::select(
                'people.id as person_id',
                'users.id as user_id', 'users.nombres', 'users.apel_pat', 'users.apel_mat'
            )   ->join('users', 'users.id', '=', 'people.user_id')
                ->where('users.rol_id', 3) // Rol: Supervisor
                ->where('people.typeperson_id', 5) // Tipo de persona: Supervisor - Requerimientos
                ->orderBy('users.apel_pat')
                ->get();

        }catch(Exception $e) {
            $persons = "302";
        }

        return view('planificacion.change.components.table')
            ->with('id_user', $id_user)
            ->with('supervisores', $persons);
    }

    public function listSltPerson(Request $request)
    {
        $person_id = $request->id_trabajador;
        try {
            $response = ModelsRequest::select(
                'requests.id', 'requests.nro_semana', 'requests.date_inicio', 'requests.date_fin',
                'users.dni', 'users.nombres', 'users.apel_pat', 'users.apel_mat',
            )   ->join('people', 'people.id', '=', 'requests.person_id')
                ->join('users', 'users.id', '=', 'people.user_id')
                ->where('people.id', intval($person_id))
                ->orderBy('requests.created_at', 'DESC')
                ->get();

        }catch(Exception $e) {
            return $response = "302";
        }

        return view('planificacion.change.components.table_select')
                ->with('requerimientos', $response);
    }

    public function create_request(Request $request)
    {
        $id_planificacion = $request->id_requerimiento;
        $status = "";

        try {
            $labores = Task::all();
            $turnos = Turno::all();
            $um = UnitMeasure::all();
            // $dtll_request = ModelsRequest::all()->where('id', intval($id_planificacion));
            $dtll_request = DB::select("SELECT * FROM requests WHERE id='$id_planificacion'");
        } catch (Exception $e) {
            $status = "302";
        }

        return view('planificacion.change.components.request')
            ->with('labores', $labores)
            ->with('turnos', $turnos)
            ->with('umedidas', $um)
            ->with('dtll_plan', $dtll_request[0]);
    }

    public function list_request(Request $request)
    {
        $id_planificacion = $request->id_planificacion;

        try {
            $dtll_tasks = RequestDetail::select(
                'request_details.id as requestdetail_id',
                'tasks.id as task_id', 'tasks.nombre as labor',
                'requests.date_inicio',
                'turnos.nombre as turno',
                'unit_measures.siglas'
            )   ->join('requests', 'requests.id', '=', 'request_details.request_id')
                ->join('tasks', 'tasks.id', '=', 'request_details.task_id')
                ->join('turnos', 'turnos.id', '=', 'request_details.turno_id')
                ->join('unit_measures', 'unit_measures.id', '=', 'request_details.um_id')
                ->where('requests.id', intval($id_planificacion))
                ->orderBy('request_details.created_at', 'DESC')
                ->orderBy('request_details.task_id', 'ASC')
                ->get();
            //$dtll_request = ModelsRequest::all()->where('id', intval($id_planificacion)); // Tabla requests
            $dtll_request = DB::select("SELECT * FROM requests WHERE id='$id_planificacion'");
            $locations     = Location::all()->sortBy('nombre');
            // $implements    = Implement::all()->sortBy('nombre');
            $implements = CategoriaImplemento::all()->sortBy('nombre');
            $maquinarias = CategoriaMaquinaria::all()->sortBy('nombre');

        } catch (Exception $e) {
            $status = "302";
        }

        return view('planificacion.change.components.request_detail')
            ->with('locations', $locations)
            ->with('implements', $implements)
            ->with('maquinarias', $maquinarias)
            ->with('dtll_plan', $dtll_request[0])
            ->with('person_id', $request->user_id)
            ->with('dtll_labores', $dtll_tasks);
    }

    public function save_task_detail(Request $request)
    {
        $implementIds = $request->implementIds;
        $locationIds = $request->locationIds;
        $maquinariaIds = $request->maquinariaIds;
        $avanceIds = $request->inputIds;
        $planLaborId = $request->planLaborId;
        // $dateCopys = $request->copys;
        $hours = $request->hours;
        $day = $request->day;
        $progress = $request->progress;
        $update = $request->update;
        $nro_maquinas = $request->nroMaq;
        $responsableId = $request->responsibleId;

        $fecha = new DateTime($day);
        $day = $fecha->format('Y-m-d');
        $response = "102";

        try{
            $rdt_id = $update;

            $tb_rdt = new RequestDetailTask();
            $tb_rdt->requestdetail_id = $planLaborId;
            $tb_rdt->avance = $progress;
            $tb_rdt->dia = $day;
            $tb_rdt->nro_maquinas = $nro_maquinas;
            $tb_rdt->horas = $hours;
            $tb_rdt->changes = 2;
            $tb_rdt->change_msg = $request->msg;
            $tb_rdt->id_changes = $rdt_id;
            $tb_rdt->datetime_inicio = date('Y-m-d H:i:s');
            $tb_rdt->datetime_fin = date('Y-m-d H:i:s');
            $tb_rdt->created_at = date('Y-m-d H:i:s');
            $tb_rdt->updated_at = date('Y-m-d H:i:s');
            $tb_rdt->save();

            $updateRequest = RequestDetailTask::findOrFail($rdt_id);
            $updateRequest->ischange = 'S';
            $updateRequest->updated_at = date('Y-m-d H:i:s');
            $updateRequest->save();

            $query = RequestDetailTask::where('request_detail_tasks.dia', '=', $day)
                                            ->where('request_detail_tasks.requestdetail_id', '=', $planLaborId)
                                            ->where('request_detail_tasks.ischange', '=', 'N')
                                            ->get();
            json_encode($query);
            $laborPlanDtllId = $query[0]->id;

            $isCorrect = TaskLocation::where('requestdetailtask_id', intval($laborPlanDtllId))
                                ->delete();

            $index = 0;
            foreach ($locationIds as $locationId) {
                $laborLocation = new TaskLocation();
                $laborLocation->location_id = $locationId;
                $laborLocation->avance_plan = $avanceIds[$index];
                $laborLocation->requestdetailtask_id = $laborPlanDtllId;
                $laborLocation->created_at = date('Y-m-d H:i:s');
                $laborLocation->updated_at = date('Y-m-d H:i:s');
                $laborLocation->save();
                $index++;
            }

            $isCorrect = TaskImplement::where('requestdetailtask_id', intval($laborPlanDtllId))
                                        ->delete();

            foreach ($implementIds as $implementId) {
                $laborImplement = new TaskImplement();
                $laborImplement->cat_implemento_id = $implementId;
                $laborImplement->requestdetailtask_id = $laborPlanDtllId;
                $laborImplement->created_at = date('Y-m-d H:i:s');
                $laborImplement->updated_at = date('Y-m-d H:i:s');
                $laborImplement->save();
            }

            $isCorrect = TaskMachinery::where('requestdetailtask_id', intval($laborPlanDtllId))
                                                ->delete();

            foreach ($maquinariaIds as $maquinariaId) {
                $laborMaquinaria = new TaskMachinery();
                $laborMaquinaria->cat_maquinaria_id = $maquinariaId;
                $laborMaquinaria->requestdetailtask_id = $laborPlanDtllId;
                $laborMaquinaria->created_at = date('Y-m-d H:i:s');
                $laborMaquinaria->updated_at = date('Y-m-d H:i:s');
                $laborMaquinaria->save();
            }
            // Comentar para no guardar el supervisor
            $isCorrect = TaskSupervisor::where('requestdetailtask_id', intval($laborPlanDtllId))
                                        ->delete();

            $laborSupervisor = new TaskSupervisor();
            $laborSupervisor->requestdetailtask_id = $laborPlanDtllId;
            $laborSupervisor->person_id = $responsableId;
            $laborSupervisor->created_at = date('Y-m-d H:i:s');
            $laborSupervisor->updated_at = date('Y-m-d H:i:s');
            $laborSupervisor->save();

            $response = "402";

        }catch(Exception $e) {
            $response = $e->getMessage();
        }

        return $response;
    }

    public function update_task_detail(Request $request)
    {
        $day = $request->day;
        $planLaborId = $request->planLaborId;
        $response = [];

        try {
            $query = RequestDetailTask::select(
                'request_detail_tasks.id',
                'request_detail_tasks.avance', 'request_detail_tasks.horas',
                'request_detail_tasks.dia', 'request_detail_tasks.approved',
                'request_detail_tasks.observacion',
                'tasks.nombre AS labor',
                'turnos.nombre AS turno',
            )
                ->join('request_details', 'request_details.id', '=', 'request_detail_tasks.requestdetail_id')
                ->join('tasks', 'tasks.id', '=', 'request_details.task_id')
                ->join('turnos', 'turnos.id', '=', 'request_details.turno_id')
                ->where('request_detail_tasks.requestdetail_id', '=', $planLaborId)
                ->where('request_detail_tasks.dia', '=', $day)
                ->where('request_detail_tasks.ischange', '=', 'N')
                ->get();
            json_encode($query);
            $response[] = array(
                'data' => 'labor_detail',
                'response' => $query
            );

            ////////////////////////////////////////////////////////////////////////////////////////////
            $laborPlanDtllId = $query[0]->id;
            $queryLocation = TaskLocation::select(
                'task_locations.location_id', 'locations.nombre', 'task_locations.avance_plan'
            )   ->join('locations', 'locations.id', '=', 'task_locations.location_id')
                ->where('task_locations.requestdetailtask_id', '=', $laborPlanDtllId)
                ->get();
            json_encode($queryLocation);
            $response[] = array(
                'data' => 'labor_ubicacions',
                'response' => $queryLocation
            );

            //////////////////////////////////////////////////////////////////////////////////////////////

            ////////////////////////////////////////////////////////////////////////////////////////////
            $checked = TaskImplement::where('requestdetailtask_id', $laborPlanDtllId)->get();
            if( count($checked) > 0 ) {
                $checked = $checked[0]->checked;
            }else {
                $checked = -1;
            }

            if( $checked == 0 ) {
                $queryImplement = TaskImplement::select(
                    'task_implements.cat_implemento_id', 'categoria_implementos.nombre', 'task_implements.checked', 'task_implements.requestdetailtask_id'
                )   ->join('categoria_implementos', 'categoria_implementos.id', '=', 'task_implements.cat_implemento_id')
                    ->where('task_implements.requestdetailtask_id', '=', $laborPlanDtllId)
                    ->get();
                json_encode($queryImplement);
            }else {
                $queryImplement = TaskImplement::select(
                    'task_implements.implement_id as cat_implemento_id', 'implements.nombre', 'task_implements.checked', 'task_implements.requestdetailtask_id'
                )   ->join('implements', 'implements.id', '=', 'task_implements.implement_id')
                    ->where('task_implements.requestdetailtask_id', '=', $laborPlanDtllId)
                    ->get();
                json_encode($queryImplement);
            }

            $response[] = array(
                'data' => 'labor_implements',
                'response' => $queryImplement
            );

            //////////////////////////////////////////////////////////////////////////////////////////////
            $queryMaquinaria = TaskMachinery::select(
                'task_machineries.cat_maquinaria_id', 'categoria_maquinarias.nombre'
            )   ->join('categoria_maquinarias', 'categoria_maquinarias.id', '=', 'task_machineries.cat_maquinaria_id')
                ->where('task_machineries.requestdetailtask_id', '=', $laborPlanDtllId)
                ->get();
            json_encode($queryMaquinaria);
            $response[] = array(
                'data' => 'labor_maquinarias',
                'response' => $queryMaquinaria
            );


        } catch (Exception $e) {
            $response = $day;
        }

        return $response;
    }

    ////////// APROBACIONES //////////
    public function index_approved()
    {
        // item_id = 15 => ID Item Aprobar
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 15)
            ->get();

        $id = Crypt::encryptString(Auth::user()->id);
        return view('planificacion.approved.index')
                    ->with('menu', $menu)
                ->with('id_user', $id);
    }

    public function search_widget()
    {
        $anio = date('Y');

        $semanas = ModelsRequest::select(
            'requests.nro_semana'
        )   ->where('requests.anio', $anio)
            ->orderBy('requests.nro_semana', 'DESC')
            ->distinct()
            ->get();

        return view('planificacion.approved.components.search')
                ->with('semanas', $semanas)
                ->with('anio', $anio);
    }

    public function list_approved(Request $request)
    {

        $response = [];

        try {
            $query = RequestDetailTask::select(
                'request_detail_tasks.dia','request_detail_tasks.avance','request_detail_tasks.change_msg',
                'tasks.nombre AS labor',
                'request_detail_tasks.id AS requestdetailtask_id',
                'request_detail_tasks.approved', 'request_detail_tasks.id_changes',
                'users.nombres', 'users.apel_pat', 'users.apel_mat'
            )
                ->join('request_details', 'request_details.id', '=', 'request_detail_tasks.requestdetail_id')
                ->join('tasks', 'tasks.id', '=', 'request_details.task_id')
                ->join('requests', 'requests.id', '=', 'request_details.request_id')
                ->join('people', 'people.id', '=', 'requests.person_id')
                ->join('users', 'users.id', '=', 'people.user_id')
                ->orderBy('request_detail_tasks.dia', 'DESC')
                ->orderBy('request_detail_tasks.created_at', 'ASC')
                ->where('requests.nro_semana', $request->nro_semana)
                ->where('requests.anio', $request->anio)
                ->where('request_detail_tasks.changes', 2)
                ->get();
            json_encode($query);
            $response[] = array(
                'data' => 'labor_detail',
                'response' => $query
            );

            /////////////////////////////////////////////////////////////////////////////////////////////////

        } catch (Exception $e) {

        }

        return view('planificacion.approved.components.table')
            ->with('response', $query);
    }

    public function list_approved_js(Request $request)
    {
        $rows = [];

        try {
            $registros = RequestDetailTask::select(
                'request_detail_tasks.dia',
                'tasks.nombre AS labor',
                'request_detail_tasks.id AS requestdetailtask_id',
                'request_detail_tasks.approved', 'request_detail_tasks.id_changes', 'request_detail_tasks.changes',
                'users.nombres', 'users.apel_pat', 'users.apel_mat'
            )   ->join('request_details', 'request_details.id', '=', 'request_detail_tasks.requestdetail_id')
                ->join('tasks', 'tasks.id', '=', 'request_details.task_id')
                ->join('requests', 'requests.id', '=', 'request_details.request_id')
                ->join('people', 'people.id', '=', 'requests.person_id')
                ->join('users', 'users.id', '=', 'people.user_id')
                ->orderBy('request_detail_tasks.dia', 'DESC')
                ->orderBy('request_detail_tasks.created_at', 'ASC')
                ->where('requests.nro_semana', $request->nro_semana)
                ->where('requests.anio', $request->anio)
                //->where('request_detail_tasks.changes', 2)
                ->Where('request_detail_tasks.id_changes', '<>', 0)
                ->get();

            foreach( $registros as $reg ) {
                $supervisor = $reg->apel_pat. " " . $reg->apel_mat . " - " . $reg->nombres;
                $rdt_id = $reg->requestdetailtask_id;
                $rdt_id_actual = $reg->id_changes;
                $implement = TaskImplement::select(
                    'categoria_implementos.nombre'
                )   ->join('categoria_implementos', 'categoria_implementos.id', '=', 'task_implements.cat_implemento_id')
                    ->where('task_implements.requestdetailtask_id', $rdt_id)
                    ->get();
                $implement = $implement[0]->nombre;

                $machinerie = TaskMachinery::select(
                    'categoria_maquinarias.nombre'
                )   ->join('categoria_maquinarias', 'categoria_maquinarias.id', '=', 'task_machineries.cat_maquinaria_id')
                    ->where('task_machineries.requestdetailtask_id', $rdt_id)
                    ->get();
                $machinerie = $machinerie[0]->nombre;

                $operacion = array(
                    'rdt_id_sol' => $rdt_id,
                    'rdt_id' => $rdt_id_actual,
                    'changes' => $reg->changes
                );

                $rows[] = array(
                    'dia' => $reg->dia,
                    'supervisor' => strtoupper($supervisor),
                    'implemento' => $implement,
                    'maquinaria' => $machinerie,
                    'changes' => $reg->changes,
                    'operacion' => $operacion
                );
            }

        }catch(Exception $e) {
            $rows[] = $e->getMessage();
        }

        $response = array(
            'total' => count($rows),
            'rows' => $rows
        );

        return response()->json($response);
    }

    public function save(Request $request)
    {
        $response = "";

        $rdt_id = $request->id_new;
        $rdt_id_old = $request->id_old;
        try {
            $update = RequestDetailTask::findOrFail($rdt_id);
            $update->changes = 1;
            $update->updated_at = date('Y-m-d H:i:s');
            $update->save();

            $update_old = RequestDetailTask::findOrFail($rdt_id_old);
            $update_old->changes = 3;
            $update_old->updated_at = date('Y-m-d H:i:s');
            $update_old->save();

            $response = "402";

        }catch(Exception $e) {
            $response = "300";
        }

        return $response;
    }

    public function cancel_task_detail(Request $request)
    {
        $planLaborId = $request->planlaborId;
        $dia = $request->dia;
        $response ="";

        $labordia = RequestDetailTask::where('requestdetail_id', $planLaborId)
                        ->where('dia', $dia)
                        ->firstOrFail();
        $labordia->flag = 3;
        $labordia->updated_at = date('Y-m-d H:i:s');
        try {
            $labordia->save();
            $response = "402";

        }catch(Exception $e) {
            $response = '300';
        }

        return $response;
    }

    public function modalBody(Request $request)
    {
        $rdt_id_sol = $request->rdt_id;
        $plan_sol = RequestDetailTask::where('id', $rdt_id_sol)->get();
        $rdt_id = $plan_sol[0]->id_changes;     // ID Actual
        // $plan = RequestDetailTask::where('id', intval($rdt_id));

        $ubicaciones = '';
        $locations = TaskLocation::select(
            'locations.id', 'locations.nombre'
        )   ->join('locations', 'locations.id', '=', 'task_locations.location_id')
            ->where('task_locations.requestdetailtask_id', $rdt_id )
            ->get();

        foreach( $locations as $loc ) {
             $ubicaciones.= $loc['nombre'] .'<br> ';
        }
        $ubicaciones = trim($ubicaciones, ', ');

        $isCheck = TaskImplement::where('task_implements.requestdetailtask_id', $rdt_id)->get();
        if( count($isCheck) > 0 ) $isChecked = $isCheck[0]->checked;
        else $isChecked = 0;

        $implement_select = 0;
        $implements = '';
        $cat_implemento = 'Sin categoría';
        $implement = '';

        if( $isChecked == 0 ) {
            $cat_implemento = TaskImplement::select(
                'categoria_implementos.nombre', 'task_implements.implement_id'
            )   ->join('categoria_implementos', 'categoria_implementos.id', '=', 'task_implements.cat_implemento_id')
                ->where('task_implements.requestdetailtask_id', '=', $rdt_id)
                ->get();
            $checkImp = $cat_implemento[0]->implement_id;
            $cat_implemento = $cat_implemento[0]->nombre;
            if( $checkImp == '0' ) {
                $implement = 'Sin planificación';
            }else {
                $implements = Implement::select(
                    'implements.id', 'implements.nombre'
                )   //->join('categoria_implementos', 'categoria_implementos.id', '=', 'implements.cat_implemento_id')
                    ->join('task_implements', 'task_implements.implement_id', '=', 'implements.id')
                    ->where('task_implements.requestdetailtask_id', $rdt_id)
                    ->get();
                if( count($implements) >0 ) $implement = $implements[0]->nombre;
            }

        }else {
            $implements = Implement::select(
                'implements.id', 'implements.nombre'
            )   // ->join('categoria_implementos', 'categoria_implementos.id', '=', 'implements.cat_implemento_id')
                ->join('task_implements', 'task_implements.implement_id', '=', 'implements.id')
                ->where('task_implements.requestdetailtask_id', $rdt_id)
                ->get();
            if( count($implements) >0 ) $implement = $implements[0]->nombre;
        }



        $cat_maquinaria = TaskMachinery::select(
            'categoria_maquinarias.nombre', 'task_machineries.machinerie_id'
        )   ->join('categoria_maquinarias', 'categoria_maquinarias.id', '=', 'task_machineries.cat_maquinaria_id')
            ->where('task_machineries.requestdetailtask_id', '=', $rdt_id)
            ->get();
        $checkMaq = $cat_maquinaria[0]->machinerie_id;
        $cat_maquinaria = $cat_maquinaria[0]->nombre;

        if( $checkMaq == '0' ) {
            $machinerie = 'Sin planificación';
        }else {
            $machineries = Machinerie::select(
                'machineries.id', 'machineries.nombre'
            )   ->join('categoria_maquinarias', 'categoria_maquinarias.id', '=', 'machineries.cat_maquinaria_id')
                ->join('task_machineries', 'task_machineries.cat_maquinaria_id', '=', 'categoria_maquinarias.id')
                ->where('task_machineries.requestdetailtask_id', $rdt_id)
                ->get();
            if( count($machineries) > 0 ) $machinerie = $machineries[0]->nombre;
        }

        /////////////// Solicitado
        $ubicaciones_sol = '';
        $locations_sol = TaskLocation::select(
            'locations.id', 'locations.nombre'
        )   ->join('locations', 'locations.id', '=', 'task_locations.location_id')
            ->where('task_locations.requestdetailtask_id', $rdt_id_sol )
            ->get();

        foreach( $locations_sol as $loc ) {
             $ubicaciones_sol.= $loc['nombre'] .'<br> ';
        }
        $ubicaciones_sol = trim($ubicaciones_sol, ', ');

        $isCheckSol = TaskImplement::where('task_implements.requestdetailtask_id', $rdt_id_sol)->get();
        if( count($isCheckSol) > 0 ) $isCheckedSol = $isCheckSol[0]->checked;
        else $isCheckedSol = 0;

        $implement_select = 0;
        $implements_sol = '';
        $cat_implemento_sol = '';
        $implement_sol = '';

        if( $isCheckedSol == 0 ) {
            $cat_implemento_sol = TaskImplement::select(
                'categoria_implementos.nombre'
            )   ->join('categoria_implementos', 'categoria_implementos.id', '=', 'task_implements.cat_implemento_id')
                ->where('task_implements.requestdetailtask_id', '=', $rdt_id_sol)
                ->get();
            $cat_implemento_sol = $cat_implemento_sol[0]->nombre;
        }else {
            $implement_select = $isCheck[0]->implement_id;
        }



        $cat_maquinaria_sol = TaskMachinery::select(
            'categoria_maquinarias.nombre'
        )   ->join('categoria_maquinarias', 'categoria_maquinarias.id', '=', 'task_machineries.cat_maquinaria_id')
            ->where('task_machineries.requestdetailtask_id', '=', $rdt_id_sol)
            ->get();
        $cat_maquinaria_sol = $cat_maquinaria_sol[0]->nombre;

        return view('planificacion.approved.components.modalbody')
                ->with('ubicaciones', $ubicaciones)
                ->with('cat_implemento', $cat_implemento)
                ->with('implemento', $implement)
                ->with('cat_maquinaria', $cat_maquinaria)
                ->with('maquinaria', $machinerie)
                ->with('msg', $plan_sol[0]->change_msg)
                ->with('ubicaciones_sol', $ubicaciones_sol)
                ->with('cat_implemento_sol', $cat_implemento_sol)
                ->with('cat_maquinaria_sol', $cat_maquinaria_sol)
                ->with('changes', $request->changes);
    }
}
