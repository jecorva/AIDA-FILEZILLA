@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>

@section('title', 'Parking')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Parqueo - Gestión de parqueo</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="parking-tab" role="tablist">
        <a class="nav-item nav-link active" id="parking-list-tab" data-toggle="tab" href="#parking-list-desc" role="tab"
            aria-controls="parking-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <a class="nav-item nav-link" id="parking-create-tab" data-toggle="tab" href="#parking-create-desc" role="tab"
            aria-controls="parking-create-desc" aria-selected="false">[ <i class="fas fa-plus mr-1"></i>Nuevo ]</a>
        <a class="nav-item nav-link" id="parking-edit-tab" data-toggle="tab" href="#parking-edit-desc" role="tab"
            aria-controls="parking-edit-desc" aria-selected="false">[ <i class="fas fa-pencil-alt mr-1"></i>Editar ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border" id="nav-tabContent">
    <div class="tab-pane fade active show" id="parking-list-desc" role="tabpanel" aria-labelledby="parking-list-tab">
        <div class="alert bg-bluedark opensans text-white">
        <i class="fas fa-info-circle mr-2"></i>Tabla de Parqueos
        </div>
        <div id="tab-parking-list"></div>
    </div>
    <div class="tab-pane fade" id="parking-create-desc" role="tabpanel" aria-labelledby="parking-create-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-plus mr-2"></i>Nuevo Parking</div>
        <div id="tab-parking-create"></div>
    </div>
    <div class="tab-pane fade" id="parking-edit-desc" role="tabpanel" aria-labelledby="parking-edit-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-pencil-alt mr-2"></i>Editar Parking</div>
        <div class="sourcesansrg" id="tab-parking-edit"></div>
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
        hiddenOptionParking(0);
        listParking();
    });

    function listParking() {
        $("#tab-parking-list").html(loading);
        $.get("{!! url('api/parking-list') !!}",
            function (data) {
                $("#tab-parking-list").html(data);
            });
    }

    function hiddenOptionParking(param) {
        if( param == 1 ) {
            $("#parking-edit-tab").removeClass('invisible');
            $("#parking-edit-tab").addClass('visible active');
            $("#parking-edit-desc").addClass('active show');

            $("#parking-list-tab").removeClass('active');
            $("#parking-list-desc").removeClass('active show');
        }
        if( param == 0 ) {
            $("#parking-edit-tab").removeClass('visible active');
            $("#parking-edit-desc").removeClass('active show');
            $("#parking-edit-tab").addClass('invisible');
        }

    }

    $("#parking-list-tab").on("click", function() {
        hiddenOptionParking(0);
    });

    $("#parking-create-tab").on("click", function() {
        newParking();
        hiddenOptionParking(0);
    });

    function newParking() {
        $.get("{!! url('api/parking-new') !!}",
            function (data) {
                $("#tab-parking-create").html(data);
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
