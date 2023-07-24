@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>
@section('title', 'Tipo Trabajador')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Tipo - Gestión de tipo trabajador</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop

@section('content')

<br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="ttrabajador-tab" role="tablist">
        <a class="nav-item nav-link active" id="ttrabajador-list-tab" data-toggle="tab" href="#ttrabajador-list-desc" role="tab"
            aria-controls="ttrabajador-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <!-- <a class="nav-item nav-link" id="ttrabajador-create-tab" data-toggle="tab" href="#ttrabajador-create-desc" role="tab"
            aria-controls="ttrabajador-create-desc" aria-selected="false">[ <i class="fas fa-plus mr-1"></i>Nuevo ]</a>
        <a class="nav-item nav-link" id="ttrabajador-edit-tab" data-toggle="tab" href="#ttrabajador-edit-desc" role="tab"
            aria-controls="ttrabajador-edit-desc" aria-selected="false">[ <i class="fas fa-edit mr-1"></i>Editar ]</a> -->
    </div>
</nav>

<div class="tab-content p-3 border rounded bg-white" id="nav-tabContent">
    <div class="tab-pane fade active show" id="ttrabajador-list-desc" role="tabpanel" aria-labelledby="ttrabajador-list-tab">
        <div class="alert bg-bluedark opensans text-white">
        <i class="fas fa-info-circle mr-2"></i>Tabla de tipo trabajador
        </div>
        <div id="tab-ttrabajador-list"></div>
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
        getListaTipoTrabajador();
        //ttrabajadorCreate();
        //hidenTabEditttrabajador(0);
    });

    // Listar tipos
    function getListaTipoTrabajador() {
        $("#tab-ttrabajador-list").html(loading);
        $.get("{!! url('api/ttrabajador-list') !!}",
            function (data) {
                $("#tab-ttrabajador-list").html(data);
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
