@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>
@section('title', 'Trabajador')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Trabajadores - Gestión de trabajadores</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="trabajador-tab" role="tablist">
        <a class="nav-item nav-link active" id="trabajador-list-tab" data-toggle="tab" href="#trabajador-list-desc" role="tab"
            aria-controls="trabajador-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <a class="nav-item nav-link" id="trabajador-create-tab" data-toggle="tab" href="#trabajador-create-desc" role="tab"
            aria-controls="trabajador-create-desc" aria-selected="false">[ <i class="fas fa-plus mr-1"></i>Nuevo ]</a>
        <a class="nav-item nav-link" id="trabajador-edit-tab" data-toggle="tab" href="#trabajador-edit-desc" role="tab"
            aria-controls="trabajador-edit-desc" aria-selected="false">[ <i class="fas fa-edit mr-1"></i>Editar ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border" id="nav-tabContent">
    <div class="tab-pane fade active show" id="trabajador-list-desc" role="tabpanel" aria-labelledby="trabajador-list-tab">
        <div class="alert bg-bluedark opensans text-white">
        <i class="fas fa-info-circle mr-2"></i>Tabla Personas
        </div>
        <div id="tab-trabajador-list"></div>
    </div>
    <div class="tab-pane fade" id="trabajador-create-desc" role="tabpanel" aria-labelledby="trabajador-create-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-plus mr-2"></i>Nueva Persona</div>
        <div id="tab-trabajador-create"></div>
    </div>
    <div class="tab-pane fade" id="trabajador-edit-desc" role="tabpanel" aria-labelledby="trabajador-edit-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-pencil-alt mr-2"></i>Editar Persona</div>
        <div id="tab-trabajador-edit"></div>
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
        listEmployee();
        //newEmployee();
        hiddenOptionEmployee(0);
    });

    // Listar employee
    function listEmployee() {
        $("#tab-trabajador-list").html(loading);
        $.get("{!! url('api/trabajador-list') !!}",
            function (data) {
                $("#tab-trabajador-list").html(data);
            });
    }

    // Ocultar tabs
    function hiddenOptionEmployee(param) {
        if( param == 1 ) {
            $("#trabajador-edit-tab").removeClass('invisible');
            $("#trabajador-edit-tab").addClass('visible active');
            $("#trabajador-edit-desc").addClass('active show');

            $("#trabajador-list-tab").removeClass('active');
            $("#trabajador-list-desc").removeClass('active show');
        }
        if( param == 0 ) {
            $("#trabajador-edit-tab").removeClass('visible active');
            $("#trabajador-edit-desc").removeClass('active show');
            $("#trabajador-edit-tab").addClass('invisible');
        }
    }

    $("#trabajador-list-tab").on("click", function() {
        hiddenOptionEmployee(0);
    })

    $("#trabajador-create-tab").on("click", function() {
        hiddenOptionEmployee(0);
        newEmployee();
    })

    //
    function newEmployee() {
        $.get("{!! url('api/trabajador-create') !!}",
            function (data) {
                $("#tab-trabajador-create").html(data);
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
