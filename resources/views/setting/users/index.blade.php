@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>

@section('title', 'Usuarios')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Usuarios - Gestión de usuarios</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br><br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="user-tab" role="tablist">
        <a class="nav-item nav-link active" id="user-list-tab" data-toggle="tab" href="#user-list-desc" role="tab"
            aria-controls="user-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <a class="nav-item nav-link" id="user-create-tab" data-toggle="tab" href="#user-create-desc" role="tab"
            aria-controls="user-create-desc" aria-selected="false">[ <i class="fas fa-plus mr-1"></i>Nuevo ]</a>
        <a class="nav-item nav-link" id="user-edit-tab" data-toggle="tab" href="#user-edit-desc" role="tab"
            aria-controls="user-edit-desc" aria-selected="false">[ <i class="fas fa-edit mr-1"></i>Editar ]</a>
    </div>
</nav>

<div class="tab-content p-3 border rounded bg-white" id="nav-tabContent">
    <div class="tab-pane fade active show" id="user-list-desc" role="tabpanel" aria-labelledby="user-list-tab">
        <div class="alert bg-bluedark opensans text-white">
        <i class="fas fa-info-circle mr-2"></i>Tabla de Usuarios
        </div>
        <div id="tab-user-list"></div>
    </div>
    <div class="tab-pane fade" id="user-create-desc" role="tabpanel" aria-labelledby="user-create-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-plus mr-2"></i>Nuevo Usuario</div>
        <div id="tab-user-create"></div>
    </div>
    <div class="tab-pane fade" id="user-edit-desc" role="tabpanel" aria-labelledby="user-edit-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-pencil-alt mr-2"></i>Editar Usuario</div>
        <div id="tab-user-edit"></div>
    </div>
</div>
<br><br>
@stop

@section('js')
<script>
    var loading ="<div class='text-center'><span class='fa-stack fa-lg'>\n\
                    <i class='fa fa-spinner fa-spin fa-stack fa-fw'></i>\n\
                </span>&emsp;Cargando ...</div>";

    $(document).ready(function () {
        hiddenTabEditar(0);
        getListarUsuarios();
        // crearUsuario();
    });

    // Obtener lista de usuarios
    function getListarUsuarios() {
        $("#tab-user-list").html(loading);
        $.get("{!! url('api/user-list') !!}",
            function (data) {
                $("#tab-user-list").html(data);
            });
    }

    // Ocultar Tabs editar
    function hiddenTabEditar(param) {
        if (param == 1) {
            $("#user-edit-tab").removeClass('invisible');
            $("#user-edit-tab").addClass('visible active');
            $("#user-edit-desc").addClass('active show');

            $("#user-create-tab").removeClass('active');
            $("#user-list-tab").removeClass('active');

            $("#user-create-desc").removeClass('active show');
            $("#user-list-desc").removeClass('active show');
        }
        if (param == 0) {
            $("#user-edit-tab").removeClass('visible active');
            $("#user-edit-desc").removeClass('active show');
            $("#user-edit-tab").addClass('invisible');
        }

    }

    // Tabs crear usuario
    function crearUsuario() {
        $("#tab-user-create").html(loading);
        $.get("{!! url('api/user-create') !!}",
            function (data) {
                $("#tab-user-create").html(data);
            });
    }

    // Ocultar Tabs editar, cuando eligan nuevo o lista
    $("#user-list-tab").on("click", function () {
        hiddenTabEditar(0);
    })

    $("#user-create-tab").on("click", function () {
        hiddenTabEditar(0);
        crearUsuario();
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
