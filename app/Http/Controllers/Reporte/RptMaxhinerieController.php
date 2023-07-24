<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Controllers\Controller;
use App\Models\Machinerie;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RptMaxhinerieController extends Controller
{
    //
    public function index()
    {
        $maquinarias = Machinerie::all();

        return view('reports.Machinerie')
                ->with('maquinarias', $maquinarias);
    }

    public function list_maquinaria()
    {
        try {
            $rows = DB::select(
                'SELECT Gru.group_id AS GROUPID, Tar.id AS TAREOID, TasMac.horometro_recogida AS HMRECOGIDA, 
                    Gru.hm_inicio_task AS HMINICIOLABOR, Mac.code_abby AS ABBY, Mac.nombre AS MAQUINARIA, Loc.nombre AS LOCATION,
                    Tar.horometro_inicio AS HMINICIOTAREO, Tar.horometro_fin AS HMFINTAREO,
                    Gru.horometro_fin AS HMFINLABOR, TasMac.horometro_parking AS HMPARKING
                FROM groups Gru
                INNER JOIN tareo_groups TarGru ON TarGru.group_id = Gru.group_id
                INNER JOIN tareos Tar ON Tar.id = TarGru.tareo_id
                INNER JOIN locations Loc ON Loc.id = Tar.location_id
                INNER JOIN task_machineries TasMac ON TasMac.requestdetailtask_id = Tar.rdetailt_id
                INNER JOIN machineries Mac ON Mac.id = TasMac.machinerie_id
                ORDER BY Tar.created_at DESC');
            
            $response = array(
                'total'=> count($rows),
                'totalNotFiltered'=> count($rows),
                'rows'=> $rows
            );

        }catch(Exception $e) {
            return array(
                'status' => 402,
                'response' => $e->getMessage()
            );
        }

        return response()->json($response);
    }

    public function list_search(Request $request)
    {
        $fecInicio = $request->dateInicio != '' ? $request->dateInicio : '2023-01-01';
        $fecFin = $request->dateFin != '' ? $request->dateFin : '2023-12-31';
        $maquinaria_ID = $request->maquinaria_ID;

        if( $maquinaria_ID == '0') {            
            $where = "WHERE RDT.dia BETWEEN '". $fecInicio."' AND '".$fecFin."'";
        }else {
            $where = "WHERE Mac.id = '".$maquinaria_ID."' AND RDT.dia BETWEEN '". $fecInicio."' AND '".$fecFin."'";
        }

        try {
            $rows = DB::select(
                "SELECT Gru.group_id AS GROUPID, Tar.id AS TAREOID, TasMac.horometro_recogida AS HMRECOGIDA, 
                    Gru.hm_inicio_task AS HMINICIOLABOR, Mac.code_abby AS ABBY, Mac.nombre AS MAQUINARIA, Loc.nombre AS LOCATION,
                    Tar.horometro_inicio AS HMINICIOTAREO, Tar.horometro_fin AS HMFINTAREO,
                    Gru.horometro_fin AS HMFINLABOR, TasMac.horometro_parking AS HMPARKING
                FROM groups Gru
                    INNER JOIN tareo_groups TarGru ON TarGru.group_id = Gru.group_id
                    INNER JOIN tareos Tar ON Tar.id = TarGru.tareo_id
                    INNER JOIN request_detail_tasks RDT ON RDT.id = Tar.rdetailt_id
                    INNER JOIN locations Loc ON Loc.id = Tar.location_id
                    INNER JOIN task_machineries TasMac ON TasMac.requestdetailtask_id = Tar.rdetailt_id
                    INNER JOIN machineries Mac ON Mac.id = TasMac.machinerie_id
                ". $where ." 
                ORDER BY Tar.created_at DESC "
            );

            $response = array(
                'total'=> count($rows),
                'totalNotFiltered'=> count($rows),
                'rows'=> $rows
            );

        }catch(Exception $e) {
            $response = array(
                'status'    => 402,
                'response'  => $e->getMessage()
            );
        }

        return response()->json($response);
    }
}
