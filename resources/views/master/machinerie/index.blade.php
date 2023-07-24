@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>

@section('title', 'Maquinarias')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Maquinarias - Gestión de maquinarias</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="maquinaria-tab" role="tablist">
        <a class="nav-item nav-link active" id="maquinaria-list-tab" data-toggle="tab" href="#maquinaria-list-desc" role="tab"
            aria-controls="maquinaria-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <!--<a class="nav-item nav-link" id="maquinaria-create-tab" data-toggle="tab" href="#maquinaria-create-desc" role="tab" aria-controls="maquinaria-create-desc" aria-selected="false">CREAR</a>-->
        <a class="nav-item nav-link" id="maquinaria-edit-tab" data-toggle="tab" href="#maquinaria-edit-desc" role="tab" aria-controls="maquinaria-edit-desc" aria-selected="false">[ <i class="fas fa-edit mr-1"></i>Editar ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border rounded" id="nav-tabContent">
    <div class="tab-pane fade active show" id="maquinaria-list-desc" role="tabpanel" aria-labelledby="maquinaria-list-tab">
        <div class="alert bg-bluedark opensans text-white">
        <i class="fas fa-info-circle mr-2"></i>Tabla Maquinaria
        </div>
        <div id="tab-maquinaria-list"></div>
    </div>
    <!--<div class="tab-pane fade" id="maquinaria-create-desc" role="tabpanel" aria-labelledby="maquinaria-create-tab">
        <span class=" p-0 m-0 raleway-title h5">Crear un nuevo usuario</span>
        <hr>
        <div id="tab-maquinaria-create"></div>
    </div>-->
    <div class="tab-pane fade" id="maquinaria-edit-desc" role="tabpanel" aria-labelledby="maquinaria-edit-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-pencil-alt mr-2"></i>Editar Maquinaria</div>
        <div id="tab-maquinaria-edit"></div>
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
        hiddenOptionmachine(0);
        listMachine();
    });

    function listMachine() {
        $("#tab-maquinaria-list").html(loading);
        $.get("{!! url('api/maquinaria-list') !!}",
            function (data) {
                $("#tab-maquinaria-list").html(data);
            });
    }

    function hiddenOptionmachine(param) {
        if( param == 1 ) {
            $("#maquinaria-edit-tab").removeClass('invisible');
            $("#maquinaria-edit-tab").addClass('visible active');
            $("#maquinaria-edit-desc").addClass('active show');

            $("#maquinaria-list-tab").removeClass('active');
            $("#maquinaria-list-desc").removeClass('active show');
        }
        if( param == 0 ) {
            $("#maquinaria-edit-tab").removeClass('visible active');
            $("#maquinaria-edit-desc").removeClass('active show');
            $("#maquinaria-edit-tab").addClass('invisible');
        }

    }
    $("#maquinaria-list-tab").on("click", function() {
        hiddenOptionmachine(0);
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
