<?php

?>
<form id="frm-save-location-edit">
    <input type="hidden" id="key-edit" value="{{ $id }}" />
    <div class="row">
        <div class="col-md-12">
            <div class="card px-3 pt-2 elevation-0 border">
                <div class="row">
                    <div class="form-group col-md-3 text-secondary">
                        <label class="mb-0">Código dimensión 5</label>
                        <input value="{{ $location->dim5 }}" autocomplete="off" id="nombre-location-edit" placeholder="" type="text" class="form-control rounded-0" disabled>
                    </div>
                    <div class="form-group  col-md-6">
                        <label class="mb-0 text-secondary">Descripción dimensión 5</label>
                        <input value="{{ $location->nombre }}" autocomplete="off" id="descripcion-location-edit" placeholder="" class="form-control rounded-0" disabled/>
                    </div>
                    <div class="form-group  col-md-3">
                        <label class="mb-0 text-secondary">Ratio</label><span class="text-danger font-weight-bold">*</span>
                        <input autocomplete="off" id="ratio-location-edit" placeholder="{{ $location->ratio }}" class="form-control rounded-0" required autofocus/>
                    </div>
                </div>

                <!--<div class="row">
                    <div class="form-group col-md-6">
                        <label class="mb-0 text-secondary">Área (predeterminada)</label><span class="text-danger font-weight-bold">*</span><span class="text-danger fs-11" id="error-area"></span><br>
                        <select class="form-control  select2" id="slt-area" required>                            
                            <option value="0" selected>--- Seleccionar area ---</option>                            
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label class="mb-0 text-secondary">Cargo (predeterminada)</label><span class="text-danger font-weight-bold">*</span><span class="text-danger fs-11" id="error-area"></span><br>
                        <select class="form-control  select2" id="slt-cargo" required>
                            <option value="0" selected>--- Seleccionar cargo ---</option>                            
                        </select>
                    </div>
                </div> -->
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="text-right">
                <button type="submit" class="btn btn-bluedark text-white sourcesansrg fs-13 m-0 rounded-1">
                    <i class="fas fa-save mr-1"></i></i>Guardar
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    $("#frm-save-location-edit").on("submit", function(e) {
        e.preventDefault();

        // Actualizar datos        
        var key = $("#key-edit").val();        
        var ratio = $("#ratio-location-edit").val();        

        var data = {
            key: key,
            ratio: ratio,        
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/location-update') !!}",
            data: data,
        }).done(function(msg) {
            console.log(msg);
            if (msg == '402') {
                loadListLocation();

                $("#location-edit-tab").removeClass('visible active');
                $("#location-edit-desc").removeClass('active show');
                $("#location-edit-tab").addClass('invisible');

                $("#location-list-tab").addClass('active');
                $("#location-list-desc").addClass('active show');

                toastr.success('Registro actualizado', '', {
                    "positionClass": "toast-top-right"
                });                
            }

            if (msg == '303') {
                toastr.error('Error al actualizar.', '', {
                    "positionClass": "toast-top-right"
                });
            }

        })
    });
</script>