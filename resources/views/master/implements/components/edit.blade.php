<?php

?>
<form id="frm-save-implemento-edit">
    <input type="hidden" id="key-edit" value="{{ $id }}" />
    <div class="row">
        <div class="col-md-12">
            <div class="card px-3 pt-2 elevation-0 border">
                <div class="row">
                    <div class="form-group col-md-3 text-secondary">
                        <label class="mb-0">Código dimensión 5</label>
                        <input value="{{ $implemento->code_nisira }}" autocomplete="off" id="nombre-implemento-edit" placeholder="" type="text" class="form-control rounded-0" disabled>
                    </div>
                    <div class="form-group  col-md-6">
                        <label class="mb-0 text-secondary">Descripción dimensión 5</label>
                        <input value="{{ $implemento->nombre }}" autocomplete="off" id="descripcion-implemento-edit" placeholder="" class="form-control rounded-0" disabled/>
                    </div>
                    <div class="form-group  col-md-3">
                        <label class="mb-0 text-secondary font-weight-bold">Código Abby</label><span class="text-danger font-weight-bold">*</span>
                        <input autocomplete="off" value="{{$implemento->code_abby}}" id="abby-implemento-edit" placeholder="{{ $implemento->code_abby }}" class="form-control rounded-0" required autofocus/>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group  col-md-3">
                        <label class="mb-0 text-secondary fs-14 mr-1">Categoría Implemento</label><span class="text-danger font-weight-bold">*</span>
                        <select class="form-control select2" id="slt-categoria">
                            <option value="0">Seleccionar</option>
                            @foreach( $categorias as $categoria )
                            <option value="{{ $categoria->id }}" <?php if( $categoria->id == $implemento->cat_implemento_id ) echo 'selected'; ?>>{{ $categoria->nombre }}</option>
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
    $("#frm-save-implemento-edit").on("submit", function(e) {
        e.preventDefault();

        // Actualizar datos        
        var key = $("#key-edit").val();        
        var _cod_abby = $("#abby-implemento-edit").val();
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
            url: "{!! url('api/implemento-update') !!}",
            data: data,
        }).done(function(msg) {
            console.log(msg);
            if (msg == '402') {
                listImplement();

                $("#implemento-edit-tab").removeClass('visible active');
                $("#implemento-edit-desc").removeClass('active show');
                $("#implemento-edit-tab").addClass('invisible');

                $("#implemento-list-tab").addClass('active');
                $("#implemento-list-desc").addClass('active show');

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