<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Implement;
use App\Models\Location;
use App\Models\Machinerie;
use App\Models\MenuItem;
use App\Models\Person;
use App\Models\Request as ModelsRequest;
use App\Models\RequestDetail;
use App\Models\RequestDetailTask;
use App\Models\Tareo;
use App\Models\TaskImplement;
use App\Models\TaskLocation;
use App\Models\TaskMachinery;
use App\Models\TaskOperator;
use App\Models\TaskSupervisor;
use App\Models\User;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PlanningController extends Controller
{
    //
    public function index()
    {
        // item_id = 13 => ID Item Gestión Requerimientos
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 13)
            ->get();

        return view('planificacion.planning.index')->with('menu', $menu);
    }

    public function list(Request $request)
    {

        $response = [];

        try {
            $query = RequestDetailTask::select(
                'request_detail_tasks.dia',
                'turnos.nombre AS nombre_turno',
                'tasks.nombre AS labor',
                'request_detail_tasks.id AS requestdetailtask_id',
                'request_detail_tasks.approved',
                'request_detail_tasks.nro_maquinas'
            )
                ->join('request_details', 'request_details.id', '=', 'request_detail_tasks.requestdetail_id')
                ->join('turnos', 'turnos.id', '=', 'request_details.turno_id')
                ->join('tasks', 'tasks.id', '=', 'request_details.task_id')
                ->join('requests', 'requests.id', '=', 'request_details.request_id')
                ->orderBy('request_detail_tasks.dia', 'DESC')
                ->orderBy('request_detail_tasks.created_at', 'ASC')
                ->where('requests.nro_semana', $request->nro_semana)
                ->where('requests.anio', $request->anio)
                ->where('request_detail_tasks.changes', 1)
                ->where('request_detail_tasks.flag', '<>', 3)
                ->get();
            json_encode($query);
            $response[] = array(
                'data' => 'labor_detail',
                'response' => $query
            );

            /////////////////////////////////////////////////////////////////////////////////////////////////

        } catch (Exception $e) {

        }

        return view('planificacion.planning.components.new_table')
            ->with('nro_sem', $request->nro_semana)
            ->with('anio', $request->anio)
            ->with('response', $query)
            ;
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
        $areas = Area::all()->sortBy('nombre');

        return view('planificacion.planning.components.search')
                ->with('semanas', $semanas)
                ->with('areas', $areas)
                ->with('anio', $anio);
    }

    public function save(Request $request)
    {
        $supervisor_id = $request->supervisor_id;
        $trabajador_id = $request->operator_id;
        $machinery_id = $request->machinery_id;
        $lbdtll_id   = $request->lbdtll_id;
        $msgResponse = "102";

        $lbMachinery = new TaskMachinery();
        try {
            $lbMachinery->machinerie_id = $machinery_id;
            $lbMachinery->requestdetailtask_id = $lbdtll_id;
            $lbMachinery->created_at = date('Y-m-d H:i:s');
            $lbMachinery->updated_at = date('Y-m-d H:i:s');
            $lbMachinery->save();
            $msgResponse = "402";

        }catch(Exception $e) {
            $msgResponse = "302";
        }

        // $lbOperator = new TaskOperator();
        // try {
        //     $lbOperator->person_id = $trabajador_id;
        //     $lbOperator->requestdetailtask_id = $lbdtll_id;
        //     $lbOperator->created_at = date('Y-m-d H:i:s');
        //     $lbOperator->updated_at = date('Y-m-d H:i:s');
        //     $lbOperator->save();
        //     $msgResponse = "402-2";

        // }catch(Exception $e) {
        //     $msgResponse = "302-2";
        // }

        $lbSupervisor = new TaskSupervisor();
        try{
            $lbSupervisor->person_id = $supervisor_id;
            $lbSupervisor->requestdetailtask_id = $lbdtll_id;
            $lbSupervisor->created_at = date('Y-m-d H:i:s');
            $lbSupervisor->updated_at = date('Y-m-d H:i:s');
            $lbSupervisor->save();
            $msgResponse = "402-3";
        }catch(Exception $e) {
            $msgResponse = "302-3";
        }

        $details = RequestDetailTask::findOrFail($lbdtll_id);
        try {
            $details->approved = 1;
            $details->updated_at = date('Y-m-d H:i:s');
            $details->save();

        }catch(Exception $e) {
            $msgResponse = "302-4";
        }

        return $msgResponse;
    }

    public function index_manage()
    {
        // item_id = 16 => ID Planificación - online
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 16)
            ->get();

        return view('planificacion.manage.index')->with('menu', $menu);
    }

    public function list_manage()
    {

        try {
            $requerimientos = Tareo::select(
                'tareos.id', 'tareos.avance', 'tareos.horometro_inicio', 'tareos.horometro_fin', 'tareos.flag',
                'tasks.nombre as task',
                'locations.nombre as location',
                'implements.nombre as implement',
                'machineries.nombre as machinerie',
                'users.id as user_id', 'users.nombres', 'users.apel_pat', 'users.apel_mat',
                'request_detail_tasks.dia','request_detail_tasks.id As rdetailt_id',
                'tareo_states.name', 'tareo_states.colorweb', 'tareo_states.id As state_id',
                'requests.nro_semana'
            )   ->join('locations', 'locations.id', '=', 'tareos.location_id')
                ->join('tasks', 'tasks.id', '=', 'tareos.task_id')
                ->join('implements', 'implements.id', '=', 'tareos.implement_id')
                ->join('machineries', 'machineries.id', '=', 'tareos.machinerie_id')
                ->join('people', 'people.id', '=', 'tareos.operator_id')
                ->join('users', 'users.id', '=', 'people.user_id')
                ->join('tareo_states', 'tareo_states.id', '=', 'tareos.state_id')
                ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'tareos.rdetailt_id')
                ->join('request_details', 'request_details.id', '=', 'request_detail_tasks.requestdetail_id')
                ->join('requests', 'requests.id', '=', 'request_details.request_id')
                ->orderBy('requests.nro_semana', 'DESC')
                ->orderBy('request_detail_tasks.dia', 'DESC')
                ->get();
                $response = "402";

        } catch (Exception $e) {
            return $response = $e->getMessage();
        }

        return view('planificacion.manage.components.table')
                ->with('requerimientos', $requerimientos);
    }

    public function newlist_manage()
    {
        $rows = [];

        try {
            $requerimientos = Tareo::select(
                'tareos.id', 'tareos.avance', 'tareos.horometro_inicio', 'tareos.horometro_fin', 'tareos.flag',
                'tasks.nombre as task',
                'locations.nombre as location',
                'implements.nombre as implement',
                'machineries.nombre as machinerie',
                'users.id as user_id', 'users.nombres', 'users.apel_pat', 'users.apel_mat',
                'request_detail_tasks.dia','request_detail_tasks.id As rdetailt_id',
                'tareo_states.name', 'tareo_states.colorweb', 'tareo_states.id As state_id',
                'requests.nro_semana', 'requests.person_id'
            )   ->join('locations', 'locations.id', '=', 'tareos.location_id')
                ->join('tasks', 'tasks.id', '=', 'tareos.task_id')
                ->join('implements', 'implements.id', '=', 'tareos.implement_id')
                ->join('machineries', 'machineries.id', '=', 'tareos.machinerie_id')
                ->join('people', 'people.id', '=', 'tareos.operator_id')
                ->join('users', 'users.id', '=', 'people.user_id')
                ->join('tareo_states', 'tareo_states.id', '=', 'tareos.state_id')
                ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'tareos.rdetailt_id')
                ->join('request_details', 'request_details.id', '=', 'request_detail_tasks.requestdetail_id')
                ->join('requests', 'requests.id', '=', 'request_details.request_id')
                ->orderBy('requests.nro_semana', 'DESC')
                ->orderBy('request_detail_tasks.dia', 'DESC')
                ->get();

            foreach( $requerimientos as $requerimiento ) {
                $fecha = new DateTime($requerimiento->dia);
                $dia = $fecha->format('d/m/Y');
                $apel_nom = $requerimiento->apel_pat ." ". $requerimiento->apel_mat." ". $requerimiento->nombres;

                $operacion = array(
                    'colorweb' => $requerimiento->colorweb,
                    'status_name' => $requerimiento->name,
                    'status_id' => $requerimiento->state_id
                );

                $value = Person::select(
                    'users.nombres', 'users.apel_pat', 'users.apel_mat'
                )   ->join('users', 'users.id', '=', 'people.user_id')
                    ->where('people.id', $requerimiento->person_id)
                    ->get();

                if( count($value) > 0 ) $supervisor = $value[0]->nombres. ' ' . $value[0]->apel_pat . ' ' . $value[0]->apel_mat;
                else $supervisor = '-';

                $rows[] = array(
                    'semana' => $requerimiento->nro_semana,
                    'dia' => $dia,
                    'supervisor' => strtoupper($supervisor),
                    'ubicacion' => $requerimiento->location,
                    'operario' => strtoupper($apel_nom),
                    'implemento' => $requerimiento->implement,
                    'maquinaria' => $requerimiento->machinerie,
                    'operacion' => $operacion
                );
            }

        } catch (Exception $e) {
            $rows[] = $e->getMessage();
        }

        $response = array(
            'total' => count($rows),
            'rows' => $rows,
        );

        return response()->json($response);
    }

    public function search_operator(Request $request)
    {
        $operator_id = $request->operator_id;
        $dia = $request->date;

        $response = array(
            'status' => '200',
            'response' => ''
        );

        try{
            $operator = RequestDetailTask::select(
                'task_operators.person_id'
            )   ->join('task_operators', 'task_operators.requestdetailtask_id', '=', 'request_detail_tasks.id')
                ->where('task_operators.person_id', $operator_id)
                ->where('request_detail_tasks.dia', $dia)
                ->get()
                ->count();

            $response = array(
                'status' => '402',
                'response' => $operator
            );

        }catch(Exception $e) {
            $response = array(
                'status' => '300',
                'response' => 'error'
            );
        }

        return json_encode($response);
    }

    public function save_operator(Request $request)
    {
        $trabajador_id = $request->operator_id;
        $lbdtll_id   = $request->lbDtllID;
        $response = '200';

        $isCorrect = TaskOperator::where('requestdetailtask_id', intval($lbdtll_id))
                                            ->delete();

        $taskoperator = new TaskOperator();
        $taskoperator->requestdetailtask_id = $lbdtll_id;
        $taskoperator->person_id = $trabajador_id;
        $taskoperator->created_at = date('Y-m-d H:i:s');
        $taskoperator->updated_at = date('Y-m-d H:i:s');

        try {
            $taskoperator->save();
            $response = '402';
        }catch(Exception $e) {
            $response = $e->getMessage();
        }

        return $response;
    }

    public function save_operator_cero(Request $request)
    {
        $lbdtll_id   = $request->lbDtllID;

        $isCorrect = TaskOperator::where('requestdetailtask_id', intval($lbdtll_id))
                                            ->delete();
        return "402";
    }

    ////
    public function plan_list2($nro_semana, $anio)
    {
        try {
            $query = RequestDetailTask::select(
                'request_detail_tasks.dia',
                'request_detail_tasks.nro_maquinas',
                'turnos.nombre AS nombre_turno',
                'tasks.nombre AS labor','tasks.id AS labor_id',
                'request_detail_tasks.id AS requestdetailtask_id',
                'request_detail_tasks.approved',
                'locations.nombre AS location', 'locations.id AS location_id',
                'users.nombres', 'users.apel_pat', 'users.apel_mat'
            )
                ->join('request_details', 'request_details.id', '=', 'request_detail_tasks.requestdetail_id')
                ->join('turnos', 'turnos.id', '=', 'request_details.turno_id')
                ->join('tasks', 'tasks.id', '=', 'request_details.task_id')
                ->join('requests', 'requests.id', '=', 'request_details.request_id')
                ->join('people', 'people.id', '=', 'requests.person_id')
                ->join('users', 'users.id', '=', 'people.user_id')
                ->join('task_locations', 'task_locations.requestdetailtask_id', '=', 'request_detail_tasks.id')
                ->join('locations', 'locations.id', '=', 'task_locations.location_id')
                ->orderBy('request_detail_tasks.dia', 'DESC')
                ->orderBy('request_detail_tasks.created_at', 'ASC')
                ->where('requests.nro_semana', $nro_semana)
                ->where('requests.anio', $anio)
                ->where('request_detail_tasks.changes', 1)
                ->where('request_detail_tasks.flag', '<>', 3)
                ->get();
                $resp = array();
                $nro = 1;

                foreach($query as $labor) {
                    $d1 = $labor['dia'];
                    $d2 = $labor['nombre_turno'];
                    $d3 = $labor['labor'];
                    $d4 = $labor['requestdetailtask_id'];
                    $ubicaciones = [];
                    $implementos = '';
                    $supervisor = $labor['apel_pat']. " " . $labor['apel_mat']. " - " . $labor['nombres'];

                    $locations = TaskLocation::select(
                        'locations.id', 'locations.nombre'
                    )   ->join('locations', 'locations.id', '=', 'task_locations.location_id')
                        ->where('task_locations.requestdetailtask_id', $labor['requestdetailtask_id'])
                        ->get();

                    $d5 = array(
                        'total' => count($locations),
                        'locations' => $locations
                    );

                    $d5 = $labor['location'];

                    $implements = Implement::select(
                        'implements.id', 'implements.nombre'
                    )   ->join('categoria_implementos', 'categoria_implementos.id', '=', 'implements.cat_implemento_id')
                        ->join('task_implements', 'task_implements.cat_implemento_id', '=', 'categoria_implementos.id')
                        ->where('task_implements.requestdetailtask_id', $labor['requestdetailtask_id'])
                        ->get();

                    $data = Tareo::select('tareos.implement_id', 'tareos.machinerie_id', 'tareos.operator_id')
                                ->where('rdetailt_id', $labor['requestdetailtask_id'])
                                ->where('location_id', $labor['location_id'])
                                ->get();
                    if( count($data) != 0 ) {
                        $imp_id = $data[0]->implement_id;
                        $maq_id = $data[0]->machinerie_id;
                        $ope_id = $data[0]->operator_id;
                    }else {
                        $imp_id = 0;
                        $maq_id = 0;
                        $ope_id = 0;
                    }

                    $d6 = array(
                        'implementos' => $implements,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'imp_id' => $imp_id,
                        'dia' => $labor['dia']
                    );

                    $maquinarias = Machinerie::select(
                        'machineries.id', 'machineries.nombre'
                    )   ->join('categoria_maquinarias', 'categoria_maquinarias.id', '=', 'machineries.cat_maquinaria_id')
                        ->join('task_machineries', 'task_machineries.cat_maquinaria_id', '=', 'categoria_maquinarias.id')
                        ->where('task_machineries.requestdetailtask_id', $labor['requestdetailtask_id'])
                        ->get();

                    $d7 = array(
                        'maquinarias' => $maquinarias,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'maq_id' => $maq_id,
                        'dia' => $labor['dia']
                    );

                    $operators = Person::select(
                        'people.id',
                        'users.nombres',
                        'users.apel_pat',
                        'users.apel_mat'
                    )
                        ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
                        ->join('users', 'users.id', '=', 'people.user_id')
                        ->where('people.typeperson_id', '=', 1)
                        ->orWhere('people.typeperson_id', '=', 2)
                        ->get();

                    $d8 = array(
                        'operarios' => $operators,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'ope_id' => $ope_id,
                        'dia' => $labor['dia']
                    );

                    // foreach( $implements as $implement ) {
                    //     $implementos.= $implement['nombre'] .', ';
                    // }
                    // $d6 = trim($implementos, ', ');

                    $d9 = array(
                        'rdt_id'    => $labor['requestdetailtask_id'],
                        'labor_id'  => $labor['labor_id'],
                        'location_id' => $labor['location_id']
                    );

                    $d10 = $supervisor;

                    $resp[] = array(
                        'nro'   => $nro,
                        'dia'   => $d1,
                        'nombre_turno'  => $d2,
                        'labor' => $d3,
                        'requestdetailtask_id'=>$d4,
                        'ubicaciones'   =>$d5,
                        'implementos'   => $d6,
                        'maquinarias'   => $d7,
                        'operarios'     => $d8,
                        'operacion' => $d9,
                        'supervisor' => $d10
                    );

                    $nro++;
                }


        } catch (Exception $e) {

        }

        $response = array(
            'total'=> count($resp),
            'totalNotFiltered'=> count($resp),
            'rows'=> $resp
        );

        return response()->json($response);
    }

    public function list_sup()
    {
        $supervisores = Person::select(
            'people.id',
            'users.nombres',
            'users.apel_pat',
            'users.apel_mat'
        )
            ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
            ->join('users', 'users.id', '=', 'people.user_id')
            ->where('people.typeperson_id', '=', 3)
            ->get();

        return response()->json($supervisores);
    }

    public function select_sup()
    {
        return view('planificacion.manage.components.new_supervisor');
    }

    public function save_row(Request $request)
    {
        $response = array();
        $implemento_id = $request->implemento;
        $maquinaria_id = $request->maquinaria;
        $operario_id = $request->operario;
        $rdt_id = $request->rdt_id;
        $labor_id = $request->labor_id;
        $location_ids = $request->location_ids;

        $location_ids = explode(',', $location_ids);
        foreach( $location_ids as $location_id ) {
            try {
                // si existe tareo rdt/ubicacion
                $exits = DB::table('tareos')->where('rdetailt_id', $rdt_id)->where('location_id', $location_id)->exists();
                if( $exits == '' )
                {
                    $tareo = new Tareo();
                    $tareo->rdetailt_id = $rdt_id;
                    $tareo->task_id = $labor_id;
                    $tareo->location_id = $location_id;
                    $tareo->implement_id = $implemento_id;
                    $tareo->operator_id = $operario_id;
                    $tareo->machinerie_id = $maquinaria_id;
                    $tareo->state_id = 1;
                    $tareo->avance = 0;
                    $tareo->save();
                    $tareo_id = $tareo->id; // Obtener el ID guardado

                    $reg = RequestDetailTask::where('id', $rdt_id)->get();
                    $reg = $reg[0];
                    $jsonTareo = array();
                    if( $reg->tareos != null ) {
                        $ds = json_decode($reg->tareos);
                        foreach( $ds as $d ) {
                            $jsonTareo[] = $d;
                        }
                        $jsonTareo[] = $tareo_id;
                    }else {
                        $jsonTareo[] = $tareo_id;
                    }

                    $RDT = RequestDetailTask::findOrFail($rdt_id);
                    $RDT->flag = 2;
                    $RDT->tareos = json_encode($jsonTareo);
                    $RDT->approved = 1;
                    $RDT->save();

                    // $reg = TaskMachinery::findOrFail($rdt_id);
                    // $reg->machinerie_id = $maquinaria_id;
                    // $reg->save();

                    TaskMachinery::where('requestdetailtask_id', $rdt_id)
                                ->update(['machinerie_id'=>$maquinaria_id],);
                    TaskImplement::where('requestdetailtask_id', $rdt_id)
                                ->update(['implement_id'=>$implemento_id],);

                    $isCorrect = TaskOperator::where('requestdetailtask_id', intval($rdt_id))
                                ->delete();

                    $reg2 = new TaskOperator();
                    $reg2->requestdetailtask_id = $rdt_id;
                    $reg2->person_id = $operario_id;
                    $reg2->save();

                    $response = array(
                        'status' => 200,
                        'response' => 'OK'
                    );
                }else {
                    $id_tareo = Tareo::where('rdetailt_id', $rdt_id)->where('location_id', $location_id)->get();
                    $tareo_id = $id_tareo[0]->id;
                    $reg = Tareo::find($tareo_id);

                    $reg->task_id = $labor_id;
                    $reg->location_id = $location_id;
                    $reg->implement_id = $implemento_id;
                    $reg->operator_id = $operario_id;
                    $reg->machinerie_id = $maquinaria_id;
                    $reg->state_id = 1;
                    $reg->avance = 0;
                    $reg->save();

                    TaskMachinery::where('requestdetailtask_id', $rdt_id)
                                ->update(['machinerie_id'=>$maquinaria_id],);
                    TaskImplement::where('requestdetailtask_id', $rdt_id)
                                ->update(['implement_id'=>$implemento_id],);

                    $isCorrect = TaskOperator::where('requestdetailtask_id', intval($rdt_id))
                                ->delete();

                    $reg2 = new TaskOperator();
                    $reg2->requestdetailtask_id = $rdt_id;
                    $reg2->person_id = $operario_id;
                    $reg2->save();

                    $group_id = DB::select("SELECT group_id FROM tareo_groups WHERE tareo_id='$tareo_id'");
                    if( count($group_id) > 0 ) {
                        $group_id = $group_id[0]->group_id;
                        $delete1 = DB::select("DELETE FROM tareo_groups WHERE tareo_id='$tareo_id'");
                    }

                    $response = array(
                        'status' => 201,
                        'response' => $group_id
                    );
                }


            }catch(Exception $e) {
                $response = array(
                    'status' => 402,
                    'response' => $e->getMessage()
                );
            }
        }

        return response()->json($response);
    }


    // [  ]
    public function plan_list($nro_semana, $anio)
    {
        $resp = array();
        try {
            $query = RequestDetailTask::select(
                'request_detail_tasks.dia',
                'request_detail_tasks.nro_maquinas',
                'turnos.nombre AS nombre_turno',
                'tasks.nombre AS labor','tasks.id AS labor_id',
                'request_detail_tasks.id AS requestdetailtask_id',
                'request_detail_tasks.approved',
                //'locations.nombre AS location', 'locations.id AS location_id',
                'users.nombres', 'users.apel_pat', 'users.apel_mat',
                'areas.nombre as area', 'sub_areas.nombre as subarea'
            )
                ->join('request_details', 'request_details.id', '=', 'request_detail_tasks.requestdetail_id')
                ->join('turnos', 'turnos.id', '=', 'request_details.turno_id')
                ->join('tasks', 'tasks.id', '=', 'request_details.task_id')
                ->join('requests', 'requests.id', '=', 'request_details.request_id')
                ->join('people', 'people.id', '=', 'requests.person_id')
                ->join('users', 'users.id', '=', 'people.user_id')
                //->join('task_locations', 'task_locations.requestdetailtask_id', '=', 'request_detail_tasks.id')
                //->join('locations', 'locations.id', '=', 'task_locations.location_id')
                ->join('areas', 'areas.id', '=', 'people.area_id')
                ->join('sub_areas', 'sub_areas.id', '=', 'people.subarea_id')
                ->orderBy('request_detail_tasks.dia', 'DESC')
                ->orderBy('request_detail_tasks.created_at', 'ASC')
                ->where('requests.nro_semana', $nro_semana)
                ->where('requests.anio', $anio)
                ->where('request_detail_tasks.changes', 1)
                ->where('request_detail_tasks.flag', '<>', 3)
                ->get();
                $nro = 1;

                foreach($query as $labor) {
                    $d1 = $labor['dia'];
                    $d2 = $labor['nombre_turno'];
                    $d3 = $labor['labor'];
                    $d4 = $labor['requestdetailtask_id'];
                    $ubicaciones = '';
                    $ubicaciones_id = [];
                    $implementos = '';
                    $supervisor = $labor['apel_pat']. " " . $labor['apel_mat']. " - " . $labor['nombres'];

                    $locations = TaskLocation::select(
                        'locations.id', 'locations.nombre'
                    )   ->join('locations', 'locations.id', '=', 'task_locations.location_id')
                        ->where('task_locations.requestdetailtask_id', $labor['requestdetailtask_id'])
                        ->get();

                    $d5 = array(
                        'total' => count($locations),
                        'locations' => $locations
                    );

                    foreach( $locations as $loc ) {
                         $ubicaciones.= $loc['nombre'] .'<br> ';
                         $ubicaciones_id[] = array($loc['id']);
                    }
                    $d5 = array(
                        'nombres' => trim($ubicaciones, ', '),
                        'ids' => $ubicaciones_id
                    );

                    //$d5 = $labor['location'];
                    $isCheck = TaskImplement::where('task_implements.requestdetailtask_id', $labor['requestdetailtask_id'])->get();
                    if( count($isCheck) > 0 ) $isChecked = $isCheck[0]->checked;
                    else $isChecked = 0;

                    $implement_select = 0;

                    if( $isChecked == 0 ) {
                        $implements = Implement::select(
                            'implements.id', 'implements.nombre'
                        )   ->join('categoria_implementos', 'categoria_implementos.id', '=', 'implements.cat_implemento_id')
                            ->join('task_implements', 'task_implements.cat_implemento_id', '=', 'categoria_implementos.id')
                            ->where('task_implements.requestdetailtask_id', $labor['requestdetailtask_id'])
                            ->get();
                    }else {
                        $implements = Implement::all();
                        $implement_select = $isCheck[0]->implement_id;
                    }



                    $taskImplements = TaskImplement::where('requestdetailtask_id',  $labor['requestdetailtask_id'])->count();
                    //// UBICACIONES E IMPLEMENTOS AL GUARDAR MODIFICA TODAS LAS TABLAS QUE RELACION AL TAREO.
                    //if( $taskImplements == 0 ) $implement_select = 282;

                    $data = Tareo::select('tareos.implement_id', 'tareos.machinerie_id', 'tareos.operator_id')
                                ->where('rdetailt_id', $labor['requestdetailtask_id'])
                                ->get();

                    if( count($data) > 0 ) {
                        $imp_id = $data[0]->implement_id;
                        $maq_id = $data[0]->machinerie_id;
                        $ope_id = $data[0]->operator_id;
                    }else {
                        $imp_id = $implement_select;
                        $maq_id = 0;
                        $ope_id = 0;
                    }

                    $d6 = array(
                        'implementos' => $implements,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'imp_id' => $imp_id,
                        //'imp_select' => $implement_select,
                        'dia' => $labor['dia'],
                        'timplementos' => $taskImplements
                    );

                    $maquinarias = Machinerie::select(
                        'machineries.id', 'machineries.nombre', 'machineries.code_abby'
                    )   ->join('categoria_maquinarias', 'categoria_maquinarias.id', '=', 'machineries.cat_maquinaria_id')
                        ->join('task_machineries', 'task_machineries.cat_maquinaria_id', '=', 'categoria_maquinarias.id')
                        ->where('task_machineries.requestdetailtask_id', $labor['requestdetailtask_id'])
                        ->get();

                    $d7 = array(
                        'maquinarias' => $maquinarias,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'maq_id' => $maq_id,
                        'dia' => $labor['dia'],
                        'timplementos' => $taskImplements
                    );

                    $operators = Person::select(
                        'people.id',
                        'users.nombres',
                        'users.apel_pat',
                        'users.apel_mat'
                    )
                        ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
                        ->join('users', 'users.id', '=', 'people.user_id')
                        ->where('people.typeperson_id', '=', 1)
                        ->orWhere('people.typeperson_id', '=', 2)
                        ->get();

                    $d8 = array(
                        'operarios' => $operators,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'ope_id' => $ope_id,
                        'dia' => $labor['dia'],
                        'timplementos' => $taskImplements
                    );

                    // foreach( $implements as $implement ) {
                    //     $implementos.= $implement['nombre'] .', ';
                    // }
                    // $d6 = trim($implementos, ', ');

                    $d9 = array(
                        'rdt_id'    => $labor['requestdetailtask_id'],
                        'labor_id'  => $labor['labor_id'],
                        'location_ids' => $ubicaciones_id,
                        'dia' => $labor['dia']
                    );

                    $d10 = $supervisor;
                    $d11 = $labor['area'];
                    $d12 = $labor['subarea'];

                    $supervisores = Person::select(
                        'people.id',
                        'users.nombres',
                        'users.apel_pat',
                        'users.apel_mat'
                    )
                        ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
                        ->join('users', 'users.id', '=', 'people.user_id')
                        ->where('people.typeperson_id', '=', 3)
                        ->get();
                    $sup_slt = TaskSupervisor::where('requestdetailtask_id', $labor['requestdetailtask_id'] )->get();
                    if( count($sup_slt) > 0 ) $sup_id = $sup_slt[0]->person_id;
                    else $sup_id = 0;

                    $d13 = array(
                        'supervisores' => $supervisores,
                        'sup_id' => $sup_id,
                        'dia' => $labor['dia'],
                    );

                    $resp[] = array(
                        'nro'   => $nro,
                        'area'  => $d11,
                        'subarea'  => $d12,
                        'dia'   => $d1,
                        'nombre_turno'  => $d2,
                        'labor' => $d3,
                        'requestdetailtask_id'=>$d4,
                        'ubicaciones'   =>$d5,
                        'supervisores' => $d13,
                        'implementos'   => $d6,
                        'maquinarias'   => $d7,
                        'operarios'     => $d8,
                        'operacion' => $d9,
                        'supervisor' => $d10
                    );

                    $nro++;
                }


        } catch (Exception $e) {

        }

        $response = array(
            'total'=> count($resp),
            'totalNotFiltered'=> count($resp),
            'rows'=> $resp
        );

        return response()->json($response);
    }

    public function list_supervisor($nro_semana, $anio, $person_id)
    {
        try {
            $query = RequestDetailTask::select(
                'request_detail_tasks.dia',
                'request_detail_tasks.nro_maquinas',
                'turnos.nombre AS nombre_turno',
                'tasks.nombre AS labor','tasks.id AS labor_id',
                'request_detail_tasks.id AS requestdetailtask_id',
                'request_detail_tasks.approved',
                //'locations.nombre AS location', 'locations.id AS location_id',
                'users.nombres', 'users.apel_pat', 'users.apel_mat',
                'areas.nombre as area', 'sub_areas.nombre as subarea'
            )
                ->join('request_details', 'request_details.id', '=', 'request_detail_tasks.requestdetail_id')
                ->join('turnos', 'turnos.id', '=', 'request_details.turno_id')
                ->join('tasks', 'tasks.id', '=', 'request_details.task_id')
                ->join('requests', 'requests.id', '=', 'request_details.request_id')
                ->join('people', 'people.id', '=', 'requests.person_id')
                ->join('users', 'users.id', '=', 'people.user_id')
                //->join('task_locations', 'task_locations.requestdetailtask_id', '=', 'request_detail_tasks.id')
                //->join('locations', 'locations.id', '=', 'task_locations.location_id')
                ->join('areas', 'areas.id', '=', 'people.area_id')
                ->join('sub_areas', 'sub_areas.id', '=', 'people.subarea_id')
                ->orderBy('request_detail_tasks.dia', 'DESC')
                ->orderBy('request_detail_tasks.created_at', 'ASC')
                ->where('requests.nro_semana', $nro_semana)
                ->where('requests.anio', $anio)
                ->where('requests.person_id', $person_id)
                ->where('request_detail_tasks.changes', 1)
                ->where('request_detail_tasks.flag', '<>', 3)
                ->get();
                $resp = array();
                $nro = 1;

                foreach($query as $labor) {
                    $d1 = $labor['dia'];
                    $d2 = $labor['nombre_turno'];
                    $d3 = $labor['labor'];
                    $d4 = $labor['requestdetailtask_id'];
                    $ubicaciones = '';
                    $ubicaciones_id = [];
                    $implementos = '';
                    $supervisor = $labor['apel_pat']. " " . $labor['apel_mat']. " - " . $labor['nombres'];

                    $locations = TaskLocation::select(
                        'locations.id', 'locations.nombre'
                    )   ->join('locations', 'locations.id', '=', 'task_locations.location_id')
                        ->where('task_locations.requestdetailtask_id', $labor['requestdetailtask_id'])
                        ->get();

                    $d5 = array(
                        'total' => count($locations),
                        'locations' => $locations
                    );

                    foreach( $locations as $loc ) {
                         $ubicaciones.= $loc['nombre'] .'<br> ';
                         $ubicaciones_id[] = array($loc['id']);
                    }
                    $d5 = array(
                        'nombres' => trim($ubicaciones, ', '),
                        'ids' => $ubicaciones_id
                    );

                    //$d5 = $labor['location'];
                    $isCheck = TaskImplement::where('task_implements.requestdetailtask_id', $labor['requestdetailtask_id'])->get();
                    if( count($isCheck) > 0 ) $isChecked = $isCheck[0]->checked;
                    else $isChecked = 0;

                    $implement_select = 0;

                    if( $isChecked == 0 ) {
                        $implements = Implement::select(
                            'implements.id', 'implements.nombre'
                        )   ->join('categoria_implementos', 'categoria_implementos.id', '=', 'implements.cat_implemento_id')
                            ->join('task_implements', 'task_implements.cat_implemento_id', '=', 'categoria_implementos.id')
                            ->where('task_implements.requestdetailtask_id', $labor['requestdetailtask_id'])
                            ->get();
                    }else {
                        $implements = Implement::all();
                        $implement_select = $isCheck[0]->implement_id;
                    }

                    $taskImplements = TaskImplement::where('requestdetailtask_id',  $labor['requestdetailtask_id'])->count();
                    //// UBICACIONES E IMPLEMENTOS AL GUARDAR MODIFICA TODAS LAS TABLAS QUE RELACION AL TAREO.

                    $data = Tareo::select('tareos.implement_id', 'tareos.machinerie_id', 'tareos.operator_id')
                                ->where('rdetailt_id', $labor['requestdetailtask_id'])
                                ->get();

                    if( count($data) > 0 ) {
                        $imp_id = $data[0]->implement_id;
                        $maq_id = $data[0]->machinerie_id;
                        $ope_id = $data[0]->operator_id;
                    }else {
                        $imp_id = $implement_select;
                        $maq_id = 0;
                        $ope_id = 0;
                    }

                    $d6 = array(
                        'implementos' => $implements,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'imp_id' => $imp_id,
                        //'imp_select' => $implement_select,
                        'dia' => $labor['dia'],
                        'timplementos' => $taskImplements
                    );

                    $maquinarias = Machinerie::select(
                        'machineries.id', 'machineries.nombre', 'machineries.code_abby'
                    )   ->join('categoria_maquinarias', 'categoria_maquinarias.id', '=', 'machineries.cat_maquinaria_id')
                        ->join('task_machineries', 'task_machineries.cat_maquinaria_id', '=', 'categoria_maquinarias.id')
                        ->where('task_machineries.requestdetailtask_id', $labor['requestdetailtask_id'])
                        ->get();

                    $d7 = array(
                        'maquinarias' => $maquinarias,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'maq_id' => $maq_id,
                        'dia' => $labor['dia'],
                        'timplementos' => $taskImplements
                    );

                    $operators = Person::select(
                        'people.id',
                        'users.nombres',
                        'users.apel_pat',
                        'users.apel_mat'
                    )
                        ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
                        ->join('users', 'users.id', '=', 'people.user_id')
                        ->where('people.typeperson_id', '=', 1)
                        ->orWhere('people.typeperson_id', '=', 2)
                        ->get();

                    $d8 = array(
                        'operarios' => $operators,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'ope_id' => $ope_id,
                        'dia' => $labor['dia'],
                        'timplementos' => $taskImplements
                    );

                    // foreach( $implements as $implement ) {
                    //     $implementos.= $implement['nombre'] .', ';
                    // }
                    // $d6 = trim($implementos, ', ');

                    $d9 = array(
                        'rdt_id'    => $labor['requestdetailtask_id'],
                        'labor_id'  => $labor['labor_id'],
                        'location_ids' => $ubicaciones_id,
                        'dia' => $labor['dia']
                    );

                    $d10 = $supervisor;
                    $d11 = $labor['area'];
                    $d12 = $labor['subarea'];

                    $supervisores = Person::select(
                        'people.id',
                        'users.nombres',
                        'users.apel_pat',
                        'users.apel_mat'
                    )
                        ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
                        ->join('users', 'users.id', '=', 'people.user_id')
                        ->where('people.typeperson_id', '=', 3)
                        ->get();
                    $sup_slt = TaskSupervisor::where('requestdetailtask_id', $labor['requestdetailtask_id'] )->get();
                    if( count($sup_slt) > 0 ) $sup_id = $sup_slt[0]->person_id;
                    else $sup_id = 0;

                    $d13 = array(
                        'supervisores' => $supervisores,
                        'sup_id' => $sup_id,
                        'dia' => $labor['dia'],
                    );

                    $resp[] = array(
                        'nro'   => $nro,
                        'area'  => $d11,
                        'subarea'  => $d12,
                        'dia'   => $d1,
                        'nombre_turno'  => $d2,
                        'labor' => $d3,
                        'requestdetailtask_id'=>$d4,
                        'ubicaciones'   =>$d5,
                        'supervisores' => $d13,
                        'implementos'   => $d6,
                        'maquinarias'   => $d7,
                        'operarios'     => $d8,
                        'operacion' => $d9,
                        'supervisor' => $d10
                    );

                    $nro++;
                }


        } catch (Exception $e) {

        }

        $response = array(
            'total'=> count($resp),
            'totalNotFiltered'=> count($resp),
            'rows'=> $resp
        );

        return response()->json($response);
    }

    public function list_area($nro_semana, $anio, $area_id)
    {
        try {
            $query = RequestDetailTask::select(
                'request_detail_tasks.dia',
                'request_detail_tasks.nro_maquinas',
                'turnos.nombre AS nombre_turno',
                'tasks.nombre AS labor','tasks.id AS labor_id',
                'request_detail_tasks.id AS requestdetailtask_id',
                'request_detail_tasks.approved',
                //'locations.nombre AS location', 'locations.id AS location_id',
                'users.nombres', 'users.apel_pat', 'users.apel_mat',
                'areas.nombre as area', 'sub_areas.nombre as subarea'
            )
                ->join('request_details', 'request_details.id', '=', 'request_detail_tasks.requestdetail_id')
                ->join('turnos', 'turnos.id', '=', 'request_details.turno_id')
                ->join('tasks', 'tasks.id', '=', 'request_details.task_id')
                ->join('requests', 'requests.id', '=', 'request_details.request_id')
                ->join('people', 'people.id', '=', 'requests.person_id')
                ->join('users', 'users.id', '=', 'people.user_id')
                //->join('task_locations', 'task_locations.requestdetailtask_id', '=', 'request_detail_tasks.id')
                //->join('locations', 'locations.id', '=', 'task_locations.location_id')
                ->join('areas', 'areas.id', '=', 'people.area_id')
                ->join('sub_areas', 'sub_areas.id', '=', 'people.subarea_id')
                ->orderBy('request_detail_tasks.dia', 'DESC')
                ->orderBy('request_detail_tasks.created_at', 'ASC')
                ->where('requests.nro_semana', $nro_semana)
                ->where('requests.anio', $anio)
                ->where('areas.id', $area_id)
                ->where('request_detail_tasks.changes', 1)
                ->where('request_detail_tasks.flag', '<>', 3)
                ->get();
                $resp = array();
                $nro = 1;

                foreach($query as $labor) {
                    $d1 = $labor['dia'];
                    $d2 = $labor['nombre_turno'];
                    $d3 = $labor['labor'];
                    $d4 = $labor['requestdetailtask_id'];
                    $ubicaciones = '';
                    $ubicaciones_id = [];
                    $implementos = '';
                    $supervisor = $labor['apel_pat']. " " . $labor['apel_mat']. " - " . $labor['nombres'];

                    $locations = TaskLocation::select(
                        'locations.id', 'locations.nombre'
                    )   ->join('locations', 'locations.id', '=', 'task_locations.location_id')
                        ->where('task_locations.requestdetailtask_id', $labor['requestdetailtask_id'])
                        ->get();

                    $d5 = array(
                        'total' => count($locations),
                        'locations' => $locations
                    );

                    foreach( $locations as $loc ) {
                         $ubicaciones.= $loc['nombre'] .'<br> ';
                         $ubicaciones_id[] = array($loc['id']);
                    }
                    $d5 = array(
                        'nombres' => trim($ubicaciones, ', '),
                        'ids' => $ubicaciones_id
                    );

                    //$d5 = $labor['location'];
                    $isCheck = TaskImplement::where('task_implements.requestdetailtask_id', $labor['requestdetailtask_id'])->get();
                    if( count($isCheck) > 0 ) $isChecked = $isCheck[0]->checked;
                    else $isChecked = 0;

                    $implement_select = 0;

                    if( $isChecked == 0 ) {
                        $implements = Implement::select(
                            'implements.id', 'implements.nombre'
                        )   ->join('categoria_implementos', 'categoria_implementos.id', '=', 'implements.cat_implemento_id')
                            ->join('task_implements', 'task_implements.cat_implemento_id', '=', 'categoria_implementos.id')
                            ->where('task_implements.requestdetailtask_id', $labor['requestdetailtask_id'])
                            ->get();
                    }else {
                        $implements = Implement::all();
                        $implement_select = $isCheck[0]->implement_id;
                    }

                    $taskImplements = TaskImplement::where('requestdetailtask_id',  $labor['requestdetailtask_id'])->count();
                    //// UBICACIONES E IMPLEMENTOS AL GUARDAR MODIFICA TODAS LAS TABLAS QUE RELACION AL TAREO.

                    $data = Tareo::select('tareos.implement_id', 'tareos.machinerie_id', 'tareos.operator_id')
                                ->where('rdetailt_id', $labor['requestdetailtask_id'])
                                ->get();

                    if( count($data) > 0 ) {
                        $imp_id = $data[0]->implement_id;
                        $maq_id = $data[0]->machinerie_id;
                        $ope_id = $data[0]->operator_id;
                    }else {
                        $imp_id = $implement_select;
                        $maq_id = 0;
                        $ope_id = 0;
                    }

                    $d6 = array(
                        'implementos' => $implements,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'imp_id' => $imp_id,
                        //'imp_select' => $implement_select,
                        'dia' => $labor['dia'],
                        'timplementos' => $taskImplements
                    );

                    $maquinarias = Machinerie::select(
                        'machineries.id', 'machineries.nombre', 'machineries.code_abby'
                    )   ->join('categoria_maquinarias', 'categoria_maquinarias.id', '=', 'machineries.cat_maquinaria_id')
                        ->join('task_machineries', 'task_machineries.cat_maquinaria_id', '=', 'categoria_maquinarias.id')
                        ->where('task_machineries.requestdetailtask_id', $labor['requestdetailtask_id'])
                        ->get();

                    $d7 = array(
                        'maquinarias' => $maquinarias,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'maq_id' => $maq_id,
                        'dia' => $labor['dia'],
                        'timplementos' => $taskImplements
                    );

                    $operators = Person::select(
                        'people.id',
                        'users.nombres',
                        'users.apel_pat',
                        'users.apel_mat'
                    )
                        ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
                        ->join('users', 'users.id', '=', 'people.user_id')
                        ->where('people.typeperson_id', '=', 1)
                        ->orWhere('people.typeperson_id', '=', 2)
                        ->get();

                    $d8 = array(
                        'operarios' => $operators,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'ope_id' => $ope_id,
                        'dia' => $labor['dia'],
                        'timplementos' => $taskImplements
                    );

                    // foreach( $implements as $implement ) {
                    //     $implementos.= $implement['nombre'] .', ';
                    // }
                    // $d6 = trim($implementos, ', ');

                    $d9 = array(
                        'rdt_id'    => $labor['requestdetailtask_id'],
                        'labor_id'  => $labor['labor_id'],
                        'location_ids' => $ubicaciones_id,
                        'dia' => $labor['dia']
                    );

                    $d10 = $supervisor;
                    $d11 = $labor['area'];
                    $d12 = $labor['subarea'];

                    $supervisores = Person::select(
                        'people.id',
                        'users.nombres',
                        'users.apel_pat',
                        'users.apel_mat'
                    )
                        ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
                        ->join('users', 'users.id', '=', 'people.user_id')
                        ->where('people.typeperson_id', '=', 3)
                        ->get();
                    $sup_slt = TaskSupervisor::where('requestdetailtask_id', $labor['requestdetailtask_id'] )->get();
                    if( count($sup_slt) > 0 ) $sup_id = $sup_slt[0]->person_id;
                    else $sup_id = 0;

                    $d13 = array(
                        'supervisores' => $supervisores,
                        'sup_id' => $sup_id,
                        'dia' => $labor['dia'],
                    );

                    $resp[] = array(
                        'nro'   => $nro,
                        'area'  => $d11,
                        'subarea'  => $d12,
                        'dia'   => $d1,
                        'nombre_turno'  => $d2,
                        'labor' => $d3,
                        'requestdetailtask_id'=>$d4,
                        'ubicaciones'   =>$d5,
                        'supervisores' => $d13,
                        'implementos'   => $d6,
                        'maquinarias'   => $d7,
                        'operarios'     => $d8,
                        'operacion' => $d9,
                        'supervisor' => $d10
                    );

                    $nro++;
                }


        } catch (Exception $e) {

        }

        $response = array(
            'total'=> count($resp),
            'totalNotFiltered'=> count($resp),
            'rows'=> $resp
        );

        return response()->json($response);
    }

    public function list_subarea($nro_semana, $anio, $subarea_id)
    {
        try {
            $query = RequestDetailTask::select(
                'request_detail_tasks.dia',
                'request_detail_tasks.nro_maquinas',
                'turnos.nombre AS nombre_turno',
                'tasks.nombre AS labor','tasks.id AS labor_id',
                'request_detail_tasks.id AS requestdetailtask_id',
                'request_detail_tasks.approved',
                //'locations.nombre AS location', 'locations.id AS location_id',
                'users.nombres', 'users.apel_pat', 'users.apel_mat',
                'areas.nombre as area', 'sub_areas.nombre as subarea'
            )
                ->join('request_details', 'request_details.id', '=', 'request_detail_tasks.requestdetail_id')
                ->join('turnos', 'turnos.id', '=', 'request_details.turno_id')
                ->join('tasks', 'tasks.id', '=', 'request_details.task_id')
                ->join('requests', 'requests.id', '=', 'request_details.request_id')
                ->join('people', 'people.id', '=', 'requests.person_id')
                ->join('users', 'users.id', '=', 'people.user_id')
                //->join('task_locations', 'task_locations.requestdetailtask_id', '=', 'request_detail_tasks.id')
                //->join('locations', 'locations.id', '=', 'task_locations.location_id')
                ->join('areas', 'areas.id', '=', 'people.area_id')
                ->join('sub_areas', 'sub_areas.id', '=', 'people.subarea_id')
                ->orderBy('request_detail_tasks.dia', 'DESC')
                ->orderBy('request_detail_tasks.created_at', 'ASC')
                ->where('requests.nro_semana', $nro_semana)
                ->where('requests.anio', $anio)
                ->where('sub_areas.id', $subarea_id)
                ->where('request_detail_tasks.changes', 1)
                ->where('request_detail_tasks.flag', '<>', 3)
                ->get();
                $resp = array();
                $nro = 1;

                foreach($query as $labor) {
                    $d1 = $labor['dia'];
                    $d2 = $labor['nombre_turno'];
                    $d3 = $labor['labor'];
                    $d4 = $labor['requestdetailtask_id'];
                    $ubicaciones = '';
                    $ubicaciones_id = [];
                    $implementos = '';
                    $supervisor = $labor['apel_pat']. " " . $labor['apel_mat']. " - " . $labor['nombres'];

                    $locations = TaskLocation::select(
                        'locations.id', 'locations.nombre'
                    )   ->join('locations', 'locations.id', '=', 'task_locations.location_id')
                        ->where('task_locations.requestdetailtask_id', $labor['requestdetailtask_id'])
                        ->get();

                    $d5 = array(
                        'total' => count($locations),
                        'locations' => $locations
                    );

                    foreach( $locations as $loc ) {
                         $ubicaciones.= $loc['nombre'] .'<br> ';
                         $ubicaciones_id[] = array($loc['id']);
                    }
                    $d5 = array(
                        'nombres' => trim($ubicaciones, ', '),
                        'ids' => $ubicaciones_id
                    );

                    //$d5 = $labor['location'];
                    $isCheck = TaskImplement::where('task_implements.requestdetailtask_id', $labor['requestdetailtask_id'])->get();
                    if( count($isCheck) > 0 ) $isChecked = $isCheck[0]->checked;
                    else $isChecked = 0;

                    $implement_select = 0;

                    if( $isChecked == 0 ) {
                        $implements = Implement::select(
                            'implements.id', 'implements.nombre'
                        )   ->join('categoria_implementos', 'categoria_implementos.id', '=', 'implements.cat_implemento_id')
                            ->join('task_implements', 'task_implements.cat_implemento_id', '=', 'categoria_implementos.id')
                            ->where('task_implements.requestdetailtask_id', $labor['requestdetailtask_id'])
                            ->get();
                    }else {
                        $implements = Implement::all();
                        $implement_select = $isCheck[0]->implement_id;
                    }

                    $taskImplements = TaskImplement::where('requestdetailtask_id',  $labor['requestdetailtask_id'])->count();
                    //// UBICACIONES E IMPLEMENTOS AL GUARDAR MODIFICA TODAS LAS TABLAS QUE RELACION AL TAREO.

                    $data = Tareo::select('tareos.implement_id', 'tareos.machinerie_id', 'tareos.operator_id')
                                ->where('rdetailt_id', $labor['requestdetailtask_id'])
                                ->get();

                    if( count($data) > 0 ) {
                        $imp_id = $data[0]->implement_id;
                        $maq_id = $data[0]->machinerie_id;
                        $ope_id = $data[0]->operator_id;
                    }else {
                        $imp_id = $implement_select;
                        $maq_id = 0;
                        $ope_id = 0;
                    }

                    $d6 = array(
                        'implementos' => $implements,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'imp_id' => $imp_id,
                        //'imp_select' => $implement_select,
                        'dia' => $labor['dia'],
                        'timplementos' => $taskImplements
                    );

                    $maquinarias = Machinerie::select(
                        'machineries.id', 'machineries.nombre', 'machineries.code_abby'
                    )   ->join('categoria_maquinarias', 'categoria_maquinarias.id', '=', 'machineries.cat_maquinaria_id')
                        ->join('task_machineries', 'task_machineries.cat_maquinaria_id', '=', 'categoria_maquinarias.id')
                        ->where('task_machineries.requestdetailtask_id', $labor['requestdetailtask_id'])
                        ->get();

                    $d7 = array(
                        'maquinarias' => $maquinarias,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'maq_id' => $maq_id,
                        'dia' => $labor['dia'],
                        'timplementos' => $taskImplements
                    );

                    $operators = Person::select(
                        'people.id',
                        'users.nombres',
                        'users.apel_pat',
                        'users.apel_mat'
                    )
                        ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
                        ->join('users', 'users.id', '=', 'people.user_id')
                        ->where('people.typeperson_id', '=', 1)
                        ->orWhere('people.typeperson_id', '=', 2)
                        ->get();

                    $d8 = array(
                        'operarios' => $operators,
                        'nro_maquinas' => $labor['nro_maquinas'],
                        'ope_id' => $ope_id,
                        'dia' => $labor['dia'],
                        'timplementos' => $taskImplements
                    );

                    // foreach( $implements as $implement ) {
                    //     $implementos.= $implement['nombre'] .', ';
                    // }
                    // $d6 = trim($implementos, ', ');

                    $d9 = array(
                        'rdt_id'    => $labor['requestdetailtask_id'],
                        'labor_id'  => $labor['labor_id'],
                        'location_ids' => $ubicaciones_id,
                        'dia' => $labor['dia']
                    );

                    $d10 = $supervisor;
                    $d11 = $labor['area'];
                    $d12 = $labor['subarea'];

                    $supervisores = Person::select(
                        'people.id',
                        'users.nombres',
                        'users.apel_pat',
                        'users.apel_mat'
                    )
                        ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
                        ->join('users', 'users.id', '=', 'people.user_id')
                        ->where('people.typeperson_id', '=', 3)
                        ->get();
                    $sup_slt = TaskSupervisor::where('requestdetailtask_id', $labor['requestdetailtask_id'] )->get();
                    if( count($sup_slt) > 0 ) $sup_id = $sup_slt[0]->person_id;
                    else $sup_id = 0;

                    $d13 = array(
                        'supervisores' => $supervisores,
                        'sup_id' => $sup_id,
                        'dia' => $labor['dia'],
                    );

                    $resp[] = array(
                        'nro'   => $nro,
                        'area'  => $d11,
                        'subarea'  => $d12,
                        'dia'   => $d1,
                        'nombre_turno'  => $d2,
                        'labor' => $d3,
                        'requestdetailtask_id'=>$d4,
                        'ubicaciones'   =>$d5,
                        'supervisores' => $d13,
                        'implementos'   => $d6,
                        'maquinarias'   => $d7,
                        'operarios'     => $d8,
                        'operacion' => $d9,
                        'supervisor' => $d10
                    );

                    $nro++;
                }


        } catch (Exception $e) {

        }

        $response = array(
            'total'=> count($resp),
            'totalNotFiltered'=> count($resp),
            'rows'=> $resp
        );

        return response()->json($response);
    }
}
