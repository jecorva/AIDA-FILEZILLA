@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>
@section('title', 'Cambios')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class="p-0 m-0 sourcesans h4">Cambios - Gestión de cambios</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')
<br>
<input type="hidden" value="{{ $id_user }}" id="key" />
<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="change-tab" role="tablist">
        <a class="nav-item nav-link active" id="change-list-tab" data-toggle="tab" href="#change-list-desc" role="tab" aria-controls="change-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <a class="nav-item nav-link" id="change-plan-tab" data-toggle="tab" href="#change-plan-desc" role="tab" aria-controls="change-plan-desc" aria-selected="false">[ <i class="fas fa-calendar-alt mr-1"></i>Detalle ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border rounded" id="nav-tabContent">
    <div class="tab-pane fade active show" id="change-list-desc" role="tabpanel" aria-labelledby="change-list-tab">
        <div id="tab-change-list"></div>
    </div>
    <div class="tab-pane fade" id="change-plan-desc" role="tabpanel" aria-labelledby="change-plan-tab">
        <div class="alert bg-bluedark opensans text-white"><i class="fas fa-list-ol mr-2"></i>Requerimiento programado de la semana
        </div>
        <div id="tab-change-plan"></div>
    </div>
</div>
<br><br>
@stop

@section('js')
<script>
    var loading ="<div class='text-center'><span class='fa-stack fa-lg'>\n\
                    <i class='fa fa-spinner fa-spin fa-stack fa-fw'></i>\n\
                </span>&emsp;Cargando ...</div>"

    $(document).ready(function() {
        listRequest();
        hiddenOptionRequest(0);
    });

    function listRequest() {
        $("#tab-change-list").html(loading);
        var key = $("#key").val();
        var data = {
            key: key
        };

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: "{!! url('api/change-list') !!}",
            data: data
        }).done(function(resp) {
            $("#tab-change-list").html(resp);
        });
    }

    function hiddenOptionRequest(param) {
        if (param == 1) {
            $("#change-plan-tab").removeClass('invisible');
            $("#change-plan-tab").addClass('visible active');
            $("#change-plan-desc").addClass('active show');

            $("#change-list-tab").removeClass('active');
            $("#change-list-desc").removeClass('active show');
        }
        if (param == 0) {
            $("#change-plan-tab").removeClass('visible active');
            $("#change-plan-desc").removeClass('active show');
            $("#change-plan-tab").addClass('invisible');
        }
    }

    $("#change-list-tab").on("click", function() {
        hiddenOptionRequest(0);
    })

    $("#change-create-tab").on("click", function() {
        hiddenOptionRequest(0);
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
