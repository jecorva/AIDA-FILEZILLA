@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>

@section('title', 'Implementos')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Implementos - Gestión de implementos</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="implemento-tab" role="tablist">
        <a class="nav-item nav-link active" id="implemento-list-tab" data-toggle="tab" href="#implemento-list-desc" role="tab"
            aria-controls="implemento-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <!--<a class="nav-item nav-link" id="implemento-create-tab" data-toggle="tab" href="#implemento-create-desc" role="tab" aria-controls="implemento-create-desc" aria-selected="false">CREAR</a>-->
        <a class="nav-item nav-link" id="implemento-edit-tab" data-toggle="tab" href="#implemento-edit-desc" role="tab" aria-controls="implemento-edit-desc" aria-selected="false">[ <i class="fas fa-edit mr-1"></i>Editar ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border" id="nav-tabContent">
    <div class="tab-pane fade active show" id="implemento-list-desc" role="tabpanel" aria-labelledby="implemento-list-tab">
        <div class="alert bg-bluedark opensans text-white">
        <i class="fas fa-info-circle mr-2"></i>Tabla de Implementos
        </div>
        <div id="tab-implemento-list"></div>
    </div>
    <!--<div class="tab-pane fade" id="implemento-create-desc" role="tabpanel" aria-labelledby="implemento-create-tab">
        <span class=" p-0 m-0 raleway-title h5">Crear un nuevo usuario</span>
        <hr>
        <div id="tab-implemento-create"></div>
    </div>-->
    <div class="tab-pane fade" id="implemento-edit-desc" role="tabpanel" aria-labelledby="implemento-edit-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-pencil-alt mr-2"></i>Editar Implemento</div>
        <div id="tab-implemento-edit"></div>
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
        hiddenOptionImplement(0);
        listImplement();
    });

    function listImplement() {
        $("#tab-implemento-list").html(loading);
        $.get("{!! url('api/implemento-list') !!}",
            function (data) {
                $("#tab-implemento-list").html(data);
            });
    }

    function hiddenOptionImplement(param) {
        if( param == 1 ) {
            $("#implemento-edit-tab").removeClass('invisible');
            $("#implemento-edit-tab").addClass('visible active');
            $("#implemento-edit-desc").addClass('active show');

            $("#implemento-list-tab").removeClass('active');
            $("#implemento-list-desc").removeClass('active show');
        }
        if( param == 0 ) {
            $("#implemento-edit-tab").removeClass('visible active');
            $("#implemento-edit-desc").removeClass('active show');
            $("#implemento-edit-tab").addClass('invisible');
        }

    }

    $("#implemento-list-tab").on("click", function() {
        hiddenOptionImplement(0);
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
