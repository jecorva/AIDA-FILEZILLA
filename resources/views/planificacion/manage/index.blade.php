@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>
@section('title', 'manages')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class="p-0 m-0 sourcesans h4">Requerimientos - Online</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br>
<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="manage-tab" role="tablist">
        <a class="nav-item nav-link active" id="manage-list-tab" data-toggle="tab" href="#manage-list-desc" role="tab" aria-controls="manage-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border rounded" id="nav-tabContent">
    <div class="tab-pane fade active show" id="manage-list-desc" role="tabpanel" aria-labelledby="manage-list-tab">
        <div class="alert bg-bluedark opensans text-white">
            <i class="fas fa-info-circle mr-2"></i>Tabla de estado de labores.</span>
            <div class="clock float-right">
                [ <span id="hours" class="hours"></span> :
                <span id="minutes" class="minutes"></span> :
                <span id="seconds" class="seconds"></span> ]
            </div>
        </div>
        <div>

        </div>
        <div id="div-table" class="font-weight-normal">
            <table
                id="table"
                class="table-sm"
                data-page-size="25"
                data-toggle="bootstrap-table"
                data-search="true"
                >
            </table>
        </div>
        <div class="sourcesans" height="35px" id="tab-manage-list"></div>
    </div>
</div>
<br><br>
@stop

@section('css')
<style>
    label {
        font-size: 13px;
    }
    #table thead {
        font-size: 13px !important;
    }

    #table tbody{
        font-size: 14px !important;
    }

    .select2-selection {
        font-size: 14px !important;
    }
    .bg-header-modal {
        background-color: #d6d8db !important;
    }
</style>
@stop

@section('js')
<script>
    var $table = $('#table');

    $(document).ready(function() {
        listManage();
        hiddenOptionManage(0);
    });

    $table.bootstrapTable('destroy')
        .bootstrapTable({
            data: [],
            pagination: true,
            height: 720,
            locale:'es_ES',
            columns: [
                {
                    field: 'nro',
                    title: 'Nro',
                    align: 'center',
                    formatter: indice
                },
                {
                    field: 'semana',
                    title: 'Semana',
                    align: 'center',
                    // sortable: true,
                    //width: 550,
                    //widthUnit: 'px'
                },
                {
                    field: 'dia',
                    title: 'Fecha',
                    align: 'center',
                    //width: 50,
                    //formatter: diaRow
                },
                {
                    field: 'supervisor',
                    title: 'Supervisor',
                    // sortable: true,
                },
                {
                    field: 'ubicacion',
                    title: 'Ubicación',
                    // sortable: true,
                },
                {
                    field: 'operario',
                    title: 'Operario',
                    //formatter: ubicaciones
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
                    field: 'operacion',
                    title: 'Estado',
                    formatter: rowState,
                    align: 'center'
                }
            ]
        });

    function indice(value, row, index) {
        return index + 1;
    }

    function rowState(data) {
        let html = ''
        let color = "color:" + data.colorweb + " !important;";

        html = '<div class="border rounded"><i style="'+ color +'" class="fas fa-circle fs-12"></i> <small style="'+ color +'">'+ data.status_name +'</small></div>'

        return html;
    }

    async function listManage() {
        // $("#tab-manage-list").html(loading);
        $table.bootstrapTable('showLoading')
        var data = {}
        // await getComponent('/api/request-manage-list', data, 'tab-manage-list')
        const response = await httpMyRequest('/api/request-manage-newlist', data)
        console.log(response)
        $table.bootstrapTable('refreshOptions', { data: response })

    }

    function hiddenOptionManage(param) {
        if (param == 1) {
            $("#manage-plan-tab").removeClass('invisible');
            $("#manage-plan-tab").addClass('visible active');
            $("#manage-plan-desc").addClass('active show');

            $("#manage-list-tab").removeClass('active');
            $("#manage-list-desc").removeClass('active show');
        }
        if (param == 0) {
            $("#manage-plan-tab").removeClass('visible active');
            $("#manage-plan-desc").removeClass('active show');
            $("#manage-plan-tab").addClass('invisible');
        }
    }

    $("#manage-list-tab").on("click", function() {
        hiddenOptionManage(0);
    })

    $("#manage-create-tab").on("click", function() {
        hiddenOptionManage(0);
    })

    async function httpMyRequest(url, data) {
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

    async function getComponent(url, data, id) {
        let uri = "{!! url('') !!}";
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: uri + url,
            type: 'POST',
            data: data,
        }).done(function(component) {
            $("#" + id).html(component)
        })
    }

    $(function() {
        $('li a').removeClass('active');
        $('.menu-is-opening').removeClass('menu-is-opening');
        $('.menu-open').removeClass('menu-open');
        $('#liPlan').addClass('menu-is-opening');
        $('#liPlan').addClass('menu-open');
        $('#liPlan .title').addClass('active');
        $('#liPlan-manage').addClass('active');
    })

    var udateTime = function() {
        let currentDate = new Date(),
        hours = currentDate.getHours(),
        minutes = currentDate.getMinutes(),
        seconds = currentDate.getSeconds();
        // document.getElementById('hours').textContent = hours;
        //document.getElementById('minutes').textContent = minutes;
        //document.getElementById('seconds').textContent = seconds;

        $('#hours').text(hours);

        if (minutes < 10) {
            minutes = "0" + minutes
        }

        if (seconds < 10) {
            seconds = "0" + seconds
        }
        $("#minutes").text(minutes)
        $("#seconds").text(seconds)
    };

    udateTime();

    setInterval(udateTime, 1000);

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
