<form id="frm-save-operario">
    <div class="row">
        <div class="col-md-6">
            <!-- <label class="raleway-title  h6">Datos del trabajador</label> -->
            <div class="card px-3 pt-2 elevation-0 border fs-13">
                <div class="row">
                    <div class="form-group col-md-12 RobotoC">
                        <label class="mb-1 text-label mr-1">Apellidos y Nombres</label><span class="text-danger font-weight-bold">*</span><span class="text-danger fs-11" id="error-area"></span><br>
                        <div class="fs-14 text-label">
                            <select class="form-control select2" id="slt-work">
                                <option value="0" selected>Seleccionar</option>
                                @if( $status == "402" )
                                @foreach( $trabajadores as $trabajador )
                                <?php
                                $apel_nom = $trabajador->apel_pat . " " . $trabajador->apel_mat . " - " . $trabajador->nombres;
                                ?>
                                <option value="{{ $trabajador->id }}"><?php echo strtoupper($apel_nom) ?></option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <input id="id-work" type="hidden" />
                    <div class="form-group col-md-6">
                        <label class="mb-1 text-label mr-1">Área</label>
                        <input autocomplete="off" id="area" placeholder="" type="text" class="form-control text-uppercase rounded-0" disabled>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mb-1 text-label mr-1">Tipo trabajador</label>
                        <input autocomplete="off" id="tipo" placeholder="" type="text" class="form-control text-uppercase rounded-0" disabled>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- <label class="raleway-title  h6">Maquinarias e implementos</label> -->
            <div class="card px-3 pt-2 elevation-0 border fs-13">
                <div class="row">
                    <div class="form-group col-md-12 RobotoC">
                        <label class="mb-1 text-label mr-1">Implemento</label><span class="text-danger font-weight-bold">*</span>
                        <div class="fs-14 text-label">
                            <select class="form-control select2" id="slt-implemento">
                                <option value="0" selected>Seleccionar</option>
                                @if( $status == "402" )
                                @foreach( $implementos as $implemento )
                                <option value="{{ $implemento->id }}">{{ $implemento->nombre }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                    </div>

                    <div class="form-group col-md-12 RobotoC">
                        <label class="mb-1 text-label mr-1">Maquinaria</label><span class="text-danger font-weight-bold">*</span><span class="text-danger fs-11" id="error-area"></span><br>
                        <div class="fs-14 text-label">
                            <select class="form-control select2" id="slt-maquinaria">
                                <option value="0" selected>Seleccionar</option>
                                @if( $status == "402" )
                                @foreach( $maquinarias as $maquinaria )
                                <option value="{{ $maquinaria->id }}">{{ $maquinaria->nombre }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>

                    </div>

                </div>
                <div class="row">

                    <!--<div class="form-group col-md-6">
                        <label class="mb-0 ">Rol usuario</label><span class="text-danger font-weight-bold">*</span>
                        <select class="form-control text-input" id="slt-rol" disabled>
                            <option>--- Seleccionar rol ---</option>
                        </select>
                    </div>-->
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
    $("#slt-work").on("change", function() {
        var swork = $(this).val();
        var data = {
            id: swork
        };

        if (swork != 0) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{!! url('api/operario-select') !!}",
                data: data,
            }).done(function(msg) {
                var data = JSON.parse(msg);
                console.log(data.status);
                if (data.status == "402") {
                    $("#id-work").val(data.response['id']);
                    $("#area").val(data.response['area']);
                    $("#tipo").val(data.response['tipo']);
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                // Mostramos en consola el mensaje con el error que se ha producido
                //$("#consola").html("The following error occured: " + textStatus + " " + errorThrown);
            });
        } else {
            $("#area").val("");
            $("#tipo").val("");
        }
    });

    $("#frm-save-operario").on('submit', function(e) {
        e.preventDefault();
        var id_work = $("#id-work").val();
        var swork = $("#slt-work").val();
        var smaquinaria = $("#slt-maquinaria").val();
        var simplemento = $("#slt-implemento").val();

        if (swork != '0') {
            if (simplemento != '0') {
                if (smaquinaria != '0') {
                    var data = {
                        id_w: id_work,
                        id_m: smaquinaria,
                        id_i: simplemento
                    };

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: "{!! url('api/operario-save') !!}",
                        data: data
                    }).done(function(resp) {
                        console.log(resp);
                        if (resp == "402") {
                            listSkill();
                            $("#operario-create-tab").removeClass('active');
                            $("#operario-create-desc").removeClass('active show');

                            $("#operario-list-tab").addClass('active');
                            $("#operario-list-desc").addClass('active show');

                            toastr.success('Habilidad registrada.', 'AIDA', {
                                "positionClass": "toast-bottom-right"
                            });
                            $("#id-work").val("");
                            $("#slt-work").select2('val', "0");
                            $("#area").val("");
                            $("#cargo").val("");
                            $("#slt-maquinaria").select2('val', "0");
                            $("#slt-implemento").select2('val', "0");
                        }

                        if (resp == "502") {
                            toastr.error('Registro ya existe!', 'AIDA', {
                                "positionClass": "toast-top-right"
                            });
                        }

                    })

                } else {
                    toastr.info('Seleccionar <b>Maquinaria!</b>', 'AIDA', {
                        "positionClass": "toast-top-right"
                    });
                    $("#slt-maquinaria").focus();
                }

            } else {
                toastr.info('Seleccionar <b>Implemento!</b>', 'AIDA', {
                    "positionClass": "toast-top-right"
                });
                $("#slt-implemento").focus();
            }

        } else {
            toastr.info('Seleccionar <b>Operario!</b>', 'AIDA', {
                "positionClass": "toast-top-right"
            });
            $("#slt-work").focus();
        }
    });

    $(function() {
        $('.select2').select2({
            width: 'resolve',
            dropdownCssClass: "RobotoC fs-14",
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