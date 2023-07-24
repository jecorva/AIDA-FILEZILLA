<?php
$chedked = $stop->flag == '1' ? 'checked' : '';
?>
<form id="frm-save-stop-edit">
    <input type="hidden" id="key-edit" value="{{ $id }}" />
    <div class="row">
        <div class="col-md-6">
            <!-- <label class="raleway-title  h6">Datos del trabajador</label> -->
            <div class="card px-3 pt-2 elevation-0 border fs-13">
                <div class="row">
                    <div class="col-md-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="form-check-input" id="chbActiveEdit" <?php echo $stop->flag == '1' ? 'checked' : '' ?>>
                            <label class="form-check-label fs-14 align-middle mb-3 text-success" for="chbActiveEdit">Activo</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="mb-1 text-label mr-1">Código</label>
                        <input value="{{ $stop->code }}" autocomplete="off" id="codStopEdit" placeholder="Escribir" type="text" class="form-control rounded-0 text-capitalize" disabled required>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="mb-1 text-label mr-1">Descripción</label><span class="text-danger font-weight-bold">*</span>
                        <input value="{{ $stop->descripcion }}" autocomplete="off" id="descriptionStopEdit" placeholder="Escribir" type="text" class="form-control rounded-0 text-capitalize" autofocus required>
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
                            <select id="categorySltEdit" class="form-control sourcesansrg" required>
                                <option disabled value="">Seleccionar</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" <?php echo $category->id == $stop->catstop_id ? 'selected' : '' ?> >{{ $category->descripcion }}</option>
                                @endforeach
                            </select>
                        </div>
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
    $("#frm-save-stop-edit").on("submit", function(e) {
        e.preventDefault();

        // Actualizar datos        
        var key = $("#key-edit").val();
        var codigo = $("#codStopEdit").val();
        var descripcion = $("#descriptionStopEdit").val();
        var category = $("#categorySltEdit").val();
        var checked = '';
        if ($("#chbActiveEdit").is(':checked')) {
            checked = '1';
        } else {
            checked = '0';
        }

        var data = {
            key: key,
            codigo: codigo,
            descripcion: descripcion,
            category: category,
            checked: checked
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/stop-update') !!}",
            data: data,
        }).done(function(msg) {
            console.log(msg);
            if (msg == '402') {
                listStop();

                $("#stop-edit-tab").removeClass('visible active');
                $("#stop-edit-desc").removeClass('active show');
                $("#stop-edit-tab").addClass('invisible');

                $("#stop-list-tab").addClass('active');
                $("#stop-list-desc").addClass('active show');

                toastr.success('Registro actualizado.', '', {
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