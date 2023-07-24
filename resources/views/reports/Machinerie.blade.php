<?php
$url = "http://127.0.0.1:8000/api/paradas-list";

?>
@extends('adminlte::page')

@section('title', 'Reporte Maquinaria')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Reportes - Reporte de maquinarias</span>
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
            <i class="fas fa-table mr-2"></i>Filtro de reporte de maquinarias
        </div>  
        <div id="toolbar"> 
            <div class="form-group form-inline">
                <select class="form-control select2 mr-2" id="SltMachinerie">
                    <option value="0">Seleccione</option>
                    @foreach( $maquinarias as $value )
                    <option value="{{ $value['id'] }}">{{ $value['nombre'] }}</option>
                    @endforeach
                </select>
                <input class="form-control mx-3" type="date" id="date-inicio" />
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
    var id = '';

    $("#SltMachinerie").on('change', async function() {        
        id = $(this).val()
        var param = {
            maquinaria_ID: id,
        }
        data = await myRequest('/api/rptmaquinaria-list-search', param)
        // console.log(data)
        $table.bootstrapTable('refreshOptions', {          
            data: data
        })
    });

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
            dateFin: dateFin,
            maquinaria_ID: id,
        };

        data = await myRequest('/api/rptmaquinaria-list-search', param)
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
            height: 450,
            locale: 'es_ES',
            columns: [{
                    field: 'TAREOID',
                    title: 'TAREOID',
                    align: 'center',
                    width: 10
                },
                {
                    field: 'HMRECOGIDA',
                    title: 'HM-RECOGIDA',
                    align: 'center'
                },
                {
                    field: 'HMINICIOLABOR',
                    title: 'HM-INILABOR',
                    //sortable: true,
                },
                {
                    field: 'ABBY',
                    title: 'ABBY',
                    sortable: true,
                },
                {
                    field: 'MAQUINARIA',
                    title: 'MAQUINARIA',
                    sortable: true,
                },
                {
                    field: 'LOCATION',
                    title: 'UBICACIÓN',
                    sortable: true,                    
                },
                {
                    field: 'HMINICIOTAREO',
                    title: 'HM-INITAREO',
                    //sortable: true,           
                },
                {
                    field: 'HMFINTAREO',
                    title: 'HM-FINTAREO',
                },
                {
                    field: 'HMFINLABOR',
                    title: 'HM-FINLABOR',                    
                },
                {
                    field: 'HMPARKING',
                    title: 'HM-PARKING',
                },
                // {
                //     field: 'hm_fin',
                //     title: 'Hora Fin',                    
                // }
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

    $('.select2').select2({
        width: 'resolve',
        dropdownCssClass: "RobotoC fs-13",
        language: {
            noResults: function() {
                return "Sin resultados";
            },
            searching: function() {
                return "Buscando..";
            }
        }
    });

    $(function () {
        $('li a').removeClass('active');
        $('.menu-is-opening').removeClass('menu-is-opening');
        $('.menu-open').removeClass('menu-open');
        $('#li-report').addClass('menu-is-opening');
        $('#li-report').addClass('menu-open');
        $('#li-report .title').addClass('active');
        $('#liRep-machinerie').addClass('active');
    })
</script>
@stop