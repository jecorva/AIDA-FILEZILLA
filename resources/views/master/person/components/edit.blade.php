<form id="frm-save-trabajador-edit">
    <input type="hidden" id="key-edit" value="{{ $work[0]->id }}" />
    <div class="row">
        <div class="col-md-6">
            <div class="card elevation-0 border">
                <div class="card-header py-2 bg-body text-secondary">
                    <label class="sourcesansrg h6 p-0 m-0">Registrar datos personales</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 text-secondary">
                            <label class="mb-0">D.N.I.</label><span class="text-danger font-weight-bold">*</span>
                            <input value="{{ $work[0]->dni }}" autocomplete="off" id="dni-edit" placeholder="Número de documento" type="text" class="form-control " maxlength="8" onKeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="mb-0 text-secondary">Nombre(s)</label><span class="text-danger font-weight-bold">*</span>
                            <input value="{{ $work[0]->nombres }}" autocomplete="off" id="nombre-edit" placeholder="" type="text" class="form-control " disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="mb-0 text-secondary">Apellido Paterno</label><span class="text-danger font-weight-bold">*</span>
                            <input value="{{ $work[0]->apel_pat }}" autocomplete="off" id="apel-pat-edit" placeholder="" type="text" class="form-control " disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="mb-0 text-secondary">Apellido Materno</label><span class="text-danger font-weight-bold">*</span>
                            <input value="{{ $work[0]->apel_mat }}" autocomplete="off" id="apel-mat-edit" placeholder="" type="text" class="form-control " disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card elevation-0 border">
                <div class="card-header py-2 bg-body text-secondary">
                    <label class="sourcesansrg h6 p-0 m-0">Registrar datos generales</label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-6 sourcesans">
                            <label class="mb-0 text-secondary">Área</label><span class="text-danger font-weight-bold">*</span><span class="text-danger fs-11" id="error-area"></span><br>
                            <select class="form-control select2" id="slt-area-edit">
                                <option value="0" selected>Seleccionar</option>
                                @foreach( $areas as $area )
                                <?php
                                $selected = $area->id == $work[0]->area_id ? "selected" : "";
                                ?>
                                <option value="{{ $area->id }}" <?php echo $selected ?>>{{ $area->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 sourcesans">
                            <label class="mb-0 text-secondary">Sub-Área</label><span class="text-danger font-weight-bold">*</span><span class="text-danger fs-11" id="error-area"></span><br>
                            <select class="form-control select2" id="slt-subarea-edit">
                                <option value="0" selected>Seleccionar</option>
                                @foreach( $subareas as $subarea )
                                <?php
                                $selected = $subarea->id == $work[0]->subarea_id ? "selected" : "";
                                ?>
                                <option value="{{ $subarea->id }}" <?php echo $selected ?>>{{ $subarea->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">

                        <div class="form-group col-md-6 sourcesans">
                            <label class="mb-0 text-secondary">Tipo trabajador</label><span class="text-danger font-weight-bold">*</span>
                            <select class="form-control select2" id="slt-cargo-edit">
                                <option value="0" selected>Seleccionar</option>
                                @foreach( $tipos as $tipo )
                                <?php
                                $selected = $tipo->id == $work[0]->typeperson_id ? "selected" : "";
                                ?>
                                <option value="{{ $tipo->id }}" <?php echo $selected ?>>{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group col-md-6 sourcesans">
                            <label class="mb-0 text-secondary">Rol usuario</label><span class="text-danger font-weight-bold">*</span>
                            <select class="form-control" id="slt-rol-edit" disabled>
                                <option>Seleccionar</option>
                                @foreach( $roles as $rol )
                                <?php
                                $selected = $rol->id == 4 ? "selected" : "";
                                ?>
                                <option value="{{ $rol->id }}" <?php echo $selected  ?>>{{ $rol->nombre }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <div class="text-right">
            <button type="submit" class="btn btn-bluedark text-white sourcesansrg fs-13 m-0 rounded-1">
                <i class="fas fa-save mr-1"></i></i>Guardar
            </button>
        </div>
    </div>
</form>
<script>

    $("#slt-area-edit").on('change', async function() {
        let area_id = $(this).val()
        let data = { area_id: area_id }
        console.log(data)
        await getComponent('/api/component-trabajador-subarea', data, 'slt-subarea-edit')
    });

    $("#frm-save-trabajador-edit").on('submit', function(e) {
        e.preventDefault();
        var sarea = $("#slt-area-edit").val();
        var subarea = $("#slt-subarea-edit").val();
        var stipo = $("#slt-cargo-edit").val();
        var key = $("#key-edit").val();

        if (sarea != '0') {
            if (stipo != '0') {

                var data = {
                    key: key,
                    sarea: sarea,
                    stipo: stipo,
                    subarea: subarea
                };

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url:"{!! url('api/trabajador-update') !!}",
                    data: data,
                }).done(function(msg) {
                    console.log(msg);
                    if (msg == '402') {
                        listEmployee();

                        $("#trabajador-edit-tab").removeClass('visible active');
                        $("#trabajador-edit-desc").removeClass('active show');
                        $("#trabajador-edit-tab").addClass('invisible');

                        $("#trabajador-list-tab").addClass('active');
                        $("#trabajador-list-desc").addClass('active show');

                        toastr.success('Registro actualizado.', 'AIDA', {
                            "positionClass": "toast-top-right"
                        });
                    }

                    if (msg == '303') {
                        toastr.error('Error al actualizar.', 'AIDA', {
                            "positionClass": "toast-top-right"
                        });
                    }

                })

            } else {
                toastr.info('Seleccionar <b>Tipo Trabajador!</b>', 'AIDA', {
                    "positionClass": "toast-top-right"
                });
                $("#slt-cargo-edit").focus();
            }

        } else {
            toastr.info('Seleccionar <b>Área!</b>', 'AIDA', {
                "positionClass": "toast-top-right"
            });
            $("#slt-area-edit").focus();
        }
    })

    $(function() {
        $('.select2').select2({
            width: 'resolve',
            dropdownCssClass: "sourcesans fs-13",
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
