<form id="frm-save-parking">
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
                        <label class="mb-1 text-label mr-1">Nombre</label><span class="text-danger font-weight-bold">*</span>
                        <input autocomplete="off" id="nameParking" placeholder="Escribir" type="text" class="form-control rounded-0 text-capitalize" autofocus required>
                    </div>
                    <div class="form-group col-md-8">
                        <label class="mb-1 text-label mr-1">Referencia</label>
                        <input autocomplete="off" id="referenceParking" placeholder="Escribir" type="text" class="form-control rounded-0">
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
    $("#frm-save-parking").on('submit', function(e) {
        e.preventDefault();
        var name = $("#nameParking").val();
        var reference = $("#referenceParking").val();

        var data = {
            name: name.toUpperCase(),
            reference: reference,
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/parking-save') !!}",
            data: data
        }).done(function(resp) {
            console.log(resp);
            if (resp == "402") {
                listParking();
                $("#parking-create-tab").removeClass('active');
                $("#parking-create-desc").removeClass('active show');

                $("#parking-list-tab").addClass('active');
                $("#parking-list-desc").addClass('active show');

                toastr.success('Registrado correctamente.', '', {
                    "positionClass": "toast-bottom-right"
                });                
            }

            if (resp == "502") {
                toastr.warning('No se pudo crear el registro.', '', {
                    "positionClass": "toast-bottom-right"
                });
            }

        })
    });

    $(function() {
        $('.select2').select2({
            width: 'resolve',
            dropdownCssClass: "text-input"
        });
    });
</script>