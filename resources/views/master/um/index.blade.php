@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>

@section('title', 'Unidad Medidad')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Unidad de Medida - Gestión de unidades de medida</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="unitm-tab" role="tablist">
        <a class="nav-item nav-link active" id="unitm-list-tab" data-toggle="tab" href="#unitm-list-desc" role="tab"
            aria-controls="unitm-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <a class="nav-item nav-link" id="unitm-create-tab" data-toggle="tab" href="#unitm-create-desc" role="tab"
            aria-controls="unitm-create-desc" aria-selected="false">[ <i class="fas fa-plus mr-1"></i>Nuevo ]</a>
        <a class="nav-item nav-link" id="unitm-edit-tab" data-toggle="tab" href="#unitm-edit-desc" role="tab"
            aria-controls="unitm-edit-desc" aria-selected="false">[ <i class="fas fa-pencil-alt mr-1"></i>Editar ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border" id="nav-tabContent">
    <div class="tab-pane fade active show" id="unitm-list-desc" role="tabpanel" aria-labelledby="unitm-list-tab">
        <div class="alert bg-bluedark opensans text-white">
        <i class="fas fa-info-circle mr-2"></i>Tabla Unidad Medida
        </div>
        <div id="tab-unitm-list"></div>
    </div>
    <div class="tab-pane fade" id="unitm-create-desc" role="tabpanel" aria-labelledby="unitm-create-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-plus mr-2"></i>Nueva Unidad Medida</div>
        <div id="tab-unitm-create"></div>
    </div>
    <div class="tab-pane fade" id="unitm-edit-desc" role="tabpanel" aria-labelledby="unitm-edit-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-pencil-alt mr-2"></i>Editar Unidad Medida</div>
        <div class="sourcesansrg" id="tab-unitm-edit"></div>
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
        hiddenOptionUnitm(0);
        listUnitm();
    });

    function listUnitm() {
        $("#tab-unitm-list").html(loading);
        $.get("{!! url('api/unitm-list') !!}",
            function (data) {
                $("#tab-unitm-list").html(data);
            });
    }

    function hiddenOptionUnitm(param) {
        if( param == 1 ) {
            $("#unitm-edit-tab").removeClass('invisible');
            $("#unitm-edit-tab").addClass('visible active');
            $("#unitm-edit-desc").addClass('active show');

            $("#unitm-list-tab").removeClass('active');
            $("#unitm-list-desc").removeClass('active show');
        }
        if( param == 0 ) {
            $("#unitm-edit-tab").removeClass('visible active');
            $("#unitm-edit-desc").removeClass('active show');
            $("#unitm-edit-tab").addClass('invisible');
        }

    }

    $("#unitm-list-tab").on("click", function() {
        hiddenOptionUnitm(0);
    });

    $("#unitm-create-tab").on("click", function() {
        newunitm();
        hiddenOptionUnitm(0);
    });

    function newunitm() {
        $.get("{!! url('api/unitm-new') !!}",
            function (data) {
                $("#tab-unitm-create").html(data);
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
