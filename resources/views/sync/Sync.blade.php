<?php
$url = "http://127.0.0.1:8000/api/list-fecha-sync";
?>
@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>

@section('title', 'Sincronizar')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Sincronización con NIRISA</span>
    </div>
</div>
@stop
@section('content')
<br>
<div class="alert bg-bluedark RobotoC text-white">
    <i class="fas fa-table mr-2"></i>
</div>


<div id="toolbar">
    <select class="form-control select2" id="slt-semana">
        <option value="0" disabled selected>Seleccionar semana</option>
        @foreach( $semanas as $semana )
        <option value="{{ $semana->nro_semana }}">Semana Nº {{ $semana->nro_semana }}</option>
        @endforeach
    </select>
</div>
<div class="bg-white border-light p-3 rounded position-relative">
    <div id="loading" class="loader"></div>
    <table id="table-sync" 
        class="table-sm"
        data-toggle="bootstrap-table" 
        data-toolbar="#toolbar" 
        data-search="true"
        >
    </table>
</div>
<br>
<div class="bg-white border-light p-3 rounded">
    <table id="table-sync-detail" 
        class="table-striped table-sm" 
        data-toggle="bootstrap-table" 
        data-search="true" 
        data-show-columns="true" 
        data-pagination="true"
        >
    </table>
</div>
<br>
@stop
@section('css')
<style>
    #table tbody {
        font-size: 13px !important;
    }
</style>
@stop

