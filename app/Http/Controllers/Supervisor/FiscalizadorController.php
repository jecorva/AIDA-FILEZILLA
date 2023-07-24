<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Request as ModelsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FiscalizadorController extends Controller
{
    //
    public function index()
    {
        $user_id = Auth::user()->id;
        $anio = date('Y');
        $semanas = ModelsRequest::select(
            'requests.nro_semana'
        )   ->where('requests.anio', $anio)
            ->orderBy('requests.nro_semana', 'DESC')            
            ->distinct()
            ->get();

        return view('supervisor.fiscalizador')
                ->with('user_id', $user_id)
                ->with('semanas', $semanas)
                ->with('', '');
    }

    public function list_tareos(Request $request)
    {  
        $person_ID = Person::where('user_id', $request->user_id)->get();
        $person_ID = $person_ID[0];
        $person_ID = $person_ID->id;

        $registros = DB::select(
            'SELECT t.id as TAREOID, 
                t.task_id, tas.nombre as nam_labor, 
                t.location_id, loc.nombre as nam_ubicacion,
                t.implement_id, imp.nombre as nam_implement, 
                t.operator_id, t.machinerie_id, t.state_id,
                usu.dni, concat(usu.nombres, " ",usu.apel_pat, " ", usu.apel_mat) as operador,
                mac.nombre as nam_machinerie,
                sta.name as estado, sta.color
            FROM tareos t
            INNER JOIN request_detail_tasks ta ON ta.id = t.rdetailt_id
            INNER JOIN request_details rd ON rd.id = ta.requestdetail_id
            INNER JOIN requests re ON re.id = rd.request_id 
            inner join tasks tas on tas.id = t.task_id
            inner join locations loc on loc.id = t.location_id
            inner join implements imp on imp.id = t.implement_id
            inner join people peo on peo.id = t.operator_id
            inner join users usu on usu.id = peo.user_id
            inner join machineries mac on mac.id = t.machinerie_id
            inner join tareo_states sta on sta.id = t.state_id
            WHERE re.person_id = 4 AND re.nro_semana = '. $request->sem .';'
        );

        ///WHERE re.person_id = '. $person_ID .' AND re.nro_semana = '. $request->sem .';'


        return response()->json($registros);
    }
}
