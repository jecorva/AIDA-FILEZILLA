<div class="container-fluid">
    <div class="row">
        <div class="col-md-1">
            <label class="mb-0 text-secondary fs-14 mr-1">Año actual</label><span class="text-danger font-weight-bold">*</span>
            <input id="anio" value="{{ $anio }}" class="form-control text-uppercase" required disabled />
        </div>
        <div class="col-md-2">
            <label class="mb-0 text-secondary fs-14 mr-1">Nº de Semana</label><span class="text-danger font-weight-bold">*</span>
            <select class="form-control select2" id="slt-semana">
                <option value="0" disabled selected>Seleccionar</option>
                @foreach( $semanas as $semana )
                <option value="{{ $semana->nro_semana }}">Semana Nº {{ $semana->nro_semana }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="mb-0 text-secondary fs-14 mr-1">Supervisor</label>
            <select class="form-control select2" id="slt-supervisor" disabled>
                <option value="0" disabled selected>Seleccionar</option>
            </select>
        </div>
        <div class="col-md-3">
            <label class="mb-0 text-secondary fs-14 mr-1">Área</label>
            <select class="form-control select2" id="slt-area" disabled>
                <option value="0" selected>Seleccionar</option>
                @foreach($areas as $area)
                <option value="{{ $area->id }}" >{{ $area->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="mb-0 text-secondary fs-14 mr-1">Sub-Área</label>
            <select class="form-control select2" id="slt-subarea" disabled>
                <option value="0" selected>Seleccionar</option>
            </select>
        </div>
    </div>
</div>
<hr>

<script>
    var $table = $('#table');

    $("#slt-semana").on('change', async function() {
        var anio = $("#anio").val();
        var nro_semana = $("#slt-semana").val();
        $("#slt-supervisor").prop('disabled', false);
        $("#slt-area").prop('disabled', false);
        $("#slt-subarea").prop('disabled', false);
        listMachinery(anio, nro_semana);
        let data = { anio: anio, nro_semana: nro_semana }
        await getComponent('/api/component-select-supervisor', data, 'slt-supervisor')
    });

    $("#slt-supervisor").on('change', function() {
        //$("#slt-area").select2('refresh');
        //$("#slt-subarea").select2('val', '0');
        let sup = $(this).val()
        let anio = $("#anio").val();
        let nro_semana = $("#slt-semana").val();
        let data = { nro_semana: nro_semana, anio: anio, person_id: sup }
        console.log(data)
        var url = '{!! url("api/planificacion-list-supervisor/'+ nro_semana +'/'+ anio +'/'+ sup +'") !!}';
        console.log(url)
        $table.bootstrapTable('refreshOptions', {
            search: true,
            url: url
        })
    });

    $("#slt-area").on('change', async function() {
        $("#slt-supervisor").select2('val', '0')
        let area_id = $(this).val()
        let data ={ area_id: area_id }
        await getComponent('/api/component-trabajador-subarea', data, 'slt-subarea')
        let anio = $("#anio").val();
        let nro_semana = $("#slt-semana").val();
        var url = '{!! url("api/planificacion-list-area/'+ nro_semana +'/'+ anio +'/'+ area_id +'") !!}';
        console.log(url)
        $table.bootstrapTable('refreshOptions', {
            search: true,
            url: url
        })
    });

    $("#slt-subarea").on('change', async function() {
        //$("#slt-supervisor").select2('val', '0')
        let subarea_id = $(this).val()
        let anio = $("#anio").val();
        let nro_semana = $("#slt-semana").val();
        var url = '{!! url("api/planificacion-list-subarea/'+ nro_semana +'/'+ anio +'/'+ subarea_id +'") !!}';
        console.log(url)
        $table.bootstrapTable('refreshOptions', {
            search: true,
            url: url
        })
    });

    ////////////////////////////////////////////////
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

    $(function() {
        $('.select2').select2({
            dropdownCssClass: "opensans fs-13",
            language: {
                noResults: function() {
                    return "Sin resultados";
                },
                searching: function() {
                    return "Buscando..";
                }
            }
        });
    });
</script>