@section('js')
<script>
    var $table = $('#table-sync')
    var $tableDetail = $("#table-sync-detail")
    var data = []
    var nro_semana = ''
    $("#loading").hide();

    $("#slt-semana").on('change', async function() {
        nro_semana = $(this).val()
        let param = {
            'nro_semana': nro_semana
        }
        var data = await myRequest('/api/load-list-sync-semana', param)
        $table.bootstrapTable('refreshOptions', {
            data: data
        })
    })

    $table.on('click-row.bs.table', async function(e, row, $element) {
        let param = {
            'fecha': row.fecha
        }
        // console.log(param)
        var data = await myRequest('/api/load-list-sync', param);
        // console.log(data)

        $tableDetail.bootstrapTable('refreshOptions', {
            data: data,
        })
    });

    $table.bootstrapTable('destroy')
        .bootstrapTable({
            // url: '{!! $url !!}',
            //data: data,
            pagination: true,
            height: 450,
            locale: 'es_ES',
            columns: [{
                    field: 'nro',
                    title: 'Nro',
                    align: 'center',
                    width: 10
                },
                {
                    field: 'dia',
                    title: 'Dia',
                    sortable: true,
                },
                {
                    field: 'fecha',
                    title: 'Fecha',
                    align: 'center',
                },
                {
                    field: 'tareos',
                    title: 'Total Tareos',
                    align: 'center',
                },
                {
                    field: 'status',
                    title: 'Estado',
                    align: 'center',
                    formatter: statusRow,
                    width: 50
                },
                {
                    field: 'operator',
                    title: 'Operación',
                    align: 'center',
                    formatter: operatorRow,
                    width: 50
                }
            ]
        })

    $tableDetail.bootstrapTable('destroy')
        .bootstrapTable({
            //url: '{!! $url !!}',
            data: data,
            pagination: true,
            height: 450,
            locale: 'es_ES',
            columns: [{
                    field: 'id',
                    title: 'ID',
                    align: 'center'
                },
                {
                    field: 'IDTRABAJADOR',
                    title: 'IDTRABAJADOR',
                    sortable: true,
                },
                {
                    field: 'IDACTIVIDADEXTERNO',
                    title: 'IDACTIVIDADEXTERNO',
                    align: 'center',
                },
                {
                    field: 'FECHATAREO',
                    title: 'FECHATAREO',
                    align: 'center',
                },
                {
                    field: 'IDMAQUINARIAEXTERNO',
                    title: 'IDMAQUINARIAEXTERNO',
                    align: 'center',
                },
                {
                    field: 'IDLUGAR_SALIDACAMPO',
                    title: 'IDLUGAR_SALIDACAMPO',
                    align: 'center',
                },
                {
                    field: 'IDLUGAR_ENTREGAPARQUEO',
                    title: 'IDLUGAR_ENTREGAPARQUEO',
                    align: 'center',
                },
                {
                    field: 'HOROMETRO_ENTREGAPARQUEO',
                    title: 'HOROMETRO_ENTREGAPARQUEO',
                    align: 'center',
                },
                {
                    field: 'IDLABOREXTERNO',
                    title: 'IDLABOREXTERNO',
                    align: 'center',
                },
                {
                    field: 'IDIMPLEMENTOEXTERNO',
                    title: 'IDIMPLEMENTOEXTERNO',
                    align: 'center',
                },
                {
                    field: 'INICIO_HOROMETROLABOR',
                    title: 'INICIO_HOROMETROLABOR',
                    align: 'center',
                },
                {
                    field: 'FIN_HOROMETROLABOR',
                    title: 'FIN_HOROMETROLABOR',
                    align: 'center',
                },
                {
                    field: 'IDSUPERVISOR_LABOR',
                    title: 'IDSUPERVISOR_LABOR',
                    align: 'center',
                },
                {
                    field: 'OBSSUPERVISOR_LABOR',
                    title: 'OBSSUPERVISOR_LABOR',
                    align: 'center',
                },
                {
                    field: 'AVANCEDIURNO_PLANIFICADO',
                    title: 'AVANCEDIURNO_PLANIFICADO',
                    align: 'center',
                },
                {
                    field: 'observaciones',
                    title: 'observaciones',
                    align: 'center',
                },
                {
                    field: 'AVANCEDIURNO',
                    title: 'AVANCEDIURNO',
                    align: 'center',
                }


            ]
        })


    
        function statusRow(data) {
        var html = ''
        if (data == 0) html = '<i class="fas fa-circle text-danger"></i>'
        if (data == 1) html = '<i class="fas fa-circle text-success"></i>'

        return html;
    }

    function operatorRow(data) {
        let disabled = data.status == '0' ? '' : 'disabled';

        var html = [
            '<button class="btn btn-success btn-sm" onclick="migrateClick(\'' + data.fecha + '\', \'' + data.status + '\', \'' + data.tareos + '\')">',
            '<i class="fas fa-angle-double-right"></i>',
            '</button>'
        ].join('')

        return html;
    }

    async function migrateClick(fecha, status, tareos) {
        if( tareos != '0' ) {
            if( status == '0' ) {
                let param = {'fecha': fecha}
                Swal.fire({
                    title: 'Continuar con operación?',
                    text: 'Se migrará tareo del día a NISIRA.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, Continuar!',
                    cancelButtonText: 'Cancelar'
                }).then(async (result) => {
                    if (result.isConfirmed) {

                        $("#loading").show();

                        var response = await myRequest('/api/migrate-nisira', param)        
                        console.log(response.response)

                        if( response.status == 200 ) {
                            Toast.fire({
                                icon: 'success',
                                title: 'Migración correcta.',

                            })
                            resetTables()
                            $("#loading").hide()
                        }
                        
                        if( response.status == 202 ) {
                            Toast.fire({
                                icon: 'info',
                                title: 'Migración ya realizada.',

                            })
                            $("#loading").hide()
                        }

                        if( response.status == 402 ) {
                            Toast.fire({
                                icon: 'error',
                                title: 'Conectar BD SQL Server.',

                            })
                            $("#loading").hide()
                        }


                    } else {
                        
                    }
                }) 
            }else {
                Toast.fire({
                    icon: 'warning',
                    title: 'Este proceso ya se realizó.',
                })
            }
        }else {
            Toast.fire({
                icon: 'info',
                title: 'Sin tareos para migrar.',

            })
        }
               
    }

    async function resetTables() {
        param = {'nro_semana': nro_semana }
        var values = await myRequest('/api/load-list-sync-semana', param)
        $table.bootstrapTable('refreshOptions', {
            search: true,
            data: values
        })
        $tableDetail.bootstrapTable('refreshOptions', {
            search: true,
            data: []
        }) 
    }


    ////////////////////////////////////////////////////////

    async function myRequest(url, data) {
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

    $(function() {
        $('.select2').select2({
            width: 'resolve',
            dropdownCssClass: "RobotoC fs-12 text-uppercase",
            language: {
                noResults: function() {
                    return "Sin resultados";
                },
                searching: function() {
                    return "Buscando..";
                }
            }
        });
    })

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    $(function() {
        $('li a').removeClass('active');
        $('.menu-is-opening').removeClass('menu-is-opening');
        $('.menu-open').removeClass('menu-open');
        $('#{!! $_MENU_ITEM !!}').addClass('menu-is-opening');
        $('#{!! $_MENU_ITEM !!}').addClass('menu-open');
        $('#{!! $_MENU_ITEM !!} .title').addClass('active');
        $('#{!! $_SUBMENU_ITEM !!}').addClass('active');
    });
</script>
@stop
