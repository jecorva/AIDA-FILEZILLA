@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>

@section('title', 'Sub - Áreas')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class=" p-0 m-0 sourcesans h4">Sub Áreas</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')

<br>

<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="subarea-tab" role="tablist">
        <a class="nav-item nav-link active" id="subarea-list-tab" data-toggle="tab" href="#subarea-list-desc" role="tab"
            aria-controls="subarea-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <a class="nav-item nav-link" id="subarea-create-tab" data-toggle="tab" href="#subarea-create-desc" role="tab"
            aria-controls="subarea-create-desc" aria-selected="false">[ <i class="fas fa-plus mr-1"></i>Nuevo ]</a>
        <a class="nav-item nav-link" id="subarea-edit-tab" data-toggle="tab" href="#subarea-edit-desc" role="tab"
            aria-controls="subarea-edit-desc" aria-selected="false">[ <i class="fas fa-pencil-alt mr-1"></i>Editar ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border" id="nav-tabContent">
    <div class="tab-pane fade active show" id="subarea-list-desc" role="tabpanel" aria-labelledby="subarea-list-tab">
        <div class="alert bg-bluedark opensans text-white">
        <i class="fas fa-info-circle mr-2"></i>Tabla Sub Áreas
        </div>
        <div id="tab-subarea-list"></div>
    </div>
    <div class="tab-pane fade" id="subarea-create-desc" role="tabpanel" aria-labelledby="subarea-create-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-plus mr-2"></i>Nueva Sub Área</div>
        <div id="tab-subarea-create"></div>
    </div>
    <div class="tab-pane fade" id="subarea-edit-desc" role="tabpanel" aria-labelledby="subarea-edit-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-pencil-alt mr-2"></i>Editar Sub Área</div>
        <div class="sourcesansrg" id="tab-subarea-edit"></div>
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
        hiddenOptionSubarea(0);
        listSubarea();
    });

    async function listSubarea() {
        $("#tab-subarea-list").html(loading);
        let data = { temp: 'temp' }
        await getComponent('/api/component-list-subarea', data, 'tab-subarea-list')
    }

    function hiddenOptionSubarea(param) {
        if( param == 1 ) {
            $("#subarea-edit-tab").removeClass('invisible');
            $("#subarea-edit-tab").addClass('visible active');
            $("#subarea-edit-desc").addClass('active show');

            $("#subarea-list-tab").removeClass('active');
            $("#subarea-list-desc").removeClass('active show');
        }
        if( param == 0 ) {
            $("#subarea-edit-tab").removeClass('visible active');
            $("#subarea-edit-desc").removeClass('active show');
            $("#subarea-edit-tab").addClass('invisible');
        }

    }

    $("#subarea-list-tab").on("click", function() {
        hiddenOptionSubarea(0);
    });

    $("#subarea-create-tab").on("click", function() {
        newSubarea();
        hiddenOptionSubarea(0);
    });

    async function newSubarea() {
        $("#tab-subarea-create").html(loading);
        let data = { temp: 'temp' }
        await getComponent('/api/component-new-subarea', data, 'tab-subarea-create')
    }

    //////////////////////////////////////////////////////////////////////////

    async function getComponent(url, data, id) {
        let uri = "{!! url('') !!}";
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: uri + url,
            type: 'POST',
            data: data,
        }).done(function(component) {
            $("#" + id).html(component)
        })
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
