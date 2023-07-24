<?php

use Illuminate\Support\Facades\Crypt;

$nro = 1;
?>
<div class="sourcesans table-responsive">
    <table id="datatable" class="table table-hover table-bordered">
        <thead class="fs-14 text-input" height="35px">
            <tr class="text-center">
                <th width='5%'>#</th>
                <th width='15%'>Nombre</th>
                <th>Descripción</th>
                <th width='10%'>Estado</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-13 sourcesansrg fw-500">
            @foreach( $ttrabajadors as $trabajador )
            <?php
            $status = $trabajador->flag == '1'
            ? '<i class="fas fa-circle text-success mr-1"></i><small class="text-success">Activo</small>'
            : '<i class="fas fa-circle text-danger mr-1"></i><small class="text-danger">Inactivo</small>';

            // $id_area = Crypt::encryptString($area->id);

            ?>
            <tr>
                <td class="align-middle text-center font-weight-bold">{{ $nro++ }}</td>
                <td class="align-middle">{{ $trabajador->nombre }}</td>
                <td class="align-middle">{{ $trabajador->descripcion }}</td>
                <td class="align-middle text-center text-capitalize"><?php echo $status ?></td>
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
        "pageLength": 10,
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
