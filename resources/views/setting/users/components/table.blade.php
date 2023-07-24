<?php

use Illuminate\Support\Facades\Crypt;

$nro = 1;
?>
<div class="sourcesans">
    <table id="datatable" class="table table-hover table-bordered table-responsive-md">
        <thead class="fs-14 bg-light text-input" height="35px">
            <tr class="text-center">
                <th width="3%">Nro</th>
                <th width="10%">Dni</th>
                <th width="10%">Apell. Pat.</th>
                <th width="10%">Apell. Mat.</th>
                <th width="15%">Nombres</th>
                <th>Email</th>
                <th width="15%">Rol</th>
                <th width="5%">Estado</th>
                <th width="10%">Operación</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-13 sourcesansrg fw-500 text-secondary">
            @foreach( $usuarios as $usuario )
            <?php
            $disabled = $usuario->rol_id == '1' ? "disabled" : "";
            $disabled_delete = $usuario->flag == '0' ? "disabled" : "";
            $btnWarning = "text-warning";
            $btnDanger = "text-danger";

            if( $disabled != "" || $disabled_delete != "" ) {
                $btnWarning = "btn-outline-secondary";
                $btnDanger = "btn-outline-secondary";
            }

            $id_usuario = Crypt::encryptString($usuario->id);
            $status = $usuario->flag == '1'
            ? '<i class="fas fa-circle text-success mr-1"></i><small class="text-success">Activo</small>'
            : '<i class="fas fa-circle text-danger mr-1"></i><small class="text-danger">Inactivo</small>';
            ?>
            <tr>
                <td class="align-middle font-weight-bold text-center">{{ $nro++ }}</td>
                <td class="align-middle text-center">{{ $usuario->dni }}</td>
                <td class="align-middle">{{ $usuario->apel_pat }}</td>
                <td class="align-middle">{{ $usuario->apel_mat }}</td>
                <td class="align-middle">{{ $usuario->nombres }}</td>
                <td class="align-middle text-center text-lowercase">{{ $usuario->email }}</td>
                <td class="align-middle text-center">{{ $usuario->nameRol }}</td>
                <td class="align-middle text-center text-capitalize">
                    <?php echo $status ?>
                </td>
                <td class="align-middle text-center">
                    <div class="">
                    <!-- <div class="btn-group rounded border border-white">-->
                        <button onclick="btnEditarUsuario('{{ $id_usuario }}')" type="submit"
                            class="<?php echo $btnWarning ?> border rounded fs-15 sourcesans tooltip-jac"
                            <?php echo $disabled_delete ?>
                            <?php echo $disabled ?>>
                            <i class="fas fa-pencil-alt"></i>
                            @if( $disabled == "" )<span class="tooltip-box-jac">Editar</span>@endif
                            <!--<i class="fas fa-pencil-alt mr-1 text-white-50 fs-10"></i>Editar-->
                        </button>
                        <button onclick="bntEliminarUsuario('{{ $id_usuario }}')" type="submit"
                            class="<?php echo $btnDanger ?> border rounded fs-15 sourcesans tooltip-jac"
                            <?php echo $disabled_delete ?>
                            <?php echo $disabled ?>>
                            <i class="fas fa-trash"></i>
                            @if( $disabled == "" )<span class="tooltip-box-jac">Eliminar</span>@endif
                            <!-- <i class="fas fa-trash mr-1 text-white-50 fs-10"></i>Eliminar -->
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>

<script>
    function btnEditarUsuario(id) {
        hiddenTabEditar(1);
        var data = { id: id };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/user-edit') !!}",
            data: data,
        }).done(function (view) {
            $("#tab-user-edit").html(view);
        }).fail(function (jqXHR, textStatus, errorThrown) {
        });
    }

    function bntEliminarUsuario(id) {
        var data = {
            id: id,
        };

        Swal.fire({
            title: 'desea eliminar usuario?',
            // text: "Todos los usuarios eliminados pasan a estado de inactivos.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'No',
            customClass: {
                title: 'sourcesans',
                confirmButton: 'sourcesans',
                denyButton: 'sourcesans',
                cancelButton: 'sourcesans',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: "{!! url('api/user-delete') !!}",
                    data: data,
                }).done(function(resp) {
                    console.log(resp);
                    if( resp == "402") {
                        getListUser();
                        toastr.success('Usuario deshabilitado correctamente.');
                    }

                    if( resp == "303") {
                        toastr.error('Error al eliminar usuario.');
                    }


                }).fail(function(jqXHR, textStatus, errorThrown) {
                    // Mostramos en consola el mensaje con el error que se ha producido
                    $("#consola").html("The following error occured: " + textStatus + " " + errorThrown);
                });
            }
        })
    }


    jQuery('#datatable').DataTable({
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

    $(document).ready(function () {
        const tooltips = document.querySelectorAll('.tt')
        tooltips.forEach(t => {
            new bootstrap.Tooltip(t)
        });
    });
</script>
