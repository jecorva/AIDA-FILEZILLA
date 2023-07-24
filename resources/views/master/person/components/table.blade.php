<?php
use Illuminate\Support\Facades\Crypt;

$nro = 1;
?>
<div class="sourcesans table-responsive">
    <table id="datatable" class="table table-hover table-bordered">
        <thead class="fs-14 text-input" height="35px">
            <tr class="text-center">
                <th width='5%'>#</th>
                <th width='10%'>DNI</th>
                <th>Apellidos y Nombre(s)</th>
                <th>Área</th>
                <th>Tipo</th>
                <th width='10%'>Estado</th>
                <th width='18%'>Operación</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-13 sourcesansrg fw-500">
            @foreach( $trabajadores as $trabajador )
            <?php
            $apel_nom = $trabajador->apel_pat. " " .$trabajador->apel_mat." ". $trabajador->nombres;
            $id_work = Crypt::encryptString( $trabajador->id );
            $status = $trabajador->flag == '1'
            ? '<i class="fas fa-circle text-success mr-1"></i><small class="text-success">Activo</small>'
            : '<i class="fas fa-circle text-danger mr-1"></i><small class="text-danger">Inactivo</small>';
            ?>
            <tr>
                <td class="align-middle text-center">{{ $nro++ }}</td>
                <td class="align-middle text-center">{{ $trabajador->dni }}</td>
                <td class="align-middle">{{ $apel_nom }}</td>
                <td class="align-middle text-center">{{ $trabajador->area }}</td>
                <td class="align-middle text-center">{{ $trabajador->tipo }}</td>
                <td class="align-middle text-center text-capitalize"><?php echo $status ?></td>
                <td class="align-middle text-center">
                    <div class="">
                        <button onclick="showEditEmployee('{{ $id_work }}')" type="submit"
                            class="border rounded fs-15 sourcesans text-warning tooltip-jac">
                            <i class="fas fa-pencil-alt"></i>
                            <span class="tooltip-box-jac">Editar</span>
                        </button>
                        <button onclick="bntEliminarUsuario('{{ $id_work }}')" type="submit"
                            class="border rounded fs-15 sourcesans text-danger tooltip-jac">
                            <i class="fas fa-trash"></i>
                            <span class="tooltip-box-jac">Eliminar</span>
                        </button>
                    </div>
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
    function showEditEmployee( id ) {
        hiddenOptionEmployee(1);
        var data = { id: id };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/trabajador-edit') !!}",
            data: data,
        }).done(function(view) {
            $("#tab-trabajador-edit").html(view);
        }).fail(function(jqXHR, textStatus, errorThrown) {
        });
    }


    jQuery('#datatable').DataTable({
        "pageLength": 25,
        "ordering": false,
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

    $(document).ready(function() {
        const tooltips = document.querySelectorAll('.tt')
        tooltips.forEach(t => {
            new bootstrap.Tooltip(t)
        });
    });
</script>
