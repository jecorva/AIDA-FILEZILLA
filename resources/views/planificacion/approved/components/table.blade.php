<?php

use App\Models\Machinerie;
use App\Models\Person;
use App\Models\RequestDetailTask;
use App\Models\TaskImplement;
use App\Models\TaskLocation;
use App\Models\TaskMachinery;
use App\Models\TaskOperator;
use App\Models\TaskSupervisor;

$nro = 1;
$nro_ubi = 1;
?>
<div class="sourcesans">
    <table id="datatable" class="table table table-responsive-md">
        <thead class="fs-14 bg-light text-secondary text-input" height="35px">
            <tr>
                <th width='3%'>Nro</th>
                <th width='5%'>Fecha</th>
                <th width='20%'>Labor</th>
                <th width='20%' class="">Supervisor - Requerimiento</th>
                
                <th width='5%'>Avance</th>
                <th width='20%'>Ubicaciones</th>
                <th width='20%'>Implementos</th>

                <th width='5%' class="text-center">Operación</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-11 RobotoC fw-500">
            @foreach( $response as $resp )
            <?php
            $fecha = new DateTime($resp->dia);
            $dia = $fecha->format('d/m/Y');
            $id_dtlllabor = $resp['requestdetailtask_id'];

            $name = $resp->apel_pat . " " . $resp->apel_mat . "-" . $resp->nombres;

            $ubicacions = TaskLocation::select('locations.nombre', 'locations.dim5', 'locations.id')
                ->join('locations', 'locations.id', '=', 'task_locations.location_id')
                ->where('task_locations.requestdetailtask_id', '=', $id_dtlllabor)
                ->get();
            $implements = TaskImplement::select('implements.nombre', 'implements.id')
                ->join('implements', 'implements.id', '=', 'task_implements.implement_id')
                ->where('task_implements.requestdetailtask_id', '=',  $id_dtlllabor)
                ->get();

            $ubicacions_old = TaskLocation::select('locations.nombre', 'locations.dim5', 'locations.id')
                ->join('locations', 'locations.id', '=', 'task_locations.location_id')
                ->where('task_locations.requestdetailtask_id', '=', $resp->id_changes)
                ->get();
            $implements_old = TaskImplement::select('implements.nombre', 'implements.id')
                ->join('implements', 'implements.id', '=', 'task_implements.implement_id')
                ->where('task_implements.requestdetailtask_id', '=',  $resp->id_changes)
                ->get();

            $avance_old = RequestDetailTask::select(
                'request_detail_tasks.avance'
            )->where('request_detail_tasks.id', $resp->id_changes)
                ->get();

            // $query = RequestDetailTask::select(
            //     'request_detail_tasks.dia',
            //     'tasks.nombre AS labor',
            //     'request_detail_tasks.id AS requestdetailtask_id',
            //     'request_detail_tasks.approved', 'request_detail_tasks.id_changes',
            //     'users.nombres', 'users.apel_pat', 'users.apel_mat'
            // )
            //     ->join('request_details', 'request_details.id', '=', 'request_detail_tasks.requestdetail_id')                
            //     ->join('tasks', 'tasks.id', '=', 'request_details.task_id')   
            //     ->join('requests', 'requests.id', '=', 'request_details.request_id')
            //     ->join('people', 'people.id', '=', 'requests.person_id')
            //     ->join('users', 'users.id', '=', 'people.user_id')
            //     ->orderBy('request_detail_tasks.dia', 'DESC')
            //     ->orderBy('request_detail_tasks.created_at', 'ASC')                
            //     ->where('request_detail_tasks.id', $resp->id_changes)
            //     ->get();
            // foreach( $query as $data) {
            //     echo $resp->nombres;
            // }

            ?>
            <tr class="font-weight-bold">
                <td class="align-middle text-center font-weight-bold">{{ $nro }}</td>
                <td class="align-middle text-center">{{ $dia }}</td>
                <td class="align-middle">{{ strtoupper($resp->labor) }}</td>
                <td class="align-middle">
                    <?php echo $name; ?>
                    <label class="tooltip-jac bg-danger px-2 py-1 rounded d-block text-capitalize"><i class="fas fa-info-circle mr-1"></i>
                    {{ $resp->change_msg }}
                    </label>
                </td>
                
                <td class="align-middle text-center">
                    <span class="px-2 py-1 bg-danger rounded d-block my-1">{{ $avance_old[0]->avance }}</span>
                    <span class="px-2 py-1 bg-success rounded d-block my-1">{{ $resp->avance }}</span>
                </td>
                <td class="align-middle">
                    <?php $nro_ubi = 1 ?>
                    @foreach( $ubicacions_old as $ubi )
                    <span class="px-2 py-1 bg-danger rounded d-block my-1">{{ $ubi->nombre }}</span>
                    @endforeach
                    @foreach( $ubicacions as $ubi )
                    <span class="px-2 py-1 bg-success rounded d-block my-1">{{ $ubi->nombre }}</span>
                    @endforeach
                </td>
                <td class="align-middle">
                    <?php $nro_imp = 1 ?>
                    @foreach( $implements_old as $imp )
                    <span class="px-2 py-1 rounded bg-danger d-block my-1">{{ $nro_imp++ }} - {{ $imp->nombre }}</span>
                    @endforeach
                    @foreach( $implements as $imp )
                    <span class="px-2 py-1 rounded bg-success d-block my-1">{{ $nro_imp++ }} - {{ $imp->nombre }}</span>
                    @endforeach
                </td>

                <td class="align-middle text-center">
                    <div class="btn-group rounded">
                        <button onclick="btnSaveOperatorAndMachinery('{{ $nro }}', '{{ $id_dtlllabor }}', '{{ $resp->id_changes }}')" class="border rounded fs-15 text-success sourcesans tooltip-jcv ">
                            <i class="fas fa-check"></i>
                            <span class="tooltip-box-jcv">Aprobar</span>
                        </button>
                        <!--<button onclick="printRequerimiento('')" class="btn btn-warning btn-sm fs-13 sourcesans">
                        <i class="fas fa-pencil-alt mr-1 text-white-50 fs-10"></i>                        
                    </button> -->
                    </div>
                </td>
            </tr>
            <?php
            $nro++;
            ?>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    // Guardar operador y maquinaria
    function btnSaveOperatorAndMachinery(arg1, arg2, arg3) {
        var anio = $("#anio").val();
        var nro_semana = $("#slt-semana").val();

        Swal.fire({
            title: 'Aprobar solicitud?',
            text: "Si está seguro de aprobarlo, eliga [Si, aprobar!]",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, aprobar!',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                var data = {
                    id_new: arg2,
                    id_old: arg3,                  
                };

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url:"{!! url('api/change-request-save') !!}",
                    data: data,
                }).done(function(msg) {
                    console.log(msg);
                    if (msg == "402") {
                        listApproved(anio, nro_semana);                       
                        toastr.success('Se actualizo requerimiento.', 'AIDA', {
                            "positionClass": "toast-top-right"
                        });
                    }
                    if (msg == "300") {                        
                        toastr.error('Error al guardar.', 'AIDA', {
                            "positionClass": "toast-top-right"
                        });
                    }
                })
            }
        })        
    }

    jQuery('#datatable').DataTable({
        "pageLength": 10,
        "ordering": false,
        //"sScrollX": "100%",
        //"sScrollXInner": "120%",
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

    $(function() {
        $('.select2').select2({
            width: 'resolve',
            dropdownCssClass: "RobotoC fs-12 text-uppercase",
            language: {
                noResults: function() {
                    return "Sin resultados";
                },
                searching: function() {
                    return "Buscando..";
                }
            }
        });
    });
</script>