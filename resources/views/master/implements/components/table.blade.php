<?php
$nro = 1;
?>
<div class="sourcesans table-responsive">
    <table id="datatable" class="table table-hover table-bordered">
        <thead class="fs-14 text-input" height="35px">
            <tr class="text-center">
                <th width='4%'>Nro</th>
                <th width='10%'>Dim. 5</th>
                <th width='10%'>Abby</th>
                <th>Descripción Dim. 5</th>
                <th width='10%'>Opción</th>
                <th width='10%'>Acción</th>
                <th width='8%'>Estado</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-13 sourcesansrg fw-500">
            @foreach( $implementos as $implemento )
            <?php
            $id = $implemento->id;
            $btnWarning = " text-warning";
            $status = $implemento->flag == '1'
            ? '<i class="fas fa-circle text-success mr-1"></i><small class="text-success">Activo</small>'
            : '<i class="fas fa-circle text-danger mr-1"></i><small class="text-danger">Inactivo</small>';

            $checked = $implemento->flag == '1'
                ? 'checked'
                : '';

            ?>
            <tr>
                <td class="align-middle text-center font-weight-bold">{{ $nro++ }}</td>
                <td class="align-middle text-center">{{ $implemento->code_nisira }}</td>
                <td class="align-middle text-center">{{ $implemento->code_abby }}</td>
                <td class="align-middle">{{ $implemento->nombre }}</td>
                <td class="align-middle text-center">
                    <button onclick="editImplementClick('{{ $id }}')" type="submit" class="<?php echo $btnWarning ?> border rounded fs-15 sourcesans tooltip-jac">
                        <i class="fas fa-pencil-alt"></i>
                        <span class="tooltip-box-jac">Editar</span>
                    </button>
                </td>
                <td class="align-middle text-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="chb-<?php echo $nro; ?>" onclick="checkedImplement('<?php echo $id ?>', '<?php echo $nro ?>')" <?php echo $checked ?>>
                        <label class="form-check-label sourcesans" for="invalidCheck"></label>
                    </div>
                </td>
                <td class="align-middle text-center text-capitalize">
                    <div id="stt-<?php echo $nro; ?>"><?php echo $status; ?></div>
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

    function checkedImplement(id, nro) {
        if ($("#chb-" + nro).is(':checked')) {
            $("#stt-" + nro).html('<blockquote class="quote-success m-0 py-1 table-success rounded-right sourcesans text-success">Activo</blockquote>');
            checkedStateImplement('1', id, 'habilitado');
        } else {
            $("#stt-" + nro).html('<blockquote class="quote-danger m-0 py-1 table-danger rounded-right sourcesans text-danger">Inactivo</blockquote>');
            checkedStateImplement('0', id, 'deshabilitado');
        }
    }

    function checkedStateImplement(flag, id, title) {
        var data = {
            flag: flag,
            id: id
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/implemento-state') !!}",
            data: data
        }).done(function(resp) {
            console.log(resp);
            if (resp == "402") {
                toastr.success('Implemento '+title, 'AIDA', {
                    "positionClass": "toast-top-right"
                });
            }

            if (resp == "303") {
                toastr.warning('Error al conectarse BD', 'AIDA', {
                    "positionClass": "toast-top-right"
                });
            }

        })
    }

    function editImplementClick(arg) {
        $("#tab-implemento-edit").html('');
        hiddenOptionImplement(1);
        var data = {
            id: arg,
        };
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/implemento-get') !!}",
            data: data,
        }).done(function(view) {
            $("#tab-implemento-edit").html(view);
        })
    }

    $(document).ready(function() {
        const tooltips = document.querySelectorAll('.tt')
        tooltips.forEach(t => {
            new bootstrap.Tooltip(t)
        });
    });
</script>
