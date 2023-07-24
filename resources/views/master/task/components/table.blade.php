<?php
$nro = 1;
?>
<div class="sourcesans table-responsive">
    <table id="datatable" class="table table-hover table-bordered">
        <thead class="fs-14 text-input" height="35px">
            <tr class="text-center">
                <th width='4%'>#</th>
                <th width='10%'>Cod.NISIRA</th>
                <th>Nombre</th>
                <th width='10%'>HA/HR</th>
                <th width='10%'>U.M.</th>
                <th width='10%'>Opción</th>
                <th width='10%'>Estado</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-13 sourcesansrg fw-500">
            @foreach( $labores as $labor )
            <?php
            $id = $labor->id;
            $btnWarning = " text-warning";
            $status = $labor->flag == '1'
            ? '<i class="fas fa-circle text-success mr-1"></i><small class="text-success">Activo</small>'
            : '<i class="fas fa-circle text-danger mr-1"></i><small class="text-danger">Inactivo</small>';

            ?>
            <tr>
                <td class="align-middle text-center font-weight-bold">{{ $nro++ }}</td>
                <td class="align-middle text-center">{{ $labor->code_nisira }}</td>
                <td class="align-middle">{{ $labor->nombre }}</td>
                <td class="align-middle text-center">{{ $labor->ratio }}</td>
                <td class="align-middle text-center">{{ $labor->siglas }}</td>
                <td class="align-middle text-center">
                    <button onclick="editTaskClick('{{ $id }}')" type="submit" class="<?php echo $btnWarning ?> border rounded fs-15 sourcesans tooltip-jac">
                        <i class="fas fa-pencil-alt"></i>
                        <span class="tooltip-box-jac">Editar</span>
                    </button>
                </td>
                <td class="align-middle text-center text-capitalize"><?php echo $status; ?></td>
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

    function editTaskClick(arg) {
        $("#tab-labores-edit").html('');
        hiddenTasks(1);


        var data = {
            id: arg,
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/tasks-get') !!}",
            data: data,
        }).done(function(view) {

            $("#tab-labores-edit").html(view);

        })
    }
</script>
