<?php

?>
<form id="frm-save-task-edit">
    <input type="hidden" id="key-edit" value="{{ $id }}" />
    <div class="row">
        <div class="col-md-6">
            <!-- <label class="raleway-title  h6">Datos del trabajador</label> -->
            <div class="card px-3 pt-2 elevation-0 border fs-13">
                <div class="row">                    
                    <div class="form-group col-md-12">
                        <label class="mb-1 text-label mr-1">Código NISIRA</label>
                        <input value="{{ $task->code_nisira }}" autocomplete="off" id="nisiraTaskEdit" placeholder="Escribir" type="text" class="form-control rounded-0 text-capitalize" disabled required>
                    </div>
                    <div class="form-group col-md-12">
                        <label class="mb-1 text-label mr-1">Nombre Labor</label><span class="text-danger font-weight-bold">*</span>
                        <input value="{{ $task->nombre }}" autocomplete="off" id="nombreTaskEdit" placeholder="Escribir" type="text" class="form-control rounded-0 text-capitalize" disabled required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!-- <label class="raleway-title  h6">Datos del trabajador</label> -->
            <div class="card px-3 pt-2 elevation-0 border fs-13">
                <div class="row">
                    <div class="form-group col-md-12">
                        <label class="mb-1 text-label mr-1">Ratio</label><span class="text-danger font-weight-bold">*</span>
                        <input value="{{ $task->ratio }}" 
                            onKeypress="if (event.keyCode < 46 || event.keyCode > 57) event.returnValue = false;"
                            autocomplete="off" id="ratioTaskEdit" placeholder="Escribir" type="text" class="form-control rounded-0 text-capitalize" required>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="mb-1 text-label">Unidad de Medida</label><span class="text-danger font-weight-bold">*</span>
                            <select id="umSltEdit" class="form-control sourcesansrg select2" required>
                                <option disabled value="">Seleccionar</option>
                                @foreach($unit_measures as $unit)
                                <option value="{{ $unit->id }}" <?php echo $unit->id == $task->um_id ? 'selected' : '' ?> >{{ $unit->siglas }} - {{ $unit->nombre }}</option>
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
    $("#frm-save-task-edit").on("submit", function(e) {
        e.preventDefault();

        // Actualizar datos        
        var key = $("#key-edit").val();
        var ratio = $("#ratioTaskEdit").val();
        var um = $("#umSltEdit").val();        
             

        var data = {
            key: key,
            ratio: ratio,
            um: um,
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/tasks-update') !!}",
            data: data,
        }).done(function(msg) {
            console.log(msg);
            if (msg == '402') {
                listLabors();

                $("#labores-edit-tab").removeClass('visible active');
                $("#labores-edit-desc").removeClass('active show');
                $("#labores-edit-tab").addClass('invisible');

                $("#labores-list-tab").addClass('active');
                $("#labores-list-desc").addClass('active show');

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

    $(function() {
        $('.select2').select2({
            width: 'resolve',
            dropdownCssClass: "RobotoC fs-15",
            language: {
                noResults: function() {
                    return "Sin resultados";
                },
                searching: function() {
                    return "Buscando..";
                }
            }
        });
    });
</script>