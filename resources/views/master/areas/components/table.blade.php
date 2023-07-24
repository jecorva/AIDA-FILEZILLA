<?php
$nro = 1;
?>
<div class="sourcesans table-responsive">
    <table id="datatable" class="table table-hover table-bordered">
        <thead class="fs-14 text-input" height="35px">
            <tr class="text-center">
                <th width='3%'>Nro</th>
                <th width='10%'>Cod.NISIRA</th>
                <th>Descripción</th>
                <!-- <th>Operación</th> -->
                <th width='8%'>Estado</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-13 sourcesansrg fw-500 ">
            @foreach( $areas as $area )
            <?php
            $id = $area->id;
            $btnWarning = " text-warning";
            $btnDanger = " text-success";
            $status = $area->flag == '1'
            //? "<label class='w3-code table-success rounded-right-jac px-2 my-0 fs-12'>Activo</label>"
            ? '<i class="fas fa-circle text-success mr-1"></i><small class="text-success">Activo</small>'
            : '<i class="fas fa-circle text-danger mr-1"></i><small class="text-danger">Inactivo</small>';

            // $id_area = Crypt::encryptString($area->id);

            ?>
            <tr>
                <td class="align-middle text-center font-weight-bold">{{ $nro++ }}</td>
                <td class="align-middle text-center">{{ $area->code_nisira }}</td>
                <td class="align-middle">{{ $area->nombre }}</td>
                <!-- <td class="align-middle text-center">
                    <button onclick="editAreaClick('{{ $id }}')" type="submit" class="<?php echo $btnWarning ?> border rounded fs-15 sourcesans tooltip-jac">
                        <i class="fas fa-pencil-alt"></i>
                        <span class="tooltip-box-jac">Editar</span>
                    </button>
                </td> -->
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
    var loading ="<div class='text-center'><span class='fa-stack fa-lg'>\n\
                    <i class='fa fa-spinner fa-spin fa-stack fa-fw'></i>\n\
                </span>&emsp;Cargando ...</div>";

    async function editAreaClick() {
        $("#area-tab-edit").html(loading);
        hiddenOptionArea(1);

        let data = { id: arg, };
        await getComponent('/api/component-edit-area', data, 'area-tab-edit')
    }

    /////////////////////////////////////////////////

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

    async function requestManager(url, data) {
        let uri = "{!! url('') !!}";
        var result = await $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: uri + url,
            type: 'POST',
            data: data,
        })
        return result;
    }

    jQuery('#datatable').DataTable({
        "pageLength": 25,
        "ordering": false,
        "scrollY": "300px",
        //"scrollCollapse": true,
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
