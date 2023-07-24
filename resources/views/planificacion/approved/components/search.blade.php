<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <label class="mb-0 text-secondary fs-14 mr-1">Año actual</label><span class="text-danger font-weight-bold">*</span>
            <input id="anio" value="{{ $anio }}" class="form-control text-uppercase" required disabled />
        </div>
        <div class="col-md-3">
            <label class="mb-0 text-secondary fs-14 mr-1">Nº de Semana</label><span class="text-danger font-weight-bold">*</span>
            <select class="form-control select2" id="slt-semana">
                <option value="0" disabled selected>Seleccionar</option>
                @foreach( $semanas as $semana )
                <option value="{{ $semana->nro_semana }}">Semana Nº {{ $semana->nro_semana }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="div-table" class="font-weight-normal">
                <table
                    id="table"
                    class="table-sm"
                    data-toggle="bootstrap-table"
                    data-search="true"
                    >
                    <!-- <thead class="" style="background-color:#e2e2e2;"></thead> -->
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade show" id="modal-detalle" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-3">
                <h5 class="mb-0"><i class="fas fa-info mr-2"></i> Detalle Solicitud</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div id="modal-body" class="modal-body">

            </div>

        </div>

    </div>

</div>
<!-- End Modal -->
<style>
    #table tbody {
        font-size: 14px !important;
    }
    #table thead tr th {
        font-weight: 500 !important;
    }

    .fixed-table-header {
        margin-right: 17px !important;
    }

    [data-field="nro"] .th-inner { width: 28px; }
    [data-field="dia"] .th-inner { width: 70px; }


</style>
<script>
    $("#slt-semana").on('change', async function() {
        var anio = $("#anio").val();
        var nro_semana = $("#slt-semana").val();

        $table.bootstrapTable('showLoading')
        var data = {
            anio: anio,
            nro_semana: nro_semana,
        };
        const response = await httpRequest('/api/change-approved-list-js', data)
        $table.bootstrapTable('refreshOptions', {
                    search: true,
                    data: response
                })
        //await listApproved(anio, nro_semana);
    })

    var $table = $('#table');

    $table.bootstrapTable('destroy')
        .bootstrapTable({
            data: [],
            pagination: true,
            height: 420,
            locale:'es_ES',
            columns: [
                {
                    field: 'nro',
                    title: 'N°',
                    align: 'center',
                    formatter: item,
                },
                {
                    field: 'dia',
                    title: 'Fecha',
                    align: 'center',
                    //width: 50,
                    //formatter: diaRow
                },
                // {
                //     field: 'labor',
                //     title: 'Nombre de labor',
                //     sortable: true,
                //     //width: 550,
                //     //widthUnit: 'px'
                // },
                {
                    field: 'supervisor',
                    title: 'Supervisor - Requerimiento',
                    sortable: true,
                },
                {
                    field: 'implemento',
                    title: 'Implemento',
                    //formatter: selectImplement,

                },
                {
                    field: 'maquinaria',
                    title: 'Maquinaria',
                    //formatter: selectMaquinaria,

                },
                {
                    field: 'changes',
                    title: 'Estado',
                    align: 'center',
                    formatter: changesState
                },
                {
                    field: 'operacion',
                    title: 'Operación',
                    formatter: selectOperacion,
                    align: 'right'
                }
            ]
        });

    function item(value, row, index) {
        return index +1;
    }

    function changesState(data) {
        let html = ''
        let color = ''
        let title = ''

        if( data == 2 ) { color = 'text-warning'; title = 'Pendiente'; }
        if( data == 1 ) { color = 'text-success'; title = 'Aprobado'; }

        html = [
            '<div class="px-1 border"><i class="fas fa-circle '+color+' fs-11"></i> <small class="'+color+'">'+title+'</small></div>'
        ].join('');

        return html;
    }

    function selectOperacion(data) {
        let html = '';
        let disabled = ''
        let colorApro = 'text-success'
        let colorRech = 'text-danger'
        if( data.changes != '2' ) {
            disabled ='disabled'
            colorApro = 'text-secondary'
            colorRech = 'text-secondary'
        }

        html = [
            '<div id="btns" class="btn-group" role="group">',
                    '<button type="button" class="btn btn-sm btn-default btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">',
                        '<i class="fas fa-list"></i>',
                    '</button>',
                    '<div class="dropdown-menu">',
                        '<a class="dropdown-item" onclick="openModalConsulta(\''+data.rdt_id_sol+'\', \''+data.rdt_id+'\', \''+data.changes+'\')" href="#"><i class="fas fa-eye text-primary"></i> Consultar</a>',
                        '<a class="dropdown-item '+ disabled +'" onclick="onclickAprobar(\''+data.rdt_id_sol+'\', \''+data.rdt_id+'\')" href="#"><i class="fas fa-check-square '+ colorApro +'"></i>  Aprobar</a>',
                        '<a class="dropdown-item '+ disabled +'" onclick="" href="#"><i class="fas fa-window-close '+ colorRech +'"></i>  Rechazar</a>',
                    '</div>',
                '</div>'
        ].join('');

        return html;
    }

    async function openModalConsulta(rdt_id_sol, rdt_id, changes) {
        $("#btns *").prop('disabled', true);
        var data = { rdt_id: rdt_id_sol, changes: changes }
        await getComponent('/api/change-modal-body', data, 'modal-body')
        $("#btns *").prop('disabled', false);

        $("#modal-detalle").modal('show')
    }

    function onclickAprobar(rdt_id_sol, rdt_id) {
        Swal.fire({
            title: 'Aprobar solicitud?',
            text: "Si está seguro de aprobar, eliga [Si, aprobar!]",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, aprobar!',
            cancelButtonText: 'No'
        }).then( async (result) => {
            if (result.isConfirmed) {
                var anio = $("#anio").val();
                var nro_semana = $("#slt-semana").val();

                var data = {
                    id_new: rdt_id_sol,
                    id_old: rdt_id,
                };

                const msg = await httpRequest('/api/change-request-save', data)
                console.log(msg);
                if (msg == "402") {
                    $table.bootstrapTable('showLoading')
                    data = {
                        anio: anio,
                        nro_semana: nro_semana,
                    };
                    const response = await httpRequest('/api/change-approved-list-js', data)
                    $table.bootstrapTable('refreshOptions', {
                        search: true,
                        data: response
                    })
                    toastr.success('Requerimiento aprobado.', 'AIDA', {
                        "positionClass": "toast-top-right"
                    });
                }
                if (msg == "300") {
                    toastr.error('Error al guardar.', 'AIDA', {
                        "positionClass": "toast-top-right"
                    });
                }
            }
        });
    }


    ///////////////////////////////////////////////

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

    async function getComponent(url, data, id) {
        let uri = "{!! url('') !!}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: uri + url,
            type: 'POST',
            data: data,
        }).done(function(component) {
            $("#" + id).html(component)
        })
    }

    async function httpRequest(url, data) {
        let uri = "{!! url('') !!}";
        var result = await $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: uri + url,
            type: 'POST',
            data: data,
        })
        return result
    }
</script>
