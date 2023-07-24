<form id="frm-save-trabajador">
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
                            <input autocomplete="off" id="dni" placeholder="Número de documento" type="text" class="form-control  " maxlength="8" onKeypress="if (event.keyCode < 48 || event.keyCode > 57) event.returnValue = false;" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="mb-0 text-secondary">Nombre(s)</label><span class="text-danger font-weight-bold">*</span>
                            <input autocomplete="off" id="nombre" placeholder="" type="text" class="form-control  " required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="mb-0 text-secondary">Apellido Paterno</label><span class="text-danger font-weight-bold">*</span>
                            <input autocomplete="off" id="apel-pat" placeholder="" type="text" class="form-control  " required>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="mb-0 text-secondary">Apellido Materno</label><span class="text-danger font-weight-bold">*</span>
                            <input autocomplete="off" id="apel-mat" placeholder="" type="text" class="form-control  " required>
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
                            <select class="form-control select2" id="slt-area">
                                <option value="0" selected>Seleccionar</option>
                                @foreach( $areas as $area )
                                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 sourcesans">
                            <label class="mb-0 text-secondary">Sub-Área</label><span class="text-danger font-weight-bold">*</span><span class="text-danger fs-11" id="error-area"></span><br>
                            <select class="form-control select2" id="slt-subarea">
                                <option value="0" selected>Seleccionar</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 sourcesans">
                            <label class="mb-0 text-secondary">Tipo trabajador</label><span class="text-danger font-weight-bold">*</span>
                            <select class="form-control select2" id="slt-tipo">
                                <option value="0" selected>Seleccionar</option>
                                @foreach( $employees as $employee )
                                <option value="{{ $employee->id }}">{{ $employee->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 sourcesans">
                            <label class="mb-0 text-secondary">Rol usuario</label><span class="text-danger font-weight-bold">*</span>
                            <select class="form-control select2" id="slt-rol">
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
    $(function() {
        $('.select2').select2({
            width: 'resolve',
            dropdownCssClass: "sourcesans fs-15",
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

    $("#slt-area").on('change', async function() {
        let area_id = $(this).val()
        let data = { area_id: area_id }
        console.log(data)
        await getComponent('/api/component-trabajador-subarea', data, 'slt-subarea')
    });

    // Guardar
    $("#frm-save-trabajador").on('submit', function(e) {
        e.preventDefault();

        var dni = $("#dni").val();
        var nombres  = $("#nombre").val();
        var apel_pat = $("#apel-pat").val();
        var apel_mat = $("#apel-mat").val();
        var slt_area   = $("#slt-area").val();
        var slt_tipo  = $("#slt-tipo").val();
        var slt_rol    = $("#slt-rol").val();
        let slt_suba = $("#slt-subarea").val();

        if( slt_area != '0' ) {
            if( slt_tipo != '0' ) {
                var data = {
                    dni: dni,
                    nombres: nombres,
                    apel_pat: apel_pat,
                    apel_mat: apel_mat,
                    rol: slt_rol,
                };

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: "{!! url('api/trabajador-usave') !!}",
                    data: data,
                }).done(function(msg) {
                    console.log(msg);
                    if ( msg != '303' && msg != '202' ) {
                        var iduser = msg;
                        toastr.success('Registro usuario exitoso.', 'AIDA', { "positionClass": "toast-top-right" });

                        var data = {
                            iduser: iduser,
                            area: slt_area,
                            tipo: slt_tipo,
                            dni: dni,
                            slt_suba: slt_suba
                        };

                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            url: "{!! url('api/trabajador-save') !!}",
                            data: data,
                        }).done(function(msg) {
                            console.log(msg);
                            if ( msg == '402' ) {
                                listEmployee();
                                $("#trabajador-create-tab").removeClass('active');
                                $("#trabajador-create-desc").removeClass('active show');

                                $("#trabajador-list-tab").addClass('active');
                                $("#trabajador-list-desc").addClass('active show');

                                toastr.success('Registro trabajador exitoso.', 'AIDA', { "positionClass": "toast-top-right" });
                                $("#dni").val("");
                                $("#nombre").val("");
                                $("#apel-pat").val("");
                                $("#apel-mat").val("");
                                $("#slt-area").select2('val', "0");
                                $("#slt-tipo").select2('val', "0");
                            }

                            if (msg == '303') {
                                toastr.error('Error al guardar trabajador.', 'AIDA', { "positionClass": "toast-top-right" });
                            }

                        }).fail(function(jqXHR, textStatus, errorThrown) {
                            // Mostramos en consola el mensaje con el error que se ha producido
                            // $("#consola").html("The following error occured: " + textStatus + " " + errorThrown);
                        });
                    }

                    if( msg == '202' ) {
                        toastr.warning('Dni registrado.', 'AIDA', { "positionClass": "toast-top-right" });
                        $("#dni").focus();
                    }

                    if (msg == '303') {
                        toastr.error('Error al guardar usuario.', 'AIDA', { "positionClass": "toast-top-right" });
                    }

                }).fail(function(jqXHR, textStatus, errorThrown) {
                    // Mostramos en consola el mensaje con el error que se ha producido
                    // $("#consola").html("The following error occured: " + textStatus + " " + errorThrown);
                });
            }else {
                toastr.info('Seleccionar un tipo trabajador', 'AIDA',{ "positionClass": "toast-top-right" });
                $("#slt-cargo").focus();
            }
        }else {
            toastr.info('Seleccionar una área', 'AIDA',{ "positionClass": "toast-top-right" });
            $("#slt-area").focus();
        }
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
