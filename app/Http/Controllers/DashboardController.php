<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\RequestDetailTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $nombre_host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
        $clientIP = request()->ip();

        // Total de labores del día
        $fechaActual = date('Y-m-d');
        $numLab = RequestDetailTask::select(
            'request_detail_tasks.id'
        )->where('dia', $fechaActual)
            ->where('changes', 1)
            ->where('ischange', 'N')
            ->count();

        // Total de operarios registrados
        $numOper = Person::select()->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
            ->orWhere('person_types.id', 1)
            ->orWhere('person_types.id', 2)
            ->count();

        $hoy = date('d-m-Y');

        $totalTareos = DB::select("SELECT COUNT(*) AS total FROM tareos WHERE rdetailt_id IN (SELECT id FROM request_detail_tasks RDT WHERE dia='$fechaActual' AND  changes=1 AND ischange='N')");
        $totalTareos = json_encode($totalTareos);
        $pendientes = DB::select("SELECT COUNT(*) AS total FROM tareos WHERE rdetailt_id IN (SELECT id FROM request_detail_tasks RDT WHERE dia='$fechaActual' AND  changes=1 AND ischange='N') AND state_id =1");
        $pendientes = json_encode($pendientes);
        $traslado = DB::select("SELECT count(*) AS total FROM tareos WHERE rdetailt_id IN (SELECT id FROM request_detail_tasks RDT WHERE dia='$fechaActual' AND changes=1 AND ischange='N') AND state_id =2");
        $traslado = json_encode($traslado);
        $procesos = DB::select("SELECT COUNT(*) AS total FROM tareos WHERE rdetailt_id IN (SELECT id FROM request_detail_tasks RDT WHERE dia='$fechaActual' AND changes=1 AND ischange='N') AND state_id =3");
        $procesos = json_encode($procesos);
        $pendaprob = DB::select("SELECT COUNT(*) AS total FROM tareos WHERE rdetailt_id IN (SELECT id FROM request_detail_tasks RDT WHERE dia='$fechaActual' AND changes=1 AND ischange='N') AND state_id =4");
        $pendaprob = json_encode($pendaprob);
        $aprobado = DB::select("SELECT COUNT(*) AS total FROM tareos WHERE rdetailt_id IN (SELECT id FROM request_detail_tasks RDT WHERE dia='$fechaActual' AND changes=1 AND ischange='N') AND state_id =5");
        $aprobado = json_encode($aprobado);


        return view('dashboard.index')
            ->with('mycpu', $clientIP)
            ->with('numLab', $numLab)
            ->with('numOper', $numOper)
            ->with('hoy', $hoy)
            ->with('pendientes', $pendientes)
            ->with('pendaprob', $pendaprob)
            ->with('aprobado', $aprobado)
            ->with('traslado', $traslado)
            ->with('totalTareos', $totalTareos)
            ->with('procesos', $procesos);
    }
}
