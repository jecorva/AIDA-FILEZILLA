<?php

use App\Models\CategoriaImplemento;
use App\Models\Implement;
use App\Models\Location;
use App\Models\RequestDetailTask;
use App\Models\TaskImplement;

ini_set('max_execution_time', 520);

$fecha = $dtll_plan->date_inicio;
$fecha = new DateTime($fecha);
$fecha_inicio = $fecha->format('d-m-Y');
$fecha_actual = date($fecha_inicio);
$dia = date("d-m-Y", strtotime($fecha_actual . "+ 1 days"));
$hoy = date('Y-m-d');
$ayer = date('Y-m-d', strtotime($hoy . "- 1 days"));

$lunes  = date("d-m-Y", strtotime($fecha_actual . "+ 0 days"));
$martes = date("d-m-Y", strtotime($fecha_actual . "+ 1 days"));
$miercoles = date("d-m-Y", strtotime($fecha_actual . "+ 2 days"));
$jueves  = date("d-m-Y", strtotime($fecha_actual . "+ 3 days"));
$viernes = date("d-m-Y", strtotime($fecha_actual . "+ 4 days"));
$sabado  = date("d-m-Y", strtotime($fecha_actual . "+ 5 days"));
$domingo = date("d-m-Y", strtotime($fecha_actual . "+ 6 days"));
$nro = 1;

$div = "<div> Bienvenido a blade</div>";

?>
<div class="row opensans">
    <div class="col-md-2">
        <i class="fas fa-circle text-success"></i> Ubicación(es)
    </div>
    <div class="col-md-2">
        <i class="fas fa-circle text-primary"></i> Implemento(s)
    </div>
