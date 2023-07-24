<?php

use Illuminate\Support\Facades\Crypt;
?>
<form id="frm-save-rol-edit">
    <input type="hidden" id="key-edit" value="{{ $id }}" />
    <div class="row">
        <div class="col-md-12">
            <div class="card px-3 pt-2 elevation-0 border">
                <div class="row">
                    <div class="form-group col-md-6 text-secondary">
                        <label class="mb-0">Nombre</label><span class="text-danger font-weight-bold">*</span>
                        <input value="{{ $rol->nombre }}" autocomplete="off" id="nombre-rol-edit" placeholder="" type="text" 
                        class="form-control rounded-0" required>
                    </div>

                </div>
                <div class="form-group">
                    <label class="mb-0 text-secondary">Descripción</label><span class="text-danger font-weight-bold">*</span>
                    <input value="{{ $rol->descripcion }}" autocomplete="off" id="descripcion-rol-edit" 
                        placeholder="" class="form-control rounded-0" required /> 
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
    $("#frm-save-rol-edit").on("submit", function (e) {
        e.preventDefault();

        // Actualizar datos        
        var key = $("#key-edit").val();
        var nombre = $("#nombre-rol-edit").val();
        var descripcion = $("#descripcion-rol-edit").val();
        ///var sarea = $("#slt-area").val();
        ///var scargo = $("#slt-cargo").val();

        //if (sarea != 0) {
        //    if (scargo != 0) {
                var data = {
                    key: key,
                    nombre: nombre,
                    descripcion: descripcion,
                    //sarea: sarea,
                    //scargo: scargo
                };

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: "{!! url('api/rol-edit-save') !!}",
                    data: data,
                }).done(function (msg) {
                    console.log(msg);
                    if (msg == '402') {
                        getListaRoles();

                        $("#rol-edit-tab").removeClass('visible active');
                        $("#rol-edit-desc").removeClass('active show');
                        $("#rol-edit-tab").addClass('invisible');

                        $("#rol-list-tab").addClass('active');
                        $("#rol-list-desc").addClass('active show');

                        toastr.success('Registro actualizado', '',{ "positionClass": "toast-top-right" });
                        $("#nombre").val("");
                        $("#descripcion").val("");
                        $("#slt-area").select2('val', '0');
                        $("#slt-cargo").select2('val', '0');
                    }

                    if (msg == '303') {
                        toastr.error('Error al actualizar.', '',{ "positionClass": "toast-top-right" });
                    }

                }).fail(function (jqXHR, textStatus, errorThrown) {
                    // Mostramos en consola el mensaje con el error que se ha producido
                    $("#consola").html("The following error occured: " + textStatus + " " + errorThrown);
                });
    /*        } else {
                toastr.info('Debe de seleccionar un cargo');
            }
        } else {
            toastr.info('Debe de seleccionar un área');
        }*/
    });
</script>