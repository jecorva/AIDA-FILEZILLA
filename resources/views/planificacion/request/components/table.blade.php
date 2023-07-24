<?php
///////////// Tabla principal sin registros ////////////
//////////// Select para elegir al supervisor //////////
$disabled = "";

foreach($supervisores as $supervisor) {
    //$disabled = $supervisor->user_id == $id_user ? 'disabled' : '';
    if( $supervisor->user_id == $id_user ) $disabled = 'disabled';
    // echo $supervisor->user_id."<br>";
}

$nro = 1;
?>
<div class="alert bg-bluedark opensans text-white"><i class="fas fa-info-circle mr-2"></i>Semana de requerimientos</div>
<div class="row">
    <input  type="hidden" value="{{ $id_user }}" id="key" />
    <div class="col-md-5">
        <!-- <label class="mb-1 text-label fs-14 mr-1">Solicitante</label><span class="text-danger font-weight-bold">*</span><br> -->
        <select class="form-control font-weight-bold select2" id="slt-employee-list" {{ $disabled }}>
            <option value="0" selected>Seleccionar solicitante</option>
            @foreach( $supervisores as $supervisor )
            <?php
            $selected = $supervisor->user_id == $id_user ? 'selected' : '';
            $apel_pat = $supervisor->apel_pat . " " . $supervisor->apel_mat . " - " . $supervisor->nombres;
            $name = strtoupper($apel_pat);
            ?>
            <option value="{{ $supervisor->person_id }}" {{ $selected }} >{{ $name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <button type="button" onclick="fnSearchRequest()" class="btn btn-bluedark text-white sourcesansrg fs-13 m-0 rounded-1">
            <i class="fas fa-search mr-1"></i>Listar</button>
    </div>
</div>
<br>
<div class="sourcesans">
    <div id="content-table" class=" table-responsive-md">
        <table id="datatable" class="table table-hover table-bordered">
            <thead class="text-input fs-14">
                <tr class="text-center">
                    <th width='5%'>#</th>
                    <th>Supervisor</th>
                    <th width='5%'>Turno</th>
                    <th width='5%'>Nro.Sem.</th>
                    <th width='15%'>Fecha Inicio</th>
                    <th width='15%'>Fecha Fin</th>
                    <th width='10%'>Operación</th>
                </tr>
            </thead>
            <tbody class="text-uppercase fs-13 sourcesansrg fw-500">

            </tbody>
        </table>
    </div>
</div>

<script>
    var loading ="<div class='text-center'><span class='fa-stack fa-lg'>\n\
                    <i class='fa fa-spinner fa-spin fa-stack fa-fw'></i>\n\
                </span>&emsp;Cargando ...</div>"

    async function fnSearchRequest() {
        var id_trabajador = $('#slt-employee-list').val();
        var data = {
            id_trabajador: id_trabajador
        };
        $("#content-table").html(loading);
        await getComponent('/api/requerimiento-list-table', data, 'content-table')

    }

    $("#slt-employee-list").on("change", function() {

    })

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    async function requestAPI(url, data) {
        let uri = "{!! url('') !!}";
        var result = await $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: uri + url,
            type: 'POST',
            data: data,
        })
        return result
    }

    async function getComponent(url, data, id) {
        let uri = "{!! url('') !!}";
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: uri + url,
            type: 'POST',
            data: data,
        }).done(function(component) {
            $("#" + id).html(component)
        })
    }

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

    $('.select2').select2({
        dropdownCssClass: "sourcesans fw-500 fs-13",
        language: {
            noResults: function() {
                return "Sin resultados";
            },
            searching: function() {
                return "Buscando..";
            }
        }
    });


</script>
