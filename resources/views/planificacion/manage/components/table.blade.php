<?php

use App\Models\LaborMachinery;
use App\Models\LaborOperator;
use App\Models\LaborPlanDtll;
use App\Models\RequestDetailTask;
use Illuminate\Support\Facades\Crypt;

$nro = 1;
?>
<div class="sourcesans">
    <table id="datatable" class="table table-hover table-bordered">
        <thead class="fs-14 text-input" height="35px">
            <tr class="text-center">
                <th width='3%'>Nro</th>
                <th width='5%'>Semana</th>
                <th width='5%'>Fecha</th>
                <th width='15%'>Ubicación</th>
                <th width='15%'>Operario</th>
                <th width='15%'>Implemento</th>
                <th width='15%'>Maquinaria</th>
                <th width='10%'>Estado</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-13 sourcesansrg fw-500">
            @foreach( $requerimientos as $requerimiento )
            <?php
            $fecha = new DateTime($requerimiento->dia);
            $dia = $fecha->format('d/m/Y');
            $apel_nom = $requerimiento->apel_pat ." ". $requerimiento->apel_mat." ". $requerimiento->nombres;
            // $status = $requerimiento->flag == '2'
            //     ? "<label class='border-radius bg-success px-2 my-0 fs-11 sourcesans'>En Proceso</label>"
            //     : "<label class='border-radius bg-warning px-2 my-0 fs-11 sourcesans'>Pendiente</label>";
            $color = "background-color:".$requerimiento->colorweb." !important;";
            //echo "ID: ".$requerimiento->rdetailt_id. "<br>";
            $supervisor = RequestDetailTask::select(
                'users.nombres', 'users.apel_pat', 'users.apel_mat'
            )   ->join('task_supervisors', 'task_supervisors.requestdetailtask_id', '=', 'request_detail_tasks.id')
                ->join('people', 'people.id', '=', 'task_supervisors.person_id')
                ->join('users', 'users.id', '=', 'people.user_id')
                ->where('request_detail_tasks.id', $requerimiento->rdetailt_id)
                ->get();

            // $supervisor = json_decode($supervisor);
            // $response = $supervisor->toJson();
            //          $response = json_decode($supervisor);
            //var_dump($supervisor);
            // echo $response[0]->nombres;
            // echo "<br>";

            //$span = '<span class="tooltip-box-jac">'. $response[0]->apel_pat .' '. $response[0]->apel_mat .'-'. $response[0]->nombres .'</span>';
            $span = '';
            $status = '<div class="tooltip-jac"><blockquote style="'.$color.'" class="quote-dark m-0 py-1 fs-10 rounded-right sourcesans text-white tooltip-jcv">
                        '.$requerimiento->name.'</blockquote>';
            $status .= $requerimiento->state_id == '4' ? $span : '';
            $status .= '</div>';

            ?>
            <tr>
                <td class="align-middle text-center">{{ $nro++ }}</td>
                <td class="align-middle text-center">{{ $requerimiento->nro_semana}}</td>
                <td class="align-middle text-center">{{ $dia }}</td>
                <td class="align-middle">{{ $requerimiento->location }}</td>
                <td class="align-middle">{{ $apel_nom }}</td>
                <td class="align-middle">{{ $requerimiento->implement }}</td>
                <td class="align-middle text-center">{{ $requerimiento->machinerie }}</td>
                <td class="align-middle text-center">
                    <?php echo $status ?>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<style>
.dataTables_scrollBody > table > thead > tr {
    visibility: collapse;
    height: 0px !important;
}
</style>
<script>
    jQuery('#datatable').DataTable({
        "pageLength": 25,
        "ordering": true,
        "scrollY": "300px",
        "language": {
            "lengthMenu": "Mostrar _MENU_  registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Lista del _START_ al _END_ de _TOTAL_ registros",
            "infoEmpty": "Lista del 0 al 0 de 0 Registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
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
