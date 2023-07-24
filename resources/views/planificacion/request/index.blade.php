@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>
@section('title', 'Requerimientos')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class="p-0 m-0 sourcesans h4">Requerimientos - Gestión de labores</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')
<br>
<input type="hidden" value="{{ $id_user }}" id="key" />
<input type="hidden" value="{{ $user_id }}" id="keyuser" />
<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="requerimiento-tab" role="tablist">
        <a class="nav-item nav-link active" id="requerimiento-list-tab" data-toggle="tab" href="#requerimiento-list-desc" role="tab" aria-controls="requerimiento-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <a class="nav-item nav-link" id="requerimiento-create-tab" data-toggle="tab" href="#requerimiento-create-desc" role="tab" aria-controls="requerimiento-create-desc" aria-selected="false">[ <i class="fas fa-plus mr-1"></i>Nuevo ]</a>
        <a class="nav-item nav-link" id="requerimiento-plan-tab" data-toggle="tab" href="#requerimiento-plan-desc" role="tab" aria-controls="requerimiento-plan-desc" aria-selected="false">[ <i class="fas fa-calendar-alt mr-1"></i>Detalle ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border rounded" id="nav-tabContent">
    <div class="tab-pane fade active show" id="requerimiento-list-desc" role="tabpanel" aria-labelledby="requerimiento-list-tab">
        <div id="tab-requerimiento-list"></div>
    </div>
    <div class="tab-pane fade" id="requerimiento-create-desc" role="tabpanel" aria-labelledby="requerimiento-create-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-plus mr-2"></i>Nuevo Requerimiento</div>
        <div id="tab-requerimiento-create"></div>
    </div>
    <div class="tab-pane fade" id="requerimiento-plan-desc" role="tabpanel" aria-labelledby="requerimiento-plan-tab">
        <div class="alert bg-bluedark opensans text-white d-flex justify-content-between">
            <span class="mt-1"><i class="fas fa-list-ol mr-2"></i>Registrar labores del día</span>
            <button id='btn-show-modal' class='btn btn-primary btn-sm rounded-1 m-0 px-2 ml-2'>
                [ <i class='fas fa-plus fs-12'></i> Agregar ]
            </button>
        </div>
        <div id="tab-requerimiento-plan"></div>
    </div>
</div>
<br><br>
@stop

@section('js')
<script>
    var loading ="<div class='text-center'><span class='fa-stack fa-lg'>\n\
                    <i class='fa fa-spinner fa-spin fa-stack fa-fw'></i>\n\
                </span>&emsp;Cargando ...</div>"

    $(document).ready(function() {
        listRequest();
        //newRequest();
        hiddenOptionRequest(0);
    });

    function listRequest() {
        $("#tab-requerimiento-list").html(loading);
        var key = $("#key").val();
        var data = {
            key: key
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/requerimiento-list') !!}",
            data: data
        }).done(function(resp) {
            $("#tab-requerimiento-list").html(resp);
        });
    }

    $("#requerimiento-create-tab").on('click', function() {
        hiddenOptionRequest(0);
        newRequest();
    });

    function newRequest() {
        var key = $("#key").val();
        var data = {
            key: key
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/requerimiento-create') !!}",
            data: data
        }).done(function(resp) {
            $("#tab-requerimiento-create").html(resp);
        });
    }

    function hiddenOptionRequest(param) {
        if (param == 1) {
            $("#requerimiento-plan-tab").removeClass('invisible');
            $("#requerimiento-plan-tab").addClass('visible active');
            $("#requerimiento-plan-desc").addClass('active show');

            $("#requerimiento-list-tab").removeClass('active');
            $("#requerimiento-list-desc").removeClass('active show');
        }
        if (param == 0) {
            $("#requerimiento-plan-tab").removeClass('visible active');
            $("#requerimiento-plan-desc").removeClass('active show');
            $("#requerimiento-plan-tab").addClass('invisible');
        }
    }

    $("#requerimiento-list-tab").on("click", function() {
        hiddenOptionRequest(0);
    })

    $("#requerimiento-create-tab").on("click", function() {
        hiddenOptionRequest(0);
    })

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
