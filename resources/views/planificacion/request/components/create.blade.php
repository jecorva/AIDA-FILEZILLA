<?php
///////////// Tabla principal sin registros ////////////
//////////// Select para elegir al supervisor //////////

$disabled = "";

foreach($supervisores as $supervisor) {
    //$disabled = $supervisor->user_id == $id_user ? 'disabled' : '';
    if( $supervisor->user_id == $id_user ) $disabled = 'disabled';
}

?>
<form id="frm-save-requerimiento">
    <div class="row">
        <div class="col-md-12">
            <div class="card px-3 pt-2 elevation-0 border fs-13">
                <div class="row">
                    <div class="form-group col-md-6 sourcesans">
                        <label class="mb-1 text-label mr-1">Planificador</label><span class="text-danger font-weight-bold">*</span><span class="text-danger fs-11" id="error-area"></span><br>
                        <div class="fs-14 text-label">
                            <select class="form-control select2 w-100" id="slt-employee" {{ $disabled }}>
                                <option value="0" selected>Seleccionar</option>
                                @foreach( $supervisores as $supervisor )
                                <?php
                                $selected = $supervisor->user_id == $id_user ? 'selected' : '';
                                $apel_pat = $supervisor->apel_pat . " " . $supervisor->apel_mat . "-" . $supervisor->nombres;
                                $name = strtoupper($apel_pat);
                                ?>
                                <option value="{{ $supervisor->person_id }}" {{ $selected }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" id="slt-change" />
                        </div>
                    </div>

                    <div class="form-group col-md-3">
                        <label class="mb-1 text-label mr-1 ">Fecha Inicio [Lunes]</label><span class="text-danger font-weight-bold">*</span><span class="text-danger fs-11" id="error-area"></span><br>
                        <input type="date" class="form-control opensans fs-14" autocomplete="off" id="finicio" placeholder="" onblur="obtenerfechafinf1();" onChange="sinDomingos();" type="text" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label class="mb-1 text-label mr-1">Fecha Fin [Domingo]</label><span class="text-danger font-weight-bold">*</span><span class="text-danger fs-11" id="error-area"></span><br>
                        <input autocomplete="off" id="ffin" placeholder="" type="date" class="form-control opensans fs-14" disabled>
                    </div>
                    <div class="form-group col-md-9">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="mb-1 text-label mr-1">Nro. Semana</label><span class="text-danger font-weight-bold">*</span><span class="text-danger fs-11" id="error-area"></span><br>
                        <input autocomplete="off" id="nsemana" placeholder="" type="text" class="form-control text-uppercase opensans fs-14" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="text-right">
                <button type="submit" id="elSubmit" class="btn btn-bluedark text-white sourcesansrg fs-13 m-0 rounded-1">
                    <i class="fas fa-plus-circle mr-1"></i></i>Generar
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    $("#frm-save-requerimiento").on('submit', function(e) {
        e.preventDefault();
        $("#elSubmit").prop('disabled', true)
        var slt_employee = $("#slt-employee").val();
        // var slt_turno = $("#slt-turno").val();
        var finicio = $("#finicio").val();
        var ffin    = $("#ffin").val();
        var nsemana = $("#nsemana").val();
        var person  = $('#slt-change').val();

        if (slt_employee != 0) {

            var data = {
                swork: slt_employee,
                // sturno: slt_turno,
                finicio: finicio,
                ffin: ffin,
                nsemana: nsemana,
                person: person != '' ? person : slt_employee
            };

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{!! url('api/requerimiento-save') !!}",
                data: data
            }).done(function(resp) {
                console.log(resp);
                if (resp == "402") {
                    //getListOperario();
                    $("#requerimiento-create-tab").removeClass('active');
                    $("#requerimiento-create-desc").removeClass('active show');

                    $("#requerimiento-list-tab").addClass('active');
                    $("#requerimiento-list-desc").addClass('active show');

                    toastr.success('Requerimiento registrado.', 'AIDA', {
                        "positionClass": "toast-top-right"
                    });
                    $("#slt-employee").select2('val', "0");
                    $("#slt-turno").select2('val', "0");
                    $("#finicio").val("");
                    $("#ffin").val("");
                    $("#nsemana").val("");
                    $("#elSubmit").prop('disabled', false)
                }

                if (resp == "302") {
                    toastr.error('Error al guardar', 'AIDA', {
                        "positionClass": "toast-top-right"
                    });
                    $("#elSubmit").prop('disabled', false)
                }

                if (resp == "502") {
                    toastr.error('Semana de planificación <b>Existe</b>', 'AIDA', {
                        "positionClass": "toast-top-right"
                    });
                    $("#elSubmit").prop('disabled', false)
                }
            });

        } else {
            toastr.info('Seleccionar <b>Supervisor!</b>', 'AIDA', {
                "positionClass": "toast-top-right"
            });
            $("#slt-employee").focus();
        }
    });

    $("#finicio").on('change', function() {

    });

    $('#slt-employee').on('change', function() {
        $('#slt-change').val($(this).val());
    })

    function seleccionardia() {
        var fecha = new Date($("#finicio").val());
        var dias = 7; // Número de días a agregar
        fecha.setDate(fecha.getDate() + dias);
        var ffin = moment(fecha).format('YYYY-MM-DD');
        console.log(ffin);
        $("#ffin").val(ffin);
        fecha = moment(fecha).format('DD/MM/YYYY');
        var nsem = semanadelano(fecha);
        $("#nsemana").val(nsem);
    }

    function semanadelano($fecha) {
        $const = [2, 1, 7, 6, 5, 4, 3];

        if ($fecha.match(/\//)) {
            $fecha = $fecha.replace(/\//g, "-", $fecha);
        };

        $fecha = $fecha.split("-");

        $dia = eval($fecha[0]);
        $mes = eval($fecha[1]);
        $ano = eval($fecha[2]);
        if ($mes != 0) {
            $mes--;
        };

        $dia_pri = new Date($ano, 0, 1);
        $dia_pri = $dia_pri.getDay();
        $dia_pri = eval($const[$dia_pri]);
        $tiempo0 = new Date($ano, 0, $dia_pri);
        $dia = ($dia + $dia_pri);
        $tiempo1 = new Date($ano, $mes, $dia);
        $lapso = ($tiempo1 - $tiempo0)
        $semanas = Math.floor($lapso / 1000 / 60 / 60 / 24 / 7);

        if ($dia_pri == 1) {
            $semanas++;
        };

        if ($semanas == 0) {
            $semanas = 52;
            $ano--;
        };

        if ($ano < 10) {
            $ano = '0' + $ano;
        };

        // alert($semanas+" - "+$ano);
        return $semanas;
    };


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



    var elDate = document.getElementById('finicio');
    var elForm = document.getElementById('frm-save-requerimiento');
    var elSubmit = document.getElementById('elSubmit');

    function sinDomingos() {
        var day = new Date(elDate.value).getUTCDay();
        // Días 0-6, 0 es Domingo 6 es Sábado
        elDate.setCustomValidity(''); // limpiarlo para evitar pisar el fecha inválida
        if (day == 0 || day == 2 || day == 3 || day == 4 || day == 5 || day == 6) {
            elDate.setCustomValidity('Por favor seleccione día Lunes');
        } else {
            elDate.setCustomValidity('');
            seleccionardia();
        }
        if (!elForm.checkValidity()) {
            elSubmit.click()
        };
    }

    function obtenerfechafinf1() {
        sinDomingos();
    }
</script>
