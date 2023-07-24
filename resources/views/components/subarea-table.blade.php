<?php
$nro = 1;
?>
<div class="sourcesans table-responsive">
    <table id="datatable" class="table table-hover table-bordered">
        <thead class="fs-14 text-input" height="35px">
            <tr class="text-center">
                <th width='5%'>Nro</th>
                <th >Nombre</th>
                <th>Área</th>
                <th >Opción</th>
                <th >Estado</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-13 sourcesansrg fw-500">
            @foreach( $subareas as $subarea )
            <?php
            $id = $subarea->id;
            $btnWarning = " text-warning";
            $btnDanger = " text-danger";
            $status = $subarea->flag == '1'
            ? '<i class="fas fa-circle text-success mr-1"></i><small class="text-success">Activo</small>'
            : '<i class="fas fa-circle text-danger mr-1"></i><small class="text-danger">Inactivo</small>';
            ?>

            <tr>
                <td class="align-middle text-center font-weight-bold">{{ $nro++ }}</td>
                <td class="align-middle text-center">{{ $subarea->nombre }}</td>
                <td class="align-middle">{{ $subarea->nom_area }}</td>
                <td class="align-middle text-center">
                    <button onclick="editSubareaClick('{{ $id }}')" type="submit" class="<?php echo $btnWarning ?> border rounded fs-15 sourcesans tooltip-jac">
                        <i class="fas fa-pencil-alt"></i>
                        <span class="tooltip-box-jac">Editar</span>
                    </button>
                    <button onclick="deleteSubareaClick('{{ $id }}')" type="submit" class="<?php echo $btnDanger ?> border rounded fs-15 sourcesans tooltip-jac">
                        <i class="fas fa-trash-alt"></i>
                        <span class="tooltip-box-jac">Eliminar</span>
                    </button>
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
    var loading ="<div class='text-center'><span class='fa-stack fa-lg'>\n\
                    <i class='fa fa-spinner fa-spin fa-stack fa-fw'></i>\n\
                </span>&emsp;Cargando ...</div>";

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

    async function editSubareaClick(arg) {
        $("#tab-subarea-edit").html(loading);
        hiddenOptionSubarea(1);

        let data = { arg: arg }
        await getComponent('/api/component-edit-subarea', data, 'tab-subarea-edit')
    }
    // 
    async function deleteSubareaClick(id) {
        let data = { id: id }

        Swal.fire({
                title: 'Esta seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, Eliminar'
            }).then( async (result) => {
                if (result.isConfirmed) {
                    const response = await requestManager('/api/subarea-delete', data);
                    console.log(response)
                    if( response.status == 200 ) {
                        listSubarea();

                        toastr.success( response.message, 'AIDA', {
                            "positionClass": "toast-top-right"
                        });
                    }
                }
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

</script>
