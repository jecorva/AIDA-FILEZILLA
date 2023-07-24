<?php

?>
<form id="frm-save-maquinaria-edit">
    <input type="hidden" id="key-edit" value="{{ $id }}" />
    <div class="row">
        <div class="col-md-12">
            <div class="card px-3 pt-2 elevation-0 border">
                <div class="row">
                    <div class="form-group col-md-3 text-secondary">
                        <label class="mb-0">Código dimensión 5</label>
                        <input value="{{ $maquinaria->code_nisira }}" autocomplete="off" id="nombre-maquinaria-edit" placeholder="" type="text" class="form-control rounded-0" disabled>
                    </div>
                    <div class="form-group  col-md-6">
                        <label class="mb-0 text-secondary">Descripción dimensión 5</label>
                        <input value="{{ $maquinaria->nombre }}" autocomplete="off" id="descripcion-maquinaria-edit" placeholder="" class="form-control rounded-0" disabled/>
                    </div>
                    <div class="form-group  col-md-3">
                        <label class="mb-0 text-secondary font-weight-bold">Código Abby</label><span class="text-danger font-weight-bold">*</span>
                        <input autocomplete="off" value="{{ $maquinaria->code_abby }}" id="abby-maquinaria-edit" placeholder="{{ $maquinaria->code_abby }}" class="form-control rounded-0" required autofocus/>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="form-group  col-md-3">
                        <label class="mb-0 text-secondary fs-14 mr-1">Categoría Maquinaria</label><span class="text-danger font-weight-bold">*</span>
                        <select class="form-control select2" id="slt-categoria">
                            <option value="0">Seleccionar</option>
                            @foreach( $categorias as $categoria )
                            <option value="{{ $categoria->id }}" <?php if( $categoria->id == $maquinaria->cat_maquinaria_id ) echo 'selected'; ?>>{{ $categoria->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
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
    $("#frm-save-maquinaria-edit").on("submit", function(e) {
        e.preventDefault();

        // Actualizar datos        
        var key = $("#key-edit").val();        
        var _cod_abby = $("#abby-maquinaria-edit").val();
        var categoria_id = $("#slt-categoria").val();

        var data = {
            key: key,
            cod_abby: _cod_abby,
            categoria_id: categoria_id
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/machinerie-update') !!}",
            data: data,
        }).done(function(msg) {
            console.log(msg);
            if (msg == '402') {
                // listImplement();

                /*$("#maquinaria-edit-tab").removeClass('visible active');
                $("#maquinaria-edit-desc").removeClass('active show');
                $("#maquinaria-edit-tab").addClass('invisible');

                $("#maquinaria-list-tab").addClass('active');
                $("#maquinaria-list-desc").addClass('active show'); */

                toastr.success('Actualización pendiente.', 'AIDA', {
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