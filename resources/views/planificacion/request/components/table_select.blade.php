<?php
$nro = 1;
?>
<div class="sourcesans" class="table-responsive-md">
    <table id="datatable-i" class="table table-striped table-bordered">
        <thead class="fs-14 text-input" height="35px">
            <tr class="text-center">
                <th width='5%'>#</th>
                <th>Supervisor</th>
                <th width='5%'>Nro.Sem.</th>
                <th width='15%'>Fecha Inicio</th>
                <th width='15%'>Fecha Fin</th>
                <th width='18%'>Operación</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-13 sourcesansrg fw-500">
            @foreach( $requerimientos as $requerimiento )
            <?php
            $apel_nom = $requerimiento->apel_pat . " " . $requerimiento->apel_mat . "-" . $requerimiento->nombres;
            $id_requerimiento = $requerimiento->id;
            $fecha = new DateTime($requerimiento->date_inicio);
            $fecha_inicio = $fecha->format('d/m/Y');
            $fecha = new DateTime($requerimiento->date_fin);
            $fecha_fin = $fecha->format('d/m/Y');
            ?>
            <tr>
                <td class="align-middle text-center font-weight-bold">{{ $nro++ }}</td>
                <td class="align-middle">{{ strtoupper($apel_nom) }}</td>
                <td class="align-middle text-center">{{ $requerimiento->nro_semana }}</td>
                <td class="align-middle text-center">{{ $fecha_inicio }}</td>
                <td class="align-middle text-center">{{ $fecha_fin }}</td>
                <td class="align-middle text-center">
                    <div class="">
                        <button onclick="btnPlanView('{{ $id_requerimiento }}')" class=" btn btn-sm bg-gradient-primary sourcesans ">
                            <i class="fas fa-eye"></i> Ver detalle
                            <!-- <span class="tooltip-box-jac">Plan</span> tooltip-jac -->
                        </button>
                        <!-- <button onclick="printRequerimiento('{{ $id_requerimiento }}')" class="btn btn-light btn-sm fs-13 sourcesans elevation-1 tooltip-jac">
                            <i class="fas fa-pencil-alt"></i>
                            <span class="tooltip-box-jac">Editar</span>
                        </button> -->
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    var loading ="<div class='text-center'><span class='fa-stack fa-lg'>\n\
                    <i class='fa fa-spinner fa-spin fa-stack fa-fw'></i>\n\
                </span>&emsp;Cargando ...</div>"

    function btnPlanRequest(id_requerimiento) {
        // $("#btn-hiden-modal").toggle();
        var data = {
            id_requerimiento: id_requerimiento
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/requerimiento-plan') !!}",
            data: data
        }).done(function(resp) {
            //console.log(resp);
            //$("#btn-add-labor").html("");
            $("#tab-requerimiento-plan").html(resp);
        });
        hiddenOptionRequest(1);
    }

    async function btnPlanView(id_requerimiento) {
        $("#tab-requerimiento-plan").html(loading)
        let data = { id_requerimiento: id_requerimiento }
        await getComponent('/api/requerimiento-plan', data, 'tab-requerimiento-plan')
        hiddenOptionRequest(1);
    }

    /////////////////////////////////////////////////////////////////////////////////////////////

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

    jQuery('#datatable-i').DataTable({
        "pageLength": 25,
        "ordering": false,
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
