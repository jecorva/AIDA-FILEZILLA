<?php
//////////////////////////////// Tabla Inicial para el detalle del Plan /////////////////////////

$fecha = $dtll_plan->date_inicio;
$fecha = new DateTime($fecha);
$fecha_inicio = $fecha->format('d-m-Y');
$fecha_actual = date($fecha_inicio);
$dia =  date("d-m-Y", strtotime($fecha_actual . "+ 1 days"));
if (isset($dtll_plan->id)) $dp = $dtll_plan->id;
else $dp = "";
?>
<style>
    .modal-open {
        overflow: auto !important;
        padding-right: 0 !important;
    }

</style>
<div id="nueva-labor" class="card elevation-0 border" style="display: none">
    <div class="card-body">

    </div>
</div>

<div class="sourcesans">
    <div id="content-table-labores">

    </div>
</div>

<div class="modal fade" id="mdl-new" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content fs-14">
            <form id="saveLaborForm">
                <div class="modal-header bg-bluedark">
                    <h4 class="text-white m-0 p-0"><i class='fas fa-plus text-white-50'></i> Nueva labor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input id="id_planificacion" value="{{ $dp }}" type="hidden" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group sourcesans">
                                <label class="mb-0 text-secondary fs-14 mr-1">Labor</label><span class="text-danger font-weight-bold">*</span>
                                <select class="form-control text-uppercase select2" id="slt-labor">
                                    <option value="0" selected>Seleccionar</option>
                                    @foreach( $labores as $labor )
                                    <option data-um="{{ $labor->um_id }}" value="{{ $labor->id }}">{{ $labor->code_nisira }} - {{ $labor->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-0 text-secondary fs-14 mr-1">Turno</label><span class="text-danger font-weight-bold">*</span>
                                <select class="form-control sourcesans fs-14" id="slt-turno-plan">
                                    <option value="0" selected>Seleccionar</option>
                                    @foreach( $turnos as $turno )
                                    <option value="{{ $turno->id }}">{{ $turno->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group sourcesans">
                                <label class="mb-0 text-secondary fs-14 mr-1">Unidad Medida</label><span class="text-danger font-weight-bold">*</span>
                                <select class="form-control select2" id="slt-uavance" disabled>
                                    <option value="0" selected>Seleccionar</option>
                                    @foreach( $umedidas as $um )
                                    <option value="{{ $um->id }}">{{ $um->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                    <!-- <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="text-right">
                                <button id="btn-hiden-nuevo" type="button" class="btn btn-outline-secondary sourcesansrg fs-13 m-0 rounded-1">
                                    <i class="fas fa-times mr-1"></i>Cerrar
                                </button>
                                <button type="submit" class="btn btn-bluedark text-white sourcesansrg fs-13 m-0 rounded-1">
                                    <i class="fas fa-plus-circle mr-1"></i></i>Agregar
                                </button>
                            </div>
                        </div>
                    </div> -->

                    <script>
                        $(function() {
                            $('.select2').select2({
                                dropdownCssClass: "sourcesansrg fs-13",
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
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-secondary sourcesansrg fs-13 m-0 rounded-1" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Cerrar
                    </button>
                    <button type="submit" class="btn btn-bluedark  text-white sourcesansrg m-0 rounded-1">
                        <i class="fas fa-plus-circle mr-1"></i></i>Agregar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var loading ="<div class='text-center'><span class='fa-stack fa-lg'>\n\
                    <i class='fa fa-spinner fa-spin fa-stack fa-fw'></i>\n\
                </span>&emsp;Cargando ...</div>"

    $(document).ready(function() {
        $("#slt-labor").on('change', function() {
            var um_id = $('#slt-labor option:selected').attr('data-um');
            $("#slt-uavance").select2("val", um_id);
            console.log(um_id)
        })
        listLaboresPlan();
    })



    $("#saveLaborForm").on("submit", function(e) {
        e.preventDefault();
        var slt_labor = $("#slt-labor").val();
        var slt_turno = $("#slt-turno-plan").val();
        var uavance = $("#slt-uavance").val();
        var id_planificacion = $("#id_planificacion").val();

        if (slt_labor != 0) {
            if (slt_turno != 0) {
                data = {
                    id_labor: slt_labor,
                    id_turno: slt_turno,
                    uavance: uavance,
                    id_planificacion: id_planificacion
                };
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: "{!! url('api/requerimiento-save-labor') !!}",
                    data: data
                }).done(function(resp) {
                    if (resp == "402") {
                        listLaboresPlan();
                        $("#mdl-new").modal("hide");
                        $("#slt-labor").select2('val', '0');
                        $("#slt-turno-plan option[value=0]").prop('selected', true);
                        $("#slt-uavance").select2('val', '0');
                        //$("#nueva-labor").removeClass("d-block");

                        toastr.success('Nueva labor creada.', 'AIDA', {
                            "positionClass": "toast-top-right"
                        });

                    }

                    if (resp == "302") {
                        toastr.error('Error al guardar', 'AIDA', {
                            "positionClass": "toast-top-right"
                        });
                    }
                });

            } else {
                toastr.info('Seleccionar un turno', '', {
                    "positionClass": "toast-top-right"
                });
                $("#slt-turno").focus();
            }

        } else {
            toastr.info('Seleccionar una labor', '', {
                "positionClass": "toast-top-right"
            });
            $("#slt-labor").focus();
        }
    });

    async function listLaboresPlan() {
        $("#content-table-labores").html(loading);
        var id_planificacion = $("#id_planificacion").val();
        var data = {
            id_planificacion: id_planificacion
        };
        await getComponent('/api/requerimiento-list-labor', data, 'content-table-labores')
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

    $("#btn-show-modal").on("click", function() {
        $("#slt-labor").select2('val', '0');
        $("#slt-turno-plan option[value=0]").prop('selected', true);
        $("#slt-uavance").select2('val', '0');
        $("#mdl-new").modal("show");
        // $("#nueva-labor").addClass("d-block");
    });

    $("#btn-hiden-nuevo").on("click", function() {
        $("#nueva-labor").removeClass("d-block");
    });
</script>
