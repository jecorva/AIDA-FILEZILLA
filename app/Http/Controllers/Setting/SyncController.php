<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\NisiraMigrate;
use App\Models\Request as ModelsRequest;
use App\Models\RequestDetailTask;
use Exception;
use Illuminate\Database\PDO\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

class SyncController extends Controller
{
    //
    public function index()
    {
        $anio = date('Y');
        // ItemID 18: ID Sync-NISIRA
        $menu = MenuItem::select(
            'menu.key as KeyMenu',
            'items.key as KeySubmenu'
        ) ->join('menu', 'menu.id', '=', 'menu_items.menu_id')
            ->join('items', 'items.id', '=', 'menu_items.item_id')
            ->where('menu_items.item_id', 18)
            ->get();

        $semanas = ModelsRequest::select(
            'requests.nro_semana'
        )   ->where('requests.anio', $anio)            
            ->orderBy('requests.nro_semana', 'DESC')            
            ->distinct()
            ->get();

        return view('sync.Sync')
                ->with('menu', $menu)
                ->with('semanas', $semanas);
    }

    public function list_date()
    {
        $data = array();        

        $dates = RequestDetailTask::select(
            'request_detail_tasks.dia'
        )   ->groupBy('request_detail_tasks.dia')
            ->orderBy('request_detail_tasks.dia', 'DESC')
            ->get();
        $nro = 1;
        foreach( $dates as $date ) {
            $dia = date('l', strtotime($date->dia));
            
            switch($dia) {
                case $dia == 'Monday': 
                    $dia = 'Lunes';
                    break;
                case $dia == 'Tuesday': 
                    $dia = 'Martes';
                    break;
                case $dia == 'Wednesday': 
                    $dia = 'Miercoles';
                    break;
                case $dia == 'Thursday': 
                    $dia = 'Jueves';
                    break;
                case $dia == 'Friday': 
                    $dia = 'Viernes';
                    break;
                case $dia == 'Saturday': 
                    $dia = 'Sábado';
                    break;
                case $dia == 'Sunday': 
                    $dia = 'Domingo';
                    break;
            }            

            $migrate = NisiraMigrate::where('fecha', $date->dia)
                                        ->count();

            $data_row = array(
                'fecha' => $date->dia,
                'status'=> $migrate
            );                            

            $data[] = array(
                'nro'   => $nro,
                'dia'   => $dia,                
                'fecha' => $date->dia,
                'status' => $migrate,
                'operator' => $data_row
            );
            $nro++;
        }

        
        $response = array(
            'total'=> count($data),
            'totalNotFiltered'=> count($data),
            'rows'=> $data
        );       

        return response()->json($response);
    }

