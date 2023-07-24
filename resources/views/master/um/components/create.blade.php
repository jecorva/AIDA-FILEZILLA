<form id="frm-save-unitm">
    <div class="row">
        <div class="col-md-12">
            <!-- <label class="raleway-title  h6">Datos del trabajador</label> -->
            <div class="card px-3 pt-2 elevation-0 border fs-13">
                <div class="row">
                    <div class="col-md-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="chbActive" disabled checked>
                            <label class="custom-control-label fs-14 align-middle mb-3 text-success" for="chbActive">Activo</label>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="mb-1 text-label mr-1">Siglas</label><span class="text-danger font-weight-bold">*</span>
                        <input autocomplete="off" id="siglasUnitm" placeholder="Escribir" type="text" maxlength="3" class="form-control rounded-0 text-capitalize" autofocus required>
                    </div>
                    <div class="form-group col-md-8">
                        <label class="mb-1 text-label mr-1">Descripcion</label>
                        <input autocomplete="off" id="descUnitm" placeholder="Escribir" type="text" class="form-control rounded-0">
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
    $("#frm-save-unitm").on('submit', function(e) {
        e.preventDefault();
        var siglas = $("#siglasUnitm").val();
        var descripcion = $("#descUnitm").val();

        var data = {
            siglas: siglas.toUpperCase(),
            descripcion: descripcion,
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/unitm-save') !!}",
            data: data
        }).done(function(resp) {
            console.log(resp);
            if (resp == "402") {
                listUnitm();
                $("#unitm-create-tab").removeClass('active');
                $("#unitm-create-desc").removeClass('active show');

                $("#unitm-list-tab").addClass('active');
                $("#unitm-list-desc").addClass('active show');

                toastr.success('Registro agregado.', 'AIDA', {
                    "positionClass": "toast-top-right"
                });                
            }

            if (resp == "303") {
                toastr.warning('No se pudo crear el registro.', '', {
                    "positionClass": "toast-top-right"
                });
            }

        })
    });
    
</script>