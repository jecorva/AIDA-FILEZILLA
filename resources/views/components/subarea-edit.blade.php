<form id="editSubareaForm">
    <div class="row">
        <div class="col-md-12">
            <!-- <label class="raleway-title  h6">Datos del trabajador</label> -->
            <div class="card px-3 pt-2 elevation-0 border fs-13">
                <div class="row">
                    <div class="form-group col-md-5">
                        <label class="mb-1 text-label mr-1">Nombre de la sub-área</label><span class="text-danger font-weight-bold">*</span>
                        <input autocomplete="off" value="{{ $name }}" id="new-name" placeholder="Escribir" type="text" class="form-control rounded-0 text-capitalize" required>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="mb-1 text-label">Área</label><span class="text-danger font-weight-bold">*</span>
                            <select id="new-area" class="form-control sourcesansrg select2" required>
                                <option value="">Seleccionar</option>
                                @foreach($areas as $area)
                                <option value="{{ $area->id }}" <?php if( $area->id == $area_id) echo 'selected'; else echo ''; ?> >{{ $area->nombre }}</option>
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
    $("#new-area").select('val', '')

    $("#editSubareaForm").on('submit', async function(e) {
        e.preventDefault();
        let name    = $("#new-name").val();
        let area_id = $("#new-area").val();

        let data = { name: name, area_id: area_id, subarea_id: '{!! $subarea_id !!}' }
        console.log(data)
        const response = await requestManager('/api/subarea-update', data)
        console.log(response)
        if( response.status == 200 ) {            
            $("#subarea-edit-tab").removeClass('active');
            $("#subarea-edit-desc").removeClass('active show');
            $("#subarea-edit-tab").addClass('invisible');

            $("#subarea-list-tab").addClass('active');
            $("#subarea-list-desc").addClass('active show');

            listSubarea();

            toastr.success( response.message, 'AIDA', {
                "positionClass": "toast-top-right"
            });
        }

        if (response == 402) {
            toastr.warning( response.message, '', {
                "positionClass": "toast-top-right"
            });
        }

    });

    async function requestManager(url, data) {
        let uri = "{!! url('') !!}";
        var result = await $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: uri + url,
            type: 'POST',
            data: data,
        })
        return result;
    }

    $(function() {
        $('.select2').select2({
            dropdownCssClass: "opensans fs-13",
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