    // Select tabla semana
    public function list_semana(Request $request)
    {
        $nrosemana = $request->nro_semana;
        $data = array();  
        
        try{
            $dtllSemana = RequestDetailTask::select(
                'request_detail_tasks.dia'
            )   ->join('request_details', 'request_details.id', '=', 'request_detail_tasks.requestdetail_id')
                ->join('requests', 'requests.id', '=', 'request_details.request_id')
                ->where('requests.nro_semana', $nrosemana)
                ->where('request_detail_tasks.flag', 2)
                ->groupBy('request_detail_tasks.dia')
                ->orderBy('request_detail_tasks.dia', 'DESC')
                ->get();

        }catch(Exception $e) {
            $dtllSemana = $e->getMessage();
        }

        $nro = 1;
        foreach( $dtllSemana as $date ) {
            $dia = date('l', strtotime($date->dia));
            
            switch($dia) {
                case $dia == 'Monday': 
                    $dia = 'Lunes';
                    break;
                case $dia == 'Tuesday': 
                    $dia = 'Martes';
                    break;
                case $dia == 'Wednesday': 
                    $dia = 'Miercoles';
                    break;
                case $dia == 'Thursday': 
                    $dia = 'Jueves';
                    break;
                case $dia == 'Friday': 
                    $dia = 'Viernes';
                    break;
                case $dia == 'Saturday': 
                    $dia = 'Sábado';
                    break;
                case $dia == 'Sunday': 
                    $dia = 'Domingo';
                    break;
            }            

            $migrate = NisiraMigrate::where('fecha', $date->dia)->count();
            $diaconsulta = $date->dia;

            $rows = DB::select("SELECT  Tar.id, Usu.dni AS IDTRABAJADOR, Are.code_nisira AS IDACTIVIDADEXTERNO, RDT.dia AS FECHATAREO, Mac.code_abby AS IDMAQUINARIAEXTERNO, ParIni.id AS IDLUGAR_SALIDACAMPO,
            TasMac.horometro_recogida AS HOROMETRO_SALIDACAMPO, ParFin.id AS IDLUGAR_ENTREGAPARQUEO, TasMac.horometro_parking AS HOROMETRO_ENTREGAPARQUEO, Lab.code_nisira AS IDLABOREXTERNO,
            Imp.code_abby AS IDIMPLEMENTOEXTERNO, Tar.horometro_inicio AS INICIO_HOROMETROLABOR, Tar.horometro_fin AS FIN_HOROMETROLABOR, SupUsu.dni AS IDSUPERVISOR_LABOR,
            Tar.obs_rejected AS OBSSUPERVISOR_LABOR, 
            (	SELECT avance_plan 
                FROM task_locations
                WHERE requestdetailtask_id = RDT.id AND location_id = Tar.location_id ) AS AVANCEDIURNO_PLANIFICADO,
            (	SELECT UndMed.siglas
                FROM request_details RD
                INNER JOIN unit_measures UndMed ON UndMed.id = RD.um_id
                WHERE RD.id = RDT.requestdetail_id ) AS observaciones,
                    Tar.avance AS AVANCEDIURNO
                FROM  people Per
                INNER JOIN users Usu ON Usu.id = Per.user_id
                INNER JOIN person_types PerTyp ON PerTyp.id = Per.typeperson_id
                INNER JOIN areas Are ON Are.id = Per.area_id
                INNER JOIN tareos Tar ON Tar.operator_id = Per.id
                INNER JOIN request_detail_tasks RDT ON RDT.id = Tar.rdetailt_id
                INNER JOIN machineries Mac ON Mac.id = Tar.machinerie_id
                INNER JOIN task_machineries TasMac ON TasMac.requestdetailtask_id = RDT.id
                INNER JOIN task_supervisors TasSup ON TasSup.requestdetailtask_id = RDT.id
                INNER JOIN people Sup ON Sup.id = TasSup.person_id
                INNER JOIN users SupUsu ON SupUsu.id = Sup.user_id
                INNER JOIN parkings ParIni ON ParIni.id = Mac.parking_id
                INNER JOIN parkings ParFin ON ParFin.id = TasMac.parking_id
                INNER JOIN locations Loc ON Loc.id = Tar.location_id
                INNER JOIN implements Imp ON Imp.id = Tar.implement_id
                INNER JOIN tasks Lab ON Lab.id = Tar.task_id       
                
                WHERE PerTyp.id = 1 AND RDT.dia =  '$diaconsulta' AND Tar.state_id=6 AND RDT.changes=1");

            $data_row = array(
                'fecha' => $date->dia,
                'status'=> $migrate,
                'tareos'=>count($rows),
            );                            

            $data[] = array(
                'nro'   => $nro,
                'dia'   => $dia,                
                'fecha' => $date->dia,
                'tareos' => count($rows),
                'status' => $migrate,
                'operator' => $data_row
            );
            $nro++;
        }
        
        $response = array(
            'total'=> count($data),
            'totalNotFiltered'=> count($data),
            'rows'=> $data
        );       

        return response()->json($response);
    }

    // Detalle de tabla
    public function list_sync(Request $request)
    {
        $diaconsulta = $request->fecha;

        $data = DB::select("SELECT  Tar.id, Usu.dni AS IDTRABAJADOR, Are.code_nisira AS IDACTIVIDADEXTERNO, RDT.dia AS FECHATAREO, Mac.code_abby AS IDMAQUINARIAEXTERNO, ParIni.id AS IDLUGAR_SALIDACAMPO,
        TasMac.horometro_recogida AS HOROMETRO_SALIDACAMPO, ParFin.id AS IDLUGAR_ENTREGAPARQUEO, TasMac.horometro_parking AS HOROMETRO_ENTREGAPARQUEO, Lab.code_nisira AS IDLABOREXTERNO,
        Imp.code_abby AS IDIMPLEMENTOEXTERNO, Tar.horometro_inicio AS INICIO_HOROMETROLABOR, Tar.horometro_fin AS FIN_HOROMETROLABOR, SupUsu.dni AS IDSUPERVISOR_LABOR,
        Tar.obs_rejected AS OBSSUPERVISOR_LABOR, 
        (	SELECT avance_plan 
            FROM task_locations
            WHERE requestdetailtask_id = RDT.id AND location_id = Tar.location_id ) AS AVANCEDIURNO_PLANIFICADO,
        (	SELECT UndMed.siglas
            FROM request_details RD
            INNER JOIN unit_measures UndMed ON UndMed.id = RD.um_id
            WHERE RD.id = RDT.requestdetail_id ) AS observaciones,
                Tar.avance AS AVANCEDIURNO         
                
            FROM  people Per
            INNER JOIN users Usu ON Usu.id = Per.user_id
            INNER JOIN person_types PerTyp ON PerTyp.id = Per.typeperson_id
            INNER JOIN areas Are ON Are.id = Per.area_id
            INNER JOIN tareos Tar ON Tar.operator_id = Per.id
            INNER JOIN request_detail_tasks RDT ON RDT.id = Tar.rdetailt_id
            INNER JOIN machineries Mac ON Mac.id = Tar.machinerie_id
            INNER JOIN task_machineries TasMac ON TasMac.requestdetailtask_id = RDT.id
            INNER JOIN task_supervisors TasSup ON TasSup.requestdetailtask_id = RDT.id
            INNER JOIN people Sup ON Sup.id = TasSup.person_id
            INNER JOIN users SupUsu ON SupUsu.id = Sup.user_id
            INNER JOIN parkings ParIni ON ParIni.id = Mac.parking_id
            INNER JOIN parkings ParFin ON ParFin.id = TasMac.parking_id
            INNER JOIN locations Loc ON Loc.id = Tar.location_id
            INNER JOIN implements Imp ON Imp.id = Tar.implement_id
            INNER JOIN tasks Lab ON Lab.id = Tar.task_id       
            
            WHERE PerTyp.id = 1 AND RDT.dia =  '$diaconsulta' AND Tar.state_id=6 AND RDT.changes=1");

            $response = array(
                'total'=> count($data),
                'totalNotFiltered'=> count($data),
                'rows'=> $data
            );       

            return response()->json($response);
    }

    public function migrate(Request $request)
    {
        define('DB_HOST', '10.10.100.155');
        define('DB_USER', 'desarrollador');
        define('DB_PASS', '-D3s4rr0ll0Ec0-');
        define('DB_NAME', 'Nisira_Dev');                

        try {
            $pdo = new PDO(
                "sqlsrv:Server=" . DB_HOST . ";Database=" . DB_NAME,
                DB_USER,
                DB_PASS,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // return array(                
            //     'status'=> 402,
            //     'response' => 'Conexión exitosa'
            // );
        } catch (PDOException $e) {
            return array(                
                'status'=> 402,
                'response' => $e->getMessage()
            );
        }

        $migrate = DB::table('nisira_migrates')
                        ->where('fecha', $request->fecha)
                        ->exists();
        $resp = [];
        
        if( !$migrate ) {
            $migrate = new NisiraMigrate();
            $migrate->fecha = $request->fecha;
            $migrate->created_at =  date('Y-m-d H:i:s');    
            $migrate->updated_at = date('Y-m-d H:i:s'); 
            try{
                $migrate->save();
                $data = 200;
    
            }catch(Exception $e) {
                return array(                
                    'status'=> 402,
                    'response' => $e->getMessage()
                );
            }

            ///////////////// [ CONNECT NISIRA ] //////////////
            $IDEMPRESA = '001'; // Ecosac Agricola
            $IDEMPRESAEXTERNO = '';

            try {
                $query = "SELECT IDEMPRESA FROM EMPRESAS WHERE IDEMPRESA= :ID";            
                // $resp = array();
                $stmt = $pdo->prepare($query);            
                $stmt->bindParam(':ID', $IDEMPRESA); 
                if( $stmt->execute() === true ) {
                    if( $row = $stmt->fetch(PDO::FETCH_OBJ) ) {
                        $IDEMPRESAEXTERNO = $row->IDEMPRESA;
                        // $resp[] = $row;
                    }
                }
                $stmt = null;

            }catch(Exception $e) {
                return array(                
                    'status'=> 402,
                    'response' => $e->getMessage()
                );
            }
            
            $diaconsulta = $request->fecha;

            $rows = DB::select("SELECT  Tar.id, Usu.dni AS IDTRABAJADOR, Are.code_nisira AS IDACTIVIDADEXTERNO, RDT.dia AS FECHATAREO, Mac.code_abby AS IDMAQUINARIAEXTERNO, 
                ParIni.id AS IDLUGAR_SALIDACAMPO, Loc.dim5 AS IDCONSUMIDOREXTERNO,
                TasMac.horometro_recogida AS HOROMETRO_SALIDACAMPO, ParFin.id AS IDLUGAR_ENTREGAPARQUEO, TasMac.horometro_parking AS HOROMETRO_ENTREGAPARQUEO, Lab.code_nisira AS IDLABOREXTERNO,
                Imp.code_abby AS IDIMPLEMENTOEXTERNO, Tar.horometro_inicio AS INICIO_HOROMETROLABOR, Tar.horometro_fin AS FIN_HOROMETROLABOR, SupUsu.dni AS IDSUPERVISOR_LABOR,
                Tar.obs_rejected AS OBSSUPERVISOR_LABOR, 
                (	SELECT avance_plan 
                    FROM task_locations
                    WHERE requestdetailtask_id = RDT.id AND location_id = Tar.location_id ) AS AVANCEDIURNO_PLANIFICADO,
                (	SELECT UndMed.siglas
                    FROM request_details RD
                    INNER JOIN unit_measures UndMed ON UndMed.id = RD.um_id
                    WHERE RD.id = RDT.requestdetail_id ) AS observaciones,
                (	SELECT Tur.id
                    FROM request_details RD
                    INNER JOIN turnos Tur ON Tur.id = RD.turno_id
                    WHERE RD.id = RDT.requestdetail_id ) AS turno_id,
                        Tar.avance AS AVANCEDIURNO         
                        
                    FROM  people Per
                    INNER JOIN users Usu ON Usu.id = Per.user_id
                    INNER JOIN person_types PerTyp ON PerTyp.id = Per.typeperson_id
                    INNER JOIN areas Are ON Are.id = Per.area_id
                    INNER JOIN tareos Tar ON Tar.operator_id = Per.id
                    INNER JOIN request_detail_tasks RDT ON RDT.id = Tar.rdetailt_id
                    INNER JOIN machineries Mac ON Mac.id = Tar.machinerie_id
                    INNER JOIN task_machineries TasMac ON TasMac.requestdetailtask_id = RDT.id
                    INNER JOIN task_supervisors TasSup ON TasSup.requestdetailtask_id = RDT.id
                    INNER JOIN people Sup ON Sup.id = TasSup.person_id
                    INNER JOIN users SupUsu ON SupUsu.id = Sup.user_id
                    INNER JOIN parkings ParIni ON ParIni.id = Mac.parking_id
                    INNER JOIN parkings ParFin ON ParFin.id = TasMac.parking_id
                    INNER JOIN locations Loc ON Loc.id = Tar.location_id
                    INNER JOIN implements Imp ON Imp.id = Tar.implement_id
                    INNER JOIN tasks Lab ON Lab.id = Tar.task_id            
                    WHERE PerTyp.id = 1 AND RDT.dia =  '$diaconsulta' AND Tar.state_id=6 AND RDT.changes=1");

            $resp = array();
            foreach( $rows as $row ) {
                $IDTRABAJADOR           = $row->IDTRABAJADOR; // Dni trabajador
                $IDACTIVIDADEXTERNO     = $row->IDACTIVIDADEXTERNO; // Area
                $originalDate = $row->FECHATAREO;
                $newDate = date("Y/d/m", strtotime($originalDate));
                $FECHATAREO             = $newDate; // Fecha tareo
                $IDMAQUINARIAEXTERNO    = $row->IDMAQUINARIAEXTERNO;
                $IDLUGAR_SALIDACAMPO    = $row->IDLUGAR_SALIDACAMPO;
                $HOROMETRO_SALIDACAMPO  = $row->HOROMETRO_SALIDACAMPO;
                $IDLUGAR_ENTREGAPARQUEO = $row->IDLUGAR_ENTREGAPARQUEO;
                $HOROMETRO_ENTREGAPARQUEO = $row->HOROMETRO_ENTREGAPARQUEO;
                $IDLABOREXTERNO         = $row->IDLABOREXTERNO;
                $IDCONSUMIDOREXTERNO    = $row->IDCONSUMIDOREXTERNO;
                $IDIMPLEMENTOEXTERNO    = $row->IDIMPLEMENTOEXTERNO;
                $INICIO_HOROMETROLABOR  = $row->INICIO_HOROMETROLABOR;
                $FIN_HOROMETROLABOR     = $row->FIN_HOROMETROLABOR;
                $turno_id = $row->turno_id;
                /**
                 * 01: Diurno
                 * 03: Nocturno
                 */
                $IDTURNO = $turno_id == 1 ? '01' : '02';
                $IDSUPERVISOR_LABOR  = $row->IDSUPERVISOR_LABOR; // IDRESPONSABLEEXTERNO
                $OBSSUPERVISOR_LABOR = $row->OBSSUPERVISOR_LABOR;
                $AVANCEDIURNO_PLANIFICADO = $row->AVANCEDIURNO_PLANIFICADO;
                $observaciones  = $row->observaciones;
                $AVANCEDIURNO   = $row->AVANCEDIURNO;

                /// Faltan ID
                $IDTAREOEXTERNO = '001';
                $ITEMTAREOEXTERNO = '1';
                $NROTAREOEXTERNO = '1';

                /////////////

                try {
                    $query = "INSERT INTO NIS_TAREOEXTERNO_MANTENIMIENTO 
                            (IDEMPRESAEXTERNO, IDTRABAJADOR, IDACTIVIDADEXTERNO, FECHATAREO, IDMAQUINARIAEXTERNO, IDLUGAR_SALIDACAMPO, HOROMETRO_SALIDACAMPO,
                            IDLUGAR_ENTREGAPARQUEO, HOROMETRO_ENTREGAPARQUEO, IDLABOREXTERNO, IDCONSUMIDOREXTERNO, IDIMPLEMENTOEXTERNO, INICIO_HOROMETROLABOR,
                            FIN_HOROMETROLABOR, IDTURNO, IDRESPONSABLEEXTERNO, OBSSUPERVISOR_LABOR, AVANCEDIURNO_PLANIFICADO, observaciones, AVANCEDIURNO,
                            IDTAREOEXTERNO, ITEMTAREOEXTERNO, NROTAREOEXTERNO)
                            VALUES
                            (:d1, :d2, :d3, :d4, :d5, :d6, :d7, :d8, :d9, :d10, :d11, :d12, :d13, :d14, :d15, :d16, :d17, :d18, :d19, :d20, :d21, :d22, :d23)";

                    $stmt = $pdo->prepare($query);            
                    $stmt->bindParam(':d1', $IDEMPRESAEXTERNO);
                    $stmt->bindParam(':d2', $IDTRABAJADOR);
                    $stmt->bindParam(':d3', $IDACTIVIDADEXTERNO);
                    $stmt->bindParam(':d4', $FECHATAREO);
                    $stmt->bindParam(':d5', $IDMAQUINARIAEXTERNO);
                    $stmt->bindParam(':d6', $IDLUGAR_SALIDACAMPO);
                    $stmt->bindParam(':d7', $HOROMETRO_SALIDACAMPO);
                    $stmt->bindParam(':d8', $IDLUGAR_ENTREGAPARQUEO);
                    $stmt->bindParam(':d9', $HOROMETRO_ENTREGAPARQUEO);
                    $stmt->bindParam(':d10', $IDLABOREXTERNO);
                    $stmt->bindParam(':d11', $IDCONSUMIDOREXTERNO);
                    $stmt->bindParam(':d12', $IDIMPLEMENTOEXTERNO);
                    $stmt->bindParam(':d13', $INICIO_HOROMETROLABOR);
                    $stmt->bindParam(':d14', $FIN_HOROMETROLABOR);
                    $stmt->bindParam(':d15', $IDTURNO);
                    $stmt->bindParam(':d16', $IDSUPERVISOR_LABOR);
                    $stmt->bindParam(':d17', $OBSSUPERVISOR_LABOR);
                    $stmt->bindParam(':d18', $AVANCEDIURNO_PLANIFICADO);
                    $stmt->bindParam(':d19', $observaciones);
                    $stmt->bindParam(':d20', $AVANCEDIURNO);
                    $stmt->bindParam(':d21', $IDTAREOEXTERNO);
                    $stmt->bindParam(':d22', $ITEMTAREOEXTERNO);
                    $stmt->bindParam(':d23', $NROTAREOEXTERNO);

                    if( $stmt->execute() === true ) {                        
                        $resp[] = array(
                            'fecha' => $FECHATAREO,
                            'response' => 'OK'
                        );                        
                    }
                }catch(Exception $e) {
                    $resp[] = array(
                        'status'=> 402,
                        'response' => $e->getMessage()
                    );
                }
                /// resp: MUESTRA LISTA DE DATOS INSERTADOS
                // $resp[] = array(
                //     'IDEMPRESAEXTERNO'  =>  $IDEMPRESAEXTERNO,
                //     'IDTRABAJADOR'  =>  $IDTRABAJADOR,
                //     'IDACTIVIDADEXTERNO'=>  $IDACTIVIDADEXTERNO,
                //     'FECHATAREO'    =>  $FECHATAREO,
                //     'IDMAQUINARIAEXTERNO' => $IDMAQUINARIAEXTERNO,
                //     'IDLUGAR_SALIDACAMPO' => $IDLUGAR_SALIDACAMPO,
                //     'HOROMETRO_SALIDACAMPO' => $HOROMETRO_SALIDACAMPO,
                //     'IDLUGAR_ENTREGAPARQUEO'=> $IDLUGAR_ENTREGAPARQUEO,
                //     'HOROMETRO_ENTREGAPARQUEO' => $HOROMETRO_ENTREGAPARQUEO,
                //     'IDLABOREXTERNO' => $IDLABOREXTERNO,
                //     'IDCONSUMIDOREXTERNO'   => $IDCONSUMIDOREXTERNO,
                //     'IDIMPLEMENTOEXTERNO'   => $IDIMPLEMENTOEXTERNO,
                //     'INICIO_HOROMETROLABOR' => $INICIO_HOROMETROLABOR,
                //     'FIN_HOROMETROLABOR'    => $FIN_HOROMETROLABOR,
                //     'IDTURNO'        => $IDTURNO,
                //     'IDSUPERVISOR_LABOR'    => $IDSUPERVISOR_LABOR,
                //     'OBSSUPERVISOR_LABOR'   => $OBSSUPERVISOR_LABOR,
                //     'AVANCEDIURNO_PLANIFICADO' => $AVANCEDIURNO_PLANIFICADO,
                //     'observaciones' => $observaciones,
                //     'AVANCEDIURNO'  => $AVANCEDIURNO,
                // );
            }
            $data = 200;

            ///////////////// [ END NISIRA ] ///////////////

        }else {
            $data = 202;
        }
                
        $pdo = null;

        return response()->json(
            array(
                'status' => $data,
                'response' => $resp
            ));
    }
}

