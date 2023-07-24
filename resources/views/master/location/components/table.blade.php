<?php
$nro = 1;
?>
<div class="sourcesans">
    <table id="datatable" class="table table-striped table-responsive-md">
        <thead class="fs-14 table-secondary text-secondary text-input" height="35px">
            <tr class="text-center">
                <th width='5%'>Nro</th>
                <th width='10%'>Dim. 5</th>
                <th>Descripción Dim. 5</th>
                <!-- <th width='10%'>Ratio</th> -->
                <!-- <th width='10%'>Opción</th> -->
                <th width='10%'>Estado</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-12 RobotoC fw-500 text-secondary">
            @foreach( $locations as $location )
            <?php
            $id = $location->id;            
            $btnWarning = " text-warning";
            $btnDanger = " text-success";            
            $status = $location->flag == '1'
                ? '<blockquote class="quote-success m-0 py-1 table-success rounded-right sourcesans text-success">Activo</blockquote>'
                : '<blockquote class="quote-danger m-0 py-1 table-danger rounded-right sourcesans text-danger">Inactivo</blockquote>';

            $checked = $location->flag == '1'
                ? 'checked'
                : '';
            ?>
            <tr>
                <td class="align-middle text-center font-weight-bold">{{ $nro++ }}</td>
                <td class="align-middle text-center">{{ $location->dim5 }}</td>
                <td class="align-middle">{{ $location->nombre }}</td>
                <!-- <td class="align-middle text-center">{{ $location->ratio }}</td> -->
                <!-- <td class="align-middle text-center">
                    <button onclick="editLocationClick('{{ $id }}')" type="submit" class="<?php echo $btnWarning ?> border rounded fs-15 sourcesans tooltip-jac">
                        <i class="fas fa-pencil-alt"></i>
                        <span class="tooltip-box-jac">Editar</span>
                        <i class="fas fa-pencil-alt mr-1 text-white-50 fs-10"></i>Editar
                    </button>                    
                </td> -->
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

    function editLocationClick(arg) {
        $("#tab-location-edit").html('');
        hiddenLocation(1);


        var data = {
            id: arg,
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/location-get') !!}",
            data: data,
        }).done(function(view) {
            
            $("#tab-location-edit").html(view);

        })
    }

    /*function checkedMachinerie(id, nro) {        
        if ($("#chb-" + nro).is(':checked')) {
            $("#stt-" + nro).html('<blockquote class="quote-success m-0 py-1 table-success rounded-right sourcesans text-success">Activo</blockquote>');
            checkedStateMachinerie('1', id, 'habilitada');
        } else {            
            $("#stt-" + nro).html('<blockquote class="quote-danger m-0 py-1 table-danger rounded-right sourcesans text-danger">Inactivo</blockquote>');
            checkedStateMachinerie('0', id, 'deshabilitada');
        }
    }

    function checkedStateMachinerie(flag, id, title) {
        var data = {            
            flag: flag,
            id: id
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '../api/machinerie-state',
            data: data
        }).done(function(resp) {
            console.log(resp);
            if (resp == "402") {
                toastr.info('location '+title, '', {
                    "positionClass": "toast-top-right"
                });               
            }

            if (resp == "303") {
                toastr.warning('Error al conectarse BD', '', {
                    "positionClass": "toast-top-right"
                });
            }

        })
    }*/
</script>