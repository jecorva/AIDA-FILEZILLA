<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueryController extends Controller
{
    //
    public function index($sem)
    {
       try {
            $rows = DB::select(
              'SELECT r.nro_semana, rd.task_id, rdt.dia, t.horometro_inicio AS HR_GRNAPP, t.horometro_fin AS HR_GRNAPPF,
                    gro.horometro_inicio AS HRINICIO, gro.hm_inicio_task AS HRINICIOLABOR, gro.horometro_fin AS HRFIN,
                    (SELECT horometro_recogida FROM task_machineries WHERE requestdetailtask_id=rdt.id ) AS HRRECO,
                    (SELECT horometro_parking FROM task_machineries WHERE requestdetailtask_id=rdt.id ) AS HRPARK
                FROM requests r
                INNER JOIN request_details rd ON rd.request_id = r.id
                INNER JOIN request_detail_tasks rdt ON rdt.requestdetail_id = rd.request_id
                INNER JOIN tareos t ON t.rdetailt_id = rdt.id
                INNER JOIN tareo_groups tg ON tg.tareo_id = t.id
                INNER JOIN groups gro ON gro.group_id = tg.group_id
                WHERE r.nro_semana = '.$sem  
            );

            $response = array(
                'status' => 200,
                'response' => $rows
            );

        }catch(Exception $e) {
            $response = array(
                'status' => 402,
                'response' => $e->getMessage()
            );
        }

        return response()
                // ->withHeaders([
                //     'Access-Control-Allow-Origin' => '*',
                //     'Access-Control-Allow-Headers' => 'X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method',
                //     'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS, PUT, DELETE'
                // ])
                ->json($response);
    }
}
