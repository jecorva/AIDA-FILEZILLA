<?php

use Illuminate\Support\Facades\Crypt;

$nro = 1;
?>
<div class="sourcesans">    
    <table id="datatable" class="table table-striped table-responsive-md">
        <thead class="fs-14 table-secondary text-secondary text-input" height="35px">
            <tr class="text-center">
                <th width="3%">Nro</th>
                <th width="20%">Nombre</th>
                <th>Descripción</th>
                <th width="10%">Operación</th>
            </tr>
        </thead>
        <tbody class="text-uppercase fs-13 RobotoC fw-500 text-secondary">
            @foreach( $roles as $rol )
            <?php
            $disabled = $rol->id == '1' ? "disabled" : "";
            $id_rol = Crypt::encryptString($rol->id);
            $btnWarning = " text-warning";
            $btnDanger = " text-success";

            if ( $disabled != "") {
                $btnWarning = "btn-outline-secondary";
                $btnDanger = "btn-outline-secondary";
            }
            ?>
            <tr>
                <td class="align-middle text-center font-weight-bold">{{ $nro++ }}</td>
                <td class="align-middle">{{ $rol->nombre }}</td>
                <td class="align-middle">{{ $rol->descripcion }}</td>
                <td class="align-middle text-center">
                    <div class="">
                    <!-- <div class="btn-group rounded"> -->
                        <button onclick="btnEditarRol('{{ $id_rol }}')" type="submit" 
                            class="<?php echo $btnWarning ?> border rounded fs-15 sourcesans tooltip-jac"
                            <?php echo $disabled ?>>
                            <i class="fas fa-pencil-alt"></i>
                            @if( $disabled == "" )<span class="tooltip-box-jac">Editar</span>@endif
                            <!--<i class="fas fa-pencil-alt mr-1 text-white-50 fs-10"></i>Editar-->
                        </button>
                        <button onclick="deleteUser('{{ $id_rol }}')" type="submit" 
                            class="<?php echo $btnDanger ?> border rounded fs-15 sourcesans tooltip-jac"
                            <?php echo $disabled ?>>
                            <i class="fas fa-check"></i>
                            @if( $disabled == "" )<span class="tooltip-box-jac">Permisos</span>@endif
                            <!-- <i class="fas fa-check-square mr-1 text-white-50"></i>Permisos -->
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script>
    function btnEditarRol(id) {
        hiddenTabEditarRol(1);

        var data = {
            id: id,
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/rol-edit') !!}", 
            data: data,
        }).done(function(view) {
            
            $("#tab-rol-edit").html(view);

        }).fail(function(jqXHR, textStatus, errorThrown) {
            // Mostramos en consola el mensaje con el error que se ha producido
            $("#consola").html("The following error occured: " + textStatus + " " + errorThrown);
        });
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

    $(document).ready(function() {
        const tooltips = document.querySelectorAll('.tt')
        tooltips.forEach(t => {
            new bootstrap.Tooltip(t)
        });
    });
</script>