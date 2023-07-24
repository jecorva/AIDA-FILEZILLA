<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParadasController extends Controller
{
    //
    public function index()
    {
        return view('reports.Paradas');
    }

    public function list()
    {
        try {
            $rows = DB::select(
                'SELECT Gru.group_id, Tar.id AS tareo_id,
                    (SELECT nombre FROM tasks WHERE id = Tar.task_id) AS labor, 
                    (SELECT nombre FROM implements WHERE id = Tar.implement_id) AS implemento,
                    (SELECT nombre FROM locations WHERE id = Tar.location_id) AS ubicacion,
                    GruSto.stop_id, StoCat.descripcion AS cat_parada , Sto.descripcion AS stop_desc, GruSto.observacion, GruSto.hm_inicio, GruSto.hm_fin
                FROM tareo_groups TarGru
                    INNER JOIN groups Gru ON TarGru.group_id = Gru.group_id
                    INNER JOIN tareos Tar ON Tar.id = TarGru.tareo_id
                    INNER JOIN group_stops GruSto ON GruSto.group_id = Gru.group_id
                    INNER JOIN stops Sto ON Sto.id = GruSto.stop_id
                    INNER JOIN stop_categories StoCat ON StoCat.id = Sto.catstop_id                    
                WHERE Tar.state_id=6 ');
            
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
        $fecInicio = $request->dateInicio;
        $fecFin = $request->dateFin;

        try {
            $rows = DB::select(
                "SELECT Gru.group_id, Tar.id AS tareo_id, RDT.dia,
                    (SELECT nombre FROM tasks WHERE id = Tar.task_id) AS labor, 
                    (SELECT nombre FROM implements WHERE id = Tar.implement_id) AS implemento,
                    (SELECT code_abby FROM implements WHERE id = Tar.implement_id) AS imp_abby,
                    (SELECT nombre FROM locations WHERE id = Tar.location_id) AS ubicacion,
                    GruSto.stop_id, StoCat.descripcion AS cat_parada , Sto.descripcion AS stop_desc, GruSto.observacion, GruSto.hm_inicio, GruSto.hm_fin
                FROM tareo_groups TarGru
                    INNER JOIN groups Gru ON TarGru.group_id = Gru.group_id
                    INNER JOIN tareos Tar ON Tar.id = TarGru.tareo_id
                    INNER JOIN group_stops GruSto ON GruSto.group_id = Gru.group_id
                    INNER JOIN stops Sto ON Sto.id = GruSto.stop_id
                    INNER JOIN stop_categories StoCat ON StoCat.id = Sto.catstop_id
                    INNER JOIN request_detail_tasks RDT ON RDT.id = Tar.rdetailt_id                    
                WHERE Tar.state_id=6 AND RDT.dia BETWEEN '".$fecInicio."' AND '".$fecFin."'"              
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
