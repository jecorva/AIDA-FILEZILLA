<?php

?>
@extends('adminlte::page')

@section('title', 'Fiscalizador')

@section('content_header')
<!-- <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Fiscalizadores</span>
    </div>
</div> -->
@stop
@section('content')
<br>
<div class="alert bg-bluedark text-white">
    <h5 class="text-input mb-0">
        <i class="fas fa-tag mr-2 text-white-50"></i>
        Control de tareo de labores
    </h5>
</div>


<div id="toolbar">
    <div class="form-group">
    <label for="semana">Semanas</label>
    <select class="select2" id="semana">
        <option value="">--- Seleccionar ---</option>
        @foreach( $semanas as $semana )
        <option value="{{ $semana->nro_semana }}">Semana Nº {{ $semana->nro_semana }}</option>
        @endforeach
    </select>
  </div>
</div>
<div class="bg-white border-light p-3 rounded position-relative">
    <div id="loading" class="loader"></div>
    <table id="table" 
        class="table-sm"
        data-toggle="bootstrap-table" 
        data-toolbar="#toolbar" 
        data-search="true"
        >
        <thead style="font-size: 12px; 
                    background-color: #eeeeee;
                    font-family: 'Varela Round', sans-serif !important;"></thead>
    </table>
</div>
<br>
<form id="nuevo-fiscal-form">
    <div class="alert bg-bluedark text-white d-flex justify-content-between">
        <h5 class="text-input mb-0 pt-1">    
            <i class="fas fa-tag mr-2 text-white-50"></i>
            Control de fiscalizadores
        </h5>
        <div class="text-center">
            <button type="submit" id="save-nuevo" class="btn btn-sm bg-gradient-success"><i class="fas fa-save"></i></button>
            <button type="button" id="btn-nuevo-fiscal" class="btn btn-sm bg-gradient-secondary"><i class="fas fa-plus"></i></button>
        </div>
        
    </div>

    <div id="div-nuevo" class="bg-white border border-secondary p-3 rounded mb-3">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label for="apel-pat" class="mb-0 text-muted">DNI</label>
                    <input type="text" class="form-control text-capitalize" id="apel-pat" placeholder="D.N.I." autocomplete="off" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="apel-pat" class="mb-0 text-muted">Apellido Paterno</label>
                    <input type="text" class="form-control text-capitalize" id="apel-pat" placeholder="Apellido Paterno" autocomplete="off" required>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="apel-mat" class="mb-0 text-muted">Apellido Materno</label>
                    <input type="text" class="form-control text-capitalize" id="apel-mat" placeholder="Apellido Materno" autocomplete="off" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="nombres" class="mb-0 text-muted">Nombre(s)</label>
                    <input type="text" class="form-control text-capitalize" id="nombres" placeholder="Nombres" autocomplete="off" required>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="content-table" class="bg-white border-light p-3 mb-3 rounded position-relative">
    <div id="loading-2" class="loader"></div>
    <table id="table-fiscal"
        class="table-sm"
        data-toggle="bootstrap-table" 
        data-toolbar="#toolbar2" 
        data-search="true"
        >
        <thead style="font-size: 12px; 
                    background-color: #eeeeee;
                    font-family: 'Varela Round', sans-serif !important;"></thead>
    </table>
</div>

<br>
@stop
@section('css')
<style>
    #table, #table-fiscal tbody {
        font-size: 13px !important;        
    }
    .form-group label {
        font-family: 'Varela Round' !important;        
        font-size: 12px !important; 
        font-weight: bold !important;
    }
</style>
@stop

@section('js')
<script>
    var $table = $('#table')
    var $tableFiscal = $("#table-fiscal")
    $("#loading-2").hide();
    $("#div-nuevo").hide();
    $("#save-nuevo").hide()

    $("#semana").on('change', async function() {
        let sem = $(this).val()
        let user_id = '{!! $user_id !!}'
        var param = {
            sem: sem,
            user_id: user_id
        }
        console.log(param)

        var response = await myRequest('/api/planificacion-list-tareos', param)        
        $table.bootstrapTable('refreshOptions', {
                search: true,
                data: response
            })

        console.log(response)
    })

    $("#btn-nuevo-fiscal").on('click', function() {
        $(this).prop('disabled', true)        
        $("#div-nuevo").show()        
        $("#save-nuevo").show()
    });

    $("#save-nuevo").on('submit', function(e) {
        e.preventDefault()
        $("#div-nuevo").hide()
        $("#save-nuevo").hide()
        $("#btn-nuevo-fiscal").prop('disabled', false)
    })

    $table
        .bootstrapTable('destroy')
            .bootstrapTable({            
                data: [],                
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
                        field: 'nam_labor',
                        title: 'LABOR',
                        align: 'center'
                    },
                    {
                        field: 'nam_ubicacion',
                        title: 'UBICACIÓN',
                        //sortable: true,
                    },
                    {
                        field: 'nam_implement',
                        title: 'IMPLEMENTO',
                        //sortable: true,
                    },
                    {
                        field: 'nam_machinerie',
                        title: 'MAQUINARIA',
                        //sortable: true,
                    },
                    {
                        field: 'operador',
                        title: 'OPERARIO',
                        //sortable: true,                    
                    },
                    {
                        field: 'HMINICIOTAREO',
                        title: 'FISCAL',
                        //sortable: true,           
                    },
                    {
                        field: 'state_id',
                        title: 'ESTADO',
                    },
                    // {
                    //     field: 'HMFINLABOR',
                    //     title: 'HM-FINLABOR',                    
                    // },
                    // {
                    //     field: 'HMPARKING',
                    //     title: 'HM-PARKING',
                    // },
                    // {
                    //     field: 'hm_fin',
                    //     title: 'Hora Fin',                    
                    // }
                ]
            });
    
    $tableFiscal
        .bootstrapTable('destroy')
            .bootstrapTable({            
                data: [],                
                pagination: true,
                height: 450,
                locale: 'es_ES',
                columns: [{
                        field: '',
                        title: 'NRO',
                        align: 'center',
                        width: 10
                    },
                    {
                        field: '',
                        title: 'DNI',
                        alignv: 'middle'
                    },
                    {
                        field: 'HMINICIOLABOR',
                        title: 'APELLIDOS Y NOMBRES',
                        //sortable: true,
                    },
                    {
                        field: 'ABBY',
                        title: 'FECHA INGRESO',
                        //sortable: true,
                    },
                    {
                        field: 'MAQUINARIA',
                        title: 'ESTADO',
                        //sortable: true,
                    },
                    {
                        field: 'LOCATION',
                        title: 'OPERACIÓN',
                        //sortable: true,                    
                    }                   
                ]
            });

    ////////////////////////////////////////////////////////
    $("#loading").hide();

    async function requestAPI(url, data) {
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
        $('#li-supervisor').addClass('menu-is-opening');
        $('#li-supervisor').addClass('menu-open');
        $('#li-supervisor .title').addClass('active');
        $('#liSup-fiscalizador').addClass('active');
    })
</script>
@stop
