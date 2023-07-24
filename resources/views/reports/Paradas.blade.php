<?php
$url = "http://127.0.0.1:8000/api/paradas-list";

?>
@extends('adminlte::page')

@section('title', 'Paradas')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Reportes - Reporte de paradas</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br><br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="user-tab" role="tablist">
        <a class="nav-item nav-link active" id="user-list-tab" data-toggle="tab" href="#user-list-desc" role="tab"
            aria-controls="user-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>        
    </div>
</nav>

<div class="tab-content p-3 border rounded bg-white position-relative" id="nav-tabContent">
    <div class="tab-pane fade active show" id="user-list-desc" role="tabpanel" aria-labelledby="user-list-tab">
        <div id="loading" class="loader"></div>
        <div class="alert bg-bluedark text-white">
            <i class="fas fa-table mr-2"></i>Filtro de reporte de paradas
        </div>  
        <div id="toolbar"> 
            <div class="form-group form-inline">
                <input class="form-control mr-3" type="date" id="date-inicio" />
                <input class="form-control" type="date" id="date-fin" />
            </div>                   
        </div>   
        
        <table id="table" 
            class="table-sm"
            data-toggle="bootstrap-table" 
            data-toolbar="#toolbar" 
            data-search="true"
            data-show-export="true"
            >
        </table>
    </div>
</div>
<br><br>
@stop

@section('css')
<style>
    #table tbody {
        font-size: 12px !important;
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/tableExport.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF/jspdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/libs/jsPDF-AutoTable/jspdf.plugin.autotable.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.21.4/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script>    
    $("#loading").hide()
    var $table = $('#table')
    var dateInicio = '';
    var dateFin = '';

    $("#date-inicio").on('change', function() {
        dateInicio = $(this).val()
        $("#date-fin").val('')
        $("#date-fin").attr('min', dateInicio)
        $("#date-fin").focus()
    });

    $("#date-fin").on('change', async function() {
        dateFin = $(this).val()
        var param = {
            dateInicio: dateInicio,
            dateFin: dateFin
        };

        data = await myRequest('/api/paradas-list-search', param)
        console.log(data)

        $table.bootstrapTable('refreshOptions', {
            // url: '{!! $url !!}',
            data: data
        })
    });


    $table.bootstrapTable('destroy')
        .bootstrapTable({            
            data: [],
            // url: '{!! $url !!}',
            pagination: true,
            height: 650,
            locale: 'es_ES',
            columns: [{
                    field: 'tareo_id',
                    title: 'TAREOID',
                    align: 'center',
                    width: 10
                },
                {
                    field: 'group_id',
                    title: 'GROUPID',
                    align: 'center'
                },
                {
                    field: 'labor',
                    title: 'Descripción de Labor',
                    sortable: true,
                },
                // {
                //     field: 'imp_abby',
                //     title: 'I.Abby',
                //     sortable: true,
                // },
                {
                    field: 'implemento',
                    title: 'Implemento',
                    sortable: true,
                },
                {
                    field: 'ubicacion',
                    title: 'Ubicación',
                    sortable: true,                    
                },
                {
                    field: 'cat_parada',
                    title: 'Categoría Parada',
                    sortable: true,           
                },
                {
                    field: 'stop_desc',
                    title: 'Descripción de Parada',                    
                },
                {
                    field: 'observacion',
                    title: 'Observación',                    
                },
                {
                    field: 'hm_inicio',
                    title: 'Hora Inicio',
                },
                {
                    field: 'hm_fin',
                    title: 'Hora Fin',                    
                }
            ]
        });
    
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

    $(function () {
        $('li a').removeClass('active');
        $('.menu-is-opening').removeClass('menu-is-opening');
        $('.menu-open').removeClass('menu-open');
        $('#li-report').addClass('menu-is-opening');
        $('#li-report').addClass('menu-open');
        $('#li-report .title').addClass('active');
        $('#liRep-paradas').addClass('active');
    })
</script>
@stop