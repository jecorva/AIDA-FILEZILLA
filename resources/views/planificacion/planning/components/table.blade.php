<?php

use App\Models\Machinerie;
use App\Models\Person;
use App\Models\TaskImplement;
use App\Models\TaskLocation;
use App\Models\TaskMachinery;
use App\Models\TaskOperator;
use App\Models\TaskSupervisor;

$nro = 1;
$nro_ubi = 1;
?>
<div class="sourcesans">
    <table id="datatable" class="table table table-hover">
        <thead class="fs-14 bg-light text-secondary text-input">
            <tr>
                <th width='3%'>Nro</th>
                <th width='20%'>Labor</th>
                <th width='5%'>Fecha</th>
                <th width='5%'>Turno</th>
                <th width='10%'>Ubicaciones</th>
                <th width='10%'>Implementos</th>
                <th width='15%' class="text-center">Supervisor</th>
                <th width='15%' class="text-center">Operario</th>
                <th width='15%' class="text-center">Maquinaria</th>
                <th width='5%' class="text-center">Acción</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-11 RobotoC fw-500">
            @foreach( $response as $resp )
            <?php
            $fecha = new DateTime($resp->dia);
            $dia_db = $resp->dia;
            $dia = $fecha->format('d/m/Y');
            $id_dtlllabor = $resp['requestdetailtask_id'];
            $nombre_turno = $resp['nombre_turno'];
            if( $nombre_turno == 'NOCHE' ) $icon_turno = '<i class="fas fa-moon text-dark"></i>';
            else $icon_turno = '<i class="fas fa-sun text-warning"></i>';
            $disabled = "";
            if( $resp->approved == 1 ) $disabled = "disabled";

            $ubicacions = TaskLocation::select('locations.nombre', 'locations.dim5', 'locations.id')
                ->join('locations', 'locations.id', '=', 'task_locations.location_id')
                ->where('task_locations.requestdetailtask_id', '=', $id_dtlllabor)
                ->get();
            $implements = TaskImplement::select('implements.nombre', 'implements.id')
                ->join('implements', 'implements.id', '=', 'task_implements.implement_id')
                ->where('task_implements.requestdetailtask_id', '=',  $id_dtlllabor)
                ->get();
            $operators = Person::select(
                'people.id',
                'users.nombres',
                'users.apel_pat',
                'users.apel_mat'
            )
                ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
                ->join('users', 'users.id', '=', 'people.user_id')
                ->where('people.typeperson_id', '=', 1)
                ->orWhere('people.typeperson_id', '=', 2)
                ->get();
            $supervisores = Person::select(
                'people.id',
                'users.nombres',
                'users.apel_pat',
                'users.apel_mat'
            )
                ->join('person_types', 'person_types.id', '=', 'people.typeperson_id')
                ->join('users', 'users.id', '=', 'people.user_id')
                ->where('people.typeperson_id', '=', 3)
                ->get();
            $machinerys = Machinerie::all()->where('flag', 1);
            $lbOperator = TaskOperator::where('task_operators.requestdetailtask_id', '=', $id_dtlllabor)
                ->orderBy('task_operators.created_at', 'DESC')
                ->take(1)
                ->get();
            json_encode($lbOperator);
            $trabajador_id = "";
            if (count($lbOperator)) $trabajador_id = $lbOperator[0]->person_id;

            $lbSupervisor = TaskSupervisor::where('task_supervisors.requestdetailtask_id', '=', $id_dtlllabor)
                ->get();
            json_encode($lbSupervisor);            
            $supervisor_id = "";
            if (count($lbSupervisor)) $supervisor_id = $lbSupervisor[0]->person_id;


            $lbMachinery = TaskMachinery::where('task_machineries.requestdetailtask_id', '=', $id_dtlllabor)
                ->orderBy('task_machineries.created_at', 'DESC')
                ->take(1)
                ->get();
            json_encode($lbMachinery);
            $machinery_id = "";
            if (count($lbMachinery)) $machinery_id = $lbMachinery[0]->machinerie_id;

            ?>
            <tr>
                <td class="align-middle text-center font-weight-bold">{{ $nro }}</td>
                <td class="align-middle">{{ strtoupper($resp->labor) }}</td>
                <td class="align-middle text-center">{{ $dia }}</td>
                <td class="align-middle text-center"><?php echo $icon_turno ?> {{ $nombre_turno }}</td>
                <td class="align-middle">
                    <?php $nro_ubi = 1 ?>
                    @foreach( $ubicacions as $ubi )
                    <span class="px-2 py-1 bg-body rounded d-block my-1">{{ $ubi->nombre }}</span>
                    @endforeach
                </td>
                <td class="align-middle">
                    <?php $nro_imp = 1 ?>
                    @foreach( $implements as $imp )
                    <span class="px-2 py-1 bg-body rounded d-block my-1">{{ $nro_imp++ }} - {{ $imp->nombre }}</span>
                    @endforeach
                </td>
                <td class="align-middle">
                    <select id="supervisorSlt{{ $nro }}" class="form-control select2" {{ $disabled }}>
                        <option value="0">Seleccionar</option>
                        @foreach( $supervisores as $sup )
                        <?php
                        $apel_nom = $sup->apel_pat . " " . $sup->apel_mat . " " . $sup->nombres;
                        $selected = $sup->id == $supervisor_id ? "selected" : "";
                        ?>
                        <option value="{{ $sup->id }}" class="text-uppercase" {{ $selected }}>{{ $apel_nom }}</option>
                        @endforeach
                    </select>
                    <script>
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
                    </script>
                </td>
                <td class="align-middle">
                    <select id="operatorSlt{{ $nro }}" onchange="searchOperator('{{$nro}}', '{{$dia_db}}', '{{ $id_dtlllabor}}')" class="form-control operator select2" {{ $disabled }}>
                        <option value="0">Seleccionar</option>
                        @foreach( $operators as $ope )
                        <?php
                        $apel_nom = $ope->apel_pat . " " . $ope->apel_mat . " " . $ope->nombres;
                        $selected = $ope->id == $trabajador_id ? "selected" : "";
                        ?>
                        <option value="{{ $ope->id }}" class="text-uppercase" {{ $selected }}>{{ $apel_nom }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="align-middle">
                    <select id="machinerySlt{{ $nro }}" class="form-control select2" {{ $disabled }}>
                        <option value="0">Seleccionar</option>
                        @foreach( $machinerys as $machinery )
                        <?php $selected = $machinery->id == $machinery_id ? "selected" : ""; ?>
                        <option value="{{ $machinery->id }}" class="text-uppercase" {{ $selected }}>{{ $machinery->nombre }}</option>
                        @endforeach
                    </select>
                </td>
                <td class="align-middle text-center">
                    <div class="btn-group rounded">
                        <button onclick="btnSaveOperatorAndMachinery('{{ $nro }}', '{{ $id_dtlllabor}}')" 
                        class="border rounded fs-15 text-success sourcesans tooltip-jcv" {{ $disabled }}>
                            <i class="fas fa-save"></i>
                            <?php if( $resp->approved != 1 ) { ?> <span class="tooltip-box-jcv">Guardar</span> <?php } ?>
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
    var sltOperador = [];
    var sltNumber = [];
    // Guardar operador y maquinaria
    function btnSaveOperatorAndMachinery(SltID, lbDtllID) {
        // var operator_id = $("#operatorSlt"+SltID +" :selected").text();
        // var machinery_id = $("#machinerySlt"+SltID+ " :selected").text();
        var supervisor_id = $("#supervisorSlt" + SltID).val();
        var operator_id = $("#operatorSlt" + SltID).val();
        var machinery_id = $("#machinerySlt" + SltID).val();

        if (supervisor_id != 0) {
            if (operator_id != 0) {
                if (machinery_id != 0) {

                    Swal.fire({
                        title: 'Registrar requerimiento?',
                        text: "Si está seguro de guardar, eliga [Si, guardar!]",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Si, guardar!',
                        cancelButtonText: 'No'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            var data = {
                                supervisor_id: supervisor_id,
                                operator_id: operator_id,
                                machinery_id: machinery_id,
                                lbdtll_id: lbDtllID
                            };

                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: 'POST',
                                url: "{!! url('api/request-machinery-save') !!}",
                                data: data,
                            }).done(function(msg) {
                                console.log(msg);
                                if (msg == "402-3") {
                                    $("#supervisorSlt" + SltID).prop('disabled', true);
                                    $("#operatorSlt" + SltID).prop('disabled', true);
                                    $("#machinerySlt" + SltID).prop('disabled', true);
                                    toastr.success('Selección guardada.', '', {
                                        "positionClass": "toast-top-right"
                                    });
                                }
                            })
                        }
                    })

                } else {
                    toastr.warning('Seleccionar maquinaria.', '', {
                        "positionClass": "toast-top-right"
                    });
                }

            } else {
                toastr.warning('Seleccionar operador.', '', {
                    "positionClass": "toast-top-right"
                });
            }
        }else {
            toastr.warning('Seleccionar supervisor.', '', {
                    "positionClass": "toast-top-right"
                });
        }
    }

    function searchOperator(nro, date, lbDtllID) {
        // console.log(nro)
        // let data =  $("#operatorSlt" + nro).val() + date;
        // data = data.replaceAll("-", "")
        // console.log(data);
        // sltOperador = [];        
        // var klass='operator'
        // $('select.' + klass).children('option:selected').each( function() {
        //     var $this = $(this);
        //     if( $this.val() != 0 ) sltOperador.push($this.val())

        //     // sltOperador.push( { text: $this.text(), value: $this.val() });
        // });       


        var operator_id = $("#operatorSlt" + nro).val();        
        var data = {
            operator_id: operator_id,
            date: date,
            lbDtllID: lbDtllID
        };

        if( $("#operatorSlt" + nro).val() != 0 ) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{!! url('api/request-search-operator') !!}",
                data: data,
            }).done(function(msg) {
                console.log(msg);
                msg = JSON.parse(msg);
                if (msg.status == "402") {
                    if( msg.response == '0' ) {
                        // Sin operarios en el día
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            url: "{!! url('api/request-save-operator') !!}",
                            data: data,
                        }).done(function(msg) {
                            console.log(msg);
                        })
                        
                    }else {
                        Swal.fire({
                            title: 'Desea continuar?',
                            text: 'Operario ocupado en el día.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, Continuar!',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    type: 'POST',
                                    url: "{!! url('api/request-save-operator') !!}",
                                    data: data,
                                }).done(function(msg) {
                                    console.log(msg);
                                })
                            } else {
                                $("#operatorSlt" + nro).select2('val', '0');
                            }
                        })
                    }
                }
            })
        }else {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{!! url('api/request-cero-operator') !!}",
                data: data,
            }).done(function(msg) {
                console.log(msg);
            })
        }
    }

    function onclick(nro) {
        console.log(nro);
    }

    jQuery('#datatable').DataTable({
        "pageLength": 25,
        "ordering": false,
        "sorting": true,
        scrollY: '50vh',
        "sScrollX": "100%",
        "sScrollXInner": "120%",
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