</div>
<br>
<div class="sourcesans">
    <table id="datatable" class="table table-bordered table-responsive-md">
        <thead class="fs-14 text-input">
            <tr class="text-center">
                <th width='5%' class="align-middle">Nro</th>
                <th width='' class="align-middle">Descripción de labor</th>
                <th width='5%' class="align-middle">Turno</th>
                <th width='5%' class="align-middle">UM</th>
                <th width='10%' class="bg-light">Lunes <br><?php echo $lunes ?></th>
                <th width='10%' class="bg-light">Martes <br><?php echo $martes ?></th>
                <th width='10%' class="bg-light">Miercoles <br><?php echo $miercoles ?></th>
                <th width='10%' class="bg-light">Jueves <br><?php echo $jueves ?></th>
                <th width='10%' class="bg-light">Viernes <br><?php echo $viernes ?></th>
                <th width='10%' class="bg-light">Sábado <br><?php echo $sabado ?></th>
                <th width='10%' class="bg-light">Domingo <br><?php echo $domingo ?></th>
                <th>Avance Proyectado</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-12 opensans fw-500">
            @foreach( $dtll_labores as $labor )
            <?php
            $id_planlabor = $labor->requestdetail_id;
            //$id_planlabor      = $labor->id_planlabor;
            $fecha = new DateTime($lunes);
            $lunes = $fecha->format('Y-m-d');
            $fecha = new DateTime($martes);
            $martes = $fecha->format('Y-m-d');
            $fecha = new DateTime($miercoles);
            $miercoles = $fecha->format('Y-m-d');
            $fecha = new DateTime($jueves);
            $jueves = $fecha->format('Y-m-d');
            $fecha = new DateTime($viernes);
            $viernes = $fecha->format('Y-m-d');
            $fecha = new DateTime($sabado);
            $sabado = $fecha->format('Y-m-d');
            $fecha = new DateTime($domingo);
            $domingo = $fecha->format('Y-m-d');

            $avance_total = 0;

            $tDias = RequestDetailTask::where('changes', '=', 1)
                        ->where('requestdetail_id', $id_planlabor)
                        ->where('changes', '=', 1)
                        ->where('flag', '<>', 3)
                        ->get();

            $tDias = json_decode($tDias);   
            //echo count($tDias);

            // $tlunes = RequestDetailTask::all()
            //             ->where('changes', '=', 1)
            //             ->where('requestdetail_id', $id_planlabor)
            //             ->where('changes', '=', 1)
            //             ->where('flag', '<>', 3)
            //             ->where('dia', $lunes)
            //             ->count();    
            $column = array_column($tDias, 'dia');
            $column_index = array_search( strval($lunes), $column);
            $tlunes = strlen(strval($column_index)) > 0 ? 1 : 0;        
            
            // $tMartes = RequestDetailTask::all()
            //             ->where('requestdetail_id', $id_planlabor)
            //             ->where('dia', $martes)
            //             ->where('changes', '=', 1)
            //             ->where('flag', '<>', 3)
            //             ->count();
            $column = array_column($tDias, 'dia');
            $column_index = array_search( strval($martes), $column);
            $tMartes = strlen(strval($column_index)) > 0 ? 1 : 0;        
            // echo "M-$column_index-$tMartes";
            // var_dump($column);
            // echo "<br>";
            // echo $martes;
            // echo "<br>";
            // echo $column_index;

            // $tMiercoles = RequestDetailTask::all()
            //             ->where('requestdetail_id', $id_planlabor)
            //             ->where('dia', $miercoles)
            //             ->where('changes', '=', 1)
            //             ->where('flag', '<>', 3)
            //             ->count();
            $column = array_column($tDias, 'dia');
            $column_index = array_search( strval($miercoles), $column);
            $tMiercoles = strlen(strval($column_index)) > 0 ? 1 : 0;
            // echo "MI-$column_index-$tMiercoles";
            // var_dump($column);
            // echo "<br>";
            // echo $miercoles;
            // echo "<br>";
            // echo $column_index;

            // $tJueves = RequestDetailTask::all()
            //             ->where('requestdetail_id', $id_planlabor)
            //             ->where('dia', $jueves)
            //             ->where('changes', '=', 1)
            //             ->where('flag', '<>', 3)
            //             ->count();

            $column = array_column($tDias, 'dia');
            $column_index = array_search( strval($jueves), $column);
            $tJueves = strlen(strval($column_index)) > 0 ? 1 : 0;

            // $tViernes = RequestDetailTask::all()
            //             ->where('requestdetail_id', $id_planlabor)
            //             ->where('dia', $viernes)
            //             ->where('changes', '=', 1)
            //             ->where('flag', '<>', 3)
            //             ->count();

            $column = array_column($tDias, 'dia');
            $column_index = array_search( strval($viernes), $column);
            $tViernes = strlen(strval($column_index)) > 0 ? 1 : 0;

            // $tSabado = RequestDetailTask::all()
            //             ->where('requestdetail_id', $id_planlabor)
            //             ->where('dia', $sabado)
            //             ->where('changes', '=', 1)
            //             ->where('flag', '<>', 3)
            //             ->count();

            $column = array_column($tDias, 'dia');
            $column_index = array_search( strval($sabado), $column);
            $tSabado = strlen(strval($column_index)) > 0 ? 1 : 0;

            // $tDomingo = RequestDetailTask::all()
            //             ->where('requestdetail_id', $id_planlabor)
            //             ->where('dia', $domingo)
            //             ->where('changes', '=', 1)
            //             ->where('flag', '<>', 3)
            //             ->count();

            $column = array_column($tDias, 'dia');
            $column_index = array_search( strval($domingo), $column);
            $tDomingo = $column_index != '' ? 1 : 0;

            if ($tlunes != 0) {
                $lunCheck = TaskImplement::select(
                    'task_implements.checked',
                )   ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $lunes)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();

                if( count($lunCheck) > 0 ) $lunCheck = $lunCheck[0]->checked;
                else $lunCheck = -1;

                if( $lunCheck == 0 ) {
                    $_implementsLunes = CategoriaImplemento::select(
                        'categoria_implementos.nombre',
                    )
                        ->join('task_implements', 'task_implements.cat_implemento_id', '=', 'categoria_implementos.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $lunes)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }else {
                    $_implementsLunes = Implement::select(
                        'implements.nombre',
                    )
                        ->join('task_implements', 'task_implements.implement_id', '=', 'implements.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $lunes)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }

                $_locationLunes = Location::select(
                    'locations.nombre',
                    'locations.dim5', 'task_locations.avance_plan'
                )
                    ->join('task_locations', 'task_locations.location_id', '=', 'locations.id')
                    ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_locations.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $lunes)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();
            }

            if ($tMartes != 0) {
                $marCheck = TaskImplement::select(
                    'task_implements.checked',
                )   ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $martes)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();
                if( count($marCheck) > 0) $marCheck = $marCheck[0]->checked;
                else $marCheck = -1;

                if( $marCheck == 0 ) {
                    $_implementsMartes = CategoriaImplemento::select(
                        'categoria_implementos.nombre',
                    )
                        ->join('task_implements', 'task_implements.cat_implemento_id', '=', 'categoria_implementos.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $martes)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }else {
                    $_implementsMartes = Implement::select(
                        'implements.nombre',
                    )
                        ->join('task_implements', 'task_implements.implement_id', '=', 'implements.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $martes)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }

                $_locationMartes = Location::select(
                    'locations.nombre',
                    'locations.dim5', 'task_locations.avance_plan'
                )
                    ->join('task_locations', 'task_locations.location_id', '=', 'locations.id')
                    ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_locations.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $martes)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();
            }

            if ($tMiercoles != 0) {
                $mieCheck = TaskImplement::select(
                    'task_implements.checked',
                )   ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $miercoles)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();
                if( count($mieCheck) > 0) $mieCheck = $mieCheck[0]->checked;
                else $mieCheck = -1;

                if( $mieCheck == 0 ) {
                    $_implementsMiercoles = CategoriaImplemento::select(
                        'categoria_implementos.nombre',
                    )
                        ->join('task_implements', 'task_implements.cat_implemento_id', '=', 'categoria_implementos.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $miercoles)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }else {
                    $_implementsMiercoles = Implement::select(
                        'implements.nombre',
                    )
                        ->join('task_implements', 'task_implements.implement_id', '=', 'implements.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $miercoles)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }

                $_locationMiercoles = Location::select(
                    'locations.nombre',
                    'locations.dim5', 'task_locations.avance_plan'
                )
                    ->join('task_locations', 'task_locations.location_id', '=', 'locations.id')
                    ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_locations.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $miercoles)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();
            }

            if ($tJueves != 0) {
                $jueCheck = TaskImplement::select(
                    'task_implements.checked',
                )   ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $jueves)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();

                if( count($jueCheck) > 0) $jueCheck = $jueCheck[0]->checked;
                else $jueCheck = -1;

                if( $jueCheck == 0 ) {
                    $_implementsJueves = CategoriaImplemento::select(
                        'categoria_implementos.nombre',
                    )
                        ->join('task_implements', 'task_implements.cat_implemento_id', '=', 'categoria_implementos.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $jueves)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }else {
                    $_implementsJueves = Implement::select(
                        'implements.nombre',
                    )
                        ->join('task_implements', 'task_implements.implement_id', '=', 'implements.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $jueves)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }

                $_locationJueves = Location::select(
                    'locations.nombre',
                    'locations.dim5', 'task_locations.avance_plan'
                )
                    ->join('task_locations', 'task_locations.location_id', '=', 'locations.id')
                    ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_locations.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $jueves)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();
            }

            if ($tViernes != 0) {
                $vieCheck = TaskImplement::select(
                    'task_implements.checked',
                )   ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $viernes)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();
                if(count($vieCheck) > 0) $vieCheck = $vieCheck[0]->checked;
                else $vieCheck = -1;

                if( $vieCheck == 0 ) {
                    $_implementsViernes = CategoriaImplemento::select(
                        'categoria_implementos.nombre',
                    )
                        ->join('task_implements', 'task_implements.cat_implemento_id', '=', 'categoria_implementos.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $viernes)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }else {
                    $_implementsViernes = Implement::select(
                        'implements.nombre',
                    )
                        ->join('task_implements', 'task_implements.implement_id', '=', 'implements.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $viernes)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }

                $_locationViernes = Location::select(
                    'locations.nombre',
                    'locations.dim5', 'task_locations.avance_plan'
                )
                    ->join('task_locations', 'task_locations.location_id', '=', 'locations.id')
                    ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_locations.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $viernes)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();
            }

            if ($tSabado != 0) {

                $sabCheck = TaskImplement::select(
                    'task_implements.checked',
                )   ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $sabado)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();
                if(count($sabCheck)>0) $sabCheck = $sabCheck[0]->checked;
                else $sabCheck = -1;

                if( $sabCheck == 0 ) {
                    $_implementsSabado = CategoriaImplemento::select(
                        'categoria_implementos.nombre',
                    )
                        ->join('task_implements', 'task_implements.cat_implemento_id', '=', 'categoria_implementos.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $sabado)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }else {
                    $_implementsSabado = Implement::select(
                        'implements.nombre',
                    )
                        ->join('task_implements', 'task_implements.implement_id', '=', 'implements.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $sabado)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }

                $_locationSabado = Location::select(
                    'locations.nombre',
                    'locations.dim5', 'task_locations.avance_plan'
                )
                    ->join('task_locations', 'task_locations.location_id', '=', 'locations.id')
                    ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_locations.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $sabado)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();
            }

            if ($tDomingo != 0) {
                $domCheck = TaskImplement::select(
                    'task_implements.checked',
                )   ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $domingo)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();
                if(count($domCheck) > 0) $domCheck = $domCheck[0]->checked;
                else $domCheck = -1;

                if( $domCheck == 0 ) {
                    $_implementsDomingo = CategoriaImplemento::select(
                        'categoria_implementos.nombre',
                    )
                        ->join('task_implements', 'task_implements.cat_implemento_id', '=', 'categoria_implementos.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $domingo)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }else {
                    $_implementsDomingo = Implement::select(
                        'implements.nombre',
                    )
                        ->join('task_implements', 'task_implements.implement_id', '=', 'implements.id')
                        ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_implements.requestdetailtask_id')
                        ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                        ->where('request_detail_tasks.dia', $domingo)
                        ->where('request_detail_tasks.changes', '=', 1)
                        ->where('request_detail_tasks.flag', '<>', 3)
                        ->get();
                }

                $_locationDomingo = Location::select(
                    'locations.nombre',
                    'locations.dim5', 'task_locations.avance_plan'
                )
                    ->join('task_locations', 'task_locations.location_id', '=', 'locations.id')
                    ->join('request_detail_tasks', 'request_detail_tasks.id', '=', 'task_locations.requestdetailtask_id')
                    ->where('request_detail_tasks.requestdetail_id', $id_planlabor)
                    ->where('request_detail_tasks.dia', $domingo)
                    ->where('request_detail_tasks.changes', '=', 1)
                    ->where('request_detail_tasks.flag', '<>', 3)
                    ->get();
            }

            $pointViernes = $tViernes == 0 ? 'style="cursor:pointer;"' : '';

            // 1: vacio | 0: editar
            $activeLunes  = $tlunes == 0 ? 1 : 0;
            $activeMartes = $tMartes == 0 ? 1 : 0;
            $activeMiercoles = $tMiercoles == 0 ? 1 : 0;
            $activeJueves  = $tJueves == 0 ? 1 : 0;
            $activeViernes = $tViernes == 0 ? 1 : 0;
            $activeSabado  = $tSabado == 0 ? 1 : 0;
            $activeDomingo = $tDomingo == 0 ? 1 : 0;

            $lockLun = $lunes <= $ayer ? 'Off' : 'On';
            $lockMar = $martes <= $ayer ? 'Off' : 'On';
            $lockMie = $miercoles <= $ayer ? 'Off' : 'On';
            $lockJue = $jueves <= $ayer ? 'Off' : 'On';
            $lockVie = $viernes <= $ayer ? 'Off' : 'On';
            $lockSab = $sabado <= $ayer ? 'Off' : 'On';
            $lockDom = $domingo <= $ayer ? 'Off' : 'On';

            /*
            $fecha = $labor->date_inicio;
            $api = "./requerimiento-dtll-labor/". $id_planlabor ."/". $fecha;
            $html = file_get_contents($api);*/
            ?>
            <tr>
                <td class="align-middle text-center font-weight-bold">
                    <?php 
                    $showRow = '';
                    if( $tlunes == 0 && $tMartes == 0 && $tMiercoles == 0 && $tJueves == 0 && $tViernes == 0 && $tSabado == 0 && $tDomingo == 0 )
                        echo '<button data-id-request-details='.$id_planlabor.' id="btn-delete-labor" class="btn btn-danger btn-sm px-2 py-1"><i class="fas fa-trash-alt"></i></button>';
                    else 
                        echo $nro++;
                    ?>
                </td>
                <td height="100" class="align-middle">{{ $labor->labor }}</td>
                <td class="align-middle fs-12">{{ $labor->turno }}</td>
                <td class="align-middle fs-12 text-center">{{ $labor->siglas }}</td>
                <td style="cursor:pointer;"
                    class="align-middle bg-hover"
                    onclick="btnCell('{{ $lockLun }}', '<?php echo $lunes ?>', '<?php echo $id_planlabor ?>' , '<?php echo $labor->labor ?>' , '<?php echo $labor->turno ?>', '<?php echo $labor->siglas ?>', '<?php echo $activeLunes ?>')"
                    >
                    <?php
                    //echo $cLunes;

                    if ($tlunes == 0){
                        echo $lockLun == 'Off'
                            ? "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label><i class='fas fa-lock text-center d-block'></i>"
                            : "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label>";
                    }else {
                        if (isset($_implementsLunes))
                        foreach ($_implementsLunes as $imple) {
                            echo "<label type='text' class='fs-11 bg-primary w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($imple->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $imple->nombre . "</span>
                                    </label>";
                        }
                    if (isset($_locationLunes))
                        foreach ($_locationLunes as $loca) {
                            echo "<label type='text' class='fs-11 bg-success w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($loca->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $loca->nombre . "</span>
                                    </label>";
                            $avance_total += $loca->avance_plan;
                        }
                    }
                    ?>
                </td>
                <td style="cursor:pointer;" class="align-middle bg-hover" onclick="btnCell('{{ $lockMar }}','<?php echo $martes ?>'   , '<?php echo $id_planlabor ?>' , '<?php echo $labor->labor ?>' , '<?php echo $labor->turno ?>', '<?php echo $labor->siglas ?>' , '<?php echo $activeMartes ?>')">
                    <?php
                    //echo $cMartes

                    if ($tMartes == 0){
                        echo $lockMar == 'Off'
                            ? "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label><i class='fas fa-lock text-center d-block'></i>"
                            : "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label>";
                    }else {
                        if (isset($_implementsMartes))
                        foreach ($_implementsMartes as $imple) {
                            echo "<label type='text' class='fs-11 bg-primary w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($imple->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $imple->nombre . "</span>
                                    </label>";
                        }
                    if (isset($_locationMartes))
                        foreach ($_locationMartes as $loca) {
                            echo "<label type='text' class='fs-11 bg-success w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($loca->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $loca->nombre . "</span>
                                    </label>";
                            $avance_total += $loca->avance_plan;
                        }
                    }
                    ?>
                </td>
                <td style="cursor:pointer;" class="align-middle bg-hover" onclick="btnCell('{{ $lockMie }}','<?php echo $miercoles ?>', '<?php echo $id_planlabor ?>' , '<?php echo $labor->labor ?>' , '<?php echo $labor->turno ?>', '<?php echo $labor->siglas ?>' , '<?php echo $activeMiercoles ?>')">
                    <?php
                    //echo $cMiercoles

                    if ($tMiercoles == 0){
                        echo $lockMie == 'Off'
                            ? "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label><i class='fas fa-lock text-center d-block'></i>"
                            : "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label>";
                    }else {
                        if (isset($_implementsMiercoles))
                        foreach ($_implementsMiercoles as $imple) {
                            echo "<label type='text' class='fs-11 bg-primary w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($imple->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $imple->nombre . "</span>
                                    </label>";
                        }
                    if (isset($_locationMiercoles))
                        foreach ($_locationMiercoles as $loca) {
                            echo "<label type='text' class='fs-11 bg-success w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($loca->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $loca->nombre . "</span>
                                    </label>";
                            $avance_total += $loca->avance_plan;
                        }
                    }
                    ?>
                </td>
                <td style="cursor:pointer;" class="align-middle bg-hover" onclick="btnCell('{{ $lockJue }}','<?php echo $jueves ?>'   , '<?php echo $id_planlabor ?>' , '<?php echo $labor->labor ?>' , '<?php echo $labor->turno ?>', '<?php echo $labor->siglas ?>' , '<?php echo $activeJueves ?>')">
                    <?php
                    //echo $cJueves

                    if ($tJueves == 0){
                        echo $lockJue == 'Off'
                            ? "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label><i class='fas fa-lock text-center d-block'></i>"
                            : "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label>";
                    }else {
                        if (isset($_implementsJueves))
                        foreach ($_implementsJueves as $imple) {
                            echo "<label type='text' class='fs-11 bg-primary w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($imple->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $imple->nombre . "</span>
                                    </label>";
                        }
                    if (isset($_implementsJueves))
                        foreach ($_locationJueves as $loca) {
                            echo "<label type='text' class='fs-11 bg-success w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($loca->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $loca->nombre . "</span>
                                    </label>";
                            $avance_total += $loca->avance_plan;
                        }
                    }
                    ?>
                </td>
                <td style="cursor:pointer;" class="align-middle bg-hover p-0 " onclick="btnCell('{{ $lockVie }}','<?php echo $viernes ?>'  , '<?php echo $id_planlabor ?>' , '<?php echo $labor->labor ?>' , '<?php echo $labor->turno ?>', '<?php echo $labor->siglas ?>' , '<?php echo $activeViernes ?>')">
                    <?php
                    // echo $cViernes

                    if ($tViernes == 0){
                        echo $lockVie == 'Off'
                            ? "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label><i class='fas fa-lock text-center d-block'></i>"
                            : "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label>";
                    }else {
                            if (isset($_implementsViernes))
                        foreach ($_implementsViernes as $imple) {
                            echo "<label type='text' class='fs-11 bg-primary w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($imple->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $imple->nombre . "</span>
                                    </label>";
                        }
                    if (isset($_locationViernes))
                        foreach ($_locationViernes as $loca) {
                            echo "<label type='text' class='fs-11 bg-success w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($loca->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $loca->nombre . "</span>
                                    </label>";
                            $avance_total += $loca->avance_plan;
                        }
                        }
                    ?>
                </td>
                <td style="cursor:pointer;" class="align-middle bg-hover" onclick="btnCell('{{ $lockSab }}','<?php echo $sabado ?>'   , '<?php echo $id_planlabor ?>' , '<?php echo $labor->labor ?>' , '<?php echo $labor->turno ?>', '<?php echo $labor->siglas ?>' , '<?php echo $activeSabado ?>')">
                    <?php
                    //echo $cSabado

                    if ($tSabado == 0){
                        echo $lockSab == 'Off'
                            ? "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label><i class='fas fa-lock text-center d-block'></i>"
                            : "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label>";
                    }else {
                        if (isset($_implementsSabado))
                        foreach ($_implementsSabado as $imple) {
                            echo "<label type='text' class='fs-11 bg-primary w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($imple->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $imple->nombre . "</span>
                                    </label>";
                        }
                    if (isset($_locationSabado))
                        foreach ($_locationSabado as $loca) {
                            echo "<label type='text' class='fs-11 bg-success w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($loca->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $loca->nombre . "</span>
                                    </label>";
                            $avance_total += $loca->avance_plan;
                        }
                    }
                    ?>
                </td>
                <td style="cursor:pointer;" class="align-middle bg-hover" onclick="btnCell('{{ $lockDom }}','<?php echo $domingo ?>'  , '<?php echo $id_planlabor ?>' , '<?php echo $labor->labor ?>' , '<?php echo $labor->turno ?>', '<?php echo $labor->siglas ?>' , '<?php echo $activeDomingo ?>')">
                    <?php
                    //echo $cDomingo

                    if ($tDomingo == 0){
                        echo $lockDom == 'Off'
                            ? "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label><i class='fas fa-lock text-center d-block'></i>"
                            : "<label type='text' class='fs-11 text-danger font-weight-bold text-center w-100 px-2 mb-1'>Solicitud</label>";
                    }else {
                        if (isset($_implementsDomingo))
                        foreach ($_implementsDomingo as $imple) {
                            echo "<label type='text' class='fs-11 bg-primary w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($imple->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $imple->nombre . "</span>
                                    </label>";
                        }
                    if (isset($_locationDomingo))
                        foreach ($_locationDomingo as $loca) {
                            echo "<label type='text' class='fs-11 bg-success w-100 px-2 mb-1 rounded tooltip-jcv'> " .  mb_strimwidth($loca->nombre, 0, 15, "...") . "
                                        <span class='tooltip-box-jcv fs-11'>" . $loca->nombre . "</span>
                                    </label>";
                            $avance_total += $loca->avance_plan;
                        }
                    }
                    ?>
                </td>
                <td class="align-middle bg-hover text-center">
                    <span class="font-weight-bold">{{ $avance_total }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="mdl-dtll-planlabor" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <form id="saveDtllLaborFrm">
            <div class="modal-content">
                <input id="planLaborId" type="hidden" />
                <input id="dayMdl" type="hidden" />
                <input id="id_planlabor" type="hidden" />
                <div id="modal-body-edit" class="modal-body">
                    <div class="alert bg-bluedark text-input p-2 text-white elevation-1" id="title-labor">
                    </div>
                    <div id="grupo-check" class="border border-dark rounded px-2 fs-13 bg-light">
                        <span class="fs-12 text-muted"><i class="fas fa-copy mr-2"></i>Copiar</span><span class="fs-12 font-weight-light">  [ Seleccionar día(s) ]</span><br>
                        <div class="form-check form-check-inline">
                          <input data-date='copy' name='lunes' id="ch-<?php echo $lunes; ?>" value="<?php echo $lunes; ?>" class="form-check-input" type="checkbox" >
                          <label class="form-check-label">Lun</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input data-date='copy' name='martes' id="ch-<?php echo $martes; ?>" value="<?php echo $martes; ?>" class="form-check-input" type="checkbox" >
                          <label class="form-check-label">Mar</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input data-date='copy' name='miercoles' id="ch-<?php echo $miercoles; ?>" value="<?php echo $miercoles; ?>" class="form-check-input" type="checkbox" >
                          <label class="form-check-label">Mie</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input data-date='copy' name='jueves' id="ch-<?php echo $jueves; ?>" value="<?php echo $jueves; ?>" class="form-check-input" type="checkbox" >
                          <label class="form-check-label">Jue</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input data-date='copy' name='viernes' id="ch-<?php echo $viernes; ?>" value="<?php echo $viernes; ?>" class="form-check-input" type="checkbox" >
                          <label class="form-check-label">Vie</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input data-date='copy' name='sabado' id="ch-<?php echo $sabado; ?>" value="<?php echo $sabado; ?>" class="form-check-input" type="checkbox" >
                          <label class="form-check-label">Sab</label>
                        </div>
                        <div class="form-check form-check-inline">
                          <input data-date='copy' name='domingo' id="ch-<?php echo $domingo; ?>" value="<?php echo $domingo; ?>" class="form-check-input" type="checkbox" >
                          <label class="form-check-label">Dom</label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-0 text-secondary">Ubicación</label><span class="text-danger font-weight-bold">*</span>
                                <select class="form-control select2" id="locationSlt">
                                    <option value="0">Seleccionar</option>
                                    @foreach( $locations as $location )
                                    <option value="{{ $location->id }}">{{ $location->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="mt-1" id="locationList"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mb-0 text-secondary">Maquinarias</label><span class="text-danger font-weight-bold">*</span>
                                    <select class="form-control text-input select2" id="maquinariaSlt">
                                        <option value="0">Seleccionar</option>
                                        @foreach( $maquinarias as $maquinaria )
                                        <option value="{{ $maquinaria->id }}" class="text-uppercase">{{ $maquinaria->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-1" id="maquinariaList"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="icheck-success">
                                    <input type="checkbox" name="impEspecifico" id="impEspecifico">
                                    <label for="impEspecifico">
                                        Activar implemento específico
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label class="mb-0 text-secondary">Implementos</label><span class="text-danger font-weight-bold">*</span>
                                    <select class="form-control text-input select2" id="implementsSlt">
                                        <option value="0">Seleccionar</option>
                                        @foreach( $implements as $implement )
                                        <option value="{{ $implement->id }}" class="text-uppercase">{{ $implement->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <div class="mt-1" id="implementList"></div>
                                </div>
                            </div>
                            <div class="row px-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="mb-0 text-secondary">Horas Efectivas</label><span class="text-danger font-weight-bold">*</span>
                                        <input autocomplete="off" id="hoursMdl" placeholder="" type="text" class="form-control form-control-sm text-input " required>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label class="mb-0 fs-13 text-secondary">Observacion (Opcional)</label>
                                    <textarea id="observacion" class="form-control" rows="3" placeholder="Breve descripción"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-block">
                    <div class="row">
                        <div class="col-md-5 float-left">
                            <button id="cancel-labor" type="button" class="btn btn-danger"><i class="fas fa-trash-alt mr-2"></i>Borrar</button>
                        </div>
                        <div class="col-md-7 ">
                            <div class="btn-group float-right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button id='btn-save' type="submit" class="btn btn-bluedark text-white sourcesansrg fs-13 m-0 rounded-1">
                                    <i class="fas fa-save mr-2"></i><span id="btn-save-mdl">Guardar</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div> 
        </form>
        <script>
             $('.select2').select2({
                dropdownCssClass: "opensans fs-13",
                language: {
                    noResults: function() {
                        return "Sin resultados";
                    },
                    searching: function() {
                        return "Buscando..";
                    }
                }
            });
        </script>
    </div>
</div>

<script>
    var countDivs = 0;
    var inputIds = [];
    var countDivs2 = 0;
    var locationIds = [];
    var implementIds = [];
    var countDivs3 = 0;
    var maquinariaIds = [];
    var isEdit = false;
    var check = 0;

    $("#cancel-labor").on("click", function() {
        var planLaborId = $("#planLaborId").val();
        var day = $("#dayMdl").val();
        var data = {
            'planlaborId': planLaborId,
            'dia': day
        };

        Swal.fire({
            title: 'Está seguro?',
            text: "¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await requestAPI('/api/request-cancel-dtll', data)
                console.log(response)
                if( response.status == 200 ) {
                    listLaboresPlan();
                    $('.modal-backdrop').remove();
                    $("#mdl-dtll-planlabor").modal("hide");
                    resetVar();
                    Toast.fire({
                        icon: 'success',
                        title: 'Labor del dia eliminada.',
                        position: 'top-end',
                        confirmButtonText: 'OK'
                    })
                }else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Error al guardar.',
                        position: 'top-end',
                        confirmButtonText: 'OK'
                    })
                }
            }
        })

    });

    $("#btn-delete-labor").on('click', function() {
        let requestDetailsId = $(this).attr('data-id-request-details');
        console.log(requestDetailsId)
        let param = { requestDetailsId: requestDetailsId }
        
        Swal.fire({
                title: 'Está seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!'
            }).then(async (result) =>  {
                if (result.isConfirmed) {
                    const response = await requestAPI('/api/request-delete', param)
                    console.log(response)
                    if( response.status == 200 ) {
                        listLaboresPlan();

                        Toast.fire({
                            icon: 'success',
                            title: 'Labor eliminada.',
                            position: 'top-end',
                            confirmButtonText: 'OK'
                        })
                    }
                }
            })    
   })

   //var

   $("#impEspecifico").on('click', function() {
        let checked = $("#impEspecifico").is(':checked')
        console.log(checked)
        if( checked == true ) {
            getImplementsDetails()
        }else {
            getCategoryImplements()
        }

        $("#implementList").html("");
        implementIds = [];
   });

    async function getImplementsDetails() {
        let data = { option: 'implements' }
        check = 1
        await getComponent('/api/component-select-option', data, 'implementsSlt')
    }

    async function getCategoryImplements() {
        let data = { option: 'categoria_implementos' }
        check = 0
        await getComponent('/api/component-select-option', data, 'implementsSlt')
    }

    function formato(texto) {
        return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g, '$3-$2-$1');
    }

    function btnCopy(day) {
        if ($("#ch-" + day).is(':checked')) {
            // alert(day);
        }
    }

    async function btnCell(lock, day, laborId, nameLabor, turn, siglas, active) {
        //alert(active+"-"+lock)
        $('.form-check-input').prop('checked', false)
        $('#ch-' + day).prop('checked', true);
        $("#modal-body-edit *").prop('disabled', false);
        $('#ch-' + day).prop('disabled', true);
        $("#title-labor").removeClass('bg-primary');
        $("#title-labor").addClass('bg-bluedark');
        $("#btn-save").prop('disabled', false)

        $('input[data-date=copy]').each(function() {
            if($(this).val() < day ) {
                $('#ch-' + $(this).val()).prop('disabled', true);
            }
        });

        if ( active == 0 && lock == 'On' ) {
            $('#grupo-check *').prop('disabled', true);
            isEdit = false;
            var data = {
                day: day,
                planLaborId: laborId,
                isEdit: isEdit
            };

            var msg = await requestAPI('/api/request-update-dtll', data)
            console.log(msg)
            if( msg.length != 0 ) {

                resetVar();
                $("#modal-title").html('<i class="fas fa-calendar-day mr-1"></i>Generar plan del día');
                $("#title-labor").html("<span class='fs-13'>" + msg[0].response[0].labor + "</span><br><span class='fs-13'><b> Turno: </b>" + msg[0].response[0].turno + " | <b> Fecha: </b>" + formato(msg[0].response[0].dia) + " | <b> Unidad Medida: </b>" + siglas+"</span>");
                $("#planLaborId").val(laborId);
                $("#id_planlabor").val(msg[0].response[0].id);
                $("#dayMdl").val(msg[0].response[0].dia);
                $("#progressMdl").val(msg[0].response[0].avance);
                $("#hoursMdl").val(msg[0].response[0].horas);
                $("#observacion").val(msg[0].response[0].observacion);
                // $("#responsibleSlt").val(msg[0].response[0].id_trabajador).trigger('change.select2');

                msg[1].response.forEach(function(locationUpdate, index) {
                    locationIds.push(locationUpdate.location_id.toString());
                    inputIds.push('avance_'+index);
                    $("#locationList").append("<div id=div" + index + " class='py-0 mb-1 fs-13'>" +
                                                "<div class='row'>"+
                                                    "<div class='col-md-8'>"+
                                                        "<button type='button' class='btn' onclick=btnDeleteLocation(\'" + locationUpdate.location_id + "\',\'" + index + "\') >" +
                                                            "<i class='fas fa-times-circle mr-1'></i>" +
                                                        "</button>" + locationUpdate.nombre +
                                                    "</div>"+
                                                    "<div class='col-md-4'>"+
                                                        "<input onKeypress='if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;'"+
                                                            "id=avance_"+ index +" type='text' placeholder='Avance' value='"+ locationUpdate.avance_plan +"' class='form-control' autocomplete='off' required />" +
                                                    "</div>"+
                                                "</div>"+
                                            "</div>");
                    $("#locationSlt").select2('val', '0');
                });

                msg[2].response.forEach( function(implementUpdate, index) {
                    if(implementUpdate.checked == 1 ) {
                        $("#impEspecifico").prop('checked', true);
                        getImplementsDetails()
                    }
                    else {
                        $("#impEspecifico").prop('checked', false);
                        getCategoryImplements()
                    }

                    implementIds.push(implementUpdate.cat_implemento_id); // aqui esta el error
                    $("#implementList").append("<div id=divI" + index + " class='border rounded py-0 mb-1 fs-13'><button type='button' class='btn' onclick=btnDeleteImplements(\'" + implementUpdate.cat_implemento_id + "\',\'" + index + "\') ><i class='fas fa-times-circle mr-1'></i></button>" + implementUpdate.nombre + "</div>");
                    $("#implementSlt").select2('val', '0');
                });

                msg[3].response.forEach(function(maquinaUpdate, index) {
                    maquinariaIds.push(maquinaUpdate.cat_maquinaria_id);
                    $("#maquinariaList").append("<div id=divM" + index + " class='border rounded py-0 mb-1 fs-13'><button type='button' class='btn' onclick=btnDeleteMaquinaria(\'" + maquinaUpdate.cat_maquinaria_id + "\',\'" + index + "\') ><i class='fas fa-times-circle mr-1'></i></button>" + maquinaUpdate.nombre + "</div>");
                    $("#maquinariaSlt").select2('val', '0');
                });

                $("#mdl-dtll-planlabor").modal("show");
                if( msg[0].response[0].approved == 1 ) {
                    $("#title-labor").removeClass('bg-bluedark');
                    $("#title-labor").addClass('bg-primary');
                    $("#modal-body-edit *").prop('disabled', true);
                }
            }

        } else {

            if( lock == 'On' ) {
                resetVar();
                $("#id_planlabor").val('0');
                isEdit = true;
                $("#modal-title").html('<i class="fas fa-calendar-day mr-1"></i>' + formato(day));
                $("#title-labor").html("<span class='fs-13'>" + nameLabor + "</span><br><span class='fs-13'><b> Turno: </b>" + turn + " | <b> Unidad Medida: </b>" + siglas +"</span>");
                $("#planLaborId").val(laborId);
                $("#dayMdl").val(day);
                $("#mdl-dtll-planlabor").modal("show");

            }else {
                Toast.fire({
                    icon: 'info',
                    title: 'No puede realizar esta operación.',
                    position: 'top-end',
                    confirmButtonText: 'OK'
                })
            }
        }
    }

    $("#locationSlt").on('change', function() {
        countDivs++;
        var locationName = $('#locationSlt option:selected').text();
        var locationId = $(this).val();
        let isExits = locationIds.indexOf(locationId);

        if (locationId != 0) {
            if (isExits == -1) {
                locationIds.push(locationId);
                inputIds.push('avance_'+countDivs);
                $("#locationList").append("<div id=div" + countDivs + " class='py-0 mb-1 fs-13'>" +
                                                "<div class='row'>"+
                                                    "<div class='col-md-8'>"+
                                                        "<button type='button' class='btn' onclick=btnDeleteLocation(\'" + locationId + "\',\'" + countDivs + "\') >" +
                                                            "<i class='fas fa-times-circle mr-1'></i>" +
                                                        "</button>" + locationName +
                                                    "</div>"+
                                                    "<div class='col-md-4'>"+
                                                        "<input onKeypress='if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;'"+
                                                            "id=avance_"+ countDivs +" type='text' placeholder='Avance' class='form-control' autocomplete='off' required />" +
                                                    "</div>"+
                                                "</div>"+
                                            "</div>");
                $(this).select2('val', '0');

            } else {
                toastr.warning('Ubicación seleccionada.', 'AIDA', {
                    "positionClass": "toast-top-right"
                });
                $(this).select2('val', '0');
                console.log(isExits);
            }
        }

    });

    function btnDeleteLocation(locationId, numberDiv) {
        var divDelete = 'div' + numberDiv;
        let pos = locationIds.indexOf(locationId);
        locationIds.splice(pos, 1);
        inputIds.splice(pos, 1);
        //$("#avance_" + divDelete).attr('required', false);
        //$("#avance_" + divDelete).removeAttr('required');
        $("#" + divDelete).html('');
        $("#" + divDelete).addClass('d-none');
        console.log("Delete: "+divDelete)       
        console.log(locationIds);
        console.log(inputIds);
    }

    function btnDeleteImplements(implementId, numberDiv) {
        var divDelete = 'divI' + numberDiv;
        let pos = implementIds.indexOf(implementId);
        implementIds.splice(pos, 1);
        $("#" + divDelete).addClass('d-none');
        console.log(implementIds);
    }

    function btnDeleteMaquinaria(maquinariaId, numberDiv) {
        var divDelete = 'divM' + numberDiv;
        let pos = maquinariaIds.indexOf(maquinariaId);
        maquinariaIds.splice(pos, 1);
        $("#" + divDelete).addClass('d-none');
        console.log(maquinariaIds);
    }

    $("#implementsSlt").on('change', function() {
        countDivs2++;
        var implementName = $('#implementsSlt option:selected').text();
        var implementId = $(this).val();
        let isExits = implementIds.indexOf(implementId);
        if (implementId != 0) {
            if (isExits == -1) {
                implementIds.push(implementId.toString());
                $("#implementList").append("<div id=divI" + countDivs2 + " class='border rounded py-0 mb-1 fs-13'><button class='btn' type='button' onclick=btnDeleteImplements(\'" + implementId + "\',\'" + countDivs2 + "\') ><i class='fas fa-times-circle mr-1'></i></button>" + implementName + "</div>");
                $(this).select2('val', '0');
                console.log(implementIds);
            } else {
                toastr.warning('Implemento seleccionado.', 'AIDA', {
                    "positionClass": "toast-top-right"
                });
                $(this).select2('val', '0');
                console.log(implementIds);
            }
        }
    })

    $("#maquinariaSlt").on('change', function() {
        countDivs3++;
        var maquinariaName = $('#maquinariaSlt option:selected').text();
        var maquinariaId = $(this).val();
        let isExits = maquinariaIds.indexOf(maquinariaId);
        if (maquinariaId != 0) {
            if (isExits == -1) {
                maquinariaIds.push(maquinariaId.toString());
                $("#maquinariaList").append("<div id=divM" + countDivs3 + " class='border rounded py-0 mb-1 fs-13'><button class='btn' type='button' onclick=btnDeleteMaquinaria(\'" + maquinariaId + "\',\'" + countDivs3 + "\') ><i class='fas fa-times-circle mr-1'></i></button>" + maquinariaName + "</div>");
                $(this).select2('val', '0');
                console.log(maquinariaIds);
            } else {
                toastr.warning('Maquinaria seleccionado.', 'AIDA', {
                    "positionClass": "toast-top-right"
                });
                $(this).select2('val', '0');
                console.log(maquinariaIds);
            }
        }
    })

    $("#saveDtllLaborFrm").on('submit', async function(e) {
        e.preventDefault();
        let valInputs = [];
        var progress = 0.0;


        inputIds.forEach(function(value) {
            valInputs.push($("#"+value).val())
            progress = progress + parseFloat($("#"+value).val());
            //console.log(value)
        })

        console.log(valInputs);
        let valoresCheck = [];
        $("input[data-date='copy']:checked").each(function(){
            valoresCheck.push(this.value);
        });

        //$("#btn-save-mdl").text("Guardando...");
        $("#btn-save").prop('disabled', true)
        var planLaborId = $("#planLaborId").val();
        var hours = $("#hoursMdl").val();
        var day   = $("#dayMdl").val();
        var update= $("#id_planlabor").val();
        // var numMaq= $("#nroMaquinas").val()
        var numMaq = 1
        var user_id= $("#key").val()
        var responsibleId = $("#slt-employee-list").val();
        let obs = $("#observacion").val()
        if( obs.length == 0 ) obs = '-'
        // var progress = $("#progressMdl").val();
        // alert(update);

        var data = {
            planLaborId: planLaborId,
            hours: hours,
            day: day,
            progress: progress.toFixed(2),
            locationIds: locationIds,
            inputIds: valInputs,
            implementIds: implementIds,
            maquinariaIds: maquinariaIds,
            copys: valoresCheck,
            update: update,
            nroMaq: numMaq,
            user_id: user_id,
            responsibleId: responsibleId,
            check: check,
            obs: obs
        }
        console.log(data)
        // alert(planLaborId);

        const msg = await requestAPI('/api/request-save-dtll', data);
        console.log(msg);
        if (msg.status == 200 ) {
            listLaboresPlan();
            resetVar();
            $('.modal-backdrop').remove();
            $("#mdl-dtll-planlabor").modal("hide");
            //$("#btn-save-mdl").text("Guardar");
            $("#btn-save").prop('disabled', false)
            toastr.success('Registro exitoso.', 'AIDA', {
                "positionClass": "toast-top-right"
            });
        }

        if( msg.status == 500 ) {
            toastr.error('Error al guardar en BaseDatos.', 'AIDA', {
                "positionClass": "toast-top-right"
            });
            $("#btn-save").prop('disabled', false)
        }

        // $.ajax({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     },
        //     type: 'POST',
        //     url: "{!! url('api/request-save-dtll') !!}",
        //     data: data,
        // }).done(function(msg) {
        //     console.log(msg);
        //     if (msg = "402") {
        //         listLaboresPlan();
        //         resetVar();
        //         setTimeout(function() {
        //             $('.modal-backdrop').remove();
        //             $("#mdl-dtll-planlabor").modal("hide");
        //             $("#btn-save-mdl").text("Guardar");
        //             toastr.success('Registro exitoso.', 'AIDA', {
        //                 "positionClass": "toast-top-right"
        //             });
        //         }, 2000);
        //     }
        // })
    })

    function resetVar() {
        countDivs = 0;
        countDivs2 = 0;
        countDivs3 = 0;
        locationIds = [];
        implementIds = [];
        maquinariaIds = [];

        inputIds = [];
        isEdit = false;
        //$("#planLaborId").val();
        $("#dayMdl").val();
        $("#hoursMdl").val('');
        $("#progressMdl").val('');
        $("#locationList").html('');
        $("#implementList").html('');
        $("#maquinariaList").html('');
        // $("#responsibleSlt").select2('val', '0');

        $("#impEspecifico").prop('checked', false);
        getCategoryImplements()
        $("#observacion").val('');
    }

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    async function requestAPI(url, data) {
        let uri = "{!! url('') !!}";
        var result = await $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: uri + url,
            type: 'POST',
            data: data,
        })
        return result
    }

    var Toast = Swal.mixin({
        toast: true,
        position: 'botton-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });

    async function getComponent(url, data, id) {
        let uri = "{!! url('') !!}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: uri + url,
            type: 'POST',
            data: data,
        }).done(function(component) {
            $("#" + id).html(component)
        })
    }

    jQuery('#datatable').DataTable({
        "pageLength": 10,
        "ordering": false,
        "searching": false,
        "language": {
            "lengthMenu": "Mostrar _MENU_ labores",
            "zeroRecords": "No se encontraron resultados",
            "info": "Lista del _START_ al _END_ de _TOTAL_ labores",
            "infoEmpty": "Lista del 0 al 0 de 0 Registros",
            "infoFiltered": "(filtrado de un total de _MAX_ labores)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Sig",
                "sPrevious": "Ant"
            },
            "sProcessing": "Procesando...",
        }
    });
</script>
