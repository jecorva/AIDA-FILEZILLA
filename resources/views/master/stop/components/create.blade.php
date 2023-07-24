<form id="frm-save-stop">
    <div class="row">
        <div class="col-md-6">
            <!-- <label class="raleway-title  h6">Datos del trabajador</label> -->
            <div class="card px-3 pt-2 elevation-0 border fs-13">
                <div class="row">
                    <div class="col-md-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="chbActive" disabled checked>
                            <label class="custom-control-label fs-14 align-middle mb-3 text-success" for="chbActive">Activo</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="mb-1 text-label mr-1">Código</label><span class="text-danger font-weight-bold">*</span>
                        <input autocomplete="off" id="codStop" placeholder="Escribir" type="text" class="form-control rounded-0 text-capitalize" autofocus required>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="mb-1 text-label mr-1">Descripción</label><span class="text-danger font-weight-bold">*</span>
                        <input autocomplete="off" id="descriptionStop" placeholder="Escribir" type="text" class="form-control rounded-0 text-capitalize" autofocus required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- <label class="raleway-title  h6">Datos del trabajador</label> -->
            <div class="card px-3 pt-2 elevation-0 border fs-13">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="mb-1 text-label">Categoría de Parada</label><span class="text-danger font-weight-bold">*</span>
                            <select id="categorySlt" class="form-control sourcesansrg" required>
                                <option disabled selected value="">Seleccionar</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->descripcion }}</option>                                
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
    $("#frm-save-stop").on('submit', function(e) {
        e.preventDefault();
        var code = $("#codStop").val();
        var description = $("#descriptionStop").val(); 
        var catstop_ID = $("#categorySlt").val();

        var data = {
            codigo: code.toUpperCase(),
            description: description,
            category: catstop_ID,
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/stop-save') !!}",
            data: data
        }).done(function(resp) {
            console.log(resp);
            if (resp == "402") {
                listStop();
                $("#stop-create-tab").removeClass('active');
                $("#stop-create-desc").removeClass('active show');

                $("#stop-list-tab").addClass('active');
                $("#stop-list-desc").addClass('active show');

                toastr.success('Registrado correctamente.', '', {
                    "positionClass": "toast-bottom-right"
                });
            }

            if (resp == "202") {
                $("#codStop").focus();
                toastr.warning('Código registrado.', '', {
                    "positionClass": "toast-bottom-right"
                });                
            }

            if (resp == "303") {
                toastr.danger('Error al registrar en BD.', '', {
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