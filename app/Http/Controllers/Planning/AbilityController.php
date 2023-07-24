<?php

namespace App\Http\Controllers\Planning;

use App\Http\Controllers\Controller;
use App\Models\Ability;
use App\Models\Implement;
use App\Models\Machinerie;
use App\Models\Person;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbilityController extends Controller
{
    //
    public function index()
    {
        return view('planificacion.ability.index');
    }

    public function list()
    {
        //$data = array();
        $operators = Ability::select(
            'abilities.id', 'abilities.flag',
            'users.dni', 'users.nombres', 'users.apel_pat', 'users.apel_mat',
            'areas.nombre as area', 'person_types.nombre as tipo', 'machineries.nombre as maquinaria', 'implements.nombre as implemento' 
        )   ->join('machineries', 'machineries.id', '=', 'abilities.machinerie_id')
            ->join('implements', 'implements.id', '=', 'abilities.implement_id')
            ->join('people', 'people.id', '=', 'abilities.person_id')
            ->join('users', 'users.id', '=', 'people.user_id')
            ->join('areas', 'areas.id', '=', 'people.area_id')
            ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
            ->orderBy('users.apel_pat')
            ->orderBy('implements.nombre')
            ->orderBy('machineries.nombre')
            ->get();
            
        
        return view('planificacion.ability.components.table')
                ->with('operarios', $operators);
    }

    public function create()
    {
        $status = "";

        try {
            $persons = Person::select(
                'people.id', 
                'users.dni', 'users.nombres', 'users.apel_pat', 'users.apel_mat',
                'areas.nombre as area',
                'person_types.nombre as tipo'
            )   ->join('users', 'users.id', '=', 'people.user_id')
                ->join('areas', 'areas.id', '=', 'people.area_id')
                ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
                ->where('person_types.id', 1)
                ->orWhere('person_types.id', 2)
                ->orderBy('users.apel_pat', 'ASC')
                ->get();

                $machineries = Machinerie::where('flag', 1)->orderBy('nombre')->get();
                $implements = Implement::where('flag', 1)->orderBy('nombre')->get();
                $status = "402";

        }catch(Exception $e) {
            $status = "303";
        }       
        
        return view('planificacion.ability.components.create')
                ->with('status', $status)
                ->with('maquinarias', $machineries)
                ->with('implementos', $implements)
                ->with('trabajadores', $persons);
    }

    public function get_person(Request $request)
    {
        $data = array();
        try {
            $person = Person::select(
                'people.id', 
                'users.dni', 'users.nombres', 'users.apel_pat', 'users.apel_mat',
                'areas.nombre as area',
                'person_types.nombre as tipo'
            )   ->join('users', 'users.id', '=', 'people.user_id')
                ->join('areas', 'areas.id', '=', 'people.area_id')
                ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
                ->where('people.id', intval($request->id))
                ->orderBy('users.apel_pat')
                ->get();
                $data = array (
                    'status'=> '402',
                    'response' => $person[0]
                );
                $data = json_encode($data, true);

        }catch(Exception $e) {
            $data = array (
                'status'=> '303',
                'response' => 'error'
            );
            $data = json_encode($data, true);
        }
        return $data;
    }

    public function save(Request $request)
    {
        $id_trabajador = $request->id_w;
        $id_maquinaria = $request->id_m;
        $id_implemento = $request->id_i;
        $status = "";

        $query = "SELECT COUNT(id) AS 'total' 
                FROM abilities 
                WHERE person_id='$id_trabajador' AND machinerie_id='$id_maquinaria' AND implement_id='$id_implemento'";
        try{
            $response = DB::select($query);
            $total = $response[0]->total;

            $status = $total == 0 ? "402" : "502";

        }catch(Exception $e) {
            $status = "302-1";
        }

        if( $status == "402" ){
            $operador = new Ability();
            $operador->person_id = $id_trabajador;
            $operador->machinerie_id = $id_maquinaria;
            $operador->implement_id = $id_implemento;
            $operador->created_at =  date('Y-m-d H:i:s');
            $operador->updated_at = date('Y-m-d H:i:s');

            try{
                $operador->save();
                $status = "402";

            }catch(Exception $e) {
                $status = $e->getMessage();
            }

        }else {
            return $status;
        }

        return $status;
    }

    public function update(Request $request)
    {
        $id = $request->id; // Id-Habilidades
        $habilidad = Ability::findOrFail($id);
        $response = "";

        try{
            $habilidad->flag = $request->flag;
            $habilidad->updated_at = date('Y-m-d H:i:s');
            $habilidad->save();

            $response = "402";

        }catch(Exception $e) {
            $response = "303";
        }

        return $response;
    }
}
