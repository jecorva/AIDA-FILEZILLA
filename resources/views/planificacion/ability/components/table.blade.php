<?php
use Illuminate\Support\Facades\Crypt;
$nro = 1;
?>
<div class="sourcesans">
    <table id="datatable" class="table table-striped table-responsive-md">
        <thead class="fs-14 table-secondary text-secondary text-input" height="35px">
            <tr class="text-center">
                <th width='5%'>Nro</th>
                <th width='10%'>DNI</th>
                <th>Apellidos y Nombre(s)</th>
                <th>Implemento</th>
                <th>Maquinaria</th>                                
                <th width='10%'>Acción</th>
                <th width='10%'>Estado</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-12 RobotoC fw-500 text-secondary">
            @foreach( $operarios as $operario )
            <?php
            $apel_nom = $operario->apel_pat. " " .$operario->apel_mat." - ". $operario->nombres;
            $id_work = Crypt::encryptString( $operario->id );
            $status = $operario->flag == '1'
                ? '<blockquote class="quote-success m-0 py-1 table-success rounded-right sourcesans text-success">Activo</blockquote>'
                : '<blockquote class="quote-danger m-0 py-1 table-danger rounded-right sourcesans text-danger">Inactivo</blockquote>';
            
            $checked = $operario->flag == '1'
                ? 'checked'
                : '';
            ?>
            <tr>
                <td class="align-middle text-center font-weight-bold">{{ $nro++ }}</td>
                <td class="align-middle text-center">{{ $operario->dni }}</td>
                <td class="align-middle">{{ $apel_nom }}</td>
                <td class="align-middle">{{ $operario->implemento }}</td>
                <td class="align-middle">{{ $operario->maquinaria }}</td>                                
                <td class="align-middle text-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="chb-<?php echo $nro; ?>" onclick="checkedOperator('<?php echo $operario->id ?>', '<?php echo $nro ?>')" <?php echo $checked ?>>
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
<script>
    jQuery('#datatable').DataTable({
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

    function checkedOperator(id, nro) {        
        if ($("#chb-" + nro).is(':checked')) {
            $("#stt-" + nro).html('<blockquote class="quote-success m-0 py-1 table-success rounded-right sourcesans text-success">Activo</blockquote>');
            checkedStateOperator('1', id, 'Activa');
        } else {            
            $("#stt-" + nro).html('<blockquote class="quote-danger m-0 py-1 table-danger rounded-right sourcesans text-danger">Inactivo</blockquote>');
            checkedStateOperator('0', id, 'Inactiva');
        }
    }

    function checkedStateOperator(flag, id, title) {
        var data = {            
            flag: flag,
            id: id
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/operator-state') !!}",
            data: data
        }).done(function(resp) {
            console.log(resp);
            if (resp == "402") {
                toastr.success('Habilidad <b>'+title+'!</b>', 'AIDA', {
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
</script>