<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use App\Models\CategoriaImplemento;
use App\Models\CategoriaMaquinaria;
use App\Models\Implement;
use App\Models\Location;
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

class RequestController extends Controller
{
    //
    public function index()
    {
        $id = Crypt::encryptString(Auth::user()->id);

        // item_id = 12 => ID Item Requerimientos
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 12)
            ->get();

        return view('planificacion.request.index')
            ->with('id_user', $id)
            ->with('user_id', Auth::user()->id )
            ->with('menu', $menu);
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

        return view('planificacion.request.components.table')
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

        return view('planificacion.request.components.table_select')
                ->with('requerimientos', $response);
    }

    public function create(Request $request)
    {
        $user_id = Crypt::decryptString($request->key);
        try {
            $labores = Task::all()->sortBy('nombre');
            $supervisores = Person::select(
                'people.id as person_id',
                'users.id as user_id', 'users.nombres', 'users.apel_pat', 'users.apel_mat'
            )   ->join('users', 'users.id', '=', 'people.user_id')
                ->where('users.rol_id', 3) // Rol: Supervisor
                ->where('people.typeperson_id', 5) // Tipo de persona: Supervisor - Requerimientos
                ->orderBy('users.apel_pat')
                ->get();

        } catch (Exception $e) {
            $supervisores = [];
        }

        return view('planificacion.request.components.create')
                ->with('supervisores', $supervisores)
                ->with('id_user', $user_id)
                ->with('labores', $labores);
    }

    public function save(Request $request)
    {
        $planificacion = new ModelsRequest();
        $anio = date("Y");
        $numWeekExists =
            DB::table('requests')
                ->where('nro_semana', $request->nsemana)
                ->where('anio', $anio)
                ->where('person_id', $request->person)
                ->count();
        if($numWeekExists == 0 ) {
            try {
                $planificacion->person_id = $request->swork;
                $planificacion->date_inicio = $request->finicio;
                $planificacion->date_fin = $request->ffin;
                $planificacion->nro_semana = $request->nsemana;
                $planificacion->anio = $anio;
                $planificacion->created_at =  date('Y-m-d H:i:s');
                $planificacion->updated_at = date('Y-m-d H:i:s');
                $planificacion->save();

                $status = "402";
            } catch (Exception $e) {
                $status = "302";
            }

        }else {
            $status =  "502"; // Semana planificación existe
        }

        return $status;
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

        return view('planificacion.request.components.request')
            ->with('labores', $labores)
            ->with('turnos', $turnos)
            ->with('umedidas', $um)
            ->with('dtll_plan', $dtll_request[0]);
    }

    public function list_request(Request $request)
    {
        $id_planificacion = $request->id_planificacion;
        // $person_id = Person::where('user_id', Auth::user()->id)->get();
        // $person_id = $person_id[0];
        // $person_id = $person_id->id;

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
            $locations  = Location::where('flag', 1)->orderBy('nombre')->get();
            //$implements = Implement::all()->sortBy('nombre');

            $implements = CategoriaImplemento::all()->sortBy('nombre');
            $maquinarias = CategoriaMaquinaria::all()->sortBy('nombre');


            // $responsables  = Person::select(
            //     'people.id',
            //     'users.apel_pat', 'users.apel_mat', 'users.nombres',
            // )   ->join('users', 'users.id', '=', 'people.user_id')
            //     ->where('people.typeperson_id', 3)
            //     ->get();

        } catch (Exception $e) {
            $status = "302";
        }

        return view('planificacion.request.components.request_detail')
            ->with('locations', $locations)
            ->with('implements', $implements)
            ->with('maquinarias', $maquinarias)
            ->with('dtll_plan', $dtll_request[0])
            ->with('person_id', $request->user_id)
            ->with('dtll_labores', $dtll_tasks);
    }

    public function save_task(Request $request)
    {
        $id_labor = $request->id_labor;
        $id_turno = $request->id_turno;
        $unidad_avance = $request->uavance;
        $id_planificacion = $request->id_planificacion;

        $labor_plan = new RequestDetail();
        try {
            $labor_plan->task_id = $id_labor;
            $labor_plan->turno_id = $id_turno;
            $labor_plan->um_id = $unidad_avance;
            $labor_plan->request_id = $id_planificacion;
            $labor_plan->created_at = date('Y-m-d H:i:s');
            $labor_plan->updated_at = date('Y-m-d H:i:s');
            $labor_plan->save();
        } catch (Exception $e) {
            return "302";
        }

        return "402";
    }

    public function save_task_detail(Request $request)
    {
        $implementIds = $request->implementIds == null ? [] : $request->implementIds;
        $locationIds = $request->locationIds == null ? [] : $request->locationIds;
        $maquinariaIds = $request->maquinariaIds == null ? [] : $request->maquinariaIds;
        $avanceIds = $request->inputIds;
        $planLaborId = $request->planLaborId;
        $dateCopys = $request->copys;
        $hours = $request->hours;
        $day = $request->day;
        $progress = $request->progress;
        $update = $request->update;
        $nro_maquinas = $request->nroMaq;
        $responsableId = $request->responsibleId;
        $check = $request->check;
        $observacion = $request->obs;

        $fecha = new DateTime($day);
        $day = $fecha->format('Y-m-d');
        $response = "102";

        try {
            if ($update != 0) {
                $laborPlanDtllId = $update;
                $updateRequest = RequestDetailTask::findOrFail($laborPlanDtllId);
                $updateRequest->avance = $progress;
                $updateRequest->horas = $hours;
                $updateRequest->nro_maquinas = $nro_maquinas;
                $updateRequest->observacion = $observacion;
                $updateRequest->updated_at = date('Y-m-d H:i:s');
                $updateRequest->save();

                $isCorrect = TaskLocation::where('requestdetailtask_id', intval($laborPlanDtllId))
                                        ->delete();

                $index = 0;
                foreach ($locationIds as $locationId) {
                    $laborLocation = new TaskLocation();
                    $laborLocation->location_id = $locationId;
                    $laborLocation->requestdetailtask_id = $laborPlanDtllId;
                    $laborLocation->avance_plan = $avanceIds[$index];
                    $laborLocation->created_at = date('Y-m-d H:i:s');
                    $laborLocation->updated_at = date('Y-m-d H:i:s');
                    $laborLocation->save();
                    $index++;
                }

                $isCorrect = TaskImplement::where('requestdetailtask_id', intval($laborPlanDtllId))
                                            ->delete();

                $isChecked = $check == 1 ? true : false;

                foreach ($implementIds as $implementId) {
                    $laborImplement = new TaskImplement();

                    if( $isChecked ) {
                        $laborImplement->implement_id = $implementId;
                        $laborImplement->requestdetailtask_id = $laborPlanDtllId;
                        $laborImplement->cat_implemento_id = 0;
                        $laborImplement->checked = $check;
                        $laborImplement->created_at = date('Y-m-d H:i:s');
                        $laborImplement->updated_at = date('Y-m-d H:i:s');
                        $laborImplement->save();
                    }else {
                        $laborImplement->cat_implemento_id = $implementId;
                        $laborImplement->requestdetailtask_id = $laborPlanDtllId;
                        $laborImplement->checked = $check;
                        $laborImplement->created_at = date('Y-m-d H:i:s');
                        $laborImplement->updated_at = date('Y-m-d H:i:s');
                        $laborImplement->save();
                    }
                }

                if( count($implementIds) == 0 ) {
                    $laborImplement = new TaskImplement();
                    $laborImplement->implement_id = 282;
                    $laborImplement->requestdetailtask_id = $laborPlanDtllId;
                    $laborImplement->cat_implemento_id = 0;
                    $laborImplement->checked = 1;
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

                $isCorrect = TaskSupervisor::where('requestdetailtask_id', intval($laborPlanDtllId))
                                                ->delete();

                $laborSupervisor = new TaskSupervisor();
                $laborSupervisor->requestdetailtask_id = $laborPlanDtllId;
                $laborSupervisor->person_id = $responsableId;
                $laborSupervisor->created_at = date('Y-m-d H:i:s');
                $laborSupervisor->updated_at = date('Y-m-d H:i:s');
                $laborSupervisor->save();

            } else {
                foreach( $dateCopys as $dateCopy ) {
                    $fecha = new DateTime($dateCopy);
                    $date = $fecha->format('Y-m-d');
                    $isCorrect = RequestDetailTask::where('request_detail_tasks.dia', '=', $date)
                                                    ->where('request_detail_tasks.requestdetail_id', '=', $planLaborId)
                                                    ->where('request_detail_tasks.changes', '=', 1)
                                                    ->delete();

                    $laborPlanDtll = new RequestDetailTask();
                    $laborPlanDtll->requestdetail_id = $planLaborId;
                    $laborPlanDtll->avance = $progress;
                    $laborPlanDtll->dia = $date;
                    $laborPlanDtll->horas = $hours;
                    $laborPlanDtll->nro_maquinas = $nro_maquinas;
                    $laborPlanDtll->observacion = $observacion;
                    $laborPlanDtll->datetime_inicio = date('Y-m-d H:i:s');
                    $laborPlanDtll->datetime_fin = date('Y-m-d H:i:s');
                    $laborPlanDtll->created_at = date('Y-m-d H:i:s');
                    $laborPlanDtll->updated_at = date('Y-m-d H:i:s');
                    $laborPlanDtll->save();

                    $query = RequestDetailTask::where('request_detail_tasks.dia', '=', $date)
                                            ->where('request_detail_tasks.requestdetail_id', '=', $planLaborId)
                                            ->get();
                    json_encode($query);
                    $laborPlanDtllId = $query[0]->id;

                    $isCorrect = TaskLocation::where('requestdetailtask_id', intval($laborPlanDtllId))
                                        ->delete();

                    // $sql = "DELETE FROM labor_ubicacions WHERE dtlllabor_id='$laborPlanDtllId'";
                    // $exec = DB::delete($sql);
                    $index = 0;
                    foreach ($locationIds as $locationId) {
                        $laborLocation = new TaskLocation();
                        $laborLocation->location_id = $locationId;
                        $laborLocation->requestdetailtask_id = $laborPlanDtllId;
                        $laborLocation->avance_plan = $avanceIds[$index];
                        $laborLocation->created_at = date('Y-m-d H:i:s');
                        $laborLocation->updated_at = date('Y-m-d H:i:s');
                        $laborLocation->save();
                        $index++;
                    }

                    $isCorrect = TaskImplement::where('requestdetailtask_id', intval($laborPlanDtllId))
                                                ->delete();

                    // $sql = "DELETE FROM labor_implements WHERE dtlllabor_id='$laborPlanDtllId'";
                    // $exec = DB::delete($sql);
                    $isChecked = $check == 1 ? true : false;

                    foreach ($implementIds as $implementId) {
                        $laborImplement = new TaskImplement();

                        if( $isChecked ) {
                            $laborImplement->implement_id = $implementId;
                            $laborImplement->requestdetailtask_id = $laborPlanDtllId;
                            $laborImplement->cat_implemento_id = 0;
                            $laborImplement->checked = $check;
                            $laborImplement->created_at = date('Y-m-d H:i:s');
                            $laborImplement->updated_at = date('Y-m-d H:i:s');
                            $laborImplement->save();
                        }else {
                            $laborImplement->cat_implemento_id = $implementId;
                            $laborImplement->requestdetailtask_id = $laborPlanDtllId;
                            $laborImplement->checked = $check;
                            $laborImplement->created_at = date('Y-m-d H:i:s');
                            $laborImplement->updated_at = date('Y-m-d H:i:s');
                            $laborImplement->save();
                        }

                    }

                    if( count($implementIds) == 0 ) {
                        $laborImplement = new TaskImplement();
                        $laborImplement->implement_id = 282;
                        $laborImplement->requestdetailtask_id = $laborPlanDtllId;
                        $laborImplement->cat_implemento_id = 0;
                        $laborImplement->checked = 1;
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
                }
            }

            $response = array(
                'status' => 200,
                'response' => 'Ok'
            );
        } catch (Exception $e) {
            $response = array(
                'status' => 500,
                'response' => $e->getMessage()
            );
        }

        return response()->json($response);
    }

    public function update_task_detail(Request $request)
    {
        $day = $request->day;
        $planLaborId = $request->planLaborId;
        $response = [];

        try {
            $query = RequestDetailTask::select(
                'request_detail_tasks.id', 'request_detail_tasks.nro_maquinas',
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
                ->where('request_detail_tasks.changes', '=', 1)
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

    public function delete(Request $request)
    {
        $status = 0;
        $message = '';

        try {
            $delete = RequestDetail::where('id', intval($request->requestDetailsId))
                                ->delete();
            $status = 200;
            $message = 'Ok';
        }catch(Exception $e) {
            $status = 402;
            $message = $e->getMessage();
        }

        $response = array (
            'status' => $status,
            'response' => $message
        );

        return response()->json($response);
    }

    public function cancel_task_detail(Request $request)
    {
        $planLaborId = $request->planlaborId;
        $dia = $request->dia;
        $status = 0;
        $message = '';

        try {
            $labordia = RequestDetailTask::where('requestdetail_id', $planLaborId)
                        ->where('dia', $dia)
                        ->firstOrFail();
            $labordia->flag = 3;
            $labordia->updated_at = date('Y-m-d H:i:s');            
            $labordia->save();

            $status = 200;
            $message = 'Ok';

        }catch(Exception $e) {
            $status = 402;
            $message = $e->getMessage();
        }

        $response = array(
            'status' => $status,
            'message' => $message
        );

        return response()->json($response);
    }
}
