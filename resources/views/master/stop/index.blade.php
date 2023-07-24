@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>
@section('title', 'Paradas')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Paradas - Gestión de paradas</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="stop-tab" role="tablist">
        <a class="nav-item nav-link active" id="stop-list-tab" data-toggle="tab" href="#stop-list-desc" role="tab"
            aria-controls="stop-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <a class="nav-item nav-link" id="stop-create-tab" data-toggle="tab" href="#stop-create-desc" role="tab"
            aria-controls="stop-create-desc" aria-selected="false">[ <i class="fas fa-plus mr-1"></i>Nuevo ]</a>
        <a class="nav-item nav-link" id="stop-edit-tab" data-toggle="tab" href="#stop-edit-desc" role="tab"
            aria-controls="stop-edit-desc" aria-selected="false">[ <i class="fas fa-pencil-alt mr-1"></i>Editar ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border" id="nav-tabContent">
    <div class="tab-pane fade active show" id="stop-list-desc" role="tabpanel" aria-labelledby="stop-list-tab">
        <div class="alert bg-bluedark opensans text-white">
        <i class="fas fa-info-circle mr-2"></i>Tabla de Paradas
        </div>
        <div id="tab-stop-list"></div>
    </div>
    <div class="tab-pane fade" id="stop-create-desc" role="tabpanel" aria-labelledby="stop-create-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-plus mr-2"></i>Nueva Parada</div>
        <div id="tab-stop-create"></div>
    </div>
    <div class="tab-pane fade" id="stop-edit-desc" role="tabpanel" aria-labelledby="stop-edit-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-pencil-alt mr-2"></i>Editar Paradas</div>
        <div class="sourcesansrg" id="tab-stop-edit"></div>
    </div>
</div>
<br><br>
@stop

@section('js')
<script>
    var loading ="<div class='text-center'><span class='fa-stack fa-lg'>\n\
                    <i class='fa fa-spinner fa-spin fa-stack fa-fw'></i>\n\
                </span>&emsp;Cargando ...</div>";

    $(document).ready(function() {
        hiddenOptionStop(0);
        listStop();
    });

    function listStop() {
        $("#tab-stop-list").html(loading);
        $.get("{!! url('api/stop-list') !!}",
            function (data) {
                $("#tab-stop-list").html(data);
            });
    }

    function hiddenOptionStop(param) {
        if( param == 1 ) {
            $("#stop-edit-tab").removeClass('invisible');
            $("#stop-edit-tab").addClass('visible active');
            $("#stop-edit-desc").addClass('active show');

            $("#stop-list-tab").removeClass('active');
            $("#stop-list-desc").removeClass('active show');
        }
        if( param == 0 ) {
            $("#stop-edit-tab").removeClass('visible active');
            $("#stop-edit-desc").removeClass('active show');
            $("#stop-edit-tab").addClass('invisible');
        }

    }

    $("#stop-list-tab").on("click", function() {
        hiddenOptionStop(0);
    });

    $("#stop-create-tab").on("click", function() {
        newStop();
        hiddenOptionStop(0);
    });

    function newStop() {
        $.get('../api/stop-new',
            function (data) {
                $("#tab-stop-create").html(data);
            });
    }

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
