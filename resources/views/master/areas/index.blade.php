@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>

@section('title', 'Areas')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Areas - Gestión de areas</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop

@section('content')

<br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="area-tab" role="tablist">
        <a class="nav-item nav-link active" id="area-list-tab" data-toggle="tab" href="#area-list-desc" role="tab"
            aria-controls="area-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <a class="nav-item nav-link" id="area-edit-tab" data-toggle="tab" href="#area-edit-desc" role="tab"
            aria-controls="area-edit-desc" aria-selected="false">[ <i class="fas fa-pencil-alt mr-1"></i>Editar ]</a>
    </div>
</nav>

<div class="tab-content p-3 border rounded bg-white" id="nav-tabContent">
    <div class="tab-pane fade active show" id="area-list-desc" role="tabpanel" aria-labelledby="area-list-tab">
        <div class="alert bg-bluedark opensans text-white">
            <i class="fas fa-info-circle mr-2"></i>Tabla de Áreas
        </div>
        <div id="tab-area-list"></div>
    </div>
    <div class="tab-pane fade" id="area-edit-desc" role="tabpanel" aria-labelledby="area-edit-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-pencil-alt mr-2"></i>Editar Área</div>
        <div class="sourcesansrg" id="area-tab-edit"></div>
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
        getListaArea();
        //areaCreate();
        hiddenOptionArea(0);
    });

    function getListaArea() {
        $("#tab-area-list").html(loading);
        $.get("{!! url('api/area-list') !!}",
            function (data) {
                $("#tab-area-list").html(data);
            });
    }

    $("#area-list-tab").on("click", function() {
        hiddenOptionArea(0);
    })

    function hiddenOptionArea(param) {
        if( param == 1 ) {
            $("#area-edit-tab").removeClass('invisible');
            $("#area-edit-tab").addClass('visible active');
            $("#area-edit-desc").addClass('active show');

            $("#area-list-tab").removeClass('active');
            $("#area-list-desc").removeClass('active show');
        }
        if( param == 0 ) {
            $("#area-edit-tab").removeClass('visible active');
            $("#area-edit-desc").removeClass('active show');
            $("#area-edit-tab").addClass('invisible');
        }

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
