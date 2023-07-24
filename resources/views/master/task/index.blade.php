@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>

@section('title', 'Labores')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class="p-0 m-0 sourcesans h4">Labores - Gestión de labores</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="labores-tab" role="tablist">
        <a class="nav-item nav-link active" id="labores-list-tab" data-toggle="tab" href="#labores-list-desc" role="tab"
            aria-controls="labores-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <!-- <a class="nav-item nav-link" id="labores-create-tab" data-toggle="tab" href="#labores-create-desc" role="tab" aria-controls="labores-create-desc" aria-selected="false">[ <i class="fas fa-plus mr-1"></i>Nuevo ]</a>-->
        <a class="nav-item nav-link" id="labores-edit-tab" data-toggle="tab" href="#labores-edit-desc" role="tab" aria-controls="labores-edit-desc" aria-selected="false">[ <i class="fas fa-edit mr-1"></i>Editar ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border rounded" id="nav-tabContent">
    <div class="tab-pane fade active show" id="labores-list-desc" role="tabpanel" aria-labelledby="labores-list-tab">
        <div class="alert bg-bluedark opensans text-white">
        <i class="fas fa-info-circle mr-2"></i>Tabla Labores
        </div>
        <div id="tab-labores-list"></div>
    </div>
    <div class="tab-pane fade" id="labores-edit-desc" role="tabpanel" aria-labelledby="labores-edit-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-pencil-alt mr-2"></i>Editar Labores</div>
        <div id="tab-labores-edit"></div>
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
        listLabors();
        //laboresCreate();
        hiddenTasks(0);
        //hidenTabEditRequirimiento(0);
    });

    function listLabors() {
        $("#tab-labores-list").html(loading);
        $.get("{!! url('api/labores-list') !!}",
            function (data) {
                $("#tab-labores-list").html(data);
            });
    }

    function hiddenTasks(param) {
        if( param == 1 ) {
            $("#labores-edit-tab").removeClass('invisible');
            $("#labores-edit-tab").addClass('visible active');
            $("#labores-edit-desc").addClass('active show');

            $("#labores-list-tab").removeClass('active');
            $("#labores-list-desc").removeClass('active show');
        }
        if( param == 0 ) {
            $("#labores-edit-tab").removeClass('visible active');
            $("#labores-edit-desc").removeClass('active show');
            $("#labores-edit-tab").addClass('invisible');
        }

    }

    $("#labores-list-tab").on("click", function() {
        hiddenTasks(0);
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
