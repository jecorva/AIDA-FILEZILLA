@extends('adminlte::page')
<?php
$_MENU_ITEM = $menu[0]['KeyMenu'];
$_SUBMENU_ITEM = $menu[0]['KeySubmenu'];
?>
@section('title', 'Planificación - Aprobaciones')

@section('content_header')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center">
        <span class="p-0 m-0 sourcesans h4">Aprobación de Solicitudes</span>
        <!--<button class="btn btn-app fs-12">
            <i class="fas fa-plus"></i>
        </button>-->
    </div>
</div>
@stop
@section('content')
<br>
<nav class="w-100 sourcesans fs-14">
    <div class="nav nav-tabs" id="approved-tab" role="tablist">
        <a class="nav-item nav-link active" id="approved-list-tab" data-toggle="tab" href="#approved-list-desc" role="tab" aria-controls="approved-list-desc" aria-selected="true"><i class="fas fa-list mr-1"></i>Lista</a>
        <!-- <a class="nav-item nav-link" id="approved-create-tab" data-toggle="tab" href="#approved-create-desc" role="tab" aria-controls="approved-create-desc" aria-selected="false">[ <i class="fas fa-plus mr-1"></i>Nuevo ]</a> -->
        <a class="nav-item nav-link" id="approved-plan-tab" data-toggle="tab" href="#approved-plan-desc" role="tab" aria-controls="approved-plan-desc" aria-selected="false">[ <i class="fas fa-calendar-alt mr-1"></i>Planificar ]</a>
    </div>
</nav>

<div class="tab-content bg-white p-3 border rounded" id="nav-tabContent">
    <div class="tab-pane fade active show" id="approved-list-desc" role="tabpanel" aria-labelledby="approved-list-tab">
        <div class="alert bg-bluedark opensans text-white">
            <i class="fas fa-info-circle mr-2"></i>Tabla de requerimientos.
        </div>
        <div class="sourcesans" id="tab-approved-search"></div>
        <!-- <div class="sourcesans" id="tab-approved-list"></div> -->
    </div>
    <div class="tab-pane fade" id="approved-create-desc" role="tabpanel" aria-labelledby="approved-create-tab">
        <span class="p-0 m-0 opensans text-label h5">Crear un nuevo approved</span>
        <br>
        <br>
        <div id="tab-approved-create"></div>
    </div>
    <div class="tab-pane fade" id="approved-plan-desc" role="tabpanel" aria-labelledby="approved-plan-tab">
        <div class="p-0 m-0 opensans text-label h5 ml-2">Registrar labores
            <button id='btn-show-modal' class='btn btn-link m-0 p-0 mb-1 sourcesans'>
                [ <i class='fas fa-plus fs-12'></i> Nuevo ]
            </button>
        </div>
        <br>
        <div id="tab-approved-plan"></div>
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
        //listapproved();
        //newapproved();
        searchWidget();
        hiddenOptionApproved(0);
    });

    function searchWidget() {
        $("#tab-approved-search").html(loading);
        $.get("{!! url('api/change-approved-search') !!}",
            function(data) {
                $("#tab-approved-search").html(data);
            });
    }

    function newapproved() {
        $.get("{!! url('api/approved-create') !!}",
            function(data) {
                $("#tab-approved-create").html(data);
            });
    }

    function hiddenOptionApproved(param) {
        if (param == 1) {
            $("#approved-plan-tab").removeClass('invisible');
            $("#approved-plan-tab").addClass('visible active');
            $("#approved-plan-desc").addClass('active show');

            $("#approved-list-tab").removeClass('active');
            $("#approved-list-desc").removeClass('active show');
        }
        if (param == 0) {
            $("#approved-plan-tab").removeClass('visible active');
            $("#approved-plan-desc").removeClass('active show');
            $("#approved-plan-tab").addClass('invisible');
        }
    }

    $("#approved-list-tab").on("click", function() {
        hiddenOptionapproved(0);
    })

    $("#approved-create-tab").on("click", function() {
        hiddenOptionapproved(0);
    });

    async function getComponent(url, data, id) {
        let uri = "{!! url('') !!}";
        $.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: uri + url,
            type: 'POST',
            data: data,
        }).done(function(component) {
            $("#" + id).html(component)
        })
    }

    $(function() {
        $('.select2').select2({
            dropdownCssClass: "opensans fs-14",
            language: {
                noResults: function() {
                    return "Sin resultados";
                },
                searching: function() {
                    return "Buscando..";
                }
            }
        });
    });

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
