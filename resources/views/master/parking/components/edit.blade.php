<?php
 $chedked = $parking->flag == '1' ? 'checked' : '';
?>
<form id="frm-save-parking-edit">
    <input type="hidden" id="key-edit" value="{{ $id }}" />
    <div class="row">
        <div class="col-md-12">
            <div class="card px-3 pt-2 elevation-0 border">
                <div class="row">
                    <div class="col-md-12">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="form-check-input" id="chbActiveEdit" <?php echo $chedked ?>>
                            <label class="form-check-label fs-14 align-middle mb-3 text-success" for="chbActive">Activo</label>
                        </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label class="mb-1 text-label mr-1">Nombre</label><span class="text-danger font-weight-bold">*</span>
                        <input value="{{ $parking->nombre }}" autocomplete="off" id="nameParkingEdit" placeholder="Escribir" type="text" class="form-control rounded-0 text-capitalize" autofocus required>
                    </div>
                    <div class="form-group col-md-8">
                        <label class="mb-1 text-label mr-1">Referencia</label>
                        <input value="{{ $parking->referencia }}" autocomplete="off" id="referenceParkingEdit" placeholder="Escribir" type="text" class="form-control rounded-0" required>
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
    $("#frm-save-parking-edit").on("submit", function(e) {
        e.preventDefault();

        // Actualizar datos        
        var key = $("#key-edit").val();        
        var name = $("#nameParkingEdit").val();
        var reference = $("#referenceParkingEdit").val();
        var checked = '';
        if( $("#chbActiveEdit").is(':checked') ) {
            checked = '1';
        }else {
            checked = '0';
        }

        var data = {
            key: key,
            name: name,
            reference: reference,
            checked: checked
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/parking-update') !!}",
            data: data,
        }).done(function(msg) {
            console.log(msg);
            if (msg == '402') {
                listParking();

                $("#parking-edit-tab").removeClass('visible active');
                $("#parking-edit-desc").removeClass('active show');
                $("#parking-edit-tab").addClass('invisible');

                $("#parking-list-tab").addClass('active');
                $("#parking-list-desc").addClass('active show');

                toastr.success('Registro actualizado.', 'AIDA', {
                    "positionClass": "toast-top-right"
                });                
            }

            if (msg == '303') {
                toastr.error('Error al actualizar.', 'AIDA', {
                    "positionClass": "toast-top-right"
                });
            }

        })
    });
</script>